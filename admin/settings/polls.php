<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "polls";

//nyelvi file betoltese
$locale->useArea($module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('ins', 'unins', 'mod');

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
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

/**
 *ha modositjuk a beallitasokat
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_polls', 'post', 'admin.php?p=settings&file='.$_GET['file']);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', $locale->get('form_header'));
	$form->addElement('hidden', 'act', $act);

	//captcha hasznalata
	$captcha = array();
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
	$form->addGroup($captcha, 'captcha', $locale->get('field_settings_captcha'));

	//menuponthoz kapcsolhato
	$menu = array();
	$menu[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$menu[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
	$form->addGroup($menu, 'menu', $locale->get('field_settings_menu'));

	//regi szavazasok lathatoak
	$oldpoll = array();
	$oldpoll[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$oldpoll[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
	$form->addGroup($oldpoll, 'oldpoll', $locale->get('field_settings_oldpoll'));

	//nem regisztralt userek szavazasa kozott eltelt ido
	$form->addElement('text', 'reuse', $locale->get('field_settings_reuse'));

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//lekerdezzuk a poll config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Polls_Configs
	";
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults(array(
			'captcha' => $row['captcha'],
			'menu'    => $row['is_menu'],
			'reuse'   => $row['reuse_time'],
			'oldpoll' => $row['oldpoll_view']
			)
		);
	}

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('captcha', $locale->get('error_captcha'), 'required');
	$form->addRule('menu',    $locale->get('error_menu'),    'required');
	$form->addRule('reuse',   $locale->get('error_reuse'),   'required');
	$form->addRule('reuse',   $locale->get('error_reuse2'),  'numeric');
	$form->addRule('oldpoll', $locale->get('error_oldpoll'), 'required');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$captcha = intval($form->getSubmitValue('captcha'));
		$menu    = intval($form->getSubmitValue('menu'));
		$reuse   = intval($form->getSubmitValue('reuse'));
		$oldpoll = intval($form->getSubmitValue('oldpoll'));

		$query = "
			UPDATE iShark_Polls_Configs 
			SET captcha      = '$captcha', 
				is_menu      = '$menu', 
				reuse_time   = '$reuse', 
				oldpoll_view = '$oldpoll'
		";
		$mdb2->exec($query);

		//loggolas
		logger($act, 0);

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

	//a file-hoz tartozo nyelvi valtozok atadasa a template-nek
	$tpl->assign('lang_title', $locale->get('title_settings'));

	$breadcrumb->add($locale->get('form_header'), 'admin.php?p=settings&amp;file='.$_GET['file'].'&amp;act=mod');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

?>
