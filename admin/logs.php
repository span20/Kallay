<?php

// közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "logs";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'logs' => $locale->get('tabs_title')
);

$acts = array(
    'logs' => array('lst', 'tru')
);

//aktualis ful beallitasa
$page = 'logs';
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
if (!check_perm($page, 0, 0, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
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
			$fieldorder   = "ORDER BY L.time ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY U.name ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = "ORDER BY U.user_name ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   = "ORDER BY M.module_name ";
			$fieldselect4 = "selected";
			break;
		case 5:
			$fieldorder   = "ORDER BY F.function_alias ";
			$fieldselect5 = "selected";
			break;
		case 6:
			$fieldorder   = "ORDER BY L.description ";
			$fieldselect5 = "selected";
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
	$fieldorder = "ORDER BY L.time";
	$order      = "DESC";
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
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
//rendezes vege

/**
 * ha uritjuk a rendszernaplot
 */
if ($sub_act == "tru") {
    $query = "
	    TRUNCATE iShark_Logs
    ";
    $mdb2->exec($query);

    //loggolas
    logger($sub_act, '', '');

    header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
    exit;
} //rendszernaplo urites vege

/**
 * ha nincs semmilyen muvelet, akkor a listat mutatjuk
 */
if ($sub_act == 'lst') {
    //lekerdezzuk az adatbazisbol a csoportok listajat
    $query = "
    	SELECT L.log_id AS log_id, L.time AS time, U.name AS name, U.user_name AS user_name, M.module_name AS module_name, 
			F.function_alias AS function_desc, L.description AS description
    	FROM iShark_Logs L
    	LEFT JOIN iShark_Users U ON U.user_id = L.user_id
    	LEFT JOIN iShark_Modules M ON M.module_id = L.module_id
    	LEFT JOIN iShark_Functions F ON F.module_id = L.module_id AND F.function_name = L.function_name 
    	$fieldorder $order
	";

    require_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=tru&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID=1',
			'title' => $locale->get('title_truncate'),
			'pic'   => 'logtrun.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
    $tpl->assign('page_data',  $paged_data['data']);
    $tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('add_new',    $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
    $acttpl = 'logs_list';
}

?>
