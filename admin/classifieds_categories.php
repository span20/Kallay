<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$javascripts[] = "javascripts";

$groupnums = 0;
//lekerdezzuk, hogy mennyi kategoria van jelenleg
$query = "
	SELECT * 
	FROM iShark_Classifieds_Category 
";
$result =& $mdb2->query($query);
$groupnums = $result->numRows();

$cid = 0;
if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid'])) {
	$cid = intval($_REQUEST['cid']);

	$query = "
		SELECT c.category_name AS cname, c.category_desc AS cdesc, c.timer_start AS timer_start, c.timer_end AS timer_end, 
			c.picture AS picture, c.lang AS lang 
		FROM iShark_Classifieds_Category c 
		WHERE c.category_id = $cid
	";
	$result =& $mdb2->query($query);
	if (!($row = $result->fetchRow())) {
	    $acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('category_error_category_noexists'));
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
		'languages'   => $row['lang'],
		'name'        => $row['cname'],
		'desc'        => $row['cdesc'],
		'timer_start' => $timer_start,
		'timer_end'   => $timer_end
	);

	$filename = $row['picture'];
}

$par = 0;
if (isset($_REQUEST['par']) && is_numeric($_REQUEST['par'])) {
	$par = intval($_REQUEST['par']);
}

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/Array.php';
	require_once $include_dir.'/function.check.php';
	require_once $include_dir.'/function.classifieds.php';

	$titles = array('add' => $locale->get('category_title_category_add'), 'mod' => $locale->get('category_title_category_mod'));

	$form_class =& new HTML_QuickForm('frm_class', 'post', 'admin.php?p='.$module_name);
	$form_class->removeAttribute('name');

	$form_class->setRequiredNote($locale->get('category_form_required_note'));

	$form_class->addElement('header', $locale->get('category_form_header'));
	$form_class->addElement('hidden', 'sub_act', $sub_act);
	$form_class->addElement('hidden', 'cid',     $cid);
	$form_class->addElement('hidden', 'par',     $par);
	//ha kereses volt, akkor bele kell tenni hidden-be a mezoket
	if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
		$form_class->addElement('hidden', 's', intval($_REQUEST['s']));
		$form_class->addElement('hidden', 'searchtext', $_REQUEST['searchtext']);
		$form_class->addElement('hidden', 'searchtype', $_REQUEST['searchtype']);
	}

	//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
	if (!empty($_SESSION['site_multilang'])) {
		include_once $include_dir.'/functions.php';
		$form_class->addElement('select', 'languages', $locale->get('category_field_category_lang'), $locale->getLocales());
	}
	//kategoria neve
	$form_class->addElement('text', 'name', $locale->get('category_field_category_name'));

	//idozito
	$form_class->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly' => 'readonly')),
	        HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('category_deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('category_field_category_timerstart'), null, false
	);
	$form_class->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
	        HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('category_deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('category_field_category_timerend'), null, false
	);

	//ha lehet kepet tolteni a kategoriahoz
	if (!empty($_SESSION['site_class_is_catpic'])) {
		$file =& $form_class->addElement('file', 'picture', $locale->get('category_field_category_picture'));

		//modositas eseten jelenlegi kep kirajzolasa
		if ($sub_act == 'mod' && !empty($filename)) {
		    $form_class->addElement('hidden',   'oldpic',  $filename);
		    $form_class->addElement('static',   'pic',     $locale->get('adverts_field_main_currentpic'), '<img src="'.$_SESSION['site_class_catpicdir'].'/'.$filename.'" alt="'.$filename.'" />' );
			$delpic =& $form_class->addElement('checkbox', 'delpic', '', $locale->get('adverts_field_main_delpic'));
		}
	}

	//leiras
	if (!empty($_SESSION['site_class_is_catdesc'])) {
		$description =& $form_class->addElement('textarea', 'desc', $locale->get('category_field_category_description'));
	}

	$form_class->addElement('submit', 'submit', $locale->get('category_form_submit'), 'class="submit"');
	$form_class->addElement('reset',  'reset',  $locale->get('category_form_reset'),  'class="reset"');

	//szurok beallitasa
	$form_class->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form_class->addRule('name', $locale->get('category_error_category_name'), 'required');
	if (!empty($_SESSION['site_class_is_catdesc'])) {
		$form_class->addRule('desc', $locale->get('category_error_category_description'), 'required');
	}
	//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
	if ($form_class->isSubmitted() && ($form_class->getSubmitValue('timer_start') != "" || $form_class->getSubmitValue('timer_end') != "")) {
		$form_class->addFormRule('check_timer');
	}

	/**
	 * Ha uj kategoriat adunk hozza
	 */
	if ($sub_act == "add") {
		if (!empty($_SESSION['site_class_maxcat']) && $_SESSION['site_class_maxcat'] <= $groupnums && $_SESSION['site_class_maxcat'] != 0) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('category_error_category_catnum'));
			return;
		} else {
			//breadcrumb
			$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add');

			//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
			$form_class->setDefaults(array(
				'languages' => $_SESSION['site_deflang']
				)
			);

			$form_class->addFormRule('check_classifieds_addcategory');
			if ($form_class->validate()) {
				$form_class->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name        = $form_class->getSubmitValue('name');
				$parent      = intval($form_class->getSubmitValue('par'));
				$filename    = "";
				$timer_start = $form_class->getSubmitValue('timer_start');
				$timer_end   = $form_class->getSubmitValue('timer_end');

				//ha hasznaljuk a leirast a kategoriakhoz
				if (!empty($_SESSION['site_class_is_catdesc'])) {
					$desc = $form_class->getSubmitValue('desc');
				} else {
					$desc = "";
				}

				//kep feltoltese
				if (!empty($_SESSION['site_class_is_catpic'])) {
					if ($file->isUploadedFile()) {
						$filevalues = $file->getValue();
						$sdir = preg_replace('|/$|','', $_SESSION['site_class_catpicdir']).'/';
						$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
						$tn_name = 'tn_'.$filename;

						//kep atmeretezese
						include_once 'includes/function.images.php';
						if (($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_class_catpicwidth'], $_SESSION['site_class_catpicheight'])) && ($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_class_catpictwidth'], $_SESSION['site_class_catpictheight']))) {
							@chmod($sdir.$filename,0664);
							@chmod($sdir.$tn_name,0664);

							@unlink($filevalues['tmp_name']);
						}
						$form_class->setElementError('picture', $locale->get('category_error_category_picupload'));
					}
				}

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (!empty($_SESSION['site_multilang'])) {
					$languages = $form_class->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				//lekerdezzuk a legmagasabb sorszamokat
				$maxorder = 0;
				$query = "
					SELECT MAX(sortorder) AS sortorder 
					FROM iShark_Classifieds_Category 
				";
				$result =& $mdb2->query($query);
				while ($row = $result->fetchRow())
				{
					$maxorder = $row['sortorder'];
				}

				$category_id = $mdb2->extended->getBeforeID('iShark_Classifieds_Category', 'category_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Classifieds_Category 
					(category_id, category_name, category_desc, parent, sortorder, add_user_id, add_date, mod_user_id, mod_date, 
					 is_active, timer_start, timer_end, is_preferred, picture, lang) 
					VALUES 
					($category_id, '".$name."', '".$desc."', $parent, $maxorder+1, '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', NOW(), 
					 1, '$timer_start', '$timer_end', 0, '".$filename."', '".$languages."')
				";
				$mdb2->exec($query);

				//loggolas
				logger($page.'_'.$sub_act);

				//"fagyasztjuk" a form-ot
				$form_class->freeze();

				//visszadobjuk a lista oldalra
				header('Location: admin.php?p='.$module_name.'&act='.$page);
				exit;
			}
		}
	} //hozzaadas vege

	/**
	 * Ha modositunk egy kategoriat
	 */
	if ($sub_act == "mod" && $cid != 0) {
		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;cid='.$cid);

		//beallitjuk az alapertelmezett ertekeket, csak modositasnal
		$form_class->setDefaults($form_defaults);

		//ellenorzes, vegso muveletek
		$form_class->addFormRule('check_classifieds_modcategory');
		if ($form_class->validate()) {
			$form_class->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$oldpic = $form_class->getSubmitValue('oldpic');

			//ha ki akarjuk torolni a regi kepet
			if (!empty($_SESSION['site_class_is_catpic'])) {
				if (isset($delpic) && $delpic->getChecked()) {
					$filename = "";
					if (file_exists($_SESSION['site_class_catpicdir'].'/'.$oldpic)) {
						@unlink($_SESSION['site_class_catpicdir'].'/'.$oldpic);
						@unlink($_SESSION['site_class_catpicdir'].'/tn_'.$oldpic);
					}
				}

				//kep feltoltese
				if ($file->isUploadedFile()) {
					$filevalues = $file->getValue();
					$sdir = preg_replace('|/$|','', $_SESSION['site_class_catpicdir']).'/';
					$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
					$tn_name = 'tn_'.$filename;

					//kep atmeretezese
					include_once 'includes/function.images.php';
					if (($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_class_catpicwidth'], $_SESSION['site_class_catpicheight'])) && ($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_class_catpictwidth'], $_SESSION['site_class_catpictheight']))) {
						@chmod($sdir.$filename,0664);
						@chmod($sdir.$tn_name,0664);

						@unlink($filevalues['tmp_name']);
						//ha volt regi kep, akkor toroljuk
						if ($oldpic != "") {
							if (file_exists($_SESSION['site_class_catpicdir'].'/'.$oldpic)) {
								@unlink($_SESSION['site_class_catpicdir'].'/'.$oldpic);
								@unlink($_SESSION['site_class_catpicdir'].'/tn_'.$oldpic);
							}
						} //regi kep torlesenek vege
					}
					$form_class->setElementError('picture', $locale->get('category_error_category_picupload'));
				}
			}

			$name        = $form_class->getSubmitValue('name');
			$timer_start = $form_class->getSubmitValue('timer_start');
			$timer_end   = $form_class->getSubmitValue('timer_end');

			//ha hasznaljuk a leirast a kategoriakhoz
			if (!empty($_SESSION['site_class_is_catdesc'])) {
				$desc = $form_class->getSubmitValue('desc');
			} else {
				$desc = "";
			}

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (!empty($_SESSION['site_multilang'])) {
				$languages = $form_class->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			$query = "
				UPDATE iShark_Classifieds_Category 
				SET category_name = '".$name."', 
					category_desc = '".$desc."', 
					mod_user_id   = '".$_SESSION['user_id']."', 
					mod_date      = NOW(),
					timer_start   = '$timer_start',
					timer_end     = '$timer_end',
					picture       = '".$filename."',
					lang          = '".$languages."'
				WHERE category_id = $cid
			";
			$mdb2->exec($query);

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form_class->freeze();

			//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
			if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
				header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
				exit;
			} else {
				header('Location: admin.php?p='.$module_name.'&act='.$page);
				exit;
			}
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form_class->accept($renderer);

	$tpl->assign('tiny_fields', 'desc');
	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form',        $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha sorrendet modositunk
 */
