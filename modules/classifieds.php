<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "classifieds";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//css
$css[] = "classifieds";

//ezek az elfogadhato muveleti hivasok ($act)
$is_act = array('lst', 'add', 'mod', 'del', 'act');

//menu azonosito vizsgalata
$menu_id = 0;
if (isset($_GET['mid'])) {
	$menu_id    = intval($_GET['mid']);
	$self_class = "mid=".$menu_id;
} else {
	$self_class = "p=".$module_name;
}

//breadcrumb
if (!empty($_SESSION['site_class_is_breadcrumb'])) {
	$class_breadcrumb->add($locale->get('breadcrumb_classifieds'), 'index.php?'.$self_class);
}

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
	$site_error[] = array('text' => $locale->get('error_no_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

$tpl->assign('back',       NULL);
$tpl->assign('self_class', $self_class);

//aprohirdetes egyedi beallitasainak lekerdezese
$query_class = "
	SELECT * 
	FROM iShark_Classifieds_Configs
";
$result_class =& $mdb2->query($query_class);
if ($result_class->numRows() > 0) {
	$row_class = $result_class->fetchRow();

	$class_mail      = $row_class['class_mail'];
	$class_reguser   = $row_class['class_reguser'];
	$class_captcha   = $row_class['class_captcha'];
	$class_flood     = $row_class['class_flood'];
	$class_floodtime = $row_class['class_floodtime'];
} else {
	$site_error[] = array('text' => $locale->get('error_no_configtable'), 'link' => 'javascript:history.back(-1)');
	return;
}

/**
 * nem aktivalt hirdetesek torlese
 */
$query = "
	DELETE FROM iShark_Classifieds_Advert 
	WHERE is_active = 0 AND TO_DAYS(NOW()) - TO_DAYS(add_date) > ".$_SESSION['site_class_autodel']."
";
$mdb2->exec($query);

/**
 * uj hirdetes feladasa
 */
if ($act == "add" || $act == "mod") {
	$titles = array('add' => $locale->get('breadcrumb_add'), 'mod' => $locale->get('breadcrumb_mod'));

	//ha csak regisztralt felhasznalo adhat fel hirdetest
	if (!empty($class_reguser) && empty($_SESSION['user_id'])) {
	    $site_error[] = array('text' => $locale->get('error_no_permission'), 'link' => 'javascript:history.back(-1)');
		return;
	}
	//flood figyelese
	else if ($class_flood == 1 && checkFlood($module_name, $class_floodtime) === false) {
        $site_errors[] = array('text' => $locale->get('error_flooding'), 'link' => 'javascript:history.back(-1)');
        return;
	}
	else {
		if (isset($_REQUEST['aid']) && is_numeric($_REQUEST['aid'])) {
			$aid = intval($_REQUEST['aid']);
		}

		$javascripts[] = "javascripts";

		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/jscalendar.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		require_once $include_dir.'/function.check.php';
		require_once $include_dir.'/function.classifieds.php';

		$form_class =& new HTML_QuickForm('frm_class', 'post', 'index.php?'.$self_class);
		$form_class->removeAttribute('name');

		$form_class->setRequiredNote($locale->get('form_required_note'));

		$form_class->addElement('header', 'class', $locale->get('form_header'));
		$form_class->addElement('hidden', 'act',   $act);

		//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
		if (!empty($_SESSION['site_multilang'])) {
			include_once $include_dir.'/functions.php';
			$form->addElement('select', 'languages', $locale->get('field_main_languages'), $locale->getLocales());
		}

		//ha hasznaljuk a tipusokat kulon (eladas, vetel, csere)
		if (!empty($_SESSION['site_class_autocategory'])) {
			$autocat = array();
			$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('field_sell'), '0');
			$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('field_buy'),  '1');
			$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('field_swap'), '2');
			$form_class->addGroup($autocat, 'class_autocat', $locale->get('field_main_autocat'));

			//ha hozzaadas, akkor beallitjuk az alapertelmezettet
			if ($act == "add") {
				$form_class->setDefaults(array(
					'class_autocat' => 0
					)
				);
			}
		}

		//kategoriak listaja
		$category = array();
		$cats = explode(";", get_classifieds_category());
		foreach ($cats as $key => $value) {
			$cats2[$key] = explode(",", $value);
		}
		if (is_array($cats2) && count($cats2) > 0) {
			foreach ($cats2 as $key2 => $value2) {
				if (!empty($value2[1])) {
					$category[$value2[0]] = trim($value2[1]);
				}
			}
		}
		//ha ures a kategoriak listaja, akkor hiba
		if (empty($category)) {
		    $site_errors[] = array('text' => $locale->get('error_no_category'), 'link' => 'javascript:history.back(-1)');
		    return;
		}
		$form_class->addElement('select', 'class_category', $locale->get('field_main_category'), $category);

		//korzetek listaja
		$query = "
			SELECT county_id, name 
			FROM iShark_Classifieds_Counties 
			ORDER BY name
		";
		$result =& $mdb2->query($query);
		$select =& $form_class->addElement('select', 'class_section', $locale->get('field_main_section'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);

		//felado neve
		$form_class->addElement('text', 'class_name', $locale->get('field_main_name'));

		//felado telefonszama
		$form_class->addElement('text', 'class_phone', $locale->get('field_main_phone'));

		//felado e-mail cime
		$form_class->addElement('text', 'class_mail', $locale->get('field_main_mail'));

		//idozito
		$form_class->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
		        HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
				HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_end\')"')
			),
			'date_end', $locale->get('field_main_timerend'), null, false
		);

		//hirdetes szovege
		$form_class->addElement('textarea', 'class_desc', $locale->get('field_main_description'));

		//ar
		$form_class->addElement('text', 'class_price', $locale->get('field_main_price'));

		//kepek a hirdeteshez
		if (!empty($_SESSION['site_class_is_advpic'])) {
			for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
				${'file'.$p} =& $form_class->addElement('file', 'picture'.$p, $locale->get('field_main_picture'));
			}

			//modositas eseten jelenlegi kep kirajzolasa
			if ($act == 'mod' && isset($aid)) {
				//lekerdezzuk a jelenlegi kep(ek)et
				$query = "
					SELECT picture 
					FROM iShark_Classifieds_Advert_Pictures 
					WHERE advert_id = $aid 
				";
				$mdb2->setLimit($_SESSION['site_class_advpicnum']);
				$result =& $mdb2->query($query);
				//megnezzuk jelenleg hany kep van feltoltve a termekhez
				$pic_num = $result->numRows();
				if ($pic_num > 0) {
					$q = 1;
					while ($row = $result->fetchRow())
					{
						${'oldpic'.$q} = $row['picture'];

						$form_class->addElement('static', 'pic'.$q, $locale->get('field_main_currentpic'), '<img src="'.$_SESSION['site_class_advpicdir'].'/'.${'oldpic'.$q}.'" alt="'.${'oldpic'.$q}.'" />' );
						${'delpic'.$q} =& $form_class->addElement('checkbox', 'delpic'.$q, '', $locale->get('field_main_delpic'));
						$q++;
					}
				}
			}
		}

		//ha be van jelentkezve a user, akkor kitoltunk par mezot
		if (!empty($_SESSION['user_id'])) {
			$query = "
				SELECT u.email AS umail, u.user_name AS uname 
				FROM ".DB_USERS.".iShark_Users u 
				WHERE user_id = ".$_SESSION['user_id']."
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
					$form_class->setDefaults(array(
						'class_mail' => $row['umail'],
						'class_name' => $row['uname']
						)
					);
					//csak olvashatova tesszuk a nev es e-mail mezoket
					$form_class->updateElementAttr('class_mail', 'readonly');
					$form_class->updateElementAttr('class_name', 'readonly');
				}
			}
		}

		//form gombok
		$form_class->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
		$form_class->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

		//szurok beallitasa
		$form_class->applyFilter('__ALL__', 'trim');

		if (!empty($_SESSION['site_class_autocategory'])) {
			$form_class->addRule('class_autocat', $locale->get('error_main_autocat'), 'required');
		}
		$form_class->addRule(     'class_category', $locale->get('error_main_category'), 'required');
		$form_class->addGroupRule('class_section',  $locale->get('error_main_section'),  'required');
		$form_class->addRule(     'class_name',     $locale->get('error_main_name'),     'required');
		$form_class->addRule(     'class_phone',    $locale->get('error_main_phone'),    'required');
		$form_class->addRule(     'class_mail',     $locale->get('error_main_email'),    'required');
		$form_class->addGroupRule('date_end', array(
			'timer_end' => array(
				array($locale->get('error_main_timerend'), 'required')
				)
			)
		);
		if (isset($_POST['timer_end']) && $_POST['timer_end'] < date("Y-m-d H:i:s")) {
			$form_class->setElementError('date_end', $locale->get('error_main_timerend2'));
		}
		$form_class->addRule('class_desc',  $locale->get('error_main_desc'),   'required');
		$form_class->addRule('class_price', $locale->get('error_main_price'),  'required');
		$form_class->addRule('class_price', $locale->get('error_main_price2'), 'numeric');

		if ($act == "add") {
			//ha kell captcha, akkor kirakjuk
			if (!empty($class_captcha)) {
			$form_class->addElement('text', 'recaptcha', $locale->get('field_main_captcha'), 'class="input_box"');
				$form_class->addRule('recaptcha', $locale->get('error_captcha'), 'required');
				if ($form_class->isSubmitted() && $form_class->getSubmitValue('recaptcha') != $_SESSION['class_phrase']) {
					$form_class->setElementError('recaptcha', $locale->get('error_compare_captcha'));
				}

				include_once $include_dir.'/function.captcha.php';
                create_captcha('class', 'class_phrase', 'class_');
                $tpl->assign('class_captcha', 'files/class_'.md5(session_id()).'.png');
			}

			if ($form_class->validate()) {
				//levelkuldesbe kulon kell belerakni a leirast
				$mail_desc = strip_tags($form_class->getSubmitValue('class_desc'));

				$form_class->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				//kep(ek) feltoltese
				if (!empty($_SESSION['site_class_is_advpic'])) {
					for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
						if (${'file'.$p}->isUploadedFile()) {
							$filevalues      = ${'file'.$p}->getValue();
							$sdir            = preg_replace('|/$|','', $_SESSION['site_class_advpicdir']).'/';
							${'filename'.$p} = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
							$tn_name         = 'tn_'.${'filename'.$p};

							//kep atmeretezese
							include_once 'includes/function.images.php';
							if (($pic = img_resize($filevalues['tmp_name'], $sdir.${'filename'.$p}, $_SESSION['site_class_advpicwidth'], $_SESSION['site_class_advpicheight'])) && ($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_class_advpictwidth'], $_SESSION['site_class_advpictheight']))) {
								//beallitjuk a jogosultsagot
								@chmod($sdir.${'filename'.$p}, 0664);
								@chmod($sdir.$tn_name, 0664);

								@unlink($filevalues['tmp_name']);
							} else {
								$form_class->setElementError('picture'.$p, $locale->get('error_main_picupload'));
							}
						}
					}
				}

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (!empty($_SESSION['site_multilang'])) {
					$languages = $form_class->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				if (!empty($_SESSION['site_class_autocategory'])) {
					$autocat = intval($form_class->getSubmitValue('class_autocat'));
					//levelkuldeshez
					if ($autocat == 0) {
						$mail_autocat = $locale->get('field_sell');
					}
					else if ($autocat == 1) {
						$mail_autocat = $locale->get('field_buy');
					}
					else {
						$mail_autocat = $locale->get('field_swap');
					}
				} else {
					$autocat = 9999;
				}

				$category  = intval($form_class->getSubmitValue('class_category'));
				$name      = $form_class->getSubmitValue('class_name');
				$phone     = $form_class->getSubmitValue('class_phone');
				$usermail  = $form_class->getSubmitValue('class_mail');
				$timer_end = $form_class->getSubmitValue('timer_end');
				$desc      = strip_tags($form_class->getSubmitValue('class_desc'));
				$price     = intval($form_class->getSubmitValue('class_price'));

				//aktivalo kod
				require_once "Text/Password.php";
				$activate = Text_Password::create(8, 'unpronounceable', 'alphanumeric');

				$advert_id = $mdb2->extended->getBeforeID('iShark_Classifieds_Advert', 'advert_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Classifieds_Advert 
					(advert_id, name, phone, email, description, category_id, type, add_user_id, add_date, mod_user_id, mod_date, 
						is_active, gen_code, timer_end, lang, price, is_finished_mail) 
					VALUES 
					($advert_id, '".$name."', '".$phone."', '".$usermail."', '".$desc."', $category, $autocat, ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW(), 
						'0', '".$activate."', '".$timer_end."', '".$languages."', '$price', '0')
				";
				$mdb2->exec($query);
				$last_advert_id = $mdb2->extended->getAfterID($advert_id, 'iShark_Classifieds_Advert', 'advert_id');

				//korzetek felvitele
				$section = $form_class->getSubmitValue('class_section');
				if (is_array($section) && count($section) > 0) {
					foreach ($section as $key => $value) {
						$query = "
							INSERT INTO iShark_Classifieds_Advert_Counties 
							(advert_id, county_id) 
							VALUES 
							($last_advert_id, $value)
						";
						$mdb2->exec($query);
					}
				}

				//kepek felvitele
				if (!empty($_SESSION['site_class_is_advpic'])) {
					for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
						if (isset(${'filename'.$p})) {
							$query = "
								INSERT INTO iShark_Classifieds_Advert_Pictures 
								(advert_id, picture) 
								VALUES 
								($last_advert_id, '".${'filename'.$p}."')
							";
							$mdb2->exec($query);
						}
					}
				}

				if (!empty($class_captcha)) {
					@unlink('files/class_'.md5(session_id()).'.png');
				}

				//kikuldjuk az aktivalo e-mailt a hirdetonek
				$msg =  $locale->get('mail_header').' '.$_SESSION['username'].'!<br />';
				$msg .= $locale->getBySmarty('mail_activate_text1');
				$msg .= '<table style="width: 100%;">';
				$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->getBySmarty('mail_header2').'</th></tr>';
				if (!empty($_SESSION['site_class_autocategory'])) {
					$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_autocat').'</strong></td><td>'.$mail_autocat.'</td></tr>';
				}
				$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_name').'</strong></td><td>'.$name.'</td></tr>';
				$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_phone').'</strong></td><td>'.$phone.'</td></tr>';
				$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_mail').'</strong></td><td>'.$usermail.'</td></tr>';
				$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_timerend').'</strong></td><td>'.$timer_end.'</td></tr>';
				$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_price').'</strong></td><td>'.$price.'</td></tr>';
				$msg .= '<tr><td valign="top"><strong>'.$locale->getBySmarty('field_main_description').'</strong></td><td>'.nl2br($mail_desc).'</td></tr>';
				$msg .= '</table><br />';
				$msg .= $locale->get('mail_activate_text2')." <a href=".$_SESSION['site_sitehttp']."/index.php?".$self_class."&amp;act=act&amp;aid=".$last_advert_id."&amp;act_code=".$activate." title=".$locale->get('mail_activate_activate').">".$locale->get('mail_activate_activate')."</a><br />";
				$msg .= $locale->getBySmarty('mail_activate_text5');

				if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
					$tpl->assign('mail_body', $msg);
					$msg = $tpl->fetch('mail/mail_html.tpl');
				}

				include_once $include_dir.'/function.mail.php';
	            send_mime_mail($usermail, $locale->get('mail_subject_activate'), html_entity_decode(strip_tags($msg)), $msg, '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$class_mail.'>');

    			//ha van flood figyeles, akkor beallitjuk a cookie-t
				if ($class_flood == 1) {
				    addFlood($module_name);
				}

				//loggolas
				logger($act);

				//"fagyasztjuk" a form-ot
				$form_class->freeze();

				header('Location: index.php?'.$self_class);
				exit;
			}
		}

		if ($act == "mod") {
			if (isset($_REQUEST['aid']) && is_numeric($_REQUEST['aid'])) {
				$aid = intval($_REQUEST['aid']);

				//form-hoz elemek hozzaadasa - csak modositasnal
				$form_class->addElement('hidden', 'act', $act);
				$form_class->addElement('hidden', 'aid', $aid);

				//berakjuk az azonosito kod mezot
				$form_class->addElement('text', 'act_code', $locale->get('field_activate_code'));

				//beallitjuk az alapertelmezett ertekeket
				$query = "
					SELECT a.category_id AS category_id, a.name AS name, a.phone AS phone, a.email AS email, 
						a.description AS description, a.timer_end AS timer_end, a.lang AS lang, a.type AS type, 
						a.price AS price
					FROM iShark_Classifieds_Advert a 
					WHERE a.is_active = '1' AND a.advert_id = ".$aid."
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
					$row = $result->fetchRow();

					//lekerdezzuk a teruleteket is
					$query2 = "
						SELECT c.county_id AS county_id
						FROM iShark_Classifieds_Counties c, iShark_Classifieds_Advert_Counties ac 
						WHERE ac.advert_id = ".$aid." AND ac.county_id = c.county_id
					";
					$result2 =& $mdb2->query($query2);
					$counties = array();
					if ($result2->numRows() > 0) {
						while ($row2 = $result2->fetchRow())
						{
							$counties[$row2['county_id']] = $row2['county_id'];
						}
					}

					$defaults = array();

					//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
					if (!empty($_SESSION['site_multilang'])) {
						$defaults['languages'] = $row['lang'];
					}

					//ha hasznaljuk a tipusokat kulon (eladas, vetel, csere)
					if (!empty($_SESSION['site_class_autocategory'])) {
						$defaults['class_autocat'] = $row['type'];
					}

					$defaults['class_category'] = $row['category_id'];
					$defaults['class_name']     = $row['name'];
					$defaults['class_phone']    = $row['phone'];
					$defaults['class_mail']     = $row['email'];
					$defaults['class_desc']     = $row['description'];
					$defaults['timer_end']      = $row['timer_end'];
					$defaults['class_section']  = $counties;
					$defaults['class_price']    = $row['price'];

					$form_class->setDefaults($defaults);

					//ha kepet is lehet feltolteni
					if (!empty($_SESSION['site_class_is_advpic'])) {
						//megszamoljuk hany kepet akarnak most feltolteni
						$newpic_num = 0;
						for ($s = 1; $s <= $_SESSION['site_class_advpicnum']; $s++) {
							if (${'file'.$s}->isUploadedFile()) {
								$newpic_num++;
							}
						}

						//megszamoljuk hany kepet akarnak kitorolni
						$delpic_num = 0;
						for ($q = 1; $q <= $_SESSION['site_class_advpicnum']; $q++) {
							if (isset(${'delpic'.$q}) && ${'delpic'.$q}->getChecked()) {
								$delpic_num++;
							}
						}

						//ha a regi kepek szama + az uj kepek szama - torlesre jelolet kepek szama 
						//meghaladja a feltoltheto kepek szamat, akkor hibauzenet
						if ($pic_num+$newpic_num-$delpic_num > $_SESSION['site_class_advpicnum']) {
							$form_class->setElementError('picture1', $locale->getBySmarty('error_main_maxpicupload'));
						}
					}

					$form_class->addRule('act_code', $locale->get('error_actcode'), 'required');
					$form_class->addFormRule('check_activatecode');

					//form validalas
					if ($form_class->validate()) {
						$form_class->applyFilter('__ALL__', array(&$mdb2, 'escape'));

						$aid = intval($form_class->getSubmitValue('aid'));

						//ha tolthet fel kepet
						if (!empty($_SESSION['site_class_is_advpic'])) {
							//ha ki akarjuk torolni a regi kepet - de semmi mast nem csinalunk
							for ($q = 1; $q <= $_SESSION['site_class_advpicnum']; $q++) {
								if (isset(${'delpic'.$q}) && ${'delpic'.$q}->getChecked()) {
									if (file_exists($_SESSION['site_class_advpicdir'].'/'.${'oldpic'.$q})) {
										@unlink($_SESSION['site_class_advpicdir'].'/'.${'oldpic'.$q});
										@unlink($_SESSION['site_class_advpicdir'].'/tn_'.${'oldpic'.$q});
									}

									//kitoroljuk a tablabol a torlendo bejegyzeseket
									$query = "
										DELETE FROM iShark_Classifieds_Advert_Pictures 
										WHERE advert_id = $aid AND picture = '".${'oldpic'.$q}."'
									";
									$mdb2->exec($query);
								}
							}

							//kep(ek) feltoltese
							for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
								if (${'file'.$p}->isUploadedFile()) {
									$filevalues = ${'file'.$p}->getValue();
									$sdir = preg_replace('|/$|','', $_SESSION['site_class_advpicdir']).'/';
									${'filename'.$p} = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
									$tn_name = 'tn_'.${'filename'.$p};

									//kep atmeretezese
									include_once 'includes/function.images.php';
									if (($pic = img_resize($filevalues['tmp_name'], $sdir.${'filename'.$p}, $_SESSION['site_class_advpicwidth'], $_SESSION['site_class_advpicheight'])) && ($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_class_advpictwidth'], $_SESSION['site_class_advpictheight']))) {
										@chmod($sdir.${'filename'.$p},0664);
										@chmod($sdir.$tn_name,0664);

										@unlink($filevalues['tmp_name']);

										//beszurjuk az uj file-okat a tablaba
										$query = "
											INSERT INTO iShark_Classifieds_Advert_Pictures 
											(advert_id, picture) 
											VALUES 
											($aid, '".${'filename'.$p}."')
										";
										$mdb2->exec($query);
									} else {
										$form_class->setElementError('picture'.$p, $locale->get('error_main_picupload'));
									}
								}
							}
						}

						//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
						if (!empty($_SESSION['site_multilang'])) {
							$languages = $form_class->getSubmitValue('languages');
						} else {
							$languages = $_SESSION['site_deflang'];
						}

						if (!empty($_SESSION['site_class_autocategory'])) {
							$autocat = intval($form_class->getSubmitValue('class_autocat'));
						} else {
							$autocat = 9999;
						}

						$category  = intval($form_class->getSubmitValue('class_category'));
						$name      = $form_class->getSubmitValue('class_name');
						$phone     = $form_class->getSubmitValue('class_phone');
						$usermail  = $form_class->getSubmitValue('class_mail');
						$timer_end = $form_class->getSubmitValue('timer_end');
						$desc      = strip_tags($form_class->getSubmitValue('class_desc'));
						$price     = intval($form_class->getSubmitValue('class_price'));

						$query = "
							UPDATE iShark_Classifieds_Advert
							SET name        = '".$name."',
								phone       = '".$phone."',
								email       = '".$usermail."',
								description = '".$desc."',
								category_id = $category,
								type        = $autocat,
								mod_user_id = ".$_SESSION['user_id'].",
								mod_date    = NOW(),
								timer_end   = '".$timer_end."',
								price       = '".$price."'
							WHERE advert_id = $aid
						";
						$mdb2->exec($query);

						//korzetek felvitele
						$query = "
							DELETE FROM iShark_Classifieds_Advert_Counties 
							WHERE advert_id = $aid
						";
						$mdb2->exec($query);

						$section = $form_class->getSubmitValue('class_section');
						if (is_array($section) && count($section) > 0) {
							foreach ($section as $key => $value) {
								$query = "
									INSERT INTO iShark_Classifieds_Advert_Counties 
									(advert_id, county_id) 
									VALUES 
									($aid, $value)
								";
								$mdb2->exec($query);
							}
						}

						//kepek felvitele
						$query = "
							DELETE FROM iShark_Classifieds_Advert_Pictures 
							WHERE advert_id = $aid
						";
						$mdb2->exec($query);

						if (!empty($_SESSION['site_class_is_advpic'])) {
							for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
								if (isset(${'filename'.$p})) {
									$query = "
										INSERT INTO iShark_Classifieds_Advert_Pictures 
										(advert_id, picture) 
										VALUES 
										($aid, '".${'filename'.$p}."')
									";
									$mdb2->exec($query);
								}
							}
						}

						//loggolas
						logger($act);

						//"fagyasztjuk" a form-ot
						$form_class->freeze();

						header('Location: index.php?'.$self_class);
						exit;
					}
				} else {
					$site_error[] = array('text' => $locale->get('error_no_activate'), 'link' => 'javascript:history.back(-1)');
					return;
				}
			} else {
				$site_error[] = array('text' => $locale->get('error_no_activate'), 'link' => 'javascript:history.back(-1)');
				return;
			}
		}

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_class->accept($renderer);

		//breadcrumb, ha hasznaljuk
		if (!empty($_SESSION['site_class_is_breadcrumb'])) {
			if ($act == "add") {
				$class_breadcrumb->add($titles[$act], 'index.php?'.$self_class.'&amp;act='.$act);
			}
			if ($act == "mod") {
				$class_breadcrumb->add($titles[$act], 'index.php?'.$self_class.'&amp;act='.$act.'&amp;aid='.$aid);
			}
		}

		$tpl->assign('form_class', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az index.php-nek
		$acttpl = 'classifieds';
	}
}

