<?php

//modul neve
$module_name = "contact";

if (!empty($_REQUEST["send"]) && $_REQUEST["send"] == 1){
	$nev = $_REQUEST["nev"];
	$email = $_REQUEST["email"];
	$targy = $_REQUEST["targy"];
	$uzenet = $_REQUEST["uzenet"];
	
	$hdrs = array(
		'From'    => $email,
		'To'      => '',  // Ezt majd kesobb allitjuk
		'Subject' => $targy
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
	//$message_data['message'] = changeRelativeAbsolute($message_data['message']);

	$tpl->assign('mail_date',    date("Y.m.d."));
	$tpl->assign('mail_charset', $charset);
	$tpl->assign('mail_sender',  $nev);
	$tpl->assign('mail_title',   $targy);
	$tpl->assign('mail_message', $uzenet);
	$tpl->assign('mail_email',   $email);
	$tpl->assign('mail_unsubs',  '');

	// Alapertemezett kuldendo beallitas
	$message = '<hmtl><head><title>'.$targy.'</title></head><body>'.$uzenet.'</body></html>';
	$textmessage = $uzenet;
	
	include_once 'Mail.php';
	include_once 'Mail/mime.php';

	// Uzenetsablon beolvasasa
	if (is_file($tpl->template_dir.'/admin/newsletters/default.tpl')) {
		$tpl->assign('mail_user_email', '');
		$message = $tpl->fetch('admin/newsletters/default.tpl');
	}
	$hdrs['To'] = "crea@focus.hu";
	$mime =& new Mail_mime();

	$mime->setTXTBody($textmessage);
	$mime->setHTMLBody($message);

	$mime_body    = $mime->get($mime_params);
	$mime_headers = $mime->headers($hdrs);

	$mail =& Mail::factory('mail');
	if ($mail->send($email, $mime_headers, $mime_body)) {
		header("Location: ".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."&sent=1");
	} else {
		header("Location: ".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]);
	}
}

$acttpl = 'contact';

?>