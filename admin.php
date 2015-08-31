<?php
header('Content-Type: text/html; charset=ISO-8859-2');
include_once 'includes/config.php';
include_once 'includes/classes.php';

$mdb2->query("SET NAMES 'latin2'");

$javascripts = array();
$bodyonload  = array();
$ajax        = array();

$breadcrumb =& new Breadcrumb();

//nyelvi file betoltese
$locale->initArea('admin');

$is_admin = 0;
//megnezzuk, hogy az admin menukhoz hozzafer-e
if (isset($_SESSION['user_groups'])) {
	$usergroup_array = explode(" ", $_SESSION['user_groups']);
	if (in_array($_SESSION['site_sys_prefgroup'], $usergroup_array)) {
		$is_admin = 1;
	}
}

//website statisztika
/*if (isModule('stat', 'admin')) {
	include_once 'phpOpenTracker.php';

	// log access
	phpOpenTracker::log(
		array(
			'document' => 'admin'
			)
		);
}*/

if (isset($_SESSION['user_id'])) {
	// Breadcrumb hozzáadása
	$breadcrumb->add($locale->get('admin', 'center'), 'admin.php');

	$javascripts[] = "jquery";
	
	//ha konkret oldalra ugrunk
	if (isset($_GET['p']) && $_GET['p'] != '' && $_GET['p'] != 'index') {
		$_GET['p'] = str_replace('../', '',$_GET['p']);

		if (is_file("admin/".$_GET['p'].".php")) {
			include_once("admin/".$_GET['p'].".php");
			$tpl->assign('page', $acttpl);
		} else {
			//ha nem letezik a kert oldal, akkor hibauzenet
			$acttpl = "error";
			$tpl->assign('page',     $acttpl);
			$tpl->assign('errormsg', $locale->get('admin', 'page_not_found'));
		}
	}

	//lekerdezzuk az admin oldalhoz tartozo menupontokat
	$query = "(
		SELECT m.menu_id AS mid, m.menu_name AS mname, mo.file_name AS mfile, m.sortorder AS sortord
		FROM iShark_Menus m
		LEFT JOIN iShark_Modules mo ON mo.module_id = m.module_id
		LEFT JOIN iShark_Rights r ON r.module_id = mo.module_id
		LEFT JOIN iShark_Groups_Rights gr ON gr.right_id = r.right_id
		LEFT JOIN iShark_Groups g ON g.group_id = gr.group_id
		LEFT JOIN iShark_Groups_Users gu ON gu.group_id = g.group_id
		WHERE mo.is_active = 1 AND m.type = 'admin' AND gu.user_id = '".$_SESSION['user_id']."'
		GROUP BY m.menu_id
		ORDER BY sortorder)
	UNION
		(SELECT m.menu_id AS mid, m.menu_name AS mname, mo.file_name AS mfile, m.sortorder AS sortord
		FROM iShark_Menus m, iShark_Modules mo, iShark_Rights2 r, iShark_Groups g, iShark_Groups_Users gu
		WHERE m.module_id = mo.module_id AND mo.is_active = '1' AND m.type = 'admin'AND r.module_id = mo.module_id
		  AND r.group_id = g.group_id AND g.is_deleted <> '1' AND g.group_id = gu.group_id AND gu.user_id = ".$_SESSION["user_id"]."
	    GROUP BY m.menu_id
	    ORDER BY m.sortorder)
	";

	$result = $mdb2->query($query);

	$title_module = array (
		'title' => $locale->get('admin', 'center')
	);	

	$tpl->assign('admin_menu',  $result->fetchAll());
	$tpl->assign('javascripts', $javascripts);
	$tpl->assign('bodyonload',  $bodyonload);
	$tpl->assign('ajax',        $ajax);
	$tpl->assign('title_admin', $title_module);
	$tpl->assign('breadcrumb',  $breadcrumb->getArray());

	$locale->assignToSmarty('locale');

	$tpl->display("admin/index.tpl");
}
//ha nincs engedelyezve a felhasznalo oldali regisztracio, akkor itt kell a belepest elintezni
else if ($_SESSION['site_userlogin'] != 1 || empty($_SESSION['user_id'])) {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$uri = parse_url($_SERVER['REQUEST_URI']);

	$form =& new HTML_QuickForm('frm_login', 'post', 'admin.php');

	$form->setRequiredNote('&nbsp;');

	$form->addElement('header', 'adminlogin', $locale->get('admin', 'login_header'));
	if (!$form->isSubmitted() && @$_SERVER['REQUEST_URI'] != '' && substr($uri['path'], -9) == "admin.php" && !empty($uri['query']) && substr($uri['query'], 0, 2) == "p=") {
	    $form->addElement('hidden', 'referer', $uri['query']);
	} else {
	    $form->addElement('hidden', 'referer', 'admin.php');
	}

	//nev
	$form->addElement('text', 'name', $locale->get('admin', 'login_name'));

	//jelszo
	$form->addElement('password', 'pass', $locale->get('admin', 'login_password'));

	$form->addElement('submit', 'submit', $locale->get('admin', 'login_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('admin', 'login_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('name', $locale->get('admin', 'login_error_required_name'), 'required');
	$form->addRule('pass', $locale->get('admin', 'login_error_required_pass'), 'required');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$name    = $form->getSubmitValue('name');
		$pass    = md5($form->getSubmitValue('pass'));
		$referer = $form->getSubmitValue('referer');
		if (!substr($referer, 0, 2) == "p=") {
		    $referer = "";
		}

		$query = "
			SELECT u.user_id AS uid, u.name AS uname, u.user_name AS realname, u.activate AS uact, u.lost_password AS lpass
			FROM iShark_Users u
			WHERE u.name = '".$name."' AND u.password = '$pass' AND u.is_active = 1 AND u.is_deleted = 0
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow())
			{
				$_SESSION['user_id']  = $row['uid'];
				$_SESSION['username'] = $row['uname'];
				$_SESSION['realname'] = $row['realname'];
                
				//ha nem ures az activate es a lost_password mezo, akkor toroljuk a tartalmukat
				if ($row['uact'] != "" || $row['lpass'] != "") {
					$query = "
						UPDATE iShark_Users
						SET activate = '', lost_password = ''
						WHERE user_id = '".$_SESSION['user_id']."'
					";
					$result = $mdb2->query($query);
				}

				//lekerdezzuk a user csoportjait
				$query = "
					SELECT group_id
					FROM iShark_Groups_Users
					WHERE user_id = '".$_SESSION['user_id']."'
				";
				$result = $mdb2->query($query);
				$groups = "";
				while ($row = $result->fetchRow())
				{
					$groups .= $row['group_id']." ";
				}
				$_SESSION['user_groups'] = trim($groups);
			}
			$form->freeze();

		    header('Location: admin.php?'.$referer);
			exit;
		} else {
			$form->setElementError('name', $locale->get('admin', 'login_bad_login'));
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('javascripts', $javascripts);
	$tpl->assign('ajax',        $ajax);
	$tpl->assign('form',        $renderer->toArray());

    $locale->assignToSmarty('locale');
	$tpl->display("admin/login.tpl");
}
else {
	$acttpl = "error";
	$tpl->assign('page',     $acttpl);
	$tpl->assign('errormsg', $locale->get('admin', 'permission_denied'));
	$locale->assignToSmarty('locale');
	$tpl->display("admin/index.tpl");
	return;
}

?>

