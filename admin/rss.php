<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "rss";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act  = array('add', 'mod', 'del', 'lst', 'act', 'ins', 'unins');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 1, $module_name)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('admin', 'permission_denied'));
	return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);

/**
 * ha telepitjuk a modult
 */
if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Rss` (
		`rss_id` INT NOT NULL AUTO_INCREMENT ,
		`rss_name` VARCHAR( 255 ) NOT NULL ,
		`description` VARCHAR( 255 ) NOT NULL ,
		`url` VARCHAR( 255 ) NOT NULL ,
		`add_user_id` INT NOT NULL ,
		`add_date` DATETIME NOT NULL ,
		`mod_user_id` INT NOT NULL ,
		`mod_date` DATETIME NOT NULL ,
		`is_active` CHAR( 1 ) NOT NULL ,
		`lang` VARCHAR( 10 ) NOT NULL ,
	PRIMARY KEY ( `rss_id` ) ,
	INDEX ( `add_user_id` , `mod_user_id` )
	);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Rss_Feeds` (
		`rss_feed_id` INT NOT NULL AUTO_INCREMENT ,
		`rss_name` VARCHAR( 255 ) NOT NULL ,
		`file_name` VARCHAR( 255 ) NOT NULL ,
		`module_name` VARCHAR( 255 ) NOT NULL ,
	PRIMARY KEY ( `rss_feed_id` )	
	);
	";
	$mdb2->exec($query);

	$query = "
		INSERT INTO iShark_Rss_Feeds
		(rss_name, file_name, module_name)
		values
		('Tartalmak', 'rss_contents.php', 'contents')
	";
	$mdb2->exec($query);

	$query = "
		INSERT INTO iShark_Rss_Feeds
		(rss_name, file_name, module_name)
		values
		('Hírek', 'rss_news.php', 'news')
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha toroljuk a modult
 */
