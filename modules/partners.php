<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */

if (!eregi('index\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzáférés megtagadva');
}

/* Modul neve */
$module_name = 'partners';

// nyelvi allomany betoltese
$locale->useArea("index_".$module_name);


// jogosultsagellenorzes
if (!isset($_SESSION['user_id'])) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('main_error_only_partners'));
    return;
} else {
	$query = "
		SELECT P.partner_id as partner_id 
		FROM iShark_Users U, iShark_Partners P 
		WHERE P.partner_id = ".$_SESSION['user_id']." AND P.partner_id = U.user_id
	";
	$result =& $mdb2->query($query);
	if (!$partner = $result->fetchRow()) {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('main_error_only_partners'));
		return;
	}
}

// fulek definialasa
$tabs = array(
    'news'      => $locale->get('main_tabs_news'),
    'prices'    => $locale->get('main_tab_prices'), 
    'discounts'	=> $locale->get('main_tabs_discounts'), 
    'mailing'   => $locale->get('main_tabs_mail')
);

$acts = array(
	'news'      => array('show'),
    'prices'    => array('download'),
	'discounts' => array('show'),
	'mailing'   => array('del', 'show')
);

//aktualis ful beallitasa
$page = 'news';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

$self = 'p='.$module_name;
if (isset($_GET['mid'])) {
    $self = 'mid='.intval($_GET['mid']);
}

$tpl->assign('module_name',  $module_name);
$tpl->assign('self',         $self);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

// megfelelo ful programjanak betoltese
include_once "${module_name}_${page}.php";

?>