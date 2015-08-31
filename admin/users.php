<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "users";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('add', 'mod', 'del', 'act', 'lst', 'search', 'jatekoslista');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$fieldselect3 = "";
$fieldselect4 = "";
$fieldselect5 = "";
$fieldselect6 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY u.name ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY u.user_name ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = "ORDER BY u.email ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   = "ORDER BY is_deleted ";
			$fieldselect4 = "selected";
			break;
		case 5:
			$fieldorder   = "ORDER BY is_public ";
			$fieldselect5 = "selected";
			break;
		case 6:
			$fieldorder   = "ORDER BY is_public_mail ";
			$fieldselect6 = "selected";
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
	$fieldorder = "ORDER BY u.name";
	$order      = "ASC";
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('fieldselect3', $fieldselect3);
$tpl->assign('fieldselect4', $fieldselect4);
$tpl->assign('fieldselect5', $fieldselect5);
$tpl->assign('fieldselect6', $fieldselect6);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
$tpl->assign('page_id',      $page_id);
$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
//rendezes vege

//megnezzuk, hogy az azonosito alapjan milyen felhasznalot akar lekerdezni
$admin_user = 0;
if (isset($_REQUEST['uid']) && is_numeric($_REQUEST['uid'])) {
	$uid = intval($_REQUEST['uid']);

	$query = "
		SELECT * 
		FROM iShark_Groups_Users 
		WHERE user_id = $uid AND group_id = ".$_SESSION['site_sys_prefgroup']."
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$admin_user = 1;
	}
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 1, $module_name) || ($admin_user == 1 && $is_admin == 0)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

