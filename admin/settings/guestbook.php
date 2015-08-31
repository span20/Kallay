<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "guestbook";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod');

$menu_id=0;
//menu azonosito vizsgalata
if (isset($_GET['mid'])) {
	$menu_id = intval($_GET['mid']);
}

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, $menu_id, 1, 'settings')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_permission'));
	return;
}

//breadcrumb
$breadcrumb->add($locale->get('settings_form_header'), 'admin.php?p=settings&amp;file='.$_GET['file'].'&amp;act=mod');

/**
 * ha modositjuk
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_guestbook', 'post', 'admin.php?p=settings&file='.$_GET['file']);

	$form->setRequiredNote($locale->get('settings_form_required_note'));

	$form->addElement('header', 'guestbook', $locale->get('settings_form_header'));
	$form->addElement('hidden', 'act',       $act);

	//uzenetek csak admin engedellyel
	$grant = array();
	$grant[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$grant[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($grant, 'grant', $locale->get('settings_form_grant'));

	//csak regisztralt felhasznalo irhat
	$is_reg = array();
	$is_reg[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$is_reg[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($is_reg, 'isreg', $locale->get('settings_form_isreg'));

	//captcha hasznalata
	$captcha = array();
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($captcha, 'captcha', $locale->get('settings_form_captcha'));

	//email kuldese
	$is_mail = array();
	$is_mail[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$is_mail[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($is_mail, 'ismail', $locale->get('settings_form_ismail'));

	//email cim
	$form->addElement('text', 'gmail', $locale->get('settings_form_tomail'));

	//flood figyelese
	$flood = array();
	$flood[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$flood[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($flood, 'flood', $locale->get('settings_form_flood'));

	//flood ido
	$form->addElement('text', 'floodtime', $locale->get('settings_form_floodtime'));

	//lekerdezzuk a guestbook config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Guestbook_Configs
	";
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults(array(
			'grant'     => $row['is_admin_grant'],
			'isreg'     => $row['is_user_reg'],
			'ismail'    => $row['is_mail'],
			'gmail'     => $row['email'],
			'captcha'   => $row['captcha'],
			'flood'     => $row['flood'],
			'floodtime' => $row['flood_time']
			)
		);
	}

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('grant',   $locale->get('settings_error_grant'),   'required');
	$form->addRule('isreg',   $locale->get('settings_error_isreg'),   'required');
	$form->addRule('captcha', $locale->get('settings_error_captcha'), 'required');
	$form->addRule('ismail',  $locale->get('settings_error_ismail'),  'required');
	$form->addRule('gmail',   $locale->get('settings_error_tomail'),  'email');
	$form->addRule('flood',   $locale->get('settings_error_flood'),   'required');
	if ($form->isSubmitted() && $form->getSubmitValue('flood') == 1) {
		$form->addRule('floodtime', $locale->get('settings_error_floodtime1'), 'required');
		$form->addRule('floodtime', $locale->get('settings_error_floodtime2'), 'numeric');
		$form->addRule('floodtime', $locale->get('settings_error_floodtime3'), 'nonzero');
	}

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$grant     = intval($form->getSubmitValue('grant'));
		$userreg   = intval($form->getSubmitValue('isreg'));
		$ismail    = intval($form->getSubmitValue('ismail'));
		$gmail     = $form->getSubmitValue('gmail');
		$captcha   = intval($form->getSubmitValue('captcha'));
		$flood     = intval($form->getSubmitValue('flood'));
		$floodtime = intval($form->getSubmitValue('floodtime'));

		$query = "
			UPDATE iShark_Guestbook_Configs 
			SET is_admin_grant = '$grant', 
				is_user_reg    = '$userreg', 
				is_mail        = '$ismail', 
				email          = '".$gmail."', 
				captcha        = '$captcha', 
				flood          = '$flood', 
				flood_time     = '$floodtime'
		";
		$mdb2->exec($query);

		//loggolas
		logger($act, 0);

		$form->freeze();

		header('Location: admin.php?p=settings');
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('settings_form_submit'), array('class' => 'submit'));
	$form->addElement('reset',  'reset',  $locale->get('settings_form_reset'),  array('class' => 'reset'));

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_form', ob_get_contents());
	ob_end_clean();

	//a file-hoz tartozo nyelvi valtozok atadasa a template-nek
	$tpl->assign('lang_title', $locale->get('settings_form_header'));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

?>