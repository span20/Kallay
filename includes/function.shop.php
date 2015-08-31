<?php

include_once 'functions.php';

$aktiv_categories = aktiv_categories();

/**
 * menu - Visszaadja a menuspositions (menupos) id szerint 
 * a men�pontokat almen�ivel egy�tt. 
 *
 * Amennyiben a $menupos param�ter �rt�ke 0, 
 * akkor a teljes men�szerkezetet k�rdezi le.
 * Az $onlyactive param�terrel TRUE eset�n 
 * csak az akt�v men�pontok alszintjeit
 * olvassa be, FALSE eset�n pedig azokat is, 
 * amelyek f� �ga nem lett kiv�lasztva, magyarul 
 * a menupozici�hoz tartoz� teljes fastrukt�r�t.
 * $type vagy index vagy admin lehet, att�l f�gg�en, 
 * hogy melyik r�szen szeretn�nk a men�t lek�rdezni.
 * ha nem adunk meg semmit, akkor a teljes men�t lek�rdezi.
 *
 * @param boolean $onlyactive 
 * @param int $parent 
 * @param mixed $lang 
 * @param int $active - csak az aktiv menuk (1) vagy az inaktivak is (0)
 *
 * @access public
 *
 * @return void
 */
function categories($onlyactive = TRUE, $parent = 0, $level = 1, $lang = NULL, $active = 1)
{
	global $mdb2, $aktiv_categories;

	//nyelvnel a lekerdezeshez szukseges mezo
	if ($lang == NULL) {
		$query_lang = " AND c.lang = '".$_SESSION['site_lang']."'";
	} elseif ($lang == "all") {
		$query_lang = "";
	} else {
		$query_lang = " AND c.lang = '$lang'";
	}

	//aktiv meghatarozasa
	if ($active == 1) {
		$query_active = " AND c.is_active= 1 ";
	} else {
		$query_active = "";
	}

	$menuk      = array();
	$categories = array();
	$i = 0;
	$query = "
		SELECT c.category_id AS cid, c.category_name AS cname, c.category_desc AS cdesc, c.parent AS cparent, c.is_active AS isact, 
			c.lang AS clang, u1.name AS adduser, c.add_date AS adddate, u2.name AS moduser, c.mod_date AS moddate 
		FROM iShark_Shop_Category c 
		LEFT JOIN iShark_Users u1 ON u1.user_id = c.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = c.mod_user_id 
		WHERE c.parent = $parent $query_lang $query_active
	";
	//ha ABC szerint rendezzuk sorba
	if ($_SESSION['site_shop_ordertype'] == 1) {
		$query .= "
			ORDER BY c.category_name, c.parent, c.lang
		";
	}
	//ha egyedi sorrend szerint rendezzuk
	if ($_SESSION['site_shop_ordertype'] == 2) {
		$query .= "
			ORDER BY c.sortorder, c.parent, c.lang
		";
	}
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$query2 = "
			SELECT parent 
			FROM iShark_Shop_Category 
			WHERE parent = ".$row['cid']."
		";
		$result2 = $mdb2->query($query2);

		$aktiv = isset($aktiv_categories[$row['cid']]);
		$almenuk = array();
		if ($aktiv || !$onlyactive) {
			$almenuk = categories($onlyactive, $row['cid'], $level+1, $lang, $active);
		}
		if ($result2->numRows() > 0){
			$categories[$i]['is_sub'] = '1';
		}
		$categories[$i]['title']   = $row['cname'];
		$categories[$i]['clang']   = $row['clang'];
		$categories[$i]['level']   = $level;
		$categories[$i]['cid']     = $row['cid'];
		$categories[$i]['cparent'] = $row['cparent'];
		$categories[$i]['isact']   = $row['isact'];
		$categories[$i]['ausr']    = $row['adduser'];
		$categories[$i]['adate']   = $row['adddate'];
		$categories[$i]['musr']    = $row['moduser'];
		$categories[$i]['mdate']   = $row['moddate'];
		if (!empty($almenuk)) {
			$categories[$i]['element'] = $almenuk;
		}
		$i++;
	}

	return $categories;
}

