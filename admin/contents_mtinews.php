<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//azonosito lekerdezese, ha volt ilyen
$cid = 0;
if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid']) && $_REQUEST['cid'] != 0) {
	$cid = intval($_REQUEST['cid']);

	$query = "
		SELECT * 
		FROM iShark_Mti_News 
		WHERE id = $cid
	";
	$result =& $mdb2->query($query);
	if (!($row = $result->fetchRow())) {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('mti_error_no_mtinews'));
		return;
	}
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$fieldselect3 = "";
$fieldselect4 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field      = intval($_REQUEST['field']);
	$ord        = $_REQUEST['ord'];
	$fieldorder = " ORDER BY";

	switch ($field) {
		case 1:
			$fieldorder   .= " title ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   .= " mainsection ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   .= " createdate ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   .= " modifieddate ";
			$fieldselect4 = "selected";
			break;
	}

	switch ($ord) {
		case "asc":
			$order      = "ASC";
			$ordselect1 = "selected";
			break;
		case "desc":
			$order      = "DESC";
			$ordselect2 = "selected";
			break;
	}
} else {
	$field        = "";
	$ord          = "";
	$fieldorder   = "ORDER BY modifieddate";
	$fieldselect3 = "selected";
	$order        = "DESC";
	$ordselect2   = "selected";
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('fieldselect3', $fieldselect3);
$tpl->assign('fieldselect4', $fieldselect4);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);

//kategóriaszûrés
if (!empty($_SESSION['site_category'])) {
	$where    = "WHERE mainsection = ";

	if (isset($_REQUEST['cat_fil']) && $_REQUEST['cat_fil']) {
		$cat_fil = $_REQUEST['cat_fil'];
		$catfilt = $where."'".$cat_fil."'";

		$catselect[$cat_fil] = "selected";
		$tpl->assign('catselect', $catselect);
	} else {
		$cat_fil = "";
		$catfilt = "";
	}
} else {
	$catfilt  = "";
	$cat_fil  = "";
}
//kategóriaszûrés vége

/**
 * ha megnezzuk a cikket
 */
if ($sub_act == "show" && empty($_GET['pic'])) {
    //breadcrumb
	$breadcrumb->add($locale->get('mtinews_form_header_news'), '#');

	$tpl->assign('elements',   $row);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&amp;cat_fil='.$cat_fil);
	$tpl->assign('lang_title', $locale->get('mtinews_form_header_news'));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'contents_mtinews_show';
}

/**
 * kep mutatasa
 */
if ($sub_act == "show" && !empty($_GET['pic'])) {
    $pic = stripslashes($_GET['pic']);

    if ($picture = fopen($pic, 'r')) {
        fclose($picture);
        header("Content-type: image/jpeg");
        @readfile($pic);
    }
    exit;
}

/**
 * ha aktivaljuk
 */
