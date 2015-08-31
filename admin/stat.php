<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "stat";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'total'   => $locale->get('title_total'),
	'current' => $locale->get('title_active')
);

$acts = array(
    'total'   => array('lst'),
	'current' => array('lst')
);

//aktualis ful beallitasa
$page = 'total';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) || 
    ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
}

//Top statisztikaknal hany elem latszodik
if (!empty($_SESSION['site_stat_limit'])) {
	$limit = $_SESSION['site_stat_limit'];
} else {
	$limit = 20;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);
$tpl->assign('stat_limit',   $limit);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);

if ($sub_act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Accesslog (
			accesslog_id   INT(11)    NOT NULL,
			timestamp      INT(10)    UNSIGNED NOT NULL,
			weekday        TINYINT(1) UNSIGNED NOT NULL,
			`hour`         TINYINT(2) UNSIGNED NOT NULL,
			document_id    INT(11)    NOT NULL,
			exit_target_id INT(11)    DEFAULT '0' NOT NULL,
			entry_document TINYINT(1) UNSIGNED NOT NULL,
		KEY accesslog_id (accesslog_id),
		KEY timestamp (timestamp),
		KEY document_id (document_id),
		KEY exit_target_id (exit_target_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Add_Data (
			accesslog_id INT(11) NOT NULL,
			data_field VARCHAR(32)  NOT NULL,
			data_value VARCHAR(255) NOT NULL,
		KEY accesslog_id (accesslog_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Documents (
			data_id INT(11) NOT NULL,
			string VARCHAR(255) NOT NULL,
			document_url VARCHAR(255) NOT NULL,
		PRIMARY KEY (data_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Exit_Targets (
			data_id INT(11) NOT NULL,
			string VARCHAR(255) NOT NULL,
		PRIMARY KEY (data_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Hostnames (
			data_id INT(11) NOT NULL,
			string VARCHAR(255) NOT NULL,
		PRIMARY KEY (data_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Operating_Systems (
			data_id INT(11) NOT NULL,
			string VARCHAR(255) NOT NULL,
		PRIMARY KEY (data_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Referers (
			data_id INT(11) NOT NULL,
			string VARCHAR(255) NOT NULL,
		PRIMARY KEY (data_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_User_Agents (
			data_id INT(11) NOT NULL,
			string  VARCHAR(255) NOT NULL,
		PRIMARY KEY (data_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Visitors (
			accesslog_id INT(11) NOT NULL,
			visitor_id INT(11) NOT NULL,
			client_id INT(10) UNSIGNED NOT NULL,
			operating_system_id INT(11) NOT NULL,
			user_agent_id INT(11) NOT NULL,
			host_id INT(11) NOT NULL,
			referer_id INT(11) NOT NULL,
			timestamp INT(10) UNSIGNED NOT NULL,
			weekday TINYINT(1) UNSIGNED NOT NULL,
			`hour` TINYINT(2) UNSIGNED NOT NULL,
			returning_visitor TINYINT(1) UNSIGNED NOT NULL,
		PRIMARY KEY (accesslog_id),
		KEY client_time (client_id, timestamp)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Search_Engines (
			accesslog_id INT(11) NOT NULL,
			search_engine VARCHAR(255) NOT NULL,
			keywords VARCHAR(255) NOT NULL,
		PRIMARY KEY (accesslog_id)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Stat_Localizer (
			accesslog_id  INT(11)      NOT NULL,
			client_id     INT(10)      NOT NULL,
			ip            VARCHAR(15)  NOT NULL,
			country       VARCHAR(100) NOT NULL,
			iso2          VARCHAR(2)   NOT NULL,
			iso3          VARCHAR(3)   NOT NULL,
			fips104       VARCHAR(2)   NOT NULL,
			iso_number    DECIMAL(5,2) NOT NULL,
			flag          VARCHAR(6)   NOT NULL,
			region        VARCHAR(30)  NOT NULL,
			capital       VARCHAR(50)  NOT NULL,
			currency      VARCHAR(30)  NOT NULL,
			currency_code VARCHAR(3)   NOT NULL,
		PRIMARY KEY (accesslog_id),
		INDEX client_id (client_id, iso2, capital)
		)
	";
	$mdb2->exec($query);
}

// Month Names
$monthNames = array(
	1 => $locale->get('month_january'),
	$locale->get('month_february'),
	$locale->get('month_march'),
	$locale->get('month_april'),
	$locale->get('month_may'),
	$locale->get('month_june'),
	$locale->get('month_july'),
	$locale->get('month_august'),
	$locale->get('month_september'),
	$locale->get('month_october'),
	$locale->get('month_november'),
	$locale->get('month_december')
);

// Load phpOpenTracker
require_once 'phpOpenTracker.php';

// Handle HTTP GET parameters
$clientID = isset($_GET['client_id']) ? $_GET['client_id'] : 1;
$day      = isset($_GET['day'])       ? $_GET['day']       : date('j');
$month    = isset($_GET['month'])     ? $_GET['month']     : date('n');
$year     = isset($_GET['year'])      ? $_GET['year']      : date('Y');
$time     = time();

// Get references to phpOpenTracker's configuration
// and database objects
$config = &phpOpenTracker_Config::getConfig();
$db     = &phpOpenTracker_DB::getInstance();

// megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

?>