function aktiv_categories()
{
	global $mdb2;

	$a = array();
	$a['path'] = '';

	if (isset($_GET['cid'])) {
		$cid = intval($_GET['cid']);
	} else {
		return $a;
	}

	$id = (int) $cid ;
	$a[$id]=TRUE;
	while ($id > 0) {
		$query = "
			SELECT c.category_id AS cid, c.category_name AS cname, c.parent AS cparent, c.is_active AS isact 
			FROM iShark_Shop_Category c 
			WHERE c.category_id = $id 
		";
		//ha ABC szerint rendezzuk sorba
		if ($_SESSION['site_shop_ordertype'] == 1) {
			$query .= "
				ORDER BY c.category_name, c.parent, c.lang
			";
		}
		//ha egyedi sorrend szerint rendezzuk
		if ($_SESSION['site_shop_ordertype'] == 2) {
			$query .= "
				ORDER BY c.sortorder, c.parent, c.lang
			";
		}
		$result =& $mdb2->query($query);
		if ($result->numRows() != 0) {
			while ($sor = $result->fetchRow())
			{
				$id = $sor['cparent'];
				$a[$id] = TRUE;
				if ($sor['cid'] != (int)$cid || $sor['cparent']==0) {
					$a['path'] = $sor['cname'].(!empty($a['path']) ? ' - ' : '').$a['path'];
				}
			}
		} else {
			$id = 0;
		}
	}
	return $a;
}

/**
 * get_category - Rekurz�v men�k�r�s
 * 
 * @param int $parent 
 * @param string $path 
 * @access public
 * @return void
 */
function get_category($parent = 0, $path = '')
{
	global $mdb2;

	$ret = "";

	$query = "
		SELECT category_id, parent, category_name 
		FROM iShark_Shop_Category 
		WHERE parent = $parent
	";
	//ha ABC szerint rendezzuk sorba
	if ($_SESSION['site_shop_ordertype'] == 1) {
		$query .= "
			ORDER BY category_name
		";
	}
	//ha egyedi sorrend szerint rendezzuk
	if ($_SESSION['site_shop_ordertype'] == 2) {
		$query .= "
			ORDER BY sortorder
		";
	}
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow()) {
		$ret .= ''.$row['category_id'].', '.(empty($path) ? '' : $path.'/').htmlspecialchars($row['category_name']).';';
		$ret .= get_category($row['category_id'], (empty($path) ? '' : $path.'/').htmlspecialchars($row['category_name']));
	}
	return $ret;
}

/**
 * get_breadcrumb_category - Rekurz�v men�k�r�s - visszafele
 * 
 * @param int $parent 
 * @access public
 * @return void
 */
function get_breadcrumb_category($parent)
{
	global $mdb2;

	$ret = "";

	$query = "
		SELECT category_id, parent, category_name 
		FROM iShark_Shop_Category 
		WHERE category_id = $parent AND category_id != 0
	";
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow()) {
		$ret .= ''.$row['category_id'].'#@#'.htmlspecialchars($row['category_name']).';';
		$ret .= get_breadcrumb_category($row['parent']);
	}
	return $ret;
}

/**
 * Ellenorzi, hogy hozzaadasnal a megadott neven letezik-e a kategoria
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_addcategory($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT category_id 
		FROM iShark_Shop_Category 
		WHERE category_name = '".$name."' AND parent = ".$values['par']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_category_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy modositasnal a megadott neven letezik-e a kategoria
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_modcategory($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT category_id 
		FROM iShark_Shop_Category 
		WHERE category_name = '".$name."' AND parent = ".$values['par']." AND category_id != ".$values['cid']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_category_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy hozzaadasnal a megadott neven letezik-e a csoport
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_addgroups($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT group_id 
		FROM iShark_Shop_Groups 
		WHERE group_name = '".$name."'
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_group_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy modositasnal a megadott neven letezik-e a csoport
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_modgroups($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT group_id 
		FROM iShark_Shop_Groups 
		WHERE group_name = '".$name."' AND group_id != ".$values['gid']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_group_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy hozzaadasnal a megadott neven letezik-e a termek
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_addproduct($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT item_id 
		FROM iShark_Shop_Products 
		WHERE item_id = '".$values['item']."'
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['item'] = $locale->get('admin_shop', 'functions_error_items_exists');
	}

	$query = "
		SELECT product_id 
		FROM iShark_Shop_Products 
		WHERE product_name = '".$name."'
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_products_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy modositasnal a megadott neven letezik-e a termek
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_modproduct($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT item_id 
		FROM iShark_Shop_Products 
		WHERE item_id = '".$values['item']."' AND product_id != ".$values['pid']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['item'] = $locale->get('admin_shop', 'functions_error_items_exists');
	}

	$query = "
		SELECT product_id 
		FROM iShark_Shop_Products 
		WHERE product_name = '".$name."' AND product_id != ".$values['pid']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_products_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy van-e valami a kosarban
 *
 * @return void
 */
