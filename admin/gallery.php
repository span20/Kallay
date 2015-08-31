<?php 

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "gallery";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('main_title')
);

// fulek definialasa
$tabs = array();
$tabs['gallery'] = $locale->get('tabs_title_picture');
if (!empty($_SESSION['site_gallery_is_video'])) {
    $tabs['video'] = $locale->get('tabs_title_video');
}
if (isModule('sendnews', 'index')) {
    $tabs['send'] = $locale->get('tabs_title_send');
}

$acts = array(
    'gallery' => array('gadd', 'gmod', 'gdel', 'act', 'ftp', 'upl', 'plst', 'view', 'pmod', 'pdel', 'pord'),
    'video'   => array('gadd', 'gmod', 'gdel', 'act', 'ftp', 'upl', 'plst', 'view', 'pmod', 'pdel'),
    'send'    => array('gmod', 'gdel', 'act', 'plst', 'view', 'pmod', 'pdel')
);

//aktualis ful beallitasa
$page = 'gallery';
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

$tpl->assign('page_id',      $page_id);
$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

/**
 * ha telepitjuk a modult
 */
/*if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Galleries` (
			`gallery_id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL default '',
			`description` text NOT NULL,
			`add_user_id` int(11) NOT NULL default '0',
			`add_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`mod_user_id` int(11) NOT NULL default '0',
			`mod_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`timer_start` datetime NOT NULL default '0000-00-00 00:00:00',
			`timer_end` datetime NOT NULL default '0000-00-00 00:00:00',
			`is_active` char(1) NOT NULL default '',
			`type` char(1) NOT NULL default 'p',
			`is_rateable` char(1) NOT NULL default '0',
		PRIMARY KEY  (`gallery_id`)
		) TYPE=MyISAM
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Galleries_Contents` (
		 `gallery_id` int(11) NOT NULL default '0',
		 `content_id` int(11) NOT NULL default '0',
		 INDEX (`gallery_id`, `content_id`)
		) TYPE=MyISAM
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Galleries_Pictures` (
			`gallery_id` int(11) NOT NULL default '0',
			`picture_id` int(11) NOT NULL default '0',
		PRIMARY KEY  (`gallery_id`,`picture_id`)
		) TYPE=MyISAM
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Pictures` (
			`picture_id` int(11) NOT NULL auto_increment,
			`realname` varchar(255) NOT NULL default '',
			`name` varchar(255) NOT NULL default '',
			`width` smallint(5) unsigned NOT NULL default '0',
			`height` smallint(5) unsigned NOT NULL default '0',
			`tn_width` smallint(5) unsigned NOT NULL default '0',
			`tn_height` smallint(5) unsigned NOT NULL default '0',
			`add_user_id` int(11) NOT NULL default '0',
			`add_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`mod_user_id` int(11) NOT NULL default '0',
			`mod_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`is_active` char(1) NOT NULL default '',
		PRIMARY KEY  (`picture_id`)
		) TYPE=MyISAM
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS iShark_Pictures_Ratings (
			`picture_id` INT NOT NULL, 
			`rate` TINYINT NOT NULL, 
			`user_id` INT NOT NULL, 
		INDEX (`picture_id`, `user_id`)
		)
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$_REQUEST['page']);
	exit;
}*/

/**
 * ha toroljuk a modult
 */
/*if ($act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Pictures`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Galleries`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Galleries_Contents`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Galleries_Pictures`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Pictures_Ratings`
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$_REQUEST['page']);
	exit;
}*/ //torles vege

// Galéria azonosító lekérdezése
$gid = 0;
if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid']) && $_REQUEST['gid'] != 0) {
	$gid = intval($_REQUEST['gid']);

	// Kiválasztott galéria adatainak lekérdezése
	$query = "
		SELECT * 
		FROM iShark_Galleries 
		WHERE gallery_id = $gid
	";
	//ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
	/*if (empty($_SESSION['site_gallery_is_video'])) {
	    $query .= " AND type != 'v' ";
	}*/
	$result =& $mdb2->query($query);

	// Ha nem létezik a galéria, visszadobjuk a fõoldalra
	if (!$gallery = $result->fetchRow()) {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('error_gallery_not_exists'));
		return;
	} else {
	    $type = $gallery['type'];
	    $tpl->assign('gid', $gid);
	}
}

// Kep azonosito lekerdezes
$pid = 0;
if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid']) && $_REQUEST['pid'] != 0) {
	$pid = intval($_REQUEST['pid']);

	$query = "
		SELECT *
		FROM iShark_Pictures 
		WHERE picture_id = $pid
	";
	$result =& $mdb2->query( $query );
	if (!$picture = $result->fetchRow()) {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('error_pics_not_exists'));
		return;
	}
}

// megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

?>
