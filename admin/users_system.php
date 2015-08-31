<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "users";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod');

//ezek az elfogadhato almuveleti hivasok ($_REQUEST['type'])
$is_type = array('lst', 'add', 'mod', 'del');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, NULL, 1, 'system')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('system_error_no_permission'));
	return;
}

if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], $is_type)) {
	$type = $_REQUEST['type'];

	if ($type == "add" || $type == "mod") {
		$ftype_array = array(
			"int"      => "INT", 
			"varchar"  => "VARCHAR", 
			"char"     => "CHAR", 
			"text"     => "TEXT", 
			"datetime" => "DATETIME"
		);

		$qtype_array = array(
			"text"     => "text", 
			"file"     => "file", 
			"textarea" => "textarea", 
			"date"     => "date"
		);

		$check_num   = 6;
		$check_array = array(
			"required"      => "required", 
			"numeric"       => "numeric", 
			"nonzero"       => "nonzero", 
			"email"         => "email", 
			"lettersonly"   => "lettersonly", 
			"alphanumeric"  => "alphanumeric", 
			"nopunctuation" => "nopunctuation"
		);

		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		require_once $include_dir.'/function.check.php';

		$form =& new HTML_QuickForm('frm_users', 'post', 'admin.php?p=users_system');
		$form->removeAttribute('name');

		$form->setRequiredNote($locale->get('system_form_required_note'));

		$form->addElement('header', 'users',   $locale->get('system_form_header'));
		$form->addElement('static', 'static1', $locale->get('system_field_title1'));

		//azonosito
		$form->addElement('text', 'value', $locale->get('system_field_value'));

		//adatbazismezo tipusa
		$form->addElement('select', 'ftype', $locale->get('system_field_type'), $ftype_array);

		//mezo hossza
		$form->addElement('text', 'length', $locale->get('system_field_length'));

		//alapertelmezett
		$form->addElement('text', 'default', $locale->get('system_field_default'));

		//qf szovegmezo
		$form->addElement('static', 'static2', $locale->get('system_field_title2'));

		//qf tipus
		$form->addElement('select', 'qtype', $locale->get('system_field_qftype'), $qtype_array);

		//qf megjelenitett szoveg
		$form->addElement('text', 'display', $locale->get('system_field_display'));

		//ellenorzesek szoveg
		$form->addElement('static', 'static3', $locale->get('system_field_title3'));

		for ($i = 1; $i < $check_num; $i++) {
			${'chk'.$i} = NULL;
			${'txt'.$i} = NULL;
			$chk        = ${'chk'.$i};
			$txt        = ${'txt'.$i};

			$chk =& HTML_QuickForm::createElement('select', 'chk', $locale->get('system_field_check'), $check_array);
			$txt =& HTML_QuickForm::createElement('text',   'txt', $locale->get('system_field_errortext'));
			$form->addGroup(array($chk, $txt), 'check'.$i, $i.'.'.$locale->get('system_field_checkmain'), ',&nbsp');
		}

		//szurok beallitasa
		$form->applyFilter('__ALL__', 'trim');

		//szabalyok beallitasa
		$form->addRule(     'value',    $locale->get('system_error_value'),    'required');
		$form->addGroupRule('ftype',    $locale->get('system_error_type'),     'required');
		$form->addGroupRule('qtype',    $locale->get('system_error_qftype'),   'required');
		$form->addRule(     'display',  $locale->get('system_error_display'),  'required');
		$form->addFormRule( 'checkUserExtraFields');

		if ($type == "add") {
			$lang_title = $locale->get('system_title_add');

			//form-hoz elemek hozzaadasa - csak hozzaadasnal
			$form->addElement('hidden', 'act',  'mod');
			$form->addElement('hidden', 'type', 'add');

			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$value    = $form->getSubmitValue('value');
				$qtype    = $form->getSubmitValue('qtype');
				$display  = $form->getSubmitValue('display');
				$ftype    = $form->getSubmitValue('ftype');
				$length   = intval($form->getSubmitValue('length'));
				$default  = $form->getSubmitValue('default');

				//beszurjuk a termek tablaba az uj mezot
				$query = "
					ALTER TABLE ".DB_USERS.".iShark_Users 
					ADD $value $ftype ($length)
				";
				if ($default != "") {
					$query .= "DEFAULT '$default'";
				}
				$mdb2->exec($query);

				$prop_id = $mdb2->extended->getBeforeID('iShark_Users_Properties', 'prop_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Users_Properties 
					(prop_id, prop_value, prop_type, prop_display) 
					VALUES 
					($prop_id, '".$value."', '".$qtype."', '".$display."')
				";
				$mdb2->exec($query);
				$last_prop_id = $mdb2->extended->getAfterID($prop_id, 'iShark_Users_Properties', 'prop_id');

				//ellenorzesek beszurasa
				for ($i = 1; $i < $check_num; $i++) {
					$check = $form->getSubmitValue('check'.$i);
					if (is_array($check) && count($check) > 0) {
						if ($check['txt'] != "") {
							$query = "
								INSERT INTO iShark_Users_Properties_Check 
								(prop_id, error_check, error_txt) 
								VALUES 
								($last_prop_id, '".$check['chk']."', '".$check['txt']."')
							";
							$mdb2->exec($query);
						}
					}
				}

				//loggolas
				logger($act, '', '');

				//"fagyasztjuk" a form-ot
				$form->freeze();

				//visszadobjuk a lista oldalra
				header('Location: admin.php?p=users_system');
				exit;
			}
		}

		if ($type == "mod") {
			$lang_title = $locale->get('system_title_mod');

			if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid'])) {
				$pid = intval($_REQUEST['pid']);

				//lekerdezzuk, hogy tenyleg letezik-e ez a mezo
				$query = "
					SELECT p.prop_id AS pid, p.prop_value AS pvalue, p.prop_type AS ptype, p.prop_display AS pdisplay 
					FROM iShark_Users_Properties p 
					WHERE p.prop_id = $pid
				";
				$result = $mdb2->query($query);
				if ($result->numRows() > 0) {
					//form-hoz elemek hozzaadasa - csak modositasnal
					$form->addElement('hidden', 'act',  'mod');
					$form->addElement('hidden', 'type', 'mod');

					while ($row = $result->fetchRow())
					{
						$value = $row['pvalue'];
						$form->addElement('hidden', 'pid',  $pid);
						$form->addElement('hidden', 'pval', $value);

						//beallitjuk az alapertelmezett ertekeket, csak modositasnal
						$form->setDefaults(array(
							'value'   => $value,
							'qtype'   => $row['ptype'],
							'display' => $row['pdisplay']
							)
						);

						//lekerdezzuk a felhasznalo tablahoz adott mezo adatait
						$query2 = "
							SHOW columns 
							FROM ".DB_USERS.".iShark_Users LIKE '$value' 
						";
						$result2 =& $mdb2->query($query2);
						if ($result2->numRows() > 0) {
							while ($row2 = $result2->fetchRow(MDB2_FETCHMODE_ORDERED))
							{
								//kiszedjuk a mezo tipusat
								preg_match('/(int|varchar|char|text|datetime)\((\d{1,3})\)/', $row2[1], $matches);
								$form->setDefaults(array(
									'ftype'   => $matches[1],
									'length'  => $matches[2],
									'default' => $row2[5]
									)
								);
							}
						}

						//lekerdezzuk az ellenorzesek tablahoz tartozo adatokat
						$query3 = "
							SELECT error_check, error_txt 
							FROM iShark_Users_Properties_Check 
							WHERE prop_id = $pid
						";
						$result3 =& $mdb2->query($query3);
						if ($result3->numRows() > 0) {
							$i = 1;
							while ($row3 = $result3->fetchRow())
							{
								$form->setDefaults(array(
									'check'.$i.'[chk]' => $row3['error_check'],
									'check'.$i.'[txt]' => $row3['error_txt']
									)
								);
								$i++;
							}
						}

						if ($form->validate()) {
							$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

							$pid      = intval($form->getSubmitValue('pid'));
							$value    = $form->getSubmitValue('value');
							$qtype    = $form->getSubmitValue('qtype');
							$display  = $form->getSubmitValue('display');
							$ftype    = $form->getSubmitValue('ftype');
							$length   = intval($form->getSubmitValue('length'));
							$default  = $form->getSubmitValue('default');
							$oldval   = $form->getSubmitValue('pval');

							$query = "
								ALTER TABLE ".DB_USERS.".iShark_Users 
								CHANGE $oldval $value $ftype ($length)
							";
							if ($default != "") {
								$query .= "DEFAULT '$default'";
							}
							$mdb2->exec($query);

							$query = "
								UPDATE iShark_Users_Properties 
								SET prop_value   = '".$value."', 
									prop_type    = '".$qtype."', 
									prop_display = '".$display."'
								WHERE prop_id = $pid
							";
							$mdb2->exec($query);

							//ellenorzesek beszurasa
							$query = "
								DELETE FROM iShark_Users_Properties_Check 
								WHERE prop_id = $pid
							";
							$mdb2->query($query);
							for ($i = 1; $i < $check_num; $i++) {
								$check = $form->getSubmitValue('check'.$i);
								if (is_array($check) && count($check) > 0) {
									if ($check['txt'] != "") {
										$query = "
											INSERT INTO iShark_Users_Properties_Check 
											(prop_id, error_check, error_txt) 
											VALUES 
											($pid, '".$check['chk']."', '".$check['txt']."')
										";
										$mdb2->exec($query);
									}
								}
							}

							//loggolas
							logger($act, '', '');

							//"fagyasztjuk" a form-ot
							$form->freeze();

							//visszadobjuk a lista oldalra
							header('Location: admin.php?p=users_system');
							exit;
						}
					}
				} else {
					$acttpl = 'error';
					$tpl->assign('errormsg', $locale->get('system_error_notexists'));
					return;
				}
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('system_error_notexists'));
				return;
			}
		}

		$form->addElement('submit', 'submit', $locale->get('system_form_submit'), 'class="submit"');
		$form->addElement('reset',  'reset',  $locale->get('system_form_reset'),  'class="reset"');

		$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
		$form->accept($renderer);

		$tpl->assign('form',  $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('dynamic_form', ob_get_contents());
		$tpl->assign('lang_title',   $lang_title);
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = "dynamic_form";
	}

	/**
	 * ha torlunk egy mezot
	 */
	if ($type == "del") {
		if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
			$pid = intval($_GET['pid']);

			//lekerdezzuk a mezo nevet, mert ez alapjan toroljuk a termekektol a mezot
			$query = "
				SELECT prop_value 
				FROM ".DB_USERS.".iShark_Users 
				WHERE prop_id = $pid
			";
			$result = $mdb2->query($query);
			if ($result->numRows() > 0) {
				$row        = $result->fetchRow();
				$prop_value = $row['prop_value'];

				$query = "
					ALTER TABLE ".DB_USERS.".iShark_Users 
					DROP $prop_value
				";
				$mdb2->exec($query);

				$query = "
					DELETE FROM iShark_Users_Properties 
					WHERE prop_id = $pid
				";
				$mdb2->exec($query);

				$query = "
					DELETE FROM iShark_Users_Properties_Check 
					WHERE prop_id = $pid
				";
				$mdb2->exec($query);

				//loggolas
				logger($act, '', '');
			}
		}

		header('Location: admin.php?p=users_system');
		exit;
	} //torles vege
}

/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
    $add_new = array(
		array(
			'link'  => "admin.php?p=users_system&amp;act=mod&amp;type=add",
			'title' => $locale->get('system_title_new'),
			'pic'   => 'add.jpg'
		)
	);

	$query = "
		SELECT p.prop_id AS pid, p.prop_value AS pvalue, p.prop_type AS ptype, p.prop_display AS pdisplay 
		FROM iShark_Users_Properties p 
		ORDER BY p.prop_value 
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('add_new',   $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'users_system_list';
}
?>
