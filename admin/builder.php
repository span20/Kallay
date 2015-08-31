<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "builder";

//nyelvi file betoltese
$locale->useArea($module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

// fulek definialasa
$tabs = array(
    'builder' => $locale->get('title_builder_tab')
);

$acts = array(
    'builder' => array('add', 'mod', 'del', 'act', 'ins', 'unins', 'lst', 'pos', 'colpos')
);

//aktualis ful beallitasa
$page = 'builder';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) || 
    ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);

/**
 * ha telepitjuk a modult
 */
if ($sub_act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Builder_ColumnBox` (
		`box_id` INT NOT NULL AUTO_INCREMENT ,
		`cols` INT NOT NULL ,
		`position` INT NOT NULL ,
		`is_active` CHAR( 1 ) NOT NULL ,
	PRIMARY KEY ( `box_id` ) 
	);
	";
	$mdb2->exec($query);
	
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Builder_BoxContents` (
		`box_content_id` INT NOT NULL AUTO_INCREMENT ,
		`box_id` INT NOT NULL ,
		`menu_pos` INT NOT NULL ,
		`content_id` INT NOT NULL ,
		`category_id` INT NOT NULL ,
		`module_id` INT NOT NULL ,
		`banner_pos` INT NOT NULL ,
		`gallery_id` INT NOT NULL ,
	PRIMARY KEY ( `box_content_id` )
	);
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
if ($sub_act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Builder_ColumnBox`;
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Builder_BoxContents`;
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
if ($sub_act == "add" || $sub_act == "mod") {
	$javascripts[] = "javascripts";
	$javascripts[] = "javascript.builder";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$form =& new HTML_QuickForm('frm_builder', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', $locale->get('form_header'));

	//kirakunk egy ures option-t az elejere
	$empty_array = array('0' => '--');

	for ($i = 1; $i <= $_SESSION['site_builder_columns']; $i++){
		$columns_array[$i] = $i.". ".$locale->get('form_column');
	}

	$form->addElement('select', 'column', $locale->get('form_column'), $columns_array);
	$form->addElement('text', 'inside_columns', $locale->get('form_inside_columns'), array('onkeyup' => 'showColumnSelect(this.value,\''.$locale->get('form_column').'\')'));

	//lekérdezzük a menüpozíciókat
	$query = "
		SELECT position_id, position_name
		FROM iShark_MenusPositions
	";
    $result =& $mdb2->query($query);
	$form->addElement('select', 'menu_pos', $locale->get('form_menu_pos'), $empty_array + $result->fetchAll('', $rekey = true));

	//lekerdezzuk, hogy milyen modulokat lehet hozzaadni - fooldal
	$query = "
		SELECT m.module_id AS mid, m.module_name AS mname 
		FROM iShark_Modules m 
		WHERE m.is_active = 1 AND m.type = 'index'
		ORDER BY m.module_name
	";
	$result =& $mdb2->query($query);
	$select =& $form->addElement('select', 'module_id', $locale->get('form_module_id'), $empty_array + $result->fetchAll('', $rekey = true));

	//blockok hozzáadása
	if (is_dir("modules/")) {
	   if ($k_azon = opendir("modules/")) {
		   $i = 1;
		   while (($fajl = readdir($k_azon)) !== false) {
			   if (eregi('block', $fajl)) {
				   $blocks[$fajl] =  $fajl;
				   $i++;
			   }
		   }
		   closedir($k_azon);
	   }
	   $select =& $form->addElement('select', 'block', $locale->get('form_module_id'), $empty_array + $blocks);
	}

	//lekerdezzuk, hogy milyen tartalmakat lehet hozzaadni (a hirek kivetelevel), ha van tartalomszerkesztõ
	if (isModule('contents', 'admin')) {
		$query = "
			SELECT c.content_id AS cid, SUBSTRING(c.title, 1, 50) AS ctitle 
			FROM iShark_Contents c 
			WHERE c.is_active = 1 AND type = 1 AND (c.timer_start = '0000-00-00 00:00:00' OR c.timer_start < NOW())
			ORDER BY c.title
		";
		$result =& $mdb2->query($query);
		$select =& $form->addElement('select', 'content_id', $locale->get('form_content_id'), $empty_array + $result->fetchAll('', $rekey = true));

		//lekerdezzuk, hogy milyen rovatokat lehet hozzaadni, ha van tartalomszerkesztõ és engedelyezve vannak a rovatok
		if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == '1') {
			$query = "
				SELECT c.category_id AS cid, c.category_name AS cname 
				FROM iShark_Category c 
				WHERE c.is_active = 1 
				ORDER BY c.category_name
			";
			$result =& $mdb2->query($query);
			$select =& $form->addElement('select', 'category_id', $locale->get('form_category_id'), $empty_array + $result->fetchAll('', $rekey = true));
		}
	}
	//lekerdezzuk, hogy milyen bannerpozíciókat lehet hozzaadni, ha van banner modul
	if (isModule('banners','admin')){
		$query = "
			SELECT b.place_id AS pid, b.place_name AS pname 
			FROM iShark_Banners_Places b 
			ORDER BY b.place_name
		";
		$result =& $mdb2->query($query);
		$select =& $form->addElement('select', 'banner_pos', $locale->get('form_banner_pos'), $empty_array + $result->fetchAll('', $rekey = true));
	}

	//lekerdezzuk, hogy milyen galériákat lehet hozzaadni, ha van galéria modul
	if (isModule('gallery','admin')){
		$query = "
			SELECT g.gallery_id AS gid, g.name AS gname 
			FROM iShark_Galleries g  
			ORDER BY g.name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'gallery_id', $locale->get('form_gallery_id'), $empty_array + $result->fetchAll('', $rekey = true));
	}

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('column',         $locale->get('error_required_column'),        'required');
	$form->addRule('inside_columns', $locale->get('error_required_inside_column'), 'required');
	$form->addRule('inside_columns', $locale->get('error_numeric_inside_column'),  'numeric');

	if ($form->isSubmitted()) {
		//ha nem valasztott se menut, se modult, se tartalmat, se kategóriát, se bannert, se galériát
		if ($form->getSubmitValue('menu_pos') == "" && $form->getSubmitValue('module_id') == "" && $form->getSubmitValue('content_id') == "" && $form->getSubmitValue('category_id') == "" && $form->getSubmitValue('banner_pos') == "" && $form->getSubmitValue('gallery_id') == "" && $form->getSubmitValue('block') == "") {
			$form->addRule('menu_pos',    $locale->get('error_required_modules'), 'required');
			$form->addRule('module_id',    $locale->get('error_required_modules'), 'required');
			$form->addRule('block',    $locale->get('error_required_modules'), 'required');
			if (isModule('contents', 'admin')){
				$form->addRule('content_id',   $locale->get('error_required_modules'), 'required');
				if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == '1'){
					$form->addRule('category_id',      $locale->get('error_required_modules'), 'required');
				}
			}
			if (isModule('banners', 'admin')) {
				$form->addRule('banner_pos',     $locale->get('error_required_modules'), 'required');
			}
			if (isModule('gallery', 'admin')) {
				$form->addRule('gallery_id',     $locale->get('error_required_modules'), 'required');
			}
		}

		//ha valasztott többet is és egy a hasábok száma, akkor hiba (csak az egyiket lehet valasztani)
		$tomb = array();
		$menu_pos = $form->getSubmitValue('menu_pos');
		if ($menu_pos != 0) {
			$tomb['menu_pos'] = $menu_pos;
		}

		$block = $form->getSubmitValue('block');
		if ($block != '0') {
			$tomb['block'] = $block;
		}

		$module_id = $form->getSubmitValue('module_id');
		if ($module_id != 0) {
			$tomb['module_id'] = $module_id;
		}

		if (isModule('contents', 'admin')) {
			$content_id = $form->getSubmitValue('content_id');
			if ($content_id != 0) {
				$tomb['content_id'] = $content_id;
			}
			if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == '1') {
				$category_id = $form->getSubmitValue('category_id');
				if ($category_id != 0) {
					$tomb['category_id'] = $category_id;
				}
			}
		}

		if (isModule('banners', 'admin')) {
			$banner_pos = $form->getSubmitValue('banner_pos');
			if ($banner_pos != 0) {
				$tomb['banner_pos'] = $banner_pos;
			}
		}

		if (isModule('gallery', 'admin')) {
			$gallery_id = $form->getSubmitValue('gallery_id');
			if ($gallery_id != 0) {
				$tomb['gallery_id'] = $gallery_id;
			}
		}

		if (count($tomb) != $form->getSubmitValue('inside_columns')) {
			$form->setElementError('menu_pos',  $locale->get('error_required_modules2'));
			$form->setElementError('module_id', $locale->get('error_required_modules2'));
			$form->setElementError('block',     $locale->get('error_required_modules2'));
			if (isModule('contents', 'admin')) {
				$form->setElementError('content_id', $locale->get('error_required_modules2'));
				if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == '1') {
					$form->setElementError('category_id', $locale->get('error_required_modules2'));
				}
			}
			if (isModule('banners', 'admin')) {
				$form->setElementError('banner_pos', $locale->get('error_required_modules2'));
			}
			if (isModule('gallery', 'admin')) {
				$form->setElementError('gallery_id', $locale->get('error_required_modules2'));
			}
		}

		if ($form->getSubmitValue('inside_columns') > 1) {
			$seltomb = array();

			$menu_pos_sel = $form->getSubmitValue('menu_pos_sel');
			if ($menu_pos_sel != 0) {
				$seltomb['menu_pos'] = $menu_pos_sel;
			}

			$block_sel = $form->getSubmitValue('block_sel');
			if ($block_sel != 0) {
				$seltomb['block'] = $block_sel;
			}

			$module_id_sel = $form->getSubmitValue('module_id_sel');
			if ($module_id_sel != 0) {
				$seltomb['module_id'] = $module_id_sel;
			}

			if (isModule('contents', 'admin')) {
				$content_id_sel = $form->getSubmitValue('content_id_sel');
				if ($content_id_sel != 0) {
					$seltomb['content_id'] = $content_id_sel;
				}
				if (isset($_SESSION['site_category']) && $_SESSION['site_category'] == '1') {
					$category_id_sel = $form->getSubmitValue('category_id_sel');
					if ($category_id_sel != 0) {
						$seltomb['category_id'] = $category_id_sel;
					}
				}
			}

			if (isModule('banners', 'admin')) {
				$banner_pos_sel = $form->getSubmitValue('banner_pos_sel');
				if ($banner_pos_sel != 0) {
					$seltomb['banner_pos'] = $banner_pos_sel;
				}
			}

			if (isModule('gallery', 'admin')) {
				$gallery_id_sel = $form->getSubmitValue('gallery_id_sel');
				if ($gallery_id_sel != 0) {
					$seltomb['gallery_id'] = $gallery_id_sel;
				}
			}

			if($form->getSubmitValue('inside_columns') > 1 && count($seltomb) != $form->getSubmitValue('inside_columns')){
				$form->setElementError('menu_pos', $locale->get('error_required_modules3'));
			}

			$seltomb2 = array_unique($seltomb);
			if ($seltomb != $seltomb2){
				$form->setElementError('menu_pos', $locale->get('error_required_modules4'));
			}
		} else {
			$seltomb = "";
		}
	}
	/**
	 * ha uj menupontot adunk hozza
	 */
	if ($sub_act == "add") {
		//ful-hoz tartozo szoveg
		$lang_title = $locale->get('title_add');

		$bodyonload[] = "showColumnSelect(".$form->getSubmitValue('inside_columns').",'".$locale->get('form_column')."');";

		//breadcrumb
		$breadcrumb->add($lang_title, '#');

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		 $form->addElement('hidden', 'act',     $page);
		 $form->addElement('hidden', 'sub_act', $sub_act);

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$column           = $form->getSubmitValue('column');
			$inside_columns   = $form->getSubmitValue('inside_columns');
			$menu_pos         = intval($form->getSubmitValue('menu_pos'));
			$block            = $form->getSubmitValue('block');
			$module_id        = intval($form->getSubmitValue('module_id'));
			$content_id       = intval($form->getSubmitValue('content_id'));
			$category_id      = intval($form->getSubmitValue('category_id'));
			$banner_pos       = intval($form->getSubmitValue('banner_pos'));
			$gallery_id       = intval($form->getSubmitValue('gallery_id'));
			$menu_pos_sel     = $form->getSubmitValue('menu_pos_sel');
			$block_sel        = $form->getSubmitValue('block_sel');
			$module_id_sel    = $form->getSubmitValue('module_id_sel');
			$content_id_sel   = $form->getSubmitValue('content_id_sel');
			$category_id_sel  = $form->getSubmitValue('category_id_sel');
			$banner_pos_sel   = $form->getSubmitValue('banner_pos_sel');
			$gallery_id_sel   = $form->getSubmitValue('gallery_id_sel');

			$query = "
				SELECT max(position) AS maxpos 
				FROM iShark_Builder_ColumnBox
				WHERE cols = $column
				GROUP BY cols
			";
			$result =& $mdb2->query($query);
			$row = $result->fetchRow();
			$position = $row['maxpos']+1;

			$types  = array('integer', 'integer', 'text');
			$values = array($column, $position, '1');
			$box_id = $mdb2->extended->getBeforeID('iShark_Builder_ColumnBox', 'box_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Builder_ColumnBox 
				(box_id, cols, position, is_active) 
				VALUES 
				($box_id, ?, ?, ?)
			";
			$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
			$result->execute($values);

			$last_id = $mdb2->extended->getAfterID($box_id, 'iShark_Builder_ColumnBox', 'box_idid');;

			if (is_array($seltomb)) {
				asort($seltomb);
				foreach($seltomb as $se => $to) {
					$types  = array('integer', 'integer');
					$values = array($last_id, $tomb[$se]);
					$box_content_id = $mdb2->extended->getBeforeID('iShark_Builder_BoxContents', 'box_content_id', TRUE, TRUE);

					$query = "
						INSERT INTO iShark_Builder_BoxContents 
						(box_content_id, box_id, ".$se.") 
						VALUES 
						($box_content_id, ?, ? )
					";
					$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
					$result->execute($values);
				}
			} else {
				$types  = array('integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'text');
				$values = array($last_id, $menu_pos, $content_id, $category_id, $module_id, $banner_pos, $gallery_id, $block);
				$box_content_id = $mdb2->extended->getBeforeID('iShark_Builder_BoxContents', 'box_content_id', TRUE, TRUE);

				$query = "
					INSERT INTO iShark_Builder_BoxContents 
					(box_content_id, box_id, menu_pos, content_id, category_id, module_id, banner_pos, gallery_id, block) 
					VALUES 
					($box_content_id, ?, ?, ?, ?, ?, ?, ?, ? )
				";
				$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
				$result->execute($values);
			}

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
	if ($sub_act == "mod") {
		//ful-hoz tartozo szoveg
		$lang_title = $locale->get('title_mod');

		$bid = intval($_REQUEST['bid']);

		$query = "
			SELECT * 
			FROM iShark_Builder_ColumnBox 
			WHERE box_id = $bid
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			$form->setDefaults(array(
				"column"    => $row['cols']
				)
			);
		}

		$query = "
			SELECT * 
			FROM iShark_Builder_BoxContents 
			WHERE box_id = $bid
		";
		$result =& $mdb2->query($query);
		$i = 1;
		while ($row = $result->fetchRow())
		{
			if ($result->numRows()>1) {
				if (empty($menu_pos)) {
					$menu_pos = $row['menu_pos'];
				}
				if (empty($block)) {
					$block = $row['block'];
				}
				if (empty($module_id)) {
					$module_id = $row['module_id'];
				}
				if (empty($content_id)) {
					$content_id = $row['content_id'];
				}
				if (empty($category_id)) {
					$category_id = $row['category_id'];
				}
				if (empty($banner_pos)) {
					$banner_pos = $row['banner_pos'];
				}
				if (empty($gallery_id)) {
					$gallery_id = $row['gallery_id'];
				}

				if($row['menu_pos'] != '0') {
					$menu_pos         = $row['menu_pos'];
					$menu_pos_sel[$i] = "selected";
					$tpl->assign('menu_pos_sel', $i);
				}
				if($row['block'] != '0') {
					$block         = $row['block'];
					$block_sel[$i] = "selected";
					$tpl->assign('block_sel', $i);
				}
				if($row['module_id'] != '0') {
					$module_id         = $row['module_id'];
					$module_id_sel[$i] = "selected";
					$tpl->assign('module_id_sel', $i);
				}
				if($row['content_id'] != '0') {
					$content_id         = $row['content_id'];
					$content_id_sel[$i] = "selected";
					$tpl->assign('content_id_sel', $i);
				}
				if($row['category_id'] != '0') {
					$category_id         = $row['category_id'];
					$category_id_sel[$i] = "selected";
					$tpl->assign('category_id_sel', $i);
				}
				if($row['banner_pos'] != '0') {
					$banner_pos         = $row['banner_pos'];
					$banner_pos_sel[$i] = "selected";
					$tpl->assign('banner_pos_sel', $i);
				}
				if($row['gallery_id'] != '0') {
					$gallery_id         = $row['gallery_id'];
					$gallery_id_sel[$i] = "selected";
					$tpl->assign('gallery_id_sel', $i);
				}
			} else {
				$menu_pos    = $row['menu_pos'];
				$block       = $row['block'];
				$module_id   = $row['module_id'];
				$content_id  = $row['content_id'];
				$category_id = $row['category_id'];
				$banner_pos  = $row['banner_pos'];
				$gallery_id  = $row['gallery_id'];
			}

			$form->setDefaults(array(
				"inside_columns" => $result->numRows(),
				"menu_pos"       => $menu_pos,
				"block"          => $block,
				"module_id"      => $module_id,
				"content_id"     => $content_id,
				"category_id"    => $category_id,
				"banner_pos"     => $banner_pos,
				"gallery_id"     => $gallery_id
				)
			);
			$i++;
		}

		$bodyonload[] = "showColumnSelect(".$result->numRows().",'".$locale->get('form_column')."');";

		//breadcrumb
		$breadcrumb->add($lang_title, '#');

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);
		$form->addElement('hidden', 'bid',     $bid);

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$column          = $form->getSubmitValue('column');
			$inside_columns  = $form->getSubmitValue('inside_columns');
			$menu_pos        = intval($form->getSubmitValue('menu_pos'));
			$block           = $form->getSubmitValue('block');
			$module_id       = intval($form->getSubmitValue('module_id'));
			$content_id      = intval($form->getSubmitValue('content_id'));
			$category_id     = intval($form->getSubmitValue('category_id'));
			$banner_pos      = intval($form->getSubmitValue('banner_pos'));
			$gallery_id      = intval($form->getSubmitValue('gallery_id'));
			$menu_pos_sel    = $form->getSubmitValue('menu_pos_sel');
			$block_sel       = $form->getSubmitValue('block_sel');
			$module_id_sel   = $form->getSubmitValue('module_id_sel');
			$content_id_sel  = $form->getSubmitValue('content_id_sel');
			$category_id_sel = $form->getSubmitValue('category_id_sel');
			$banner_pos_sel  = $form->getSubmitValue('banner_pos_sel');
			$gallery_id_sel  = $form->getSubmitValue('gallery_id_sel');

			$query = "
				UPDATE iShark_Builder_ColumnBox 
				SET cols = $column 
				WHERE box_id = $bid
			";
			$result = $mdb2->exec($query);

			$query = "
				DELETE FROM iShark_Builder_BoxContents 
				WHERE box_id = $bid
			";
			$result = $mdb2->exec($query);

			if (is_array($seltomb)) {
				asort($seltomb);
				foreach($seltomb as $se => $to) {
					$types  = array('integer', 'integer');
					$values = array($bid, $tomb[$se]);
					$box_content_id = $mdb2->extended->getBeforeID('iShark_Builder_BoxContents', 'box_content_id', TRUE, TRUE);

					$query = "
						INSERT INTO iShark_Builder_BoxContents 
						(box_content_id, box_id, ".$se.") 
						VALUES 
						($box_content_id, ?, ?)
					";
					$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
					$result->execute($values);
				}
			} else {
				$types  = array('integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'integer', 'text');
				$values = array($bid, $menu_pos, $content_id, $category_id, $module_id, $banner_pos, $gallery_id, $block);
				$box_content_id = $mdb2->extended->getBeforeID('iShark_Builder_BoxContents', 'box_content_id', TRUE, TRUE);

				$query = "
					INSERT INTO iShark_Builder_BoxContents 
					(box_content_id, box_id, menu_pos, content_id, category_id, module_id, banner_pos, gallery_id, block) 
					VALUES 
					($box_content_id, ?, ?, ?, ?, ?, ?, ?, ?)
				";
				$result = $mdb2->prepare( $query, $types, MDB2_PREPARE_MANIP );
				$result->execute( $values );
			}

			//loggolas
			logger($act, '', '');

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name);
			exit;
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('lang_title',  $lang_title);
	$tpl->assign('tiny_fields', "a");
	$tpl->assign('form',        $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'builder_add';
}

/**
 * ha torlunk egy menupontot
 */
if ($sub_act == "del") {
	if (isset($_GET['bid']) && is_numeric($_GET['bid'])) {
		$bid = intval($_GET['bid']);

		$query = "
			DELETE FROM iShark_Builder_ColumnBox 
			WHERE box_id = $bid
		";
		$mdb2->exec($query);
		
		$query = "
			DELETE FROM iShark_Builder_BoxContents
			WHERE box_id = $bid
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
if ($sub_act == "act") {
	include_once $include_dir.'/function.check.php';
	$rid = intval($_REQUEST['rid']);

	check_active('iShark_Rss', 'rss_id', $rid);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha pozíciót változtatunk
 */
if ($sub_act == "pos") {
	$bid = intval($_REQUEST['bid']);
	$way = $_REQUEST['way'];

	$query = "
		SELECT * 
		FROM iShark_Builder_ColumnBox 
		WHERE box_id = $bid
	";
	$result =& $mdb2->query($query);
	$row = $result->fetchRow();

	if ($way == 'up') {
		$new_pos = $row['position']-1;
	} else {
		$new_pos = $row['position']+1;
	}

	$query_other = "
		UPDATE iShark_Builder_ColumnBox 
		SET position='".$row['position']."' 
		WHERE cols = '".$row['cols']."' AND position = '".$new_pos."'
	";
	$result_other = $mdb2->query($query_other);

	$query_pos = "
		UPDATE iShark_Builder_ColumnBox 
		SET position='".$new_pos."' 
		WHERE box_id = $bid
	";
	$result_pos = $mdb2->query($query_pos);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha oszlop pozíciót változtatunk
 */
if ($sub_act == "colpos") {
	$bid = intval($_REQUEST['bid']);
	$way = $_REQUEST['way'];

	$query = "
		SELECT * 
		FROM iShark_Builder_ColumnBox 
		WHERE box_id = $bid
	";
	$result =& $mdb2->query($query);
	$row = $result->fetchRow();

	if ($way == 'right') {
		$new_col = $row['cols']+1;
	} else {
		$new_col = $row['cols']-1;
	}

	$query_col = "
        SELECT max(position) AS maxpos 
		FROM iShark_Builder_ColumnBox 
		WHERE cols = '$new_col' 
		GROUP BY cols
	";
	$result_col =& $mdb2->query($query_col);
	$row_col = $result_col->fetchRow();
	$new_pos = $row_col['maxpos']+1;

	$query_pos = "
		UPDATE iShark_Builder_ColumnBox 
		SET cols     = '$new_col', 
			position = '$new_pos' 
		WHERE box_id = $bid
	";
	$result_pos = $mdb2->exec($query_pos);

	$query_old_col = "
		SELECT * 
		FROM iShark_Builder_ColumnBox 
		WHERE cols = '".$row['cols']."' 
		ORDER BY position ASC
	";
	$result_old_col =& $mdb2->query($query_old_col);
	$i = 1;
	while($row_old_col = $result_old_col->fetchRow())
	{
		$update_old_col = "
            UPDATE iShark_Builder_ColumnBox 
			SET position = '$i' 
			WHERE box_id = '".$row_old_col['box_id']."'
		";
		$result_update_old_col = $mdb2->exec($update_old_col);
		$i++;
	}

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha nincs semmilyen muvelet, akkor a listat mutatjuk
 */
if ($sub_act == "lst") {
	$c        = array();
	$colwidth = explode(";", $_SESSION['site_builder_columns_width']);

	$cols = "
		SELECT * 
		FROM iShark_Builder_ColumnBox 
		GROUP BY cols
	";
	$colres = $mdb2->query($cols);
	while($row = $colres->fetchRow())
	{
		$boxes = "
			SELECT * 
			FROM iShark_Builder_ColumnBox 
			WHERE cols = ".$row['cols']." 
			ORDER BY position ASC
		";
		//$w = $row['cols']-1;
		//$c[$row['cols']]['width']=$colwidth[$w];

		$boxres = $mdb2->query($boxes);
		while($row2 = $boxres->fetchRow())
		{
			$c[$row['cols']][$row2['box_id']] = array (
				'pos'   => $row2['position']
			);

			$boxcontents = "
				SELECT * 
				FROM iShark_Builder_BoxContents 
				WHERE box_id = ".$row2['box_id']." 
				ORDER BY box_content_id ASC
			";
			$boxcontentsres =& $mdb2->query($boxcontents);
			while($row3 = $boxcontentsres->fetchRow()){
				$contents[$row3['box_content_id']] = array(
					'menu_pos'    => $row3['menu_pos'],
					'content_id'  => $row3['content_id'],
					'category_id' => $row3['category_id'],
					'module_id'   => $row3['module_id'],
					'banner_pos'  => $row3['banner_pos'],
					'gallery_id'  => $row3['gallery_id'],
					'block'       => $row3['block']
				);
			}

			$c[$row['cols']][$row2['box_id']]['contents'] = $contents;
			unset($contents);
		}
	}

	$query = "
		SELECT bc.box_id, bc.cols, bc.position, bc.is_active, bbc.box_content_id, bbc.menu_pos, bbc.content_id, 
			bbc.category_id, bbc.module_id, bbc.banner_pos, bbc.gallery_id
		FROM iShark_Builder_ColumnBox AS bc
		LEFT JOIN iShark_Builder_BoxContents AS bbc ON bc.box_id = bbc.box_id
	";
	$result =& $mdb2->query($query);

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$module_name.'&sub_act=add',
			'title' => $locale->get('act_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('add_new',  $add_new);
	$tpl->assign('c',        $c);
	$tpl->assign('colwidth', $colwidth);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'builder_list';
}

?>