/**
 * hirdetes aktivalasa
 */
if ($act == "act" && isset($_GET['aid']) && is_numeric($_GET['aid']) && !empty($_GET['act_code'])) {
	$aid      = intval($_GET['aid']);
	$act_code = $_GET['act_code'];

	$query = "
		SELECT * 
		FROM iShark_Classifieds_Advert 
		WHERE advert_id = ".$aid." AND is_active = 0 AND gen_code = '".$act_code."'
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();

		$query2 = "
			UPDATE iShark_Classifieds_Advert 
			SET is_active = '1' 
			WHERE advert_id = $aid AND gen_code = '".$act_code."'
		";
		$mdb2->exec($query2);

		//kikuldjuk a levelet, amellyel majd modositani, torolni tudja az aprohirdetest
		$msg =  $locale->get('mail_header').' '.$_SESSION['username'].'!<br /><br />';
		$msg .= $locale->getBySmarty('mail_activate_text6').'<br />';
		$msg .= '<font color="red">'.$locale->getBySmarty('mail_activate_text7').'</font><br />';
		//hirdetes azonositoi
		$msg .= '<table style="width: 100%;">';
		$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->getBySmarty('mail_header3').'</th></tr>';
		$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('mail_activate_id').'</strong></td><td>'.$aid.'</td></tr>';
		$msg .= '<tr><td><strong>'.$locale->getBySmarty('mail_activate_code').'</strong></td><td>'.$act_code.'</td></tr>';
		$msg .= '</table><br />';
		//hirdetes tartalma
		$msg .= '<table style="width: 100%;">';
		$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->getBySmarty('mail_header2').'</th></tr>';
		$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_name').'</strong></td><td>'.$row['name'].'</td></tr>';
		$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_phone').'</strong></td><td>'.$row['phone'].'</td></tr>';
		$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_mail').'</strong></td><td>'.$row['email'].'</td></tr>';
		$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_timerend').'</strong></td><td>'.$row['timer_end'].'</td></tr>';
		$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_price').'</strong></td><td>'.$row['price'].'</td></tr>';
		$msg .= '<tr><td valign="top"><strong>'.$locale->getBySmarty('field_main_description').'</strong></td><td>'.nl2br($row['description']).'</td></tr>';
		$msg .= '</table><br />';
		//adminisztracios linkek
		$msg .= '<table style="width: 100%;">';
		$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->getBySmarty('mail_header3').'</th></tr>';
		$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->get('mail_activate_text4').'</strong></td><td><a href='.$_SESSION['site_sitehttp'].'/index.php?'.$self_class.'&amp;act=del&amp;aid='.$aid.'&amp;act_code='.$act_code.' title='.$locale->get('mail_activate_delete').'>'.$locale->get('mail_activate_delete').'</a></td></tr>';
		$msg .= '<tr><td><strong>'.$locale->get('mail_activate_text3').'</strong></td><td><a href='.$_SESSION['site_sitehttp'].'/index.php?'.$self_class.'&amp;act=mod&amp;aid='.$aid.' title='.$locale->get('mail_activate_modify').'>'.$locale->get('mail_activate_modify').'</a></td></tr>';
		$msg .= '</table><br />';
		$msg .= $locale->getBySmarty('mail_activate_text5');

		if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
			$tpl->assign('mail_body', $msg);
			$msg = $tpl->fetch('mail/mail_html.tpl');
		}

		include_once $include_dir.'/function.mail.php';
	    send_mime_mail($row['email'], $locale->get('mail_subject_modify'), html_entity_decode(strip_tags($msg)), $msg, '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$class_mail.'>');

		header('Location: index.php?success=classifieds_act&link=index.php%3F'.$self_class);
		exit;
	} else {
		$site_error[] = array('text' => $locale->get('error_no_activate'), 'link' => 'javascript:history.back(-1)');
		return;
	}
}

