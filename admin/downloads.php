<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

$module_name = "downloads";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'downloads' => $locale->get('tabs_title')
);

$acts = array(
    'downloads' => array('lst', 'add', 'mod', 'upl', 'act', 'del', 'ftp')
);

//aktualis ful beallitasa
$page = 'downloads';
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

//modulhoz tartozo beallitasok lekerdezese
$query = "
	SELECT c.is_ftpdir AS isftp, c.ftpdir AS fdir, c.downdir AS ddir, c.maxdir AS mdir, c.allow_filetypes AS types, 
		c.maxsize AS msize 
	FROM iShark_Configs c
";
$result = $mdb2->query($query);
while ($row = $result->fetchRow())
{
	$isftp = $row['isftp'];
	$fdir  = $row['fdir'];
	$ddir  = $row['ddir'];
	$mdir  = $row['mdir'];
	$types = $row['types'];
	$msize = $row['msize'];
}

require_once $include_dir.'/function.downloads.php';

if (isset($_REQUEST['parent'])) {
	$parent = intval($_REQUEST['parent']);
} else {
	$parent = 0;
}

$dir = get_aktdir($parent);

$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

/**
 * ha telepitjuk a modult
 */
/*if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Downloads` (
			`download_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 255 ) NOT NULL ,
			`realname` VARCHAR( 255 ) NOT NULL ,
			`description` TEXT NOT NULL ,
			`size` INT NOT NULL ,
			`type` ENUM( 'D', 'F') ,
			`parent` INT NOT NULL ,
			`amount` INT NOT NULL DEFAULT '0' ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL ,
			`timer_start` DATETIME NOT NULL ,
			`timer_end` DATETIME NOT NULL ,
		INDEX ( `download_id` , `add_user_id` , `mod_user_id` )
		);
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}*/

/**
 * ha toroljuk a modult
 */
