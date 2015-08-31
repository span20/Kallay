<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

$attached_downloads = ($_SESSION['site_cnt_is_attached_download'] && isModule('downloads'));
$attached_galleries = ($_SESSION['site_cnt_is_attached_gallery'] && isModule('gallery'));
$attached_contents  = ($_SESSION['site_cnt_is_attached_content']);
$attached_forms     = ($_SESSION['site_cnt_is_attached_forms']);

//$attached_links     = ($_SESSION['site_cnt_is_attached_link']);

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
        $tpl->assign('errormsg', $locale->get('contents_error_not_exists'));
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
        'content2'     => $row['content2'],
        'heading_color'     => $row['heading_color'],
        'timer_start' => $timer_start,
        'timer_end'   => $timer_end,
        'languages'   => $row['lang'],
        'lead_len'    => $_SESSION['site_leadmax']-strlen($row['lead']),
        'indexpage'   => $row['is_index'],
    );

    // kapcsolodo tartalmak alapertelmezes beallitasa
    if ($attached_contents) {
        $query = "
            SELECT a_content_id 
            FROM iShark_Contents_Contents 
            WHERE content_id = $cid
        ";
        $result =& $mdb2->query($query);
        $form_defaults['a_contents'] = $result->fetchCol();
    }

    // kapcsolodo kulso linkek
/*	if ($attached_links) {
		$values = "";
    $query = "
			SELECT link, title
			FROM iShark_Contents_Links
			WHERE content_id = $cid AND link <> '' 
		";
    $result =& $mdb2->query($query);
    if ( $result->numRows() > 0 ) {
			while($row = $result->fetchRow()) {
				$values .= $row['link'].",".$row['title'].",";
			}
    }
		$bodyonload[] = "addLink('".$values."')";
	} */

    // kapcsolodo galeriak alapertelmezes beallitasa
    if ($attached_galleries) {
        $query = "
            SELECT gallery_id 
            FROM iShark_Contents_Galleries 
            WHERE content_id = $cid
        ";
        $result =& $mdb2->query($query);
        $form_defaults['galleries'] = $result->fetchCol();
    }

    // Kapcsolodo letoltesek alapertelmezes beallitasa
    if ($attached_downloads) {
        $query = "
            SELECT download_id 
            FROM iShark_Contents_Downloads 
            WHERE content_id = $cid
        ";
        $result =& $mdb2->query($query);
        $form_defaults['downloads'] = $result->fetchCol();
    }

    // kapcsolodo urlapok alapertelmezett beallitasa
	if ($attached_forms) {
	    $query = "
			SELECT form_id 
			FROM iShark_Contents_Forms 
			WHERE content_id = $cid
		";
		$result =& $mdb2->query($query);
		$form_defaults['forms'] = $result->fetchCol();
	}

    $tpl->assign('show', $row['type']);
    $content_picture = $filename = $row['picture'];
}

