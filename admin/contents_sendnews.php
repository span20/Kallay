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
		$tpl->assign('errormsg', $locale->get('sendnews_error_not_exists'));
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
		'mainnews'    => intval($row['is_mainnews']),
		'type'        => $row['type'],
		'title'       => $row['title'],
		'lead'        => $row['lead'],
		'content'     => $row['content'],
		'timer_start' => $timer_start,
		'timer_end'   => $timer_end,
		'languages'   => $row['lang'],
		'lead_len'    => $_SESSION['site_leadmax']-strlen($row['lead']),
        'indexpage'   => intval($row['is_index']),
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
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY c.title ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY c.lang ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = "ORDER BY u.name ";
			$fieldselect4 = "selected";
			break;
		case 4:
			$fieldorder   = "ORDER BY c.add_date ";
			$fieldselect5 = "selected";
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
	$field      = "";
	$ord        = "";
	$fieldorder = "ORDER BY c.title";
	$order      = "ASC";
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

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "show") {
	// Hibakezeles ha nem adott meg content_id-t.
	if ($cid == '0') {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('sendnews_error_not_exists'));
		return;
	}

	$javascripts[] = "javascripts";
	$javascripts[] = "javascript.contents";

	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';
	require_once $include_dir.'/function.contents.php';

	//breadcrumb
	$breadcrumb->add($locale->get('sendnews_title_show'), '#');

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm( 'frm_contents', 'post', 'admin.php?p='.$module_name );
	$form->removeAttribute('name');

	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('sendnews_form_required_note'));

	//form-hoz elemek hozzadasa
	$form->addElement('header', $locale->get('sendnews_form_header'));
	$form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
	$form->addElement('hidden', 'field',   $field);
	$form->addElement('hidden', 'ord',     $ord);
	$form->addElement('hidden', 'cid',     $cid);

	//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		include_once $include_dir.'/functions.php';
		$form->addElement('select', 'languages', $locale->get('field_news_lang'), directory_list($lang_dir, 'php', array(), 1));
	}
	$form->addElement('text', 'title', $locale->get('sendnews_field_title'));

	//ha engedelyeztuk a vezeto hireket, akkor kirakjuk a valasztot hozza
	if (isset($_SESSION['site_lead']) && $_SESSION['site_lead'] == 1) {
		$mainnews = array();
		$mainnews[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('sendnews_form_yes'), '1');
		$mainnews[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('sendnews_form_no'),  '0');
		$form->addGroup($mainnews, 'mainnews', $locale->get('sendnews_field_mainnews'), '&nbsp;');
	}

	//fooldalon tartjuk-e a hirt, fuggetlenul attol, hogy van-e frissebb nala
	$indexpage = array();
	$indexpage[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('sendnews_form_yes'), '1');
	$indexpage[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('sendnews_form_no'),  '0');
	$form->addGroup($indexpage, 'indexpage', $locale->get('sendnews_field_index'), '&nbsp;');

	//ha engedelyeztuk a kategoriakat, akkor megjelenitjuk oket
	if (!empty($_SESSION['site_category'])) {
		//lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a user-t
		$query = "
			SELECT c.category_id AS cid, c.category_name AS cname 
			FROM iShark_Category c 
			WHERE c.is_active = 1 AND c.is_deleted = 0
			ORDER BY c.category_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'category', $locale->get('sendnews_field_category'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);
		//ha nincs meg egyetlen kategoria sem, akkor hibauzenet
		if ($result->numRows() == 0) {
			$form->setElementError('category', $locale->get('sendnews_error_no_category'));
		}

		//beallitjuk a kategoriakat
		$query = "
			SELECT category_id 
			FROM iShark_Contents_Category 
			WHERE content_id = $cid
		";
		$result =& $mdb2->query($query);
		$select->setSelected($result->fetchCol());
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

		$tag_select =& $form->addElement('select', 'tags', $locale->get('sendnews_field_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);

		//beallitjuk a tag-eket
		$query = "
			SELECT tag_id 
			FROM iShark_Tags_Modules 
			WHERE module_name = 'news' and id = $cid
		";
		$result =& $mdb2->query($query);
		$tag_select->setSelected($result->fetchCol());
	}

	//ha engedelyezve van az idozites a hirekre
	if (isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == 1) {
		$form->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly'=>'readonly')),
				HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
				HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('sendnews_deltimer'), 'onclick="deltimer(\'timer_start\')"')
			),
			'date_start', $locale->get('sendnews_field_timerstart'), null, false
		);
		$form->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
				HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
				HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('sendnews_deltimer'), 'onclick="deltimer(\'timer_end\')"')
			),
			'date_end', $locale->get('sendnews_field_timerend'), null, false
		);
	}

	//ha hasznaljuk a bevezeto szoveget
	if (isset($_SESSION['site_is_lead']) && $_SESSION['site_is_lead'] == 1) {
		// akkor:
		$leadarea =& $form->addElement('textarea', 'lead', $locale->get('sendnews_field_lead'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" id="leadfield"');
		$leadarea->setCols(95);
		$leadarea->setRows(7);
		$form->addElement('text', 'lead_len', $locale->get('sendnews_field_leadlen'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));
	}

	//ha tolthetunk fel kepet a hirekhez
	if ((isset($_SESSION['site_leadpic']) && $_SESSION['site_leadpic'] == 1) || (isset($_SESSION['site_newspic']) && $_SESSION['site_newspic'] == 1)) {
        $file =& $form->addElement('file', 'lead_file', $locale->get('sendnews_field_leadfile'));

        //modositas eseten jelenlegi kep kirajzolasa
        if (!empty($content_picture)) {
            $form->addElement('static', 'pic', $locale->get('sendnews_field_currentpic'), '<img src="'.$_SESSION['site_cnt_picdir'].'/'.$content_picture.'" alt="'.$content_picture.'" />' );
            $delpic =& $form->addElement('checkbox', 'delpic', '', $locale->get('sendnews_field_delpic'));
        }
	}

	//tartalom szoveg - ez minden esetben van
	$contentarea =& $form->addElement('textarea', 'content', $locale->get('sendnews_field_content'));
	$contentarea->setCols(30);
	$contentarea->setRows(30);

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	/**
	 * Szabalyok 
	 */
	//ha tobbnyelvu az oldal
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		$form->addRule('languages', $locale->get('sendnews_error_no_lang'), 'required');
	}
	//cim vizsgalata
	$form->addRule('title', $locale->get('sendnews_error_no_title'), 'required');

	//ha hasznaljuk a bevezeto szoveget
	if (isset($_SESSION['site_is_lead']) && $_SESSION['site_is_lead'] == 1) {
		$form->addRule('lead', $locale->get('sendnews_error_no_lead'), 'required');
	}

	if (isset($_SESSION['site_lead']) && $_SESSION['site_lead'] == 1) {
		$form->addRule('mainnews', $locale->get('sendnews_error_no_main'), 'required');
	}
	//kategoriahoz tartozo szabaly
	if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == 1) {
		$form->addGroupRule('category', $locale->get('sendnews_error_no_newscategory'), 'required');
	}
	//ha engedelyezve van az idozites
	if (isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == 1) {
		//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
		if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
			$form->addFormRule('check_timer');
		}
	}

	$form->addRule('content', $locale->get('sendnews_error_no_newscontent'), 'required');

	$form->addElement('submit', 'submit', $locale->get('sendnews_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('sendnews_form_reset'),  'class="reset"');

	// beallitjuk az alapertelmezett form ertekeket
	$form->setDefaults($form_defaults);

	//ellenorzes, vegso muveletek
	//csak akkor ellenorizzuk, ha engedelyezve vannak a vezeto hirek
	if (isset($_SESSION['site_lead']) && $_SESSION['site_lead'] == 1) {
		$form->addFormRule('change_index');
	}

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
		if ((isset($_SESSION['site_leadpic']) && $_SESSION['site_leadpic'] == 1) || (isset($_SESSION['site_newspic']) && $_SESSION['site_newspic'] == 1)) {
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
				$form->setElementError('lead_file', $locale->get('sendnews_error_picupload'));

				//regi kep torlese - ha volt
				if ($content_picture != "") {
					if (file_exists($_SESSION['site_cnt_picdir']."/".$content_picture)) {
						@unlink($_SESSION['site_cnt_picdir']."/".$content_picture);
					}
				}
			}
		}

		//bevezeto szoveg csak akkor van, ha ezt engedelyeztuk
		if (isset($_SESSION['site_is_lead']) && $_SESSION['site_is_lead'] == 1) {
			$lead = $form->getSubmitValue('lead');
		} else {
			$lead = "";
		}

		//ha hireket viszunk fel, akkor meg belerakunk par mezot
		$mainnews = intval($form->getSubmitValue('mainnews'));
		if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == 1) {
			$category = $form->getSubmitValue('category');
		} else {
			$category = "";
		}

		if (isset($_SESSION['site_cnt_is_tags']) && $_SESSION['site_cnt_is_tags'] == 1 && isModule('tags')) {
			$tags = $form->getSubmitValue('tags');
		} else {
			$tags = "";
		}

		$title       = $form->getSubmitValue('title');
		$content     = $form->getSubmitValue('content');
		$is_index    = $form->getSubmitValue('indexpage');
		$timer_start = $form->getSubmitValue('timer_start');
		$timer_end   = $form->getSubmitValue('timer_end');

		//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
		if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
			$languages = $form->getSubmitValue('languages');
		} else {
			$languages = $_SESSION['site_deflang'];
		}

		// tartalom mentese
		$query = "
			UPDATE iShark_Contents 
			SET is_mainnews = '$mainnews', 
				type        = '0', 
				lead        = '".$lead."', 
				content     = '".$content."', 
				mod_user_id = '".$_SESSION['user_id']."', 
				mod_date    = NOW(), 
				timer_start = '".$timer_start."', 
				timer_end   = '".$timer_end."', 
				lang        = '".$languages."', 
				title       = '".$title."', 
				picture     = '".$filename."', 
				is_index    = '$is_index'
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
						($id, 'news', $cid, NOW())
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
		header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
		exit;
	}

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('tiny_fields', 'content');
	$tpl->assign('form',        $renderer->toArray());
	$tpl->assign('back_arrow',  'admin.php?p='.$module_name.'&amp;act='.$page);
	$tpl->assign('lang_title',  $locale->get('title_show_sendnews'));

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha aktivaljuk vagy deaktivaljuk
 */
if ($sub_act == "act") {
	include_once $include_dir.'/function.check.php';

	check_active('iShark_Contents', 'content_id', $cid);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //aktivalas, deaktivalas vege

/**
 * ha toroljuk a tartalmat
 */
if ($sub_act == "del") {
	include_once $include_dir.'/function.contents.php';

	//megvizsgaljuk, hogy letezik-e ilyen tartalom
	$query = "
		SELECT content_id, picture 
		FROM iShark_Contents
		WHERE content_id = $cid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('sendnews_error_not_exists'));
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
			DELETE FROM iShark_Contents_Comments 
			WHERE content_id = $cid
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

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	//lekerdezzuk a bekuldott hirek listajat
	$query = "
		SELECT c.content_id AS cid, c.title AS ctitle, c.is_active AS cact, c.lang AS clang, c.is_mainnews AS mnews, c.add_date AS add_date, 
			u.name AS username
		FROM iShark_Contents c 
		LEFT JOIN iShark_Users u ON u.user_id = c.add_user_id 
		WHERE c.type = '0' AND c.is_active = 2
		$fieldorder $order
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('back_arrow', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'contents_sendnews_list';
}

?>
