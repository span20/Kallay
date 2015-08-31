#!/usr/bin/env php
<?php

require_once 'includes/config.php';

//hirlevelhez tartozo dolgok
if (isModule('newsletter', 'admin')) {
    /* How many mails could we send each time the script is called */
	$max_amount_mails = 100;

    require_once 'Mail/Queue.php';

	/* we use the db_options and mail_options from the config again  */
	$mail_queue =& new Mail_Queue($mail_queue_db_options, $mail_queue_mail_options);

	/* really sending the messages */
	$mail_queue->sendMailsInQueue($max_amount_mails);

	//kiszedjuk azokat a mail-eket, amik mar el lettek kuldve
	$query = "
		SELECT sent_time, recipient, create_time, id_user
		FROM iShark_Mail_Queue
		WHERE sent_time != '' 
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
	    while ($row = $result->fetchRow())
	    {
   	        //atrakjuk az idopontot a kikuldott levelek koze
   	        $query_add = "
   				UPDATE iShark_Newsletter_Sends 
   				SET send_date = '".$row['sent_time']."'
   				WHERE email = '".$row['recipient']."' AND send_date = '0000-00-00 00:00:00' AND to_user_id = ".$row['id_user']."
   			";
   	        $mdb2->exec($query_add);

   	        //toroljuk a queue-bol a levelet
   	        $query_del = "
   				DELETE FROM iShark_Mail_Queue
   				WHERE sent_time != '' AND recipient = '".$row['recipient']."' AND id_user = ".$row['id_user']."
   			";
   	        $mdb2->exec($query_del);
	    }
	}
}

