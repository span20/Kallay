<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "settings";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

// Cím beállítása
$title_module = array(
	'title' => $locale->get('title')
);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst');

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p=settings');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

if (!check_perm($act, '', 1, 'settings')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_permission_denied'));
	return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);

if (isset($_GET['file'])) {
	include_once 'admin/settings/'.$_GET['file'];
} else {
	if ($act == "lst") {
		//lekerdezzuk es kiirjuk a rendszerben talalhato fooldali modulokat
		$query = "
			SELECT DISTINCT m.module_id AS mid, m.module_name AS mname, m.file_name AS mfname, m.file_ext AS mfext, 
				m.description AS mdesc, m.is_active AS mactive, m.type AS mtypem 
			FROM iShark_Modules AS m 
			WHERE m.is_active = 1 
			GROUP BY m.file_name 
			ORDER BY m.module_name
		";
		$result = $mdb2->query($query);
		//ha ures a lista, akkor uzenet
		if ($result->numRows() != 0) {
			$dirlist = directory_list('admin/settings', 'php', array(), 1);

			$i = 0;
			$itemdata = array();
			while ($row = $result->fetchRow())
			{
				//csak akkor rakjuk bele a tombbe, ha letezik hozza adminisztracios file is
				if (is_array($dirlist) && count($dirlist) > 0 && in_array($row['mfname'], $dirlist) && file_exists('admin/settings/'.$row['mfname'].$row['mfext'])) {
					$itemdata[$i]['mname'] = $row['mname'];
					$itemdata[$i]['mfile'] = $row['mfname'];
					$itemdata[$i]['mext']  = $row['mfext'];
					$i++;
				}
			}

			//atadjuk a smarty-nak a kiirando cuccokat
			$tpl->assign('settinglist', $itemdata);
		}

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = "settings_list";
	}
}

?>