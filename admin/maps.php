<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */

if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzáférés megtagadva');
}

//modul neve
$module_name = "maps";

// nyelvi allomany betoltese
require_once $lang_dir.'/modules/'.$module_name.'/'.$_SESSION['site_lang'].'.php';

//design-hoz a cim betoltese
$title_module = array(
	'title' => $strAdminMapsModuleTitle
);

// fulek definialasa
$tabs = array(
    'resellers' => $strAdminMapsTabResellers,
    'agencies'  => $strAdminMapsTabAgencies,
);

$acts = array(
    'resellers' => array('add', 'mod', 'del'),
    'agencies'  => array('add', 'mod', 'del'),
);

//aktualis ful beallitasa
$page = 'resellers';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//aktualis lapszam beallitasa
if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) || 
    ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $strAdminMapsErrorPermissionDenied);
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);

$breadcrumb->add($strAdminMapsModuleTitle, 'admin.php?p='.$module_name);
$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);

// megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

?>