//aprohirdeteshez tartozo dolgok
if (isModule('classifieds', 'index')) {
    $charset = $locale->getCharset();

	// Karakterkeszlet beallitasok
	$mime_params = array(
		"text_encoding" => "8bit",
		"text_charset"  => $charset,
		"head_charset"  => $charset,
		"html_charset"  => $charset,
	);

	//lekerdezzuk, hogy kiknek kell mail-t kuldeni - a hirdetes lejarta elott
	$query = "
		SELECT a.advert_id AS advert_id, a.email AS email, a.name AS name, a.phone AS phone, a.description AS description, 
			a.timer_end AS timer_end, a.price AS price, a.category_id AS autocat 
		FROM iShark_Classifieds_Advert a
		WHERE a.is_finished_mail = 0 AND a.is_active = 1
			AND (a.timer_end <= NOW() AND a.timer_end >= (NOW( ) - INTERVAL ".$_SESSION['site_class_expiration_mail']." DAY))
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();

		//nyelvi file betoltese
		$locale->useArea('classifieds');

		require_once "Mail/Queue.php";

		/* Uj mail queue letrehozasa */
		$mail_queue =& new Mail_Queue($mail_queue_db_options, $mail_queue_mail_options);
		$hdrs = array(
			'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
			'To'      => '',  // Ezt majd kesobb allitjuk
			'Subject' => $locale->get('mail_subject_expire')
		);

		$msg = $locale->get('mail_header').' '.$row['name'].'!<br />';
		$msg .= $locale->getBySmarty('mail_expire_text1').' <strong>'.$row['timer_end'].'</strong> '.$locale->getBySmarty('mail_expire_text2');
		$msg .= '<table style="width: 100%;">';
		$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->getBySmarty('mail_header2').'</th></tr>';
		if (!empty($_SESSION['site_class_autocategory'])) {
			if ($row['autocat'] == 0) {
					$mail_autocat = $locale->get('field_sell');
				}
				else if ($row['autocat'] == 1) {
					$mail_autocat = $locale->get('field_buy');
				}
				else {
					$mail_autocat = $locale->get('field_swap');
				}
			$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_autocat').'</strong></td><td>'.$mail_autocat.'</td></tr>';
			$msg .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_name').'</strong></td><td>'.$row['name'].'</td></tr>';
			$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_phone').'</strong></td><td>'.$row['phone'].'</td></tr>';
			$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_mail').'</strong></td><td>'.$row['email'].'</td></tr>';
			$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_timerend').'</strong></td><td>'.$row['timer_end'].'</td></tr>';
			$msg .= '<tr><td><strong>'.$locale->getBySmarty('field_main_price').'</strong></td><td>'.$row['price'].'</td></tr>';
			$msg .= '<tr><td valign="top"><strong>'.$locale->getBySmarty('field_main_description').'</strong></td><td>'.nl2br($row['description']).'</td></tr>';
			$msg .= '</table><br />';
			$msg .= $locale->getBySmarty('mail_activate_text5');
		}

		// Uzenetsablon beolvasasa
		if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
			$tpl->assign('mail_body', $msg);
			$msg = $tpl->fetch('mail/mail_html.tpl');
		}

		//cimzett
		$hdrs['To'] = $row['email'];

		$mime =& new Mail_mime();
		$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
		$mime->setHTMLBody($msg);
		$mime_body = $mime->get($mime_params);

		$mime_headers = $mime->headers($hdrs);
		$mail_queue->put($_SESSION['site_sitemail'], $row['email'], $mime_headers, $mime_body);

		//beallitjuk a mezot, amivel jeloljuk, hogy kapott-e mar levelet
		$query = "
			UPDATE iShark_Classifieds_Advert 
			SET is_finished_mail = 1 
			WHERE advert_id = ".$row['advert_id']."
		";
		$mdb2->exec($query);
	} //figyelmeztetes hirdetes lejarta elott - vege

	//lekerdezzuk, hogy kiknek kell a hirdetest torolni, mert lejart
	$query = "
		SELECT a.advert_id AS advert_id, a.email AS email, a.name AS name, a.phone AS phone, a.description AS description, 
			a.timer_end AS timer_end, a.price AS price, a.category_id AS autocat 
		FROM iShark_Classifieds_Advert a
		WHERE a.is_finished_mail = 1 AND a.is_active = 1 AND a.timer_end <= NOW()
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();

		//nyelvi file betoltese
		$locale->useArea('classifieds');

		require_once "Mail/Queue.php";

		/* Uj mail queue letrehozasa */
		$mail_queue2 =& new Mail_Queue($mail_queue_db_options, $mail_queue_mail_options);
		$hdrs2 = array(
			'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
			'To'      => '',  // Ezt majd kesobb allitjuk
			'Subject' => $locale->get('mail_subject_delete')
		);

		$msg2 = $locale->get('mail_header').' '.$row['name'].'!<br /><br />';
		$msg2 .= $locale->getBySmarty('mail_expire_text1').' <strong>'.$row['timer_end'].'</strong> '.$locale->getBySmarty('mail_expire_text3');
		$msg2 .= '<table style="width: 100%;">';
		$msg2 .= '<tr><th colspan="2" style="text-align: left;">'.$locale->getBySmarty('mail_header2').'</th></tr>';
		if (!empty($_SESSION['site_class_autocategory'])) {
			if ($row['autocat'] == 0) {
					$mail_autocat = $locale->get('field_sell');
				}
				else if ($row['autocat'] == 1) {
					$mail_autocat = $locale->get('field_buy');
				}
				else {
					$mail_autocat = $locale->get('field_swap');
				}
			$msg2 .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_autocat').'</strong></td><td>'.$mail_autocat.'</td></tr>';
			$msg2 .= '<tr><td style="width: 50%;"><strong>'.$locale->getBySmarty('field_main_name').'</strong></td><td>'.$row['name'].'</td></tr>';
			$msg2 .= '<tr><td><strong>'.$locale->getBySmarty('field_main_phone').'</strong></td><td>'.$row['phone'].'</td></tr>';
			$msg2 .= '<tr><td><strong>'.$locale->getBySmarty('field_main_mail').'</strong></td><td>'.$row['email'].'</td></tr>';
			$msg2 .= '<tr><td><strong>'.$locale->getBySmarty('field_main_timerend').'</strong></td><td>'.$row['timer_end'].'</td></tr>';
			$msg2 .= '<tr><td><strong>'.$locale->getBySmarty('field_main_price').'</strong></td><td>'.$row['price'].'</td></tr>';
			$msg2 .= '<tr><td valign="top"><strong>'.$locale->getBySmarty('field_main_description').'</strong></td><td>'.nl2br($row['description']).'</td></tr>';
			$msg2 .= '</table><br />';
			$msg2 .= $locale->getBySmarty('mail_activate_text5');
		}

		// Uzenetsablon beolvasasa
		if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
			$tpl->assign('mail_body', $msg2);
			$msg2 = $tpl->fetch('mail/mail_html.tpl');
		}

		//cimzett
		$hdrs2['To'] = $row['email'];

		$mime2 =& new Mail_mime();
		$mime2->setTXTBody(html_entity_decode(strip_tags($msg2)));
		$mime2->setHTMLBody($msg2);
		$mime_body2 = $mime2->get($mime_params2);

		$mime_headers2 = $mime2->headers($hdrs2);
		$mail_queue2->put($_SESSION['site_sitemail'], $row['email'], $mime_headers2, $mime_body2);

		//kitoroljuk a lejart hirdeteseket
		$query = "
			DELETE FROM iShark_Classifieds_Advert 
			WHERE advert_id = ".$row['advert_id']."
		";
		$mdb2->exec($query);

		//kitoroljuk a bejegyzest az aprohirdetes - terulet kapcsolotablabol is
		$query = "
			DELETE FROM iShark_Classifieds_Advert_Counties 
			WHERE advert_id = ".$row['advert_id']."
		";
		$mdb2->exec($query);

		//kapcsolodo kepek kitorlese
		$query2 = "
			SELECT picture 
			FROM iShark_Classifieds_Advert_Pictures 
			WHERE advert_id = ".$row['advert_id']."
		";
		$result2 =& $mdb2->query($query2);
		while ($row2 = $result2->fetchRow())
		{
			@unlink($_SESSION['site_class_advpicdir'].'/'.$row2['picture']);
		}
		$query = "
			DELETE FROM iShark_Classifieds_Advert_Pictures 
			WHERE advert_id = ".$row['advert_id']."
		";
		$mdb2->exec($query);
	} //hirdetes torlesenek vege

	require_once 'Mail/Queue.php';

	/* How many mails could we send each time the script is called */
	$max_amount_mails = 100;

	/* we use the db_options and mail_options from the config again  */
	$mail_queue =& new Mail_Queue($mail_queue_db_options, $mail_queue_mail_options);

	/* really sending the messages */
	$mail_queue->sendMailsInQueue($max_amount_mails);
}