/*if ($act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Downloads`
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}*/

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$titles = array('add' => $locale->get('main_title_add'), 'mod' => $locale->get('main_title_mod'));

	$form =& new HTML_QuickForm('frm_downloads', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('main_form_required_note'));

	$form->addElement('header', 'downloads', $locale->get('main_form_header'));
	$form->addElement('hidden', 'parent',    $parent);

	//mappa neve
	$form->addElement('text', 'dirname', $locale->get('main_field_dirname'));

	//leiras
	$form->addElement('textarea', 'desc', $locale->get('main_field_description'));

	$form->addElement('submit', 'submit', $locale->get('main_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('main_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('dirname', $locale->get('main_error_dirname'), 'required');

	/**
	 * uj mappa felvitele
	 */
	if ($sub_act == "add") {
	    //form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);

		//megvizsgaljuk, hogy milyen melysegig hozhat letre almappakat
		if ($mdir != 0 && $dir['szint'] >= $mdir) {
			header('Location: admin.php?mid='.$menu_id);
			exit;
		}

		//szabalyok hozzadasa - csak hozzaadasnal
		$form->addFormRule('check_adddownloads');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$dirname = $form->getSubmitValue('dirname');
			$desc    = $form->getSubmitValue('desc');

			$download_id = $mdb2->extended->getBeforeID('iShark_Downloads', 'download_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Downloads 
				(download_id, name, description, type, parent, add_user_id, add_date, mod_user_id, mod_date, is_active) 
				VALUES 
				($download_id, '".$dirname."', '".$desc."', 'D', '$parent', ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW(), '1')
			";
			$mdb2->exec($query);

			//loggolas
			logger($sub_act, '', '');

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
			exit;
		}
	} //mappa felvitel vege

	/**
	 * mappa modositas
	 */
	if ($sub_act == "mod") {
		$did = intval($_REQUEST['did']);

		//form-hoz elemek hozzaadasa - csak modositasnal
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);
		$form->addElement('hidden', 'did',     $did);

		//lekerdezzuk a letoltesek tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT name, description 
			FROM iShark_Downloads 
			WHERE download_id = $did
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$form->setDefaults(array(
					'dirname' => $row['name'],
					'desc'    => $row['description']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name);
			exit;
		}

		$form->addFormRule('check_moddownloads');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name = $form->getSubmitValue('dirname');
			$desc = $form->getSubmitValue('desc');

			$query = "
				UPDATE iShark_Downloads 
				SET name        = '".$name."', 
					description = '".$desc."', 
					mod_user_id = ".$_SESSION['user_id'].", 
					mod_date    = NOW() 
				WHERE download_id = $did
			";
			$mdb2->exec($query);

			//loggolas
			logger($sub_act, '', '');

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
			exit;
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	$tpl->assign('act_dir',    $dir['dir']);
	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('form',       $renderer->toArray());

	//capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'downloads_add';
}

/**
 * ha uj file-t toltunk fel
 */
if ($sub_act == "upl") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_downloads', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('upload_form_required_note'));

	$form->addElement('header', 'upload',  $locale->get('upload_form_header'));
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);
	$form->addElement('hidden', 'parent',  $parent);

	//leiras
	$form->addElement('textarea', 'desc', $locale->get('upload_field_description'));

	//feltoltendo file
	$file =& $form->addElement('file', 'downfile', $locale->get('upload_field_file'));

	$form->addElement('submit', 'submit', $locale->get('upload_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('upload_form_reset'),  'class="reset"');

	$form->addRule('downfile', $locale->get('upload_error_no_file'), 'uploadedfile');
	if ($msize != 0) {
		$form->addRule('downfile', $locale->get('upload_error_size'), 'maxfilesize', $msize);
	}

	if ($form->validate()) {
		if ($file->isUploadedFile()) {
			$filevalues = $file->getValue();

			//megnezzuk a file tipusat
			$is_type_ok = 0;
			if ($types != "") {
				$types     = str_replace(" ", "", $types);
				$filetypes = explode(',', $types);
				$extension = strtolower(strrchr($filevalues['name'],"."));
				if (in_array($extension, $filetypes)) {
					$is_type_ok = 1;
				} else {
					$is_type_ok = 0;
				}
			} else {
				$is_type_ok = 1;
			}

			if ($is_type_ok == 1) {
				$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
				if ($file->moveUploadedFile($ddir, $filename)) {
					@chmod($ddir.$filename, 0664);
					$name = $filevalues['name'];
					$size = $filevalues['size'];
					$desc = $form->getSubmitValue('desc');

					$download_id = $mdb2->extended->getBeforeID('iShark_Downloads', 'download_id', TRUE, TRUE);
					$query = "
						INSERT INTO iShark_Downloads 
						(download_id, name, realname, description, size, type, parent, add_user_id, add_date, 
							mod_user_id, mod_date, is_active) 
						VALUES 
						($download_id, '".$name."', '".$filename."', '".$desc."', '$size', 'F', '$parent', '".$_SESSION['user_id']."', NOW(), 
							'".$_SESSION['user_id']."', NOW(), '1')
					";
					$mdb2->exec($query);

					//loggolas
					logger($sub_act, '', '');

					$form->freeze();

					header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
					exit;
				} else {
					$form->setElementError('downfile', $locale->get('upload_error_upload'));
				}
			} else {
				$form->setElementError('downfile', $locale->get('upload_error_type'));
			}
		} else {
			$form->setElementError('downfile', $locale->get('upload_error_upload'));
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('act_dir',    $dir['dir']);
	$tpl->assign('lang_title', $locale->get('upload_title_upload'));

	//breadcrumb
	$breadcrumb->add($locale->get('upload_breadcrumb_upload'), '#');

	//capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'downloads_up';
} //feltoltes vege

/**
 * ha aktivaljuk vagy deaktivaljuk
 */
if ($sub_act == "act") {
	$did = intval($_REQUEST['did']);

	$query = "
		SELECT * 
		FROM iShark_Downloads 
		WHERE download_id = $did
	";
	$result =& $mdb2->query($query);

	if (!($sor = $result->fetchRow())) {
		header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
		exit;
	}

	$active = '1';
	if ($sor['is_active'] == '1') {
		$active = '0';
	}
	set_active_r($sor['download_id'], $active);

	//loggolas
	logger($sub_act, '', '');

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
	exit;
} //aktivalas, deaktivalas vege

/**
 * ha torlunk
 */
if ($sub_act == "del") {
	$did = intval($_REQUEST['did']);

	delete($did);

	//loggolas
	logger($sub_act, '', '');

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
	exit;
} //torles vege

/**
 * ha az ftp feltoltest vegezzuk
 */
if ($sub_act == "ftp" && $isftp == 1) {
	$javascripts[] = "javascripts.downloads";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_downloads', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('ftp_form_required_note'));

	$form->addElement('header',   'ftp',     $locale->get('ftp_form_header'));
	$form->addElement('hidden',   'act',     $page);
	$form->addElement('hidden',   'sub_act', $sub_act);
	$form->addElement('hidden',   'parent',  $parent);

	//checkbox kijeloles
	$form->addElement('checkbox', 'all', null, $locale->get('ftp_field_checkall'), 'onclick=doNow()');

	$form->addElement('submit', 'submit', $locale->get('ftp_form_submit_copy'), 'class="submit"');

	$dirlist = get_ftpdir($fdir);

	if ($form->validate()) {
		if (isset($_POST['fileChecked']) && is_array($_POST['fileChecked'])) {
			foreach ($_POST['fileChecked'] as $key => $value) {
				//$error = array();

				if (get_magic_quotes_gpc()) {
					$file = stripslashes($value);
				} else {
					$file = $value;
				}

				$filename = time().preg_replace('|[^\w\d_\.]|', '_', change_hunchar($file));
				if (!@copy($fdir.$file, $ddir.$filename)) {
					//$error[] = $file;
                	//echo "Nem sikerult a kovetkezo file-t atmasolni: $file<br>";
				} else {
					@chmod($ddir.$filename, 0664);
					$name = $file;
					$size = filesize($ddir.$filename);

					$download_id = $mdb2->extended->getBeforeID('iShark_Downloads', 'download_id', TRUE, TRUE);
					$query = "
						INSERT INTO iShark_Downloads 
						(download_id, name, realname, size, type, parent, add_user_id, add_date, 
						mod_user_id, mod_date, is_active) 
						VALUES 
						($download_id, '".$name."', '".$filename."', '$size', 'F', '$parent', '".$_SESSION['user_id']."', NOW(), 
						'".$_SESSION['user_id']."', NOW(), '1')
					";
					$mdb2->exec($query);

					@unlink($fdir.$file);
				}
			}
			$form->freeze();

			//loggolas
			logger($sub_act, '', '');

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&parent='.$parent);
			exit;
		} else {
			$form->setElementError('all', $locale->get('ftp_error_checked'));
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('act_dir',    $dir['dir']);
	$tpl->assign('dirlist',    $dirlist);
	$tpl->assign('lang_title', $locale->get('ftp_title_ftp'));

	//capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'downloads_ftp';
} //ftp feltoltes vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	include_once $include_dir.'/function.downloads.php';

	$dir = get_aktdir($parent);

	//ha hozhat meg letre almappat
	if ($mdir == 0 || $dir['szint'] < $mdir) {
		$subdir = 1;
	} else {
		$subdir = 0;
	}

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=upl&amp;parent='.$parent,
			'title' => $locale->get('upload_title_upload'),
			'pic'   => 'upload.jpg'
		),
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;parent='.$parent,
			'title' => $locale->get('main_title_add'),
			'pic'   => 'add_dir.jpg'
		),
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=ftp&amp;parent='.$parent,
			'title' => $locale->get('ftp_title_ftp'),
			'pic'   => 'ftpupload.jpg'
		)
	);
	if ($isftp != 1) {
		unset($add_new[2]);
	}
	if ($subdir != 1) {
		unset($add_new[1]);
	}
	$add_new = array_values($add_new);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('dirlist',    filelist($dir['dir'], 'name', $parent));
	$tpl->assign('dirsumsize', get_dirsumsize());
	$tpl->assign('add_new',    $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'downloads_list';
}

?>