if ($sub_act == "act") {
	//lekerdezzuk az mti hir tartalmat
	$query_mti = "
		SELECT * 
		FROM iShark_Mti_News 
		WHERE id = $cid
	";
	$result_mti =& $mdb2->query($query);
	if ($result_mti->numRows() > 0) {
		$row_mti    = $result_mti->fetchRow();
		$content_id = $mdb2->extended->getBeforeID('iShark_Contents', 'content_id', TRUE, TRUE);

		//kep feltoltese
		$filename = "";
		if (!empty($row_mti['image']) && (!empty($_SESSION['site_leadpic']) || !empty($_SESSION['site_newspic']))) {
			$sdir = preg_replace('|/$|', '', $_SESSION['site_cnt_picdir']).'/';
			//atmasoljuk a kepet local-ba
			$file    = file_get_contents($row_mti['image']);
			$mtifile = fopen($sdir.'mti_temp_pic', 'x+');
			if (file_exists($sdir.'mti_temp_pic')) {
			    fwrite($mtifile, $file);

    			//kiszedjuk a tipus alapjan a kiterjesztest, mivel ezt a link alapjan nem tudjuk
    			$size = GetImageSize($sdir.'/mti_temp_pic');
    			if (!empty($size)) {
    				$tipus = $size[2];
    				switch ($tipus) {
    					case "1":
    						$ext = ".gif";
    						break ;
    					case "2":
    						$ext = ".jpg";
    						break;
    					case "3":
    						$ext = ".png";
    						break;
    					default:
    						$ext = ".jpg";
    						break;
    				}
    			}
    			$filename = time().preg_replace('|[^\da-zA-Z_\.]|', '_', 'mtipic'.$ext);
    			fclose($mtifile);
    
    			//kep atmeretezese
    			include_once 'includes/function.images.php';
    			if ($pic = img_resize($sdir.'mti_temp_pic', $sdir.$filename, $_SESSION['site_newspicw'], $_SESSION['site_newspich'])) {
    				@chmod($sdir.$filename, 0664);
    			}
			}
			
			unlink($sdir.'mti_temp_pic');
		}

		$types  = array('integer', 'text', 'text', 'text', 'integer', 'integer', 'text');
		$values = array($content_id, $row_mti['title'], $row_mti['lead'], $row_mti['body'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['site_deflang']);

		//beszurjuk az mti hirt a sajt hir tablankba
		$query = "
			INSERT INTO iShark_Contents 
			(content_id, is_mainnews, is_index, type, title, lead, content, add_user_id, add_date, mod_user_id, mod_date, is_active, lang, picture) 
			VALUES 
			(?, 0, 0, 2, ?, ?, ?, ?, NOW(), ?, NOW(), 1, ?, '".$filename."')
		";
		$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
		$result->execute($values);
		$last_content_id = $mdb2->extended->getAfterID($content_id, 'iShark_Contents', 'content_id');

		//rovat, ha hasznaljuk
		if (!empty($_SESSION['site_category'])) {
			//megnezzuk, hogy van-e mar ilyen mti kategoriank
			$query_mtisec = "
				SELECT category_id 
				FROM iShark_Mti_Category 
				WHERE UPPER(category_name) = UPPER('".$row_mti['mainsection']."')
			";
			$result_mtisec =& $mdb2->query($query_mtisec);
			//ha van mar ilyen mti kategoriank, akkor csak kiszedjuk az id-t
			if ($result_mtisec->numRows() > 0) {
				$row_mtisec = $result_mtisec->fetchRow();
				$mtisec_id  = $row_mtisec['category_id'];
			}
			//ha meg nincs, akkor elobb letrehozzuk
			else {
				$mti_category_id = $mdb2->extended->getBeforeID('iShark_Mti_Category', 'category_id', TRUE, TRUE);
				$query_newmtisec = "
					INSERT INTO iShark_Mti_Category 
					(category_id, category_name) 
					VALUES 
					($mti_category_id, '".$row_mti['mainsection']."')
				";
				$mdb2->exec($query_newmtisec);
				$mtisec_id = $mdb2->extended->getAfterID($mti_category_id, 'iShark_Mti_Category', 'category_id');
			}

			//id alapjan megnezzuk, hogy melyik sajat kategoriahoz tartozik
			$query_sec = "
				SELECT category_id 
				FROM iShark_Category 
				WHERE mti_category_id = $mtisec_id
			";
			$result_sec =& $mdb2->query($query_sec);
			//ha van mar ilyen sajat kategoriank, akkor a hirt berakjuk hozza
			if ($result_sec->numRows() > 0) {
				$row_sec = $result_sec->fetchRow();
				$sec_id  = $row_sec['category_id'];
				//felvisszuk a hir - kategoria kapcsolatot
				$query_newssec = "
					INSERT INTO iShark_Contents_Category 
					(content_id, category_id) 
					VALUES 
					($last_content_id, $sec_id)
				";
				$mdb2->exec($query_newssec);
			}
			//ha meg nincs ilyen kategoriank
			else {
				//akkor elobb letrehozzuk
				$types_cat  = array('text', 'integer', 'integer', 'text', 'integer');
				$values_cat = array($row_mti['mainsection'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['site_deflang'], $mtisec_id);
				$category_id = $mdb2->extended->getBeforeID('iShark_Category', 'category_id', TRUE, TRUE);
				$query_cat = "
					INSERT INTO iShark_Category 
					(category_id, category_name, add_user_id, add_date, mod_user_id, mod_date, is_active, is_deleted, lang, mti_category_id) 
					VALUES 
					($category_id, ?, ?, NOW(), ?, NOW(), 1, 0, ?, ?)
				";
				$result_cat = $mdb2->prepare($query_cat, $types_cat, MDB2_PREPARE_MANIP);
				$result_cat->execute($values_cat);
				$last_category_id = $mdb2->extended->getAfterID($category_id, 'iShark_Category', 'category_id');

				//majd osszekapcsoljuk a kategoriat a tartalommal
				$query_newssec = "
					INSERT INTO iShark_Contents_Category 
					(content_id, category_id) 
					VALUES 
					($last_content_id, $last_category_id)
				";
				$mdb2->exec($query_newssec);
			}
		}

		//kitoroljuk az mti hirt a sajat tablajabol, mert mar atkerult a vegso hir tablaba
		$query_del = "
			DELETE FROM iShark_Mti_News 
			WHERE id = $cid
		";
		$mdb2->exec($query_del);

		//loggolas
		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&amp;cat_fil='.$cat_fil);
		exit;
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('mti_error_no_mtinews'));
		return;
	}
} //aktivalas

/**
 * ha toroljuk a tartalmat
 */
if ($sub_act == "del") {
	//megvizsgaljuk, hogy letezik-e ilyen tartalom
	$query = "
		SELECT id 
		FROM iShark_Mti_News
		WHERE id = $cid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('mti_error_no_mtinews'));
		return;
	} else {
		$query = "
			DELETE FROM iShark_Mti_News 
			WHERE id = $cid
		";
		$mdb2->exec($query);
	}

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&amp;cat_fil='.$cat_fil);
	exit;
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	//lekerdezzuk az mit hirek listajat
	$query = "
		SELECT id AS cid, title, mainsection, createdate, modifieddate, lead 
		FROM iShark_Mti_News 
		$catfilt
		$fieldorder $order
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//ha vannak kategoriak
	if (!empty($_SESSION['site_category'])) {
		$all_select = array('all' => $locale->get('mtinews_field_news_list_allfilter'));

		$query_cat = "
			SELECT mainsection 
			FROM iShark_Mti_News 
			GROUP BY mainsection
			ORDER BY mainsection
		";
		$result_cat =& $mdb2->query($query_cat);
		$row_cat = $result_cat->fetchCol();

		$tpl->assign('category_list', $all_select + $row_cat);
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('back_arrow', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'contents_mtinews_list';
}

?>