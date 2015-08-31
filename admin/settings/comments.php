<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "comments";

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
	$tpl->assign('errormsg', $locale->get('settings_error_permission_denied'));
	return;
}

/**
 * ha modositjuk
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_contents', 'post', 'admin.php?p=settings&file='.$_GET['file']);
	$breadcrumb->add($locale->get('form_settings_header'), 'admin.php?p=settings&amp;file='.$_GET['file'].'&amp;act=mod');

	$form->setRequiredNote($locale->get('settings_form_required_note'));

	$form->addElement('header', $locale->get('settings_form_header'));
	$form->addElement('hidden', 'act', 'mod');

	//ha lehet megjegyzest irni a hirhez
	if (!empty($_SESSION['site_cnt_is_comment_cnt']) || !empty($_SESSION['site_cnt_is_comment_news'])) {
		//regisztralt felhasznalo irhat
		$is_reg = array();
		$is_reg[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
		$is_reg[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
		$form->addGroup($is_reg, 'isreg', $locale->get('settings_field_reguser'));

		//captcha hasznalata
		$captcha = array();
		$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
		$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
		$form->addGroup($captcha, 'captcha', $locale->get('settings_field_captcha'));

		//flood figyelese
		$flood = array();
		$flood[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
		$flood[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
		$form->addGroup($flood, 'flood', $locale->get('settings_field_flood'));
		//ket hozzaszolas kozotti ido
		$form->addElement('text', 'floodtime', $locale->get('settings_field_floodtime'));
	}

	//lekerdezzuk a guestbook config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Comments_Configs
	";
	$is_empty = 0;
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults(array(
			'isreg'     => $row['is_user_reg'],
			'captcha'   => $row['captcha'],
			'flood'     => $row['flood'],
			'floodtime' => $row['flood_time']
			)
		);
		$is_empty = 1;
	}

	$form->applyFilter('__ALL__', 'trim');

	//ha lehet megjegyzest irni a hirhez
	if (!empty($_SESSION['site_cnt_is_comment'])) {
		$form->addRule('isreg',   $locale->get('settings_error_isreg'),   'required');
		$form->addRule('captcha', $locale->get('settings_error_captcha'), 'required');
		$form->addRule('flood',   $locale->get('settings_error_flood'),   'required');
		if ($form->isSubmitted() && $form->getSubmitValue('flood') == 1) {
			$form->addRule('floodtime', $locale->get('settings_error_floodtime1'), 'required');
			$form->addRule('floodtime', $locale->get('settings_error_floodtime2'), 'numeric');
			$form->addRule('floodtime', $locale->get('settings_error_floodtime3'), 'nonzero');
		}
	}

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$userreg   = intval($form->getSubmitValue('isreg'));
		$captcha   = intval($form->getSubmitValue('captcha'));
		$flood     = intval($form->getSubmitValue('flood'));
		$floodtime = intval($form->getSubmitValue('floodtime'));

		if ($is_empty == 1) {
			$query = "
				UPDATE iShark_Comments_Configs 
				SET is_user_reg    = '$userreg', 
					captcha        = '$captcha', 
					flood          = '$flood', 
					flood_time     = '$floodtime'
			";
		} else {
			$query = "
				INSERT INTO iShark_Comments_Configs 
				(is_user_reg, captcha, flood, flood_time) 
				VALUES 
				('$userreg', '$captcha', '$flood', '$floodtime')
			";
		}
		$mdb2->exec($query);

		//loggolas
		logger($act, 0);

		$form->freeze();

		header('Location: admin.php?p=settings');
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('settings_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('settings_form_reset'),  'class="reset"');

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
