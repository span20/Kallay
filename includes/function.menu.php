<?php

include_once 'functions.php';

$aktiv_menuk = aktiv_menuk();

/**
 * menu - Visszaadja a menuspositions (menupos) id szerint 
 * a menupontokat almenuivel egyutt. 
 *
 * Amennyiben a $menupos parameter erteke 0, 
 * akkor a teljes menuszerkezetet kerdezi le.
 * Az $onlyactive paramterrel TRUE esetn 
 * csak az aktiv menupontok alszintjeit
 * olvassa be, FALSE eseten pedig azokat is, 
 * amelyek fo aga nem lett kivalasztva, magyarul 
 * a menupoziciohoz tartozo teljes fastrukturat.
 * $type vagy index vagy admin lehet, attol fuggeen, 
 * hogy melyik reszen szeretnenk a menut lekerdezni.
 * ha nem adunk meg semmit, akkor a teljes menut lekerdezi.
 *
 * @param int $menupos 
 * @param boolean $onlyactive 
 * @param int $parent 
 * @param int $level 
 * @param mixed $lang 
 * @param mixed $type
 * @param int $active - csak az aktiv menuk (1) vagy az inaktivak is (0)
 *
 * @access public
 *
 * @return void
 */
function menu($menupos, $onlyactive = TRUE, $parent = 0, $level = 1, $lang = NULL, $type = NULL, $active = 1)
{
	global $mdb2, $aktiv_menuk;

	//nyelvnel a lekerdezeshez szukseges mezo
	if ($lang == NULL) {
		$query_lang = " AND m.lang = '".$_SESSION['site_lang']."'";
	} elseif ($lang == "all") {
		$query_lang = "";
	} else {
		$query_lang = " AND m.lang = '$lang'";
	}

	$query_pos = ($menupos != '0' && $level == 1) ? "AND m.position_id='$menupos'" : "";

	if  (isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == '1') {
		$query_pos .= " AND (m.timer_start = '0000-00-00 00:00:00' OR (m.timer_start<NOW() AND m.timer_end>NOW()))";
	}

	//tipus meghatarozas
	$query_type = ($type != NULL && ($type == "index" || $type == "admin")) ? " AND m.type = '$type'" : "";

	//ativ meghatarozasa
	if ($active == 1) {
		$query_active = " AND m.is_active= 1 ";
	} else {
		$query_active = "";
	}

	$menuk = array();
	$i = 0;
	$query = "
		SELECT m.menu_id AS menu_id, m.menu_name AS menu_name, m.parent AS parent, mp.position_name AS posname, m.type AS mtype, 
			m.lang AS mlang, m.is_active AS isact, m.is_protected AS mprot, m.position_id AS posid, mo.module_name AS moname, 
			c.title AS ctitle, m.link AS mlink, cat.category_name AS catname, m.open_in_new_window AS mblank, m.menu_color 
		FROM iShark_Menus_Positions mp, iShark_Menus m
		LEFT JOIN iShark_Modules mo ON mo.module_id = m.module_id 
		LEFT JOIN iShark_Contents c ON c.content_id = m.content_id 
		LEFT JOIN iShark_Category cat ON cat.category_id = m.category_id 
		WHERE m.parent = '$parent' $query_active $query_pos AND mp.position_id = m.position_id
			".$query_lang." ".$query_type."
		ORDER BY m.position_id, m.sortorder
	";
	
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
	    // csoport ellenorzese, ha nem egyezik le sem fut a tobbi
		if (!empty($_SESSION['user_id'])) {
		    $usid = $_SESSION['user_id'];
		} else {
		    $usid = 0;
		}

		$azonos = 0;
		$query3 = "
			SELECT * 
			FROM iShark_Menus_Groups 
			WHERE menu_id = ".$row['menu_id']."
		";
		$result3 =& $mdb2->query($query3);
		if ($result3->numRows() > 0) {
			//lekerdezzuk az user csoportjait
			$query4 = "
				SELECT group_id 
				FROM iShark_Groups_Users 
				WHERE user_id = $usid
			";
			$result4 = $mdb2->query($query4);
			$azonos = 1;
			while ($row4 = $result4->fetchRow())
			{
				$g_id = $row4['group_id'];
				//lekerdezzuk, hogy az adott csoporthoz tartozik-e engedely a menuhoz
				$query5 = "
					SELECT * 
					FROM iShark_Menus_Groups 
					WHERE menu_id = ".$row['menu_id']." AND group_id = $g_id
				";
				$result5 = $mdb2->query($query5);
				if (($result5->numRows() > 0 ) || ($g_id == $_SESSION['site_sys_prefgroup'])) {
				    $azonos = 0;
				}
			}
		}

		if ($azonos != 1) {
    		$query2 = "
    			SELECT parent 
    			FROM iShark_Menus 
    			WHERE parent = '".$row['menu_id']."'
    		";
    		$result2 = $mdb2->query($query2);
    
    		$aktiv = isset($aktiv_menuk[$row['menu_id']]);
    		$almenuk = array();
    		if ($aktiv || !$onlyactive) {
    			$almenuk = menu($menupos, $onlyactive, $row['menu_id'], $level+1, $lang, $type, $active);
    		}
    		if ($result2->numRows() > 0){
    			$menuk[$i]['is_sub'] = '1';
    		}
    		
    		$hunnev = change_hunchar($row['menu_name']);
    		//$hunnev = eregi_replace(" ", "", $hunnev);
    		
    		$menuk[$i]['menu_id']   = $row['menu_id'];
    		$menuk[$i]['menu_name'] = $row['menu_name'];
    		$menuk[$i]['menu_name_no'] = $hunnev;		
    		$menuk[$i]['level']     = $level;
    		$menuk[$i]['posname']   = $row['posname'];
    		$menuk[$i]['menu_color']   = $row['menu_color'];
    		$menuk[$i]['parent']    = $row['parent'];
    		$menuk[$i]['mtype']     = $row['mtype'];
    		$menuk[$i]['mlang']     = $row['mlang'];
    		$menuk[$i]['isact']     = $row['isact'];
    		$menuk[$i]['mprot']     = $row['mprot'];
    		$menuk[$i]['posid']     = $row['posid'];
    		$menuk[$i]['moname']    = $row['moname'];
    		$menuk[$i]['ctitle']    = $row['ctitle'];
    		$menuk[$i]['mlink']     = $row['mlink'];
    		$menuk[$i]['catname'] 	= $row['catname'];
    		$menuk[$i]['mblank'] 	= $row['mblank'];
    		if (!empty($almenuk)) {
    			$menuk[$i]['element'] = $almenuk;
    		}
    		$i++;
    	}
	}

	return $menuk;
}

function aktiv_menuk() 
{
	global $mdb2;

	$a = array();
	$a['path'] = '';
	$a['is_protected'] = FALSE ;

	if (isset($_GET['mid'])) {
		$mid = intval($_GET['mid']);
	} else {
		return $a;
	}

	$id = (int) $mid ;
	$a[$id]=TRUE;
	while ($id > 0) {
		$query = "
			SELECT m.menu_id AS menu_id, m.parent AS parent, m.menu_name AS menu_name, m.is_protected AS is_protected, 
				mp.position_name AS posname 
			FROM iShark_Menus m, iShark_Menus_Positions mp 
			WHERE m.menu_id = '$id' AND mp.position_id = m.position_id AND m.type = 'index'
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() != 0) {
			while ($sor = $result->fetchRow())
			{
				if ($sor['is_protected']=='1') {
					$a['is_protected'] = TRUE ;
				}
				$id = $sor['parent'] ;
				$a[$id]= TRUE;
				if ($sor['menu_id'] != (int)$mid || $sor['parent']==0) {
					$a['path'] = $sor['menu_name'].(!empty($a['path'])?' - ' : '').$a['path'] ;
				}
			}
		} else {
			$id = 0;
		}
	}
	return $a;
}

?>
