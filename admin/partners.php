<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */

if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzáférés megtagadva');
}

//modul neve
$module_name  = "partners";

// nyelvi allomany betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
	'partners'  => $locale->get('title_partners'),
	'groups'    => $locale->get('title_groups'),
	'prices'    => $locale->get('title_prices'),
	'discounts'	=> $locale->get('title_discounts'),
	'news'      => $locale->get('title_news'),
	'mailing'   => $locale->get('title_mailing')
);

$acts = array(
	'partners'  => array('add', 'mod', 'del', 'act'),
	'groups'    => array('add', 'mod', 'del'),
	'prices'    => array('add', 'mod', 'del'),
	'discounts' => array('add', 'mod', 'del'),
	'news'      => array('add',	'mod', 'del'),
	'mailing'   => array('add', 'mod', 'del', 'send', 'sendinfo')
);

//aktualis ful beallitasa
$page = 'partners';
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
    $tpl->assign('errormsg', $locale->get('error_permission_denied'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);

// megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

/**
 * adatbazis letrehozasa
 *
 */
/*function _install() {
    global $mdb2;
        $mdb2->exec("
             CREATE TABLE IF NOT EXISTS `iShark_Partners_Discounts` (
                `discount_id` int(11) NOT NULL auto_increment,
                `title` varchar(255) NOT NULL default '',
                `timer_start` datetime NOT NULL default '0000-00-00 00:00:00',
                `timer_end` datetime NOT NULL default '0000-00-00 00:00:00',
                `description` text NOT NULL,
            PRIMARY KEY  (`discount_id`)
            ) TYPE=MyISAM ;
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Groups` (
                `pg_id` int(11) NOT NULL auto_increment,
                `group_name` varchar(255) NOT NULL default '',
                PRIMARY KEY  (`pg_id`)
            ) TYPE=MyISAM
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Mails_Sends` (
                `send_id` int(11) NOT NULL auto_increment,
                `title` varchar(255) NOT NULL default '',
                `content` longtext NOT NULL,
                `send_date` datetime NOT NULL default '0000-00-00 00:00:00',
                `sender_user_id` int(11) NOT NULL default '0',
                `mail_id` int(11) NOT NULL default '0',
                PRIMARY KEY  (`send_id`)
            ) TYPE=MyISAM
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Mails_Tos` (
                `partner_id` int(11) NOT NULL default '0',
                `send_id` int(11) NOT NULL default '0',
                `is_read` char(1) NOT NULL default '0',
                `is_deleted` char(1) NOT NULL default '0',
                PRIMARY KEY  (`partner_id`,`send_id`)
            ) TYPE=MyISAM
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Mails` (
                `mail_id` int(11) NOT NULL auto_increment,
                `title` varchar(255) NOT NULL default '',
                `content` longtext NOT NULL,
                `send_date` datetime NOT NULL default '0000-00-00 00:00:00',
                `mod_user_id` int(11) NOT NULL default '0',
                `add_user_id` int(11) NOT NULL default '0',
                PRIMARY KEY  (`mail_id`)
            ) TYPE=MyISAM 
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_News` (
                `news_id` int(11) NOT NULL auto_increment,
                `title` varchar(255) NOT NULL default '',
                `timer_start` datetime NOT NULL default '0000-00-00 00:00:00',
                `timer_end` datetime NOT NULL default '0000-00-00 00:00:00',
                `description` text NOT NULL,
                `add_date` datetime NOT NULL default '0000-00-00 00:00:00',
                PRIMARY KEY  (`news_id`)
                ) TYPE=MyISAM 
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Partner_Groups` (
                `pg_id` int(11) NOT NULL default '0',
                `partner_id` int(11) NOT NULL default '0',
                PRIMARY KEY  (`pg_id`,`partner_id`)
            ) TYPE=MyISAM
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Prices_Groups` (
                `price_id` int(10) unsigned NOT NULL default '0',
                `group_id` int(10) unsigned NOT NULL default '0',
                PRIMARY KEY  (`price_id`,`group_id`)
            ) TYPE=MyISAM
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners_Prices_Lists` (
                `price_id` int(10) unsigned NOT NULL auto_increment,
                `name` varchar(255) NOT NULL default '',
                `file_name` varchar(255) NOT NULL default '',
                `file_orig` varchar(255) NOT NULL default '',
                PRIMARY KEY  (`price_id`)
            ) TYPE=MyISAM
        ");
        $mdb2->exec("
            CREATE TABLE IF NOT EXISTS `iShark_Partners` (
                `partner_id` int(11) NOT NULL default '0',
                `phone` varchar(30) NOT NULL default '',
                `company` varchar(255) NOT NULL default '',
                `website` varchar(255) NOT NULL default '',
                `fax` varchar(30) NOT NULL default '',
                `address` varchar(255) NOT NULL default '',
                PRIMARY KEY  (`partner_id`)
            ) TYPE=MyISAM
        ");
}*/
?>