<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//nyelvi file betoltese
include_once $lang_dir.'/modules/modules/'.$_SESSION['site_lang'].'.php';

//design-hoz a cim betoltese
$title_module = array(
	'title' => $strAdminModulesTitle
);
$tpl->assign('title_module', $title_module);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('act', 'lst', 'ins');

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
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY m.module_name ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY m.type ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = "ORDER BY m.file_name ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   = "ORDER BY m.description ";
			$fieldselect4 = "selected";
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
	$fieldorder = "ORDER BY module_name";
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
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
$tpl->assign('page_id',      $page_id);
//rendezes vege

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 1, 'modules')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $strErrorPermission);
	return;
}

/**
 * ha aktivaljuk valamelyik modult
 */
if ($act == "act") {
	include_once $include_dir.'/function.check.php';
	$mid = intval($_GET['m_id']);

	check_active('iShark_Modules', 'module_id', $mid);

	//loggolas
	logger($act, NULL, '');

	header('Location: admin.php?p=modules&field='.$field.'&ord='.$ord);
	exit;
} //aktivalas vege

/**
 * ha telepitjuk valamelyik modult
 */
if ($act == "ins") {
	include_once $include_dir.'/function.check.php';
	$mid = intval($_GET['m_id']);

	check_install($mid, 'modules');

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p=modules&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //telepites vege

if ($act == "lst") {
	include_once $include_dir.'/modules.inc';

	//lekerdezzuk az adatbazisbol, hogy melyik szerepel mar benne es melyik nem
	if (isset($modules) && is_array($modules)) {
		foreach ($modules as $sorszam => $modtomb) {
			//ha fizikailag is letezik a file, csak akkor tesszük betölthetõvé
			if (($modtomb['type'] == "admin" && file_exists('admin/'.$modtomb['file'].$modtomb['ext'])) || ($modtomb['type'] == "index" && file_exists('modules/'.$modtomb['file'].$modtomb['ext']))) {
				$query = "
					SELECT module_name 
					FROM iShark_Modules 
					WHERE file_name = '".$modtomb['file']."' AND type = '".$modtomb['type']."'
				";
				$result = $mdb2->query($query);
				//ha meg nem szerepel, akkor beszurjuk a tablaba, de inaktivva tesszuk
				if ($result->numRows() == 0) {
					$query_modules = "
						INSERT INTO iShark_Modules 
						(module_name, file_name, file_ext, description, is_active, type) 
						VALUES 
						('".$modtomb['name']."', '".$modtomb['file']."', '".$modtomb['ext']."', '".$modtomb['desc']."', '".$modtomb['dact']."', '".$modtomb['type']."')
					";
					$mdb2->exec($query_modules);

					//feltoltjuk a jogosultsaghoz tartozo funkcio tablat
					if (is_array($modtomb['acts']) && !empty($modtomb['acts'])) {
						$last_module_id = $mdb2->lastInsertId('iShark_Modules');
						foreach ($modtomb['acts'] as $key => $item) {
							$query_functions = "
								INSERT INTO iShark_Functions 
								(module_id, function_name, function_alias) 
								VALUES 
								('$last_module_id', '".$key."', '".$item."')
							";
							$mdb2->exec($query_functions);
						}
					}
				} else {
					$query = "
						SELECT module_id 
						FROM iShark_Modules 
						WHERE file_name = '".$modtomb['file']."' AND type = '".$modtomb['type']."'
					";
					$result = $mdb2->query($query);
					if ($result->numRows() > 0) {
						while ($row = $result->fetchRow())
						{
							$last_module_id = $row['module_id'];
						}

						$query = "
							UPDATE iShark_Modules 
							SET module_name = '".$modtomb['name']."', description = '".$modtomb['desc']."' 
							WHERE module_id = '$last_module_id'
						";
						$mdb2->exec($query);

						//feltoltjuk a jogosultsaghoz tartozo funkcio tablat
						if (is_array($modtomb['acts']) && !empty($modtomb['acts'])) {
							foreach ($modtomb['acts'] as $key => $item) {
								$query = "
									SELECT f.function_id AS fid 
									FROM iShark_Functions f 
									WHERE f.module_id = '$last_module_id' AND f.function_name = '".$key."'
								";
								$result = $mdb2->query($query);
								if ($result->numRows() == 0) {
									$query = "
										INSERT INTO iShark_Functions 
										(module_id, function_name, function_alias) 
										VALUES 
										('$last_module_id', '".$key."', '".$item."')
									";
									$mdb2->exec($query);
								}
							} //end foreach
						} //end if
					} //end if
				} //end else
			} //end if
		} //end foreach

		//lekerdezzuk es kiirjuk a rendszerben talalhato modulokat
		$query = "
			SELECT m.module_id AS mid, m.module_name AS mname, m.file_name AS mfname, m.file_ext AS mfext, 
				m.description AS mdesc, m.is_active AS mactive, m.type AS mtype, m.is_installed AS mins 
			FROM iShark_Modules AS m 
			".$fieldorder." ".$order."
		";
		$result = $mdb2->query($query);
		//lekerdezzuk, hogy fizikailag is megvannak-e a file-ok, ha valamelyik hianyzik, azt toroljuk a tablabol
		while ($row = $result->fetchRow()) {
			$type = $row['mtype'];
			//admin modulok
			if ($type == "admin") {
				if (!file_exists('admin/'.$row['mfname'].$row['mfext'])) {
					$mid = $row['mid'];
					$query_del = "
						DELETE FROM iShark_Modules 
						WHERE module_id = '$mid'
					";
					$mdb2->exec($query_del);
				}
			}
			//index modulok
			if ($type == "main") {
				if (!file_exists('modules/'.$row['mfname'].$row['mfext'])) {
					$mid = $row['mid'];
					$query_del = "
						DELETE FROM iShark_Modules 
						WHERE module_id = '$mid'
					";
					$mdb2->exec($query_del);
				}
			}
		}

		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);
	}

	$lang_modules = array(
		'strAdminModulesHeader'     => $strAdminModulesHeader,
		'strAdminModulesName'       => $strAdminModulesName,
		'strAdminModulesType'       => $strAdminModulesType,
		'strAdminModulesFile'       => $strAdminModulesFile,
		'strAdminModulesDesc'       => $strAdminModulesDesc,
		'strAdminModulesAction'     => $strAdminModulesAction,
		'strAdminModulesActivate'   => $strAdminModulesActivate,
		'strAdminModulesInActivate' => $strAdminModulesInActivate,
		'strAdminModulesInstall'    => $strAdminModulesInstall,
		'strAdminModulesUnInstall'  => $strAdminModulesUnInstall,
		'strAdminModulesEmptylist'  => $strAdminModulesEmptylist
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',    $paged_data['data']);
	$tpl->assign('page_list',    $paged_data['links']);
	$tpl->assign('lang_modules', $lang_modules);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'modules_list';
}

?>
