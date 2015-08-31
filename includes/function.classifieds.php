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
	} elseif ($lang == '0') {
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
		FROM iShark_Classifieds_Category c 
		LEFT JOIN iShark_Users u1 ON u1.user_id = c.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = c.mod_user_id 
		WHERE c.parent = $parent $query_lang $query_active
		ORDER BY c.sortorder, c.parent, c.lang
	";
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$query2 = "
			SELECT parent 
			FROM iShark_Classifieds_Category 
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
			FROM iShark_Classifieds_Category c 
			WHERE c.category_id = $id 
			ORDER BY c.sortorder, c.parent, c.lang
		";
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
 * get_classifieds_category - Rekurz�v men�k�r�s
 * 
 * @param int $parent 
 * @param string $path 
 * @access public
 * @return void
 */
function get_classifieds_category($parent = 0, $path = '')
{
	global $mdb2;

	$ret = "";

	$query = "
		SELECT category_id, parent, category_name 
		FROM iShark_Classifieds_Category 
		WHERE parent = $parent
		ORDER BY sortorder
	";
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow()) {
		$ret .= ''.$row['category_id'].', '.(empty($path) ? '' : $path.'/').htmlspecialchars($row['category_name']).';';
		$ret .= get_classifieds_category($row['category_id'], (empty($path) ? '' : $path.'/').htmlspecialchars($row['category_name']));
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
function check_classifieds_addcategory($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT category_id 
		FROM iShark_Classifieds_Category 
		WHERE category_name = '".$name."' AND parent = ".$values['par']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('category_error_category_existsname');
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
function check_classifieds_modcategory($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['name']);

	$query = "
		SELECT category_id 
		FROM iShark_Classifieds_Category 
		WHERE category_name = '".$name."' AND parent = ".$values['par']." AND category_id != ".$values['cid']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['name'] = $locale->get('category_error_category_existsname');
	}

	return empty($errors) ? true: $errors;
}

/**
 * get_classifieds_breadcrumb_category - Rekurz�v men�k�r�s - visszafele
 * 
 * @param int $parent 
 * @access public
 * @return void
 */
function get_classifieds_breadcrumb_category($parent)
{
	global $mdb2;

	$ret = "";

	$query = "
		SELECT category_id, parent, category_name 
		FROM iShark_Classifieds_Category 
		WHERE category_id = $parent AND category_id != 0
	";
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow()) {
		$ret .= ''.$row['category_id'].'#@#'.htmlspecialchars($row['category_name']).';';
		$ret .= get_classifieds_breadcrumb_category($row['parent']);
	}
	return $ret;
}

/**
 * Ellenorzi, hogy a megadott aktivalo kod az jo-e az adott hirdeteshez
 *
 * @param	array	a formban szereplo mezok
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_activatecode($values)
{
	global $mdb2, $locale;

	$query = "
		SELECT *
		FROM iShark_Classifieds_Advert a 
		WHERE a.is_active = '1' AND a.advert_id = ".$values['aid']." AND a.gen_code = '".$values['act_code']."'
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() == 0) {
		$errors['act_code'] = $locale->get('error_wrong_actcode');
	}

	return empty($errors) ? true: $errors;
}

?>
