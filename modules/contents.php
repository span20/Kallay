<?php

//modul neve
$module_name = "contents";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod', 'del');

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

//ha belso oldalkent hivatkozunk ra
if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid'])) {
	$content_id = intval($_REQUEST['cid']);
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
if (!empty($_SESSION['site_cnt_is_rating_cnt'])) {
	$cookie_contents_tmp = array();

	if (!empty($_COOKIE['iShark_Contents_Rated'])) {
		foreach ($_COOKIE['iShark_Contents_Rated'] as $key => $value) {
			$cookie_contents_tmp[$key] = $value;
		}
	}

	//ha ertekeli a tartalmat
	if (!empty($_POST['contentsrate']) && is_numeric($_POST['contentsrate']) && !empty($_REQUEST['cid']) && is_numeric($_REQUEST['cid']) ) {
		if (empty($_SESSION['user_id'])) {
			$userid = 0;
		}
		else {
			$userid = intval($_SESSION['user_id']);
		}
		$cid          = intval($_REQUEST['cid']);
		$contentsrate = intval($_POST['contentsrate']);

		$query = "
			INSERT INTO iShark_Contents_Ratings 
			(content_id, rate, user_id) 
			VALUES 
			($cid, $contentsrate, ".$userid.")
		";
		$mdb2->exec($query);

		setcookie("iShark_Contents_Rated[".$cid."]", 1, 0);
		$cookie_contents_tmp[$cid] = 1;

		header('Location: index.php?p='.$module_name.'&act=lst&cid='.$cid);
		exit;
	}

	$tpl->assign('rated_contents', $cookie_contents_tmp);
}

/**
 * ha csak egyszeruen mutatjuk a tartalmat
 */