if ($sub_act == "ord") {
    if ($cid != 0) {
    	if (isset($_GET['way']) && ($_GET['way'] == "up" || $_GET['way'] == "down")) {
    		// Attól függ, hogy lefelé vagy felfelé akarjuk mozgatni
    		$gt_lt  = ($_GET['way'] == 'up' ? '<' : '>');
    		$order  = ($_GET['way'] == 'up' ? 'DESC' : '');
    		$query  = "
    			SELECT sortorder 
    			FROM iShark_Classifieds_Category 
    			WHERE category_id = $cid
    		";
    		$result =& $mdb2->query($query);
    		while ($regihely = $result->fetchRow()) {
    			// Kicserélendõ elem kiválasztása, gt_lt tõl függõen az alatta, vagy felette levõ elem
    			$query = "
    				SELECT category_id, sortorder 
    				FROM iShark_Classifieds_Category 
    				WHERE parent = $par AND sortorder $gt_lt $regihely[sortorder]
    				ORDER BY sortorder $order
    			";
    			$mdb2->setLimit(1);
    			$csere = $mdb2->query($query);
    			// sorrend adatok cseréje:
    			while ($ujhely = $csere->fetchRow()) {
    				$query = "
    					UPDATE iShark_Classifieds_Category 
    					SET sortorder = $ujhely[sortorder] 
    					WHERE category_id = $cid
    				";
    				$mdb2->exec($query);
    				$query = "
    					UPDATE iShark_Classifieds_Category 
    					SET sortorder = $regihely[sortorder] 
    					WHERE category_id = $ujhely[category_id]
    				";
    				$mdb2->exec($query);
    			}
    		}
    	}

    	//loggolas
    	logger($page.'_'.$sub_act);

    	//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
    	if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
    		header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
    		exit;
    	} else {
    		header('Location: admin.php?p='.$module_name.'&act='.$page);
    		exit;
    	}
    }  else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('category_error_category_noexists'));
		return;
	}
} //sorrend modositas vege

