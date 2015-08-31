<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "sendnews";

//nyelvi file
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('sendnews_upl');

$javascripts[] = "javascript.contents";

//tipus
if (isset($_REQUEST['type'])) {
	$type = $_REQUEST['type'];
} else {
	$type = "news";
}

if ($type == "news") {
	$cat_type = "site_category";
} else {
	$cat_type = "site_gallery_is_category";
}

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "sendnews_upl";
}
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
	$site_errors[] = array('text' => $locale->get('config', 'error_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

//modulbeállítások
$module_settings_query = "
	SELECT * 
	FROM iShark_Sendnews_Configs
";
$module_settings_result =& $mdb2->query($module_settings_query);
$module_settings = $module_settings_result->fetchRow();

//megnezzuk, hogy van-e joga rogton fooldalra kuldeni a hirt
if ($module_settings['is_admin'] == '1') {
	if (isset($_SESSION['user_id']) && isset($_SESSION['user_groups'])) {
		$usergroups = explode(' ', $_SESSION['user_groups']);
		if ($module_settings['is_check'] == '1' && (in_array($module_settings['prefgroup'], $usergroups))) {
			$send_news_num = 0;
			$send_gal_num  = 0;
			$send_sum_num  = 0;
			//ha van hir bekuldes
			if (isModule('news', 'index')) {
				$is_sent_query = "
					SELECT add_user_id 
					FROM iShark_Contents 
					WHERE add_user_id = ".$_SESSION['user_id']." and is_active = '1'
				";
				$is_sent_result =& $mdb2->query($is_sent_query);
				$send_news_num = $is_sent_result->numRows();
			}
			//ha van kep vagy video bekuldes
			if (isModule('gallery', 'index')) {
				$is_gal_query = "
					SELECT add_user_id 
					FROM iShark_Galleries 
					WHERE add_user_id = ".$_SESSION['user_id']." AND is_active = '1'
				";
				$is_gal_result =& $mdb2->query($is_gal_query);
				$send_gal_num = $is_gal_result->numRows();
			}
			$send_sum_num = $send_news_num + $send_gal_num;
			if ($send_sum_num >= $module_settings['is_check_num']){
				$is_active = '1';
			} else {
				$is_active = '2';
			}
		} else {
			$is_active = '2';
		}
	} else {
		$is_active = '2';
	}
} else {
	$is_active = '1';
}

if (($module_settings['is_reg'] == '1' && isset($_SESSION['user_id'])) || $module_settings['is_reg'] == '0') {
	if ($act == 'sendnews_upl') {
		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		$form_sendnews =& new HTML_QuickForm('frm_sendnews', 'post', 'index.php?p='.$module_name.'&type='.$type);

		$form_sendnews->setRequiredNote($locale->get('form_required_note'));

		$form_sendnews->addElement('header', 'sendnews', $locale->get('form_header'));

		//feltoltjuk a tombot a tipushoz
		$sendtype = array();
		//ha vannak hirek
		if (isModule('news', 'index')) {
			$sendtype['news'] = $locale->get('field_news');
		}
		//ha van galeria
		if (isModule('gallery', 'index')) {
			$sendtype['pic']   = $locale->get('field_pic');
			//ha nincsenek videokgaleriak, akkor berakjuk a feltetelt
			if (!empty($_SESSION['site_gallery_is_video'])) {
			    $sendtype['video'] = $locale->get('field_video');
			}
		}
		$typeselect =& $form_sendnews->addElement('select', 'sendtype', $locale->get('field_type'), $sendtype, 'onChange="window.location =\'index.php?p=sendnews&type=\'+this.value"');
		$typeselect->setSelected($type);

		$form_sendnews->addElement('text', 'title', $locale->get('field_title_'.$type));

		//temakorok - ha engedelyeztuk a hasznalatukat
		if (!empty($_SESSION[$cat_type])) {
			$query = "
				SELECT c.category_id AS cat_id, c.category_name AS cat_name 
				FROM iShark_Category c 
				WHERE is_active = 1 
				ORDER BY c.category_name
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$select =& $form_sendnews->addElement('select', 'category', $locale->get('field_category'), $result->fetchAll('', $rekey = true));
				$select->setMultiple(true);
				$select->setSize(5);
			}

			$form_sendnews->addGroupRule('category', $locale->get('error_category'), 'required');
		}

		$form_sendnews->addRule('title', $locale->get('error_title'), 'required');

		//tag-ek hasznalata, ha engedelyeztuk
		if (isModule('tags') && !empty($_SESSION['site_cnt_is_tags'])) {
			$form_sendnews->addElement('text', 'tags', $locale->get('field_tag'));
		}

		if ($type == "news") {
			//bevezeto szoveg
			if (!empty($_SESSION['site_is_lead'])) {
				$leadarea =& $form_sendnews->addElement('textarea', 'lead', $locale->get('field_lead'), 'onKeyDown="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" onKeyUp="textCounter(\'leadfield\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" id="leadfield" style="width: 100%"');
				$leadarea->setCols(50);
				$leadarea->setRows(5);
				$form_sendnews->addElement('text', 'lead_len', $locale->get('field_news_leadlen'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

				$form_sendnews->addRule('lead', $locale->get('error_lead'), 'required');
			}
			//teljes szoveg
			$form_sendnews->addElement('textarea', 'body', $locale->get('field_body'));

			//kep, video feltoltese
			$file =& $form_sendnews->addElement('file', 'fileupl', $locale->get('field_file'));

			$form_sendnews->applyFilter('__ALL__', 'trim');

			$form_sendnews->addRule('sendtype', $locale->get('error_sendtype'), 'required');
			$form_sendnews->addRule('title',    $locale->get('error_title'),    'required');
			$form_sendnews->addRule('body',     $locale->get('error_body'),     'required');
			//ha kepet vagy videot toltunk fel
			if (isModule('gallery', 'index') && $form_sendnews->isSubmitted() && ($_POST['sendtype'] == 'pic' || $_POST['sendtype'] == 'video')) {
				$form_sendnews->addRule('fileupl', $locale->get('error_file'), 'uploadedfile');
			}

			if ($form_sendnews->validate()) {
				$form_sendnews->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$filename = "";
				$pic = TRUE;
				//kep feltoltese
				if ((isset($_SESSION['site_leadpic']) && $_SESSION['site_leadpic'] == 1) || (isset($_SESSION['site_newspic']) && $_SESSION['site_newspic'] == 1)) {
					if ($file->isUploadedFile()) {
						$filevalues = $file->getValue();
						$sdir = preg_replace('|/$|','', $_SESSION['site_cnt_picdir']).'/';
						$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));

						//kep atmeretezese
						include_once 'includes/function.images.php';

						if (is_array($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_newspicw'], $_SESSION['site_newspich']))) {
							@chmod($sdir.$filename,0664);
							@unlink($filevalues['tmp_name']);
						}

						if (!$pic) {
							$form_sendnews->setElementError('fileupl', $locale->get('error_news_picupload'));
						}
					}
				}
				if ($pic) {
					//bevezeto szoveg csak akkor van, ha ezt engedelyeztuk
					if (isset($_SESSION['site_is_lead']) && $_SESSION['site_is_lead'] == 1) {
						$lead = $form_sendnews->getSubmitValue('lead');
					} else {
						$lead = "";
					}

					$title    = $form_sendnews->getSubmitValue('title');
					$lead     = $form_sendnews->getSubmitValue('lead');
					$body     = $form_sendnews->getSubmitValue('body');
					$category = $form_sendnews->getSubmitValue('category');
					$tags     = trim($form_sendnews->getSubmitValue('tags'));

					//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
					if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
						$languages = $form_sendnews->getSubmitValue('languages');
					} else {
						$languages = $_SESSION['site_deflang'];
					}

					if (isset($_SESSION['user_id'])) {
						$user_id = $_SESSION['user_id'];
					} else {
						$user_id = 0;
					}

					$content_id = $mdb2->extended->getBeforeID('iShark_Contents', 'content_id', TRUE, TRUE);
					$query = "
						INSERT INTO iShark_Contents
						(type, title, lead, content, add_user_id, add_date, mod_user_id, mod_date, is_active, lang, picture, is_mainnews, is_index)
						VALUES
						('0', '".$title."', '".$lead."', '".$body."', '".$user_id."', NOW(), '".$user_id."', NOW(), '".$is_active."', '".$languages."', '".$filename."', '0', '0')
					";
					$mdb2->exec($query);
					$last_id = $mdb2->extended->getAfterID($content_id, 'iShark_Contents', 'content_id');

					//ha letezik a $category tomb, akkor felvisszuk a kapcsolotablaba
					if (is_array($category) && count($category) > 0) {
						foreach ($category as $key => $id) {
							$query = "
								INSERT INTO iShark_Contents_Category 
								(category_id, content_id) 
								VALUES 
								($id, $last_id)
							";
							$mdb2->exec($query);
						}
					}

					if (!empty($tags)) {
					    include_once $include_dir.'/function.tags.php';
					    addTags($tags, 'news', $last_id);
					}

					//"fagyasztjuk" a form-ot
					$form_sendnews->freeze();

					//visszadobjuk a lista oldalra
					header('Location: index.php?success=feedback_send&link=');
					exit;
				}
			}

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'sendnews';
		}

		if ($type == "pic") {
			$javascripts[] = "multifile";

			$form_sendnews->addElement('textarea', 'lead', $locale->get('field_lead_pic'), array('style' => 'width: 100%;'));
			$file =& $form_sendnews->addElement('file', 'file_1', $locale->get('field_file'), array('id' => 'pic_select'));

			if ($form_sendnews->validate()) {
				$form_sendnews->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				if (isset($_SESSION['user_id'])) {
					$user_id = $_SESSION['user_id'];
				} else {
					$user_id = 0;
				}

				$lead     = $form_sendnews->getSubmitValue('lead');
				$title    = $form_sendnews->getSubmitValue('title');
				$category = $form_sendnews->getSubmitValue('category');
				$tags     = trim($form_sendnews->getSubmitValue('tags'));

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
					$languages = $form_sendnews->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				$gallery_id = $mdb2->extended->getBeforeID('iShark_Galleries', 'gallery_id', TRUE, TRUE);
				$query_gallery = "
					INSERT INTO iShark_Galleries 
					(gallery_id, name, description, add_user_id, add_date, is_active, type)
					VALUES 
					( ".$gallery_id.", '".$title."', '".$lead."', ".$user_id.", NOW(), '$is_active', 'p' )
				";
				$mdb2->exec($query_gallery);
				$last_gallery_id = $mdb2->extended->getAfterID($gallery_id, 'iShark_Galleries', 'gallery_id');

				//ha letezik a $category tomb, akkor felvisszuk a kapcsolotablaba
				if (is_array($category) && count($category) > 0) {
					foreach ($category as $key => $id) {
						$query = "
							INSERT INTO iShark_Gallery_Category 
							(gallery_id, category_id) 
							VALUES 
							($last_gallery_id, $id)
						";
						$mdb2->exec($query);
					}
				}

				if (!empty($tags)) {
				    include_once $include_dir.'/function.tags.php';
                    addTags($tags, 'gallery', $last_gallery_id);
				}

				//képek feltöltése
				$filename = "";
				$pic = TRUE;

				for ($i = 1; $i < $_REQUEST['pic_count']; $i++){
					if (!empty($_FILES['file_'.$i]['tmp_name'])){
						$gdir = preg_replace('|/$|','', $_SESSION['site_galerydir']).'/';
						$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($_FILES['file_'.$i]['name']));
						$tn_name = 'tn_'.$filename;

						//kep atmeretezese
						include_once 'includes/function.images.php';

						if (($pic= img_resize($_FILES['file_'.$i]['tmp_name'], $gdir.$filename, $_SESSION['site_picwidth'], $_SESSION['site_picheight'])) && ($tn = img_resize($_FILES['file_'.$i]['tmp_name'], $gdir.$tn_name, $_SESSION['site_thumbwidth'], $_SESSION['site_thumbheight']))) {
							@chmod($gdir.$filename,0664);

							$picture_id = $mdb2->extended->getBeforeID('iShark_Pictures', 'picture_id', TRUE, TRUE);

							$description = $form_sendnews->getSubmitValue('pic_'.$i.'_desc');
							if ( empty( $description ) ) {
								$description = "";
							}
							$name = $form_sendnews->getSubmitValue('pic_'.$i.'_name');
							if ( empty( $name ) ) {
								$name = "Kép ".$i+1;
							}

							$query = "
								INSERT INTO iShark_Pictures
								(picture_id, realname, name, width, height, tn_width, tn_height, add_user_id, add_date, description)
								VALUES
								($picture_id, '$filename', '$name', $pic[width], $pic[height], $tn[width], $tn[height], $user_id, now(), '".$description."')
							";
							$mdb2->exec($query);
							$last_picture_id = $mdb2->extended->getAfterID($picture_id, 'iShark_Pictures', 'picture_id');

							$query = "
								INSERT INTO iShark_Galleries_Pictures
								(gallery_id, picture_id)
								VALUES
								($last_gallery_id, $last_picture_id)
							";
							$mdb2->exec($query);

							$tags = $form_sendnews->getSubmitValue('pic_'.$i.'_tags');

							if (!empty($tags)) {
							    include_once $include_dir.'/function.tags.php';
							    addTags($tags, 'picture', $last_picture_id);
							}

							@unlink($_FILES['file_'.$i]['tmp_name']);
						}
					}
				}
				if (!$pic) {
					//$form_sendnews->setElementError('fileupl', $locale->get('error_news_picupload'));
				}

				//"fagyasztjuk" a form-ot
				$form_sendnews->freeze();

				//visszadobjuk a lista oldalra
				header('Location: index.php?success=feedback_send&link=');
				exit;
			}

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'sendnews_pics';
		}

		if ($type == "video" && !empty($_SESSION['site_gallery_is_video'])) {
			$javascripts[] = "multifile";

			$form_sendnews->addElement('textarea', 'lead', $locale->get('field_lead_pic'), array('style' => 'width: 100%;'));
			$file =& $form_sendnews->addElement('file', 'file_1', $locale->get('field_file'), array('id' => 'pic_select'));

			if ($form_sendnews->validate()) {
				$form_sendnews->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				if (isset($_SESSION['user_id'])) {
					$user_id = $_SESSION['user_id'];
				} else {
					$user_id = 0;
				}

				$lead     = $form_sendnews->getSubmitValue('lead');
				$title    = $form_sendnews->getSubmitValue('title');
				$category = $form_sendnews->getSubmitValue('category');
				$tags     = trim($form_sendnews->getSubmitValue('tags'));

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
					$languages = $form_sendnews->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				$gallery_id = $mdb2->extended->getBeforeID('iShark_Galleries', 'gallery_id', TRUE, TRUE);
				$query_gallery = "
					INSERT INTO iShark_Galleries 
					(gallery_id, name, description, add_user_id, add_date, is_active, type)
					VALUES 
					( ".$gallery_id.", '".$title."', '".$lead."', ".$user_id.", NOW(), '$is_active', 'v' )
				";
				$mdb2->exec($query_gallery);
				$last_gallery_id = $mdb2->extended->getAfterID($gallery_id, 'iShark_Galleries', 'gallery_id');

				//ha letezik a $category tomb, akkor felvisszuk a kapcsolotablaba
				if (is_array($category) && count($category) > 0) {
					foreach ($category as $key => $id) {
						$query = "
							INSERT INTO iShark_Gallery_Category 
							(gallery_id, category_id) 
							VALUES 
							($last_gallery_id, $id)
						";
						$mdb2->exec($query);
					}
				}

				if (!empty($tags)) {
				    include_once $include_dir.'/function.tags.php';
                    addTags($tags, 'gallery', $last_gallery_id);
				}

				//képek feltöltése
				$filename = "";
				$pic = TRUE;

				for ($i = 1; $i < $_REQUEST['pic_count']; $i++) {
					if (!empty($_FILES['file_'.$i]['tmp_name'])) {
						$gdir = preg_replace('|/$|','', $_SESSION['site_galerydir']).'/';
						$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($_FILES['file_'.$i]['name']));
						$tn_name = 'tn_'.$filename;

						if($_FILES['file_'.$i]['type'] == 'video/x-msvideo' || $_FILES['file_'.$i]['type'] == 'video/mpeg' || $_FILES['file_'.$i]['type'] == 'video/x-ms-wmv'){
							move_uploaded_file( $_FILES['file_'.$i]['tmp_name'], $gdir.$filename );
							@chmod($gdir.$filename,0664);
							# @chmod($gdir.$tn_name,0664);

    						// altalanos dolgok a videokrol + konvertalashoz
    					    $extension = "ffmpeg";
							$extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
							$extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

							// load extension
							if(!extension_loaded($extension)) {
							    dl($extension_soname);
							}
    
    			    		// Set our source file
    			    		$srcFile    = $gdir.$filename;
    			    		$destFile   = $gdir."tmp_".substr($filename, 0, strrpos($filename, ".")).".flv";
    			    		$newFile    = substr($filename, 0, strrpos($filename, ".")).".flv";
    			    		$thumbFile  = $gdir."tn_".substr($filename, 0, strrpos($filename, ".")).".flv.jpg";
    			    		$ffmpegPath = "ffmpeg";

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
        		    			$command = $ffmpegPath." -i ".$srcFile." -ar ".$srcAR." -ab ".$srcAB." -y -r ".$srcFPS." -f flv -s ".$srcWidth."x".$srcHeight." ".$destFile;
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
							$description = $form_sendnews->getSubmitValue('pic_'.$i.'_desc');
							if ( empty( $description ) ) {
								$description = "";
							}
							$name = $form_sendnews->getSubmitValue('pic_'.$i.'_name');
							if ( empty( $name ) ) {
								$name = "Videó ".$i+1;
							}
							$query = "
								INSERT INTO iShark_Pictures
								(picture_id, realname, name, add_user_id, add_date, description)
								VALUES
								($picture_id, '$filename', '$name', $user_id, now(), '".$description."')
							";
							$mdb2->exec($query);
							$last_picture_id = $mdb2->extended->getAfterID($picture_id, 'iShark_Pictures', 'picture_id');

							$tags = $form_sendnews->getSubmitValue('pic_'.$i.'_tags');

							if (!empty($tags)) {
							    include_once $include_dir.'/function.tags.php';
							    addTags($tags, 'picture', $last_picture_id);
							}

							$query = "
								INSERT INTO iShark_Galleries_Pictures
								(gallery_id, picture_id)
								VALUES
								($last_gallery_id, $last_picture_id)
							";
							$mdb2->exec($query);
							@unlink($_FILES['file_'.$i]['tmp_name']);
						}
					}
				}
				if (!$pic) {
					//$form_sendnews->setElementError('fileupl', $locale->get('error_news_picupload'));
				}

				//"fagyasztjuk" a form-ot
				$form_sendnews->freeze();

				//visszadobjuk a lista oldalra
				header('Location: index.php?success=feedback_send&link=');
				exit;
			}

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'sendnews_pics';
		}

		$form_sendnews->addElement('submit', 'submit', $locale->get('form_submit'), array('class' => 'submit'));
		$form_sendnews->addElement('reset',  'reset',  $locale->get('form_reset'),  array('class' => 'reset'));

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_sendnews->accept($renderer);

		$tpl->assign('form_sendnews', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();
	}
} else {
	if ($module_settings['is_reg']) {
		$tpl->assign('title_is_reg', $locale->get('form_title_is_reg'));
	}
}

if ($module_settings['is_check']) {
	$tpl->assign('title_is_civil', $locale->get('form_title_is_civil'));
}

?>