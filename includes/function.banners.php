<?php

/**
 * get_menus - men�pontok lek�rdez�se 
 * 
 * @param int $parent_id 
 * @access public
 * @return void
 */
function get_menus($parent=0, $path='')
{
	global $mdb2;

	$ret = array();

	$query = "
		SELECT menu_id, menu_name, parent FROM iShark_Menus 
		WHERE parent = $parent AND type = 'index' 
		ORDER BY position_id, sortorder
	";
	$result =& $mdb2->query($query);
	while ($row = $result->fetchRow()) {
		$ret[$row['menu_id']] = $path.(empty($path)?'':'/').$row['menu_name'];
		$gyerekek = get_menus($row['menu_id'],$path.(empty($path)?'':'/').$row['menu_name']);
		foreach($gyerekek as $key=>$value) {
			$ret[$key] = $value;
		}
	}
	return $ret;
}

/**
 * get_dimensions - Megjelenitesi meret kiirasa
 *
 * A fuggvenyt a smarty hasznalja a kepek fix 
 * megjelenitesi meretenek kiszamitasahoz.
 * 
 * @param mixed $params 
 * @param mixed $smarty 
 * @access public
 * @return string
 */
function get_dimensions($params, &$smarty) 
{
	$mw     = $_SESSION['site_banner_widths'];
	$mh     = $_SESSION['site_banner_heights'];
	$height = $params['height'];
	$width  = $params['width'];
	$xa     = ($width > 0  ? $mw / $width : 0);
	$ya     = ($height > 0 ? $mh / $height : 0);

	if ($height <= $mh && $width <= $mw) {
		$a_height = $height;
		$a_width  = $width;
	} elseif ($xa*$height < $mh) {
		$a_height = ceil($height * $xa);
		$a_width  = $mw;
	} else {
		$a_height = $mh;
		$a_width  = ceil($width * $ya);
	}

	return "width=\"$a_width\" height=\"$a_height\"";
}


function get_new_dim($width, $height, $mw, $mh) 
{
	$xa = ($width > 0  ? $mw / $width : 0);
	$ya = ($height > 0 ? $mh / $height : 0);

	if ($height <= $mh && $width <= $mw) {
		$a_height = $height;
		$a_width  = $width;
	} elseif ($xa*$height < $mh) {
		$a_height = ceil($height * $xa);
		$a_width  = $mw;
	} else {
		$a_height = $mh;
		$a_width  = ceil($width * $ya);
	}

	return array("width" => $a_width, "height" => $a_height);
} 

function get_banner_dimension($params, &$smarty)
{
    $mw = $params["max_width"];
    $mh = $params["max_height"];
    $width = $params["width"];
    $height = $params["height"];
    $dim = get_new_dim($width, $height, $mw, $mh);
    return "width=\"$dim[width]\" height=\"$dim[height]\"";
}


/**
 * letrehozza a bannerek javascript-es tombjet
 *
 * @return string
 */
function get_banners()
{
	global $mdb2;
    
	$bannerlist = "";

	//csak akkor futtatjuk, ha be van kapcsolva a modul
	if (isModule('banners', 'admin')) {
   		$query = "
   			SELECT 
   				b.banner_id AS bid, b.realname AS pic, b.type AS type, 
   				b.width as width, b.height as height, b.banner_code AS bcode,
   				p.max_width AS max_width, p.max_height AS max_height, 
   				bm.place_id AS pid, bm.menu_id AS mid 
   			FROM iShark_Banners b, iShark_Banners_Menus_Places bm, iShark_Banners_Places p 
   			WHERE b.banner_id = bm.banner_id AND bm.place_id = p.place_id AND 
   				(bm.timer_start = '0000-00-00 00:00:00' OR (bm.timer_start < NOW() AND bm.timer_end > NOW())) AND
				(bm.impression_max = 0 OR (bm.impression_max != 0 AND bm.impression_num < bm.impression_max))
   		";
   		//megvizsgaljuk, ha menuponthoz van kapcsolva
   		if (isset($_GET['mid'])) {
   			$mid = intval($_GET['mid']);
    
   			$query .= "
   				AND (bm.menu_id = $mid OR bm.menu_id = 0) 
   			";
   		} else {
   			$query .= "
   				AND bm.menu_id = 0 
   			";
   		}
        $query .= "
   			ORDER BY bm.place_id
   		";
        //ha nem rakjuk ki adott helyre az osszeset, cserelgetve
        if (empty($_SESSION['site_banner_type'])) {
            $query .= ", RAND()";
            $mdb2->setLimit(1);
        }
   		$result =& $mdb2->query($query);
   		$pid = 0;
   		if ($result->numRows() > 0) {
   			$i = 1;
   			while ($row = $result->fetchRow())
   			{
   				if ($pid !== $row['pid']) {
   					$pid = $row['pid'];
   					$i   = 0;
   					$bannerlist .= "bid[$pid] = new Array();\n";
   					$bannerlist .= "pid[$pid] = new Array();\n";
   					$bannerlist .= "mid[$pid] = new Array();\n";
   					$bannerlist .= "pic[$pid] = new Array();\n";
   					$bannerlist .= "width[$pid] = new Array();\n";
   					$bannerlist .= "height[$pid] = new Array();\n";
   					$bannerlist .= "type[$pid] = new Array();\n";
   					$bannerlist .= "reload[$pid] = new Array();\n";
   					$bannerlist .= "code[$pid] = new Array();\n\n";
   				}
   				$dims = get_new_dim($row['width'], $row['height'], $row['max_width'], $row['max_height']);
   				$bannerlist .= "bid[$pid][$i]='".$row['bid']."';\n";
   				$bannerlist .= "pid[$pid][$i]='".$row['pid']."';\n";
   				$bannerlist .= "mid[$pid][$i]='".$row['mid']."';\n";
    		    $bannerlist .= "pic[$pid][$i]='".$_SESSION['site_bannerdir']."/".$row['pic']."';\n";
   				$bannerlist .= "width[$pid][$i]='".$dims['width']."';\n";
   				$bannerlist .= "height[$pid][$i]='".$dims['height']."';\n";
   				$bannerlist .= "type[$pid][$i]='".$row['type']."';\n";
   				$bannerlist .= "reload[$pid][$i]='".$_SESSION['site_banner_reload']."';\n";
   				if ($row['bcode'] == "") {
   				    $bannerlist .= "code[$pid][$i]='';\n\n";
   				} else {
       				$newlines = array("\r\n", "\n", "\r");
       				$step1 = str_replace($newlines, "", $row['bcode']);
       				$step2 = str_replace("'", "\'", $step1);
       				$step3 = str_replace('script', "scr'+'ipt", $step2);
       				$bannerlist .= "code[$pid][$i]='".$step3."';\n\n";
   				}
   				$i++;
   			}
   		}
	}
	return $bannerlist;
}

