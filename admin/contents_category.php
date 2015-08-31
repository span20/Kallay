<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$fieldselect3 = "";
$fieldselect4 = "";
$fieldselect5 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY c.category_name ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY u.name ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = "ORDER BY c.add_date ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   = "ORDER BY u2.name ";
			$fieldselect4 = "selected";
			break;
		case 5:
			$fieldorder   = "ORDER BY c.mod_date ";
			$fieldselect5 = "selected";
			break;
	}

	switch ($ord) {
		case "asc":
			$order      = "ASC";
			$ordselect1 = "selected";
			break;
		case "desc":
			$order      = "DESC";
			$ordselect2 = "selected";
			break;
	}
} else {
	$field      = "";
	$ord        = "";
	$fieldorder = "ORDER BY c.category_name";
	$order      = "ASC";
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('fieldselect3', $fieldselect3);
$tpl->assign('fieldselect4', $fieldselect4);
$tpl->assign('fieldselect5', $fieldselect5);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
//rendezes vege

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.contents.php';

	$titles = array('add' => $locale->get('category_title_add'), 'mod' => $locale->get('category_title_mod'));

	$form =& new HTML_QuickForm('frm_categories', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('category_form_required_note'));
	$form->addElement('header', 'categories', $locale->get('category_form_header'));
	$form->addElement('hidden', 'act',        $page);
    $form->addElement('hidden', 'sub_act',    $sub_act);
	$form->addElement('hidden', 'field',      $field);
	$form->addElement('hidden', 'ord',        $ord);

	//ha tobb nyelvu az oldal, akkor kilistazzuk a nyelveket
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		include_once $include_dir.'/functions.php';
		$form->addElement('select', 'languages', $locale->get('category_field_lang'), $locale->getLocales());
	}

	//rovat neve
	$form->addElement('text', 'name', $locale->get('category_field_name'));

	//ha van mti, akkor a kategoriait kilistazzuk
	if (!empty($_SESSION['site_cnt_is_mti'])) {
		$empty_array = array('' => '');
		$query_mti = "
			SELECT category_id, category_name 
			FROM iShark_Mti_Category 
			ORDER BY category_name
		";
		$result_mti =& $mdb2->query($query_mti);
		$form->addElement('select', 'mti', $locale->get('category_field_mti'), $empty_array + $result_mti->fetchAll('', $rekey = true));
	}

	//aktiv
	$active = array();
	$active[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('category_form_yes'), '1');
	$active[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('category_form_no'),  '0');
	$form->addGroup($active, 'active', $locale->get('category_field_active'));

	$form->addElement('submit', 'submit', $locale->get('category_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('category_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('name',   $locale->get('category_form_required_name'),   'required');
	$form->addRule('active', $locale->get('category_form_required_active'), 'required');

	/**
	 * ha uj kategoriat adunk hozza
	 */
	if ($sub_act == "add") {
		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act=add');

		$form->setDefaults(array(
			'active' => '1'
			)
		);

		$form->addFormRule('check_contents_addcategory');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name   = $form->getSubmitValue('name');
			$mti    = intval($form->getSubmitValue('mti'));
			$active = intval($form->getSubmitValue('active'));

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
				$languages = $form->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			$types  = array('text', 'integer', 'integer', 'integer', 'text', 'integer');
			$values = array($name, $_SESSION['user_id'], $_SESSION['user_id'], $active, $languages, $mti);
			$category_id = $mdb2->extended->getBeforeID('iShark_Category', 'category_id', TRUE, TRUE);

			$query = "
				INSERT INTO iShark_Category 
				(category_id, category_name, add_user_id, add_date, mod_user_id, mod_date, is_active, is_deleted, lang, mti_category_id) 
				VALUES 
				($category_id, ?, ?, NOW(), ?, NOW(), ?, 0, ?, ?)
			";
			$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
			$result->execute($values);

			//loggolas
			logger($page.'_'.$sub_act);

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //hozzadas vege

	/**
	 * ha modositunk egy csoportot
	 */
	if ($sub_act == "mod") {
		$cid = intval($_REQUEST['cid']);

		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&act='.$page.'&amp;act=mod&amp;cid='.$cid);

		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);
		$form->addElement('hidden', 'cid',     $cid);

		//lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Category 
			WHERE category_id = $cid
		";
		$result = $mdb2->query($query);
		//ha nincs ilyen azonositoju adat, akkor visszarakjuk a listahoz (ide, majd hibauzenet kell!)
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$form->setDefaults(array(
					'name'   => $row['category_name'],
					'active' => $row['is_active'],
					'mti'    => $row['mti_category_id']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}

		$form->addFormRule('check_contents_modcategory');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$category_name = $form->getSubmitValue('name');
			$mti           = intval($form->getSubmitValue('mti'));
			$is_active     = intval($form->getSubmitValue('active'));

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
				$languages = $form->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			$types  = array('text', 'integer', 'integer', 'text', 'integer', 'integer');
			$values = array($category_name, $_SESSION['user_id'], $is_active, $languages, $mti, $cid);

			$query = "
				UPDATE iShark_Category 
				SET category_name   = ?, 
					mod_user_id     = ?, 
					mod_date        = NOW(), 
					is_active       = ?, 
					lang            = ?, 
					mti_category_id = ?
				WHERE category_id = ?
			";
			$result = $mdb2->prepare($query, $types, MDB2_PREPARE_MANIP);
			$result->execute($values);

			//loggolas
			logger($page.'_'.$sub_act);

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//valtozok atadasa a template-nek
	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page);
	$tpl->assign('lang_title', $titles[$sub_act]);

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha aktivaljuk vagy deaktivaljuk
 */
if ($sub_act == "act") {
	include_once $include_dir.'/function.check.php';
	$cid = intval($_GET['cid']);

	check_active('iShark_Category', 'category_id', $cid);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //aktivalas, deaktivalas vege

/**
 * ha torlunk egy rovatot
 */
if ($sub_act == "del") {
	$cid = intval($_REQUEST['cid']);

	$query = "
		UPDATE iShark_Category 
		SET is_active  = 0, 
			is_deleted = 1 
		WHERE category_id = $cid
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	//lekerdezzuk az adatbazisbol a kategoriak listajat
	$query = "
		SELECT c.category_id AS cid, c.category_name AS cname, u.name AS add_name, c.add_date AS add_date, 
			u2.name AS mod_name, c.mod_date AS mod_date, c.is_active AS cact, c.lang AS clang 
		FROM iShark_Category c 
		LEFT JOIN iShark_Users u ON u.user_id = c.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = c.mod_user_id 
		WHERE c.is_deleted = 0 
		$fieldorder $order
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//uj hozzaadasa - design miatt
	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
			'title' => $locale->get('category_title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('add_new',    $add_new);
	$tpl->assign('back_arrow', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'contents_category_list';
}

?>
