<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "groups";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);
$tpl->assign('title_module', $title_module);
$tpl->assign('self',         $module_name);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('add', 'mod', 'del');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY g.group_name ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY g.is_deleted ";
			$fieldselect2 = "selected";
			break;
	}

	switch ($ord) {
		case "asc":
			$order      = "ASC";
			$ordselect1 = "selected";
			break;
		case "desc":
			$order      = "DESC";
			$ordselect2 = "selected";
			break;
	}
} else {
	$field      = "";
	$ord        = "";
	$fieldorder = "ORDER BY g.group_name";
	$order      = "ASC";
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
$tpl->assign('page_id',      $page_id);
//rendezes vege

//megnezzuk, hogy az azonosito alapjan milyen csoportot akar lekerdezni
$admin_group = 0;
if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
	$gid = intval($_REQUEST['gid']);

	$query = "
		SELECT * 
		FROM iShark_Groups 
		WHERE group_id = $gid
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();

		if ($row['group_id'] == $_SESSION['site_sys_prefgroup']) {
			$admin_group = 1;
		}
	}
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 1, 'groups') || ($admin_group == 1 && $is_admin == 0)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

$groupnums = 0;
//lekerdezzuk, hogy mennyi nem torolt csoport van jelenleg
$query = "
	SELECT * 
	FROM iShark_Groups 
	WHERE is_deleted = 0
";
$result = $mdb2->query($query);
$groupnums = $result->numRows();

if ($act == "add" || $act == "mod") {
	//js beszurasa
	$javascripts[] = "javascripts";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_groups', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header',            $locale->get('form_header'));
	$form->addElement('hidden', 'field',   $field);
	$form->addElement('hidden', 'ord',     $ord);
	$form->addElement('hidden', 'page_id', $page_id);
	$form->addElement('text',   'name',    $locale->get('field_name'));

	//userek listaja
	$query = "
		SELECT u.user_id AS uid, u.user_name AS uname 
		FROM iShark_Users u 
		WHERE u.is_deleted = 0 
		ORDER BY u.user_name
	";
	$result =& $mdb2->query($query);
	$select =& $form->addElement('select', 'srcList', $locale->get('field_users'), $result->fetchAll('', $rekey = true), 'id="srcList"');
	$select->setSize(10);
	$select->setMultiple(true);

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('name', $locale->get('error_no_name'), 'required');

	/**
	 * ha uj csoportot adunk hozza
	 */
	if ($act == "add") {
		if (isset($_SESSION['site_groupnum']) && $groupnums >= $_SESSION['site_groupnum'] && $_SESSION['site_groupnum'] != 0) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('error_groupnum'));
			return;
		} else {
			//breadcrumb
			$breadcrumb->add($titles[$act], '#');

			$form->addElement('hidden', 'act', 'add');

			$form->addFormRule('check_addgroup');
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name = $form->getSubmitValue('name');

				$query = "
					INSERT INTO iShark_Groups 
					(group_name, is_deleted, is_active) 
					VALUES 
					('".$name."', '0', '1')
				";
				$mdb2->exec($query);
				
				//utolsokent felvitt csoport azonositoja
				$last_grp_id = $mdb2->lastInsertID('iShark_Groups');

				//loggolas
				logger($act, '', '');

				//felvisszuk a csoporthoz a termekeket
				if (isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
					foreach ($_POST['destList0'] as $key => $value) {
						//beszurjuk a csoportba a termeket
						$query = "
							INSERT INTO iShark_Groups_Users 
							(user_id, group_id) 
							VALUES 
							('$value', $last_grp_id)
						";
						$mdb2->exec($query);
					}
				}

				$form->freeze();

				header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
				exit;
			}
		}
	} //csoport hozzadas vege
	
	/**
	 * ha modositunk egy csoportot
	 */
	if ($act == "mod") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		$gid = intval($_REQUEST['gid']);

		$form->addElement('hidden', 'act', 'mod');
		$form->addElement('hidden', 'gid', $gid);
		$deleted = array();
		$deleted[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
		$deleted[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'), '0');
		$form->addGroup($deleted,  'deleted', $locale->get('field_deleted'));

		$form->addRule('deleted', $locale->get('error_deleted'), 'required');

		//lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Groups 
			WHERE group_id = $gid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$form->setDefaults(array(
					'name'    => $row['group_name'],
					'deleted' => $row['is_deleted']
					)
				);
				
				//lekerdezzuk a mar rogzitett termekeket
				$query = "
					SELECT u.user_id AS uid, u.user_name AS uname 
					FROM iShark_Groups_Users gu, iShark_Users u 
					WHERE group_id = $gid AND u.user_id = gu.user_id
					";
				$result =& $mdb2->query($query);
				$user_array = array();
				while ($row = $result->fetchRow()) {
					$user_array[$row['uid']] = $row['uname'];
				}
				$tpl->assign('destList', $user_array);
			}
		} else {
			header('Location: admin.php?p='.$module_name);
			exit;
		}

		$form->addFormRule('check_modgroup');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$group_name = $form->getSubmitValue('name');
			$is_deleted = intval($form->getSubmitValue('deleted'));

			$query = "
				UPDATE iShark_Groups 
				SET group_name = '".$group_name."', 
					is_deleted = '$is_deleted' 
				WHERE group_id = $gid
			";
			$mdb2->exec($query);

			//kitoroljuk az eddig ehhez a csoporthoz tartozo termekeket
			$query = "
				DELETE FROM iShark_Groups_Users 
				WHERE group_id = $gid
			";
			$mdb2->exec($query);

			//felvisszuk a csoporthoz a termekeket
			if (isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
				foreach ($_POST['destList0'] as $key => $value) {
					//beszurjuk a csoportba a termeket
					$query = "
						INSERT INTO iShark_Groups_Users 
						(group_id, user_id) 
						VALUES 
						($gid, '$value')
					";
					$mdb2->exec($query);
				}
			}

			//loggolas
			logger($act, '', '');

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //csoport modositas vege

	$form->addElement('submit', 'submit',  $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',   $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	//valtozok atadasa a template-nek
	$tpl->assign('lang_title', $titles[$act]);
	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'groups_add';
}
/**
 * ha torlunk egy csoportot
 */
