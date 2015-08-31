<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "recommend";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

if (!empty($_SESSION['site_cnt_is_send'])) {
	if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid']) && isset($_REQUEST['type']) && ($_REQUEST['type'] == 'contents' || $_REQUEST['type'] == 'news')) {
		$cid  = intval($_REQUEST['cid']);
		$type = $_REQUEST['type'];

		//lekerdezzuk a tartalomszerkesztohoz tartozo beallitasokat
		$query_contents_config = "
			SELECT is_send_reg 
			FROM iShark_Contents_Configs
		";
		$result_contents_config =& $mdb2->query($query_contents_config);
		if ($result_contents_config->numRows() > 0) {
			$row_configs = $result_contents_config->fetchRow();
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('error_no_config_table'));
			return;
		}

		//kirakjuk a form-ot
		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		//elinditjuk a form-ot
		$form_recommend =& new HTML_QuickForm('frm_recommend', 'post', 'index.php?p='.$module_name);

		//a szukseges szoveget jelzo resz beallitasa
		$form_recommend->setRequiredNote($locale->get('form_required_note'));

		//form-hoz elemek hozzadasa
		$form_recommend->addElement('header',   'recommend',  $locale->get('form_recommend_header'));
		$form_recommend->addElement('hidden',   'cid',        $cid);
		$form_recommend->addElement('hidden',   'type',       $type);

		//kuldo neve
		$form_recommend->addElement('text', 'sendername', $locale->get('field_recommend_sendername'));

		//kuldo mail cime
		$form_recommend->addElement('text', 'sendermail', $locale->get('field_recommend_sendermail'));

		//cimzett neve
		$form_recommend->addElement('text', 'recipename', $locale->get('field_recommend_recipename'));

		//cimzett mail cime
		$form_recommend->addElement('text', 'recipemail', $locale->get('field_recommend_recipemail'));

		//uzenet
		$form_recommend->addElement('textarea', 'message', $locale->get('field_recommend_message'));

		$form_recommend->applyFilter('__ALL__', 'trim');

		$form_recommend->addRule('sendername', $locale->get('error_recommend_sendername'),  'required');
		$form_recommend->addRule('sendermail', $locale->get('error_recommend_sendermail'),  'required');
		$form_recommend->addRule('sendermail', $locale->get('error_recommend_sendermail2'), 'email');
		$form_recommend->addRule('recipename', $locale->get('error_recommend_recipename'),  'required');
		$form_recommend->addRule('recipemail', $locale->get('error_recommend_recipemail'),  'required');
		$form_recommend->addRule('recipemail', $locale->get('error_recommend_recipemail2'), 'email');

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
					$form_recommend->setDefaults(array(
						'sendername' => $row['uname'],
						'sendermail' => $row['umail']
						)
					);
					//csak olvashatova tesszuk a nev es e-mail mezoket
					$form_recommend->updateElementAttr('sendername', 'readonly');
					$form_recommend->updateElementAttr('sendermail', 'readonly');
				}
			}
		}

		if ($form_recommend->validate()) {
			$sendername = $form_recommend->getSubmitValue('sendername');
			$sendermail = $form_recommend->getSubmitValue('sendermail');
			$recipename = $form_recommend->getSubmitValue('recipename');
			$recipemail = $form_recommend->getSubmitValue('recipemail');
			$message    = strip_tags($form_recommend->getSubmitValue('message'));

			//kiszedjuk az adott tartalomhoz tartozo cimet
			$row_title = array();
			if ($type == 'contents' || $type == 'news') {
				$query_title = "
					SELECT c.title AS title 
					FROM iShark_Contents c 
					WHERE c.content_id = $cid
				";
				//beallitjuk a valtozot, amit var az adott modul (itt tudunk tobb parametert is atadni)
				if ($type == 'contents') {
					$id = 'cid';
				}
				if ($type == 'news') {
					$id = 'act=lst&amp;cid';
				}
			}
			$result_title =& $mdb2->query($query_title);
			if ($result_title->numRows() > 0) {
				$row_title = $result_title->fetchRow();
			} else {
				$row_title['title'] = "";
			}

			//elkuldjuk a levelet
			ini_set('display_errors', 0);
			include_once 'Mail.php';
			include_once 'Mail/mime.php';

			$hdrs = array(
				'From'    => '"'.preg_replace('|"|', '\"', $sendername).'" <'.$sendermail.'>',
				'Subject' => $locale->get('mail_recommend_subject')
			);
			$mime =& new Mail_mime("\n");
			$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			$msg = $locale->get('mail_recommend_text1').' '.$recipename.'!<br /><br />';
			$msg .= $sendername.' '.$locale->get('mail_recommend_text2').' <a href="'.$_SESSION['site_sitehttp'].'" title="'.$_SESSION['site_sitename'].'">'.$_SESSION['site_sitename'].'</a> '.$locale->get('mail_recommend_text3').'<br />';
			$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p='.$type.'&amp;'.$id.'='.$cid.'" title="'.$row_title['title'].'">'.$row_title['title'].'</a><br /><br />';
			$msg .= $sendername.' '.$locale->get('mail_recommend_text4').' '.$message.'<br /><br />';
			$msg .= '<a href="'.$_SESSION['site_sitehttp'].'" title="'.$_SESSION['site_sitename'].'">'.$_SESSION['site_sitename'].'</a>';

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
			$mail->send($recipemail, $hdrs, $body);

			//"fagyasztjuk" a form-ot
			$form_recommend->freeze();

			//$link = 'index.php?p='.$type.'%26'.$id.'='.$cid;
			header('Location: index.php?success=recommend_send&link=index.php?p='.$type.'%26'.$id.'='.$cid);
			exit;
		}

		$form_recommend->addElement('submit', 'submit', $locale->get('form_submit'), array('class' => 'submit'));
		$form_recommend->addElement('reset',  'reset',  $locale->get('form_reset'),  array('class' => 'reset'));

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_recommend->accept($renderer);

		$tpl->assign('form_recommend', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'recommend';
	}
} else {
}

?>
