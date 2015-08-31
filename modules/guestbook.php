<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = 'guestbook';

//nyelvi valtozok betoltese
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('guestbook_lst', 'guestbook_add', 'guestbook_ena', 'guestbook_del', 'guestbook_rep');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "guestbook_lst";
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
	$site_errors[] = array('text' => $locale->get('config', 'error_permission'), 'link' => 'index.php');
	return;
}

//lekerdezzuk a modulhoz tartozo beallitasokat
$query = "
	SELECT g.is_admin_grant AS agrant, g.is_user_reg AS ureg, g.is_mail AS is_mail, g.email AS email, g.captcha AS captcha,
		g.flood AS gflood, g.flood_time AS gftime
	FROM iShark_Guestbook_Configs g
";
$result = $mdb2->query($query);
if (PEAR::isError($result) || $result->numRows()==0) {
	$site_errors[] = array('text' => $locale->get('error_missing_table'), 'link' => 'index.php');
	return;
}
$tpl->assign('content_title', $locale->get('title'));
$row = $result->fetchRow();

$admin_grant = $row['agrant'];
$user_reg    = $row['ureg'];
$is_mail     = $row['is_mail'];
$gemail      = $row['email'];
$captcha     = $row['captcha'];
$flood       = $row['gflood'];
$ftime       = $row['gftime'];

/**
 * ha uj bejegyzest adunk hozza
 */