//mti hirek beolvasas
if (isModule('contents', 'admin') && !empty($_SESSION['site_cnt_is_mti'])) {
	require_once 'XML/Parser.php';
	require_once 'XML/Parser/Simple.php';
	require_once $include_dir.'/MTI_News_XMLParser.php';

	$parser_counter = 1;
	$p =& new myParser();

	if ($xml = fopen($_SESSION['site_cnt_mti_link'], 'r')) {
        fclose($xml);
        $minta  = array('/'.preg_quote('encoding="utf-8"', '/').'/i', '/^\xEF\xBB\xBF/');
        $csere  = array('encoding="iso-8859-1"', '');
        $szoveg = preg_replace($minta, $csere, file_get_contents($_SESSION['site_cnt_mti_link']));
        

        // karakterkodolasok
        $out_char = strtoupper($locale->getCharset($tr_options['fallback']));
        $in_char  = mb_detect_encoding($szoveg);

        if ($in_char != $out_char) {
            $szoveg = iconv($in_char, $out_char, $szoveg);
        }

    	$result = $p->setInputString($szoveg);
    	$result = $p->parse();
	}

	if (!empty($mtidata)) {
		foreach ($mtidata as $key => $value) {
		    // cim
		    if (!empty($value['title'])) {
	            $title = $value['title'];
		    } else {
		        $title = "";
		    }

		    // bevezeto szoveg
		    if (!empty($value['lead'])) {
		        $lead = substr($value['lead'], 0, $_SESSION['site_leadmax']);
		    } else {
		        $lead = "";
		    }

		    // tartalom - ha nincs, akkor a bevezeto szoveg
		    if (!empty($value['body'])) {
	            $body = $value['body'];
		    } else {
		        $body = $value['lead'];
		    }

		    // kategoria
		    if (!empty($value['mainsection'])) {
	            $mainsection = $value['mainsection'];
		    } else {
		        $mainsection = "";
		    }

		    // kep
		    if (!empty($value['image'])) {
		        $image = $value['image'];
		    } else {
		        $image = "";
		    }

		    //datumokat at kell formaznunk
			$createdate   = date("Y-m-d H:i:s", strtotime($value['createdate']));
			$modifieddate = date("Y-m-d H:i:s", strtotime($value['modifieddate']));

			$types  = array('text', 'text', 'text', 'text', 'timestamp', 'timestamp', 'text', 'integer');
			$values = array($title, $lead, $body, $mainsection, $createdate, $modifieddate, $image, $value['id']);

			//megnezzuk, hogy van-e mar ilyen hir
			$query = "
				SELECT id 
				FROM iShark_Mti_News 
				WHERE id = ".$value['id']."
			";
			$result =& $mdb2->query($query);
			//ha van mar ilyen hir, akkor frissitjuk
			if ($result->numRows() > 0) {
				$query_update = "
					UPDATE iShark_Mti_News 
					SET title        = ?, 
						lead         = ?, 
						body         = ?, 
						mainsection  = ?, 
						createdate   = ?, 
						modifieddate = ?, 
						image        = ? 
					WHERE id = ?
				";
				$result_update = $mdb2->prepare($query_update, $types, MDB2_PREPARE_MANIP);
				$result_update->execute($values);
			}
			//ha nincs meg ilyen, akkor beszurjuk
			else {
				$query_insert = "
					INSERT INTO iShark_Mti_News 
					(title, lead, body, mainsection, createdate, modifieddate, image, id) 
					VALUES 
					(?, ?, ?, ?, ?, ?, ?, ?)
				";
				$result_insert = $mdb2->prepare($query_insert, $types, MDB2_PREPARE_MANIP);
				$result_insert->execute($values);
			}
		}
	}
}

?>