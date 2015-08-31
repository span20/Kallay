<?php
//modul neve
$module_name = "test";

if (isset($_REQUEST["sent"]) && intval($_REQUEST["sent"]) == 1) {

	if (!empty($_REQUEST["name"]) && !empty($_REQUEST["email"])) {
		if (filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
			$_SESSION["teszt_email"] = $_REQUEST["email"];
			$_SESSION["teszt_name"] = $_REQUEST["name"];
			header("Location: index.php?p=test");
		} else {
			header("Location: index.php?p=test&err=2");
		}
	} else {
		header("Location: index.php?p=test&err=1");
	}
	
}

if (isset($_REQUEST["cv_sent"]) && intval($_REQUEST["cv_sent"]) == 1) {

	$subject = "topmernokvagy.hu - ".$_SESSION["teszt_name"];

	$message = "";
	$filename = "";
	
	if (!empty($_FILES["cv_file"]["tmp_name"])) {
		
		$filename = time()."_".str_replace(array("@", "."), array("_", "_"), $_SESSION["teszt_email"])."_CV.".substr($_FILES["cv_file"]["name"], -3);
	
		move_uploaded_file($_FILES["cv_file"]["tmp_name"], "files/cvs/".$filename);
	
		$q = "
			UPDATE iShark_Users
			SET cv = '".$filename."'
			WHERE user_id = '".$_SESSION["test_user_id"]."'
		";
		$mdb2->exec($q);
	
		$message .= "Sikeres tesztkitöltés és CV!<br />";
	} else {
		$message .= "Sikeres tesztkitöltés, NINCS CV csatolva!<br />";
	}
	
	$f_array = array();
	
	$answers_exp = explode("&", $_REQUEST["answers"]);
	
	foreach( $answers_exp as $val ) {
		$tmp = explode('=', $val);
		$f_array[filter_var($tmp[0], FILTER_SANITIZE_NUMBER_INT)] = $tmp[1];
	}
	
	ksort($f_array);

	foreach( $f_array as $key => $val ) {
		$message .= "Kérdés ".($key+1).": ".$val."<br />";
	}

	$message .= "
		Név: ".$_SESSION["teszt_name"]."<br />
		Email: ".$_SESSION["teszt_email"]."
	";
	
	include_once 'Mail.php';
	include_once 'Mail/mime.php';
	
	$charset = "UTF-8";

	// Karakterkeszlet beallitasok
	$mime_params = array(
		"text_encoding" => "7bit",
		"text_charset"  => "$charset",
		"head_charset"  => "$charset",
		"html_charset"  => "$charset",
	);
	
	$hdrs = array(
		'From'    => "info@nokiasiemens.hu",
		'To'      => '',  // Ezt majd kesobb allitjuk
		'Subject' => $subject
	);
	
	$hdrs['To'] = "moodspan@gmail.com, wb@prokomm.hu";
	$mime =& new Mail_mime();

	$mime->setTXTBody($message);
	$mime->setHTMLBody($message);

	if (!empty($filename)) {
		$mime->addAttachment ("files/cvs/".$filename);
	}
	
	$mime_body    = $mime->get($mime_params);
	$mime_headers = $mime->headers($hdrs);
	
	unset($_SESSION["teszt_email"]);
	unset($_SESSION["teszt_name"]);
	unset($_SESSION["test_user_id"]);
	
	$mail =& Mail::factory('mail');
	if($mail->send("info@nokiasiemens.hu", $mime_headers, $mime_body)) {
		header("Location: index.php?p=test&m=ok");
	} else {
		header("Location: index.php?p=test&m=er");
	}
}

if ($_REQUEST["toplista"] == 1) {
	$q = "
		SELECT MAX( r.result ) AS result, MAX( r.speed ) AS speed, u.name
		FROM iShark_Users_Results AS r
		LEFT JOIN iShark_Users AS u ON u.user_id = r.user_id
		GROUP BY r.user_id
		ORDER BY result DESC, speed DESC
		LIMIT 10
	";
	$res = $mdb2->query($q);
	$all = $res->fetchAll();
	
	foreach ($all as $key => $value) {
		$secs = 900 - $value["speed"];
		
		$currentMinutes = floor($secs / 60);
		$currentSeconds = $secs % 60;
		if($currentMinutes <= 9) $currentMinutes = "0".$currentMinutes;
		if($currentSeconds <= 9) $currentSeconds = "0".$currentSeconds;
		
		$secs = $currentMinutes.":".$currentSeconds;
		
		$all[$key]["speed"] = $secs;
	}

	$tpl->assign('topli', $all);
} else {
	if (isset($_SESSION["teszt_email"]) && isset($_SESSION["teszt_name"])) {
		$bodyonload[]= "startTest();";
	}
}

$acttpl = 'test';

?>