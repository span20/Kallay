<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "news";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

$tpl->assign('self_news', $module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst', 'show');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
    $site_errors[] = array('text' => $locale->get('error_no_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

//lekerdezzuk a tartalomszerkesztohoz tartozo beallitasokat
$query_contents_config = "
	SELECT is_send_reg 
	FROM iShark_Contents_Configs
";
$result_contents_config =& $mdb2->query($query_contents_config);
if (!PEAR::isError($result_contents_config)) {
	$row_configs = $result_contents_config->fetchRow();
} else {
    $site_errors[] = array('text' => $locale->get('error_no_config_table'), 'link' => 'javascript:history.back(-1)');
	return;
}

/**
 * ertekeles
 */
if (!empty($_SESSION['site_cnt_is_rating_news'])) {
	$cookie_news_tmp = array();

	if (!empty($_COOKIE['iShark_News_Rated'])) {
		foreach ($_COOKIE['iShark_News_Rated'] as $key => $value) {
			$cookie_news_tmp[$key] = $value;
		}
	}

	//ha ertekeli a hirt
	if (!empty($_POST['newsrate']) && is_numeric($_POST['newsrate']) && !empty($_REQUEST['cid']) && is_numeric($_REQUEST['cid']) ) {
		if (empty($_SESSION['user_id'])) {
			$userid = 0;
		}
		else {
			$userid = intval($_SESSION['user_id']);
		}
		$cid      = intval($_REQUEST['cid']);
		$newsrate = intval($_POST['newsrate']);

		$query = "
			INSERT INTO iShark_Contents_Ratings 
			(content_id, rate, user_id) 
			VALUES 
			($cid, $newsrate, ".$userid.")
		";
		$mdb2->exec($query);

		setcookie("iShark_News_Rated[".$cid."]", 1, 0);
		$cookie_news_tmp[$cid] = 1;

		header('Location: index.php?p='.$module_name.'&act=show&cid='.$cid);
		exit;
	}

	$tpl->assign('rated_news', $cookie_news_tmp);
}

$query_cats = "
	SELECT category_id, category_name
	FROM iShark_Category
	WHERE is_active = '1' AND lang = '".$_SESSION['site_lang']."'
	ORDER BY category_name
";

$result_cats = $mdb2->query($query_cats);
$tpl->assign('cats', $result_cats->fetchAll());

$months_hu = array(
	'January' => 'Január',
	'Ferbuary' => 'Február',
	'March' => 'Március',
	'April' => 'Április',
	'May' => 'Május',
	'June' => 'Június',
	'July' => 'Július',
	'August' => 'Augusztus',
	'September' => 'Szeptember',
	'October' => 'Október',
	'November' => 'November',
	'December' => 'December',
	);

$query_dates = "
	SELECT DATE_FORMAT(add_date, '%Y. %M') AS date
	FROM iShark_Contents
	WHERE is_active = '1' AND type = '0' AND lang = '".$_SESSION['site_lang']."'
	GROUP BY date
	ORDER BY add_date DESC
";
$result_dates = $mdb2->query($query_dates);
$dates = $result_dates->fetchAll();
foreach ($dates as $key => $value) {
	$month = explode(".", $value["date"]);
	$dates[$key]["month_eng"] = $value["date"];
	$dates[$key]["date"] = str_replace($month[1], " ".$months_hu[trim($month[1])], $value["date"]);
}
$tpl->assign('dates', $dates);

/**
 * hir megjelenitese
 */
if ($act == "show") {
	if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid'])) {
		$cid = intval($_REQUEST['cid']);

		//ha engedelyezve van a szamlalo
		if (!empty($_SESSION['site_cnt_is_viewcounter'])) {
			include_once $include_dir.'/function.contents.php';
			$tpl->assign('view_counter', view_counter($cid));
		}

		$query = "
			SELECT c.title AS ctitle, c.lead AS clead, c.add_date AS add_date, c.picture AS cpic, 
				c.content AS ccont, c.mod_date AS mod_date, u.user_name AS addname, u2.user_name AS modname, 
				c.is_mainnews AS main, c.view_counter AS counter 
			FROM iShark_Contents c 
			LEFT JOIN iShark_Users u ON u.user_id = c.add_user_id 
			LEFT JOIN iShark_Users u2 ON u2.user_id = c.mod_user_id
			WHERE c.content_id = $cid
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();

			$tpl->assign('news_title',   $row['ctitle']);
			$tpl->assign('news_lead',    $row['clead']);
			$tpl->assign('news_adddate', $row['add_date']);
			$tpl->assign('news_cpic',    $row['cpic']);
			$tpl->assign('news_content', $row['ccont']);
			$tpl->assign('news_moddate', $row['mod_date']);
			$tpl->assign('news_addname', $row['addname']);
			$tpl->assign('news_modname', $row['modname']);
			$tpl->assign('news_main',    $row['main']);
			$tpl->assign('news_counter', $row['counter']);
		}

		//elozo, kovetkezo hir linkje
		$query = "
			SELECT c.content_id AS cid 
			FROM iShark_Contents c 
			WHERE c.is_active = '1' AND lang = '".$_SESSION['site_lang']."' AND type = '0'
			ORDER BY add_date DESC
		";
		$result =& $mdb2->query($query);
		$list = array();
		if ($result->numRows() > 0) {
			$list = $result->fetchCol(0);

			$location = array_search($cid, $list);

			if ($location - 1 >= 0) {
				$tpl->assign('prev_news', $list[$location - 1]);
			}
			if (($location + 1) < count($list)) {
				$tpl->assign('next_news', $list[$location + 1]);
			}
		}

		//ha lehet ertekelni a hireket
		if (!empty($_SESSION['site_cnt_is_rating_news'])) {
			$rating_query = "
				SELECT COUNT(rate) AS cntrate, ROUND(AVG(rate), 2) AS avgrate, SUM(rate) AS allrate 
				FROM iShark_Contents_Ratings 
				WHERE content_id = $cid
			";
			$result_rating = $mdb2->query($rating_query);
			$rating        = $result_rating->fetchRow();

			//felhasznalo ertekelese
			if (!empty($_SESSION['user_id'])) {
				$userrate_query = "
					SELECT rate 
					FROM iShark_Contents_Ratings 
					WHERE user_id = ".$_SESSION['user_id']." AND content_id = $cid
				";
				$result_userrate = $mdb2->query($userrate_query);
				$usrrate         = $result_userrate->fetchRow();

				$tpl->assign('usrrate', $usrrate['rate']);
			}

			$tpl->assign('cntrate', $rating['cntrate']);
			$tpl->assign('avgrate', $rating['avgrate']);
			$tpl->assign('allrate', $rating['allrate']);
		}

		//ha van ajanlo kuldese
		if (isModule('recommend', 'index') && !empty($_SESSION['site_cnt_is_send'])) {
			if (($row_configs['is_send_reg'] == 1 && !empty($_SESSION['user_id'])) || $row_configs['is_send_reg'] == 0) {
				$tpl->assign('send_recommend', 'index.php?p=recommend&amp;type=news&amp;cid='.$cid);
			}
		}

		//ha vannak tag-ek
		if (isModule('tags', 'admin') && !empty($_SESSION['site_cnt_is_tags'])) {
			$query_tags = "
				SELECT t.tag_id AS tag_id, t.tag_name AS tag_name 
				FROM iShark_Tags t, iShark_Tags_Modules tm 
				WHERE tm.module_name = 'news' AND tm.id = $cid AND tm.tag_id = t.tag_id
			";
			$result_tags =& $mdb2->query($query_tags);
			$tpl->assign('news_taglist', $result_tags->fetchAll('', $rekey = true));
		}

		//megadjuk a tpl file nevet, amit atadunk az index.php-nek
		$acttpl= 'news';
	}
}

/**
 * ha nincs semmilyen muvelet, akkor a listat mutatjuk
 */
if ($act == "lst") {
    /*if (empty($_REQUEST['page'])) {
        $page_start = 0;
    } else {
        $page_start = $_REQUEST['page']*10;
    }*/
    if (!empty($_REQUEST['cat'])) {
        $row_menu['catid'] = $_REQUEST['cat'];
    }
    
    if (!empty($_REQUEST['date'])) {
        $date_query = " AND DATE_FORMAT(c.add_date, '%Y. %M') = '".$_REQUEST['date']."' ";
    } else {
    	$date_query = "";
    }

	//$row_menu az index.php-bol jon
	$query = "
		SELECT c.content_id AS cid, c.title AS ctitle, c.lead AS clead, DATE_FORMAT(c.add_date, '%Y.%m.%d') AS add_date, c.picture AS cpic,
			c.is_mainnews AS main, c.view_counter AS counter, icat.category_name AS category_name, c.content AS content 
		FROM iShark_Contents c 
		LEFT JOIN iShark_Contents_Category icc ON icc.content_id = c.content_id 
		LEFT JOIN iShark_Category icat ON icat.category_id = icc.category_id 
		WHERE c.is_active = '1' AND c.lang = '".$_SESSION['site_lang']."' AND c.type = '0' 
			".(!empty($row_menu['catid']) ? "AND icc.category_id = ".$row_menu['catid'] : '' )." ".$date_query."
		ORDER BY c.add_date DESC 
       
	";
 //LIMIT ".$page_start.", 10
    //$result = $mdb2->query($query);
    require_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);
    //$pager_list = $news_list = $result->fetchAll();
    $tpl->assign('page_data',  $paged_data['data']);
    $tpl->assign('page_list',  $paged_data['links']);
    //$tpl->assign('news_list',  $news_list);
    //$tpl->assign('pager_list',  $pager_list);
    
    //szamolas
    /*$query = "
        SELECT COUNT(c.content_id) AS all_news
        FROM iShark_Contents c
        LEFT JOIN iShark_Contents_Category icc ON icc.content_id = c.content_id 
		LEFT JOIN iShark_Category icat ON icat.category_id = icc.category_id 
		WHERE c.is_active = '1' AND c.lang = '".$_SESSION['site_lang']."' AND c.type = '0' 
			".(!empty($row_menu['catid']) ? "AND icc.category_id = ".$row_menu['catid'] : '' )."
    ";
    $result = $mdb2->query($query);
    $all_news = $result->fetchRow();
    
    $tpl->assign('all_pages', (ceil($all_news['all_news'] / 10)));*/

	//ha lehet ertekelni a hireket
	if (!empty($_SESSION['site_cnt_is_rating_news'])) {
		if (!empty($paged_data['data'])) {
			foreach ($paged_data['data'] as $key => $adat) {
				$ratings = 0;
				$rating_query = "
					SELECT ROUND(AVG(rate), 2) AS avgrate 
					FROM iShark_Contents_Ratings 
					WHERE content_id = ".$adat['cid']."
				";
				$result_rating = $mdb2->query($rating_query);
				while($row_rating = $result_rating->fetchRow())
				{
					$ratings = $row_rating['avgrate'];
				}
				$paged_data['data'][$key]['ratings'] = $ratings;
			}
		}
	}

	//ha lehet megjegyzest irni a hirekhez, akkor megszamoljuk hany megjegyzes van
	if (!empty($_SESSION['site_cnt_is_comment_news'])) {
		if (!empty($paged_data['data'])) {
			foreach ($paged_data['data'] as $key => $adat) {
				$comments = 0;
				$comment_query = "
					SELECT COUNT(comment_id) AS comments
					FROM iShark_Comments 
					WHERE id = ".$adat['cid']." AND module_name = 'news'
				";
				$result_comment = $mdb2->query($comment_query);
				while($row_comment = $result_comment->fetchRow())
				{
					$comments = $row_comment['comments'];
				}
				$paged_data['data'][$key]['comments'] = $comments;
			}
		}
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	//$tpl->assign('page_data_news', $paged_data['data']);
	//$tpl->assign('page_list_news', $paged_data['links']);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'news_list';
}

/**
 * megjegyzes a hirekhez
 */
if (!empty($_SESSION['site_cnt_is_comment_news']) && isModule('comments', 'index') && isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid'])) {
	//megadjuk a valtozokat, amik kellenek a megjegyzesekhez
	$back_comment_module = $module_name;
	$back_comment_id     = intval($_REQUEST['cid']);
	$back_comment_link   = '&act=show&cid='.$back_comment_id;

	include_once 'comments.php';
}

?>
