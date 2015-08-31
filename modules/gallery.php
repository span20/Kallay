<?php

if (!eregi('index.php', $_SERVER['PHP_SELF'])) {
	die('Közvetlenül nem lehet hozzáférni a fájlhoz!');
}

$module_name = "gallery";

//nyelvi file
$locale->useArea("index_".$module_name);

// Visszatalálás
if (isset($_REQUEST['mid'])) {
	$self = "mid=".intval($_REQUEST['mid']);
} else {
	$self = "p=gallery";
}
$tpl->assign('self', $self);


// Lehetséges mûveletek
$is_act = array('gallery_lst', 'gallery_plst', 'gallery_view', 'gallery_dwn');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = 'gallery_lst';
}

$gid = 0;
if (isset($_REQUEST['gid'])) {
	$gid = intval($_REQUEST['gid']);

	$query  = "
		SELECT * 
		FROM iShark_Galleries 
		WHERE gallery_id = $gid
	";
	$result =& $mdb2->query($query);
	if (!$gallery_data = $result->fetchRow()) {
		$acttpl = 'error';
		$tpl->assign('errormsg', $strGalleryNotExists);
		return;
	}
	$tpl->assign('gallery_data', $gallery_data);
}

/**
 * ha a videot akarjuk letolteni
 */
if ($act == "gallery_dwn" && isset($_GET['pid']) && is_numeric($_GET['pid']) && !empty($_SESSION['site_gallery_is_video'])) {
	$pid = intval($_GET['pid']);

	$query = "
		SELECT name, realname 
		FROM iShark_Pictures 
		WHERE picture_id = $pid AND is_download = 1
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		return;
	} else {
		$row = $result->fetchRow();

		$name     = $row['name'];
		$filename = $row['realname'];
		//kiszedjuk a kiterjesztest
		$ext = explode(".", $row['realname']);

		$mime = 'application/octet-stream';
		header("Content-type: $mime");
		header('Content-Disposition: attachment; filename="'.$name.'.'.$ext['1'].'"');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		readfile($_SESSION['site_galerydir']."/".$filename);
		exit;
	}
} //video letoltes vege

/**
 * Videó
 **/
if ($act == 'gallery_view' && !empty($_SESSION['site_gallery_is_video'])){
	if (isset($_REQUEST['vid']) && is_numeric($_REQUEST['vid'])) {
		$vid = intval($_REQUEST['vid']);

		if ($gid == 0) {
			 $gidlek = "
				SELECT gallery_id 
				FROM iShark_Galleries_Pictures 
				WHERE picture_id = $vid
			 ";
			 $giderr = $mdb2->query($gidlek);
			 $gidrow = $giderr->fetchRow();
			 $gid = $gidrow['gallery_id'];
		}

		//ebbol a galeriabol veletlenszeruen videok
		$query5 = "
			SELECT p.picture_id, p.name, p.realname, gp.gallery_id, p.is_download 
			FROM iShark_Galleries as g
			LEFT JOIN iShark_Galleries_Pictures gp ON g.gallery_id = gp.gallery_id
			LEFT JOIN iShark_Pictures p ON gp.picture_id = p.picture_id
			WHERE g.type = 'v' AND p.picture_id != $vid AND g.gallery_id = $gid
			ORDER BY rand()
		";
		$mdb2->setLimit(3);
		$result5 = $mdb2->query($query5);
		while ($row5 = $result5->fetchRow()) {
			if (file_exists("files/gallery/".$row5['realname'])) {
				$filesize = filesize("files/gallery/".$row5['realname']);
				$fileMb   = ($filesize/1024)/1024;
			}

			$videos_actgal[] = array(
				'vidid'    => $row5['picture_id'],
				'vidname'  => $row5['name'],
				'vidreal'  => $row5['realname'],
				'galid'    => $row5['gallery_id'],
				'download' => $row5['is_download'],
				'filesize' => number_format($fileMb, '3', ',', '')
			);
		}
		if ($result5->numRows()){
			$tpl->assign('videos_actgal', $videos_actgal);
		}

		//tobbi galeriabol veletlenszeruen videok
		$query6 = "
			SELECT p.picture_id, p.name, p.realname, gp.gallery_id, p.is_download
			FROM iShark_Galleries AS g
			LEFT JOIN iShark_Galleries_Pictures AS gp ON g.gallery_id = gp.gallery_id 
			LEFT JOIN iShark_Pictures AS p ON gp.picture_id = p.picture_id
			WHERE g.type = 'v' AND p.picture_id != $vid AND g.gallery_id != $gid
			ORDER BY rand()
		";
		$mdb2->setLimit(3);
		$result6 = $mdb2->query($query6);
		while ($row6 = $result6->fetchRow()) {
			if (file_exists("files/gallery/".$row6['realname'])){
				$filesize = filesize("files/gallery/".$row6['realname']);
				$fileMb   = ($filesize/1024)/1024;
			}

			$videos_othgal[] = array(
				'vidid'    => $row6['picture_id'],
				'vidname'  => $row6['name'],
				'vidreal'  => $row6['realname'],
				'galid'    => $row6['gallery_id'],
				'download' => $row5['is_download'],
				'filesize' => number_format($fileMb, '3', ',', '')
			);
		}
		if ($result6->numRows()) {
			$tpl->assign('videos_othgal', $videos_othgal);
		}

		// aktualis video letoltheto-e
		$query = "
			SELECT picture_id, realname, name, description, is_download
			FROM iShark_Pictures 
			WHERE picture_id = $vid
		";
		$result = $mdb2->query($query);
		$row = $result->fetchRow();
	    $is_download = array();
	    if ($result->numRows() > 0) {
	        $is_download['video_id']   = $row['picture_id'];
	        $is_download['video_file'] = $row['realname'];
	        $is_download['video_name'] = $row['name'];
	        $is_download['video_desc'] = $row['description'];
	        $is_download['video_down'] = $row['is_download'];

	        $tpl->assign('video', $is_download);
	    }

		$acttpl = 'gallery_view_video';
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $strGalleryNotExists);
		return;
	 }
}

