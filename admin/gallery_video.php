<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field      = intval($_REQUEST['field']);
	$ord        = $_REQUEST['ord'];
	$fieldorder = " ORDER BY";

	switch ($field) {
		case 1:
			$fieldorder   .= " name ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   .= " add_date ";
			$fieldselect2 = "selected";
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
	$fieldorder   = "ORDER BY name";
	$fieldselect3 = "selected";
	$order        = "";
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);

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
		WHERE p.picture_id = $pid AND g.type = 'v'
	";
	$gal =& $mdb2->query($query);
	$ize = $gal->fetchRow();

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';
	$form =& new HTML_QuickForm('rename_frm', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('video_form_required_note'));

	$form->addElement('header', 'gallery_mod', $locale->get('video_form_header_modify'));
	$form->addElement('hidden', 'act',         $page);
	$form->addElement('hidden', 'sub_act',     $sub_act);
	$form->addElement('hidden', 'gid',         $gid);
	$form->addElement('hidden', 'pid',         $pid);

	//video letoltheto-e
	if (!empty($_SESSION['site_gallery_is_download']) && !empty($_SESSION['site_gallery_is_video'])){
		$isdownload =& $form->addElement('checkbox', 'is_download', $locale->get('field_video_is_download'));
	}

	//kep neve
	$form->addElement('text', 'name', $locale->get('video_field_vname'), array('maxlength' => 255));

	//kep leirasa
	$leadarea =& $form->addElement('textarea', 'description', $locale->get('video_field_vdescription'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" id="leadfield"');
	$leadarea->setCols(95);
	$leadarea->setRows(7);

	//leiras hosszusaga
	$form->addElement('text', 'lead_len', $locale->get('video_field_vdesc_length'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//ha hasznalunk tag-eket
	if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		// akkor:
		$query_tags = "
			SELECT tag_id, tag_name
			FROM iShark_Tags
			ORDER BY tag_name
		";
		$result_tags =& $mdb2->query($query_tags);

		$tag_select =& $form->addElement('select', 'tags', $locale->get('video_field_vtags'), $result_tags->fetchAll('', $rekey = true));
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

	$form->addRule('name',        $locale->get('video_error_vname'),        'required');
	$form->addRule('description', $locale->get('video_error_vdescription'), 'required');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$name        = $form->getSubmitValue('name');
		$tags        = $form->getSubmitValue('tags');
		$description = $form->getSubmitValue('description');
		if (empty($description)) {
			$description = "";
		}
			
		if (!empty($_SESSION['site_gallery_is_download'])){
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

	$form->addElement('submit', 'submit', $locale->get('video_form_vsubmit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('video_form_vreset'),  'class="reset"');

	$renderer =& new HTML_Quickform_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $locale->get('video_title_vmodify'));

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

	$form->setRequiredNote($locale->get('video_form_upload_required_note'));

	$form->addElement('header', 'header_upl', $locale->get('video_form_header_vupload'));
	$form->addElement('hidden', 'act',        $page);
	$form->addElement('hidden', 'sub_act',    $sub_act);
	$form->addElement('hidden', 'gid',        $gid);

    if (!empty($_SESSION['site_gallery_is_download'])) {
        $isdownload =& $form->addElement('checkbox', 'is_download', $locale->get('field_upload_video_is_download'));
    }

	$query = "
		SELECT g.type 
		FROM iShark_Galleries g 
		WHERE g.gallery_id = $gid AND g.type = 'v'
	";
	$gal =& $mdb2->query($query);
	$ize = $gal->fetchRow();

	$file =& $form->addElement('file', 'picture', $locale->get('field_video_file'));

	//kep neve
	$form->addElement('text', 'name', $locale->get('video_field_upload_vname'), array('maxlength' => 255));

	//kep leirasa
	$leadarea =& $form->addElement('textarea', 'description', $locale->get('video_field_upload_description'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" id="leadfield"');
	$leadarea->setCols(95);
	$leadarea->setRows(7);

	//leiras hosszusaga
	$form->addElement('text', 'lead_len', $locale->get('video_field_upload_vdesc_length'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//ha hasznalunk tag-eket
	if (!empty($_SESSION['site_gallery_is_tags']) && isModule('tags')) {
		// akkor:
		$query_tags = "
			SELECT tag_id, tag_name
			FROM iShark_Tags 
			ORDER BY tag_name
		";
		$result_tags =& $mdb2->query($query_tags);

		$tag_select =& $form->addElement('select', 'tags', $locale->get('video_field_upload_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);
	}

	//szuro
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok
	$form->addRule('name',        $locale->get('video_error_upload_vname'),        'required');
	$form->addRule('description', $locale->get('video_error_upload_vdescription'), 'required');
	$form->addRule('picture',     $locale->get('video_error_upload_file'),         'uploadedfile');
	$form->addRule('picture',     $locale->get('video_error_upload_fileext'),      'filename',    '/\.(mpe?g|avi|wmv)$/');
	$form->addRule('picture',     $locale->get('video_error_upload_filemime'),     'mimetype',    array('video/x-msvideo', 'video/mpeg', 'video/x-ms-wmv'));

	//alapertelmezett ertekek
	$form->setDefaults(
	    array(
	        'lead_len' => $_SESSION['site_gallery_max_desc']
	    )
	);

	// Adatok mentése
	if ($form->validate()) {
		if (!$file->isUploadedFile()) {
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=upl&gid='.$gid);
			exit;
		}

		$filevalues = $file->getValue();
		$gdir       = preg_replace('|/$|','', $_SESSION['site_galerydir']).'/';
		$filename   = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
		$tn_name    = 'tn_'.$filename;
		$name       = $mdb2->escape($form->getSubmitValue('name'));
		$tags       = $form->getSubmitValue('tags');

	    //ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
	    if (!empty($_SESSION['site_gallery_is_video'])) {
			$file->moveUploadedFile($gdir, $filename);
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

			// altalanos dolgok a videokrol + konvertalashoz
	        $extension = "ffmpeg";
			$extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
			$extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

			// load extension
			if(!extension_loaded($extension)) {
			    dl($extension_soname);
			}

    		// Set our source file
    		$srcFile      = $gdir.$filename;
    		$destFile     = $gdir."tmp_".substr($filename, 0, strrpos($filename, ".")).".flv";
    		$newFile      = substr($filename, 0, strrpos($filename, ".")).".flv";
    		$thumbFile    = $gdir."tn_".substr($filename, 0, strrpos($filename, ".")).".flv.jpg";
    		$ffmpegPath   = "ffmpeg";

    		// Create our FFMPEG-PHP class
    		$ffmpegObj = new ffmpeg_movie($srcFile);

    		// Save our needed variables
    		$srcWidth  = $ffmpegObj->getFrameWidth();
    		$srcHeight = $ffmpegObj->getFrameHeight();
    		$srcFPS    = $ffmpegObj->getFrameRate();
    		$srcAB     = intval($ffmpegObj->getAudioBitRate()/1000);
    		$srcAR     = $ffmpegObj->getAudioSampleRate();

    	    // thumbnail
    	    $ff_frame = $ffmpegObj->getFrame(30);
    	    if ($ff_frame) {
    	        $ff_frame->resize($_SESSION['site_thumbwidth'], $_SESSION['site_thumbheight']);
    	        $gd_image = $ff_frame->toGDImage();
    	        if ($gd_image) {
    	            //ha nincs konvertalas, akkor mas lez a file neve
    	            if (empty($_SESSION['site_gallery_is_convert'])) {
                        $thumbFile = $gdir.$tn_name.".jpg";
    	            }
    	            imagejpeg($gd_image, $thumbFile);
    	            imagedestroy($gd_image);
    	        }
    	    }

    		// ha konvertaljuk a videot
			if (!empty($_SESSION['site_gallery_is_convert'])) {
    			// Call our convert using exec()
    			$command = $ffmpegPath." -i ".$srcFile." -ar ".$srcAR." -ab ".$srcAB." -y -r ".$srcFPS." -f flv -author ".$_SESSION['site_sitename']." -copyright ".$_SESSION['site_sitename']." -s ".$srcWidth."x".$srcHeight." ".$destFile;
    			if (substr($ffmpegPath, -6) == "ffmpeg" && substr(substr($command, 0, strlen($ffmpegPath)), -6) == "ffmpeg") {
    			    escapeshellcmd(exec($command));
    			} else {
    			    $acttpl = 'error';
			        $tpl->assign('errormsg', $locale->get('video_error_command'));
			        return;
    			}

			    // eredeti file-t toroljuk
			    @unlink($srcFile);
			    // atmeneti file-t atmasoljuk az uj neven
			    @rename($destFile, $gdir.$newFile);
			    //megadjuk a tablaba toltendo file nevet
			    $filename = $newFile;

			    //ha 0 az uj file merete, akkor nem sikerult konvertalni => hiba
			    if (filesize($gdir.$newFile) == 0) {
			        @unlink($gdir.$newFile);

			        $acttpl = 'error';
			        $tpl->assign('errormsg', $locale->get('video_error_upload'));
			        return;
			    }
			}

			$picture_id = $mdb2->extended->getBeforeID('iShark_Pictures', 'picture_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Pictures
				(picture_id, realname, name, add_user_id, add_date, description, is_download, width, height, mod_user_id, mod_date, tn_width, tn_height)
				VALUES
				($picture_id, '$filename', '$name', ".$_SESSION['user_id'].", NOW(), '".$description."', '$is_download', $srcWidth, $srcHeight, ".$_SESSION['user_id'].", NOW(), ".$_SESSION['site_thumbwidth'].", ".$_SESSION['site_thumbheight'].")
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

		@unlink($filevalues['tmp_name']);
		@unlink($gdir.$filename);
		@unlink($gdir.$tn_name);
	}

	$form->addElement('submit', 'submit', $locale->get('video_form_upload_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('video_form_upload_reset'),  'class="reset"');

	$renderer =& new HTML_Quickform_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $locale->get('video_title_upload'));

	$acttpl = 'dynamic_form';
}

/**
 * Kép megtekintése 
 */
if ($sub_act == 'view') {
	$tpl->assign('pic',        $picture);
	$ext = substr($picture['realname'], strrpos($picture['realname'], "."));
	if ($ext == ".flv") {
	    $javascripts[] = "javascript.swfplayer";
	}
	$tpl->assign('ext',        $ext);
	$tpl->assign('lang_title', $locale->get('video_title_pic_view'));
	$tpl->assign('back_arrow', htmlentities($_SERVER['HTTP_REFERER']));

	$acttpl = 'gallery_video_view';
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
	@unlink($gdir.'tn_'.$picture['realname'].'.jpg');

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);
	exit;
}

/**
 * FTP feltoltes 
 */
if ($sub_act == 'ftp' && !empty($_SESSION['site_gallery_is_ftpdir'])) {
	$javascripts[] = "javascripts";

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	include_once 'includes/function.gallery.php';
	include_once 'includes/function.images.php';

	$form =& new HTML_QuickForm('gftp_frm', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_ftpgallery_required_note'));

	$form->addElement('hidden', 'gid',     $gid);
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);

	//mindet kijelol checkbox
	$form->addElement('checkbox', 'all', null, $locale->get('field_clickall'), 'onclick=doNow()');

	$form->addElement('submit', 'submit', $locale->get('form_ftp_submit'), 'class="submit"');

	$fdir    = rtrim($_SESSION['site_galleryftpdir'], '/').'/';
	$dirlist = get_ftpdir($fdir);
	$ddir    = rtrim($_SESSION['site_galerydir'], '/').'/';

	if ($form->validate()) {
		$errors = array();
		if (isset($_POST['fileChecked']) && is_array($_POST['fileChecked'])) {
			foreach ($_POST['fileChecked'] as $key => $value) {

				if (get_magic_quotes_gpc()) {
					$file = stripslashes($value);
				} else {
					$file = $value;
				}

				$filetype = exec(trim('file -bi'.escapeshellarg($fdir.$file)));

				$filename = time().preg_replace('|[^\w\d_\.]|', '_', change_hunchar($file));
				$tn_name = 'tn_'.$filename;

				if ($type == 'p') {
					if (!($pic= img_resize($fdir.$file, $ddir.$filename, $_SESSION['site_picwidth'], $_SESSION['site_picheight'])) || 
						!($tn = img_resize($fdir.$file, $ddir.$tn_name, $_SESSION['site_thumbwidth'], $_SESSION['site_thumbheight']))) {
						$errors[] = $file;
					} else {
						@chmod($ddir.$filename, 0664);
						@chmod($ddir.$tn_name, 0664);
						$name = str_replace("'", "''", $file);
						$size = filesize($ddir.$filename);

						$picture_id = $mdb2->extended->getBeforeID('iShark_Pictures', 'picture_id', TRUE, TRUE);
						$query = "
							INSERT INTO iShark_Pictures 
							(picture_id, realname, name, width, height, tn_width, tn_height, add_user_id, add_date)
							VALUES
							($picture_id, '$filename', '$name', $pic[width], $pic[height], $tn[width], $tn[height], $_SESSION[user_id], NOW())
						";
						$mdb2->exec($query);
						$last_picture_id = $mdb2->extended->getAfterID($picture_id, 'iShark_Pictures', 'picture_id');

						$query = "
							INSERT INTO iShark_Galleries_Pictures
							(gallery_id, picture_id)
							VALUES 
							($gid, $last_picture_id)
						";
						$mdb2->exec($query);
					}
				} else {
				    //ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
				    if (!empty($_SESSION['site_gallery_is_video'])) {
    					//wmv-t a szerveren application/octet-stream-nak veszi
    					if($filetype == 'video/x-msvideo' || $filetype == 'video/mpeg' || $filetype == 'video/x-ms-wmv' || $filetype == 'application/octet-stream') {
    						rename($fdir.$file, $ddir.$filename);
    						@chmod($ddir.$filename, 0664);
    						$name = str_replace("'", "''", $file);
    						$size = filesize($ddir.$filename);

    						$picture_id = $mdb2->extended->getBeforeID('iShark_Pictures', 'picture_id', TRUE, TRUE);
    						$query = "
    							INSERT INTO iShark_Pictures 
    							(picture_id, realname, name, add_user_id, add_date)
    							VALUES
    							($picture_id, '$filename', '$name', $_SESSION[user_id], NOW())
    						";
    						$mdb2->exec($query);
    						$last_picture_id = $mdb2->extended->getAfterID($picture_id, 'iShark_Pictures', 'picture_id');

    						$query = "
    							INSERT INTO iShark_Galleries_Pictures
    							(gallery_id, picture_id)
    							VALUES 
    							($gid, $last_picture_id)
    						";
    						$mdb2->exec($query);
    					}
				    }
				}
			}
			$form->freeze();

			//loggolas
			logger($page.'_'.$sub_act);

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);
			exit;
		} else {
			$form->setElementError('all', $locale->get('error_ftp_checked'));
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($locale->get('title_ftp_upload'), '#');

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('act_dir',    $gallery['name']);
	$tpl->assign('dirlist',    $dirlist);
	$tpl->assign('lang_title', $locale->get('title_ftp_upload'));
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&act='.$page.'&sub_act=plst&gid='.$gid);

	$acttpl = 'gallery_ftp';
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

	if ($_SESSION['site_gallery_is_ftpdir'] == '1' && check_perm('ftp', NULL, 1, $module_name)) {
		$tpl->assign('is_ftp', TRUE);
	}

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=upl&amp;gid='.$gid,
			'title' => $locale->get('video_title_upload'),
			'pic'   => 'add.jpg'
		)
	);

	if (!empty($_SESSION['site_gallery_is_ftpdir'])) {
		$add_new[] = array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=ftp&amp;gid='.$gid,
			'title' => $locale->get('video_title_ftp_upload'),
			'pic'   => 'ftpgallery.jpg'
		);
	}

	//breadcrumb
	$breadcrumb->add($locale->get('video_tabs_title_piclist'), '#');

	$tpl->assign('add_new',    $add_new);
	$tpl->assign('lang_title', $locale->get('video_tabs_title_piclist').' ('.$gallery['name'].')');
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);

	$acttpl = 'gallery_video';
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

// Új mappa létrehozása/mappa módosítása
if ($sub_act == 'gadd' || $sub_act == 'gmod') {
    $javascripts[] = "javascript.contents";

    $titles = array('gadd' => $locale->get('video_title_gadd'), 'gmod' => $locale->get('video_title_gmod'));

	include_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_Quickform('frm_gallery', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('video_form_required_note'));

	$form->addElement('header', 'header_vid', $locale->get('video_form_header_add'));
	$form->addElement('hidden', 'act',        $page);
	$form->addElement('hidden', 'sub_act',    $sub_act);
	$form->addElement('hidden', 'gid',        $gid);

	//galeria neve
	$form->addElement('text', 'name', $locale->get('video_field_name'), array('maxlength' => 255));

	//galeria leirasa
	$leadarea =& $form->addElement('textarea', 'description', $locale->get('video_field_description'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_gallery_max_desc'].'\');" id="leadfield"');
	$leadarea->setCols(95);
	$leadarea->setRows(7);

	//leiras hosszusaga
	$form->addElement('text', 'lead_len', $locale->get('video_field_upload_desc_length'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//temakorok - ha engedelyeztuk a hasznalatukat
	if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
		$query_cats = "
			SELECT c.category_id AS cat_id, c.category_name AS cat_name 
			FROM iShark_Category c 
			WHERE is_active = 1 
			ORDER BY c.category_name
		";
		$result_cats =& $mdb2->query($query_cats);

		$cat_select =& $form->addElement('select', 'category', $locale->get('video_field_category'), $result_cats->fetchAll('', $rekey = true));
		$cat_select->setMultiple(true);
		$cat_select->setSize(5);

		$form->addGroupRule('category', $locale->get('video_error_category'), 'required');
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

		$tag_select =& $form->addElement('select', 'tags', $locale->get('video_field_tags'), $result_tags->fetchAll('', $rekey = true));
		$tag_select->setMultiple(true);
		$tag_select->setSize(5);
	}

	//idozites
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly'=>'readonly')),
			HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('video_field_deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('video_field_timer_start'), null, false
	);
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
			HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('video_field_deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('video_field_timer_end'), null, false
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

		$cnt_select =& $form->addElement('select', 'content_select', $locale->get('video_field_contents'), $result_cnt->fetchAll('', $rekey = true));
		$cnt_select->setMultiple(true);
		$cnt_select->setSize(5);
	}

	//galeria ertekelese
	if (!empty($_SESSION['site_gallery_is_rating'])) {
		$form->addElement('checkbox', 'is_rateable', $locale->get('video_field_ratings'));
	}

	//modositas
	if ($sub_act == 'gmod') {
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
				FROM iShark_Galleries_Category 
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
	}
	//ha hozzaadas
	else {
	    $form->setDefaults(array(
	            'lead_len' => $_SESSION['site_gallery_max_desc']
	        )
	    );
	}

	$form->addRule('name', $locale->get('video_error_name'), 'required');

	// Adatok mentése
	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$name           = $form->getSubmitValue('name');
		$desc           = $form->getSubmitValue('description');
		$tags           = $form->getSubmitValue('tags');
		$category       = $form->getSubmitValue('category');
		$content_select = $form->getSubmitValue('content_select');
		$is_rateable    = intval($form->getSubmitValue('is_rateable'));

		$empty_time     = '0000-00-00 00:00:00';
		$timer_start    = $form->getSubmitValue('timer_start');
		$timer_end      = $form->getSubmitValue('timer_end');
		$timer_start    = empty($timer_start) ? $empty_time : $timer_start;
		$timer_end      = empty($timer_end) ? $empty_time : $timer_end;

		//hozzaadas
		if ($gid == '0' && $sub_act == 'gadd') {
			$gallery_id = $mdb2->extended->getBeforeID('iShark_Galleries', 'gallery_id', TRUE, TRUE);
			$query_gallery = "
				INSERT INTO iShark_Galleries 
				(gallery_id, name, description, add_user_id, add_date, is_active, type, is_rateable, timer_start, timer_end)
				VALUES 
				( ".$gallery_id.", '".$name."', '".$desc."', ".$_SESSION["user_id"].", NOW(), '1', 'v', ".$is_rateable.", '".$timer_start."', '".$timer_end."' )
			";
			$mdb2->exec($query_gallery);
			$last_gallery_id = $mdb2->extended->getAfterID($gallery_id, 'iShark_Galleries', 'gallery_id');
		}

		//modositas
		else {
			$query_gallery = "
				UPDATE iShark_Galleries 
				SET name        = '".$name."', 
					description = '".$desc."',
					mod_user_id = ".$_SESSION['user_id'].",
					mod_date    = NOW(),
					is_rateable = ".$is_rateable.", 
					timer_start = '".$timer_start."',
					timer_end   = '".$timer_end."',
					type        = 'v'
				WHERE gallery_id = $gid
			";
			$mdb2->exec($query_gallery);

			//ha rovatokhoz kapcsolhatjuk
			if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
				$query = "
					DELETE FROM iShark_Galleries_Category 
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

		}

		//ha van $last_gallery_id, akkor uj hozzadasa, egyebkent modositas, sima $gid
		$gallery_id = empty($last_gallery_id) ? $gid : $last_gallery_id;

		//ha rovatokhoz kapcsolhatjuk
		if (!empty($_SESSION['site_gallery_is_category']) && isModule('contents')) {
    		//ha letezik a $category tomb, akkor felvisszuk a kapcsolotablaba
    		if (is_array($category) && count($category) > 0) {
    			foreach ($category as $key => $id) {
    				$query = "
    					INSERT INTO iShark_Galleries_Category 
    					(category_id, gallery_id) 
    					VALUES 
    					($id, $gallery_id)
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
    					($id, 'gallery', $gallery_id, NOW())
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
        				($gallery_id, ".$value.")
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

	$form->addElement('submit', 'submit', $locale->get('video_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('video_form_reset'),  'class="reset"');

	// Form kiíratás
	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $titles[$sub_act]);
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
		SELECT gallery_id as gid, name, description, is_active, add_date
		FROM iShark_Galleries
		WHERE is_active != 2 AND type = 'v'
		$fieldorder $order
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=gadd',
			'title' => $locale->get('video_title_new'),
			'pic'   => 'add.jpg'
		)
	);
	if ($_SESSION['site_gallery_is_ftpdir'] == '0') {
		unset($add_new[1]);
	}
	$add_new = array_values($add_new);

	$tpl->assign('page_data',    $paged_data['data']);
	$tpl->assign('page_list',    $paged_data['links']);
	$tpl->assign('add_new',      $add_new);

	$acttpl = 'gallery_video_list';
}

?>