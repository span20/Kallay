<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

/**
 * Hirlevel kuldes urlap
 */
if ($sub_act == 'send') {
    // szukseges fuggvenykonyvtarak betoltese
    require_once $include_dir.'/function.newsletter.php';

    require_once 'HTML/QuickForm.php';
    require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	// elinditjuk a form-ot
	$form =& new HTML_QuickForm('frm_newsletter', 'post', 'admin.php?p='.$module_name);

	// a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('letter_form_required_note'));

	$form->addElement('header', 'header_send', $locale->get('letter_form_header_send'));

	// Kuldendo hirlevel
	$nid = intval($_REQUEST['nid']);

	$form->addElement('hidden', 'nid',     $nid);
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);

	//kuldo
	$form->addElement('text', 'sender', $locale->get('letter_field_sendermail'));

	// Csoportok kivalasztasa
	$query = "
		SELECT newsletter_group_id, group_name
		FROM iShark_Newsletter_Groups
		WHERE is_deleted='0'
		ORDER BY group_name
	";
	$result = $mdb2->query($query);
	$csoportok_options = $result->fetchAll('', true);
	$csoportok_options[0] = $locale->get('letter_option_all_users');
	$csoportok =& $form->addElement('select', 'newsletter_groups', $locale->get('letter_field_newsletter_groups'), $csoportok_options);
	$csoportok->setMultiple(true);
	$csoportok->setSize(5);

	//teszt e-mail cim
	$form->addElement('text', 'testaddr', $locale->get('letter_field_testaddr'));

	//csak a teszt cimre kuldes
	$form->addElement('checkbox', 'is_testaddr', $locale->get('letter_field_is_testaddr'));

	// Szabalyok hozzaadasa
	if (!$form->getSubmitValue('is_testaddr')){
		$form->addGroupRule('newsletter_groups', $locale->get('letter_error_required_newsletter_groups'), 'required');
	}

	if ($form->getSubmitValue('is_testaddr') == '1'){
		$form->addRule('testaddr', $locale->get('letter_error_required_testaddr'), 'required');
	}
	$form->addRule('testaddr', $locale->get('letter_error_invalid_testaddr'), 'email');
	$form->addRule('sender',   $locale->get('letter_error_required_sender'),  'required');

	$form->setDefaults(
	    array(
	        'sender' => $_SESSION['site_sitemail']
	    )
	);

	// HIRLEVEL KULDESE
	if ($form->validate()) {
	    set_time_limit(0);

		$is_test  = $form->getSubmitValue('is_testaddr');
		$testaddr = $form->getSubmitValue('testaddr');
		$nid      = intval($form->getSubmitValue('nid'));
		$sender   = $form->getSubmitValue('sender');

		// Hirlevel uzenet adatainak lekerdezese
		$query = "
			SELECT file_name, subject, message, nt.file_name as template_file, charset
			FROM iShark_Newsletter n
			LEFT JOIN iShark_Newsletter_Templates nt ON n.newsletter_template_id = nt.newsletter_template_id
			WHERE n.newsletter_id = $nid
		";
		$result = $mdb2->query($query);
		if (!$message_data = $result->fetchRow()) {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}

		// Kuldes rogzitese
		$date_id = $mdb2->extended->getBeforeID('iShark_Newsletter_Sends_Dates', 'date_id', TRUE, TRUE);
        $query = "
			INSERT INTO iShark_Newsletter_Sends_Dates
			(date_id, date, newsletter_id, sender_user_id, sender)
			VALUES
			($date_id, NOW(), $nid, ".$_SESSION['user_id'].", '".$sender."')
		";
        $mdb2->exec($query);
		$last_date_id = $mdb2->extended->getAfterID($date_id, 'iShark_Newsletter_Sends_Dates', 'date_id');

		/* Mail_Queue hasznalata */
		require_once "Mail/Queue.php";

		/* Uj mail queue letrehozasa */
		$mail_queue =& new Mail_Queue($mail_queue_db_options, $mail_queue_mail_options);

		$hdrs = array(
		    'From'    => $sender,
			'To'      => '',  // Ezt majd kesobb allitjuk
			'Subject' => $message_data['subject']
		);

		$charset = empty($message_data['charset']) ? $locale->getCharset() : $message_data['charset'];

		// Karakterkeszlet beallitasok
		$mime_params = array(
			"text_encoding" => "7bit",
			"text_charset"  => "$charset",
			"head_charset"  => "$charset",
			"html_charset"  => "$charset",
		);

        //linkek atalakitasa
        $message_data['message'] = changeRelativeAbsolute($message_data['message']);

		$tpl->assign('mail_date',    get_date());
		$tpl->assign('mail_charset', $charset);
		$tpl->assign('mail_title',   $message_data['subject']);
		$tpl->assign('mail_message', $message_data['message']);
        $tpl->assign('mail_unsubs',  $locale->get('letter_field_unsubscribe'));

		// Alapertemezett kuldendo beallitas
 		$message = '<hmtl><head><title>'.$message_data['subject'].'</title></head><body>'.$message_data['message'].'</body></html>';
		$textmessage = wordwrap(html_to_text($message_data['message']));

		//ha nem teszt level, akkor mehet a queue-ba
		if (!$is_test) {
			// Cimzettek lekerdezese, es berakas a kuldesi sorba
			$newsletter_groups = $form->getSubmitValue('newsletter_groups');

			if (is_array($newsletter_groups)) {
				// Ha mindenkinek elkuldjuk,
				if (in_array(0, $newsletter_groups)) {
					$query = "
						SELECT newsletter_user_id as nuid, name, email
						FROM iShark_Newsletter_Users
						WHERE is_active='1' and is_deleted='0'
					";

					// Kulonben a kivalasztott csoportok tagjainak e-mail cimeinek lekerdezese
				} else {
					$grouplist = '';
					foreach ($newsletter_groups as $group_id) {
						$grouplist .= (!empty($grouplist) ? ' OR ' : '').'newsletter_group_id = '.intval($group_id);
					}
					$query = "
						SELECT nu.newsletter_user_id as nuid, nu.name as name, nu.email as email
						FROM iShark_Newsletter_Groups_Users ngu, iShark_Newsletter_Users nu
						WHERE ($grouplist) AND ngu.newsletter_user_id = nu.newsletter_user_id AND nu.is_active = '1' AND nu.is_deleted = '0'
						GROUP BY nu.email
					";
				}
				$result =& $mdb2->query($query);

				$mime =& new Mail_mime();
				$mime->setTXTBody($textmessage);

				// Cimzettek kuldesi sorba rakasa
				while ($cimzett =& $result->fetchRow()) {
                    // Uzenetsablon beolvasasa
                    if (is_file($tpl->template_dir.'/admin/newsletters/'.$message_data['template_file'])) {
                        $tpl->assign('mail_user_email', $cimzett['email']);
                        $tpl->assign('mail_user_id', $cimzett['nuid']);
                        $message = $tpl->fetch('admin/newsletters/'.$message_data['template_file']);
                    }

				    $mime->setHTMLBody($message);
                    $mime_body = $mime->get($mime_params);
					$hdrs['To'] = $cimzett['email'];

					$mime_headers = $mime->headers($hdrs);
					$last_queue_id = $mail_queue->put($sender, $cimzett['email'], $mime_headers, $mime_body, 0, false, $cimzett['nuid']);

					$name  = $mdb2->quote($cimzett['name']);
					$email = $mdb2->quote($cimzett['email']);
					$query = "
						INSERT INTO iShark_Newsletter_Sends
						(date_id, to_user_id, name, email, queue_id)
						VALUES
						($last_date_id, $cimzett[nuid], $name, $email, $last_queue_id)
					";
					$mdb2->exec($query);

					//ha van teszt cim, azt is belerakjuk
					if (!empty($testaddr)) {
					    $mail_queue->put($sender, $testaddr, $mime_headers, $mime_body, 0, false);

					    $query = "
    						INSERT INTO iShark_Newsletter_Sends
    						(date_id, to_user_id, name, email)
    						VALUES
    						($last_date_id, 0, 'Teszt', '".$testaddr."')
    					";
    					$mdb2->exec($query);
					}
				}
			}
		}

		// Teszt mailcim-re egybol mehet a level
		if (!empty($is_test) && !empty($testaddr)) {
		    include_once 'Mail.php';
			include_once 'Mail/mime.php';

			// Uzenetsablon beolvasasa
			if (is_file($tpl->template_dir.'/admin/newsletters/'.$message_data['template_file'])) {
				$tpl->assign('mail_user_email', '');
				$message = $tpl->fetch('admin/newsletters/'.$message_data['template_file']);
			}
			$hdrs['To'] = $testaddr;
            $mime =& new Mail_mime();

            $mime->setTXTBody($textmessage);
		    $mime->setHTMLBody($message);

		    $mime_body    = $mime->get($mime_params);
			$mime_headers = $mime->headers($hdrs);

			$mail =& Mail::factory('mail');
			$mail->send($sender, $mime_headers, $mime_body);

			$query = "
				INSERT INTO iShark_Newsletter_Sends
				(date_id, to_user_id, name, email)
				VALUES
				($last_date_id, 0, 'Teszt', '".$testaddr."')
			";
			$mdb2->exec($query);
		}

		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&msg=1');
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('letter_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('letter_form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($locale->get('title_send'), 'admin.php?p='.$module_name.'&act='.$page.'&sub_act='.$sub_act);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $locale->get('title_send'));

	// A hasznalando sablon neve, amit atadunk az admin.php-nek.
	$acttpl = 'dynamic_form';
}

