<?php

/**
 * Status mezo jelentese:
 *
 * 1 = elo megrendeles
 * 2 = teljesitett megrendeles
 * 3 = torolt megrendeles
 */

// K�zvetlen�l ezt az �llom�nyt k�rte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("K�zvetlen�l nem lehet az �llom�nyhoz hozz�f�rni...");
}

//modul neve
$module_name = "shop";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('main_title')
);

//fulek definialasa
$tabs = array();
$tabs['categories'] = $locale->get('tabs_title_category');
//ha csoportositast hasznalunk
if (!empty($_SESSION['site_shop_groupuse'])) {
	$tabs['groups'] = $locale->get('tabs_title_groups');
}
$tabs['products'] = $locale->get('tabs_title_products');
//ha hasznaljuk az akciokat
if (!empty($_SESSION['site_shop_actionuse'])) {
	$tabs['actions'] = $locale->get('tabs_title_actions');
}
//ha a felhasznalok vasarolhatnak
if (!empty($_SESSION['site_shop_userbuy'])) {
	$tabs['orders']          = $locale->get('tabs_title_orders');
	$tabs['orders_finished'] = $locale->get('tabs_title_ordersfinished');
}
$tabs['search'] = $locale->get('tabs_title_search');

//elerheto funkciok listaja
$acts = array(
    'categories'      => array('add', 'mod', 'del', 'act', 'ord'),
    'products'        => array('add', 'mod', 'del', 'act', 'ord'),
	'groups'          => array('add', 'mod', 'del', 'act', 'ord'),
	'actions'         => array('add', 'mod', 'del', 'act'),
	'orders'          => array('add', 'mod'),
	'orders_finished' => array('mod'),
	'search'          => array()
);

//aktualis ful beallitasa
$page = 'categories';
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

