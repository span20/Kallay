<?php

// Közvetlenül ezt az állományt kérte
#if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
#    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
#}

//include_once( "includes/function.forum.php" );

$module_name = "form_builder";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst', 'show');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//menu azonosito vizsgalata
$menu_id = 0;
if (isset($_GET['mid'])) {
	$menu_id = intval($_GET['mid']);
	$self = "mid=".$menu_id;
} else {
	$self = "p=form_builder";
}
$tpl->assign('back', NULL);
$tpl->assign('self', $self);

######################################################################
### Index.php-bol valo include eseten ( !XML tartalom, muveletek ) ###
######################################################################
if ( eregi( "index.php", $_SERVER['SCRIPT_NAME'] ) && empty( $__XML_REQUEST ) ) {
	if (isset($act) && in_array($act, $is_act)) {
		
		/**
		 *  ûrlap
		 */
		
		if ($act == 'show') {
			
			if (isset($_REQUEST['form_id'])) {
			
			$form_id = intval($_REQUEST['form_id']);

			include_once 'HTML/QuickForm.php';
			include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

			$form_builder =& new HTML_QuickForm('frm_form_builder', 'post', 'index.php?'.$self);
			$form_builder->setRequiredNote( $locale->get('form_builder_required_note') );

			$form_builder->addElement('header', 'blabla', 'blabla');
			$form_builder->addElement('hidden', 'act', $act);
			

			//form lekérdezése
			$query_form = "
				SELECT form_title, form_lead, type, datafields
				FROM iShark_FormBuilder_Forms
				WHERE form_id = '$form_id'
			";
			$result_form = $mdb2->query($query_form);
			$row_form = $result_form->fetchRow();

			$tpl->assign('form_title', $row_form['form_title']);
			$tpl->assign('form_lead',  $row_form['form_lead']);
			
			if ($row_form['type'] == '0') { 
				$form_builder->addElement('hidden', 'type', 'email');
			} else {
				$form_builder->addElement('hidden', 'type', 'database');
			}
			$form_builder->addElement('hidden', 'form_id', $form_id);
			$form_builder->addElement('hidden', 'datafields', $row_form['datafields']);
			
			if ($row_form['datafields'] == '1') {
			
				if (isset($_SESSION['user_id'])) {
					$readonly = 'readonly="readonly"';
				} else {
					$readonly = '';
				}
	
				$form_builder->addElement('text', 'name',  $locale->get('field_name'), $readonly);
				$form_builder->addElement('text', 'email', $locale->get('field_email'), $readonly);
				
				if (isset($_SESSION['user_id'])) {
					$query_user = "
						SELECT user_name, email
						FROM iShark_Users
						WHERE user_id = '".$_SESSION['user_id']."'
					";
					$result_user = $mdb2->query($query_user);
					$row_user = $result_user->fetchRow();
					
					$form_builder->setDefaults(
						array(
							'name'  => $row_user['user_name'],
							'email' => $row_user['email']
						)
					);
				}
				$form_builder->addRule('email', $locale->get('error_no_title'), 'email');
				$form_builder->addRule('name', $locale->get('error_no_title'), 'required');
			}
			//mezõk
			$query_fields = "
				SELECT * 
				FROM iShark_FormBuilder_Fields
				WHERE form_id = '$form_id' and is_active = '1' and is_deleted = '0'
			";
			$result_fields = $mdb2->query($query_fields);
			while($row_fields = $result_fields->fetchRow()) {
				//értékek
				$query_values = "
					SELECT values_id, value 
					FROM iShark_FormBuilder_Values
					WHERE field_id = '".$row_fields['field_id']."'
				";
				$result_values = $mdb2->query($query_values);

				if ($row_fields['field_type'] == 'select') {
					$form_builder->addElement($row_fields['field_type'], $row_fields['field_id'], $row_fields['field_name'], $result_values->fetchAll('', $rekey = true));
				} elseif ($row_fields['field_type'] == 'checkbox') {
					$checkbox = array();
					while($row_values = $result_values->fetchRow()){
						$checkbox[] = &HTML_QuickForm::createElement($row_fields['field_type'], $row_values['values_id'], null, $row_values['value']);
					}
					$form_builder->addGroup($checkbox, $row_fields['field_id'], $row_fields['field_name']);
				} elseif ( $row_fields['field_type'] == 'radio') {
					$radio = array();
					while($row_values = $result_values->fetchRow()){
						$radio[] = &HTML_QuickForm::createElement($row_fields['field_type'], null, null, $row_values['value'], $row_values['values_id']);
					}
					$form_builder->addGroup($radio, $row_fields['field_id'], $row_fields['field_name']);
				} else {
					$form_builder->addElement($row_fields['field_type'], $row_fields['field_id'], $row_fields['field_name']);
				}
				
				if ($row_fields['field_check'] != 'none') {
					$form_builder->addRule($row_fields['field_id'], $locale->get('error_no_title'), $row_fields['field_check']);
				}
			}

			
			$form_builder->applyFilter('__ALL__', 'trim');
			//$form_builder->addRule('word', $locale->get( $module_name, 'strForumCensorErrorWord' ), 'required');

			if ($form_builder->validate()) {
				$form_builder->applyFilter('__ALL__', array(&$mdb2, 'escape'));
				
				$form_id = $form_builder->getSubmitValue('form_id');
				if ($form_builder->getSubmitValue('datafields') == '1') {
					$name    = $form_builder->getSubmitValue('name');
					$email   = $form_builder->getSubmitValue('email');
				}
				$type    = $form_builder->getSubmitValue('type');
				
				//form
				$query_form = "
					SELECT f.form_id, f.type, fi.field_id, fi.field_name, fi.field_type, f.letter
					FROM iShark_FormBuilder_Forms AS f
					LEFT JOIN iShark_FormBuilder_Fields AS fi ON fi.form_id = f.form_id
					WHERE f.form_id = '$form_id' AND fi.is_active = '1'
				";
				$result_form = $mdb2->query($query_form);
				
				ini_set('display_errors', 0);
				include_once 'Mail.php';
				include_once 'Mail/mime.php';
				
				$hdrs = array(
					'From'    => 'teszt<teszt@pte.hu>',
					'Subject' => 'ûrlap kitöltés'
				);
				$mime =& new Mail_mime("\n");
				$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();
				
				if ($type == 'email') {
					
					//$msg = $locale->get('mail_header').'<br /><br />';
					$msg = '<table style="width: 100%; text-align: left;">';
					if ($form_builder->getSubmitValue('datafields') == '1') {
						$msg .= '<tr><td valign="top">'.$locale->get('field_name').'</td><td>'.$name.'</td></tr>';
						$msg .= '<tr><td valign="top">'.$locale->get('field_email').'</td><td>'.$email.'</td></tr>';
					}
					while ($row_form = $result_form->fetchRow()) {
						$data = $form_builder->getSubmitValue($row_form['field_id']);
						if ($row_form['field_type'] == 'select' || $row_form['field_type'] == 'radio') {
							$query_value = "
								SELECT *
								FROM iShark_FormBuilder_Values
								WHERE values_id = '".$data."'
							";
							$result_value = $mdb2->query($query_value);
							$row_value = $result_value->fetchRow();
							$data = $row_value['value'];
							
							$msg .= '<tr><td valign="top">'.$row_form['field_name'].'</td><td>'.$data.'</td></tr>';
						} elseif ($row_form['field_type'] == 'checkbox') {
							$data2 = array();
							if (is_array($data) && !empty($data)) {
								foreach ($data as $key => $value) {
									if ($data[$key] == '1') {
										$query_value = "
											SELECT *
											FROM iShark_FormBuilder_Values
											WHERE values_id = '".$key."'
										";
										$result_value = $mdb2->query($query_value);
										$row_value = $result_value->fetchRow();
										$data2[] = $row_value['value'];
										
									}
								}
							}
							$msg .= '<tr><td valign="top">'.$row_form['field_name'].'</td><td>';
							foreach ($data2 as $adat) {
								$msg .= $adat.'<br />';
							}
							$msg .= '</td></tr>';
							
						} else {
							$msg .= '<tr><td valign="top">'.$row_form['field_name'].'</td><td>'.$data.'</td></tr>';
						}
						$letter_body = $row_form['letter'];
					}
					
					$msg .= '</table>';
					if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
						$tpl->assign('mail_body', $msg);
						$msg = $tpl->fetch('mail/mail_html.tpl');
					}

					$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
					$mime->setHTMLBody($msg);

					// Karakterkészlet beállítások
					$mime_params = array(
						"text_encoding" => "8bit",
						"text_charset"  => $charset,
						"head_charset"  => $charset,
						"html_charset"  => $charset,
					);

					$body = $mime->get($mime_params);
					$hdrs = $mime->headers($hdrs);
					$mail =& Mail::factory('mail');
					$mail->send("jdaniel@dolphinet.hu", $hdrs, $body);

					//"fagyasztjuk" a form-ot
					$form_builder->freeze();
				} else {
					
					$next_answer_id = $mdb2->extended->getBeforeID('iShark_FormBuilder_Users', 'answer_id', TRUE, TRUE);

					$query = "
						INSERT INTO iShark_FormBuilder_Users 
						(answer_id, name, email, form_id) 
						VALUES 
						('$next_answer_id', '".$name."', '".$email."', '$form_id')
					";
					$mdb2->exec($query);
						
					$last_answer_id = $mdb2->extended->getAfterID($next_answer_id, 'iShark_FormBuilder_Users', 'answer_id');

					while ($row_form = $result_form->fetchRow()) {
						$data = $form_builder->getSubmitValue($row_form['field_id']);
						if ($row_form['field_type'] == 'select' || $row_form['field_type'] == 'radio') {
							$query_value = "
								SELECT *
								FROM iShark_FormBuilder_Values
								WHERE values_id = '".$data."'
							";
							$result_value = $mdb2->query($query_value);
							$row_value = $result_value->fetchRow();
							$data = $row_value['value'];

							$query_insert_answer = "
								INSERT INTO iShark_FormBuilder_Answers
								(form_id, field_id, value_id, answers_id)
								VALUES
								('$form_id', '".$row_form['field_id']."', '".$row_value['values_id']."', '$last_answer_id')
							";
							$query_insert_answer = $mdb2->exec($query_insert_answer);
							
						} elseif ($row_form['field_type'] == 'checkbox') {
							$data2 = array();
							if (is_array($data) && !empty($data)) {
								foreach ($data as $key => $value) {
									if ($data[$key] == '1') {
										$query_insert_answer = "
											INSERT INTO iShark_FormBuilder_Answers
											(form_id, field_id, value_id, answers_id)
											VALUES
											('$form_id', '".$row_form['field_id']."', '".$key."', '$last_answer_id')
										";
										$query_insert_answer = $mdb2->exec($query_insert_answer);
									}
								}
							}
						} else {
							$query_insert_answer = "
								INSERT INTO iShark_FormBuilder_Answers
								(form_id, field_id, answer, answers_id)
								VALUES
								('$form_id', '".$row_form['field_id']."', '".$data."', '$last_answer_id')
							";
							$query_insert_answer = $mdb2->exec($query_insert_answer);
						}
						$letter_body = $row_form['letter'];
					}
				}
				
				$mime->setTXTBody(html_entity_decode(strip_tags($letter_body)));
				$mime->setHTMLBody($letter_body);

				// Karakterkészlet beállítások
				$mime_params = array(
					"text_encoding" => "8bit",
					"text_charset"  => $charset,
					"head_charset"  => $charset,
					"html_charset"  => $charset,
				);

				$body = $mime->get($mime_params);
				$hdrs = $mime->headers($hdrs);
				$mail =& Mail::factory('mail');
				
				$mail->send($email, $hdrs, $body);

				header("Location: index.php?$self");
				exit;
			}
			
			$form_builder->addElement('submit', 'submit', $locale->get( 'submit_button' ), 'class="submit"');
			$form_builder->addElement('reset', 'reset', $locale->get( 'reset_button' ), 'class="submit"');
			
			$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
			$form_builder->accept($renderer);
	
			$tpl->assign('form_builder', $renderer->toArray());
			
			//print_r($renderer->toArray());

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_array', ob_get_contents());
			ob_end_clean();

	
			$lang_forum = array('strForumBack' => $locale->get( 'back_button' ));
			$tpl->assign('lang_forum', $lang_forum);
			# $acttpl = 'forum_censorword';
			$forms['General_Form_Position'] = $tpl->fetch( "form_show.tpl" );
			}
		}
		

	} # $act ellenorzes vege
}

#######################################################
### Xml.php-bol valo incude eseten ( XML tartalom ) ###
#######################################################
elseif ( ( eregi( "index.php", $_SERVER['SCRIPT_NAME'] ) && !empty( $__XML_REQUEST ) && $__XML_REQUEST === TRUE )	OR eregi( "xml.php", $_SERVER['SCRIPT_NAME'] ) ) {

		if ($act == 'lst') {
			//formok lekérdezése
			$query_form = "
				SELECT form_id, form_title, form_lead
				FROM iShark_FormBuilder_Forms
				WHERE is_active = '1' and is_deleted = '0'
			";
			$result =& $mdb2->query( $query_form );
			if ( $result->numRows() > 0 ) {
				$tmp = array();
				while ( $data = $result->fetchRow() ) {
					$tmp[] = array(
						"tagName" => "form_item",
						"attrs" => array(
							"id" => $data['form_id'],
							"title" => $data['form_title']
						),
						"cData" => TRUE,
						"tagData" => $data['form_lead']
					);
				}
				$xml_data[] = array(
					"tagName" => "form_list",
					"tagData" => $tmp
				);
			}

		}

}

#####################################
### Nincs kozvetlen hozzaferes!!! ###
#####################################
else {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}


?>
