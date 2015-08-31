<?php

$locale->useArea('index_account');

//ha nem letezik a session, akkor kirakjuk a bejelentkezo form-ot
if (!isset($_SESSION['user_id'])) {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form_login =& new HTML_QuickForm('login_frm', 'post', 'index.php?p=account&act=account_in');

	$form_login->addElement('header', 'login', $locale->get('block_form_header'));

	//Nev
	$form_login->addElement('text', 'login_email', 'E-mail', 'class="input_box"');

	//Jelszo
	$form_login->addElement('password', 'login_pass', $locale->get('block_form_pass'), 'class="input_box"');

	$form_login->addElement('submit', 'acc_submit', $locale->get('block_button_submit'), 'class="submit"');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

	$form_login->accept($renderer);
	$tpl->assign('form_login', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();
} else {
	//utolso latogatas ideje
	lastvisit();
}

//ha adminisztrator, akkor kirakjuk a linket az adminrendszerhez
if (is_adminlink() === true) {
	$tpl->assign('adminlink', $locale->get('block_link_admin'));
}

//megadjuk a tpl file nevet, amit atadunk az index.php-nek
$acttpl = 'block_account';

?>