<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "forms";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

$page = 'forms';

// fulek definialasa
$tabs = array(
	'forms' => $locale->get('title')
);

$acts = array(
    'forms' => array('lst', 'add', 'mod', 'act', 'del', 'flst', 'fadd', 'fact', 'fmod', 'fdel')
);

if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);
$tpl->assign('page_id',      $page_id);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

/**
 * urlap letrehozasa, modositasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
	//js beszurasa
	$javascripts[] = "javascript.forms";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_forms', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'form',    $locale->get('form_header'));
	$form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
	$form->addElement('hidden', 'page_id', $page_id);

	//cim
	$form->addElement('text', 'title', $locale->get('field_title'));

	//megjelenites modja
	$showtype = array();
	$showtype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_showtype_link'),   '0');
	$showtype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_showtype_inline'), '1');
	$form->addGroup($showtype, 'showtype', $locale->get('form_showtype'));

	//elkuldes modja
	$type = array();
	$type[] = &HTML_QuickForm::createElement('checkbox', 'mail', null, $locale->get('form_type_email'), array('onclick' => 'email_dis(this.value);', 'id' => 'mailchk'));
	$type[] = &HTML_QuickForm::createElement('checkbox', 'db',   null, $locale->get('form_type_database'));
	$form->addGroup($type, 'type', $locale->get('form_type'));

	//e-mail cimek
	$form->addElement('text', 'email', $locale->get('field_email'), array('id' => 'email_field'));

	//nev, email mezo hozzadasa
	$isdatafield =& $form->addElement('checkbox', 'datafield',  null, $locale->get('field_datafields'));

	//bevezeto szoveg
	$leadarea =& $form->addElement('textarea', 'lead', $locale->get('field_lead'));
	$leadarea->setCols(95);
	$leadarea->setRows(10);

	//kitoltes utani szoveg
	$afterarea =& $form->addElement('textarea', 'after', $locale->get('field_after'));
	$afterarea->setCols(95);
	$afterarea->setRows(10);

	//automatikus valasz szovege
	$form->addElement('textarea', 'letter', $locale->get('field_letter'));

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('title',     $locale->get('error_no_title'),    'required');
	$form->addRule('showtype',  $locale->get('error_no_showtype'), 'required');
	$form->addRule('lead',      $locale->get('error_no_lead'),     'required');
	$form->addRule('after',     $locale->get('error_no_after'),    'required');
    $form->addGroupRule('type', $locale->get('error_no_type'),     'required', null, 1); 
	if ($form->isSubmitted() && $_POST['type']['mail'] == '1') {
		$form->addRule('email', $locale->get('error_no_email'), 'required');
	}

	/**
	 * ha uj formot adunk hozza
	 */
	if ($sub_act == "add") {
	    $form->setDefaults(array(
	        'showtype' => 1
	        )
	    );

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$title     = $form->getSubmitValue('title');
			$lead      = $form->getSubmitValue('lead');
			$after     = $form->getSubmitValue('after');
			$ftype     = $form->getSubmitValue('type');
			$fshowtype = intval($form->getSubmitValue('showtype'));
			$email     = $form->getSubmitValue('email');
			$letter    = $form->getSubmitValue('letter');
			$datafields = $form->getSubmitValue('datafields');

            $sendtype = 0;
            if ($ftype['mail'] == 1) { $sendtype = $sendtype + 1; }
            if ($ftype['db']   == 1) { $sendtype = $sendtype + 2; }

			$query = "
				INSERT INTO iShark_Forms 
				(form_title, form_lead, form_after, add_date, add_user_id, mod_date, mod_user_id, is_active, is_deleted, type, show_type, email, letter, datafields) 
				VALUES 
				('".$title."', '".$lead."', '".$after."', NOW(), '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', '1', '0', '".$sendtype."', '".$fshowtype."','".$email."', '".$letter."', '$datafields')
			";
			$mdb2->exec($query);

			//loggolas
			logger($page.'_'.$sub_act);

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
			exit;
		}
	} //form hozzadas vege
	
	/**
	 * ha modositunk egy formot
	 */
	if ($sub_act == "mod") {
		$fid = intval($_REQUEST['id']);

		$form->addElement('hidden', 'id', $fid);

		//lekerdezzuk a tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Forms 
			WHERE form_id = $fid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
                $type = array();
                if ($row['type'] == 1) { $type = array('type' => array('mail' => 1, 'db' => 0)); }
                if ($row['type'] == 2) { $type = array('type' => array('mail' => 0, 'db' => 1)); }
                if ($row['type'] == 3) { $type = array('type' => array('mail' => 1, 'db' => 1)); }
                $form->setDefaults($type);
				$form->setDefaults(array(
					'title' 	  => $row['form_title'],
					'lead'  	  => $row['form_lead'],
					'after'       => $row['form_after'],
					'showtype' 	  => $row['show_type'],
					'letter'	  => $row['letter'],
					'datafields'  => $row['datafields'],
					'email'       => $row['email']
					)
				);
			}
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$title     = $form->getSubmitValue('title');
			$lead      = $form->getSubmitValue('lead');
			$after     = $form->getSubmitValue('after');
			$ftype     = $form->getSubmitValue('type');
			$fshowtype = intval($form->getSubmitValue('showtype'));
			$letter    = $form->getSubmitValue('letter');
			$datafields = $isdatafield->getChecked() ? 1 : 0;
			$email     = $form->getSubmitValue('email');

            $sendtype = 0;
            if (isset($ftype['mail']) && $ftype['mail'] == 1) { $sendtype = $sendtype + 1; }
            if (isset($ftype['db']) && $ftype['db'] == 1) { $sendtype = $sendtype + 2; }

			$bodyonload[] = "email_dis(".$type.")";

			$query = "
				UPDATE iShark_Forms 
				SET form_title  = '".$title."', 
					form_lead   = '".$lead."',
					form_after  = '".$after."',
					mod_date    = NOW(),
					mod_user_id = '".$_SESSION['user_id']."',
					type        = '".$sendtype."',
					show_type   = '".$fshowtype."',
					letter      = '".$letter."',
					datafields  = '".$datafields."',
					email       = '".$email."'
				WHERE form_id = $fid
			";
			$mdb2->exec($query);

			//loggolas
			logger($page.'_'.$sub_act);

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
			exit;
		}
	} //form modositas vege

	$form->addElement('submit', 'submit',  $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',   $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	//valtozok atadasa a template-nek
	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form',        $renderer->toArray());
	$tpl->assign('back_arrow',  'admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
	$tpl->assign('tiny_fields', 'letter');

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}
/**
 * ha torlunk egy formot
 */
if ($sub_act == "del") {
	$fid = intval($_GET['id']);

	$query = "
		UPDATE iShark_Forms 
		SET is_active = '0', is_deleted = '1' 
		WHERE form_id = $fid
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act=forms_lst&pageID='.$page_id);
	exit;
} //torles vege

if ($sub_act == "act") {
	include_once $include_dir.'/function.check.php';

	$form_id  = intval($_REQUEST['id']);

	check_active('iShark_Forms', 'form_id', $form_id);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
	exit;
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'ftitle'   => $locale->get('field_list_name'),
        'add_date' => $locale->get('field_list_add_date'),
        '__act__'  => $locale->get('field_list_action')
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('warning_no_forms'),
        'strAdminConfirm' => $locale->get('confirm_del')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'act'  => array($locale->get('field_list_activate'), $locale->get('field_list_deactivate')),
        'mod'  => $locale->get('field_list_modify'),
        'del'  => $locale->get('field_list_delete'),
        'flst' => $locale->get('field_list_fields'),
        'csv'  => $locale->get('field_list_csv')
    );

    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;pageID='.$page_id,
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//lekerdezzuk az adatbazisbol az ûrlapok listajat
	$query = "
		SELECT fb.form_id AS id, fb.form_title AS ftitle, fb.add_date AS add_date, fb.is_deleted AS fdel, fb.is_active AS is_active 
		FROM iShark_Forms fb
		WHERE fb.is_deleted = '0'
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',       $paged_data['data']);
	$tpl->assign('page_list',       $paged_data['links']);
	$tpl->assign('back_arrow',      'admin.php');
	$tpl->assign('add_new',         $add_new);
	$tpl->assign('lang_dynamic',    $lang_dynamic);
    $tpl->assign('actions_dynamic', $actions_dynamic);
    $tpl->assign('table_headers',   $table_headers);
    $tpl->assign('csv_link',        'admin/forms_csv.php?form_id=');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_list';
}

