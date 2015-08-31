<?php 

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

/**
 * Kep modositasa 
 */
if ($sub_act == 'pmod') {
	$javascripts[] = "javascript.contents";

	$query = "
		SELECT g.type 
		FROM iShark_Galleries g 
		JOIN iShark_Galleries_Pictures gp ON gp.gallery_id = g.gallery_id 
		JOIN iShark_Pictures p ON p.picture_id = gp.picture_id 
		WHERE p.picture_id = $pid 
	";
	//ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
	if (empty($_SESSION['site_gallery_is_video'])) {
	    $query .= " AND g.type != 'v' ";
	}
	$gal =& $mdb2->query( $query );
	$ize = $gal->fetchRow();

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';
	$form =& new HTML_QuickForm('rename_frm', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'gallery_mod', $locale->get('form_header_pics_modify'));
	$form->addElement('hidden', 'act',         $page);
	$form->addElement('hidden', 'sub_act',     $sub_act);
	$form->addElement('hidden', 'gid',         $gid);
	$form->addElement('hidden', 'pid',         $pid);

	//video letoltheto-e
	if ($ize['type'] == 'v' && !empty($_SESSION['site_gallery_is_download']) && !empty($_SESSION['site_gallery_is_video'])){
		$isdownload =& $form->addElement('checkbox', 'is_download', $locale->get('field_video_is_download'));
	}

	//kep neve
	$form->addElement('text', 'name', $locale->get('field_pics_name'), array('maxlength' => 255));

	//kep leirasa
	$leadarea =& $form->addElement('textarea', 'description', $locale->get('field_pics_description'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" id="leadfield"');
	$leadarea->setCols(95);
	$leadarea->setRows(7);

	//leiras hosszusaga
	$form->addElement('text', 'lead_len', $locale->get('field_description_length'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//ha hasznalunk tag-eket
	if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		// akkor:
		$query_tags = "
			SELECT tag_id, tag_name
			FROM iShark_Tags
			ORDER BY tag_name
		";
		$result_tags =& $mdb2->query($query_tags);

		$tag_select =& $form->addElement('select', 'tags', $locale->get('field_pics_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);

		//alapertelmezett ertekek
		$query_deftags = "
			SELECT * 
			FROM iShark_Tags_Modules 
			WHERE module_name = 'picture' and id = $pid
		";
		$result_deftags =& $mdb2->query($query_deftags);
		$tag_select->setSelected($result_deftags->fetchCol());
	}

	$picture['lead_len'] = $_SESSION['site_gallery_max_desc'] - strlen($picture['description']);
	$form->setDefaults($picture);

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('name',        $locale->get('error_pics_name'),        'required');
	$form->addRule('description', $locale->get('error_pics_description'), 'required');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$name        = $form->getSubmitValue('name');
		$tags        = $form->getSubmitValue('tags');
		$description = $form->getSubmitValue('description');
		if (empty($description)) {
			$description = "";
		}
			
		if ($ize['type'] == 'v' && !empty($_SESSION['site_gallery_is_download'])){
			$is_download= $isdownload->getChecked() ? '1' : '0';
		} else {
			$is_download = 0;
		}
					
		$query = "
			UPDATE iShark_Pictures  
			SET	name        = '".$name."', 
				description = '".$description."',  
				mod_user_id = ".$_SESSION['user_id'].",
				mod_date    = NOW(),
				is_download = '$is_download'
			WHERE picture_id = $pid
		";
		$mdb2->exec($query);

		if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
			//ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
			$query = "
				DELETE FROM iShark_Tags_Modules 
				WHERE module_name = 'picture' AND id = $pid
			";
			$mdb2->exec($query);

			if (is_array($tags) && count($tags) > 0) {
				foreach ($tags as $key => $id) {
					$query = "
						INSERT INTO iShark_Tags_Modules 
						(tag_id, module_name, id, add_date) 
						VALUES 
						($id, 'picture', $pid, NOW())
					";
					$mdb2->exec($query);
				}
			}
		}

		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('form_pics_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_pics_reset'),  'class="reset"');

	$renderer =& new HTML_Quickform_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $locale->get('title_pic_modify'));

	$acttpl = 'dynamic_form';
}

/**
 * feltoltes 
 */
if ($sub_act == 'upl') {
	$javascripts[] = "javascript.contents";
	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';

	$form =& new HTML_QuickForm('upload_frm', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_upload_required_note'));

	$form->addElement('header', 'header_upl', $locale->get('form_header_pics_upload'));
	$form->addElement('hidden', 'act',        $page);
	$form->addElement('hidden', 'sub_act',    $sub_act);
	$form->addElement('hidden', 'gid',        $gid);

	if ($type == 'p') {
		$filetip = $locale->get('field_pics_file');
	} else {
	    //ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
	    if (!empty($_SESSION['site_gallery_is_video'])) {
	        $filetip = $locale->get('field_video_file');
	        if (!empty($_SESSION['site_gallery_is_download'])) {
	            $isdownload =& $form->addElement('checkbox', 'is_download', $locale->get('field_upload_video_is_download'));
	        }
	    }
	}

	$query = "
		SELECT g.type 
		FROM iShark_Galleries g 
		WHERE g.gallery_id = $gid 
	";
	//ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
	if (empty($_SESSION['site_gallery_is_video'])) {
	    $query .= " AND g.type != 'v' ";
	}
	$gal =& $mdb2->query($query);
	$ize = $gal->fetchRow();

	$file =& $form->addElement('file', 'picture', $filetip);

	//kep neve
	$form->addElement('text', 'name', $locale->get('field_upload_pics_name'), array('maxlength' => 255));

	//kep leirasa
	$leadarea =& $form->addElement('textarea', 'description', $locale->get('field_upload_pics_description'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" id="leadfield"');
	$leadarea->setCols(95);
	$leadarea->setRows(7);

	//leiras hosszusaga
	$form->addElement('text', 'lead_len', $locale->get('field_upload_description_length'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//ha hasznalunk tag-eket
	if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		// akkor:
		$query_tags = "
			SELECT tag_id, tag_name
			FROM iShark_Tags 
			ORDER BY tag_name
		";
		$result_tags =& $mdb2->query($query_tags);

		$tag_select =& $form->addElement('select', 'tags', $locale->get('field_upload_pics_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);
	}

	//szuro
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok
	$form->addRule('name',        $locale->get('error_upload_pics_name'),        'required');
	$form->addRule('description', $locale->get('error_upload_pics_description'), 'required');
	$form->addRule('picture',     $locale->get('error_upload_file'),             'uploadedfile');

	//alapertelmezett ertekek
	$form->setDefaults(
	    array(
	        'lead_len' => $_SESSION['site_gallery_max_desc']
	    )
	);

	// Adatok mentése
	if ($form->validate()) {
		if (!$file->isUploadedFile()) {
			header('Location: admin.php?p='.$module_name.'&act=pic&pic_act=upl&gid='.$gid);
			exit;
		}

		$filevalues = $file->getValue();
		$gdir       = preg_replace('|/$|','', $_SESSION['site_galerydir']).'/';
		$filename   = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
		$tn_name    = 'tn_'.$filename;
		$name       = $mdb2->escape($form->getSubmitValue('name'));
		$tags       = $form->getSubmitValue('tags');

		if ($type == 'p') {
			// Kép feltöltése átméretezéssel
			include_once 'includes/function.images.php';

			if (($pic= img_resize($filevalues['tmp_name'], $gdir.$filename, $_SESSION['site_picwidth'], $_SESSION['site_picheight'])) && 
				($tn = img_resize($filevalues['tmp_name'], $gdir.$tn_name, $_SESSION['site_thumbwidth'], $_SESSION['site_thumbheight']))) {

				@chmod($gdir.$filename, 0664);
				@chmod($gdir.$tn_name, 0664);

				$description = $form->getSubmitValue('description');
				if (empty($description)) {
					$description = "";
				}

				$picture_id = $mdb2->extended->getBeforeID('iShark_Pictures', 'picture_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Pictures
					(picture_id, realname, name, width, height, tn_width, tn_height, add_user_id, add_date, description)
					VALUES
					($picture_id, '$filename', '$name', $pic[width], $pic[height], $tn[width], $tn[height], $_SESSION[user_id], now(), '".$description."')
				";
				$mdb2->exec($query);
				$last_picture_id = $mdb2->extended->getAfterID($picture_id, 'iShark_Pictures', 'picture_id');

				//ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
				if (is_array($tags) && count($tags) > 0) {
					foreach ($tags as $key => $id) {
						$query = "
							INSERT INTO iShark_Tags_Modules 
							(tag_id, module_name, id) 
							VALUES 
							($id, 'picture', $last_picture_id)
						";
						$mdb2->exec($query);
					}
				}

				$query = "
					INSERT INTO iShark_Galleries_Pictures
					(gallery_id, picture_id)
					VALUES
					($gid, $last_picture_id)
				";
				$mdb2->exec($query);
				@unlink($filevalues['tmp_name']);

				logger($page.'_'.$sub_act);

				header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);
				exit;
			}
		} else {
		    if (!empty($_SESSION['site_gallery_is_video'])) {
    			if($filevalues['type'] == 'video/x-msvideo' || $filevalues['type'] == 'video/mpeg' || $filevalues['type'] == 'video/x-ms-wmv'){
    				$file->moveUploadedFile( $gdir, $filename );
    				@chmod($gdir.$filename, 0664);

    				if (!empty($_SESSION['site_gallery_is_download'])) {
    					$is_download= $isdownload->getChecked() ? '1' : '0';
    				} else {
    					$is_download = 0;
    				}

    				$description = $form->getSubmitValue('description');
    				if (empty($description)) {
    					$description = "";
    				}

    				$picture_id = $mdb2->extended->getBeforeID('iShark_Pictures', 'picture_id', TRUE, TRUE);
    				$query = "
    					INSERT INTO iShark_Pictures
    					(picture_id, realname, name, add_user_id, add_date, description, is_download)
    					VALUES
    					($picture_id, '$filename', '$name', $_SESSION[user_id], now(), '".$description."', '$is_download')
    				";
    				$mdb2->exec($query);
    				$last_picture_id = $mdb2->extended->getAfterID($picture_id, 'iShark_Pictures', 'picture_id');

    				//ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
    				if (is_array($tags) && count($tags) > 0) {
    					foreach ($tags as $key => $id) {
    						$query = "
    							INSERT INTO iShark_Tags_Modules 
    							(tag_id, module_name, id, add_date) 
    							VALUES 
    							($id, 'picture', $last_picture_id, NOW())
    						";
    						$mdb2->exec($query);
    					}
    				}

    				$query = "
    					INSERT INTO iShark_Galleries_Pictures
    					(gallery_id, picture_id)
    					VALUES
    					($gid, $last_picture_id)
    				";
    				$mdb2->exec($query);
    				@unlink($filevalues['tmp_name']);

    				logger($page.'_'.$sub_act);

    				header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);
    				exit;
    			}
		    }
		}

		@unlink($filevalues['tmp_name']);
		@unlink($gdir.$filename);
		@unlink($gdir.$tn_name);

		$form->setElementError('picture', $locale->get('error_upload'));
	}

	$form->addElement('submit', 'submit', $locale->get('form_upload_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_upload_reset'),  'class="reset"');

	$renderer =& new HTML_Quickform_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $locale->get('title_upload'));

	$acttpl = 'dynamic_form';
}

/**
 * Kép megtekintése 
 */
if ($sub_act == 'view') {
	$tpl->assign('pic',        $picture);
	$tpl->assign('lang_title', $locale->get('title_pic_view'));
	$tpl->assign('back_arrow', htmlentities($_SERVER['HTTP_REFERER']));

	$acttpl = 'gallery_view';
}

/**
 * Kép törlése 
 */
if ($sub_act == 'pdel') {
	$gdir = preg_replace('|/$|', '', $_SESSION['site_galerydir']).'/';

	$query = "
		DELETE FROM iShark_Galleries_Pictures 
		WHERE gallery_id = $gid AND picture_id = $pid
	";
	$mdb2->exec($query);

	$query = "
		DELETE FROM iShark_Pictures 
		WHERE picture_id = $pid
	";
	$mdb2->exec($query);

	@unlink($gdir.$picture['realname']);
	@unlink($gdir.'tn_'.$picture['realname']);

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);
	exit;
}

/**
 * képek listázása 
 */
if ($sub_act == 'plst') {
	// Galériához tartozó képek lekérdezése
	$query = "
		SELECT P.* 
		FROM iShark_Galleries_Pictures GP
		LEFT JOIN iShark_Pictures P ON GP.picture_id = P.picture_id
		WHERE GP.gallery_id = $gid
		ORDER BY name
	";

	include_once 'Pager/Pager.php';

	$paged_data  = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);
	$paged_data2 = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//breadcrumb
	$breadcrumb->add($locale->get('tabs_title_piclist'), '#');

	$tpl->assign('lang_title', $locale->get('tabs_title_piclist').' ('.$gallery['name'].')');
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);

	$acttpl = 'gallery_pics';
}

// Galéria törlése
if ($sub_act == 'gdel') {
	$query = "
		DELETE FROM iShark_Galleries
		WHERE iShark_Galleries.gallery_id = $gid
	";
	$mdb2->exec($query);

	$query = "
		DELETE FROM iShark_Galleries_Pictures
		WHERE gallery_id = $gid
	";
	$mdb2->exec($query);

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page);
	exit;
}

// Mappa aktiválás
if ($sub_act == 'act') {
	include_once 'includes/function.check.php';

	check_active('iShark_Galleries', 'gallery_id', $gid);

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page);
	exit;
}

// Mappa modositasa
if ($sub_act == 'gmod') {
    $javascripts[] = "javascript.contents";

	include_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_Quickform('frm_gallery', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_gallery_required_note'));

	$form->addElement('header', 'header_gal', $locale->get('form_header_gallery_add'));
	$form->addElement('hidden', 'act',        $page);
	$form->addElement('hidden', 'sub_act',    $sub_act);
	$form->addElement('hidden', 'gid',        $gid);

	//galeria neve
	$form->addElement('text', 'name', $locale->get('field_gallery_name'), array('maxlength' => 255));

	//galeria tipusa
	$types = array('p' => $locale->get('field_gallery_type_picture'));
	if (!empty($_SESSION['site_gallery_is_video'])) {
	    $types['v'] = $locale->get('field_gallery_type_video');
	}
	$form->addElement('select', 'type', $locale->get('field_gallery_type'), $types);

	//galeria leirasa
	$leadarea =& $form->addElement('textarea', 'description', $locale->get('field_gallery_description'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" id="leadfield"');
	$leadarea->setCols(95);
	$leadarea->setRows(7);

	//leiras hosszusaga
	$form->addElement('text', 'lead_len', $locale->get('field_upload_description_length'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//temakorok - ha engedelyeztuk a hasznalatukat
	if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
		$query_cats = "
			SELECT c.category_id AS cat_id, c.category_name AS cat_name 
			FROM iShark_Category c 
			WHERE is_active = 1 
			ORDER BY c.category_name
		";
		$result_cats =& $mdb2->query($query_cats);

		$cat_select =& $form->addElement('select', 'category', $locale->get('field_gallery_category'), $result_cats->fetchAll('', $rekey = true));
		$cat_select->setMultiple(true);
		$cat_select->setSize(5);

		$form->addGroupRule('category', $locale->get('error_gallery_category'), 'required');
	}

	//ha hasznalunk tag-eket
	if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		// akkor:
		$query_tags = "
			SELECT tag_id, tag_name
			FROM iShark_Tags
			ORDER BY tag_name
		";
		$result_tags = $mdb2->query($query_tags);

		$tag_select =& $form->addElement('select', 'tags', $locale->get('field_gallery_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);
	}

	//idozites
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly'=>'readonly')),
			HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('field_deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('field_timer_start'), null, false
	);
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
			HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('field_deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('field_timer_end'), null, false
	);

	//ha kapcsolhatunk tartalmat
	if (!empty($_SESSION['site_gallery_cnt_attach']) && isModule('contents')) {
		$query_cnt = "
			SELECT content_id, title 
			FROM iShark_Contents 
			WHERE type = 1 
			ORDER BY title ASC
		";
		$result_cnt = $mdb2->query($query_cnt);

		$cnt_select =& $form->addElement('select', 'content_select', $locale->get('field_gallery_contents'), $result_cnt->fetchAll('', $rekey = true));
		$cnt_select->setMultiple(true);
		$cnt_select->setSize(5);
	}

	//galeria ertekelese
	if (!empty($_SESSION['site_gallery_is_rating'])) {
		$form->addElement('checkbox', 'is_rateable', $locale->get('field_gallery_ratings'));
	}

	$query = "
		SELECT name, description, type, is_rateable, timer_start, timer_end   
		FROM iShark_Galleries 
		WHERE gallery_id = $gid
	";
	$result =& $mdb2->query($query);
	if (!$defaults = $result->fetchRow()) {
		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}
	//ha nem volt idozitve, akkor nem irjuk ki a sok nullat
	if ($defaults['timer_start'] == '0000-00-00 00:00:00') {
	    $defaults['timer_start'] = "";
	    $defaults['timer_end']   = "";
	}

	$defaults['lead_len'] = $_SESSION['site_gallery_max_desc'] - strlen($defaults['description']);
	$form->setDefaults($defaults);

	//ha hasznaljuk a rovatokat
	if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
		$query = "
			SELECT category_id 
			FROM iShark_Gallery_Category 
			WHERE gallery_id = $gid
		";
		$result =& $mdb2->query($query);
		$cat_select->setSelected($result->fetchCol());
	}

	//ha hasznaljuk a tag-eket
	if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		$query = "
			SELECT tag_id 
			FROM iShark_Tags_Modules 
			WHERE module_name = 'gallery' and id = $gid
		";
		$result =& $mdb2->query($query);
		$tag_select->setSelected($result->fetchCol());
	}

	//ha kapcsolhatunk tartalmat
	if (!empty($_SESSION['site_gallery_cnt_attach']) && isModule('contents')) {
   		$query = "
   			SELECT content_id 
   			FROM iShark_Galleries_Contents 
   			WHERE gallery_id = $gid 
   		";
   		$result =& $mdb2->query($query);
   		$cnt_select->setSelected($result->fetchCol());
	}

	$form->addRule('name', $locale->get('error_gallery_name'), 'required');

	// Adatok mentése
	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$name           = $form->getSubmitValue('name');
		$desc           = $form->getSubmitValue('description');
		$type           = $form->getSubmitValue('type');
		$tags           = $form->getSubmitValue('tags');
		$category       = $form->getSubmitValue('category');
		$content_select = $form->getSubmitValue('content_select');
		$is_rateable    = intval($form->getSubmitValue('is_rateable'));

		$empty_time  = '0000-00-00 00:00:00';
		$timer_start = $form->getSubmitValue('timer_start');
		$timer_end   = $form->getSubmitValue('timer_end');
		$timer_start = empty($timer_start) ? $empty_time : $timer_start;
		$timer_end   = empty($timer_end) ? $empty_time : $timer_end;

		$query_gallery = "
			UPDATE iShark_Galleries 
			SET name        = '".$name."', 
				description = '".$desc."',
				mod_user_id = ".$_SESSION['user_id'].",
				mod_date    = NOW(),
				is_rateable = ".$is_rateable.", 
				timer_start = '".$timer_start."',
				timer_end   = '".$timer_end."',
				type        = '".$type."'
			WHERE gallery_id = $gid
		";
		$mdb2->exec($query_gallery);

		//ha rovatokhoz kapcsolhatjuk
		if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
			$query = "
				DELETE FROM iShark_Gallery_Category 
				WHERE gallery_id = $gid
			";
			$mdb2->exec($query);
		}

		//ha hasznaljuk a tag-eket
		if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
			$query = "
				DELETE FROM iShark_Tags_Modules 
				WHERE module_name = 'gallery' and id = $gid
			";
			$mdb2->exec($query);
		}

		//ha kapcsolhatunk tartalmat
		if (!empty($_SESSION['site_gallery_cnt_attach']) && isModule('contents')) {
       		$query = "
       			DELETE FROM iShark_Galleries_Contents 
       			WHERE gallery_id = $gid 
       		";
       		$mdb2->exec($query);
		}

		//ha rovatokhoz kapcsolhatjuk
		if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
    		//ha letezik a $category tomb, akkor felvisszuk a kapcsolotablaba
    		if (is_array($category) && count($category) > 0) {
    			foreach ($category as $key => $id) {
    				$query = "
    					INSERT INTO iShark_Gallery_Category 
    					(category_id, gallery_id) 
    					VALUES 
    					($id, $gid)
    				";
    				$mdb2->exec($query);
    			}
    		}
		}

		//ha hasznaljuk a tag-eket
		if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		    //ha letezik a $tags tomb, akkor felvisszuk a kapcsolotablaba
    		if (is_array($tags) && count($tags) > 0) {
    			foreach ($tags as $key => $id) {
    				$query = "
    					INSERT INTO iShark_Tags_Modules 
    					(tag_id, module_name, id, add_date) 
    					VALUES 
    					($id, 'gallery', $gid, NOW())
    				";
    				$mdb2->exec($query);
    			}
    		}
		}

		//ha kapcsolhatunk tartalmat
		if (!empty($_SESSION['site_gallery_cnt_attach']) && isModule('contents')) {
        	//ha letezik a $content_select tomb, akkor felvisszuk a kapcsolotablaba
        	if (is_array($content_select) && count($content_select) > 0) {
        		foreach ($content_select as $value) {
        			$query = "
        				INSERT INTO iShark_Galleries_Contents 
        				(gallery_id, content_id) 
        				VALUES 
        				($gid, ".$value.")
        			";
        			$mdb2->exec($query);
        		}
        	}
		}

		$form->freeze();

		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('form_gallery_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_gallery_reset'),  'class="reset"');

	// Form kiíratás
	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($locale->get('title_modify'), '#');

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $locale->get('title_modify'));
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&act='.$page);

	//capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	$acttpl = 'dynamic_form';
}

// Galériák listája
if ($sub_act == 'lst') {
	$query = "
		SELECT gallery_id as gid, name, description, is_active, type
		FROM iShark_Galleries
		WHERE is_active = 2
	";
	//ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
	if (empty($_SESSION['site_gallery_is_video'])) {
	    $query .= " AND type != 'v' ";
	}
	$query .= " ORDER BY name ";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$tpl->assign('page_data',    $paged_data['data']);
	$tpl->assign('page_list',    $paged_data['links']);

	$acttpl = 'gallery_list';
}

?>