//jogosultsag ellenorzes
if (!check_perm($page, 0, 1, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('main_error_mo_permission'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);
$tpl->assign('page_id',      $page_id);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);

//megfelelo ful programjanak betoltese
include_once $module_name."_${page}.php";

/**
 * ha telepitjuk a modult
 */
/*if ($_GET['act'] == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Category` (
			`category_id` INT NOT NULL AUTO_INCREMENT ,
			`category_name` VARCHAR( 255 ) NOT NULL ,
			`category_desc` TEXT NOT NULL ,
			`parent` INT NOT NULL ,
			`sortorder` INT NOT NULL ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL ,
			`timer_start` DATETIME NOT NULL ,
			`timer_end` DATETIME NOT NULL ,
			`is_preferred` CHAR( 1 ) NOT NULL ,
			`picture` VARCHAR( 255 ) NOT NULL ,
			`lang` VARCHAR( 10 ) NOT NULL ,
		PRIMARY KEY ( `category_id` ) ,
		INDEX ( `add_user_id` , `mod_user_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Groups` (
			`group_id` INT NOT NULL AUTO_INCREMENT ,
			`group_name` VARCHAR( 255 ) NOT NULL ,
			`group_desc` TEXT NOT NULL ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL ,
			`lang` VARCHAR( 10 ) NOT NULL ,
		PRIMARY KEY ( `group_id` ) ,
		INDEX ( `add_user_id` , `mod_user_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products` (
			`product_id` INT NOT NULL AUTO_INCREMENT ,
			`item_id` VARCHAR( 255 ) NOT NULL ,
			`product_name` VARCHAR( 255 ) NOT NULL ,
			`product_desc` TEXT NOT NULL ,
			`netto` DECIMAL(9,2) NOT NULL ,
			`afa` INT NOT NULL ,
			`sortorder` INT NOT NULL ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL ,
			`is_deleted` CHAR( 1 ) NOT NULL ,
			`timer_start` DATETIME NOT NULL ,
			`timer_end` DATETIME NOT NULL ,
			`is_preferred` CHAR( 1 ) NOT NULL ,
			`lang` VARCHAR( 10 ) NOT NULL ,
			`state_id` INT NOT NULL ,
			`attributes` TEXT NULL ,
		PRIMARY KEY ( `product_id` ) ,
		INDEX ( `add_user_id`, `mod_user_id`, `state_id` ), 
		UNIQUE ( `item_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products_Picture` (
			`product_id` INT NOT NULL ,
			`picture` VARCHAR( 255 ) NOT NULL ,
		INDEX ( `product_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products_Document` (
			`document_id` INT NOT NULL AUTO_INCREMENT ,
			`product_id` INT NOT NULL ,
			`document` VARCHAR( 255 ) NOT NULL ,
			`document_gen` VARCHAR( 255 ) NOT NULL ,
			`document_real` VARCHAR( 255 ) NOT NULL ,
		PRIMARY KEY ( `document_id` ) ,
		INDEX ( `product_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products_Category` (
			`product_id` INT NOT NULL ,
			`category_id` INT NOT NULL ,
		UNIQUE (
			`product_id` ,
			`category_id`
			)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products_Groups` (
			`product_id` INT NOT NULL ,
			`group_id` INT NOT NULL ,
		UNIQUE (
			`product_id` ,
			`group_id`
			)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products_Join` (
			`product_id` INT NOT NULL ,
			`join_id` INT NOT NULL ,
		UNIQUE (
			`product_id` ,
			`join_id`
			)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Products_Rating` (
			`rating_id` INT NOT NULL AUTO_INCREMENT , 
			`product_id` INT NOT NULL , 
			`user_id` INT NOT NULL , 
			`rating` TINYINT( 4 ) NOT NULL , 
			`comment` TEXT NOT NULL , 
			`add_date` DATETIME NOT NULL ,
		PRIMARY KEY ( `rating_id` ) , 
		INDEX ( `product_id`, `user_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Category_Groups` (
			`category_id` INT NOT NULL ,
			`group_id` INT NULL ,
		UNIQUE (
			`category_id` ,
			`group_id`
			)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Properties` (
			`prop_id` INT NOT NULL AUTO_INCREMENT ,
			`prop_value` VARCHAR( 255 ) NOT NULL ,
			`prop_type` VARCHAR( 255 ) NOT NULL ,
			`prop_display` VARCHAR( 255 ) NOT NULL ,
			`prop_is_list` CHAR( 1 ) NOT NULL ,
			`prop_checkf` VARCHAR( 255 ) NOT NULL ,
		PRIMARY KEY ( `prop_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Properties_Check` (
			`prop_id` INT NOT NULL ,
			`error_check` VARCHAR( 50 ) NOT NULL ,
			`error_txt` VARCHAR( 255 ) NOT NULL ,
		INDEX ( `prop_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Properties_Category` (
			`prop_id` INT NOT NULL ,
			`category_id` INT NOT NULL ,
		UNIQUE (
			`prop_id` ,
			`category_id`
			)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Configs_Shipping` (
			`shipping_id` INT NOT NULL AUTO_INCREMENT,
			`shipping_text` VARCHAR( 255 ) NOT NULL, 
			`shipping_price` INT NOT NULL, 
		INDEX ( `shipping_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Configs` (
			`shop_ordermail` VARCHAR( 255 ) NOT NULL, 
			`shop_is_reguser_rating` CHAR( 1 ) NOT NULL, 
			`shop_rate_minchar` INT NOT NULL, 
			`shop_rate_maxchar` INT NOT NULL, 
			`shop_newprodsnum` INT NOT NULL, 
			`shop_mailsubject` VARCHAR( 255 ) NOT NULL
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Afa` (
			`afa_id` INT NOT NULL AUTO_INCREMENT ,
			`afa_percent` INT NOT NULL ,
		PRIMARY KEY ( `afa_id` )
		);
	";
	$mdb2->exec($query);
	$query = "INSERT INTO iShark_Shop_Afa VALUES (1, '5');";
	$mdb2->exec($query);
	$query = "INSERT INTO iShark_Shop_Afa VALUES (2, '20');";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Basket` (
			`basket_id` BIGINT NOT NULL AUTO_INCREMENT ,
			`product_id` INT NOT NULL ,
			`session_id` VARCHAR( 255 ) NOT NULL ,
			`user_id` INT NOT NULL ,
			`nuser_id` INT NOT NULL ,
			`amount` INT NOT NULL ,
			`price` DECIMAL( 7, 2 ) NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`attributes` TEXT NOT NULL ,
		PRIMARY KEY ( `basket_id` ) ,
		INDEX ( `product_id` , `user_id`, `nuser_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Orders` (
			`order_id` BIGINT NOT NULL AUTO_INCREMENT ,
			`user_id` INT NOT NULL ,
			`nuser_id` INT NOT NULL ,
			`order_date` DATETIME NOT NULL ,
			`comment` TEXT NOT NULL ,
			`post_address` TEXT NOT NULL ,
			`ship_address` TEXT NOT NULL ,
			`phone_mobile` VARCHAR( 50 ) NOT NULL ,
			`shipping` INT NOT NULL ,
		PRIMARY KEY ( `order_id` ) ,
		INDEX ( `user_id`, `nuser_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Orders_Finished` (
			`finished_id` BIGINT NOT NULL AUTO_INCREMENT ,
			`user_id` INT NOT NULL ,
			`nuser_id` INT NOT NULL ,
			`finished_date` DATETIME NOT NULL ,
			`comment` TEXT NOT NULL ,
			`post_address` TEXT NOT NULL ,
			`ship_address` TEXT NOT NULL ,
			`phone_mobile` VARCHAR( 50 ) NOT NULL ,
			`shipping` TEXT NOT NULL ,
		PRIMARY KEY ( `finished_id` ) ,
		INDEX ( `user_id`, `nuser_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Orders_Products` (
			`op_id` BIGINT NOT NULL AUTO_INCREMENT ,
			`order_id` BIGINT NOT NULL ,
			`product_id` INT NOT NULL ,
			`amount` INT NOT NULL ,
			`price` DECIMAL( 7, 2) NOT NULL ,
			`status` CHAR( 1 ) ,
			`state_id` INT NOT NULL ,
			`finished_id` BIGINT NOT NULL ,
			`attributes` TEXT NOT NULL ,
		PRIMARY KEY ( `op_id` ) ,
		INDEX ( `order_id`, `product_id`, `state_id`, `finished_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Country` (
			`country_id` int(11) NOT NULL auto_increment,
			`country_name` varchar(100) NOT NULL default '',
		PRIMARY KEY  (`country_id`)
		);
	";
	$mdb2->exec($query);

	$query = "INSERT INTO `iShark_Country` VALUES (1, 'Afganiszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (2, 'Alb�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (3, 'Alg�ria');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (4, 'Amerikai Egyes�lt �llamok');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (5, 'Andorra');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (6, 'Angola');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (7, 'Antigua �s Barbuda');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (8, 'Arab Em�rs�gek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (9, 'Argent�na');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (10, 'Ausztr�lia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (11, 'Ausztria');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (12, 'Azerbajdzs�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (13, 'Bahama-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (14, 'Bahrein');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (15, 'Banglades');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (16, 'Barbados');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (17, 'Belgium');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (18, 'Belize');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (19, 'Feh�roroszorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (20, 'Benin');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (21, 'Bhut�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (22, 'Bissau-Guinea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (23, 'Bol�via');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (24, 'Bosznia-Hercegovina');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (25, 'Botswana');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (26, 'Braz�lia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (27, 'Brunei');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (28, 'Bulg�ria');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (29, 'Burkina Faso');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (30, 'Burundi');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (31, 'Chile');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (32, 'Ciprus');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (33, 'Comore-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (34, 'Costa Rica');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (35, 'Cs�d');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (36, 'Csehorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (37, 'D�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (38, 'D�l-afrikai K�zt�rsas�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (39, 'D�l-Korea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (40, 'Dominikai K�z�ss�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (41, 'Dominikai K�zt�rsas�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (42, 'Dzsibuti');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (43, 'Ecuador');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (44, 'Egyenl�t�i-Guinea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (45, 'Egyes�lt Kir�lys�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (46, 'Egyiptom');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (47, 'Elef�ntcsontpart');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (48, 'Eritrea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (49, '�szak-Korea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (50, '�sztorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (51, 'Eti�pia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (52, 'Fidzsi-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (53, 'Finnorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (54, 'Franciaorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (55, 'F�l�p-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (56, 'Gabon');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (57, 'Gambia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (58, 'Gh�na');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (59, 'G�r�gorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (60, 'Grenada');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (61, 'Gr�zia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (62, 'Guatemala');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (63, 'Guinea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (64, 'Guyana');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (65, 'Haiti');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (66, 'Hollandia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (67, 'Honduras');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (68, 'Horv�torsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (69, 'India');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (70, 'Indon�zia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (71, 'Irak');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (72, 'Ir�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (73, '�rorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (74, 'Izland');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (75, 'Izrael');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (76, 'Jamaica');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (77, 'Jap�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (78, 'Jemen');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (79, 'Jord�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (80, 'Kambodzsa');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (81, 'Kamerun');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (82, 'Kanada');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (83, 'Katar');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (84, 'Kazahszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (85, 'Kelet-Timor');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (86, 'Kenya');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (87, 'K�na');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (88, 'Kirgiziszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (89, 'Kiribati');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (90, 'Kolumbia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (91, 'Kong�');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (92, 'Zaire');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (93, 'K�z�p-afrikai K�zt�rsas�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (94, 'Kuba');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (95, 'Kuvait');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (96, 'Laosz');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (97, 'Lengyelorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (98, 'Lesotho');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (99, 'Lettorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (100, 'Libanon');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (101, 'Lib�ria');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (102, 'L�bia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (103, 'Liechtenstein');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (104, 'Litv�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (105, 'Luxemburg');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (106, 'Maced�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (107, 'Madagaszk�r');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (108, 'Magyarorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (109, 'Malajzia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (110, 'Malawi');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (111, 'Mald�v-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (112, 'Mali');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (113, 'M�lta');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (114, 'Marokk�');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (115, 'Marshall-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (116, 'Maurit�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (117, 'Mauritius');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (118, 'Mexik�');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (119, 'Mianmar');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (120, 'Mikron�zia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (121, 'Moldova');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (122, 'Monaco');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (123, 'Mong�lia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (124, 'Mozambik');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (125, 'Nam�bia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (126, 'Nauru');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (127, 'N�metorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (128, 'Nep�l');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (129, 'Nicaragua');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (130, 'Niger');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (131, 'Nig�ria');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (132, 'Norv�gia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (133, 'Nyugat-Szahara');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (134, 'Nyugat-Szamoa');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (135, 'Olaszorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (136, 'Om�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (137, 'Oroszorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (138, '�rm�nyorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (139, 'Pakiszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (140, 'Palau');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (141, 'Panama');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (142, 'P�pa �j-Guinea');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (143, 'Paraguay');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (144, 'Peru');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (145, 'Portug�lia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (146, 'Rom�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (147, 'Ruanda');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (148, 'Saint Vincent �s Grenadines');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (149, 'Saint Kitts �s Nevis');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (150, 'Saint Lucia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (151, 'Salamon-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (152, 'Salvador');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (153, 'San Marino');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (154, 'Sao Tom� �s Princip�');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (155, 'Seychelles-szigetek');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (156, 'Sierra Leone');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (157, 'Spanyolorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (158, 'Sri Lanka');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (159, 'Suriname');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (160, 'Sv�jc');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (161, 'Sv�dorsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (162, 'Sza�d-Ar�bia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (163, 'Szeneg�l');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (164, 'Szerbia �s Montenegr�');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (165, 'Szingap�r');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (166, 'Sz�ria');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (167, 'Szlov�kia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (168, 'Szlov�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (169, 'Szom�lia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (170, 'Szud�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (171, 'Szv�zif�ld');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (172, 'T�dzsikiszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (173, 'Tajvan');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (174, 'Tanz�nia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (175, 'Thaif�ld');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (176, 'Togo');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (177, 'Tonga');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (178, 'T�r�korsz�g');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (179, 'Trinidad �s Tobago');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (180, 'Tun�zia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (181, 'Tuvalu');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (182, 'T�rkmeniszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (183, 'Uganda');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (184, '�j-Z�land');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (185, 'Ukrajna');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (186, 'Urugay');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (187, '�zbegiszt�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (188, 'Vanuatu');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (189, 'Vatik�n');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (190, 'Venezuela');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (191, 'Vietnam');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (192, 'Zambia');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (193, 'Zimbabwe');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Country` VALUES (194, 'Z�ld-foki K�zt�rsas�g');";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Users` (
			`user_id` INT NOT NULL ,
			`phone_mobile` VARCHAR( 50 ) NOT NULL ,
			`ship_address` INT NOT NULL ,
			`post_address` INT NOT NULL ,
		INDEX ( `user_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Users_Notreg` (
			`nuser_id` INT NOT NULL AUTO_INCREMENT,
			`user_name` VARCHAR( 255 ) NOT NULL ,
			`email` VARCHAR( 255 ) NOT NULL ,
			`phone_mobile` VARCHAR( 50 ) NOT NULL ,
			`ship_zipcode` VARCHAR( 20 ) NOT NULL ,
			`ship_city` VARCHAR( 255 ) NOT NULL ,
			`ship_country_id` INT NOT NULL ,
			`ship_address` VARCHAR( 255 ) NOT NULL ,
			`post_zipcode` VARCHAR( 20 ) NOT NULL ,
			`post_city` VARCHAR( 255 ) NOT NULL ,
			`post_country_id` INT NOT NULL ,
			`post_address` VARCHAR( 255 ) NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL DEFAULT '0' ,
			`activate` VARCHAR( 50 ) NOT NULL ,
			`add_date` DATETIME NOT NULL ,
		PRIMARY KEY ( `nuser_id` ),
		INDEX ( `ship_country_id`, `post_country_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Address` (
			`address_id` INT NOT NULL  auto_increment,
			`user_id` INT NOT NULL ,
			`country_id` INT NOT NULL ,
			`city` VARCHAR( 255 ) NOT NULL ,
			`zipcode` VARCHAR( 20 ) NOT NULL ,
			`address` VARCHAR( 255 ) NOT NULL ,
		PRIMARY KEY  (`address_id`),
		INDEX ( `user_id` , `country_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_State` (
			`state_id` INT NOT NULL  auto_increment,
			`state_name` VARCHAR( 255 ) NOT NULL ,
		PRIMARY KEY  (`state_id`)
		);
	";
	$mdb2->exec($query);

	$query = "INSERT INTO `iShark_Shop_State` VALUES (1, 'rakt�ron');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Shop_State` VALUES (2, 'rendel�sre');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Shop_State` VALUES (3, 'nincs k�szleten');";
	$mdb2->exec($query);
	$query = "INSERT INTO `iShark_Shop_State` VALUES (4, 'elfogyott');";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Actions` (
			`action_id` INT NOT NULL AUTO_INCREMENT ,
			`action_name` VARCHAR( 255 ) NOT NULL ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`timer_start` DATETIME NOT NULL ,
			`timer_end` DATETIME NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL ,
		PRIMARY KEY (`action_id`) ,
		INDEX (`add_user_id`, `mod_user_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Shop_Actions_Products` (
			`action_id` INT NOT NULL ,
			`product_id` INT NOT NULL ,
			`price` DECIMAL( 7, 2) NOT NULL ,
			`percent` TINYINT( 3 ) NOT NULL ,
		UNIQUE (`action_id`, `product_id`)
		);
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$_REQUEST['page']);
	exit;
} //telepites vege
*/

/**
 * ha toroljuk a modult
 */
/*
if ($act == "unins") {
	$query = "DROP TABLE IF EXISTS `iShark_Shop_Category`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Groups`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Products`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Products_Picture`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Products_Category`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Products_Category`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Products_Groups`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Category_Groups`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Properties`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Properties_Check`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Configs_Shipping`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Afa`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Basket`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Orders`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Orders_Finished`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Orders_Products`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Country`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Users`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Users_Notreg`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Address`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_State`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Actions`";
	$mdb2->exec($query);

	$query = "DROP TABLE IF EXISTS `iShark_Shop_Actions_Products`";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$_REQUEST['page']);
	exit;
} //eltavolitas vege
*/
?>