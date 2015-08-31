<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "newsletter";

$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'letter' => $locale->get('title'),
    'groups' => $locale->get('title_groups'),
    'users'  => $locale->get('title_users')
);

$acts = array(
    'letter' => array('add', 'mod', 'del', 'slst', 'send'),
    'groups' => array('add', 'mod', 'del'),
    'users'  => array('add', 'mod', 'del', 'act')
);

//aktualis ful beallitasa
$page = 'letter';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('permission_denied'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);
$tpl->assign('page_id',      $page_id);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

$javascripts[] = "javascripts";

//megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

// ezek a megengedett muveletek
//$is_act = array('letter', 'grp', 'usr', 'add', 'mod', 'del', 'act', 'ins', 'sendlist', 'unins', 'nuser_add', 'nuser_mod', 'nuser_del', 'nuser_act', 'nuser_lst',	'ngroup_add', 'ngroup_mod', 'ngroup_del', 'ngroup_lst');

//jogosultsag ellenorzes
/*if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "letter";
}
if (!check_perm($act, NULL, 1, $module_name)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('admin', 'permission_denied'));
	return;
}*/

/**
 * ha telepitjuk a modult
 */
/*if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletter_GroupUsers` (
			`newsletter_group_id` int(11) NOT NULL default '0',
			`newsletter_user_id` int(11) NOT NULL default '0',
		PRIMARY KEY  (`newsletter_group_id`,`newsletter_user_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletter_Groups` (
			`newsletter_group_id` int(11) NOT NULL auto_increment,
			`group_name` varchar(255) NOT NULL default '',
			`is_deleted` char(1) NOT NULL default '0',
		PRIMARY KEY  (`newsletter_group_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletter_Templates` (
			`newsletter_template_id` int(11) NOT NULL auto_increment,
			`template_name` varchar(255) NOT NULL default '',
			`file_name` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`newsletter_template_id`)
		);
	";
	$mdb2->exec($query);

	// Default template:
	$result =& $mdb2->query("SELECT count(*) as cnt FROM iShark_Newsletter_Templates");
	$row = $result->fetchRow();
	if (!$row['cnt']) {
		$mdb2->exec("INSERT INTO iShark_Newsletter_Templates (template_name, file_name) VALUES ('default', 'default.tpl')");
	}

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletter_Users` (
			`newsletter_user_id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL default '',
			`email` varchar(255) NOT NULL default '',
			`activate` varchar(50) NOT NULL default '',
			`is_active` char(1) NOT NULL default '',
			`is_deleted` char(1) NOT NULL default '',
		PRIMARY KEY  (`newsletter_user_id`),
		KEY `email` (`email`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletters` (
			`newsletter_id` int(11) NOT NULL auto_increment,
			`newsletter_template_id` int(11) NOT NULL default '0',
			`sender` varchar(255) NOT NULL DEFAULT '',
			`subject` varchar(255) NOT NULL default '',
			`message` text NOT NULL,
			`add_user_id` int(11) NOT NULL default '0',
			`add_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`charset` varchar(50) default NULL,
		PRIMARY KEY  (`newsletter_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE `iShark_Mail_Queue` (
			`id` bigint(20) NOT NULL default '0',
			`create_time` datetime NOT NULL default '0000-00-00 00:00:00',
			`time_to_send` datetime NOT NULL default '0000-00-00 00:00:00',
			`sent_time` datetime default NULL,
			`id_user` bigint(20) NOT NULL default '0',
			`ip` varchar(20) NOT NULL default 'unknown',
			`sender` varchar(50) NOT NULL default '',
			`recipient` text NOT NULL,
			`headers` text NOT NULL,
			`body` longtext NOT NULL,
			`try_sent` tinyint(4) NOT NULL default '0',
			`delete_after_send` tinyint(1) NOT NULL default '1',
		PRIMARY KEY  (`id`),
		KEY `id` (`id`),
		KEY `time_to_send` (`time_to_send`),
		KEY `id_user` (`id_user`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletter_Send_Dates` (
			`date_id` int(10) unsigned NOT NULL auto_increment,
			`newsletter_id` int(11) NOT NULL default '0',
			`date` datetime NOT NULL default '0000-00-00 00:00:00',
			`sender_user_id` int(11) NOT NULL default '0',
		PRIMARY KEY  (`date_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Newsletter_Sends` (
			`date_id` int(10) unsigned NOT NULL default '0',
			`to_user_id` int(11) NOT NULL default '0',
			`name` varchar(255) NOT NULL default '',
			`email` varchar(255) NOT NULL default '',
			KEY `dateuser` (`date_id`,`to_user_id`)
		)
	";
	$mdb2->exec($query);

	// Nyelvi beallitasok betoltese
	$locales_array =& $locale->getLocales();
	foreach($locales_array as $locale_id => $locale_name) {
		if (is_file($file = $lang_dir.'/locale_'.$module_name.'_'.$locale_id.'.xml')) {
			$locale->parseXML($file);
		} else {
			print "<pre>Nem talalhato az xml file: $file\n</pre>";
		}
	}

	//loggolas
	logger('ins');

	header('Location: admin.php?p='.$module_name);
	exit;
}*/

/**
 * ha toroljuk a modult
 */
/*if ($act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletter_GroupUsers`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletter_Groups`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletter_Templates`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletter_Users`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletters`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Mail_Queue`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletter_Send_Dates`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Newsletter_Sends`
	";
	$mdb2->exec($query);

	// nyelvi beallitasok torlese
	if ($area = $locale->getAreaByName($module_name)) {
		$locale->delArea($area['area_id']);
	}

	//loggolas
	logger('unins', NULL, '');


	header('Location: admin.php?p='.$module_name);
	exit;
}

//ha a leveleket nezzuk
if ($act == "letter") {
	include_once 'newsletter_letter.php';
}
//ha a csoportokat nezzuk
if ($act == "grp") {
	include_once 'newsletter_groups.php';
}

//ha a usereket nezzuk
if ($act == "usr") {
	include_once 'newsletter_users.php';
}*/

?>