/**
 *
 * mezõkhöz tartozó mûveletek
 */
if ($sub_act == "fact") {
	include_once $include_dir.'/function.check.php';
	$field_id = intval($_REQUEST['field_id']);
	$form_id  = intval($_REQUEST['form_id']);

	check_active('iShark_Forms_Fields', 'field_id', $field_id);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=flst&id='.$form_id.'&pageID='.$page_id);
	exit;
}

if ($sub_act == "fdel") {
	$fid = intval($_GET['field_id']);
	$form_id  = intval($_REQUEST['form_id']);

	$query = "
		UPDATE iShark_Forms_Fields 
		SET is_active = '0', 
			is_deleted = '1' 
		WHERE field_id = $fid
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=flst&id='.$form_id.'&pageID='.$page_id);
	exit;
} //torles vege
 
if ($sub_act == "flst") {
    //breadcrumb
    $breadcrumb->add($locale->get('title_fields'), 'admin.php?p='.$module_name.'&amp;act='.$page);

	if (isset($_GET['id'])) {
		$form_id = intval($_GET['id']);

		//lekerdezzuk az adatbazisbol a mezok listajat
		$query = "
			SELECT f.field_id AS field_id, f.field_name AS fname, f.field_type AS ftype, f.is_deleted AS fdel, f.is_active AS factive 
			FROM iShark_Forms_Fields f
			WHERE f.is_deleted = '0' AND f.form_id = $form_id
		";

		//lapozo
		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		$add_new = array (
			array(
				'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=fadd&amp;form_id='.$form_id.'&amp;pageID='.$page_id,
				'title' => $locale->get('title_add'),
				'pic'   => 'add.jpg'
			)
		);

		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('page_data',  $paged_data['data']);
		$tpl->assign('page_list',  $paged_data['links']);
		$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;pageID='.$page_id);
		$tpl->assign('add_new',    $add_new);
	}

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'fields_list';
}

