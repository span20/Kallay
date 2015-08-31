<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

// Content lekerdezese ha volt ilyen parameter
$cid = 0;
if (isset($_REQUEST['cid']) && $_REQUEST['cid'] != 0) {
	$cid = intval($_REQUEST['cid']);

	$query = "
		SELECT * 
		FROM iShark_Contents 
		WHERE content_id = $cid
	";
	$result =& $mdb2->query($query);
	if (!($row = $result->fetchRow())) {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('error_no_news'));
		return;
	}

	if ($row['timer_start'] == "0000-00-00 00:00:00") {
		$timer_start = "";
	} else {
		$timer_start = $row['timer_start'];
	}
	if ($row['timer_end'] == "0000-00-00 00:00:00") {
		$timer_end = "";
	} else {
		$timer_end = $row['timer_end'];
	}

	$form_defaults = array(
		'mainnews'    => $row['is_mainnews'],
		'type'        => $row['type'],
		'title'       => $row['title'],
		'lead'        => $row['lead'],
		'content'     => $row['content'],
		'timer_start' => $timer_start,
		'timer_end'   => $timer_end,
		'languages'   => $row['lang'],
		'lead_len'    => $_SESSION['site_leadmax']-strlen($row['lead']),
        'indexpage'   => $row['is_index'],
	);

	$tpl->assign('show', $row['type']);
	$content_picture = $filename = $row['picture'];
}

