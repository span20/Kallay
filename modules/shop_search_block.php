<?php

//modul neve
$module_name = "shop";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//szukseges fuggvenykonyvtarak betoltese
require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

//elinditjuk a form-ot - cimek
$form_search_block =& new HTML_QuickForm('frm_searchblock', 'post', 'index.php?p=shop&act=ser');

//a szukseges szoveget jelzo resz beallitasa
$form_search_block->setRequiredNote($locale->get('block_search_form_required_note'));

$form_search_block->addElement('header', 'search', $locale->get('block_search_form_header'));

//keresett szoveg
$form_search_block->addElement('text', 'searchtext', $locale->get('block_search_field_text'));

$form_search_block->addElement('submit', 'submit', $locale->get('block_search_form_submit'), array('class' => 'submit'));

$form_search_block->applyFilter('__ALL__', 'trim');

$form_search_block->addRule('searchtext', $locale->get('block_search_error_empty'), 'required');

$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
$form_search_block->accept($renderer);

$tpl->assign('form_search_block', $renderer->toArray());

// capture the array stucture
ob_start();
print_r($renderer->toArray());
$tpl->assign('static_array', ob_get_contents());
ob_end_clean();

//megadjuk a tpl file nevet, amit atadunk az index.php-nek
$acttpl = 'shop_search_block';

?>