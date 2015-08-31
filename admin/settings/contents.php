<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "contents";

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

	//ha lehet ajanlani a tartalmat masoknak
	if (isModule('recommend', 'index') && !empty($_SESSION['site_cnt_is_send'])) {
		$send_reg = array();
		$send_reg[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
		$send_reg[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
		$form->addGroup($send_reg, 'sendreg', $locale->get('settings_field_sendreg'));
	}

	//lekerdezzuk a guestbook config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Contents_Configs
	";
	$is_empty = 0;
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults(array(
			'sendreg'   => $row['is_send_reg']
			)
		);
		$is_empty = 1;
	}

	$form->applyFilter('__ALL__', 'trim');

	//ha lehet ajanlani a tartalmat masoknak
	if (!empty($_SESSION['site_cnt_is_send'])) {
		$form->addRule('sendreg', $locale->get('settings_error_sendreg'), 'required');
	}

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$sendreg   = intval($form->getSubmitValue('sendreg'));

		if ($is_empty == 1) {
			$query = "
				UPDATE iShark_Contents_Configs 
				SET is_send_reg    = '$sendreg'
			";
		} else {
			$query = "
				INSERT INTO iShark_Contents_Configs 
				(is_send_reg) 
				VALUES 
				('$sendreg')
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
