<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "classifieds";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod');

$menu_id = 0;
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
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

/**
 *ha modositjuk a beallitasokat
 */
if ($act == "mod") {
	$max_shipping = 5;

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_shop', 'post', 'admin.php?p=settings&file='.$_GET['file']);

	$form->setRequiredNote($locale->get('settings_form_required_note'));

	$form->addElement('header',        $locale->get('settings_form_header'));
	$form->addElement('hidden', 'act', $act);

	//e-mail cim, amirol megy a level
	$form->addElement('text', 'classmail', $locale->get('settings_field_settings_mail'));

	//csak regisztralt felhasznalo ertekelheti-e a termeket
	$regclass = array();
	$regclass[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$regclass[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($regclass, 'regclass', $locale->get('settings_field_settings_regclass'));

	//captcha hasznalata
	$captcha = array();
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($captcha, 'captcha', $locale->get('settings_field_settings_captcha'));

	//flood figyelese
	$flood = array();
	$flood[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
	$flood[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
	$form->addGroup($flood, 'flood', $locale->get('settings_field_settings_flood'));

	//flood figyelesnel ket hirdetes kozotti ido
	$form->addElement('text', 'floodtime', $locale->get('settings_field_settings_floodtime'));

	$form->addElement('submit', 'submit', $locale->get('settings_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('settings_form_reset'),  'class="reset"');

	//lekerdezzuk a shop config tablat
	$is_empty = 1;
	$query = "
		SELECT class_mail, class_reguser, class_captcha, class_flood, class_floodtime 
		FROM iShark_Classifieds_Configs
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$is_empty = 0;
		$row = $result->fetchRow();

		$form->setDefaults(array(
			'classmail' => $row['class_mail'],
			'regclass'  => $row['class_reguser'],
			'captcha'   => $row['class_captcha'],
			'flood'     => $row['class_flood'],
			'floodtime' => $row['class_floodtime']
			)
		);
	} else {
		$form->setDefaults(array(
			'regclass' => 1,
			'captcha'  => 0,
			'flood'    => 0
			)
		);
	}

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('classmail', $locale->get('settings_error_settings_classmail'), 'required');
	$form->addRule('regclass',  $locale->get('settings_error_settings_reguser'),   'required');
	$form->addRule('captcha',   $locale->get('settings_error_settings_captcha'),   'required');
	$form->addRule('flood',     $locale->get('settings_error_settings_flood'),     'required');
	if ($form->isSubmitted() && $form->getSubmitValue('flood') == 1) {
		$form->addRule('floodtime', $locale->get('settings_error_settings_floodtime'),  'required');
		$form->addRule('floodtime', $locale->get('settings_error_settings_floodtime2'), 'numeric');
		$form->addRule('floodtime', $locale->get('settings_error_settings_floodtime3'), 'nonzero');
	}

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$classmail = $form->getSubmitValue('classmail');
		$regclass  = intval($form->getSubmitValue('regclass'));
		$captcha   = intval($form->getSubmitValue('captcha'));
		$flood     = intval($form->getSubmitValue('flood'));
		$floodtime = intval($form->getSubmitValue('floodtime'));

		if ($is_empty == 1) {
			$query = "
				INSERT INTO iShark_Classifieds_Configs 
				(class_mail, class_reguser, class_captcha, class_flood, class_floodtime) 
				VALUES 
				('$classmail', '$regclass', '$captcha', '$flood', $floodtime)
			";
		} else {
			$query = "
				UPDATE iShark_Classifieds_Configs 
				SET class_mail      = '$classmail',
					class_reguser   = '$regclass',
					class_captcha   = '$captcha',
					class_flood     = '$flood',
					class_floodtime = $floodtime
			";
		}
		$mdb2->exec($query);

		//loggolas
		logger($act, $menu_id);

		$form->freeze();

		header('Location: admin.php?p=settings');
		exit;
	}

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_form', ob_get_contents());
	ob_end_clean();

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('lang_title', $locale->get('title_settings'));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

?>