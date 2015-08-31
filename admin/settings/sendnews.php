<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "sendnews";

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
	$tpl->assign('errormsg', $locale->get('settings_error_no_permission'));
	return;
}

/**
 * ha modositjuk
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_contents', 'post', 'admin.php?p=settings&file='.$_GET['file']);

	//breadcrumb
	$breadcrumb->add($locale->get('settings_form_settings_header'), 'admin.php?p=settings&amp;file='.$_GET['file'].'&amp;act=mod');

	$form->setRequiredNote($locale->get('settings_form_required_note'));

	$form->addElement('header', 'settings', $locale->get('settings_form_header'));
	$form->addElement('hidden', 'act',      'mod');
	
	//csak regisztrált felhasználó küldhet be
	$is_reg =& $form->addElement('checkbox', 'is_reg', $locale->get('settings_form_is_reg'));

	//csak admin engedéllyel kerülhet ki
	$is_admin =& $form->addElement('checkbox', 'is_admin', $locale->get('settings_form_is_admin'));

	//ha már van kint cikke, akkor mehet-e egybõl
	$is_civil =& $form->addElement('checkbox', 'is_check', $locale->get('settings_form_notcheck'));

	//ennyi cikk után akkor mehet egybõl
	$is_check_num =& $form->addElement('text', 'is_check_num', $locale->get('settings_form_notchecknum'));

	//lekerdezzuk a sendnews config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Sendnews_Configs
	";
	$is_empty = 0;
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults(array(
			'is_reg'       => $row['is_reg'],
			'is_admin'     => $row['is_admin'],
			'is_check'     => $row['is_check'],
			'is_check_num' => $row['is_check_num']
			)
		);
		$is_empty = 1;
	}

	$form->applyFilter('__ALL__', 'trim');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$isreg      = intval($form->getSubmitValue('is_reg'));
		$isadmin    = intval($form->getSubmitValue('is_admin'));
		$ischeck    = intval($form->getSubmitValue('is_check'));
		$ischecknum = intval($form->getSubmitValue('is_check_num'));

		if ($is_empty == 1) {
			$query = "
				UPDATE iShark_Sendnews_Configs 
				SET is_reg        = '$isreg', 
					is_admin      = '$isadmin', 
					is_check      = '$ischeck',
					is_check_num  = '$ischecknum'
			";
		} else {
			$query = "
				INSERT INTO iShark_Sendnews_Configs 
				(is_reg, is_admin, is_check, is_check_num) 
				VALUES 
				('$isreg', '$isadmin', '$ischeck', '$ischecknum')
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