if ($act == "guestbook_add") {
	if ($user_reg == 1 && !isset($_SESSION['user_id'])) {
		$site_errors[] = array('text' => $locale->get('error_only_reg'), 'link' => 'index.php?p='.$module_name);
		return;
	} else {
		$ip = get_ip();

		$is_gb_flood = 0;
		//ha figyeljuk a floodolast
		if ($flood == 1) {
		    if (checkFlood($module_name, $ftime) === false) {
				$is_gb_flood = 1;
				$site_errors[] = array('text' => $locale->get('error_flood'), 'link' => 'index.php?p='.$module_name);
				return;
			}
		}

		if ($is_gb_flood == 0) {
			require_once 'HTML/QuickForm.php';
			require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

			//elinditjuk a form-ot
			$form_guestbook =& new HTML_QuickForm('frm_guestbook', 'post', 'index.php?p='.$module_name);

			//a szukseges szoveget jelzo resz beallitasa
			$form_guestbook->setRequiredNote($locale->get('form_required_note'));

			//form-hoz elemek hozzadasa
			$tpl->assign('content_title', $locale->get('title').' - '.$locale->get('form_add_header'));
			$form_guestbook->addElement('header', 'guestbook', $locale->get('form_add_header'));
			$form_guestbook->addElement('hidden', 'act',       $act);

			//nev
			$form_guestbook->addElement('text', 'guestbook_name',  $locale->get('field_name'));
			//email cim
			$form_guestbook->addElement('text', 'guestbook_email', $locale->get('field_email'));
			//uzenet
			$message =& $form_guestbook->addElement('textarea', 'guestbook_message', $locale->get('field_message'));
			$message->setCols(80);
			$message->setRows(10);

			$form_guestbook->addElement('submit', 'gb_submit', $locale->get('form_submit'), array('class' => 'submit'));
			$form_guestbook->addElement('reset',  'gb_reset',  $locale->get('form_reset'),  array('class' => 'submit'));

			//ha a captcha engedelyezve van
			if ($captcha == 1) {
				require_once 'Text/CAPTCHA.php';

				$form_guestbook->addElement('text', 'gb_recaptcha', $locale->get('field_recaptcha'));
				$form_guestbook->addRule('gb_recaptcha', $locale->get('error_required_recaptcha'), 'required');
				if ($form_guestbook->isSubmitted() && $form_guestbook->getSubmitValue('gb_recaptcha') != $_SESSION['gb_phrase']) {
					$form_guestbook->setElementError('gb_recaptcha', $locale->get('error_failed_recaptcha'));
				}

				$optionsgb = array(
					'font_size' => 18,
					'font_path' => $libs_dir.'/',
					'font_file' => 'arial.ttf',
					'color'     => array('#CE9B44', '#000000'),
					'bgcolor'   => '#EFC47A'
				);

				// Generate a new Text_CAPTCHA object, Image driver
				$cgb = Text_CAPTCHA::factory('Image');
				$retvalgb = $cgb->init(180, 50, null, $optionsgb);

				// Get CAPTCHA secret passphrase
				$_SESSION['gb_phrase'] = $cgb->getPhrase();

				// Get CAPTCHA image (as PNG)
				$pnggb = $cgb->getCAPTCHAAsPNG();

				if (!function_exists('file_put_contents')) {
					function file_put_contents($filename, $contentgb) {
						if (!($filegb = @fopen($filename, 'w'))) {
							return false;
						}
						$ngb = fwrite($filegb, $contentgb);
						fclose($filegb);
						return $ngb ? $ngb : false;
					}
				}
				file_put_contents('files/gb_'.md5(session_id()) . '.png', $pnggb);
				$tpl->assign('gb_captcha', 'files/gb_'.md5(session_id()).'.png?'.time());
			}

			$form_guestbook->applyFilter('__ALL__', 'trim');

			$form_guestbook->addRule('guestbook_name',    $locale->get('error_required_name'),    'required');
			$form_guestbook->addRule('guestbook_email',   $locale->get('error_required_email'),   'required');
			$form_guestbook->addRule('guestbook_email',   $locale->get('error_invalid_email'),    'email');
			$form_guestbook->addRule('guestbook_message', $locale->get('error_required_message'), 'required');

			//ha be van jelentkezve a user, akkor kitoltunk par mezot
			if (isset($_SESSION['user_id'])) {
				$query = "
					SELECT u.user_name AS uname, u.email AS umail
					FROM iShark_Users u
					WHERE user_id = '".$_SESSION['user_id']."'
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
					while ($row = $result->fetchRow())
					{
						//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
						$form_guestbook->setDefaults(array(
							'guestbook_name'  => $row['uname'],
							'guestbook_email' => $row['umail']
							)
						);
						//csak olvashatova tesszuk a nev es e-mail mezoket
						$form_guestbook->updateElementAttr('guestbook_name',  'readonly');
						$form_guestbook->updateElementAttr('guestbook_email', 'readonly');
					}
				}
			}

			if ($form_guestbook->validate()) {
				$form_guestbook->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$gb_message = strip_tags($form_guestbook->getSubmitValue('guestbook_message'));
				//ha be van allitva a user_id session, akkor csak azt irjuk be a tablaba
				if (isset($_SESSION['user_id'])) {
					$uid   = $_SESSION['user_id'];
					$name  = "";
					$email = ""; //ez az e-mail cim kerul be a tablaba, ha nem regisztralt a felhasznalo
					$smail = $gemail; //ezzel az e-mail cimmel megy ki a level, ha kuldunk
				} else {
					$uid   = "";
					$name  = $form_guestbook->getSubmitValue('guestbook_name');
					$email = $form_guestbook->getSubmitValue('guestbook_email');
					$smail = $email;
				}
				//ha csak admin engedellyel kerulhet ki az iras
				if ($admin_grant == 1 && !check_perm($act, '', 0, $module_name, 'index')) {
						$enabled = 0;
					} else {
						$enabled = 1;
					}

					$guestbook_id = $mdb2->extended->getBeforeID('iShark_Guestbook', 'guestbook_id', TRUE, TRUE);
					$query = "
						INSERT INTO iShark_Guestbook
						(guestbook_id, add_user_id, add_user_name, add_date, email, message, is_enabled, ip)
						VALUES
						($guestbook_id, '$uid', '".$name."', NOW(), '".$email."', '".$gb_message."', '$enabled', '$ip')
					";
					$mdb2->exec($query);
					$last_guestbook_id = $mdb2->extended->getAfterID($guestbook_id, 'iShark_Guestbook', 'guestbook_id');

					//ha csak adminisztratori engedellyel lehet irni es kell errol mail-t kuldeni
					if ($admin_grant == 1 && $is_mail == 1) {
						ini_set('display_errors', 0);
						include_once 'Mail.php';
						include_once 'Mail/mime.php';

						$hdrs = array(
							'From'    => '"'.preg_replace('|"|', '\"', $name).'" <'.$smail.'>',
							'Subject' => $locale->get('mail_subject')
						);
						$mime =& new Mail_mime("\n");
						$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

						$msg = $locale->get('mail_text1').'!<br /><br />';
						$msg .= '<table style="width: 100%; text-align: left;">';
						$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->get('mail_text2').'</th></tr>';
						$msg .= '<tr><td colspan="2" valign="top">'.$message.'</td></tr>';
						$msg .= '</table><br />';
						$msg .= $locale->get('mail_text3').'<br />';
						$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p='.$module_name.'&act=guestbook_ena&gid='.$last_guestbook_id.'" title="'.$locale->get('mail_text4').'">'.$locale->get('mail_text4').'</a><br /><br />';
						$msg .= $locale->get('mail_text5').'<br />';
						$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p='.$module_name.'&act=guestbook_del&gid='.$last_guestbook_id.'" title="'.$locale->get('mail_text6').'">'.$locale->get('mail_text6').'</a><br /><br />';
						$msg .= $locale->get('mail_text7');

						if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
							$tpl->assign('mail_body', $msg);
							$msg = $tpl->fetch('mail/mail_html.tpl');
						}

						$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
						$mime->setHTMLBody($msg);

						// Karakterkészlet beállítások
						$mime_params = array(
							"text_encoding" => "8bit",
							"text_charset"  => $charset,
							"head_charset"  => $charset,
							"html_charset"  => $charset,
						);

						$body = $mime->get($mime_params);
						$hdrs = $mime->headers($hdrs);

						$mail =& Mail::factory('mail');
						$mail->send($gemail, $hdrs, $body);
					}

					//ha a captcha engedelyezve van, akkor toroljuk a file-t
					if ($captcha == 1) {
						unset($_SESSION['gb_phrase']);
						@unlink('files/gb_'.md5(session_id()).'.jpg');
					}

					//fagyasztjuk a formot
					$form_guestbook->freeze();

					//ha az admin engedelye kell a jovahagyashoz, akkor mas uzenet jelenik meg
					if ($admin_grant == 1 && !check_perm('guestbook_add', '', 1, $module_name, 'index')) {
						header('Location: index.php?success=guestbook_adda&link=index.php?p='.$module_name);
						exit;
					}
					if ($admin_grant == 0 || check_perm('guestbook_add', '', 0, $module_name, 'index')) {
						header('Location: index.php?success=guestbook_add&link=index.php?p='.$module_name);
						exit;
					}
				}

				$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
				$form_guestbook->accept($renderer);

				$tpl->assign('form_guestbook', $renderer->toArray());

				// capture the array stucture
				ob_start();
				print_r($renderer->toArray());
				$tpl->assign('static_array', ob_get_contents());
				ob_end_clean();

				//megadjuk a tpl file nevet, amit atadunk az index.php-nek
				$acttpl = "guestbook_add";
			}
		}
	} //uj bejegyzes vege

	/**
	 * ha torlunk egy bejegyzest
	 */
	else if ($act == "guestbook_del" && check_perm('guestbook_del', '', 1, $module_name, 'index') === true) {
		if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
			$gid = intval($_REQUEST['gid']);

			$query = "
				DELETE FROM iShark_Guestbook
				WHERE guestbook_id = $gid
			";
			$mdb2->exec($query);

			//loggolas
			logger($act);

			header('Location: index.php?success=guestbook_del&link=index.php?p='.$module_name);
			exit;
		}
	} //torles vege

	/**
	 * ha tiltunk, engedelyezunk egy bejegyzest
	 */
	else if ($act == "guestbook_ena" && check_perm('guestbook_ena', '', 1, $module_name, 'index') === true) {
		if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
			$gid = intval($_REQUEST['gid']);

			$query = "
				SELECT is_enabled
				FROM iShark_Guestbook
				WHERE guestbook_id = $gid
			";
			$result =& $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				if ($row['is_enabled'] == 0) {
					$query = "
						UPDATE iShark_Guestbook
						SET is_enabled = 1
						WHERE guestbook_id = $gid
					";
				} else {
					$query = "
						UPDATE iShark_Guestbook
						SET is_enabled = 0
						WHERE guestbook_id = $gid
					";
				}
				$mdb2->exec($query);

				//loggolas
				logger($act);

				header('Location: index.php?success=guestbook_ena&link=index.php?p='.$module_name);
				exit;
			}
		}
	} //tiltas, engedelyezes vege

	/**
	 * ha valaszolunk egy bejegyzesre
	 */
	else if ($act == "guestbook_rep" && check_perm('guestbook_rep', '', 1, $module_name, 'index') === true) {
		if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
			$gid = intval($_REQUEST['gid']);

			require_once 'HTML/QuickForm.php';
			require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

			//elinditjuk a form-ot
			$form_guestbook =& new HTML_QuickForm('frm_guestbook', 'post', 'index.php?p='.$module_name);

			//a szukseges szoveget jelzo resz beallitasa
			$form_guestbook->setRequiredNote($locale->get('form_required_note'));

			//form-hoz elemek hozzadasa
			$tpl->assign('content_title', $locale->get('title').' - '.$locale->get('form_rep_header'));
			$form_guestbook->addElement('header', 'guestbook', $locale->get('form_rep_header'));
			$form_guestbook->addElement('hidden', 'act',       'guestbook_rep');
			$form_guestbook->addElement('hidden', 'gid',       $gid);

			//valasz
			$answer =& $form_guestbook->addElement('textarea', 'guestbook_answer', $locale->get('field_answer'));
			$answer->setCols(40);
			$answer->setRows(10);

			$form_guestbook->addElement('submit', 'submit', $locale->get('form_submit'), array('class' => 'submit'));
			$form_guestbook->addElement('reset',  'reset',  $locale->get('form_reset'),  array('class' => 'submit'));

			$form_guestbook->applyFilter('__ALL__', 'trim');

			$form_guestbook->addRule('guestbook_answer', $locale->get('error_required_answer'), 'required');

			if ($form_guestbook->validate()) {
				$form_guestbook->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$answer = strip_tags($form_guestbook->getSubmitValue('guestbook_answer'));

				$query = "
					UPDATE iShark_Guestbook
					SET answer = '".$answer."'
					WHERE guestbook_id = $gid
				";
				$mdb2->exec($query);

				//loggolas
				logger($act);

				header('Location: index.php?success=guestbook_rep&link=index.php?p='.$module_name);
				exit;
			}

			$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
			$form_guestbook->accept($renderer);

			$tpl->assign('form_guestbook', $renderer->toArray());

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_form', ob_get_contents());
			ob_end_clean();

			//megadjuk a tpl file nevet, amit atadunk az index.php-nek
			$acttpl = "guestbook_reply";
		}
	} //valasz vege

	//hozzaszolasok listaja
	else {
		$query = "
			SELECT g.guestbook_id AS gid, u.name AS uname, u.user_name AS username, u.email AS umail, u.is_public_mail AS pmail,
				g.add_date AS add_date, g.message AS gmess, g.answer AS gans, g.email AS gemail, g.is_enabled AS gena, g.add_user_name AS gname
			FROM iShark_Guestbook g
			LEFT JOIN iShark_Users u ON u.user_id = g.add_user_id
		";
		//ha engedelyeznie kell adminnak, es nincs hozza joga
		$is_enable_link = "";
		if ($admin_grant == 1) {
			if (check_perm('guestbook_ena', '', 1, $module_name, 'index') === false) {
				$query .= "
					WHERE g.is_enabled = '1'
				";
			} else {
				$is_enable_link = "index.php?p=".$module_name."&amp;act=guestbook_ena&amp;gid=";
			}
		}
		$query .= "
			ORDER BY g.guestbook_id DESC
		";

		//ha van torles joga
		$is_delete_link = "";
		if (check_perm('guestbook_del', '', 1, $module_name, 'index') === true) {
			$is_delete_link = "index.php?p=".$module_name."&amp;act=guestbook_del&amp;gid=";
		}

		//ha van valasz joga
		$is_reply_link = "";
		if (check_perm('guestbook_rep', '', 1, $module_name, 'index') === true) {
			$is_reply_link = "index.php?p=".$module_name."&amp;act=guestbook_rep&amp;gid=";
		}

		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('page_data',      $paged_data['data']);
		$tpl->assign('page_list',      $paged_data['links']);
		$tpl->assign('total',          $paged_data['totalItems']);

		$tpl->assign('self',           $module_name);
		$tpl->assign('is_enable_link', $is_enable_link);
		$tpl->assign('is_delete_link', $is_delete_link);
		$tpl->assign('is_reply_link',  $is_reply_link);

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'guestbook_list';
	}

?>