$group_get = "";

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($act == "add" || $act == "mod") {
	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm('frm_users', 'post', 'admin.php?p='.$module_name);

	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('form_required_note'));

	//form-hoz elemek hozzadasa
	$form->addElement('header',                $locale->get('form_header'));
	$form->addElement('hidden',   'field',     $field);
	$form->addElement('hidden',   'ord',       $ord);
	$form->addElement('hidden',   'page_id',   $page_id);
	$form->addElement('text',     'name',      $locale->get('field_name'));
	$form->addElement('text',     'user_name', $locale->get('field_username'));
	$form->addElement('text',     'email',     $locale->get('field_email'));
	$form->addElement('password', 'pass1',     $locale->getBySmarty('field_pass1'));
	$form->addElement('password', 'pass2',     $locale->get('field_pass2'));
	$public = array();
	$public[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$public[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
	$form->addGroup($public, 'public', $locale->get('field_public'));
	$publicmail = array();
	$publicmail[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$publicmail[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'), '0');
	$form->addGroup($publicmail, 'publicmail', $locale->get('field_publicmail'));

	//lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a user-t
	$query = "
		SELECT g.group_id AS gid, g.group_name AS gname 
		FROM iShark_Groups g 
		WHERE g.is_deleted = 0 
	";
	if ($is_admin == 0) {
		$query .= "
			AND g.group_id != ".$_SESSION['site_sys_prefgroup']."
		";
	}
	$query .= "
		ORDER BY g.group_name
	";
	$result =& $mdb2->query($query);
	$select =& $form->addElement('select', 'group', $locale->get('field_groups'), $result->fetchAll('', $rekey = true));
	$select->setSize(5);
	$select->setMultiple(true);

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form->addRule('name',       $locale->get('error_no_name'),       'required');
	$form->addRule('user_name',  $locale->get('error_no_username'),   'required');
	$form->addRule('email',      $locale->get('error_no_mail'),       'required');
	$form->addRule('email',      $locale->get('error_email'),         'email');
	$form->addRule('public',     $locale->get('error_no_public'),     'required');
	$form->addRule('publicmail', $locale->get('error_no_publicmail'), 'required');
	//csak akkor kotelezo a csoportok hasznalata, ha a felhasznalok nem regisztralhatnak a fooldalon
	if (empty($_SESSION['site_userlogin'])) {
		$form->addGroupRule('group', $locale->get('error_no_group'), 'required');
	}

	//a jelszot csak akkor ellenorizzuk, ha
	//- uj felhasznalot adunk hozza
	//- modositjuk a felhasznalot, es uj jelszot adunk neki
	if ($_REQUEST['act'] == "add" || ($_REQUEST['act'] == "mod" && !empty($_POST['pass1']))) {
		$form->addRule('pass1', $locale->get('error_no_pass1'), 'required');
		$form->addRule('pass2', $locale->get('error_no_pass2'), 'required');
		$form->addRule('pass1', $locale->get('error_minpass'),  'minlength', $_SESSION['site_minpass']);
		$form->addRule(array('pass1', 'pass2'), $locale->get('error_cmppass'), 'compare');
	}

	/**
	 * ha uj felhasznalot adunk hozza
	 */
	if ($act == "add") {
		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act', 'add');

		//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
		$form->setDefaults(array(
			'active'     => 1,
			'public'     => 0,
			'publicmail' => 0
			)
		);

		//szabalyok hozzadasa - csak hozzaadasnal
		$form->addFormRule('check_adduser');

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name           = $form->getSubmitValue('name');
			$user_name      = $form->getSubmitValue('user_name');
			$email          = $form->getSubmitValue('email');
			$password       = md5($form->getSubmitValue('pass1'));
			$is_public      = intval($form->getSubmitValue('public'));
			$is_public_mail = intval($form->getSubmitValue('publicmail'));
			$group          = $form->getSubmitValue('group');

			//letrehozzuk a felhasznalot
			$user_id = $mdb2->extended->getBeforeID('iShark_Users', 'user_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Users 
				(user_id, name, user_name, email, password, register_date, is_deleted, is_public, is_public_mail, is_active) 
				VALUES 
				($user_id, '".$name."', '".$user_name."', '".$email."', '$password', NOW(), '0', '$is_public', '$is_public_mail', '1')
			";
			$mdb2->exec($query);
			$last_user_id = $mdb2->extended->getAfterID($user_id, 'iShark_Users', 'user_id');

			//hozzaadjuk a kivalasztott csoport(ok)hoz - ha vannak
			if (!empty($group)) {
				foreach ($group as $group_id) {
					$query = "
						INSERT INTO iShark_Groups_Users 
						(user_id, group_id) 
						VALUES 
						($last_user_id, $group_id)
					";
					$mdb2->exec($query);
				}
			}

			//loggolas
			logger($act);

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //felhasznalo hozzaadas vege

	/**
	 * ha modositunk egy felhasznalot
	 */
	if ($act == "mod") {
		$uid = intval($_REQUEST['uid']);

		$vids = "
			SELECT * 
			FROM iShark_User_Videos
			WHERE user_id = '".$uid."'
		";
		$resvids = $mdb2->query($vids);
		$vids_html = "";
		foreach ($resvids->fetchAll() as $key =>$value) {
			$vids_html .= '
				<a href="files/videos/'.$value["videofile"].'" target="_blank">'.$value["videofile"].' ('.$value["datum"].')</a><br />
			';
		}
		
		$form->addElement('static', 'videos', 'Feltöltött videók', $vids_html);
		
		//form-hoz elemek hozzaadasa - csak modositasnal
		$form->addElement('hidden', 'act', 'mod');
		$form->addElement('hidden', 'uid', $uid);
		if (isset($_REQUEST["s"]) && $_REQUEST["s"] == "1"){
			$form->addElement('hidden', 's_name',      $_REQUEST['name']);
			$form->addElement('hidden', 's_user_name', $_REQUEST['user_name']);
			$form->addElement('hidden', 's_email',     $_REQUEST['email']);
			$form->addElement('hidden', 'rel',         $_REQUEST['rel']);
			$form->addElement('hidden', 'search',      '1');
			if (!empty($_REQUEST["group"])){
				foreach ($_REQUEST["group"] as $gid){
					$form->addElement('hidden', 's_group[]', $gid);
				}
			}
		}
		$deleted = array();
		$deleted[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
		$deleted[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
		$form->addGroup($deleted, 'deleted', $locale->get('field_deleted'));

		//szabaly hozzaadasa - csak modositasnal
		$form->addRule('deleted', $locale->get('form_error_deleted'), 'required');

		//lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Users 
			WHERE user_id = $uid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				//beallitjuk az alapertelmezett ertekeket - csak modositasnal
				$form->setDefaults(array(
					'name'       => $row['name'],
					'user_name'	 => $row['user_name'],
					'email'      => $row['email'],
					'deleted'    => $row['is_deleted'],
					'public'     => $row['is_public'],
					'publicmail' => $row['is_public_mail']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}

		//lekerdezzuk a csoportuser tablat, es beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Groups_Users 
			WHERE user_id = $uid
		";
		$result = $mdb2->query($query);
		$group_array = "";
		while ($row = $result->fetchRow()) {
			$group_array .= $row['group_id'].", ";
		}
		$select->setSelected($group_array);

		//szabalyok hozzadasa - csak modositasnal
		$form->addFormRule('check_moduser');

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$name           = $form->getSubmitValue('name');
			$user_name      = $form->getSubmitValue('user_name');
			$email          = $form->getSubmitValue('email');
			$is_public      = intval($form->getSubmitValue('public'));
			$is_public_mail = intval($form->getSubmitValue('publicmail'));
			$is_deleted     = intval($form->getSubmitValue('deleted'));
			$group          = $form->getSubmitValue('group');

			$query = "
				UPDATE iShark_Users 
				SET name           = '".$name."', 
					user_name      = '".$user_name."', 
					email          = '".$email."', 
					is_deleted     = '$is_deleted', 
					is_public      = '$is_public', 
					is_public_mail = '$is_public_mail' 
			";
			//ha nem ures a jelszo mezo
			if (!empty($_POST['pass1'])) {
				$password       = md5($form->getSubmitValue('pass1'));
				$query .= "
					, password = '$password'
				";
			}
			$query .= "
				WHERE user_id = $uid
			";
			$mdb2->exec($query);

			//kitoroljuk a jelenlegi csoporttagsagait a felhasznalonak
			$query = "
				DELETE FROM iShark_Groups_Users 
				WHERE user_id = $uid
			";
			$mdb2->exec($query);

			//hozzaadjuk a kivalasztott csoport(ok)hoz
			if (!empty($group)) {
				foreach ($group as $group_id) {
					$query = "
						INSERT INTO iShark_Groups_Users 
						(user_id, group_id) 
						VALUES 
						($uid, $group_id)
					";
					$mdb2->exec($query);
				}
			}

			//loggolas
			logger($act, '', '');

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			if(isset($_REQUEST['search']) && $_REQUEST['search'] == '1') {
				$s_name      = $form->getSubmitValue('s_name');
				$s_user_name = $form->getSubmitValue('s_user_name');
				$s_email     = $form->getSubmitValue('s_email');
				$rel         = $form->getSubmitValue('rel');
				$s_group     = $form->getSubmitValue('s_group');

				if (!empty($s_group)) {
					foreach ($s_group as $gid) {
						$group_get .= "&group[]=".$gid;
					}
				} else {
					$group_get = "";
				}
				header('Location: admin.php?p='.$module_name.'&act=search&search=1&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&name='.$s_name.'&user_name='.$s_user_name.'&rel='.$rel.'&email='.$s_email.''.$group_get);
				exit;
			} else {
				header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
				exit;
			}
		}
	} //modositas vege

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$act], '#');

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	$tpl->assign('lang_title', $titles[$act]);

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha torlunk egy felhasznalot
 */
if ($act == "del") {
	$uid = intval($_REQUEST['uid']);

	//megnezzuk, ha az is_change mezo nem 1 es az is_active mezo se legyen 1, akkor torolheto fizikailag is
	$query = "
		SELECT user_id 
		FROM iShark_Users 
		WHERE user_id = $uid AND is_active = 0 AND is_change != 1 
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() != 0) {
		$query2 = "
			DELETE FROM iShark_Users 
			WHERE user_id = $uid
		";
	} else {
		$query2 = "
			UPDATE iShark_Users 
			SET is_deleted = 1, 
				is_active  = 0 
			WHERE user_id = $uid
		";
	}
	$mdb2->exec($query2);

	//loggolas
	logger($act, '', '');

	if (isset($_REQUEST["s"]) && $_REQUEST["s"] == "1") {
		if (!empty($_REQUEST["group"])) {
			foreach ($_REQUEST["group"] as $gid) {
				$group_get .= "&group[]=".$gid;
			}
		} else {
			$group_get = "";
		}
		header('Location: admin.php?p='.$module_name.'&act=search&search=1&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&name='.$_REQUEST['name'].'&user_name='.$_REQUEST['user_name'].'&rel='.$_REQUEST['rel'].'&email='.$_REQUEST['email'].''.$group_get);
		exit;
	} else {
		header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
		exit;
	}
} //torles vege

/**
 * ha aktivalunk egy felhasznalot
 */
if ($act == "act") {
	include_once $include_dir.'/function.check.php';
	$uid = intval($_REQUEST['uid']);

	check_active('iShark_Users', 'user_id', $uid);

	//beallitjuk az is_change mezot, innentol kezdve nem lehet fizikailag torolni a usert
	$query = "
		UPDATE iShark_Users 
		SET is_change = '1' 
		WHERE user_id = $uid
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	if (isset($_REQUEST["s"]) && $_REQUEST["s"] == "1") {
		if (!empty($_REQUEST["group"])) {
			foreach ($_REQUEST["group"] as $gid) {
				$group_get .= "&group[]=".$gid;
			}
		} else {
			$group_get = "";
		}
		header('Location: admin.php?p='.$module_name.'&act=search&search=1&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&name='.$_REQUEST['name'].'&user_name='.$_REQUEST['user_name'].'&rel='.$_REQUEST['rel'].'&email='.$_REQUEST['email'].''.$group_get);
		exit;
	}else{
		header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
		exit;
	}
}

/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
	//lekerdezzuk az adatbazisbol a felhasznalok listajat
	$query = "
		SELECT u.user_id AS uid, u.name AS uname, u.user_name AS username, u.email AS umail, is_active AS uact, is_deleted AS udel, 
			is_public AS upub, is_public_mail AS upubmail 
		FROM iShark_Users u
	";
	if ($is_admin == 0) {
		$query .= "
			LEFT JOIN iShark_Groups_Users gu ON gu.user_id = u.user_id AND gu.group_id = '".$_SESSION['site_sys_prefgroup']."' 
			WHERE gu.group_id IS NULL 
			$fieldorder $order
		";
	} else {
		$query .= "
			WHERE is_deleted = '0'
			$fieldorder $order
		";
	}

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//felhasznalo csoportjainak listaja
	foreach ($paged_data['data'] as $key => $adat) {
		$grouplist = "";
		$query = "
			SELECT g.group_name AS gname 
			FROM iShark_Groups g, iShark_Groups_Users gu 
			WHERE g.is_deleted = 0 AND g.is_active = 1 AND gu.group_id = g.group_id AND gu.user_id = ".$adat['uid']."
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			$grouplist .= $row['gname']."<br />";
		}
		$adat['grouplist'] = $grouplist;
		$data[] = $adat;
	}

	//uj hozzaadasa - design miatt
	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('userlist',   $data);
	$tpl->assign('add_new',    $add_new);
	$tpl->assign('back_arrow', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'users_list';
}


if ($act == "jatekoslista") {
	
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm('frm_users', 'post', 'admin.php?p='.$module_name);

	$form->addElement('header', $locale->get('form_header'));
	
	$form->addElement('hidden', 'act', 'jatekoslista');
	
	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('form_required_note'));
	
	$year_array = array('' => '-');
	//$year_array[] = "";
	for ($i = 2013; $i <= date('Y')+2; $i++) {
		$year_array[$i] = $i;
	}
	$month_array = array('' => '-');
	//$month_array[] = "";
	for ($j = 1; $j <= 12; $j++) {
		if ($j < 10) { $jj = "0".$j; } else {$jj = $j;}
		$month_array[$jj] = $jj;
	}
	$day_array = array('' => '-');
	//$day_array[] = "";
	for ($k = 1; $k <= 31; $k++) {
		if ($k < 10) { $kk = "0".$k; } else {$kk = $k;}
		$day_array[$kk] = $kk;
	}

	$bdate['year']  = &HTML_QuickForm::createElement('select', 'start_datumyear', '', $year_array, array('id' => 'start_datum_year', 'class' => 'w2', 'size' => 1));
	$bdate['month'] = &HTML_QuickForm::createElement('select', 'start_datummonth', '/', $month_array, array('id' => 'start_datum_month', 'class' => 'w3', 'size' => 1));			
	$bdate['day']   = &HTML_QuickForm::createElement('select', 'start_datumday', '/', $day_array, array('id' => 'start_datum_day', 'class' => 'w1', 'size' => 1));
	$form->addGroup($bdate, 'start_datum', 'Dátum', ' ');
	
	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));
		
		$startdate = $form->getSubmitValue('start_datum');
		$sd = $startdate['start_datumyear']."-".$startdate['start_datummonth']."-".$startdate['start_datumday'];
		
		$db = mysql_connect('mysql.e-tiger.net', '01393_vizertek', '4v0z3rt3k');
		if (!$db) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db('01393_vizertek');
		mysql_query("SET NAMES = latin2");
		
		$gamers = array();
		
		$q_fb = "
			SELECT u.fb_email AS email, u.fb_name AS uname, r.datum
			FROM results AS r
			LEFT JOIN users AS u ON u.fb_id = r.fb_id
			WHERE DATE_FORMAT(r.datum, '%Y-%m-%d') = '".$sd."'
			GROUP BY r.fb_id
		";
		$res_fb = mysql_query($q_fb);
		$counter = 0;
		while($row_fb = mysql_fetch_array($res_fb)) {
			$gamers[$counter]["email"] = $row_fb["email"];
			$gamers[$counter]["uname"] = iconv("utf-8", "iso-8859-2", $row_fb["uname"]);
			$gamers[$counter]["datum"] = $row_fb["datum"];
			$counter++;
		}
		
		$q = "
			SELECT u.email, u.user_name AS uname, gr.datum
			FROM iShark_Game_Results AS gr
			LEFT JOIN iShark_Users AS u ON u.user_id = gr.user_id
			WHERE DATE_FORMAT(gr.datum, '%Y-%m-%d') = '".$sd."'
			GROUP BY gr.user_id
		";
		$res = $mdb2->query($q);
		$gamers_w = $res->fetchAll();
		
		$gamers = array_merge($gamers, $gamers_w);
		
		$tpl->assign('userlist', $gamers);
		
		$acttpl = "users_g_list";
		
	} else {
	
		$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	
		$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
		$form->accept($renderer);

		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		$tpl->assign('form',       $renderer->toArray());
		$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
		$tpl->assign('lang_title', $titles[$act]);

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('dynamic_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'dynamic_form';
	}
}

/**
 * ha keresünk
 */  
if ($act == "search") {
	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	//require_once $include_dir.'/function.check.php';

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm('frm_search', 'post', 'admin.php?p='.$module_name.'&act=search');

	if (isset($_REQUEST["search"]) && $_REQUEST["search"] == "1") {
		if(!isset($_REQUEST['group']) || !is_array($_REQUEST['group'])) {
			$_REQUEST['group']=array();
		}

		$form->setDefaults(array(
			'name'       => $_REQUEST['name'],
			'user_name'	 => $_REQUEST['user_name'],
			'email'      => $_REQUEST['email'],
			'group'      => $_REQUEST['group']
			)
		);

		if(!empty($_REQUEST["name"])) {
			$where[] = "u.name like '%".$_REQUEST["name"]."%'";
			$tpl->assign('name', $_REQUEST["name"]);
		}

		if(!empty($_REQUEST["user_name"])) {
			$where[] = "u.user_name like '%".$_REQUEST["user_name"]."%'";
			$tpl->assign('user_name', $_REQUEST["user_name"]);
		}

		if(!empty($_REQUEST["email"])) {
			$where[] = "u.email like '%".$_REQUEST["email"]."%'";
			$tpl->assign('email', $_REQUEST["email"]);
		}

		if(!empty($_REQUEST["group"])) {
			$join = "
				left join iShark_Groups_Users as gu on u.user_id=gu.user_id
				left join iShark_Groups as g on gu.group_id=g.group_id
			";
			foreach ($_REQUEST["group"] as $gid) {
				$where_group[] = "gu.group_id='".$gid."'";
				$group_get    .= "&amp;group[]=".$gid;
			}
			$where[] = implode(" or ", $where_group);

			$tpl->assign('group',     $_REQUEST["group"]);
			$tpl->assign('group_get', $group_get);
		} else {
			$join      = "";
			$group_get = "";
		}

		if (!empty($where)) {
			$where = implode(" ".$_REQUEST["rel"]." ", $where);

			//lekerdezzuk az adatbazisbol a felhasznalok listajat
			$query = "
				SELECT u.user_id AS uid, u.name AS uname, u.user_name AS username, u.email AS umail, u.is_active AS uact, u.is_deleted AS udel, 
					u.is_public AS upub, u.is_public_mail AS upubmail 
				FROM iShark_Users u 
				$join
				WHERE $where AND u.is_deleted = '0'
				$fieldorder $order
			";
			$result = $mdb2->query($query);

			if($result->numRows()>0) {
				//lapozo
				require_once 'Pager/Pager.php';
				$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

				//felhasznalo csoportjainak listaja
				foreach ($paged_data['data'] as $key => $adat) {
					$grouplist = "";
					$query = "
						SELECT g.group_name AS gname 
						FROM iShark_Groups g, iShark_Groups_Users gu 
						WHERE g.is_deleted = 0 AND g.is_active = 1 AND gu.group_id = g.group_id AND gu.user_id = ".$adat['uid']."
					";
					$result = $mdb2->query($query);
					while ($row = $result->fetchRow())
					{
						$grouplist .= $row['gname']."<br />";
					}
					$adat['grouplist'] = $grouplist;
					$data[] = $adat;
				}

				$tpl->assign('page_data',  $paged_data['data']);
				$tpl->assign('page_list',  $paged_data['links']);
				$tpl->assign('userlist',   $data);
			}
		}
		$tpl->assign('rel', $_REQUEST["rel"]);
		$tpl->assign('search', 1);
	}

	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote('&nbsp;');

	//form-hoz elemek hozzadasa
	$form->addElement('header',              $locale->get('form_header'));
	$form->addElement('hidden', 'search',    '1');
	$form->addElement('text',   'name',      $locale->get('field_name'));
	$form->addElement('text',   'user_name', $locale->get('field_username'));
	$form->addElement('text',   'email',     $locale->get('field_email'));

	//lekerdezzuk, hogy a csoportokat
	$query = "
		SELECT g.group_id AS gid, g.group_name AS gname 
		FROM iShark_Groups g 
		WHERE g.is_deleted = 0 
		ORDER BY g.group_name
	";
	$result = $mdb2->query($query);
	$select =& $form->addElement('select', 'group', $locale->get('field_groups'), $result->fetchAll('', $rekey = true));
	$select->setSize(5);
	$select->setMultiple(true);

	$select =& $form->addElement('select', 'rel', $locale->get('field_relation'), array('and' => "ÉS", 'or' => "VAGY"));

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php');

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	$acttpl = 'users_search';
}
?>