// MODOSITAS, VAGY UJ FELVITEL ESETEN MASIK URLAP
if ($sub_act == 'add' || $sub_act == 'mod') {
    $titles = array('add' => $locale->get('letter_title_add'), 'mod' => $locale->get('letter_title_mod'));

    // szukseges fuggvenykonyvtarak betoltese
    require_once $include_dir.'/function.check.php';
    require_once 'HTML/QuickForm.php';
    require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	// elinditjuk a form-ot
	$form =& new HTML_QuickForm('frm_newsletter', 'post', 'admin.php?p='.$module_name);

	// a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('letter_form_required_note'));

	$form->addElement('header', 'letter', $locale->get('letter_form_header'));

	// Hirlevel eloredefinialt sablonjainak lekerdezese
	$query = "
		SELECT newsletter_template_id, template_name
		FROM iShark_Newsletter_Templates
		ORDER BY template_name
	";
	$result = $mdb2->query($query);
	$newsletter_templates_list = $result->fetchAll('', true);
	$form->addElement('select', 'newsletter_template_id', $locale->get('letter_field_newsletter_template_id'), $newsletter_templates_list);

	// Ha meg nincs egy template sem, akkor hibauzenet
	if ($result->numRows() == 0) {
		$form->setElementError('newsletter_template_id', $locale->get('letter_error_newsletter_empty_templates'));
	}

	// Targy:
	$form->addElement('text', 'subject', $locale->get('letter_field_subject'));

	// hirlevel szovege
	$message =& $form->addElement('textarea', 'message', $locale->get('letter_field_message'));
	$message->setCols(30);
	$message->setRows(30);

	// szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	// szabalyok beallitasa
	$form->addRule('newsletter_template_id', $locale->get('letter_error_required_newsletter_template'), 'required');
	$form->addRule('subject',                $locale->get('letter_error_required_subject'),             'required');
	$form->addRule('message',                $locale->get('letter_error_required_message'),             'required');

	/**
	 *  uj hirlevel letrehozasa
	 */
	if ($sub_act == 'add') {
		$form->addElement('hidden','sub_act', $sub_act);

		// Validalas
		if ($form->validate()) {
			// SQL escape szures
			$form->applyFilter('__ALL__', array($mdb2, 'escape'));

			$subject     = $form->getSubmitValue('subject');
			$message     = $form->getSubmitValue('message');
			$template_id = intval($form->getSubmitValue('newsletter_template_id'));

			$newsletter_id = $mdb2->extended->getBeforeID('iShark_Newsletter', 'newsletter_id', TRUE, TRUE);
			$chrset = $locale->getCharset();
			$query = "
				INSERT INTO iShark_Newsletter
				(newsletter_id, newsletter_template_id, subject, message, add_user_id, add_date, charset)
				VALUES
				($newsletter_id, $template_id, '$subject', '$message', ".$_SESSION['user_id'].", NOW(), '$chrset')
			";
			$mdb2->exec($query);

			logger($page.'_'.$sub_act);

			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	}

	/**
	 *  Hirlevel modositasa
	 */
	if ($sub_act == 'mod') {
		$nid = intval($_REQUEST['nid']);

		$form->addElement('hidden', 'sub_act', $sub_act);
		$form->addElement('hidden', 'nid',     $nid);

		//Alapertelmezett ertekek beallitasa
		$query = "
			SELECT newsletter_template_id, subject, message
			FROM iShark_Newsletter
			WHERE newsletter_id = $nid
		";
		$result =& $mdb2->query($query);
		if (!$row = $result->fetchRow()) {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
		$form->setDefaults($row);

		// Ha megfeleloek az adatok
		if ($form->validate()) {
			// SQL escape karakterek
			$form->applyFilter('__ALL__', array($mdb2, 'escape'));

			// ertekek beolvasasa
			$nid         = $form->getSubmitValue('nid');
			$subject     = $form->getSubmitValue('subject');
			$message     = $form->getSubmitValue('message');
			$template_id = intval($form->getSubmitValue('newsletter_template_id'));
			$chrset 	 = $locale->getCharset();

			// Adatok mentese
			$query = "
				UPDATE iShark_Newsletter
				SET subject                = '$subject',
					message                = '$message',
					newsletter_template_id = $template_id,
					charset                = '$chrset'
				WHERE newsletter_id = $nid
			";
			$mdb2->exec($query);

			logger($page.'_'.$sub_act);

			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	}
	$form->addElement('submit', 'submit', $locale->get('letter_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('letter_form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',        $renderer->toArray());
	$tpl->assign('tiny_fields', 'message');
	$tpl->assign('lang_title',  $titles[$sub_act]);

	// megadjuk a tpl file nevet, amit atadunk az admin.php-nek,innen
	$acttpl = 'dynamic_form';
}

/**
 * Kuldesi adatok
 */
if ($sub_act == 'slst') {
    $nid = intval($_REQUEST['nid']);

    //rendezes
    $fieldselect1 = "";
    $fieldselect2 = "";
    $fieldselect3 = "";
    $fieldselect4 = "";
    $fieldselect5 = "";
    $fieldselect6 = "";
    $ordselect1   = "";
    $ordselect2   = "";
    if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
        $field      = intval($_REQUEST['field']);
        $ord        = $_REQUEST['ord'];
        $fieldorder = " ORDER BY";

        switch ($field) {
            case 1:
                $fieldorder   .= " d.date ";
                $fieldselect1 = "selected";
                break;
            case 2:
                $fieldorder   .= " u.user_name ";
                $fieldselect2 = "selected";
                break;
            case 3:
                $fieldorder   .= " d.sender ";
                $fieldselect3 = "selected";
                break;
            case 4:
                $fieldorder   .= " name ";
                $fieldselect4 = "selected";
                break;
            case 5:
                $fieldorder   .= " email ";
                $fieldselect5 = "selected";
                break;
            case 6:
                $fieldorder   .= " send_date ";
                $fieldselect6 = "selected";
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
        $fieldorder   = (empty($_REQUEST['did'])) ? "ORDER BY d.date" : "ORDER BY name";
        $fieldselect1 = "selected";
        $order        = "ASC";
    }

	//breadcrumb
	$breadcrumb->add($locale->get('title_sent'), 'admin.php?p='.$module_name.'&act='.$page.'&sub_act=slst&nid='.$nid);

    $query = "
        SELECT d.date_id as date_id, d.date as date, u.user_name as user, d.sender AS sendermail
        FROM iShark_Newsletter_Sends_Dates d
        LEFT JOIN iShark_Users u ON d.sender_user_id=u.user_id
        WHERE newsletter_id = $nid
    	$fieldorder $order
    ";
    $acttpl = 'newsletter_sent_list';
    include_once 'Pager/Pager.php';

    //kuldesi adatok listaja
    if (isset($_REQUEST['did'])) {
        $did = intval($_REQUEST['did']);

		//breadcrumb
		$breadcrumb->add($locale->get('title_recipients'), 'admin.php?p='.$module_name.'&act='.$page.'&sub_act=slst&nid='.$nid.'&did='.$did);

        $query = "
            SELECT s.name AS name, s.email AS email, s.send_date AS send_date, q.sent_time AS sent_time
			FROM iShark_Newsletter_Sends s
			LEFT JOIN iShark_Mail_Queue q ON q.id = s.queue_id
			WHERE date_id = $did AND (q.try_sent = 0 OR try_sent = 1)
        	$fieldorder $order
        ";
        $acttpl = 'newsletter_sent_user_list';
    }

    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$tpl->assign('page_data',    $paged_data['data']);
	$tpl->assign('page_list',    $paged_data['links']);
    $tpl->assign('nid',          $nid);
    $tpl->assign('fieldselect1', $fieldselect1);
    $tpl->assign('fieldselect2', $fieldselect2);
    $tpl->assign('fieldselect3', $fieldselect3);
    $tpl->assign('fieldselect4', $fieldselect4);
    $tpl->assign('fieldselect5', $fieldselect5);
    $tpl->assign('fieldselect6', $fieldselect6);
    $tpl->assign('ordselect1',   $ordselect1);
    $tpl->assign('ordselect2',   $ordselect2);

    return;
}

/**
 * Torles
 */
if ($sub_act == 'del') {
	$nid = intval($_REQUEST['nid']);

	$query = "
		DELETE FROM iShark_Newsletter
		WHERE newsletter_id = $nid
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page);
	exit;
}

/**
 * Ha lek�rj�k a list�t
 */
if ($sub_act == "lst") {
    include_once $include_dir.'/function.newsletter.php';

    //rendezes
    $fieldselect1 = "";
    $fieldselect2 = "";
    $fieldselect3 = "";
    $fieldselect4 = "";
    $ordselect1   = "";
    $ordselect2   = "";
    if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
        $field      = intval($_REQUEST['field']);
        $ord        = $_REQUEST['ord'];
        $fieldorder = " ORDER BY";

        switch ($field) {
            case 1:
                $fieldorder   .= " n.subject ";
                $fieldselect1 = "selected";
                break;
            case 2:
                $fieldorder   .= " n.add_date ";
                $fieldselect2 = "selected";
                break;
            case 3:
                $fieldorder   .= " u.name ";
                $fieldselect3 = "selected";
                break;
            case 4:
                $fieldorder   .= " n.newsletter_id ";
                $fieldselect4 = "selected";
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
        $fieldorder   = "ORDER BY n.newsletter_id";
        $fieldselect4 = "selected";
        $order        = "DESC";
    }

	$query = "
		SELECT n.newsletter_id AS nid, n.subject AS subject, u.name AS username, n.add_date AS add_date
		FROM iShark_Newsletter n
		LEFT JOIN iShark_Users u ON u.user_id = n.add_user_id
		$fieldorder $order
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	if (isset($_GET['msg']) && $_GET['msg'] == '1') {
		$tpl->assign('message_string', $locale->get('message_sent'));
	}

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('act_add'),
			'pic'   => 'add.jpg'
		)
	);

	$tpl->assign('page_data',    $paged_data['data']);
	$tpl->assign('page_list',    $paged_data['links']);
	$tpl->assign('add_new',      $add_new);
	$tpl->assign('fieldselect1', $fieldselect1);
    $tpl->assign('fieldselect2', $fieldselect2);
    $tpl->assign('fieldselect3', $fieldselect3);
    $tpl->assign('fieldselect4', $fieldselect4);
    $tpl->assign('ordselect1',   $ordselect1);
    $tpl->assign('ordselect2',   $ordselect2);

    $tpl->register_function('is_sent', 'is_sent');

	$acttpl = 'newsletter_list';
}

?>