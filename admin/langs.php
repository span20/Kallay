<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */

if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzáférés megtagadva');
}

//modul neve
$module_name = "langs";
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'langs'  => $title_module['title'],
    'search' => $locale->get('search_title')
);

$acts = array(
    'langs'  => array('add', 'add_lang', 'mod_lang', 'del_lang', 'mod', 'del', 'w_lst', 'w_add', 'w_mod', 'w_del', 'export', 'import'),
    'search' => array()
);

$page = 'langs';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
    if (isset($_REQUEST['variable_id']) && in_array($sub_act, array('add', 'mod', 'del'))) {
        $sub_act = 'w_'.$sub_act;
    }
} 

//aktualis lapszam beallitasa
if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

$locales_array =& $locale->getLocales();

// Install rész
/*if (isset($_REQUEST['act'])) {
    // INSTALL
    if ($_REQUEST['act'] == 'ins' && check_perm('ins', 0, 1, $module_name)) {
        foreach ($locales_array as $key => $value) {
            $file = $lang_dir.'/locale_'.$module_name.'_'.$key.'.xml';
            print $file;
            if (is_file($file)) {
                $locale->parseXML($file);
            }
        }
    } 
    // Uninstall
    if ($_REQUEST['act'] == 'unins' && check_perm('unins', 0, 1, $module_name)) {
        $area = $locale->getAreaByName($module_name);
        $locale->delArea($area['area_id']);
    }
}*/

// jogosultsag ellenorzes
if (!check_perm($page, 0, 1, $module_name) || 
    ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('admin', 'permission_denied'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
//$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);

include_once $module_name."_${page}.php";

?>
