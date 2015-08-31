<?php
// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

include_once $lang_dir.'/modules/forum/'.$_SESSION['site_lang'].'.php';

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('ins', 'unins', 'mod');

//menu azonosito vizsgalata
if (isset($_GET['mid'])) {
	$menu_id = intval($_GET['mid']);
}

/**
 * ha telepitjuk a modult
 */
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Forum_Configs` (
			`admin_addtopic` CHAR( 1 ) NOT NULL DEFAULT '1',
			`captcha` CHAR( 1 ) NOT NULL DEFAULT '1' ,
			`flood` CHAR( 1 ) NOT NULL DEFAULT '1',
			`flood_time` INT NOT NULL DEFAULT '60'
		);
	";
	$mdb2->exec($query);

	//ha nem ures, akkor beszurunk egy sort
	$query = "
		SELECT * FROM iShark_Forum_Configs
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$query = "
			INSERT INTO iShark_Forum_Configs 
			(admin_addtopic, captcha, flood, flood_time) 
			VALUES 
			('1', '0', '1', '60')
		";
		$mdb2->exec($query);
	}

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Forum_Topics` (
			`topic_id` INT NOT NULL AUTO_INCREMENT ,
			`topic_name` VARCHAR(255) NOT NULL DEFAULT '',
			`topic_subject` VARCHAR(255) NOT NULL DEFAULT '',
			`last_message_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`last_user_id` INT,
			`count_visited` INT NOT NULL DEFAULT '0',
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`is_sticky` CHAR( 1 ) NOT NULL DEFAULT '0',
			`is_active` CHAR( 1 ) NOT NULL DEFAULT '0', 
			`is_deleted` CHAR( 1 ) NOT NULL DEFAULT '0',
			`write_everybody` CHAR( 1 ) NOT NULL DEFAULT '0',
			`read_everybody` CHAR( 1 ) NOT NULL DEFAULT '1',
			`default_blocked` CHAR ( 1 )  NOT NULL DEFAULT '0',
		PRIMARY KEY  (`topic_id`)
		)
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Forum_Messages` (
			`topic_id` INT NOT NULL,
			`message_id` INT NOT NULL AUTO_INCREMENT,
			`subject` VARCHAR(255) NOT NULL DEFAULT '',
			`message` TEXT,
			`re_message_id` INT NOT NULL DEFAULT '0',
			`add_user_id` INT NOT NULL DEFAULT '0',
			`add_user_name` VARCHAR(255) NOT NULL DEFAULT '',
			`add_user_email` VARCHAR(255) NOT NULL DEFAULT '',
			`add_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`ip` VARCHAR(15),
			`is_blocked` CHAR(1) NOT NULL DEFAULT '0',
		PRIMARY KEY (`topic_id`, `message_id`),
		KEY ip_key (`ip`),
		KEY re_message_key (`re_message_id`)
		)
	";

	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS 'iShark_Forum_Censor` (
		   `cens_id` int(10) unsigned NOT NULL auto_increment,
		   `word` varchar(255) NOT NULL default '',
	    PRIMARY KEY  (`cens_id`)
		)";
	$mdb2->exec($query);
	//loggolas
	logger('ins', $menu_id);

	header('Location: admin.php?mid='.$menu_id);
	exit;
}

/**
 * ha toroljuk a modult
 */
/*if (isset($_REQUEST['act']) && $_REQUEST['act'] == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Forum_Configs`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Forum_Topics`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Forum_Messages`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Forum_Censor`
	";
	$mdb2->exec($query);
	//loggolas
	logger('unins', $menu_id);

	header('Location: admin.php?mid='.$menu_id);
	exit;
}
*/
//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, $menu_id, 1)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $strErrorPermission);
	return;
}

/**
 * ha modositjuk
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_guestbook', 'post', 'admin.php?mid='.$menu_id.'&file='.$_GET['file']);

	$form->setRequiredNote($strAdminForumRequired);

	$form->addElement('header', $strAdminForumHeader);
	$form->addElement('hidden', 'act', 'mod');
	$radio1 = array();
	$radio1[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminForumYes, '1');
	$radio1[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminForumNo, '0');
	$form->addGroup($radio1, 'admin_addtopic', $strAdminForumAddTopicGrant);
	// ----
	$captcha = array();
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminForumYes, '1');
	$captcha[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminForumNo, '0');
	$form->addGroup($captcha, 'captcha', $strAdminForumCaptcha);
	$flood = array();
	$flood[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminForumYes, '1');
	$flood[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminForumNo, '0');
	$form->addGroup($flood, 'flood', $strAdminForumFlood);
	$form->addElement('text', 'flood_time', $strAdminForumFloodtime);

	//lekerdezzuk a guestbook config tablat es beallitjuk alapertelmezettnek
	$query = "
		SELECT * 
		FROM iShark_Forum_Configs
	";
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$form->setDefaults($row);
	}

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('admin_addtopic', $strAdminForumErrorAddTopicGrant, 'required');
	$form->addRule('captcha', $strAdminForumErrorCaptcha, 'required');
	$form->addRule('flood', $strAdminForumErrorFlood, 'required');
	$form->addRule('flood_time', $strAdminForumErrorFloodtime1, 'required');
	$form->addRule('flood_time', $strAdminForumErrorFloodtime2, 'numeric');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$admin_addtopic = (int) $form->getSubmitValue('admin_addtopic');
		$captcha   = intval($form->getSubmitValue('captcha'));
		$flood     = intval($form->getSubmitValue('flood'));
		$flood_time = intval($form->getSubmitValue('flood_time'));

		$query = "
			UPDATE iShark_Forum_Configs 
			SET admin_addtopic = '$admin_addtopic', captcha = '$captcha', 
				flood = '$flood', flood_time = '$flood_time'
		";
		$mdb2->exec($query);

		//loggolas
		logger($act, $menu_id);

		$form->freeze();

		header('Location: admin.php?mid='.$menu_id);
		exit;
	}

	$form->addElement('submit', 'submit', $strAdminGuestbookSubmit);
	$form->addElement('reset', 'reset', $strAdminGuestbookReset);

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_form', ob_get_contents());
	ob_end_clean();

	$lang = array(
		'strAdminHeader' => $strAdminForumHeader
	);

	//a file-hoz tartozo nyelvi valtozok atadasa a template-nek
	$tpl->assign('lang', $lang);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

?>