// alap valtozok hozzarendelese a template-hez.
$tpl->assign('cid', $cid);

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
            if (isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == 1) {
                $fieldorder   = "ORDER BY c.timer_start ";
                $fieldselect4 = "selected";
            }
            break;
        case 4:
            if (isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == 1) {
                $fieldorder   = "ORDER BY c.timer_end ";
                $fieldselect5 = "selected";
            }
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
if ($sub_act == "add" || $sub_act == "mod") {
    $javascripts[] = "javascripts";
    $javascripts[] = "javascript.contents";

    //szukseges fuggvenykonyvtarak betoltese
    require_once 'HTML/QuickForm.php';
    require_once 'HTML/QuickForm/jscalendar.php';
    require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
    require_once $include_dir.'/function.check.php';
    require_once $include_dir.'/function.contents.php';

    $titles = array('add' => $locale->get('contents_title_add'), 'mod' => $locale->get('contents_title_mod'));

    //elinditjuk a form-ot
    $form =& new HTML_QuickForm('frm_contents', 'post', 'admin.php?p='.$module_name);
    $form->removeAttribute('name');

    //a szukseges szoveget jelzo resz beallitasa
    $form->setRequiredNote($locale->get('contents_form_required_note'));

    //form-hoz elemek hozzadasa
    $form->addElement('header', 'cnt_content', $locale->get('contents_form_header'));
    $form->addElement('hidden', 'act',         $page);
    $form->addElement('hidden', 'sub_act',     $sub_act);
    $form->addElement('hidden', 'field',       $field);
    $form->addElement('hidden', 'ord',         $ord);

    //ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
    if (!empty($_SESSION['site_multilang'])) {
        include_once $include_dir.'/functions.php';
        $form->addElement('select', 'languages', $locale->get('contents_field_lang'), $locale->getLocales());
    }

    //cim
    $form->addElement('text', 'title', $locale->get('contents_field_title'));

	$form->addElement('select', 'heading_color', 'Kiemeltek színe', array(
		'' => '--',
		'kek' => 'Kék',
		'narancs' => 'Narancs',
		'rozsaszin' => 'Rózsaszín',
		'zold' => 'Zöld',
		'narancs2' => 'Narancs 2'
	));
	
    //ha hasznalunk tag-eket
    if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
        // akkor:
        $form->addElement('text', 'tags', $locale->get('contents_field_tags'));
    }

    //ha engedelyezve van az idozites a hirekre
    if (!empty($_SESSION['site_conttimer'])) {
        $form->addGroup(
            array(
                HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly'=>'readonly')),
                HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
                HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('contents_deltimer'), 'onclick="deltimer(\'timer_start\')"')
            ),
            'date_start', $locale->get('contents_field_timerstart'), null, false
        );
        $form->addGroup(
            array(
                HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
                HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
                HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('contents_deltimer'), 'onclick="deltimer(\'timer_end\')"')
            ),
            'date_end', $locale->get('contents_field_timerend'), null, false
        );
    }

    //ha hasznaljuk a bevezeto szoveget
    if (!empty($_SESSION['site_cnt_is_lead_other'])) {
        // akkor:
        $leadarea =& $form->addElement('textarea', 'lead', $locale->get('contents_field_lead'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" id="leadfield"');
        $leadarea->setCols(95);
        $leadarea->setRows(7);
        $form->addElement('text', 'lead_len', $locale->get('contents_field_leadlen'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));
    }

    //tartalom szoveg - ez minden esetben van
    $contentarea =& $form->addElement('textarea', 'content', 'Jobb hasáb');
    $contentarea->setCols(30);
    $contentarea->setRows(30);
	
	$contentarea =& $form->addElement('textarea', 'content2', 'Bal hasáb');
    $contentarea->setCols(30);
    $contentarea->setRows(30);

    //kapcsolodo tatalmak
    if ($attached_contents) {
        $query = "
            SELECT content_id, title 
            FROM iShark_Contents 
            WHERE type='1' AND is_active='1' AND content_id != $cid
            ORDER BY title
        ";
        $result =& $mdb2->query($query);
        $a_contents =& $form->addElement('select', 'a_contents', $locale->get('contents_field_a'), $result->fetchAll('0', TRUE));
        $a_contents->setMultiple(TRUE);
        $a_contents->setSize(5);
    }

    //kapcsolodo kulso linkek
/*	if ($attached_links) {
	    $links = array();
	    $links['div']   =& HTML_QuickForm::createElement('hidden', '', 0, array('id' => 'theValue'));
	    $links['link']  =& HTML_QuickForm::createElement('text',   'links_link',  'Link', array('id' => 'link_0'));
        $links['title'] =& HTML_QuickForm::createElement('text',   'links_title', 'Cím', array('id' => 'title_0'));
        $links['new']   =& HTML_QuickForm::createElement('link',   'links_new',   '', 'javascript:void(0);', 'Új link', array('onclick' => 'addLink();'));
        $links['mydiv'] =& HTML_QuickForm::createElement('static', 'links_mydiv', '', '<div id="myDiv" style="display: none;"> </div>');
        $form->addGroup($links, 'links[0]', 'Külsõ link', ',&nbsp');
	}*/

    //kapcsolodo galeria
    if ($attached_galleries) {
        $query = "
            SELECT gallery_id, name
            FROM iShark_Galleries
        ";
        //ha nincs engedelyezve a videogaleria, akkor kiszedjuk
        if (empty($_SESSION['site_gallery_is_video'])) {
            $query .= " WHERE type != 'v' ";
        }
        $query .= " ORDER BY name";
        $result =& $mdb2->query($query);
        $galleries =& $form->addElement('select', 'galleries', $locale->get('contents_field_galleries'), $result->fetchAll(0, TRUE));
        $galleries->setMultiple(TRUE);
        $galleries->setSize(5);
    }

    // Kapcsolodo letoltesek
    if ($attached_downloads) {
        $downloads =& $form->addElement('select', 'downloads', $locale->get('contents_field_downloads'), getDownloadDirs());
        $downloads->setMultiple(TRUE);
        $downloads->setSize(5);
    }

    //kapcsolodo urlapok
	if ($attached_forms) {
	    $query = "
			SELECT form_id, form_title
			FROM iShark_Forms
			WHERE is_active = '1' AND is_deleted = '0'
			ORDER BY form_title
		";
	    $result =& $mdb2->query($query);
		$forms =& $form->addElement('select', 'forms', $locale->get('contents_field_forms'), $result->fetchAll(0, TRUE));
		$forms->setMultiple(TRUE);
		$forms->setSize(5);
	}

    //szurok beallitasa
    $form->applyFilter('__ALL__', 'trim');

    /**
     * Szabalyok
     */
    //ha tobbnyelvu az oldal
    if (!empty($_SESSION['site_multilang'])) {
        $form->addRule('languages', $locale->get('contents_error_no_lang'), 'required');
    }
    //cim vizsgalata
    $form->addRule('title', $locale->get('contents_error_no_title'), 'required');

    //ha hasznaljuk a bevezeto szoveget
    if (!empty($_SESSION['site_cnt_is_lead_other'])) {
        $form->addRule('lead', $locale->get('contents_error_no_lead'), 'required');
    }

    $form->addRule('content', $locale->get('contents_error_no_content'), 'required');

    $form->addElement('submit', 'submit', $locale->get('contents_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('contents_form_reset'),  'class="reset"');

    /**
     * ha uj tartalmat adunk hozza
     */
    if ($sub_act == "add") {
        //beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
        $form->setDefaults(array(
            'type'      => 0,
            'languages' => $_SESSION['site_deflang'],
            'mainnews'  => 0,
            'lead_len'  => $_SESSION['site_leadmax'],
            'indexpage' => 0
            )
        );

        //ellenorzes, vegso muveletek
        if ($form->validate()) {
            $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

            //bevezeto szoveg csak akkor van, ha ezt engedelyeztuk
            if (!empty($_SESSION['site_is_lead']) || !empty($_SESSION['site_cnt_is_lead_other'])) {
                $lead = $form->getSubmitValue('lead');
            } else {
                $lead = "";
            }

            $title       = $form->getSubmitValue('title');
            $content     = $form->getSubmitValue('content');
            $content2     = $form->getSubmitValue('content2');
            $heading_color = $form->getSubmitValue('heading_color');
            $tags        = $form->getSubmitValue('tags');

            $empty_time  = '0000-00-00 00:00:00';
            $timer_start = $form->getSubmitValue('timer_start');
            $timer_end   = $form->getSubmitValue('timer_end');
            $timer_start = empty($timer_start) ? $empty_time : $timer_start;
            $timer_end   = empty($timer_end) ? $empty_time : $timer_end;

            //ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
            if (!empty($_SESSION['site_multilang'])) {
                $languages = $form->getSubmitValue('languages');
            } else {
                $languages = $_SESSION['site_deflang'];
            }

            $content_id = $mdb2->extended->getBeforeID('iShark_Contents', 'content_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Contents
                (content_id, type, title, lead, content, content2, add_user_id, add_date,
                mod_user_id, mod_date, is_active, timer_start, timer_end, lang, heading_color)
                VALUES
                ($content_id, '1', '".$title."', '".$lead."', '".$content."', '".$content2."', ".$_SESSION['user_id'].", NOW(),
                ".$_SESSION['user_id'].", NOW(), '1', '$timer_start', '$timer_end', '".$languages."', '".$heading_color."')
            ";
            $mdb2->exec($query);
            $last_content_id = $mdb2->extended->getAfterID($content_id, 'iShark_Contents', 'content_id');

            //ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
            if (!empty($tags)) {
                include_once $include_dir.'/function.tags.php';
                addTags($tags, 'contents', $last_content_id);
            }

            // Kapcsolodo tartalmak mentese
            if (isset($a_contents)) {
                $contents_selected = $a_contents->getSelected();
                if (is_array($contents_selected)) {
                    foreach ($contents_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Contents 
                            (content_id, a_content_id) 
                            VALUES 
                            ($last_content_id, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            // Kapcsolodo galeria mentese
            if (isset($galleries)) {
                $gallery_selected = $galleries->getSelected();
                if (is_array($gallery_selected)) {
                    foreach ($gallery_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Galleries 
                            VALUES 
                            ($last_content_id, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            // Kapcsolodo letoltes mappak mentese
            if (isset($downloads)) {
                $downloads_selected = $downloads->getSelected();
                if (is_array($downloads_selected)) {
                    foreach ($downloads_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Downloads 
                            VALUES 
                            ($last_content_id, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            //kapcsolodo urlapok
            if (isset($forms)) {
                $forms_selected = $forms->getSelected();
                if (is_array($forms_selected)) {
                    foreach ($forms_selected as $k) {
                        $query = "
							INSERT INTO iShark_Contents_Forms
							VALUES
							($last_content_id, $k)
						";
                        $mdb2->exec($query);
                    }
                }
            }

            //kapcsolodo kulso linkek
            if (isset($links)) {
                $links_add = $form->getSubmitValue('links');
                if (is_array($links_add)) {
                    foreach($links_add as $link) {
                    	if ( !empty( $link['links_link'] ) && !empty( $link['links_title'] ) ) {
                        $query = "
													INSERT INTO iShark_Contents_Links
													VALUES
													($last_content_id, '".$link['links_link']."', '".$link['links_title']."')
												";
                        $mdb2->exec($query);
                      }
                    }
                }
            }

            //loggolas
            logger($page.'_'.$sub_act);

            //"fagyasztjuk" a form-ot
            $form->freeze();

            //visszadobjuk a lista oldalra
            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }
    } //tartalom hozzadas vege

    /**
     * ha modositjuk a tartalmat
     */
    if ($sub_act == "mod") {
        // Hibakezeles ha nem adott meg content_id-t.
        if ($cid == '0') {
            $acttpl = 'error';
            $tpl->assign('errormsg', $locale->get('contents_error_not_exists'));
            return;
        }

        include_once $include_dir.'/function.contents.php';

        // beallitjuk az alapertelmezett form ertekeket.
        $form->setDefaults($form_defaults);

        //megvizsgaljuk, hogy beallitastol fuggoen, modosithatja- a tartalmat
        if (check_contents_perm($cid) === false) {
            $acttpl = "error";
            $tpl->assign('errormsg', $locale->get('contents_error_no_permission'));
            return;
        }

        //form-hoz elemek hozzaadasa - csak modositasnal
        $form->addElement('hidden', 'cid',     $cid);
        $form->addElement('hidden', 'act',     $page);
        $form->addElement('hidden', 'sub_act', $sub_act);
        
        //beallitjuk a tag-eket
        if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
			$tags_mod = "";
            $query = "
                SELECT tm.tag_id, t.tag_name 
                FROM iShark_Tags_Modules tm
				LEFT JOIN iShark_Tags t ON t.tag_id = tm.tag_id
                WHERE tm.module_name = 'contents' and tm.id = $cid
            ";
            $result =& $mdb2->query($query);
			while($row = $result->fetchRow()) {
				$tags_mod .= $row['tag_name']." ";
			}
			$tags_mod = trim($tags_mod);
            $form->setDefaults(
				array(
					'tags' => $tags_mod
				)
			);
        }

        //ellenorzes, vegso muveletek
        if ($form->validate()) {
            $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

            //bevezeto szoveg csak akkor van, ha ezt engedelyeztuk
            if (!empty($_SESSION['site_is_lead']) || !empty($_SESSION['site_cnt_is_lead_other'])) {
                $lead = $form->getSubmitValue('lead');
            } else {
                $lead = "";
            }

            if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
                $tags = $form->getSubmitValue('tags');
            } else {
                $tags = "";
            }

            $title       = $form->getSubmitValue('title');
            $content     = $form->getSubmitValue('content');
			$content2     = $form->getSubmitValue('content2');
            $heading_color = $form->getSubmitValue('heading_color');
            $timer_start = $form->getSubmitValue('timer_start');
            $timer_end   = $form->getSubmitValue('timer_end');

            //ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
            if (!empty($_SESSION['site_multilang'])) {
                $languages = $form->getSubmitValue('languages');
            } else {
                $languages = $_SESSION['site_deflang'];
            }

            // verziokovetes
            if (!empty($_SESSION['site_cnt_version'])) {
                $query = "
                    SELECT c.content_id AS id ,c.title, c.lead, c.content, c.mod_user_id, c.picture
                    FROM iShark_Contents c
                    WHERE c.content_id = $cid
                ";
                $result =& $mdb2->query($query);
                $row = $result->fetchRow();

                $versions_id = $mdb2->extended->getBeforeID('iShark_Contents_Versions', 'id', TRUE, TRUE);
                $query = "
                    INSERT INTO iShark_Contents_Versions
                    (id, parent_content_id, title, lead, content, mod_user_id, last_mod_user_id, mod_date)
                    VALUES
                    ($versions_id, ".$row['id'].", '".$row['title']."', '".$row['lead']."', '".$row['content']."', ".$_SESSION['user_id'].", ".$row['mod_user_id'].", NOW())
                ";
                $mdb2->exec($query);
            }

            // tartalom mentese
            $query = "
                UPDATE iShark_Contents
                SET lead        = '".$lead."',
                    content     = '".$content."',
                    content2     = '".$content2."',
                    mod_user_id = '".$_SESSION['user_id']."',
                    mod_date    = NOW(),
                    timer_start = '".$timer_start."',
                    timer_end   = '".$timer_end."',
                    lang        = '".$languages."',
                    title       = '".$title."',
                    heading_color = '".$heading_color."'
                WHERE content_id = $cid
            ";
            $affected = $mdb2->exec($query);
            $err = PEAR::isError($affected);

            if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
                //ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
                $query = "
                    DELETE FROM iShark_Tags_Modules 
                    WHERE module_name = 'contents' and id = $cid
                ";
                $mdb2->exec($query);

                /*if (!empty($tags)) {
                    include_once $include_dir.'/function.tags.php';
                    addTags($tags, 'contents', $cid);
                }*/
            }

            // Kapcsolodo tartalmak mentese
            if (!$err && isset($a_contents)) {
                $query = "
                    DELETE FROM iShark_Contents_Contents 
                    WHERE content_id = $cid
                ";
                $mdb2->exec($query);
                $contents_selected = $a_contents->getSelected();
                if (is_array($contents_selected)) {
                    foreach ($contents_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Contents 
                            (content_id, a_content_id) 
                            VALUES 
                            ($cid, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            // Kapcsolodo galeria mentese
            if (!$err && isset($galleries)) {
                $query = "
                    DELETE FROM iShark_Contents_Galleries 
                    WHERE content_id = $cid
                ";
                $mdb2->exec($query);
                $gallery_selected = $galleries->getSelected();
                if (is_array($gallery_selected)) {
                    foreach ($gallery_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Galleries 
                            VALUES 
                            ($cid, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            // Kapcsolodo letoltes mappak mentese
            if (!$err && isset($downloads)) {
                $query = "
                    DELETE FROM iShark_Contents_Downloads 
                    WHERE content_id = $cid
                ";
                $mdb2->exec($query);
                $downloads_selected = $downloads->getSelected();
                if (is_array($downloads_selected)) {
                    foreach($downloads_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Downloads 
                            VALUES 
                            ($cid, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            //kapcsolodo urlapok mentese
            if (!$err && isset($forms)) {
                $query = "
                    DELETE FROM iShark_Contents_Forms 
                    WHERE content_id = $cid
                ";
                $mdb2->exec($query);
                $forms_selected = $forms->getSelected();
                if (is_array($forms_selected)) {
                    foreach($forms_selected as $k) {
                        $query = "
                            INSERT INTO iShark_Contents_Forms 
                            VALUES 
                            ($cid, $k)
                        ";
                        $mdb2->exec($query);
                    }
                }
            }

            //kapcsolodo kulso linkek
            if (!$err && isset($links)) {
                $query = "
									DELETE FROM iShark_Contents_Links
									WHERE content_id = $cid
								";
                $mdb2->exec($query);
                $links_add = $form->getSubmitValue('links');
                if (is_array($links_add)) {
                    foreach($links_add as $link) {
                    	if ( !empty( $link['links_link'] ) && !empty( $link['links_title'] ) ) {
                        $query = "
													INSERT INTO iShark_Contents_Links
													VALUES
													($cid, '".$link['links_link']."', '".$link['links_title']."')
												";
                        $mdb2->exec($query);
                      }
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
    } //tartalom modositas vege

    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    //breadcrumb
    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('tiny_fields', 'content, content2');
    $tpl->assign('back_arrow',  'admin.php?p='.$module_name.'&amp;act='.$page);
    $tpl->assign('form',        $renderer->toArray());
    $tpl->assign('lang_title',  $titles[$sub_act]);

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

    //megvizsgaljuk, hogy beallitastol fuggoen, modosithatja- a tartalmat
    if (check_contents_perm($cid) === false) {
        $acttpl = "error";
        $tpl->assign('errormsg', $locale->get('contents_error_no_permission'));
        return;
    }

    //megvizsgaljuk, hogy letezik-e ilyen tartalom
    if ($cid == 0) {
        $acttpl = "error";
        $tpl->assign('errormsg', $locale->get('contents_error_not_exists'));
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
		if (!empty($_SESSION['site_cnt_version'])) {
			$query = "
				DELETE FROM iShark_Contents_Versions
				WHERE parent_content_id = $cid
			";
			$mdb2->exec($query);
		}

        // Kapcsolodo tartalom, galeria es letoltes torlese
        if ($attached_galleries) {
            $query = "
                DELETE FROM iShark_Contents_Galleries 
                WHERE content_id = $cid
            ";
            $mdb2->exec($query);
        }

        if ($attached_downloads) {
            $query = "
                DELETE FROM iShark_Contents_Downloads 
                WHERE content_id = $cid
            ";
            $mdb2->exec($query);
        }

        if ($attached_contents) {
            $query = "
                DELETE FROM iShark_Contents_Contents 
                WHERE content_id = $cid
            ";
            $mdb2->exec($query);
        }

        //kitoroljuk a tartalom kategoria kapcsolotablabol
        if (!empty($_SESSION['site_category'])) {
            $query = "
                DELETE FROM iShark_Contents_Category
                WHERE content_id = $cid
            ";
            $mdb2->exec($query);
        }
        
        //kitoroljuk a tag kapcsolotablabol
        if (!empty($_SESSION['site_cnt_is_tags']) && isModule('tags')) {
            $query = "
                DELETE FROM iShark_Tags_Modules
                WHERE module_name = 'contents' and id = $cid
            ";
            $mdb2->exec($query);
        }

        //kitoroljuk a hozzaszolas tablabol
		if (isModule('comments')) {
			$query = "
				DELETE FROM iShark_Comments 
				WHERE id = $cid AND module_name = 'contents'
			";
			$mdb2->exec($query);
		}

    }

    //loggolas
    logger($page.'_'.$sub_act);

    header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
    exit;
} //torles vege

/**
 * ha visszaalitjuk a tartalmat
 */
if ($sub_act == "restore") {
    if ( !empty( $_SESSION['site_cnt_version'] )  ) {

        include_once $include_dir.'/function.contents.php';

        //megvizsgaljuk, hogy beallitastol fuggoen, modosithatja- a tartalmat
        if (check_contents_perm($cid) === false) {
            $acttpl = "error";
            $tpl->assign('errormsg', $locale->get('contents_error_no_content'));
            return;
        }

        // verziokovetes
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
            (id, parent_content_id, title, lead, content, mod_user_id, last_mod_user_id, mod_date)
            VALUES
            ($versions_id,  ".$row['id'].", '".$row['title']."', '".$row['lead']."', '".$row['content']."', ".$_SESSION['user_id'].", ".$row['mod_user_id'].", NOW())
        ";
        $mdb2->exec($query);

        $query = "
            SELECT c.id, c.title, c.lead, c.content, c.picture
            FROM iShark_Contents_Versions c
            WHERE c.id = ".$_REQUEST['restore_version']."
        ";
        $result = $mdb2->query($query);
        $row = $result->fetchRow();

        $query = "
            UPDATE iShark_Contents
            SET title       = '".$row['title']."',
                lead        = '".$row['lead']."',
                content     = '".$row['content']."',
                mod_user_id = ".$_SESSION['user_id']."
            WHERE content_id = $cid
        ";
        $result = $mdb2->query($query);
    }
    header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
    exit;
}

/**
 * ha egy regebbi verziot akarunk megnezni
 */
if ($sub_act == "show") {
    if ( !empty( $_SESSION['site_cnt_version'] )  ) {
        $cvid = intval($_GET['cvid']);

        $query = "
            SELECT cv.id, cv.title, cv.lead, cv.content, u.name AS author, cv.mod_date, cv.parent_content_id
            FROM iShark_Contents_Versions cv
            LEFT JOIN ".DB_USERS.".iShark_Users u ON cv.mod_user_id = u.user_id
            WHERE cv.id = $cvid
        ";
        $result = $mdb2->query($query);
        $row = $result->fetchRow();

        $tpl->assign('page_data',  $row);
        $tpl->assign('lang_title', $locale->get('contents_field_versiontitle'));
        $tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page);

        $acttpl = 'contents_version_show';
    }
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
    $javascripts[] = 'javascript.contents';

    //lekerdezzuk a tartalmak listajat
    $query = "
        SELECT c.content_id AS cid, c.title AS ctitle, c.is_active AS cact, c.lang AS clang, c.type AS ctype, c.is_mainnews AS mnews
    ";
    //ha engedelyeztuk az idoziteset a tartalomnak
    if (isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == 1) {
        $query .= "
            , c.timer_start AS cstart, c.timer_end AS cend
        ";
    }
    $query .= "
        FROM iShark_Contents c
        WHERE c.type = '1'
        $fieldorder $order
    ";

    //lapozo
    require_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    $add_new = array(
        array(
            'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
            'title' => $locale->get('contents_title_add'),
            'pic'   => 'add.jpg'
        )
    );

    // verziokovetes
    if ( !empty( $_SESSION['site_cnt_version'] )  ) {
        $q = "
            SELECT cv.id, cv.parent_content_id, u.name AS author, cv.mod_date, cv.title
            FROM iShark_Contents_Versions cv
            LEFT JOIN ".DB_USERS.".iShark_Users u ON u.user_id = cv.last_mod_user_id
            JOIN iShark_Contents c ON c.content_id = cv.parent_content_id
            WHERE c.type = '1'
            ORDER BY cv.mod_date DESC
        ";
        $r = $mdb2->query( $q );

        while ( $d = $r->fetchRow() ) {
            foreach ( $paged_data['data'] as $key => $value ) {
                if ( $value['cid'] == $d['parent_content_id'] ) {
                    $paged_data['data'][$key]['versions'][$d['id']] = array(
                        'author'   => $d['author'],
                        'title'    => $d['title'],
                        'mod_date' => $d['mod_date']
                    );
                }
            }
        }
    }

    //atadjuk a smarty-nak a kiirando cuccokat
    $tpl->assign('page_data',  $paged_data['data']);
    $tpl->assign('page_list',  $paged_data['links']);
    $tpl->assign('add_new',    $add_new);
    $tpl->assign('back_arrow', 'admin.php');

    //megadjuk a tpl file nevet, amit atadunk az admin.php-nek
    $acttpl = 'contents_content_list';
}

?>