<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//nyelvi file
$locale->useArea('index_account');

$module_title = "Regisztráció";

//ezek az elfogadhato muveleti hivasok ($act)
$is_act = array('account_cal', 'account_add', 'account_mod', 'account_lst', 'account_del', 'account_in', 'account_out', 'account_act', 'account_lstact');

/**
 * ha valamilyen muveletet hajtunk vegre
 */
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];

	if ($act == 'account_mod' && !isset($_SESSION['user_id'])) {
		header("Location: index.php");
		exit;
	}
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	include_once $include_dir.'/function.check.php';

	//a form feltoltese, de csak add vagy mod resznel
    if ($act == "account_mod") {
        $result = $mdb2->query("SELECT * FROM iShark_Users WHERE user_id = '".$_SESSION["user_id"]."'");
        $row = $result->fetchRow();

        $ert_array = str_split($row["ertesites"]);

        $result_naptar1 = $mdb2->query("SELECT esemeny_id, user_id FROM iShark_Users_Add WHERE user_id = '".$_SESSION["user_id"]."'");
        $naptar_data1 = $result_naptar1->fetchAll('', $rekey = true);

        $result_naptar2 = $mdb2->query("SELECT date_id, esemeny_id, datum FROM iShark_Users_Add2 WHERE user_id = '".$_SESSION["user_id"]."'");
        $naptar_data2 = $result_naptar2->fetchAll('', $rekey = true);

        //print_r($naptar_data1);
        //echo "<br><br>";
        //print_r($naptar_data2);
        $tpl->assign("user_data", $row);
        $tpl->assign("naptar_data", $naptar_data1);
        $tpl->assign("naptar_data2", $naptar_data2);
    }

	if ($act == "account_add" || $act == "account_mod") {
		// ha nincs regisztracios jog
		if ($_SESSION['site_userlogin'] == 0) {
			$site_errors[] = array('text' => $locale->get('error_not_register'), 'link' => 'javascript:history.back(-1)');
			return;
		}
		$form_account =& new HTML_QuickForm(null, 'post', 'index.php?p=account');

		$form_account->setRequiredNote($locale->get('form_required_note'));

		//$form_account->addElement('text', 'name',      $locale->get('form_name'),     array("maxlength" => 255));
		//$form_account->addElement('text', 'user_name', $locale->get('form_username'), array("maxlength" => 255));
		$form_account->addElement('text', 'email', 'E-mail', array("maxlength" => 255, "style" => "width: 150px;"));
		//$ispublicmail =& $form_account->addElement('checkbox', 'is_public_mail', $locale->get('form_publicmail'));

		$form_account->addElement('password', 'pass1', 'Jelszó', array("id" => "pass1", "maxlength" => 30, "style" => "width: 150px;"));
		$form_account->addElement('password', 'pass2', 'Jelszó megerõsítése', array("id" => "pass2", "maxlength" => 30, "style" => "width: 150px;"));
 
		//ha active a hirlevel modul
		if (isModule('newsletter') === true) {
			$subscribe_nl =& $form_account->addElement('checkbox', 'subscribe', $locale->get('form_subscribe'));
		}

		//szurok beallitasa
		$form_account->applyFilter('__ALL__', 'trim');

		//szabalyok beallitasa
		//$form_account->addRule('name',      $locale->get('error_name'),     'required');
		//$form_account->addRule('user_name', $locale->get('error_username'), 'required');
		$form_account->addRule('email',     "A mezõ kitöltése kötelezõ!",    'required');
		$form_account->addRule('email',     "Hibás e-mail cím!",   'email');
		$form_account->addRule('pass1',     "A megadott jelszó túl rövid!",  'minlength', $_SESSION['site_minpass']);
		$form_account->addRule(array('pass1', 'pass2'), "A két jelszó nem egyezik!", 'compare');

		/**
		 * ha eloszor jelentkezik be (regisztracio helyett)
		 */
		if ($act == "account_add") {
			$form_account->addRule('pass1', $locale->get('error_pass1'), 'required');
			$form_account->addRule('pass2', $locale->get('error_pass2'), 'required');

			$form_account->addElement('header', 'account', $locale->get('form_header_add'));
			$form_account->addElement('hidden', 'act',     'account_add');

			$form_account->addFormRule('check_adduser');
			if ($form_account->validate()) {
				$form_account->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				//$name      = $form_account->getSubmitValue('name');
				$email = $form_account->getSubmitValue('email');

				$password  = md5($form_account->getSubmitValue('pass1'));
				//require_once "Text/Password.php";
				//$activate = Text_Password::create(8, 'unpronounceable', 'alphanumeric');

				$user_id = $mdb2->extended->getBeforeID('iShark_Users', 'user_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Users 
					(user_id, email, password, is_deleted, is_active, is_public)
					VALUES 
					($user_id, '$email', '$password', '0', '1', '1')
				";
				$mdb2->exec($query);
                $last_user_id = $mdb2->extended->getAfterID($user_id, 'iShark_Users', 'user_id');

				// Hírlevél feliratkozás, ha aktiv a hirlevel modul
				if (isModule('newsletter') === true) {
					if ($subscribe_nl->getChecked()) {
						$query = "
							SELECT count(*) AS cnt 
							FROM iShark_Newsletter_Users 
							WHERE email = '$email'
						";
						$result =& $mdb2->query($query);

						if ($row = $result->fetchRow()) {
							// Ha még nem iratkozott fel a hírlevélre:
							if ((int)$row['cnt'] == 0) {
							    $newsletter_user_id = $mdb2->extended->getBeforeID('iShark_Newsletter_Users', 'newsletter_user_id', TRUE, TRUE);
								$query = "
									INSERT INTO iShark_Newsletter_Users 
									(newsletter_user_id, name, email, activate, is_active, is_deleted)
									VALUES 
									($newsletter_user_id, '$name', '$email', '$activate', '0', '0')
								";
								$mdb2->exec($query);
							}
						}
					}
				}

				//kikuldunk a megadott e-mail cimre egy levelet
				/*ini_set('display_errors', 0);
				include_once 'Mail.php';
				include_once 'Mail/mime.php';

                $mail =& Mail::factory('mail');

				$hdrs = array(
					'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
					'Subject' => "Fireeng regisztráció"
				);
				$mime = new Mail_mime("\n");
                $mime_admin = new Mail_mime("\n");
				$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();
				*/
				/*$msg = $locale->get('mail_activate_header').' '.$name.'!<br /><br />';
				$msg .= $locale->get('mail_activate_text1').'<br />';
				$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p=account&act=account_act&uname='.$name.'&gc='.$activate.'" title="'.$locale->get('mail_activate_text2').'">'.$locale->get('mail_activate_text2').'</a><br /><br />';
				$msg .= $locale->get('mail_activate_text3').'<br />';
				$msg .= $_SESSION['site_sitehttp'].'/index.php?p=account&act=account_act&uname='.$name.'&gc='.$activate.'<br /><br />';
				$msg .= $locale->get('mail_activate_text4').'<br />';
				$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p=account&act=account_del&uname='.$name.'&gc='.$activate.'" title="'.$locale->get('mail_activate_text5').'">'.$locale->get('mail_activate_text5').'</a><br /><br />';
				$msg .= $locale->get('mail_activate_text6').'<br /><a href="'.$_SESSION['site_sitehttp'].'" title="'.$_SESSION['site_sitename'].'">'.$_SESSION['site_sitename'].'</a>';*/

                /*$msg = '
                    Tisztelt '.$cegnev.'!<br /><br />
                    Köszönjük regisztrációját!<br /><br />
                    Üdvözlettel:<br />
                    Fireeng Kft.
                ';

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

				$mail->send($email, $hdrs, $body);

                //admin
                $msg_admin = '
                    Regisztráció a honalpon!<br /><br />
                    Fireeng Kft.
                ';

				if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
					$tpl->assign('mail_body', $msg_admin);
					$msg_admin = $tpl->fetch('mail/mail_html.tpl');
				}

				$mime_admin->setTXTBody(html_entity_decode(strip_tags($msg_admin)));
				$mime_admin->setHTMLBody($msg_admin);

				// Karakterkészlet beállítások
				$mime_admin_params = array(
					"text_encoding" => "8bit",
					"text_charset"  => $charset,
					"head_charset"  => $charset,
					"html_charset"  => $charset,
				);

				$body_admin = $mime_admin->get($mime_admin_params);
				$hdrs = $mime_admin->headers($hdrs);
				
                $mail->send("webmester@fireeng.hu", $hdrs, $body_admin);
				$mail->send("moodspan@gmail.com", $hdrs, $body_admin);*/

				//"fagyasztjuk" a form-ot
				$form_account->freeze();

				//visszadobjuk a lista oldalra
				header('Location: index.php?p=game');
				exit;
			}
		} //regisztracio vege

		/**
		 * ha modosit
		 */
		if ($act == "account_mod") {
			$form_account->addElement('header',   'account', $locale->get('form_header_mod'));
			$form_account->addElement('hidden',   'act',     'account_mod');

			$mod_pass =& $form_account->addElement('checkbox', 'modpass', "Jelszó módosítás", null, array("id"=>"modpass", "onClick" => "modPassActivate()"));
			$form_account->addElement('password', 'oldpass', "Régi jelszó", array('maxlength' => '30'));
			$form_account->updateElementAttr('name', 'readonly');

			//lekerdezzuk az adatokat az alapbeallitasokhoz
			$query = "
				SELECT name, user_name, email, cegnev
				FROM iShark_Users
				WHERE user_id = '".$_SESSION['user_id']."'
			";
			$result = $mdb2->query($query);
			if ($row = $result->fetchRow()) {
				$form_account->setDefaults($row);
				$name  = $row['name'];
				$email = $row['email'];
				//ha van hirlevel modul
				if (isModule('newsletter') === true) {
					$query2 = "
						SELECT * 
						FROM iShark_Newsletter_Users 
						WHERE name = '$name' AND email = '$email'
					";
					$result2 =& $mdb2->query($query2);
					if ($result2->numRows() > 0) {
						$form_account->setDefaults(array(
							'subscribe' => 1
							)
						);
					}
				}
			} else {
				header("Location: index.php");
				exit;
			}
			include_once 'includes/function.check.php';
			$form_account->addFormRule('check_oldpassword');

			// Javascript miatt kellett
			if ($mod_pass->getChecked()) {
				$tpl->assign('none_block', 'block');
			} else {
				$tpl->assign('none_block', 'none');
			}

			if ($form_account->validate()) {
				$form_account->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name      = $form_account->getSubmitValue('name');
				$user_name = $form_account->getSubmitValue('user_name');
				$email     = $form_account->getSubmitvalue('email');
                $cegnev = $form_account->getSubmitValue('cegnev');
                $ertesit = $form_account->getSubmitValue('ertesito');
				//$is_pmail  = $ispublicmail->getChecked() ? '1' : '0';

                $ert = "";
                if (isset($ertesit['nap'])) {
                    $ertesit['nap'] ? $ert .= "1" : $ert .= "0";
                } else {
                    $ert .= "0";
                }
                if (isset($ertesit['het'])) {
                    $ertesit['het'] ? $ert .= "1" : $ert .= "0";
                } else {
                    $ert .= "0";
                }
                if (isset($ertesit['honap'])) {
                    $ertesit['honap'] ? $ert .= "1" : $ert .= "0";
                } else {
                    $ert .= "0";
                }

                if (!empty($ertesit['all'])) $ert = "111";

				$pass_change = '';
				if ($mod_pass->getChecked()) {
					$password = md5($form_account->getSubmitValue('pass1'));
					$pass_change = ", password='$password'";
				}
				$query = "
					UPDATE iShark_Users 
					SET email          = '$email', 
						user_name      = '$user_name',
                        cegnev         = '$cegnev',
                        ertesites      = '".$ert."'
						$pass_change
					WHERE user_id = '".$_SESSION['user_id']."' AND name = '".$name."'
				";
				$result = $mdb2->query($query);

                $mdb2->exec("DELETE FROM iShark_Users_Add WHERE user_id = '".$_SESSION["user_id"]."'");
                $mdb2->exec("DELETE FROM iShark_Users_Add2 WHERE user_id = '".$_SESSION["user_id"]."'");

                foreach ($_REQUEST["esemCheck"] as $key => $value) {
                    $query = "
                        INSERT INTO iShark_Users_Add
                        (user_id, esemeny_id)
                        VALUES
                        ('".$_SESSION['user_id']."', '".$key."')
                    ";
                    $mdb2->exec($query);

                    foreach ($_REQUEST["date"][$key] as $dkey => $dvalue) {
                        $query2 = "
                            INSERT INTO iShark_Users_Add2
                            (user_id, esemeny_id, date_id, datum)
                            VALUES
                            ('".$_SESSION['user_id']."', '".$key."', '".$dkey."', '".$dvalue."')
                        ";
                        $mdb2->exec($query2);
                    }
                }

				//ha van hirlevel modul
				if (isModule('newsletter') === true) {
					$query2 = "
						SELECT * 
						FROM iShark_Newsletter_Users 
						WHERE name = '$name'
					";
					$result2 =& $mdb2->query($query2);
					//ha a user mar fel van iratkozva a hirlevelre
					if ($result2->numRows() > 0) {
						//ha be van pipalva a hirlevel, akkor csak frissitjuk az adatokat
						if ($subscribe_nl->getChecked()) {
							$query3 = "
								UPDATE iShark_Newsletter_Users 
								SET email = '$email' 
								WHERE name = '$name'
							";
							$mdb2->exec($query3);
						}
						//ha nincs bepipalva a mezo, akkor a torlest jelzo mezot 1-re allitjuk
						else {
							$query3 = "
								UPDATE iShark_Newsletter_Users 
								SET is_deleted = 1 
								WHERE name = '$name'
							";
							$mdb2->exec($query3);
						}
					}
					//ha meg nincs feliratkozva hirlevelre
					else {
						//ha be van pipalva a hirlevel, akkor csak frissitjuk az adatokat
						if ($subscribe_nl->getChecked()) {
							$query3 = "
								INSERT INTO iShark_Newsletter_Users 
								(name, email, is_active, is_deleted)
								VALUES 
								('$name', '$email', '1', '0')
							";
							$mdb2->exec($query3);
						}
					}
				}

				//"fagyasztjuk" a form-ot
				$form_account->freeze();

				$_SESSION['realname'] = $user_name;

				header('Location: index.php?success=account_mod&link=');
				exit;
			}
		} //modositas vege

		$form_account->addElement('submit', 'submit', 'Mehet');
		$form_account->addElement('reset',  'reset',  $locale->get('button_reset'),  array('class' => 'reset'));

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

		$form_account->accept($renderer);
		$tpl->assign('form_account', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az index.php-nek
		$acttpl = 'account_main';
	}

	/**
	 * elfelejtett jelszo
	 */
	if ($act == "account_lst") {
		$form_account =& new HTML_QuickForm('frm_account', 'post', 'index.php?p=account');

		$form_account->setRequiredNote($locale->get('form_required_note'));

		$form_account->addElement('header', 'account_lost', $locale->get('form_header_lostpass'));
		$form_account->addElement('hidden', 'act',          'account_lst');
		$form_account->addElement('text',   'name',         $locale->get('form_name'));
		$form_account->addElement('text',   'email',        $locale->get('form_email'));

		//szurok beallitasa
		$form_account->applyFilter('__ALL__', 'trim');

		//szabalyok beallitasa
		$form_account->addRule('name',  $locale->get('error_name'),   'required');
		$form_account->addRule('email', $locale->get('error_email'),  'required');
		$form_account->addRule('email', $locale->get('error_email2'), 'email');

		$form_account->addFormRule('check_userlostpass');
		if ($form_account->validate()) {
			$form_account->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name     = $form_account->getSubmitValue('name');
			$email    = $form_account->getSubmitValue('email');

			require_once "Text/Password.php";
			$password = Text_Password::create(8, 'unpronounceable', 'alphanumeric');
			$activate = Text_Password::create(8, 'unpronounceable', 'alphanumeric');

			$query = "
				UPDATE iShark_Users 
				SET activate      = '$activate', 
					lost_password = '".md5($password)."' 
				WHERE name = '".$name."' AND email = '".$email."'
			";
			$result = $mdb2->query($query);

			//kikuldunk a megadott mail cimre egy levelet
			ini_set('display_errors', 0);
			include_once 'Mail.php';
			include_once 'Mail/mime.php';

			$hdrs = array(
				'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
				'Subject' => $locale->get('mail_lostpass_subject')
			);
			$mime =& new Mail_mime("\n");
			$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			$msg = $locale->get('mail_activate_header').' '.$name.'!<br /><br />';
			$msg .= $locale->get('mail_lostpass_text1').'<br /><br />';
			$msg .= $locale->get('mail_lostpass_text2').'<br /><b>'.$password.'</b><br /><br />';
			$msg .= $locale->get('mail_lostpass_text3').'<br />';
			$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p=account&act=account_lstact&uname='.$name.'&gc='.$activate.'" title="'.$locale->get('mail_lostpass_text4').'">'.$locale->get('mail_lostpass_text4').'</a><br /><br />';
			$msg .= $locale->get('mail_lostpass_text5').'<br>';
			$msg .= $_SESSION['site_sitehttp'].'/index.php?p=account&act=account_lstact&uname='.$name.'&gc='.$activate.'<br /><br />';
			$msg .= $locale->get('mail_lostpass_text6').'<br /><br />';
			$msg .= $locale->get('mail_lostpass_text7').'<br /><a href="'.$_SESSION['site_sitehttp'].'" title="'.$_SESSION['site_sitename'].'">'.$_SESSION['site_sitename'].'</a>';

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
			$mail->send($email, $hdrs, $body);

			//"fagyasztjuk" a form-ot
			$form_account->freeze();

			header('Location: index.php?success=account_lst&link=');
			exit;
		}

		$form_account->addElement('submit', 'submit', $locale->get('button_submit'), array('class' => 'submit'));
		$form_account->addElement('reset',  'reset',  $locale->get('button_reset'),  array('class' => 'reset'));

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

		$form_account->accept($renderer);
		$tpl->assign('form_account', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az index.php-nek
		$acttpl = 'account_lost';
	} //elfelejtett jelszo vege

	/**
	 * elfelejtett jelszo aktivalasa
	 */
	if ($act == "account_lstact") {
		$name = $_GET['uname'];
		$code = $_GET['gc'];

		$query = "
			SELECT user_id, lost_password 
			FROM iShark_Users 
			WHERE name = '".$name."' AND activate = '$code'
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow())
			{
				$newpass = $row['lost_password'];
			}
			$query = "
				UPDATE iShark_Users 
				SET activate      = '', 
					password      = '$newpass', 
					lost_password = '' 
				WHERE name = '".$name."' AND activate = '$code'
			";
			$result = $mdb2->query($query);
		} else {
			header('Location: index.php');
			exit;
		}
		header('Location: index.php?success=account_lstact&link=');
		exit;
	}

	/**
	 * elso bejelentkezes torlese
	 */
	if ($act == "account_del") {
		$name = $_GET['uname'];
		$code = $_GET['gc'];

		$query = "
			SELECT user_id 
			FROM iShark_Users 
			WHERE name = '".$name."' AND activate = '$code' AND is_active = '0'
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			$query = "
				UPDATE iShark_Users 
				SET activate  = '', 
					is_active = '0', 
					password  = '' 
				WHERE name = '".$name."' AND activate = '$code'
			";
			$result = $mdb2->query($query);
		} else {
			header('Location: index.php');
			exit;
		}
		header('Location: index.php?success=account_del&link=');
		exit;
	} //torles vege

	/**
	 * regisztracio aktivalas
	 */
	if ($act == "account_act") {
		$name = $_GET['uname'];
		$code = $_GET['gc'];

		$query = "
			SELECT user_id 
			FROM iShark_Users 
			WHERE name = '".$name."' AND activate = '$code' AND is_active = '0'
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();
			$user_id = $row['user_id'];

			$query = "
				UPDATE iShark_Users 
				SET activate  = '', 
					is_active = '1' 
				WHERE name = '".$name."' AND activate = '$code'
			";
			$mdb2->exec($query);
		} else {
			header('Location: index.php');
			exit;
		}

		//ha van hirlevel modul
		if (isModule('newsletter') === true) {
			$query = "
				SELECT * 
				FROM iShark_Newsletter_Users 
				WHERE name = '".$name."' AND activate = '$code'
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$query2 = "
					UPDATE iShark_Newsletter_Users 
					SET is_active = 1,
						activate  = ''
					WHERE name = '".$name."' AND activate = '$code'
				";
				$mdb2->exec($query2);
			}
		}

		//ha van sid parameter, akkor a shop-bol jon az aktivalas
		if (isset($_GET['sid']) && !empty($_GET['sid']) && isset($user_id) && is_numeric($user_id)) {
			$query = "
				UPDATE iShark_Shop_Basket 
				SET user_id = '$user_id' 
				WHERE session_id = '".$_GET['sid']."'
			";
			$mdb2->exec($query);
		}
		header('Location: index.php?success=account_act&link=');
		exit;
	} //aktivalas vege

	/**
	 * bejelentkezes
	 */
	if ($act == "account_in") {
		$name = $_REQUEST['login_email'];
		$pass = md5($_REQUEST['login_pass']);

		$query = "
			SELECT u.user_id AS uid, u.name AS uname, email, u.user_name AS realname, u.activate AS uact, u.lost_password AS lpass
			FROM iShark_Users u 
			WHERE u.email = '".$name."' AND u.password = '$pass' AND u.is_active = 1 AND u.is_deleted = 0
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow())
			{
				$_SESSION['user_id']       = $row['uid'];
                $_SESSION['usermail']      = $row['email'];
				$_SESSION['username']      = $row['uname'];
				$_SESSION['realname']      = $row['realname'];
				$_SESSION['user_realname'] = $row['realname'];

				//ha nem ures az activate es a lost_password mezo, akkor toroljuk a tartalmukat
				if ($row['uact'] != "" || $row['lpass'] != "") {
					$query = "
						UPDATE iShark_Users 
						SET activate      = '', 
							lost_password = '' 
						WHERE user_id = '".$_SESSION['user_id']."'
					";
					$result = $mdb2->query($query);
				}

				//lekerdezzuk a user csoportjait
				$query = "
					SELECT group_id 
					FROM iShark_Groups_Users 
					WHERE user_id = '".$_SESSION['user_id']."'
				";
				$result = $mdb2->query($query);
				$groups = "";
				while ($row = $result->fetchRow())
				{
					$groups .= $row['group_id']." ";
				}
				$_SESSION['user_groups'] = trim($groups);

				//ha van shop modul, es a felhasznalok vasarolhatnak
				if (isModule('shop', 'index') === true && isset($_SESSION['site_shop_userbuy']) && $_SESSION['site_shop_userbuy'] == 1 && isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
					//lekerdezzuk, hogy a kosarban van-e a userhez tartozo termek
					$query = "
						SELECT basket_id 
						FROM iShark_Shop_Basket 
						WHERE session_id = '".session_id()."'
					";
					$result =& $mdb2->query($query);
					if ($result->numRows() > 0) {
						while ($row = $result->fetchRow())
						{
							$basket_id = $row['basket_id'];

							$query2 = "
								UPDATE iShark_Shop_Basket 
								SET user_id = '".$_SESSION['user_id']."' 
								WHERE basket_id = '$basket_id'
							";
							$mdb2->exec($query2);
						}
					}
				}
			}
		} else {
			logger($act, '', $name.": ".$locale->get('error_wrong_pass_log'));

			$site_errors[] = array('text' => $locale->get('error_wrong_pass'), 'link' => 'javascript:history.back(-1)');
			return;
		}

		//ha van shop valtozo, akkor a kosarhoz dobjuk vissza
		if (isset($_REQUEST['shop']) && $_REQUEST['shop'] == 1) {
			header('Location: index.php?p=shop&act=bsk');
			exit;
		} else {
			header('Location: index.php?p='.$_REQUEST["prevpage"]);
			exit;
		}
	} //bejelentkezes vege

	/**
	 * kilepes
	 */
	if ($act == 'account_out') {
		unset($_SESSION['user_id']);
		unset($_SESSION['user_groups']);
		unset($_SESSION['lastvisit']);

		header('Location: index.php?p=game');
		exit;
	} //kilepes vege

    if ($act == 'account_cal') {
        $acttpl = "account_cal";
        include("libs/phpcalendar/calendar.inc.php");

        $month = date("m");
        $year = date("Y");

        if (!empty($_REQUEST["month"])) $month = $_REQUEST["month"];
        if (!empty($_REQUEST["year"])) $year = $_REQUEST["year"];
		
		$prevyear = $year;
		$nextyear = $year;
		$prevmonth = $month-1;
		$nextmonth = $month+1;
		if ($prevmonth < 1) {
			$prevmonth = 12;
			$prevyear = $year-1;
		}
		if ($nextmonth > 12) {
			$nextmonth = 1;
			$nextyear = $year+1;
		}
		
		$prevlink_year = $prevyear;
		$prevlink_month = $prevmonth;
		$nextlink_year = $nextyear;
		$nextlink_month = $nextmonth;
		
        $cal_prev = new CALENDAR($prevyear, $prevmonth);
        $cal_prev->offset = 2;
        $cal_prev->tFontSize = 12;
        $cal_prev->hFontSize = 10;
        $cal_prev->dFontSize = 10;
        $cal_prev->wFontSize = 10;
		$cal_prev->weekNumbers = false;
		
		$cal = new CALENDAR($year, $month);
        $cal->offset = 2;
        $cal->tFontSize = 12;
        $cal->hFontSize = 10;
        $cal->dFontSize = 10;
        $cal->wFontSize = 10;
		$cal->weekNumbers = false;
		
		$cal_next = new CALENDAR($nextyear, $nextmonth);
        $cal_next->offset = 2;
        $cal_next->tFontSize = 12;
        $cal_next->hFontSize = 10;
        $cal_next->dFontSize = 10;
        $cal_next->wFontSize = 10;
		$cal_next->weekNumbers = false;
        
        $res = $mdb2->query("
            SELECT ud.datum, n.title, d.name, u.cegnev
            FROM iShark_Users_Add2 AS ud
			LEFT JOIN iShark_Users AS u ON u.user_id = ud.user_id
            WHERE ud.user_id = '".$_SESSION["user_id"]."'
        ");
		
		$textlist = "";
		$cegnev = "";
		
        foreach ($res->fetchAll() as $value) {
			$cegnev = $value["cegnev"];
            $dateExp = explode("-", $value["datum"]);
            if ($dateExp[1] == $prevmonth && $dateExp[0] == $prevyear) {
                $cal_prev->viewEvent($dateExp[2], $dateExp[2], "#A0B0C0", $value["title"]." - ".$value["name"]);
            }
			if ($dateExp[1] == $month && $dateExp[0] == $year) {
                $cal->viewEvent($dateExp[2], $dateExp[2], "#A0B0C0", $value["title"]." - ".$value["name"]);
            }
			if ($dateExp[1] == $nextmonth && $dateExp[0] == $nextyear) {
                $cal_next->viewEvent($dateExp[2], $dateExp[2], "#A0B0C0", $value["title"]." - ".$value["name"]);
            }
			$textlist .= $value["datum"].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value["title"].' - '.$value["name"].'<br />';
        }

        $tpl->assign("cal_prev", $cal_prev->create());
		$tpl->assign("cal", $cal->create());
		$tpl->assign("cal_next", $cal_next->create());
		
		$tpl->assign("prevlink_year", $prevlink_year);
		$tpl->assign("prevlink_month", $prevlink_month);
		$tpl->assign("nextlink_year", $nextlink_year);
		$tpl->assign("nextlink_month", $nextlink_month);
		
		$tpl->assign("cegnev", $cegnev);
		
		$tpl->assign("textlist", $textlist);
    }
} else {
	$site_errors[] = array('text' => $locale->get('error_not_act'), 'link' => 'javascript:history.back(-1)');
	return;
}

?>
