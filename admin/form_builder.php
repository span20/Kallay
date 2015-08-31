<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "form_builder";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);
$tpl->assign('title_module', $title_module);
$tpl->assign('self',         $module_name);
$tpl->assign('self_2',       "admin_".$module_name);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst', 'add', 'act', 'mod', 'del', 'field_lst', 'field_add', 'field_act', 'field_mod', 'field_del');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = "ORDER BY fb.form_title ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = "ORDER BY fb.is_deleted ";
			$fieldselect2 = "selected";
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
	$fieldorder = "ORDER BY fb.form_title";
	$order      = "ASC";
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
$tpl->assign('page_id',      $page_id);
//rendezes vege

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 1, 'form_builder')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

if ($act == "add" || $act == "mod") {
	//js beszurasa
	$javascripts[] = "javascripts";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_forms', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header',              $locale->get('form_header'));
	$form->addElement('hidden',   'field',   $field);
	$form->addElement('hidden',   'ord',     $ord);
	$form->addElement('hidden',   'page_id', $page_id);
	$form->addElement('text',     'title',   $locale->get('field_title'));

	$type = array();
	$type[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_type_email'), '0');
	$type[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_type_database'), '1');
	$form->addGroup($type, 'type', $locale->get('form_type'));

	$form->addElement('checkbox', 'data_fields',  $locale->get('field_datafields'));

	$form->addElement('textarea', 'lead',    $locale->get('field_lead'));
	$form->addElement('textarea', 'letter',    $locale->get('field_letter'));
	$tpl->assign('tiny_fields', 'letter');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('title', $locale->get('error_no_title'), 'required');
	$form->addRule('lead',  $locale->get('error_no_lead'),  'required');

	/**
	 * ha uj csoportot adunk hozza
	 */
	if ($act == "add") {
			//breadcrumb
			$breadcrumb->add($titles[$act], '#');

			$form->addElement('hidden', 'act', 'add');

			//$form->addFormRule('check_addgroup');
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$title = $form->getSubmitValue('title');
				$lead  = $form->getSubmitValue('lead');
				$type  = $form->getSubmitValue('type');
				$letter= $form->getSubmitValue('letter');
				$dataf = $form->getSubmitValue('data_fields');

				$query = "
					INSERT INTO iShark_FormBuilder_Forms 
					(form_title, form_lead, add_date, add_user_id, mod_date, mod_user_id, is_active, is_deleted, type, letter, datafields) 
					VALUES 
					('".$title."', '".$lead."', NOW(), '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', '0', '0', '".$type."', '".$letter."', '$dataf')
				";
				$mdb2->exec($query);

				//loggolas
				logger($act, '', '');

				$form->freeze();

				header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
				exit;
			}
		
	} //csoport hozzadas vege
	
	/**
	 * ha modositunk egy csoportot
	 */
	if ($act == "mod") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		$fid = intval($_REQUEST['fid']);

		$form->addElement('hidden', 'act', 'mod');
		$form->addElement('hidden', 'fid', $fid);

		//lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_FormBuilder_Forms 
			WHERE form_id = $fid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$form->setDefaults(array(
					'title' 	  => $row['form_title'],
					'lead'  	  => $row['form_lead'],
					'type' 	 	  => $row['type'],
					'letter'	  => $row['letter'],
					'data_fields' => $row['datafields']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name);
			exit;
		}

		//$form->addFormRule('check_modgroup');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$title = $form->getSubmitValue('title');
			$lead  = $form->getSubmitValue('lead');
			$type  = $form->getSubmitValue('type');
			$letter= $form->getSubmitValue('letter');
			$dataf = $form->getSubmitValue('data_fields');

			$query = "
				UPDATE iShark_FormBuilder_Forms 
				SET form_title = '".$title."', 
					form_lead  = '".$lead."',
					mod_date = NOW(),
					mod_user_id = '".$_SESSION['user_id']."',
					type = '".$type."',
					letter = '".$letter."',
					datafields = '".$dataf."'
				WHERE form_id = $fid
			";
			$mdb2->exec($query);

			//loggolas
			logger($act, '', '');

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //csoport modositas vege

	$form->addElement('submit', 'submit',  $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',   $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//valtozok atadasa a template-nek
	$tpl->assign('lang_title', $titles[$act]);
	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}
/**
 * ha torlunk egy csoportot
 */
if ($act == "del") {
	$fid = intval($_GET['fid']);

	$query = "
		UPDATE iShark_FormBuilder_Forms 
		SET is_active = '0', is_deleted = '1' 
		WHERE form_id = $fid
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&act=lst&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //torles vege

if ($act == "act") {
	include_once $include_dir.'/function.check.php';
	$form_id  = intval($_REQUEST['form_id']);

	check_active('iShark_FormBuilder_Forms', 'form_id', $form_id);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&act=lst');
	exit;
}

/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
	//lekerdezzuk az adatbazisbol az ûrlapok listajat
	$query = "
		SELECT fb.form_id AS fid, fb.form_title AS ftitle, fb.add_date AS add_date, fb.is_deleted AS fdel, fb.is_active AS factive 
		FROM iShark_FormBuilder_Forms fb
		WHERE fb.is_deleted = '0'
		".$fieldorder." ".$order."
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);
	$tpl->assign('add_new', $add_new);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',   $paged_data['data']);
	$tpl->assign('page_list',   $paged_data['links']);
	$tpl->assign('back_arrow',  'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'forms_list';
}

/**
 *
 * mezõkhöz tartozó mûveletek
 */
 
if ($act == "field_act") {
	include_once $include_dir.'/function.check.php';
	$field_id = intval($_REQUEST['field_id']);
	$form_id  = intval($_REQUEST['form_id']);

	check_active('iShark_FormBuilder_Fields', 'field_id', $field_id);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&act=field_lst&form_id='.$form_id);
	exit;
}

if ($act == "field_del") {
	$fid = intval($_GET['field_id']);
	$form_id  = intval($_REQUEST['form_id']);

	$query = "
		UPDATE iShark_FormBuilder_Fields 
		SET is_active = '0', is_deleted = '1' 
		WHERE field_id = $fid
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&act=field_lst&form_id='.$form_id.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
	exit;
} //torles vege
 
if ($act == "field_lst") {
	
	if (isset($_GET['form_id'])) {
	
		$form_id = intval($_GET['form_id']);
		
		//lekerdezzuk az adatbazisbol a mezõk listajat
		$query = "
			SELECT f.field_id AS field_id, f.field_name AS fname, f.field_type AS ftype, f.is_deleted AS fdel, f.is_active AS factive 
			FROM iShark_FormBuilder_Fields f
			WHERE f.is_deleted = '0' AND f.form_id = '$form_id'
		";
	
		//lapozo
		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);
	
		$add_new = array (
			array(
				'link'  => 'admin.php?p='.$module_name.'&amp;act=field_add&amp;form_id='.$form_id.'&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id,
				'title' => $locale->get('title_add'),
				'pic'   => 'add.jpg'
			)
		);
		$tpl->assign('add_new', $add_new);
	
		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('page_data',   $paged_data['data']);
		$tpl->assign('page_list',   $paged_data['links']);
		$tpl->assign('back_arrow',  'admin.php?p='.$module_name);
	
	}

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'fields_list';
}

