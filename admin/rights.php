<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

//modul neve
$module_name = "rights";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('main_title')
);

// fulek definialasa
$tabs = array(
    'rights' => $locale->get('tabs_title')
);

$acts = array(
    'rights' => array('add', 'mod', 'del')
);

//aktualis ful beallitasa
$page = 'rights';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 0, $module_name) || 
    ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_permission_denied'));
    return;
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$fieldselect3 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY r.right_name ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY m.module_name ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = "ORDER BY c.title ";
			$fieldselect3 = "selected";
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
	$fieldorder = "ORDER BY r.right_name";
	$order      = "ASC";
}
//rendezes vege

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('fieldselect3', $fieldselect3);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
$tpl->assign('page_id',      $page_id);
$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm('frm_rights', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('form_required_note'));

	//form-hoz elemek hozzadasa
	$form->addElement('header', 'rights',  $locale->get('field_header'));
	$form->addElement('text',   'name',    $locale->get('field_name'));
	$form->addElement('hidden', 'field',   $field);
	$form->addElement('hidden', 'ord',     $ord);
	$form->addElement('hidden', 'page_id', $page_id);

	//kirakunk egy ures option-t az elejere
	$empty_array = array('' => '');

	//lekerdezzuk, hogy milyen modulokat lehet hozzaadni - fooldal
	$query = "
		SELECT DISTINCT m.module_id AS mid, m.module_name AS mname 
		FROM iShark_Modules m 
		LEFT JOIN iShark_Functions f ON f.module_id = m.module_id 
		WHERE f.module_id IS NOT NULL AND m.is_active = 1 AND m.type = 'index' 
		ORDER BY m.module_name
	";
	$result = $mdb2->query($query);
	$select =& $form->addElement('select', 'modules', $locale->get('field_modules_index'), $empty_array + $result->fetchAll('', $rekey = true), 'id="modules", onChange="funclist(\'modules\')"');

	//lekerdezzuk, hogy milyen modulokat lehet hozzaadni - adminoldal
	$query = "
		SELECT DISTINCT m.module_id AS mid, m.module_name AS mname 
		FROM iShark_Modules m 
		LEFT JOIN iShark_Functions f ON f.module_id = m.module_id 
		WHERE f.module_id IS NOT NULL AND m.is_active = 1 AND m.type = 'admin' 
		ORDER BY m.module_name
	";
	$result = $mdb2->query($query);
	$select =& $form->addElement('select', 'modulesadm', $locale->get('field_modules_admin'), $empty_array + $result->fetchAll('', $rekey = true), 'id="modulesadm", onChange="funclist(\'modulesadm\')"');

	//lekerdezzuk, hogy milyen tartalmakat lehet hozzaadni (a hirek kivetelevel)
	/*$query = "
		SELECT c.content_id AS cid, SUBSTRING(c.title, 1, 50) AS ctitle 
		FROM iShark_Contents c 
		WHERE c.is_active = 1 AND type = 1 AND (c.timer_start = '0000-00-00 00:00:00' OR c.timer_start < NOW())
		ORDER BY c.title
	";*/
	//a tipus at lett allitva valami nem letezore, hogy ne jelenjen meg tartalom, mivel meg tartalomhoz nem lehet jogot adni
	$query = "
		SELECT c.content_id AS cid, SUBSTRING(c.title, 1, 50) AS ctitle 
		FROM iShark_Contents c 
		WHERE c.is_active = 1 AND type = 5 AND (c.timer_start = '0000-00-00 00:00:00' OR c.timer_start < NOW())
		ORDER BY c.title
	";
	$result = $mdb2->query($query);
	$select =& $form->addElement('select', 'contents', $locale->get('field_contents'), $empty_array + $result->fetchAll('', $rekey = true));

	//lekerdezzuk, hogy milyen csoportokat adhatunk hozza
	$query = "
		SELECT g.group_id AS gid, g.group_name AS gname 
		FROM iShark_Groups g 
		WHERE g.is_deleted = 0 
		ORDER BY g.group_name
	";
	$result = $mdb2->query($query);
	$select_groups =& $form->addElement('select', 'group', $locale->get('field_groups'), $result->fetchAll('', $rekey = true));
	$select_groups->setSize(5);
	$select_groups->setMultiple(true);

	$form->addElement('static', 'functiontext', $locale->get('field_functions'));
	$form->addElement('submit', 'submit',       $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',        $locale->get('form_reset'),  'class="reset"');

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form->addRule('name', $locale->get('error_name'), 'required');
	$form->addGroupRule('group', $locale->get('error_group'), 'required');
	if ($form->isSubmitted() && (!isset($_REQUEST['functions']) || !is_array($_REQUEST['functions']))) {
		//$form->addGroupRule('functions', $strAdminRightsErrorFunctions, 'required', '', '1');
		$form->setElementError('functiontext', $locale->get('error_functions'));
	}
	//ha nem valasztott se modult, se tartalmat
	if ($form->getSubmitValue('modules') == "" && $form->getSubmitValue('modulesadm') == "" && $form->getSubmitValue('contents') == "") {
		$form->addRule('modules',    $locale->get('error_modules1'), 'required');
		$form->addRule('modulesadm', $locale->get('error_modules1'), 'required');
		$form->addRule('contents',   $locale->get('error_modules1'), 'required');
	}
	//ha valasztott modult es tartalmat is, akkor hiba (csak az egyiket lehet valasztani)
	$mod    = $form->getSubmitValue('modules');
	$modadm = $form->getSubmitValue('modulesadm');
	$con    = $form->getSubmitValue('contents');
	$tomb = array();
	if ($mod != 0) { $tomb[] = $mod; }
	if ($modadm != 0) { $tomb[] = $modadm; }
	if ($con != 0) { $tomb[] = $con; }
	if (count($tomb) > 1) {
		$form->setElementError('modules',    $locale->get('error_modules2'));
		$form->setElementError('modulesadm', $locale->get('error_modules2'));
		$form->setElementError('contents',   $locale->get('error_modules2'));
	}

	/**
	 * ha uj jogosultsagot adunk hozza
	 */
	if ($sub_act == "add") {
		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);

		//szabalyok hozzadasa - csak hozzaadasnal
		$form->addFormRule('check_addrights');

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name      = $form->getSubmitValue('name');
			$modulendx = intval($form->getSubmitValue('modules'));
			$moduleadm = intval($form->getSubmitValue('modulesadm'));
			$content   = intval($form->getSubmitValue('contents'));
			$group     = $form->getSubmitValue('group');
			$functions = $form->getSubmitValue('functions');

			//megnezzuk, hogy fooldali vagy adminoldali modult akarunk-e felvinni
			if ($modulendx != 0 || $moduleadm != 0) {
				if ($modulendx > 0) {
					$module = $modulendx;
				}
				if ($moduleadm > 0) {
					$module = $moduleadm;
				}
			} else {
				$module = 0;
			}

			//beszurjuk az adatbazisba az uj jogosultsagot
			$right_id = $mdb2->extended->getBeforeID('iShark_Rights', 'right_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Rights 
				(right_id, right_name, module_id, content_id) 
				VALUES 
				($right_id, '".$name."', $module, $content)
			";
			$mdb2->exec($query);
			$last_right_id = $mdb2->extended->getAfterID($right_id, 'iShark_Rights', 'right_id');

			//hozzaadjuk a kivalasztott csoport(ok)hoz
			foreach ($group as $group_id) {
				$query = "
					INSERT INTO iShark_Groups_Rights 
					(group_id, right_id) 
					VALUES 
					($group_id, $last_right_id)
				";
				$mdb2->exec($query);
			}

			//hozzaadjuk a kivalasztott funkcio(ka)t
			foreach ($functions as $function_id => $id) {
				$query = "
					INSERT INTO iShark_Rights_Functions 
					(right_id, function_id) 
					VALUES 
					($last_right_id, $id)
				";
				$mdb2->exec($query);
			}

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //jogosultsag hozzaadas vege
	
	/**
	 * ha modositunk egy jogosultsagot
	 */
	if ($sub_act == "mod") {
		$rid = intval($_REQUEST['rid']);

		//form-hoz elemek hozzaadasa - csak modositasnal
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);
		$form->addElement('hidden', 'rid',     $rid);

		//lekerdezzuk a jogok tablat, es beallitjuk az alapertelmezett ertekeket
		$query = "
			SELECT r.right_id AS rid, r.right_name AS rname, r.module_id AS mid, r.content_id AS cid 
			FROM iShark_Rights r 
			LEFT JOIN iShark_Modules m ON m.module_id = r.module_id 
			LEFT JOIN iShark_Contents c ON c.content_id = r.content_id
			WHERE r.right_id = $rid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$module_id = $row['mid'];
				//beallitjuk az alapertelmezett ertekeket - csak modositasnal
				$form->setDefaults(array(
					'name'       => $row['rname'],
					'modules'    => $module_id,
					'modulesadm' => $row['mid'],
					'contents'   => $row['cid']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}

		//lekerdezzuk a csoportok tablat, es beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Groups_Rights 
			WHERE right_id = $rid
		";
		$result = $mdb2->query($query);
		$select_groups->setSelected($result->fetchCol());

		//lekerdezzuk a funkciok tablat, es beallitjuk alapertelmezettnek
		$query = "
			SELECT f.function_id AS fid, f.function_name AS fname, f.function_alias AS falias, rf.function_id AS rfid 
			FROM iShark_Functions f 
			LEFT JOIN iShark_Rights_Functions rf ON rf.function_id = f.function_id 
			WHERE f.module_id = $module_id 
			ORDER BY f.function_id
		";
		$result = $mdb2->query($query);
		$i = 0;
		$functionchk = array();
		while ($row = $result->fetchRow()) {
			$functionchk[$i]['fid']    = $row['fid'];
			$functionchk[$i]['fname']  = $row['fname'];
			$functionchk[$i]['falias'] = $row['falias'];
			$functionchk[$i]['rfid']   = $row['rfid'];
			$i++;
		}
		$tpl->assign('functionchk', $functionchk);

		//szabalyok hozzadasa - csak modositasnal
		$form->addFormRule('check_modrights');

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name      = $form->getSubmitValue('name');
			$modulendx = intval($form->getSubmitValue('modules'));
			$moduleadm = intval($form->getSubmitValue('modulesadm'));
			$content   = intval($form->getSubmitValue('contents'));
			$group     = $form->getSubmitValue('group');
			$functions = $form->getSubmitValue('functions');

			//megnezzuk, hogy fooldali vagy adminoldali modult akarunk-e felvinni
			if ($modulendx != 0 || $moduleadm != 0) {
				if ($modulendx > 0) {
					$module = $modulendx;
				}
				if ($moduleadm > 0) {
					$module = $moduleadm;
				}
			} else {
				$module = 0;
			}

			$query = "
				UPDATE iShark_Rights 
				SET right_name = '".$name."', module_id = $module, content_id = $content
				WHERE right_id = $rid
			";
			$mdb2->exec($query);

			//kitoroljuk a kapcsolodo tablakbol az adatokat
			$query = "
				DELETE FROM iShark_Groups_Rights 
				WHERE right_id = $rid
			";
			$mdb2->exec($query);

			$query = "
				DELETE FROM iShark_Rights_Functions 
				WHERE right_id = $rid
			";
			$mdb2->exec($query);

			//hozzaadjuk a kivalasztott csoport(ok)hoz
			foreach ($group as $group_id) {
				$query = "
					INSERT INTO iShark_Groups_Rights 
					(group_id, right_id) 
					VALUES 
					($group_id, $rid)
				";
				$mdb2->exec($query);
			}

			//hozzaadjuk a kivalasztott funkcio(ka)t
			foreach ($functions as $function_id => $id) {
				$query = "
					INSERT INTO iShark_Rights_Functions 
					(right_id, function_id) 
					VALUES 
					($rid, $id)
				";
				$mdb2->exec($query);
			}

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	//ajax-hoz szukseges infok
	$ajax['link']   = "ajax.php?client=all&stub=all";
	$ajax['script'] = "
		function funclist(name) {
			var id = document.getElementById(name).value;
			HTML_AJAX.replace('target','ajax.php?act=rights&id='+id);
		}
	";

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&act='.$page);
	$tpl->assign('form',       $renderer->toArray());

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'rights';
}

/**
 * ha torlunk egy jogosultsagot
 */
if ($sub_act == "del") {
	if (isset($_GET['rid']) && is_numeric($_GET['rid'])) {
		$rid = intval($_GET['rid']);

		$query = "
			DELETE FROM iShark_Rights 
			WHERE right_id = $rid
		";
		$mdb2->exec($query);

		$query = "
			DELETE FROM iShark_Groups_Rights 
			WHERE right_id = $rid
		";
		$mdb2->exec($query);

		$query = "
			DELETE FROM iShark_Rights_Functions 
			WHERE right_id = $rid
		";
		$mdb2->exec($query);

		//loggolas
		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
		exit;
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('error_not_exists'));
		return;
	}
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	//lekerdezzuk a jogosultsag tabla tartalmat
	$query = "
		SELECT r.right_id AS rid, r.right_name AS rname, m.module_name AS mname, c.title AS ctitle, m.type AS mtype 
		FROM iShark_Rights r 
		LEFT JOIN iShark_Modules m ON r.module_id = m.module_id 
		LEFT JOIN iShark_Contents c ON r.content_id = c.content_id 
		$fieldorder $order
	";

	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//uj jogo hozzadasa
	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
			'title' => $locale->get('title_new'),
			'pic'   => 'add.jpg'
		)
	);

	//breadcrumb
	$breadcrumb->add($locale->get('title_list'), 'admin.php?p='.$module_name);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',   $paged_data['data']);
	$tpl->assign('page_list',   $paged_data['links']);
	$tpl->assign('add_new',     $add_new);
	$tpl->assign('back_arrow',  'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'rights_list';
}

?>