if ($act == "del") {
	$gid = intval($_GET['gid']);

	$query = "
		UPDATE iShark_Groups 
		SET is_active = '0', is_deleted = '1' 
		WHERE group_id = $gid
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
	//lekerdezzuk az adatbazisbol a csoportok listajat
	$query = "
		SELECT g.group_id AS gid, g.group_name AS gname, g.is_deleted AS gdel 
		FROM iShark_Groups g 
		
	";
	//ha nincs benne a kiemelt csoportban, akkor nem lathatja a kiemelt csoport-ot
	if ($is_admin == 0) {
		$query .= "
			WHERE g.group_id != '".$_SESSION['site_sys_prefgroup']."' 
		";
	}
	$query .= "
		".$fieldorder." ".$order."
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	foreach ($paged_data['data'] as $key => $adat) {
		$userlist = "";
		$query = "
			SELECT u.name AS uname, u.user_name AS username 
			FROM iShark_Users u, iShark_Groups_Users gu 
			WHERE u.is_deleted = 0 AND gu.group_id = '".$adat['gid']."' AND gu.user_id = u.user_id
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			$userlist .= $row['uname']." ".$row['username']."<br />";
		}
		$adat['userlist'] = $userlist;
		$data[] = $adat;
	}

	if ($_SESSION['site_groupnum'] > $groupnums || $_SESSION['site_groupnum'] == 0) {
		$add_new = array (
			array(
				'link'  => 'admin.php?p='.$module_name.'&amp;act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
				'title' => $locale->get('title_add'),
				'pic'   => 'add.jpg'
			)
		);
		$tpl->assign('add_new', $add_new);
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',   $data);
	$tpl->assign('page_list',   $paged_data['links']);
	$tpl->assign('back_arrow',  'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'groups_list';
}

?>