/**
 * ha aktivalunk vagy inaktivalunk egy kategoriat
 */
if ($sub_act == "act") {
    if ($cid != 0) {
		include_once $include_dir.'/function.check.php';

		check_active('iShark_Classifieds_Category', 'category_id', $cid);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('category_error_category_noexists'));
		return;
	}
}

/**
 * ha torlunk egy kategoriat
 */
if ($sub_act == "del") {
	if ($cid != 0) {
		$query = "
			DELETE FROM iShark_Classifieds_Category 
			WHERE category_id = $cid OR parent = $cid
		";
		$mdb2->exec($query);

		//kitoroljuk a bejegyzest a termek - kategoria kapcsolotablabol is
		$query = "
			DELETE FROM iShark_Classifieds_Advert_Category 
			WHERE category_id = $cid
		";
		$mdb2->exec($query);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('category_error_category_noexists'));
		return;
	}
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	include_once $include_dir.'/function.classifieds.php';

	if ($_SESSION['site_class_maxcat'] > $groupnums || $_SESSION['site_class_maxcat'] == 0) {
		$add_new = array (
			array(
				'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
				'title' => $locale->get('category_title_category_add'),
				'pic'   => "add.jpg"
			)
		);
		$tpl->assign('add_new', $add_new);
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('category_list', categories(TRUE, 0, '', 0, 0));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = "classifieds_categories_list";
}

?>