if ($act == "field_add" || $act == "field_mod") {
	//js beszurasa
	$javascripts[] = "javascript.formbuilder";
	
	$form_id = intval($_GET['form_id']);

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('field_add' => $locale->get('title_field_add'), 'field_mod' => $locale->get('title_field_mod'));

	$form =& new HTML_QuickForm('frm_fields', 'post', 'admin.php?p='.$module_name.'&form_id='.$form_id);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header',              $locale->get('form_header'));
	$form->addElement('hidden',   'field',   $field);
	$form->addElement('hidden',   'ord',     $ord);
	$form->addElement('hidden',   'page_id', $page_id);
	$form->addElement('text',     'name',    $locale->get('field_fieldtitle'));
	$form->addElement('select',   'type',    $locale->get('field_type'), array('text' => $locale->get('field_type_text'), 'select' => $locale->get('field_type_select'), 'checkbox' => $locale->get('field_type_checkbox'), 'radio' => $locale->get('field_type_radio')), array('onchange' => 'end_dis(this.value, \'new_answer\');') );
	
	$form->addElement('select',   'check',   $locale->get('field_check'), array('none' => $locale->get('field_check_none'), 'required' => $locale->get('field_check_required'), 'numeric' => $locale->get('field_check_numeric'), 'email' => $locale->get('field_check_email')) );

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('name', $locale->get('error_no_title'), 'required');

	/**
	 * ha uj csoportot adunk hozza
	 */
	if ($act == "field_add") {
			//breadcrumb
			$breadcrumb->add($titles[$act], '#');

			$form->addElement('hidden', 'act', 'field_add');

			//$form->addFormRule('check_addgroup');
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name  = $form->getSubmitValue('name');
				$type  = $form->getSubmitValue('type');
				$check = $form->getSubmitValue('check');
				
				$next_field_id = $mdb2->extended->getBeforeID('iShark_FormBuilder_Fields', 'field_id', TRUE, TRUE);

				$query = "
					INSERT INTO iShark_FormBuilder_Fields 
					(field_id, form_id, field_name, field_type, field_check, is_active, is_deleted) 
					VALUES 
					('$next_field_id', '$form_id', '".$name."', '".$type."', '".$check."', '0', '0')
				";
				$mdb2->exec($query);
				
				$last_field_id = $mdb2->extended->getAfterID($next_field_id, 'iShark_FormBuilder_Fields', 'field_id');
				
				if ($type == "select" || $type == "checkbox" || $type == "radio") {
					$fields_num = $form->getSubmitValue('fields_num');
					for ($i = 1; $i <= $fields_num; $i++) {
						$answer = $form->getSubmitValue('answer_'.$i);
						if (isset($answer) && !empty($answer)) {
							$next_value_id = $mdb2->extended->getBeforeID('iShark_FormBuilder_Values', 'values_id', TRUE, TRUE);

							$query = "
								INSERT INTO iShark_FormBuilder_Values 
								(values_id, field_id, value) 
								VALUES 
								('$next_value_id', '$last_field_id', '".$answer."')
							";
							$mdb2->exec($query);
							
							$last_value_id = $mdb2->extended->getAfterID($next_value_id, 'iShark_FormBuilder_Values', 'values_id');
						}
					}
				}

				//loggolas
				logger($act, '', '');

				$form->freeze();

				header('Location: admin.php?p='.$module_name.'&form_id='.$form_id.'&act=field_lst&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
				exit;
			}
		
	} //csoport hozzadas vege
	
	/**
	 * ha modositunk egy csoportot
	 */
	if ($act == "field_mod") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		$field_id = intval($_REQUEST['field_id']);
		$form_id = intval($_REQUEST['form_id']);

		$form->addElement('hidden', 'act', 'field_mod');
		$form->addElement('hidden', 'field_id', $field_id);
		$form->addElement('hidden', 'form_id', $form_id);

		//lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_FormBuilder_Fields 
			WHERE field_id = $field_id
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$values = "";
				$query_values = "
					SELECT * 
					FROM iShark_FormBuilder_Values
					WHERE field_id = '$field_id'
				";
				$result_values = $mdb2->query($query_values);
				while($row_values = $result_values->fetchRow()) {
					$values .= $row_values['value'].",";
				}
				
				if ($row['field_type'] == 'select' || $row['field_type'] == 'checkbox' || $row['field_type'] == 'radio') {
					$bodyonload[] = "create_fields('".$values."')";
					$bodyonload[] = "end_dis('".$row['field_type']."', 'new_answer')";
				}
				$form->setDefaults(array(
					'name'  => $row['field_name'],
					'type'  => $row['field_type'],
					'check' => $row['field_check']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name);
			exit;
		}

		//$form->addFormRule('check_modgroup');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name  = $form->getSubmitValue('name');
			$type  = $form->getSubmitValue('type');
			$check = $form->getSubmitValue('check');

			$query = "
				UPDATE iShark_FormBuilder_Fields
				set field_name = '".$name."',
				field_type = '".$type."',
				field_check = '".$check."'
				WHERE field_id = '$field_id'
			";
			$mdb2->exec($query);
			
			//régiek törlése
			$query_del = "
				DELETE FROM iShark_FormBuilder_Values WHERE field_id = '$field_id'
			";
			$result_del = $mdb2->exec($query_del);

			if ($type == "select" || $type == "checkbox" || $type == "radio") {

				$fields_num = $form->getSubmitValue('fields_num');
				for ($i = 1; $i <= $fields_num; $i++) {
					$answer = $form->getSubmitValue('answer_'.$i);
					if (isset($answer) && !empty($answer)) {
						$next_value_id = $mdb2->extended->getBeforeID('iShark_FormBuilder_Values', 'values_id', TRUE, TRUE);

						$query = "
							INSERT INTO iShark_FormBuilder_Values 
							(values_id, field_id, value) 
							VALUES 
							('$next_value_id', '$field_id', '".$answer."')
						";
						$mdb2->exec($query);
							
						$last_value_id = $mdb2->extended->getAfterID($next_value_id, 'iShark_FormBuilder_Values', 'values_id');
					}
				}
			}

			//loggolas
			logger($act, '', '');

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act=field_lst&form_id='.$form_id.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);
			exit;
		}
	} //csoport modositas vege

	$form->addElement('submit', 'submit',  $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',   $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	//valtozok atadasa a template-nek
	$tpl->assign('lang_title', $titles[$act]);
	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id);

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'field_add';
}

?>