/**
 * hirdetes torlese
 */
if ($act == "del" && isset($_GET['aid']) && is_numeric($_GET['aid']) && !empty($_GET['act_code'])) {
	$aid      = intval($_GET['aid']);
	$act_code = $_GET['act_code'];

	$query = "
		SELECT * 
		FROM iShark_Classifieds_Advert 
		WHERE advert_id = ".$aid." AND is_active = 1 AND gen_code = '".$act_code."'
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$query = "
			DELETE FROM iShark_Classifieds_Advert 
			WHERE advert_id = ".$aid." AND gen_code = '".$act_code."'
		";
		$mdb2->exec($query);

		//kitoroljuk a bejegyzest az aprohirdetes - terulet kapcsolotablabol is
		$query = "
			DELETE FROM iShark_Classifieds_Advert_Counties 
			WHERE advert_id = $aid
		";
		$mdb2->exec($query);

		//kapcsolodo kepek kitorlese
		$query = "
			SELECT picture 
			FROM iShark_Classifieds_Advert_Pictures 
			WHERE advert_id = $aid
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			@unlink($_SESSION['site_class_advpicdir'].'/'.$row['picture']);
		}

		$query = "
			DELETE FROM iShark_Classifieds_Advert_Pictures 
			WHERE advert_id = $aid
		";
		$mdb2->exec($query);

		header('Location: index.php?success=classifieds_del&code=index.php%3F'.$self_class);
		exit;
	} else {
		$site_error[] = array('text' => $locale->get('error_no_activate'), 'link' => 'javascript:history.back(-1)');
		return;
	}
}

