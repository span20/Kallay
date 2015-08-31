<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

$module_name = "static_editor";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'static_editor' => $locale->get('tabs_title')
);

$acts = array(
    'static_editor' => array('lst', 'mod')
);

//aktualis ful beallitasa
$page = 'static_editor';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//jogosultsag ellenorzes
if (!check_perm($page, 0, 0, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$titles = array('add' => $locale->get('main_title_add'), 'mod' => $locale->get('main_title_mod'));

	$form =& new HTML_QuickForm('frm_static_editor', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('main_form_required_note'));

	$form->addElement('header', 'static_editor', $locale->get('main_form_header'));

	//leiras
	$form->addElement('textarea', 'source', $locale->get('main_field_description'), 'style="width: 700px; height: 500px;"');

	$form->addElement('submit', 'submit', $locale->get('main_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('main_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('source', $locale->get('main_error_desc'), 'required');

    //form-hoz elemek hozzaadasa - csak modositasnal
    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
    $form->addElement('hidden', 'filename', $_REQUEST["filename"]);

    //file betöltése
    $filecontents = file_get_contents($theme_dir."/".$theme."/templates/".$_REQUEST["filename"].".tpl");
    
	$form->setDefaults(array(
		'source' => $filecontents
		)
	);
    
    if ($form->validate()) {
        //$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $source = $form->getSubmitValue('source');
        $filename = $form->getSubmitValue('filename');
                
        $handle = fopen($theme_dir."/".$theme."/templates/".$filename.".tpl", 'w+');
        fwrite($handle, $source);
        fclose($handle);

        //loggolas
        logger($sub_act, '', '');

        $form->freeze();

        header('Location: admin.php?p='.$module_name.'&act='.$page);
        exit;
    }

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('form',       $renderer->toArray());

	//capture the array stucture
	/*ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();*/

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
    
	//atadjuk a smarty-nak a kiirando cuccokat
	$paged_data['data'] = array(
			array(
				'name' => 'Rólunk',
				'filename' => 'rolunk'
				),
			array(
				'name' => 'Szolgáltatások',
				'filename' => 'szolgaltatasok'
				)
		);
	$tpl->assign('page_data',  $paged_data['data']);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'static_editor_list';
}

?>