/**
 * Képek listája 
 */
if ($act == 'gallery_plst') {
    
    $gid = 1;
    
    if (empty($_REQUEST['page'])) {
        $page_start = 0;
    } else {
        $page_start = $_REQUEST['page']*9;
    }
    
    if (empty($_REQUEST['section'])) {
        $section = 1;
        $section_minus = 0;
        $section_plus = 2;
    } else {
        $section = $_REQUEST['section'];
        $section_minus = $_REQUEST['section']-1;
        $section_plus = $_REQUEST['section']+1;
    }
    $tpl->assign('section',  $section);
    $tpl->assign('section_minus',  $section_minus);
    $tpl->assign('section_plus',  $section_plus);
    
	$query = "
		SELECT GP.picture_id AS picture_id, P.realname AS realname, P.name AS name, P.width AS width, P.height AS height,
			P.tn_width AS tn_width, P.tn_height	AS tn_height
		FROM iShark_Galleries_Pictures GP, iShark_Pictures P
		WHERE GP.gallery_id = $gid AND P.picture_id = GP.picture_id
        ORDER BY GP.orders
        LIMIT ".$page_start.", 9
	";
    
    $result = $mdb2->query($query);
    $pager_list = $pic_list = $result->fetchAll();
    $tpl->assign('pd_gallery',  $pic_list);
    
    $query = "
		SELECT COUNT(GP.picture_id) AS all_pics
		FROM iShark_Galleries_Pictures GP, iShark_Pictures P
		WHERE GP.gallery_id = $gid AND P.picture_id = GP.picture_id
	";
    $result = $mdb2->query($query);
    
    $all_pics = $result->fetchRow();
    $all_pages = ceil($all_pics['all_pics'] / 9);
    $all_sections = ceil($all_pages / 10);
    $tpl->assign('all_pages', $all_pages);
    $tpl->assign('all_sections', $all_sections);
    $tpl->assign('gallery_pages', $page_start);
    
    //WHERE GP.gallery_id = $gid AND P.picture_id = GP.picture_id

	// Kiválasztott kép
	/*if (isset($_REQUEST['pid'])) {
		$pid			= (int) $_REQUEST['pid'];
		$pic_query		= $query." AND GP.picture_id = $pid ORDER BY GP.picture_id";
		// ELõzõ és következõ kiszámítása
		$elozo_q		= $query." AND GP.picture_id < $pid ORDER BY GP.picture_id DESC";
		$kovetkezo_q	= $query." AND GP.picture_id > $pid ORDER BY GP.picture_id";
		$mdb2->setLimit(1);
		$elozo			=& $mdb2->query($elozo_q);
		if ($elo = $elozo->fetchRow()) {
			$tpl->assign('prev', $elo['picture_id']);
		}
		$mdb2->setLimit(1);
		$kovetkezo		=& $mdb2->query($kovetkezo_q);
		if ($kov = $kovetkezo->fetchRow()) {
			$tpl->assign('next', $kov['picture_id']);
		}
	} else {
		$pic_query		= $query.' ORDER BY GP.picture_id LIMIT 1';
		$kovetkezo_q	= $query.' ORDER BY GP.picture_id';
		$mdb2->setLimit(1,1);
		$kov_res		=& $mdb2->query($kovetkezo_q);
		// Következõ kiszámítása
		if ($kov = $kov_res->fetchRow()) {
			$tpl->assign('next', $kov['picture_id']);
		}
	}
	$query .= ' ORDER BY GP.picture_id';
	if (isset($_REQUEST['pageID'])) {
		$tpl->assign('page_id', '&amp;'.$_REQUEST['pageID']);
	}
	$result =& $mdb2->query($pic_query);
	if ($pic = $result->fetchRow()) {
		$tpl->assign('pic', $pic);
	}*/
    
    

	//include_once "Pager/Pager.php";
	//$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    
	//$tpl->assign('pd_gallery', $paged_data['data']);
	//$tpl->assign('pl_gallery', $paged_data['links']);

	$acttpl = 'gallery_pic_list';

	return;
}