if ($sub_act == "fadd" || $sub_act == "fmod") {
	//js beszurasa
	$javascripts[] = "javascript.forms";
	
	$form_id = intval($_GET['form_id']);

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('fadd' => $locale->get('title_field_add'), 'fmod' => $locale->get('title_field_mod'));

	$form =& new HTML_QuickForm('frm_fields', 'post', 'admin.php?p='.$module_name.'&form_id='.$form_id);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'field',   $locale->get('form_header'));
	$form->addElement('hidden', 'page_id', $page_id);
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);

	//nev
	$form->addElement('text', 'name', $locale->get('field_fieldtitle'));

	//tipus
	$form->addElement('select', 'type', $locale->get('field_type'), array('text' => $locale->get('field_type_text'), 'select' => $locale->get('field_type_select'), 'checkbox' => $locale->get('field_type_checkbox'), 'radio' => $locale->get('field_type_radio'), 'file' => $locale->get('field_type_file'), 'textarea' => $locale->get('field_type_textarea')), array('onchange' => 'end_dis(this.value, \'new_answer\');'));

	//ellenorzes
	$checks =& $form->addElement('select', 'check', $locale->get('field_check'), array('required' => $locale->get('field_check_required'), 'numeric' => $locale->get('field_check_numeric'), 'email' => $locale->get('field_check_email')));
	$checks->setMultiple(TRUE);
	$checks->setSize(4);

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('name',  $locale->get('error_no_fieldtitle'), 'required');
	$form->addRule('type',  $locale->get('error_no_fieldtype'),  'required');

	/**
	 * ha uj mezot adunk hozza
	 */
	if ($sub_act == "fadd") {
    	if ($form->validate()) {
    		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));
    
    		$name  = $form->getSubmitValue('name');
    		$type  = $form->getSubmitValue('type');
    		$check = $form->getSubmitValue('check');
    		
    		$next_field_id = $mdb2->extended->getBeforeID('iShark_Forms_Fields', 'field_id', TRUE, TRUE);
    		$query = "
    			INSERT INTO iShark_Forms_Fields 
    			(field_id, form_id, field_name, field_type, is_active, is_deleted) 
    			VALUES 
    			('$next_field_id', '$form_id', '".$name."', '".$type."', '1', '0')
    		";
    		$mdb2->exec($query);
    		$last_field_id = $mdb2->extended->getAfterID($next_field_id, 'iShark_Forms_Fields', 'field_id');

    		//ahol tobb ertek is lehet, azokat kulon tablaba tesszuk
    		if ($type == "select" || $type == "checkbox" || $type == "radio") {
    			$fields_num = $form->getSubmitValue('fields_num');
    			for ($i = 1; $i <= $fields_num; $i++) {
    				$answer = $form->getSubmitValue('answer_'.$i);
    				if (isset($answer) && !empty($answer)) {
    					$next_value_id = $mdb2->extended->getBeforeID('iShark_Forms_Values', 'values_id', TRUE, TRUE);
    					$query = "
    						INSERT INTO iShark_Forms_Values 
    						(values_id, field_id, value) 
    						VALUES 
    						($next_value_id, $last_field_id, '".$answer."')
    					";
    					$mdb2->exec($query);
    					$last_value_id = $mdb2->extended->getAfterID($next_value_id, 'iShark_Forms_Values', 'values_id');
    				}
    			}
    		}

    		//ellenorzesek
    		if (is_array($check) && !empty($check)) {
    		    foreach ($check as $key => $value) {
    		        $query = "
						INSERT INTO iShark_Forms_Fields_Check
						(field_id, field_check)
						VALUES
						($last_field_id, '$value')
					";
    		        $mdb2->exec($query);
    		    }
    		}

    		//loggolas
    		logger($page.'_'.$sub_act);
    
    		$form->freeze();
    
    		header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=flst&id='.$form_id.'&pageID='.$page_id);
    		exit;
    	}
	} //mezo hozzadas vege
	
	/**
	 * ha modositunk egy mezot
	 */
	if ($sub_act == "fmod") {
		$field_id = intval($_REQUEST['field_id']);
		$form_id  = intval($_REQUEST['form_id']);

		$form->addElement('hidden', 'field_id', $field_id);
		$form->addElement('hidden', 'form_id',  $form_id);

		//lekerdezzuk a mezo tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT * 
			FROM iShark_Forms_Fields 
			WHERE field_id = $field_id
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$values = "";
				$query_values = "
					SELECT * 
					FROM iShark_Forms_Values
					WHERE field_id = $field_id
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
					'type'  => $row['field_type']
					)
				);

				//ellenorzesek alapertekei
				$query_check = "
					SELECT field_check
					FROM iShark_Forms_Fields_Check
					WHERE field_id = $field_id
				";
				$result_check =& $mdb2->query($query_check);
				$ch = "";
				if ($result_check->numRows() > 0) {
				    while($row_check = $result_check->fetchRow())
				    {
				        $ch .= $row_check['field_check'].",";
				    }
				}
				$checks->setSelected($ch);
			}
		} else {
			header('Location: admin.php?p='.$module_name);
			exit;
		}

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name  = $form->getSubmitValue('name');
			$type  = $form->getSubmitValue('type');
			$check = $form->getSubmitValue('check');

			$query = "
				UPDATE iShark_Forms_Fields
				SET field_name  = '".$name."',
					field_type  = '".$type."'
				WHERE field_id = $field_id
			";
			$mdb2->exec($query);
			
			//régiek törlése
			$query_del = "
				DELETE FROM iShark_Forms_Values 
				WHERE field_id = $field_id
			";
			$result_del = $mdb2->exec($query_del);

			//ahol tobb ertek is lehet, azokat kulon tablaba tesszuk
			if ($type == "select" || $type == "checkbox" || $type == "radio") {
				$fields_num = $form->getSubmitValue('fields_num');
				for ($i = 1; $i <= $fields_num; $i++) {
					$answer = $form->getSubmitValue('answer_'.$i);
					if (isset($answer) && !empty($answer)) {
						$next_value_id = $mdb2->extended->getBeforeID('iShark_Forms_Values', 'values_id', TRUE, TRUE);
						$query = "
							INSERT INTO iShark_Forms_Values 
							(values_id, field_id, value) 
							VALUES 
							($next_value_id, $field_id, '".$answer."')
						";
						$mdb2->exec($query);
					}
				}
			}

			//ellenorzesek
			$query_delcheck = "
				DELETE FROM iShark_Forms_Fields_Check
				WHERE field_id = $field_id
			";
			$mdb2->exec($query_delcheck);

    		if (is_array($check) && !empty($check)) {
    		    foreach ($check as $key => $value) {
    		        $query = "
						INSERT INTO iShark_Forms_Fields_Check
						(field_id, field_check)
						VALUES
						($field_id, '$value')
					";
    		        $mdb2->exec($query);
    		    }
    		}

			//loggolas
			logger($page.'_'.$sub_act);

			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=flst&id='.$form_id.'&pageID='.$page_id);
			exit;
		}
	} //mezo modositas vege

	$form->addElement('submit', 'submit',  $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',   $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($locale->get('title_fields'), 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=flst&amp;id='.$form_id);
	$breadcrumb->add($titles[$sub_act], '#');

	//valtozok atadasa a template-nek
	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=flst&amp;id='.$form_id);

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'fields_add';
}

?>