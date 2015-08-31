<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "feedback";

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
	$tpl->assign('errormsg', $locale->get('error_permission'));
	return;
}

//breadcrumb
$breadcrumb->add($locale->get('settings_form_header'), 'admin.php?p=settings&amp;file='.$_GET['file'].'&amp;act=mod');

/**
 *ha modositjuk a beallitasokat
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_feedback', 'post', 'admin.php?p=settings&file='.$_GET['file']);

	$form->setRequiredNote($locale->get('settings_form_required_note'));

	$form->addElement('header', 'feedback', $locale->get('settings_form_header'));
	$form->addElement('hidden', 'act',      $act);

	//email cim
	$form->addElement('text', 'feedmail', $locale->get('settings_form_email'));

	$form->addElement('submit', 'submit', $locale->get('settings_form_submit'), array('class' => 'submit'));
	$form->addElement('reset',  'reset',  $locale->get('settings_form_reset'),  array('class' => 'reset'));

	//lekerdezzuk a feedback config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Feedback_Configs
	";
	$az = 0;
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults(array(
			'feedmail' => $row['email']
			)
		);
		$az = 1;
	}

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('feedmail', $locale->get('settings_error_email1'), 'required');
	$form->addRule('feedmail', $locale->get('settings_error_email2'), 'email');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$feedmail = $form->getSubmitValue('feedmail');

		if ($az == 1) {
			$query = "
				UPDATE iShark_Feedback_Configs 
				SET email = '".$feedmail."'
			";
		} else {
			$query = "
				INSERT INTO iShark_Feedback_Configs 
				(email) 
				VALUES 
				('".$feedmail."')
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

	//a file-hoz tartozo nyelvi valtozok atadasa a template-nek
	$tpl->assign('lang_title', $locale->get('settings_form_header'));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

?>