/**
 * Galériák listája 
 */
if ($act == 'gallery_lst') {
	
	$css[] = 'prettyPhoto';
	$javascripts[] = 'jquery.prettyPhoto';
	$javascripts[] = 'javascript.gallery';
	include_once $include_dir.'/function.gallery.php';

	if (empty( $_GET['which']) || $_GET['which'] != 'videos') {
		
		if (isset($row_menu['gallery_id'])) {
			$gallery_id = $row_menu['gallery_id'];
		} else {
			$gallery_id = "";
		}
		$tpl->assign('type', $gallery_id);
				
		
		//képgalériák
		$query = "
			SELECT g.gallery_id, g.name, g.description, gp.picture_id, p.realname
			FROM iShark_Galleries_Pictures AS gp
			LEFT JOIN iShark_Galleries AS g ON g.gallery_id = gp.gallery_id
			LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
			WHERE gp.gallery_id = g.gallery_id AND g.type = 'p' AND g.is_active = 1 AND g. timer_start < NOW() 
			GROUP BY g.gallery_id 
			ORDER BY g.add_date DESC, gp.orders ASC
		";
		$result =& $mdb2->query($query);
		$gals = $result->fetchAll();

		$tpl->assign('picgals', $gals);
		
	}

	if (empty($_GET['which']) || $_GET['which'] != 'pics' && !empty($_SESSION['site_gallery_is_video'])) {
		//videogalériák
		$query = "
			SELECT g.gallery_id, g.name, g.description
			FROM iShark_Galleries_Pictures AS gp, iShark_Galleries AS g
			WHERE gp.gallery_id = g.gallery_id AND g.type = 'v' AND g.is_active = 1 
			GROUP BY g.gallery_id 
			ORDER BY g.name ASC
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while($row = $result->fetchRow()) {
				$query2 = "
					SELECT pg.picture_id AS pgpic_id, p.picture_id AS ppic_id, p.name
					FROM iShark_Galleries_Pictures AS pg
					LEFT JOIN iShark_Pictures AS p ON pg.picture_id = p.picture_id
					WHERE pg.gallery_id = ".$row['gallery_id']."
				";
				$result2 = $mdb2->query($query2);
				if ($result2->numRows()>0) {
					while($row2 = $result2->fetchRow()) {
						$vids[] = array(
							'vid_id'   => $row2['ppic_id'],
							'vid_name' => $row2['name']
						);
					}
				} else {
					$vids = "";
				}
	
				$vidgals[] = array(
					'name' 		  => $row['name'],
					'gallery_id'  => $row['gallery_id'],
					'description' => $row['description'],
					'vids'		  => $vids
				);
				unset($vids);
			}
			$tpl->assign('vidgals', $vidgals);
		}
	}

	//beallitastol fuggoen mas linket kuldunk ki
	if (empty($_SESSION['site_gallery_type'])) {
	    //pop-up
	    $gallery_link1 = "href=\"javascript:;\" onclick=\"gallery_popup(";
	    $gallery_link2 = ");\"";
    } else {
        //inline
        $gallery_link1 = "href=\"index.php?p=gallery&amp;act=gallery_plst&amp;gid=";
        $gallery_link2 = "\"";
    }

    /*$tpl->assign('gallery_link1', $gallery_link1);
    $tpl->assign('gallery_link2', $gallery_link2);*/
	$tpl->register_function('pic_count', 'picCount');

	$acttpl = 'gallery_list';
}

?>