<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//nyelvi file
$locale->useArea('index_newsletter');

//ezek az elfogadhato muveleti hivasok ($act)
$is_act = array('newsletter_subs', 'newsletter_unsubs');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$site_errors[] = array('text' => $locale->get('error_not_act'), 'link' => 'javascript:history.back(-1)');
	return;
}
if (!check_perm($act, NULL, 0, 'newsletter', 'index')) {
    $site_errors[] = array('text' => $locale->get('error_no_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

/**
 * hirlevél jelentkezés
 */
if ($act == "newsletter_subs") {
    $query = "
		SELECT * 
		FROM iShark_Newsletter_Users 
		WHERE is_active = '1' AND is_deleted = '0' AND activate = '' AND email = '".$_REQUEST['email']."'
	";
	$result =& $mdb2->query($query);		    
	if ($result->numRows() > 0) {
	    $site_errors[] = array('text' => $locale->get('error_email_exists'), 'link' => 'javascript:history.back(-1)');
	    return;
    } else {
        //TODO - siman, csak e-mail cim alapjan ne irjuk fel hirlevelre, kelljen hozza egy aktivalas is
        //TODO - megcsinlani a kulon hirlevel feliratkozas block-ot is
	    $query = "
			INSERT INTO iShark_Newsletter_Users
			(name, email, is_active, is_deleted)
			VALUES
			('".$_REQUEST['nev']."', '".$_REQUEST['email']."', '1', '0')
		";    
		$mdb2->exec($query);
			         
		//$site_success[] = array('text' => $locale->get('success_unsubscribe_activate'), 'link' => 'index.php');
	    //return;
	}
} //jelentkezés vege

/**
 * hirlevel leiratkozas
 */
if ($act == "newsletter_unsubs") {
    if (isset($_GET['nl_email'])) {
        $nl_email = $_GET['nl_email'];

        //ha nincs aktivalo kod, akkor kikuldjuk azt
        if (!isset($_GET['nl_gc'])) {
            $query = "
				SELECT newsletter_user_id, name
				FROM iShark_Newsletter_Users
				WHERE email = '$nl_email' AND is_active = '1' AND is_deleted = '0'
			";
            $result =& $mdb2->query($query);
            if ($result->numRows() > 0) {
                $row = $result->fetchRow();

                //ha van ilyen e-mail cim, generalunk egy aktivalo kodot
                require_once "Text/Password.php";
			    $activate = Text_Password::create(8, 'unpronounceable', 'alphanumeric');

			    $query = "
					UPDATE iShark_Newsletter_Users
					SET activate = '$activate'
					WHERE email = '$nl_email'
				";
			    $mdb2->exec($query);

    			//kikuldunk egy e-mail-t, amiben benne van mar az aktivalo kod is
                ini_set('display_errors', 0);
			    include_once 'Mail.php';
			    include_once 'Mail/mime.php';

			    $hdrs = array(
				    'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
				    'Subject' => $locale->get('mail_unsubscribe_subject')
			    );
			    $mime = new Mail_mime("\n");
			    $charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			    $msg = $locale->get('mail_unsubscribe_header').' '.$row['name'].'!<br /><br />';
			    $msg .= $locale->get('mail_unsubscribe_text1').'<br />';
			    $msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p=newsletter&act=newsletter_unsubs&nl_email='.$nl_email.'&nl_gc='.$activate.'" title="'.$locale->get('mail_unsubscribe_text2').'">'.$locale->get('mail_unsubscribe_text2').'</a><br /><br />';
			    $msg .= $locale->get('mail_unsubscribe_text3').'<br /><a href="'.$_SESSION['site_sitehttp'].'" title="'.$_SESSION['site_sitename'].'">'.$_SESSION['site_sitename'].'</a>';

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
			    $mail->send($nl_email, $hdrs, $body);

			    $site_success[] = array('text' => $locale->get('success_unsubscribe_activate'), 'link' => 'index.php');
			    return;
            } else {
                $site_errors[] = array('text' => $locale->get('error_no_user'), 'link' => 'javascript:history.back(-1)');
	            return;
            }
        //ha van aktivalo kod is, akkor tenyleg leirjuk a hirlevelrol
        } else {
            $nl_gc = $_GET['nl_gc'];

            //lekerdezzuk, hogy letezik-e ilyen e-mail cim, aktivalo koddal
            $query = "
				SELECT *
				FROM iShark_Newsletter_Users
				WHERE email = '$nl_email' AND activate = '$nl_gc'
			";
            $result =& $mdb2->query($query);
            if ($result->numRows() > 0) {
                $query = "
					UPDATE iShark_Newsletter_Users
					SET is_deleted = '1',
						is_active  = '0',
						activate   = ''
					WHERE email = '$nl_email' AND activate = '$nl_gc'
				";
                $mdb2->exec($query);

                $site_success[] = array('text' => $locale->get('success_unsubscribe'), 'link' => 'index.php');
                return;
            }
        }
    } else {
        $site_errors[] = array('text' => $locale->get('error_no_user'), 'link' => 'javascript:history.back(-1)');
	    return;
    }
}

?>