// alap valtozok hozzarendelese a template-hez.
$tpl->assign('cid',   $cid);

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
	$fieldorder .= ", c.is_index DESC";

	switch ($field) {
		case 1:
			$fieldorder   .= ", c.title ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   .= ", c.lang ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   .= ", c.add_date ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   .= ", c.mod_date ";
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
	$fieldorder   = "ORDER BY c.is_mainnews DESC, c.is_index DESC, c.add_date";
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

//kategóriaszűrés
if (!empty($_SESSION['site_category'])) {
	$catsel   = ", ca.category_name AS category_name ";
	$catfrom  = ", iShark_Category ca ";
	$catjoin  = "LEFT JOIN iShark_Contents_Category cc ON cc.content_id = c.content_id ";
	$catwhere = " AND ca.category_id = cc.category_id ";
	$where    = " AND cc.category_id = ";

	if (isset($_REQUEST['cat_fil']) && is_numeric($_REQUEST['cat_fil'])) {
		$cat_fil = intval($_REQUEST['cat_fil']);
		$catfilt = $where.$cat_fil;

		$catselect[$cat_fil] = "selected";
		$tpl->assign('catselect', $catselect);
	} else {
		$cat_fil = "";
		$catfilt = "";
	}
} else {
	$catsel   = "";
	$catfrom  = "";
	$catjoin  = "";
	$catwhere = "";
	$catfilt  = "";
	$cat_fil  = "";
}
//kategóriaszűrés vége

/**
 * ha aktivaljuk vagy deaktivaljuk
 */
if ($sub_act == "act") {
	include_once $include_dir.'/function.check.php';

	check_active('iShark_Contents', 'content_id', $cid);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
	exit;
} //aktivalas, deaktivalas vege

/**
 * ha megnezzuk, modositjuk a hirt
 */
if ($sub_act == "mod") {
    //breadcrumb
	$breadcrumb->add($locale->get('mti_form_header_news'), '#');

    //szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm( 'frm_mti', 'post', 'admin.php?p='.$module_name );
	$form->removeAttribute('name');

	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('mti_form_required_note'));

	//form-hoz elemek hozzadasa
	$form->addElement('header', 'mtihead', $locale->get('mti_form_header_news'));
	$form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
	$form->addElement('hidden', 'field',   $field);
	$form->addElement('hidden', 'ord',     $ord);
	$form->addElement('hidden', 'cid',     $cid);

	// cim
	$form->addElement('text', 'title', $locale->get('mti_field_title'));

	// rovatok
	if (!empty($_SESSION['site_category'])) {
		//lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a user-t
		$query = "
			SELECT c.category_id AS cid, c.category_name AS cname 
			FROM iShark_Category c 
			WHERE c.is_active = 1 AND c.is_deleted = 0
			ORDER BY c.category_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'category', $locale->get('mti_field_category'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);
		//ha nincs meg egyetlen kategoria sem, akkor hibauzenet
		if ($result->numRows() == 0) {
			$form->setElementError('category', $locale->get('mti_error_no_category'));
		}
	}

    //ha hasznalunk tag-eket
	if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
		// akkor:
		$query_tags = "
			SELECT tag_id, tag_name
			FROM iShark_Tags
			ORDER BY tag_name
		";
		$result_tags = $mdb2->query($query_tags);

		$tag_select =& $form->addElement('select', 'tags', $locale->get('mti_field_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);
	}

    //ha engedelyezve van az idozites a hirekre
	if (!empty($_SESSION['site_conttimer'])) {
		$form->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly'=>'readonly')),
				HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
				HTML_QuickForm::createElement('link', 'mti_deltimer', null, 'javascript:void(null);', $locale->get('news_deltimer'), 'onclick="deltimer(\'timer_start\')"')
			),
			'date_start', $locale->get('mti_field_timerstart'), null, false
		);
		$form->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
				HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
				HTML_QuickForm::createElement('link', 'mti_deltimer', null, 'javascript:void(null);', $locale->get('news_deltimer'), 'onclick="deltimer(\'timer_end\')"')
			),
			'date_end', $locale->get('mti_field_timerend'), null, false
		);
	}

    //ha hasznaljuk a bevezeto szoveget
	if (!empty($_SESSION['site_is_lead'])) {
		// akkor:
		$leadarea =& $form->addElement('textarea', 'lead', $locale->get('mti_field_lead'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" id="leadfield"');
		$leadarea->setCols(95);
		$leadarea->setRows(7);
		$form->addElement('text', 'lead_len', $locale->get('mti_field_leadlen'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));
	}

    //ha tolthetunk fel kepet a hirekhez
	if (!empty($_SESSION['site_leadpic']) || !empty($_SESSION['site_newspic'])) {
        $file =& $form->addElement('file', 'lead_file', $locale->get('mti_field_leadfile'));

        //modositas eseten jelenlegi kep kirajzolasa
        if ($sub_act == 'mod' && !empty($content_picture)) {
            $form->addElement('static', 'pic', $locale->get('mti_field_currentpic'), '<img src="'.$_SESSION['site_cnt_picdir'].'/'.$content_picture.'" alt="'.$content_picture.'" />' );
            $delpic =& $form->addElement('checkbox', 'delpic', '', $locale->get('mti_field_delpic'));
        }
	}

	//tartalom szoveg - ez minden esetben van
	$contentarea =& $form->addElement('textarea', 'content', $locale->get('mti_field_content'));
	$contentarea->setCols(30);
	$contentarea->setRows(30);

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

    /**
	 * Szabalyok 
	 */
	//cim vizsgalata
	$form->addRule('title', $locale->get('mti_error_no_title'), 'required');

	//ha hasznaljuk a bevezeto szoveget
	if (!empty($_SESSION['site_is_lead'])) {
		$form->addRule('lead', $locale->get('mti_error_no_lead'), 'required');
	}

	//kategoriahoz tartozo szabaly
	if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == 1) {
		$form->addGroupRule('category', $locale->get('mti_error_no_newscategory'), 'required');
	}

	//ha engedelyezve van az idozites
	if (!empty($_SESSION['site_conttimer'])) {
		//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
		if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
			$form->addFormRule('check_timer');
		}
	}

	//tartalom
    $form->addRule('content', $locale->get('mti_error_no_content'), 'required');

    // beallitjuk az alapertelmezett form ertekeket.
	$form->setDefaults($form_defaults);

    //beallitjuk a kategoriakat, ha hirek
	if (!empty($_SESSION['site_category'])) {
		$query = "
			SELECT category_id 
			FROM iShark_Contents_Category 
			WHERE content_id = $cid
		";
		$result =& $mdb2->query($query);
		$select->setSelected($result->fetchCol());
	}
	
	//beallitjuk a tag-eket
	if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
		$query = "
			SELECT * 
			FROM iShark_Tags_Modules 
			WHERE module_name = 'news' and id = $cid
		";
		$result =& $mdb2->query($query);
		$tag_select->setSelected($result->fetchCol());
	}

	//ellenorzes, vegso muveletek
	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		//ha ki akarjuk torolni a regi kepet - de semmi mast nem csinalunk
		if (isset($delpic) && $delpic->getChecked()) {
			$filename = "";
			if (file_exists($_SESSION['site_cnt_picdir']."/".$content_picture)) {
				@unlink($_SESSION['site_cnt_picdir']."/".$content_picture);
			}
		}

	    //kep feltoltese
        if (!empty($_SESSION['site_leadpic']) || !empty($_SESSION['site_newspic'])) {
			if ($file->isUploadedFile()) {
				$filevalues = $file->getValue();
				$sdir = preg_replace('|/$|','', $_SESSION['site_cnt_picdir']).'/';
				$filename = time().preg_replace('|[^\da-zA-Z_\.]|', '_', change_hunchar($filevalues['name']));

				//kep atmeretezese
				include_once 'includes/function.images.php';
				//ha vezeto hirhez toltunk fel
				if($form->getSubmitValue('mainnews') == 1) {
					if ($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_leadpicw'], $_SESSION['site_leadpich'])) {
						@chmod($sdir.$filename,0664);
						@unlink($filevalues['tmp_name']);
					}
				}
				//ha sima hirhez toltunk fel
				if($form->getSubmitValue('mainnews') == 0) {
					if ($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_newspicw'], $_SESSION['site_newspich'])) {
						@chmod($sdir.$filename,0664);
						@unlink($filevalues['tmp_name']);
					}
				}
				$form->setElementError('lead_file', $locale->get('news_error_picupload'));

				//regi kep torlese - ha volt
				if ($content_picture != "") {
					if (file_exists($_SESSION['site_cnt_picdir']."/".$content_picture)) {
						@unlink($_SESSION['site_cnt_picdir']."/".$content_picture);
					}
				}
			}
		}

		//bevezeto szoveg csak akkor van, ha ezt engedelyeztuk
		if (!empty($_SESSION['site_is_lead'])) {
			$lead = $form->getSubmitValue('lead');
		} else {
			$lead = "";
		}

		//ha hireket viszunk fel, akkor meg belerakunk par mezot
		$mainnews = intval($form->getSubmitValue('mainnews'));
		if (!empty($_SESSION['site_category'])) {
			$category = $form->getSubmitValue('category');
		} else {
			$category = "";
		}

		if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
			$tags = $form->getSubmitValue('tags');
		} else {
			$tags = "";
		}

		$title       = $form->getSubmitValue('title');
		$content     = $form->getSubmitValue('content');
		$is_index    = $form->getSubmitValue('indexpage');
		$timer_start = $form->getSubmitValue('timer_start');
		$timer_end   = $form->getSubmitValue('timer_end');
		$languages   = $_SESSION['site_deflang'];

	    // verziokovetes
		if (!empty($_SESSION['site_cnt_version'])) {
			$query = "
				SELECT c.content_id AS id ,c.title, c.lead, c.content, c.mod_user_id, c.picture
				FROM iShark_Contents c 
				WHERE c.content_id = $cid
			";
			$result = $mdb2->query($query);
			$row = $result->fetchRow();

			$versions_id = $mdb2->extended->getBeforeID('iShark_Contents_Versions', 'id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Contents_Versions 
				(id, parent_content_id, title, lead, content, mod_user_id, last_mod_user_id, picture, mod_date) 
				VALUES 
				($versions_id, ".$row['id'].", '".$row['title']."', '".$row['lead']."', '".$row['content']."', ".$_SESSION['user_id'].", ".$row['mod_user_id'].", '".$row['picture']."', NOW())
			";
			$mdb2->exec($query);
		}

		// tartalom mentese
		$query = "
			UPDATE iShark_Contents 
			SET lead        = '".$lead."', 
				content     = '".$content."', 
				mod_user_id = '".$_SESSION['user_id']."', 
				mod_date    = NOW(), 
				timer_start = '".$timer_start."', 
				timer_end   = '".$timer_end."', 
				lang        = '".$languages."', 
				title       = '".$title."', 
				picture     = '".$filename."'
			WHERE content_id = $cid
		";
		$mdb2->exec($query);

		if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == 1) {
			//ha letezik a $category tomb, akkor felvisszuk a kapcsolotablaba
			$query = "
				DELETE FROM iShark_Contents_Category 
				WHERE content_id = $cid
			";
			$mdb2->exec($query);

			if (is_array($category) && count($category) > 0) {
				foreach ($category as $key => $id) {
					$query = "
						INSERT INTO iShark_Contents_Category 
						(category_id, content_id) 
						VALUES 
						($id, $cid)
					";
					$mdb2->exec($query);
				}
			}
		}

		if (isset($_SESSION['site_cnt_is_tags']) && $_SESSION['site_cnt_is_tags'] == 1 && isModule('tags')) {
			//ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
			$query = "
				DELETE FROM iShark_Tags_Modules 
				WHERE module_name = 'news' and id = $cid
			";
			$mdb2->exec($query);

			if (is_array($tags) && count($tags) > 0) {
				foreach ($tags as $key => $id) {
					$query = "
						INSERT INTO iShark_Tags_Modules 
						(tag_id, module_name, id, add_date) 
						VALUES 
						($id, 'mti', $cid, NOW())
					";
					$mdb2->exec($query);
				}
			}
		}

		//loggolas
		logger($page.'_'.$sub_act);

		//"fagyasztjuk" a form-ot
		$form->freeze();

		//visszadobjuk a lista oldalra
		header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('mti_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('mti_form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('tiny_fields', 'content');
	$tpl->assign('form',        $renderer->toArray());
	$tpl->assign('back_arrow',  'admin.php?p='.$module_name.'&amp;act='.$page);
	$tpl->assign('lang_title',  $locale->get('mti_title_show'));

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha toroljuk a tartalmat
 */
if ($sub_act == "del") {
	include_once $include_dir.'/function.contents.php';

	//megvizsgaljuk, hogy beallitastol fuggoen, modosithatja- a tartalmat
	if (check_contents_perm($cid) === false) {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('error_no_newspermission'));
		return;
	}

	//megvizsgaljuk, hogy letezik-e ilyen tartalom
	$query = "
		SELECT content_id, picture 
		FROM iShark_Contents
		WHERE content_id = $cid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('error_no_news'));
		return;
	} else {
		$pic = $result->fetchRow();
		//ha van hozza kep, akkor azt is toroljuk
		if ($pic['picture'] != "") {
			@unlink($_SESSION['site_cnt_picdir'].'/'.$pic['picture']);
		}

		//kitoroljuk a tartalmat
		$query = "
			DELETE FROM iShark_Contents 
			WHERE content_id = $cid
		";
		$mdb2->exec($query);

		//a dokumentum elobbi verziojanak torlese
		$query = "
			DELETE FROM iShark_Contents_Versions 
			WHERE parent_content_id = $cid
		";
		$mdb2->exec($query);

		//kitoroljuk a tartalom kategoria kapcsolotablabol
		if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == 1) {
			$query = "
				DELETE FROM iShark_Contents_Category 
				WHERE content_id = $cid
			";
			$mdb2->exec($query);
		}

		//kitoroljuk a tag kapcsolotablabol
		if (isset($_SESSION['site_cnt_is_tags']) && $_SESSION['site_cnt_is_tags'] == 1 && isModule('tags')) {
			$query = "
				DELETE FROM iShark_Tags_Modules
				WHERE module_name = 'news' and id = $cid
			";
			$mdb2->exec($query);
		}

		//kitoroljuk a tartalom ertekeles tablabol
		$query = "
			DELETE FROM iShark_Contents_Ratings 
			WHERE content_id = $cid
		";
		$mdb2->exec($query);

		//kitoroljuk a tartalom hozzaszolas tablabol
		$query = "
			DELETE FROM iShark_Comments 
			WHERE id = $cid AND module_name = 'news'
		";
		$mdb2->exec($query);

		//kitoroljuk a jogok tablabol
		$query = "
			SELECT r.right_id AS rid 
			FROM iShark_Rights r 
			WHERE r.content_id = $cid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fethcRow())
			{
				$right_id = $row['rid'];
				//kitoroljuk a jogok tablabol
				$query = "
					DELETE FROM iShark_Rights 
					WHERE content_id = $cid
				";
				$mdb2->exec($query);

				//kitoroljuk a csoportjog tablabol
				$query = "
					DELETE FROM iShark_GroupRights 
					WHERE right_id = $right_id
				";
				$mdb2->exec($query);

				//kitoroljuk a jog funkciok tablabol
				$query = "
					DELETE FROM iShark_RightsFunctions 
					WHERE right_id = $right_id
				";
				$mdb2->exec($query);
			}
		}
	}

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
	exit;
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	$javascripts[] = 'javascript.contents';

	//lekerdezzuk a tartalmak listajat
	$query = "
		SELECT c.content_id AS cid, c.title AS ctitle, c.is_active AS cact, c.lang AS clang, c.type AS ctype, c.is_mainnews AS mnews, 
			c.lead AS lead, c.is_index AS is_index, c.add_date AS add_date, c.mod_date as mod_date $catsel 
		FROM iShark_Contents c $catfrom 
		$catjoin
		WHERE c.type = '2' AND c.is_active != '2' $catwhere 
		$catfilt $fieldorder $order
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//ha vannak kategoriak
	if (!empty($_SESSION['site_category'])) {
		$all_select = array('all' => $locale->get('mti_field_news_list_allfilter'));

		$query_cat = "
			SELECT category_id AS cat_id, category_name AS cat_name 
			FROM iShark_Category 
			WHERE is_active = 1 AND is_deleted = 0 
			ORDER BY category_name
		";
		$result_cat =& $mdb2->query($query_cat);
		$row_cat = $result_cat->fetchAll('', $rekey = true);

		$tpl->assign('category_list', $all_select + $row_cat);
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',   $paged_data['data']);
	$tpl->assign('page_list',   $paged_data['links']);
	$tpl->assign('back_arrow',  'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'contents_mti_list';
}

?>