function is_empty_basket()
{
	global $mdb2;

	$query = "
		SELECT * 
		FROM iShark_Shop_Basket b 
		WHERE 
	";
	if (isset($_SESSION['user_id']) || session_id()) {
		$query .= " (";
		if (isset($_SESSION['user_id'])) {
			$query .= "b.user_id = ".$_SESSION['user_id']." ";
			if (session_id()) {
				$query .= " OR ";
			}
		}
		if (session_id()) {
			$query .= "(b.session_id = '".session_id()."' AND b.user_id = '')";
		}
		$query .= ")";
	}
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		return false;
	} else {
		return true;
	}
}

/**
 * Ellenorzi, hogy ne legyen ket ugyan olyan cim
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_add_address($values)
{
	global $mdb2, $locale;

	$errors   = array();
	$shipcity = $mdb2->escape($values['shipcity']);
	$shipzip  = $mdb2->escape($values['shipzip']);
	$shipaddr = $mdb2->escape($values['shipaddr']);

	//ha van ilyen user
	if (isset($_SESSION['user_id'])) {
		$query = "
			SELECT address_id 
			FROM iShark_Shop_Address 
			WHERE city = '".$shipcity."' AND zipcode = '".$shipzip."' 
				AND address = '".$shipaddr."' AND user_id = ".$_SESSION['user_id']."
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			$errors['shipzip'] = $locale->get('index_shop', 'functions_error_address_exists');
		}

		return empty($errors) ? true: $errors;
	}
	//ha nincs, akkor visszadobjuk a kosarhoz
	else {
		header('Location: index.php?p=shop&act=bsk');
		exit;
	}
}

/**
 * Ellenorzi, hogy ne legyen ket ugyan olyan cim
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_mod_address($values)
{
	global $mdb2, $locale;

	$errors   = array();
	$shipcity = $mdb2->escape($values['shipcity']);
	$shipzip  = $mdb2->escape($values['shipzip']);
	$shipaddr = $mdb2->escape($values['shipaddr']);

	//ha van ilyen user
	if (isset($_SESSION['user_id'])) {
		$query = "
			SELECT address_id 
			FROM iShark_Shop_Address 
			WHERE city = '".$shipcity."' AND zipcode = '".$shipzip."' 
				AND address = '".$shipaddr."' AND user_id = ".$_SESSION['user_id']."
				AND address_id != ".$values['aid']."
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			$errors['shipzip'] = $locale->get('index_shop', 'functions_error_address_exists');
		}

		return empty($errors) ? true: $errors;
	}
	//ha nincs, akkor visszadobjuk a kosarhoz
	else {
		header('Location: index.php?p=shop&act=bsk');
		exit;
	}
}

/**
 * Ellenorzi, hogy ne legyen ket ugyanolyan nevu akcio - hozzaadasnal
 */
function check_addaction($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT * 
		FROM iShark_Shop_Actions 
		WHERE action_name = '".$name."'
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_actions_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy ne legyen ket ugyanolyan nevu akcio - modositasnal
 */
function check_modaction($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT * 
		FROM iShark_Shop_Actions 
		WHERE action_name = '".$name."' AND action_id != ".$values['aid']."
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('admin_shop', 'functions_error_actions_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * checkShopExtraFields - ellenorzi, hogy ne legyen ket ugyanolyan nevu mezo a tablaban
 * 
 * @param	array	a formban szereplo mezoket tartalmazza
 * 
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function checkShopExtraFields($values)
{
    global $mdb2, $locale;

    $errors = array();

    $query = "
		DESCRIBE iShark_Shop_Products
	";
    $result =& $mdb2->query($query);
    while ($row = $result->fetchRow())
    {
        if ($row['field'] == $values['value']) {
            $errors['value'] = $locale->get('admin_shop', 'system_error_value_exists');
        }
    }

    return empty($errors) ? true: $errors;
}
?>