if ($act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Rss`;
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Rss_Feeds`;
	";
	$mdb2->exec($query);

	//loggolas
	logger('unins', '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}


/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($act == "add" || $act == "mod") {
	$javascripts[] = "javascripts";	
	$javascripts[] = "javascript.contents";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_rss', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', $locale->get('form_header'));

	//ha tobb nyelvu az oldal, akkor kilistazzuk a nyelveket
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		include_once $include_dir.'/functions.php';
		$form->addElement('select', 'languages', $locale->get('form_lang'), directory_list($lang_dir, 'php', array(), 1));
	}
	$form->addElement('text', 'name', $locale->get('form_name'));

	$desc =& $form->addElement('textarea', 'desc', $locale->get('form_desc'), 'onKeyDown="textCounter(\'desc_field\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" onKeyUp="textCounter(\'desc_field\',\'lengthfield\',\''.$_SESSION['site_leadmax'].'\');" id="desc_field"');
	$desc->setCols(60);
	$desc->setRows(7);
	$form->addElement('text', 'desc_len', $locale->get('form_desclen'), array('size' => 5, 'id' => 'lengthfield', 'readonly' => 'readonly'));

	//kirakunk egy ures option-t az elejere
	$empty_array = array('' => '');
	$cats_array  = array();

	//lekerdezzuk, hogy milyen rss fájlok vannak
	if(isModule('contents')){
		if(!empty($_SESSION['site_is_news']) && file_exists('modules/rss_news.php')){
			$where[] = "module_name='news'";

			//ha hasznaljuk a kategoriakat, akkor azokat kulon is kiemeljuk
			if (!empty($_SESSION['site_category'])) {
				$query_cats = "
					SELECT category_id, category_name 
					FROM iShark_Category 
					WHERE is_active = 1 AND is_deleted = 0 
					ORDER BY category_name
				";
				$result_cats =& $mdb2->query($query_cats);
				while ($row_cats = $result_cats->fetchRow())
				{
					$cats_array['rss_news.php?cat='.$row_cats['category_id']] = $row_cats['category_name'];
				}
			}
		}
		if(!empty($_SESSION['site_is_other']) && file_exists('modules/rss_contents.php')){
			$where[] = "module_name='contents'";
		}
	}
	if(!empty($where)){
		$where = implode(" or ", $where);
		$query = "
			SELECT file_name AS fname,rss_name AS rname 
			FROM iShark_Rss_Feeds
			WHERE $where
			ORDER BY rss_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'rss_feed', $locale->get('form_feeds'), $empty_array + $cats_array + $result->fetchAll('', $rekey = true));
	}

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	//ha tobbnyelvu az oldal
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		$form->addRule('languages', $locale->get('error_required_language'), 'required');
	}

	$form->addRule('name',     $locale->get('error_required_name'), 'required');
	$form->addRule('desc', 	   $locale->get('error_required_desc'), 'required');
	$form->addRule('rss_feed', $locale->get('error_required_feed'), 'required');

	/**
	 * ha uj menupontot adunk hozza
	 */
	if ($act == "add") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act', 'add');

		//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
		$form->setDefaults(array(
			'languages' => $_SESSION['site_deflang'],
			'desc_len'  => $_SESSION['site_leadmax']
			)
		);

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name = $form->getSubmitValue('name');
			$desc = $form->getSubmitValue('desc');
			$feed = $form->getSubmitValue('rss_feed');

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
				$languages = $form->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			$query = "
				INSERT INTO iShark_Rss 
				(rss_name, description, url, add_user_id, add_date,	mod_user_id, mod_date, is_active, lang) 
				VALUES 
				('".$name."', '$desc', '$feed', ".$_SESSION['user_id'].", NOW(), 
				".$_SESSION['user_id'].", NOW(), '1', '$languages')
			";
			$mdb2->exec($query);

			//loggolas
			logger($act, '', '');

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name);
			exit;
		}
	} //hozzaadas vege

	/**
	 * ha modositunk egy menupontot
	 */
	if ($act == "mod") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		if (isset($_REQUEST['rid']) && is_numeric($_REQUEST['rid'])) {
			$rid = intval($_REQUEST['rid']);

			//form-hoz elemek hozzaadasa - csak modositasnal
			$form->addElement('hidden', 'act', 'mod');
			$form->addElement('hidden', 'rid', $rid);

			//lekerdezzuk a menu tablat, es az eredmenyt beallitjuk alapertelmezettnek
			$query = "
				SELECT * 
				FROM iShark_Rss 
				WHERE rss_id = $rid
			";
			$result = $mdb2->query($query);
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					//beallitjuk az alapertelmezett ertekeket, csak modositasnal
					$form->setDefaults(array(
						'languages' => $row['lang'],
						'name'      => $row['rss_name'],
						'desc'		=> $row['description'],
						'rss_feed' 	=> $row['url'],
						'desc_len'  => $_SESSION['site_leadmax']-strlen($row['description'])
						)
					);
				}
			} else {
				header('Location: admin.php?p='.$module_name);
				exit;
			}

			//ellenorzes, vegso muveletek
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name = $form->getSubmitValue('name');
				$desc = $form->getSubmitValue('desc');
				$feed = $form->getSubmitValue('rss_feed');

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
					$languages = $form->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				$query = "
					UPDATE iShark_Rss 
					SET rss_name    = '".$name."', 
						description = '".$desc."', 
						url         = '".$feed."', 
						mod_user_id = ".$_SESSION['user_id'].", 
						mod_date    = NOW(), 
						lang        = '".$languages."'
					WHERE rss_id = $rid
				";
				$mdb2->exec($query);

				//loggolas
				logger($act, '', '');

				//visszadobjuk a lista oldalra
				header('Location: admin.php?p='.$module_name);
				exit;
			}
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('lang_title',  $titles[$act]);
	$tpl->assign('tiny_fields', "a");
	$tpl->assign('form',        $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha torlunk egy menupontot
 */
if ($act == "del") {
	if (isset($_GET['rid']) && is_numeric($_GET['rid'])) {
		$rid = intval($_GET['rid']);

		$query = "
			DELETE FROM iShark_Rss 
			WHERE rss_id = $rid
		";
		$mdb2->exec($query);

		//loggolas
		logger($act, '', '');
	}

	header('Location: admin.php?p='.$module_name);
	exit;
} //torles vege


/**
 * ha aktivalunk vagy inaktivalunk egy menupontot
 */
if ($act == "act") {
	include_once $include_dir.'/function.check.php';
	$rid = intval($_REQUEST['rid']);

	check_active('iShark_Rss', 'rss_id', $rid);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha nincs semmilyen muvelet, akkor a listat mutatjuk
 */
if ($act == "lst") {
	$query = "
		SELECT *
		FROM iShark_Rss
	";

	include_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act=add',
			'title' => $locale->get('act_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('add_new',   $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'rss_list';
}

?>