function get_banners2()
{
	global $mdb2, $tpl
	;
    $tpl->register_function("redim_banner", "get_banner_dimension");
    
	$bannerlist = "";

	//csak akkor futtatjuk, ha be van kapcsolva a modul
	if (!isModule('banners', 'admin')) {
	    return array();
	}
   	$query = "
   		SELECT 
   			b.banner_id AS banner_id, b.realname AS pic, b.type AS type, 
   			b.width as width, b.height as height, b.banner_code AS banner_code,
   			p.max_width AS max_width, p.max_height AS max_height, 
   			bm.place_id AS place_id, bm.menu_id AS menu_id, b.name as name
   		FROM iShark_Banners b, iShark_Banners_Menus_Places bm, iShark_Banners_Places p 
   		WHERE b.banner_id = bm.banner_id AND bm.place_id = p.place_id AND 
   			(bm.timer_start = '0000-00-00 00:00:00' OR (bm.timer_start < NOW() AND bm.timer_end > NOW())) AND
			(bm.impression_max = 0 OR (bm.impression_max != 0 AND bm.impression_num < bm.impression_max))
   	";
   	//megvizsgaljuk, ha menuponthoz van kapcsolva
   	if (isset($_GET['mid'])) {
   		$mid = intval($_GET['mid']);
    	$query .= "
   			AND (bm.menu_id = $mid OR bm.menu_id = 0) 
   		";
   	} else {
   		$query .= "
   			AND bm.menu_id = 0 
   		";
   	}
    $query .= "
   		ORDER BY bm.place_id
   	";
    //ha nem rakjuk ki adott helyre az osszeset, cserelgetve
    /*if (empty($_SESSION['site_banner_type'])) {
        $query .= ", RAND()";
        $mdb2->setLimit(1);
    }*/
    $result =& $mdb2->query($query);
    $banners = array();
    
    while ($row = $result->fetchRow()) {
        $banners[$row["place_id"]][] = $row;
    }
    return $banners;
}


/**
 * Ellenorzi, hogy a megadott nevvel letezik-e bannerhely
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_bannerplace_add($values)
{
	global $mdb2, $locale;

	$errors = array();
	$name   = $mdb2->escape($values['placename']);

	$query = "
		SELECT place_id 
		FROM iShark_Banners_Places 
		WHERE place_name = '".$name."'
	";
	$result = $mdb2->query($query);
	if ($result->numRows() != 0) {
		$errors['placename'] = $locale->get('error_system_place_exists');
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
 function check_bannerplace_mod($values)
 {
	 global $mdb2, $locale;

	 $errors = array();
	 $name   = $mdb2->escape($values['placename']);

	 $query = "
	 	SELECT place_id 
		FROM iShark_Banners_Places 
		WHERE place_name = '".$name."' AND place_id != ".$values['pid']."
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$errors['placename'] = $locale->get('error_system_place_exists');
	}

	return empty($errors) ? true: $errors;
}

?>