<?php

/**
 * Adatbazis mezok allapotai
 *
 * is_active:
 * 0 - nem aktivalt
 * 1 - aktivalt
 * 2 - bekuldott hir, aktivalni vagy torolni lehet
 *
 * type:
 * 0 - h�r
 * 1 - tartalom
 * 2 - mti hir
 *
 * mainnews:
 * 0 - sima hir
 * 1 - vezeto hir
 */

if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzaferes megtagadva');
}

//modul neve
$module_name = "contents";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('main_title')
);

if (empty($_SESSION['site_is_news']) && empty($_SESSION['site_is_other']) && empty($_SESSION['site_category'])) {
	$acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('main_error_permission_denied'));
    return;
}

$page = 'content';
// fulek definialasa
$tabs = array(
	'news'     => $locale->get('main_title_tab_news'),
	'mti'      => $locale->get('main_title_tab_mti'),
	'content'  => $locale->get('main_title_tab_contents'),
	'category' => $locale->get('main_title_tab_category'),
	'sendnews' => $locale->get('main_title_tab_sendnews'),
	'mtinews'  => $locale->get('main_title_tab_mtinews')
);
//hireket lehet-e hasznalni
if (empty($_SESSION['site_is_news'])) {
	unset($tabs['news']);
}
//tartalmat lehet-e hasznalni
if (empty($_SESSION['site_is_other'])) {
	unset($tabs['content']);
}
//kategoriakat lehet-e hasznalni
if (empty($_SESSION['site_category'])) {
	unset($tabs['category']);
}
//bekuldott hirek
if (!isModule('sendnews', 'index')) {
	unset($tabs['sendnews']);
}
//mti hirek
if (empty($_SESSION['site_cnt_is_mti'])) {
    unset($tabs['mti']);
	unset($tabs['mtinews']);
}

$acts = array(
    'news'     => array('act', 'add', 'mod', 'del', 'restore', 'show'),
    'content'  => array('act', 'add', 'mod', 'del', 'restore', 'show'),
	'category' => array('act', 'add', 'mod', 'del'),
	'sendnews' => array('act', 'del', 'show'),
	'mtinews'  => array('act', 'del', 'show'),
	'mti'      => array('act', 'mod', 'del')
);

$tabok = array_keys($tabs);
$page = $tabok[0];
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
    $tpl->assign('errormsg', $locale->get('main_error_permission_denied'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);
$tpl->assign('page_id',      $page_id);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
$breadcrumb->add($tabs[$page],           'admin.php?p='.$module_name.'&amp;act='.$page);

// megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

?>