/**
 * ha a hirdetesek listajat mutatjuk
 */
if ($act == "lst") {
	/**
	 * hirdetesfeladas link
	 *
	 * ha csak regisztralt felhasznalo adhat fel hirdetest, es a felhasznalo be is van lepve
	 * vagy ha nem csak regisztralt felhasznalo adhat fel hirdetest
	 */
	if ((!empty($class_reguser) && !empty($_SESSION['user_id'])) || empty($class_reguser)) {
		$tpl->assign('class_add_link', 'index.php?'.$self_class.'&amp;act=add');
	}

    //csoportok listaja, amiben benne van a user
    if (!empty($_SESSION['user_groups'])) {
        $ugroups   = str_replace(' ', ',', $_SESSION['user_groups']);
        $grp_where = " OR ag.group_id IN ('".$ugroups."')";
    } else {
        $ugroups   = NULL;
        $grp_where = "";
    }

	//kategoriak listaja
	$query = "
		SELECT c.category_id AS cid, c.category_name AS cname, c.category_desc AS cdesc, c.picture AS cpic 
		FROM iShark_Classifieds_Category c 
		LEFT JOIN iShark_Classifieds_Groups ag ON ag.category_id = c.category_id
		WHERE (ag.category_id IS NULL $grp_where) AND c.is_active = '1' AND 
			(c.timer_start = '0000-00-00 00:00:00' OR (c.timer_start < NOW() AND c.timer_end > NOW())) 
	";
	if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
		$cid = intval($_GET['cid']);

		$query .= "
			AND parent = $cid 
		";
	} else {
		$query .= "
			AND parent = 0 
		";
	}
	$query .= "
		ORDER BY is_preferred DESC, sortorder, parent, lang 
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$class_category = array();
		$i = 0;
		while ($row = $result->fetchRow()) {
			$class_category[$i]['cid']   = $row['cid'];
			$class_category[$i]['cname'] = $row['cname'];
			$class_category[$i]['cdesc'] = $row['cdesc'];
			$class_category[$i]['cpic']  = $row['cpic'];

			//kategoriakban talalhato hirdetesek szama
			$query_count = "
				SELECT COUNT(advert_id) AS aid 
				FROM iShark_Classifieds_Advert 
				WHERE category_id = ".$row['cid']." AND is_active = 1 AND timer_end > NOW()
			";
			$result_count =& $mdb2->query($query_count);
			if ($result_count->numRows() == 0) {
				$class_category[$i]['count'] = 0;
			} else {
				$row_count = $result_count->fetchRow();
				$class_category[$i]['count'] = $row_count['aid'];
			}

			$i++;
		}
		$tpl->assign('class_category', $class_category);
	}

	//hirdetesek listaja
	if (isset($cid)) {
	    //lekerdezzuk, hogy lathatja-e ezt a kategoriat
	    $query = "
			SELECT c.category_id AS cid, c.category_name AS cname, c.category_desc AS cdesc, c.picture AS cpic 
    		FROM iShark_Classifieds_Category c 
    		LEFT JOIN iShark_Classifieds_Groups ag ON ag.category_id = c.category_id
    		WHERE (ag.category_id IS NULL $grp_where) AND c.is_active = '1' AND c.category_id = $cid AND 
    			(c.timer_start = '0000-00-00 00:00:00' OR (c.timer_start < NOW() AND c.timer_end > NOW()))
		";
	    $result =& $mdb2->query($query);
	    if ($result->numRows() > 0) {
    		//breadcrumb
    		include_once $include_dir.'/function.classifieds.php';

    		$category = get_classifieds_breadcrumb_category($cid, $ugroups);
    		$cat1 = explode(";", $category);
    		$cat2 = array();
    		foreach ($cat1 as $key => $value) {
    			if (!empty($value)) {
    				$robbant = explode("#@#", $value);
    				$cat2[$robbant[0]] = $robbant[1];
    			}
    		}
    		$cat_array = array_reverse($cat2, true);
    		foreach ($cat_array as $key => $value) {
    			if (!empty($_SESSION['site_class_is_breadcrumb'])) {
    				$class_breadcrumb->add($value, 'index.php?'.$self_class.'&amp;act=lst&amp;cid='.$key);
    			}
    		}
    		$category_name = $value;

    		//ha hasznaljuk az autocategory funkciot (vetel-eladas-csere)
    		if (!empty($_SESSION['site_class_autocategory'])) {
    			$autocat = "
    				, (CASE a.type 
    					WHEN '0' THEN '".$locale->get('field_sell')."' 
    					WHEN '1' THEN '".$locale->get('field_buy')."' 
    					ELSE '".$locale->get('field_swap')."' 
    					END
    				) AS cattype
    			";
    			if (!empty($_REQUEST['type']) && ($_REQUEST['type'] == 0 || $_REQUEST['type'] == 1 || $_REQUEST['type'] == 2)) {
    				$type = intval($_REQUEST['type']);
    
    				$autocat_where = "
    					AND a.type = '".$type."'
    				";
    			} else {
    				$autocat_where = "
    					AND a.type = '0'
    				";
    			}

    			//autocat-ban talalhato hirdetesek szama
    			//eladas
    			$query_sell = "
    				SELECT a.advert_id AS aid 
    				FROM iShark_Classifieds_Advert a 
    				WHERE a.is_active = '1' AND a.category_id = $cid AND a.type = '0' AND timer_end > NOW() 
    			";
    			$result_sell =& $mdb2->query($query_sell);

    			//vetel
    			$query_buy = "
    				SELECT a.advert_id AS aid 
    				FROM iShark_Classifieds_Advert a 
    				WHERE a.is_active = '1' AND a.category_id = $cid AND a.type = '1' AND timer_end > NOW() 
    			";
    			$result_buy =& $mdb2->query($query_buy);

    			//vetel
    			$query_swap = "
    				SELECT a.advert_id AS aid 
    				FROM iShark_Classifieds_Advert a 
    				WHERE a.is_active = '1' AND a.category_id = $cid AND a.type = '2' AND timer_end > NOW() 
    			";
    			$result_swap =& $mdb2->query($query_swap);
    
    			$tpl->assign('autocat_sell', $result_sell->numRows());
    			$tpl->assign('autocat_buy',  $result_buy->numRows());
    			$tpl->assign('autocat_swap', $result_swap->numRows());
    		} else {
    			$autocat       = "";
    			$autocat_where = "";
    		}

    		//lekerdezzuk az aprohirdetesek adatait
    		$query = "
    			SELECT a.advert_id AS aid, a.add_date AS add_date, a.mod_date AS mod_date, a.phone AS phone, a.email AS email, 
    				a.timer_end AS timer_end, a.description AS cdesc, a.name AS classname, a.price AS price 
    				$autocat
    			FROM iShark_Classifieds_Advert a 
    			WHERE a.is_active = '1' AND a.category_id = $cid AND timer_end > NOW()
    				$autocat_where
    			ORDER BY a.add_date DESC
    		";

    		//lapozo
    		require_once 'Pager/Pager.php';
    		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    		//korzeteket berakjuk a tombbe
    		if (!empty($paged_data['data'])) {
    			foreach ($paged_data['data'] as $key => $adat) {
    				$counties = array();
    				$query = "
    					SELECT c.name AS county_name
    					FROM iShark_Classifieds_Counties c, iShark_Classifieds_Advert_Counties ac 
    					WHERE ac.advert_id = ".$adat['aid']." AND ac.county_id = c.county_id
    				";
    				$result =& $mdb2->query($query);
    				while ($row = $result->fetchRow())
    				{
    					$counties[] = $row['county_name'];
    				}
    				$adat['counties'] = $counties;
    				$data[] = $adat;
    			}
    		}

    		//kepeket berakjuk a tombbe
    		if (!empty($data)) {
    			foreach ($data as $key2 => $adat2) {
    				$pictures = array();
    				$query = "
    					SELECT picture 
    					FROM iShark_Classifieds_Advert_Pictures 
    					WHERE advert_id = ".$adat2['aid']."
    				";
    				$mdb2->setLimit($_SESSION['site_class_advpiclistnum']);
    				$result =& $mdb2->query($query);
    				while ($row = $result->fetchRow())
    				{
    					$pictures[] = $row['picture'];
    				}
    				$adat2['pictures'] = $pictures;
    				$datas[] = $adat2;
    			}
    		}

    		//atadjuk a smarty-nak a kiirando cuccokat
    		if (isset($datas)) {
    			$tpl->assign('page_data', $datas);
    		} else {
    			$tpl->assign('page_data', '');
    		}
    		$tpl->assign('page_list', $paged_data['links']);
    		$tpl->assign('cat_name',  $category_name);
    		$tpl->assign('cid',       $cid);
	    }
	}

	//megadjuk a tpl file nevet
	$acttpl = 'classifieds_list';
}

?>