if ($act == "lst" && !empty($content_id)) {
	$tpl->assign('header_picture', 'header_pic.jpg' );

	//ha engedelyezve van a szamlalo
	if (!empty($_SESSION['site_cnt_is_viewcounter'])) {
		include_once $include_dir.'/function.contents.php';
		$tpl->assign('view_counter', view_counter($content_id));
	}
    
    if (empty($_REQUEST['page'])) {
        $page_start = 0;
    } else {
        $page_start = $_REQUEST['page']*10;
    }

	$query = "
		SELECT c.content_id AS cid, c.title AS ctitle, c.lead AS clead, c.content AS ccont, c.content2 AS ccont2, c.add_date, c.mod_date, c.view_counter AS counter, 
			u.user_name AS addname, u2.user_name AS modname, c.heading_color 
		FROM iShark_Contents c 
		LEFT JOIN iShark_Users u ON c.add_user_id = u.user_id 
		LEFT JOIN iShark_Users u2 ON c.mod_user_id = u2.user_id 
		WHERE c.content_id = $content_id AND c.is_active = 1
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();
        
        /*$contentexp = explode('<p>##pagebreak##</p>', nl2br($row['ccont']));
        $pages = count($contentexp);
        $contslice = array_slice($contentexp, $page_start, 10);
        $loopnum = count($contslice);*/

        //$tpl->assign('content_pages',   $page_start);
        //$tpl->assign('all_pages',       (ceil($pages / 10)));
        //$tpl->assign('loopnum',         $loopnum);
		$tpl->assign('content_id',      $row['cid']);
		$tpl->assign('content_title',   $row['ctitle']);
		$tpl->assign('content_lead',    $row['clead']);
		$tpl->assign('content_content', $row['ccont']);
		$tpl->assign('content_content2', $row['ccont2']);
		$tpl->assign('heading_color', $row['heading_color']);
		$tpl->assign('content_adddate', $row['add_date']);
		$tpl->assign('content_moddate', $row['mod_date']);
		$tpl->assign('content_counter', $row['counter']);
		$tpl->assign('content_adduser', $row['addname']);
		$tpl->assign('content_moduser', $row['modname']);

        $module_title = $row['ctitle'];
	}

	//ha lehet megjegyzest irni a hirekhez, akkor megszamoljuk hany megjegyzes van
	if (!empty($_SESSION['site_cnt_is_comment_cnt'])) {
		$comment_query = "
			SELECT COUNT(comment_id) AS comments
			FROM iShark_Comments 
			WHERE id = $content_id AND module_name = 'contents'
		";
		$result_comment =& $mdb2->query($comment_query);
		$row_comment = $result_comment->fetchRow();

		$tpl->assign('cnt_countcomment', $row_comment['comments']);
	}

	//ha lehet ertekelni a hireket
	if (!empty($_SESSION['site_cnt_is_rating_cnt'])) {
		$rating_query = "
			SELECT COUNT(rate) AS cntrate, ROUND(AVG(rate), 2) AS avgrate, SUM(rate) AS allrate 
			FROM iShark_Contents_Ratings 
			WHERE content_id = $content_id
		";
		$result_rating = $mdb2->query($rating_query);
		$rating        = $result_rating->fetchRow();

		//felhasznalo ertekelese
		if (!empty($_SESSION['user_id'])) {
			$userrate_query = "
				SELECT rate 
				FROM iShark_Contents_Ratings 
				WHERE user_id = ".$_SESSION['user_id']." AND content_id = $content_id
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
			$tpl->assign('send_recommend', 'index.php?p=recommend&amp;type=contents&amp;cid='.$content_id);
		}
	}

	//ha hasznaljuk a tag-eket, akkor megjelenitjuk azokat
	if (isModule('tags', 'admin') && !empty($_SESSION['site_cnt_is_tags'])) {
		$query_tags = "
			SELECT t.tag_id AS tag_id, t.tag_name AS tag_name 
			FROM iShark_Tags t, iShark_Tags_Modules tm 
			WHERE tm.module_name = 'contents' AND tm.tag_id = t.tag_id AND tm.id = $content_id
		";
		$result_tags =& $mdb2->query($query_tags);
		$tpl->assign('cnt_taglist', $result_tags->fetchAll('', $rekey = true));
	}

	//kapcsolodo tartalmak
	if (!empty($_SESSION['site_cnt_is_attached_content'])) {
		$query_attached_contents = "
			SELECT ac.a_content_id AS ac_id, c.title AS title
			FROM iShark_Contents_Contents AS ac
			LEFT JOIN iShark_Contents AS c ON ac.a_content_id = c.content_id 
			WHERE ac.content_id = $content_id AND is_active = 1
		";
		$result_attached_contents = $mdb2->query($query_attached_contents);
		$tpl->assign('cnt_attach_cnt', $result_attached_contents->fetchAll('', $rekey = true));
	}
				
	//kapcsolodo galériák
	if (!empty($_SESSION['site_cnt_is_attached_gallery']) && isModule('gallery', 'index')) {
	    $javascripts[] = 'javascript.gallery';
		$query_attached_galleries = "
			SELECT ag.gallery_id AS ag_id, g.name AS title
			FROM iShark_Contents_Galleries AS ag
			LEFT JOIN iShark_Galleries AS g ON ag.gallery_id = g.gallery_id 
			WHERE ag.content_id = $content_id AND is_active = 1
		";
		$result_attached_galleries = $mdb2->query($query_attached_galleries);
		$tpl->assign('cnt_attach_gal', $result_attached_galleries->fetchAll('', $rekey = true));
	}

	//kapcsolodo urlapok
	if (!empty($_SESSION['site_cnt_is_attached_forms'])) {
	    $query_attached_forms = "
			SELECT af.form_id AS af_id, f.form_title AS title, f.show_type AS stype
			FROM iShark_Contents_Forms AS af
			LEFT JOIN iShark_Forms AS f ON af.form_id = f.form_id 
			WHERE af.content_id = $content_id AND is_active = 1 AND is_deleted = 0
		";
		$result_attached_forms = $mdb2->query($query_attached_forms);
		$form_arr = array();
		$i        = 0;
		while ($row_forms = $result_attached_forms->fetchRow())
		{
		    $form_arr[$i]['af_id'] = $row_forms['af_id'];
		    $form_arr[$i]['title'] = $row_forms['title'];
		    $form_arr[$i]['stype'] = $row_forms['stype'];
		    //ha a tipus 1, akkor meghivjuk a forms file-t
		    if ($row_forms['stype'] == 1) {
		        $_REQUEST['act']     = "show";
		        $_REQUEST['form_id'] = $row_forms['af_id'];
		        // hogy tudjuk hova kell visszadobni
		        $backToCnt = "p=contents&cid=".$content_id;
		        include 'modules/forms.php';
		    }
		    $i++;
		}
		$tpl->assign('cnt_attach_form', $form_arr);
	}

	//kulso linkek
	if (!empty($_SESSION['site_cnt_is_attached_link'])) {
	    $query_attached_link = "
			SELECT link, title
			FROM iShark_Contents_Links
			WHERE content_id = $content_id
		";
	    $result_attached_link =& $mdb2->query($query_attached_link);
	    $tpl->assign('cnt_attach_link', $result_attached_link->fetchAll('', $rekey = true));
	}

	$tpl->assign('self_contents',    $module_name);
	$tpl->assign('content_backlink', 'javascript:history.back()');

	$acttpl = 'contents';
}

/**
 * megjegyzes a hirekhez
 * 
 * mivel menuponthoz is lehet csatolni, ezert kell vizsgalni a $content_id-t is
 */
if (!empty($_SESSION['site_cnt_is_comment_cnt']) && isModule('comments', 'index') && ((isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid']) || !empty($content_id)))) {
    //megadjuk a valtozokat, amik kellenek a megjegyzesekhez
	$back_comment_module = $module_name;
	$back_comment_id     = !empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : $content_id;
	$back_comment_link   = '&act=lst&cid='.$back_comment_id;

	include_once 'comments.php';
}

?>