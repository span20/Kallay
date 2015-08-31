<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

//modul neve
$module_name = "comments";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

$tpl->assign('self_comments', $module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['com_act'])
$is_act = array('comments_lst', 'comments_add', 'comments_mod', 'comments_del');

if (isset($_REQUEST['com_act']) && in_array($_REQUEST['com_act'], $is_act)) {
	$com_act = $_REQUEST['com_act'];
} else {
	$com_act = "comments_lst";
}

//jogosultsag ellenorzes
if (!check_perm($com_act, NULL, 0, $module_name, 'index')) {
	$site_errors[] = array('text' => $locale->get('error_no_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

//lekerdezzuk a tartalomszerkesztohoz tartozo beallitasokat
$query_comments_config = "
	SELECT is_user_reg, captcha, flood, flood_time 
	FROM iShark_Comments_Configs
";
$result_comments_config =& $mdb2->query($query_comments_config);
if (!PEAR::isError($result_comments_config)) {
	$row_comments = $result_comments_config->fetchRow();
} else {
	$site_errors[] = array('text' => $locale->get('error_no_config_table'), 'link' => 'javascript:history.back(-1)');
	return;
}

/**
* ha torolni szeretnenk egy hozzaszolast
 */
if ($com_act == "comments_del" && check_perm('comments_del', NULL, 1, $module_name, 'index') && isset($_REQUEST['coid']) && is_numeric($_REQUEST['coid']) && isset($_REQUEST['module']) && isset($_REQUEST['link'])) {
	$coid        = intval($_REQUEST['coid']);
	$back_module = $_REQUEST['module'];
	$back_link   = $_REQUEST['link'];

	//lekerdezzuk, hogy van-e ilyen hozzaszolas
	$query = "
		SELECT * 
		FROM iShark_Comments 
		WHERE comment_id = $coid AND module_name = '".$back_module."'
	";
	$result =& $mdb2->query($query);
	if ($result->numrows() > 0) {
		//toroljuk a megjegyzest
		$query = "
			DELETE FROM iShark_Comments 
			WHERE comment_id = $coid AND module_name = '".$back_module."'
		";
		$mdb2->exec($query);

		//nullazzuk az elozmenyeket, ahol ez a hozzaszolas szerepelt
		$query = "
			UPDATE iShark_Comments 
			SET premise = 0 
			WHERE premise = $coid AND module_name = '".$back_module."'
		";
		$mdb2->exec($query);

		//loggolas
		logger($com_act);

		header('Location: index.php?p='.$back_module.$back_link);
		exit;
	} else {
	    $site_errors[] = array('text' => $locale->get('error_notexists_comments'), 'link' => 'javascript:history.back(-1)');
	    return;
	}
}

/**
 * ha modositani szeretnenk egy hozzaszolast
 */
elseif ($com_act == "comments_mod" && check_perm('comments_mod', NULL, 1, $module_name, 'index') && isset($_REQUEST['coid']) && is_numeric($_REQUEST['coid']) && isset($_REQUEST['back_id']) && is_numeric($_REQUEST['back_id']) && isset($_REQUEST['module']) && isset($_REQUEST['link'])) {
	$coid        = intval($_REQUEST['coid']);
	$id          = intval($_REQUEST['back_id']);
	$back_module = $_REQUEST['module'];
	$back_link   = $_REQUEST['link'];

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	//elinditjuk a form-ot
	$form_news_comment =& new HTML_QuickForm('frm_comment', 'post', 'index.php?p='.$module_name);

	//a szukseges szoveget jelzo resz beallitasa
	$form_news_comment->setRequiredNote($locale->get('form_required_note'));

	//form-hoz elemek hozzadasa
	$form_news_comment->addElement('header', 'newscomment', $locale->get('form_comment_header'));
	$form_news_comment->addElement('hidden', 'com_act',     'comments_mod');
	$form_news_comment->addElement('hidden', 'back_id',     $id);
	$form_news_comment->addElement('hidden', 'coid',        $coid);
	$form_news_comment->addElement('hidden', 'module',      $back_module);
	$form_news_comment->addElement('hidden', 'link',        $back_link);

	$form_news_comment->addElement('text', 'newscomment_name', $locale->get('field_comment_name'));
	$newscomment =& $form_news_comment->addElement('textarea', 'newscomment_message', $locale->get('field_comment_message'));
	$newscomment->setCols(35);
	$newscomment->setRows(10);

	//lekerdezzuk a megjegyzeshez tartozo adatokat
	$query = "
		SELECT c.comment_id AS comment_id, c.comment AS comment, 
			(CASE c.user_id 
				WHEN '0' THEN c.name
				ELSE u.name
			END
			) AS name
		FROM iShark_Comments c 
		LEFT JOIN iShark_Users u ON u.user_id = c.user_id 
		WHERE comment_id = $coid
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();

		$form_news_comment->setDefaults(array(
			'newscomment_name'    => $row['name'],
			'newscomment_message' => $row['comment']
			)
		);
		//csak olvashatova tesszuk a nev mezot
		$form_news_comment->updateElementAttr('newscomment_name', 'readonly');
	}

	$form_news_comment->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form_news_comment->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="submit"');

	$form_news_comment->applyFilter('__ALL__', 'trim');

	$form_news_comment->addRule('newscomment_name',    $locale->get('error_name'),    'required');
	$form_news_comment->addRule('newscomment_message', $locale->get('error_message'), 'required');

	if ($form_news_comment->validate()) {
		$form_news_comment->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$id          = intval($form_news_comment->getSubmitValue('back_id'));
		$coid        = intval($form_news_comment->getSubmitValue('coid'));
		$message     = strip_tags($form_news_comment->getSubmitValue('newscomment_message'));
		$back_module = $form_news_comment->getSubmitValue('module');
		$back_link   = $form_news_comment->getSubmitValue('link');

		$query = "
			UPDATE iShark_Comments 
			SET comment =  '".$message."'
			WHERE comment_id = $coid AND id = $id
		";
		$mdb2->exec($query);

		header('Location: index.php?p='.$back_module.$back_link.'#'.$coid);
		exit;
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form_news_comment->accept($renderer);

	$tpl->assign('form_news_comment', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'comments_mod';
}

//uj megjegyzes hozzaadasa
elseif ($com_act == "comments_add" && check_perm('comments_add', NULL, 1, $module_name, 'index') && isset($_REQUEST['module']) && isset($_REQUEST['back_id']) && is_numeric($_REQUEST['back_id'])) {
    $id     = intval($_REQUEST['back_id']);
    $module = $_REQUEST['module'];

    //flood figyelese - ha be van kapcsolva
    $is_news_flood = 0;
    if ($row_comments['flood'] == 1) {
        if (checkFlood($module, $row_comments['flood_time']) === false) {
            $is_news_flood = 1;
            $site_errors[] = array('text' => $locale->get('error_flooding'), 'link' => 'javascript:history.back(-1)');
            return;
        }
    }

	if ($is_news_flood == 0) {
		if (($row_comments['is_user_reg'] == 1 && !empty($_SESSION['user_id'])) || $row_commenst['is_user_reg'] == 0) {
			require_once 'HTML/QuickForm.php';
			require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

			//elinditjuk a form-ot
			$form_comment =& new HTML_QuickForm('frm_comment', 'post', 'index.php?p='.$module_name);

			//a szukseges szoveget jelzo resz beallitasa
			$form_comment->setRequiredNote($locale->get('form_required_note'));

			//form-hoz elemek hozzadasa
			$form_comment->addElement('header', 'newscomment', $locale->get('form_comment_header'));
			$form_comment->addElement('hidden', 'com_act',     $com_act);
			$form_comment->addElement('hidden', 'back_id',     $id);
			$form_comment->addElement('hidden', 'module',      $module);

			//ha valamire valaszolunk
			if (!empty($_REQUEST['pre']) && is_numeric($_REQUEST['pre'])) {
			    $pre = intval($_REQUEST['pre']);

				$form_comment->addElement('hidden', 'pre', $pre);

				//kiszedjuk az elozmenyt is, es kiirjuk
				$query_pre = "
					SELECT c.comment_id AS comment_id, c.comment AS comment, c.add_date AS add_date,
						(CASE c.user_id 
							WHEN '0' THEN c.name
							ELSE u.name
						END
						) AS name
					FROM iShark_Comments c 
					LEFT JOIN iShark_Users u ON u.user_id = c.user_id 
					WHERE comment_id = $pre
				";
				$result_pre =& $mdb2->query($query_pre);
				$tpl->assign('predata', $result_pre->fetchRow());
			}

			$form_comment->addElement('text', 'newscomment_name', $locale->get('field_comment_name'));
			$newscomment =& $form_comment->addElement('textarea', 'newscomment_message', $locale->get('field_comment_message'));
			$newscomment->setCols(35);
			$newscomment->setRows(10);

			//ha kell captcha, akkor kirakjuk
			if ($row_comments['captcha'] == 1) {
				require_once 'Text/CAPTCHA.php';

				$form_comment->addElement('text', 'newscomment_recaptcha', $locale->get('field_comment_captcha'), 'class="input_box"');
				$form_comment->addRule('newscomment_recaptcha', $locale->get('error_captcha'), 'required');
				if ($form_comment->isSubmitted() && $form_comment->getSubmitValue('newscomment_recaptcha') != $_SESSION['newscomment_phrase']) {
					$form_comment->setElementError('newscomment_recaptcha', $locale->get('error_compare_captcha'));
				}

				$captcha_options = array(
				    'font_size' => 14,
					'font_path' => $libs_dir.'/',
					'font_file' => 'arial.ttf'
				);

				// Generate a new Text_CAPTCHA object, Image driver
				$nc_class = Text_CAPTCHA::factory('Image');
				$retval  = $nc_class->init(250, 150, null, $captcha_options);

				// Get CAPTCHA secret passphrase
				$_SESSION['newscomment_phrase'] = $nc_class->getPhrase();

				// Get CAPTCHA image (as PNG)
				$newsc_png = $nc_class->getCAPTCHAAsPNG();

				if (!function_exists('file_put_contents')) {
					function file_put_contents($filename, $content) {
						if (!($file = fopen('files/'.$filename, 'w'))) {
							return false;
						}
						$n = fwrite($file, $content);
						fclose($file);
						return $n ? $n : false;
					}
				}
				file_put_contents('newscomment_'.md5(session_id()).'.png', $newsc_png);
				$tpl->assign('newscomment_captcha', 'files/newscomment_'.md5(session_id()).'.png');
			}

			$form_comment->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
			$form_comment->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

			$form_comment->applyFilter('__ALL__', 'trim');

			$form_comment->addRule('newscomment_name',    $locale->get('error_name'),    'required');
			$form_comment->addRule('newscomment_message', $locale->get('error_message'), 'required');

			//ha be van jelentkezve a user, akkor kitoltunk par mezot
			if (isset($_SESSION['user_id'])) {
				//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
				$form_comment->setDefaults(array(
					'newscomment_name'  => $_SESSION['username']
					)
				);
				//csak olvashatova tesszuk a nev mezot
				$form_comment->updateElementAttr('newscomment_name', 'readonly');
			}

			if ($form_comment->validate()) {
				$form_comment->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$message = strip_tags($form_comment->getSubmitValue('newscomment_message'));

				//ha valaszolunk
				if ($form_comment->getSubmitValue('pre')) {
					$premise = intval($form_comment->getSubmitValue('pre'));
				} else {
					$premise = 0;
				}

				//ha be van allitva a user_id session, akkor csak azt irjuk be a tablaba
				if (isset($_SESSION['user_id'])) {
					$uid   = $_SESSION['user_id'];
					$name  = "";
				} else {
					$uid   = 0;
					$name  = $form_comment->getSubmitValue('newscomment_name');
				}

				$newscomment_id = $mdb2->extended->getBeforeID('iShark_Contents_Comments', 'comment_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Comments 
					(comment_id, module_name, id, user_id, name, add_date, comment, premise) 
					VALUES 
					($newscomment_id, '".$module."', $id, $uid, '".$name."', NOW(), '".$message."', $premise)
				";
				$mdb2->exec($query);

				//ha a captcha engedelyezve van, akkor toroljuk a file-t
				if ($row_comments['captcha'] == 1) {
					unset($_SESSION['newscomment_phrase']);
					@unlink('files/newscomment_'.md5(session_id()).'.jpg');
				}

				//ha van flood figyeles, akkor beallitjuk a cookie-t
				if ($row_comments['flood'] == 1) {
				    addFlood($module);
				}

				header('Location: index.php?p=success&code=017');
				exit;
			}

			$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
			$form_comment->accept($renderer);

			$tpl->assign('form_comment', $renderer->toArray());

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_array', ob_get_contents());
			ob_end_clean();

			$acttpl = "comments_add";
		} else {
		    $site_errors[] = array('text' => $locale->get('error_no_writeperm'), 'link' => 'javascript:history.back(-1)');
		    return;
		}
	} else {
	    $site_errors[] = array('text' => $locale->get('error_flooding'), 'link' => 'javascript:history.back(-1)');
		return;
	}
}
else {
    $query = "
		SELECT c.comment_id AS comment_id, c.add_date AS add_date, c.comment AS comment, c.premise AS premise, 
			(CASE c.user_id 
				WHEN '0' THEN c.name
				ELSE u.name
				END
			) AS name
		FROM iShark_Comments c 
		LEFT JOIN iShark_Users u ON u.user_id = c.user_id 
		WHERE c.id = $back_comment_id AND module_name = '".$back_comment_module."' 
		ORDER BY c.add_date DESC
	";
    $result =& $mdb2->query($query);
    if ($result->numRows() > 0) {
        $tpl->assign('news_comment', $result->fetchAll('', $rekey = true));
    }

    //par valtozot at kell adnunk smarty-nak, mert ettol fuggoen rakjuk ki az uj hozzaszolas, modositas, torles gombot
    $tpl->assign('is_user_reg',           $row_comments['is_user_reg']);
    $tpl->assign('is_newscomment_modify', check_perm('comments_mod', NULL, 1, $module_name, 'index'));
    $tpl->assign('is_newscomment_delete', check_perm('comments_del', NULL, 1, $module_name, 'index'));
    $tpl->assign('back_module',           $back_comment_module);
	$tpl->assign('back_id',               $back_comment_id);
	$tpl->assign('back_link',             $back_comment_link);
}

?>