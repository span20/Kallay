<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "feedback";

//nyelvi file
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('feedback_lst');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "feedback_lst";
}
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
	$site_errors[] = array('text' => $locale->get('error_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

if ($act == "feedback_lst") {
	//lekerdezzuk a modulhoz tartozo beallitasokat
	$query = "
		SELECT f.email AS fmail 
		FROM iShark_Feedback_Configs f
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$site_errors[] = array('text' => $locale->get('error_missing_table'), 'link' => 'javascript:history.back(-1)');
		return;
	} else {
		while ($row = $result->fetchRow())
		{
			$feedback_mail = $row['fmail'];
		}

		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		$form_feedback =& new HTML_QuickForm('frm_feedback', 'post', 'index.php?p='.$module_name);

		$form_feedback->setRequiredNote($locale->get('form_required_note'));

		$form_feedback->addElement('header', 'feedback', $locale->get('form_header'));

		//nev
		$form_feedback->addElement('text', 'name', $locale->get('form_name'));

		//email cim
		$form_feedback->addElement('text', 'email', $locale->get('form_email'));

		//targy
		$form_feedback->addElement('text', 'subject', $locale->get('form_subject'));

		//uzenet
		$form_feedback->addElement('textarea', 'message', $locale->get('form_message'));

		//masolat sajat cimre
		$copymail =& $form_feedback->addElement('checkbox', 'copymail', $locale->get('form_copymail'), null);

		$form_feedback->applyFilter('__ALL__', 'trim');

		$form_feedback->addRule('name',    $locale->get('error_name'),    'required');
		$form_feedback->addRule('email',   $locale->get('error_email1'),  'required');
		$form_feedback->addRule('email',   $locale->get('error_email2'),  'email');
		$form_feedback->addRule('subject', $locale->get('error_subject'), 'required');
		$form_feedback->addRule('message', $locale->get('error_message'), 'required');

		//ha be van jelentkezve a user, akkor kitoltunk par mezot
		if (isset($_SESSION['user_id'])) {
			$query = "
				SELECT u.user_name AS uname, u.email AS umail 
				FROM iShark_Users u 
				WHERE user_id = '".$_SESSION['user_id']."'
			";
			$result = $mdb2->query($query);
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
					$form_feedback->setDefaults(array(
						'name'  => $row['uname'],
						'email' => $row['umail']
						)
					);
					//csak olvashatova tesszuk a nev es e-mail mezoket
					$form_feedback->updateElementAttr('name',  'readonly');
					$form_feedback->updateElementAttr('email', 'readonly');
				}
			}
		}

		if ($form_feedback->validate()) {
			$name    = $form_feedback->getSubmitValue('name');
			$email   = $form_feedback->getSubmitValue('email');
			$subject = strip_tags($form_feedback->getSubmitValue('subject'));
			$message = strip_tags($form_feedback->getSubmitValue('message'));

			//elkuldjuk a levelet
			ini_set('display_errors', 0);
			include_once 'Mail.php';
			include_once 'Mail/mime.php';

			$hdrs = array(
				'From'    => '"'.preg_replace('|"|', '\"', $name).'" <'.$email.'>',
				'Subject' => $subject
			);
			$mime =& new Mail_mime("\n");
			$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			$msg = $locale->get('mail_header').'<br /><br />';
			$msg .= '<table style="width: 100%; text-align: left;">';
			$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->get('mail_text1').'</th></tr>';
			$msg .= '<tr><td>'.$locale->get('mail_text2').'</td><td>'.$name.'</td></tr>';
			$msg .= '<tr><td>'.$locale->get('mail_text3').'</td><td>'.get_date().'</td></tr>';
			$msg .= '<tr><td valign="top">'.$locale->get('mail_text4').'</td><td>'.nl2br($message).'</td></tr>';
			$msg .= '</table>';

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
			$mail->send($feedback_mail, $hdrs, $body);

			//ha masolatot kerunk sajat cimre
			if ($copymail->getChecked()) {
				$mail->send($email, $hdrs, $body);
			}

			//"fagyasztjuk" a form-ot
			$form_feedback->freeze();

			header('Location: index.php?success=feedback_send&link=');
			exit;
		}

		$form_feedback->addElement('submit', 'submit', $locale->get('form_submit'), array('class' => 'submit'));
		$form_feedback->addElement('reset',  'reset',  $locale->get('form_reset'),  array('class' => 'reset'));

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_feedback->accept($renderer);

		$tpl->assign('form_feedback', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'feedback';
	}
}

?>