<?php

// KĂśzvetlenĂźl ezt az ĂĄllomĂĄnyt kĂŠrte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("KĂśzvetlenĂźl nem lehet az ĂĄllomĂĄnyhoz hozzĂĄfĂŠrni...");
}

$module_name = "forms";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('show');

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
	$self = "p=forms";
}
$tpl->assign('back', NULL);
$tpl->assign('self', $self);

if ($act == 'show' && isset($_REQUEST['form_id']) && is_numeric($_REQUEST['form_id'])) {
	$form_id = intval($_REQUEST['form_id']);

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form_builder =& new HTML_QuickForm('frm_form_builder', 'post', 'index.php?'.$self.'&form_id='.$form_id);

	$form_builder->setRequiredNote($locale->get('form_builder_required_note'));

	$form_builder->addElement('header', 'forms',   $locale->get('field_header'));
	$form_builder->addElement('hidden', 'act',     $act);
	$form_builder->addElement('hidden', 'form_id', $form_id);
	// ha tartalomhoz van kapcsolva, akkor tudni kell, hogy hova dobjuk vissza
	if (!empty($backToCnt)) {
	    $form_builder->addElement('hidden', 'backtocnt', $backToCnt);
	} else {
	    $form_builder->addElement('hidden', 'backtocnt', '');
	}

	//form lekérdezése
	$query_form = "
		SELECT form_title, form_lead, form_after, type, datafields
		FROM iShark_Forms
		WHERE form_id = $form_id
	";
	$result_form =& $mdb2->query($query_form);
	$row_form   = $result_form->fetchRow();
	$datafields = $row_form['datafields'];
	$form_after = $row_form['form_after'];

	$form_builder->addElement('hidden', 'type',       $row_form['type']);
	$form_builder->addElement('hidden', 'datafields', $row_form['datafields']);

	//ha van nev es e-mail mezo
	if ($datafields == '1') {
		if (isset($_SESSION['user_id'])) {
			$readonly = 'readonly="readonly"';
		} else {
			$readonly = '';
		}

		//nev
		$form_builder->addElement('text', 'name',  $locale->get('field_name'),  $readonly);

		//e-mail cim
		$form_builder->addElement('text', 'email', $locale->get('field_email'), $readonly);

		//masolat sajat cimre
		$copymail =& $form_builder->addElement('checkbox', 'copymail', $locale->get('field_copymail'), null);

		//ha regisztralt, akkor kitoltjuk a mezoket
		if (isset($_SESSION['user_id'])) {
			$query_user = "
				SELECT user_name, email
				FROM ".DB_USERS.".iShark_Users
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
		$form_builder->addRule('email', $locale->get('error_email1'), 'email');
		$form_builder->addRule('email', $locale->get('error_email2'), 'required');
		$form_builder->addRule('name',  $locale->get('error_name'),   'required');
	}

	//mezők
	$query_fields = "
		SELECT * 
		FROM iShark_Forms_Fields
		WHERE form_id = $form_id AND is_active = '1' AND is_deleted = '0'
	";
	$result_fields =& $mdb2->query($query_fields);
	while($row_fields = $result_fields->fetchRow()) {
		//ertekek
		$query_values = "
			SELECT values_id, value 
			FROM iShark_Forms_Values
			WHERE field_id = '".$row_fields['field_id']."'
		";
		$result_values = $mdb2->query($query_values);

		//select mezo
		if ($row_fields['field_type'] == 'select') {
			$form_builder->addElement($row_fields['field_type'], $row_fields['field_id'], $row_fields['field_name'], $result_values->fetchAll('', $rekey = true));
		}
		//checkbox
		elseif ($row_fields['field_type'] == 'checkbox') {
			$checkbox = array();
			while($row_values = $result_values->fetchRow()){
				$checkbox[] = &HTML_QuickForm::createElement($row_fields['field_type'], $row_values['values_id'], null, $row_values['value'], 'style="width: 10px; border: 0px;"');
			}
			$form_builder->addGroup($checkbox, $row_fields['field_id'], $row_fields['field_name']);
		}
		//radio
		elseif ($row_fields['field_type'] == 'radio') {
			$radio = array();
			while($row_values = $result_values->fetchRow()){
				$radio[] = &HTML_QuickForm::createElement($row_fields['field_type'], null, null, $row_values['value'], $row_values['values_id'], 'style="width: 10px; border: 0px;"');
			}
			$form_builder->addGroup($radio, $row_fields['field_id'], $row_fields['field_name']);
		}
		//textarea
		elseif ($row_fields['field_type'] == "textarea") {
			$form_builder->addElement($row_fields['field_type'], $row_fields['field_id'], $row_fields['field_name'], array('cols' => '65', 'rows' => '10'));
		}
		//file
		elseif ($row_fields['field_type'] == "file") {
		    ${"file".$row_fields['field_id']} =& $form_builder->addElement($row_fields['field_type'], $row_fields['field_id'], $row_fields['field_name']);
		}
		//minden mas
		else {
			$form_builder->addElement($row_fields['field_type'], $row_fields['field_id'], $row_fields['field_name']);
		}

		//ellenorzesek
		$query_check = "
			SELECT *
			FROM iShark_Forms_Fields_Check
			WHERE field_id = ".$row_fields['field_id']."
		";
		$result_check =& $mdb2->query($query_check);
		if ($result_check->numRows() > 0) {
		    while ($row_check = $result_check->fetchRow())
		    {
		        if ($row_fields['field_type'] == 'file') {
		            $form_builder->addGroupRule($row_check['field_id'], $locale->get('error_no_title'), $row_check['field_check']);
		        } else {
		            $form_builder->addRule($row_check['field_id'], $locale->get('error_no_title'), $row_check['field_check']);
		        }
		    }
		}
	}

	$form_builder->applyFilter('__ALL__', 'trim');

	if ($form_builder->validate()) {
		$form_builder->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$form_id = $form_builder->getSubmitValue('form_id');
		if ($form_builder->getSubmitValue('datafields') == '1') {
			$name  = $form_builder->getSubmitValue('name');
			$email = $form_builder->getSubmitValue('email');
		}
		$type      = $form_builder->getSubmitValue('type');
		$backtocnt = $form_builder->getSubmitValue('backtocnt');

		//form
		$query_form = "
			SELECT f.form_id, f.type, f.form_title AS ftitle, fi.field_id AS field_id, fi.field_name AS field_name, 
				fi.field_type AS field_type, f.letter AS letter, f.email AS email
			FROM iShark_Forms AS f
			LEFT JOIN iShark_Forms_Fields AS fi ON fi.form_id = f.form_id
			WHERE f.form_id = $form_id AND fi.is_active = '1'
		";
		$result_form =& $mdb2->query($query_form);

        //elinditjuk a levelkuldest az elejen, ha kell
        if ($type == '1' || $type == '3') {
            ini_set('display_errors', 0);
            include_once 'Mail.php';
            include_once 'Mail/mime.php';

            $mime =& new Mail_mime("\n");
        }

        //ha e-mail vagy mindketto
        if ($type == '1' || $type == '3') {
            $msg = '<table style="width: 100%; text-align: left;">';
            if ($form_builder->getSubmitValue('datafields') == '1') {
                $msg .= '<tr><td valign="top">'.$locale->get('field_name').'</td><td>'.$name.'</td></tr>';
                $msg .= '<tr><td valign="top">'.$locale->get('field_email').'</td><td>'.$email.'</td></tr>';
            }
        }
        //ha db vagy mindketto
        if ($type == '2' || $type == '3') {
            $next_answer_id = $mdb2->extended->getBeforeID('iShark_Forms_Users', 'answer_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Forms_Users 
				(answer_id, name, email, form_id) 
				VALUES 
				($next_answer_id, '".$name."', '".$email."', $form_id)
			";
			$mdb2->exec($query);
			$last_answer_id = $mdb2->extended->getAfterID($next_answer_id, 'iShark_Forms_Users', 'answer_id');
        }

		while ($row_form = $result_form->fetchRow()) {
			$data = $form_builder->getSubmitValue($row_form['field_id']);

			// select vagy radio gombok eseten
			if ($row_form['field_type'] == 'select' || $row_form['field_type'] == 'radio') {
				$query_value = "
					SELECT *
					FROM iShark_Forms_Values
					WHERE values_id = '".$data."'
				";
				$result_value =& $mdb2->query($query_value);
				$row_value = $result_value->fetchRow();
				$data      = $row_value['value'];

                //ha e-mail vagy mindketto
                if ($type == '1' || $type == '3') {
                    $msg .= '<tr><td valign="top">'.$row_form['field_name'].'</td><td>'.str_replace( "\\r\\n", "<br />", $data ).'</td></tr>';
                }
                //ha db vagy mindketto
                if ($type == '2' || $type == '3') {
					$query_insert_answer = "
						INSERT INTO iShark_Forms_Answers
						(form_id, field_id, value_id, answers_id)
						VALUES
						($form_id, ".$row_form['field_id'].", ".$row_value['values_id'].", $last_answer_id)
					";
					$mdb2->exec($query_insert_answer);
                }
			}

			// checkbox
			elseif ($row_form['field_type'] == 'checkbox') {
				$data2 = array();

                //ha e-mail vagy mindketto
                if ($type == '1' || $type == '3') {
                    if (is_array($data) && !empty($data)) {
                        foreach ($data as $key => $value) {
                            if ($data[$key] == '1') {
                                $query_value = "
                                    SELECT *
                                    FROM iShark_Forms_Values
                                    WHERE values_id = '".$key."'
                                ";
                                $result_value =& $mdb2->query($query_value);
                                $row_value = $result_value->fetchRow();
                                $data2[]   = $row_value['value'];
                            }
                        }
                    }
                    $msg .= '<tr><td valign="top">'.$row_form['field_name'].'</td><td>';
                    foreach ($data2 as $adat) {
                        $msg .= $adat.'<br />';
                    }
                    $msg .= '</td></tr>';
                }
                //ha db vagy mindketto
                if ($type == '2' || $type == '3') {
                    if (is_array($data) && !empty($data)) {
						foreach ($data as $key => $value) {
							if ($data[$key] == '1') {
								$query_insert_answer = "
									INSERT INTO iShark_Forms_Answers
									(form_id, field_id, value_id, answers_id)
									VALUES
									('$form_id', '".$row_form['field_id']."', '".$key."', '$last_answer_id')
								";
								$mdb2->exec($query_insert_answer);
							}
						}
					}
                }
			}
			// file
			elseif ($row_form['field_type'] == 'file') {
			    if (${"file".$row_form['field_id']}->isUploadedFile()) {
					$filevalues = ${"file".$row_form['field_id']}->getValue();

                    //ha e-mail vagy mindketto
                    if ($type == '1' || $type == '3') {
                        // a file-t hozzarakjuk a levelhez csatolmanykent
                        $mime->addAttachment($filevalues['tmp_name'], $filevalues['type'], $filevalues['name']);
                    }
                    //ha db vagy mindketto
                    if ($type == '2' || $type == '3') {
                        $sdir       = "files/forms/";
    					$filename   = time().'_'.$row_form['ftitle'].'_'.$row_form['field_name'].'_'.$filevalues['name'];

    					${"file".$row_form['field_id']}->moveUploadedFile($sdir, $filename);

    					// berakjuk az adatbazisba
    					$query_insert_answer = "
							INSERT INTO iShark_Forms_Answers
							(form_id, field_id, answer, answers_id)
							VALUES
							($form_id, '".$row_form['field_id']."', '".$filename."', $last_answer_id)
						";
    					$mdb2->exec($query_insert_answer);
                    }
				}
			}
			// minden mas esetben
			else {
                //ha e-mail vagy mindketto
                if ($type == '1' || $type == '3') {
                    $msg .= '<tr><td valign="top">'.$row_form['field_name'].'</td><td>'.str_replace( "\\r\\n", "<br />", $data ).'</td></tr>';

                }
                //ha db vagy mindketto
                if ($type == '2' || $type == '3') {
                    $query_insert_answer = "
						INSERT INTO iShark_Forms_Answers
						(form_id, field_id, answer, answers_id)
						VALUES
						('$form_id', '".$row_form['field_id']."', '".str_replace( "\\r\\n", "<br />", $data )."', '$last_answer_id')
					";
					$mdb2->exec($query_insert_answer);
                }
			}
			$letter_body = $row_form['letter'];
			$emails      = $row_form['email'];
                     $ftitle      = $row_form['ftitle'];
		}
        $msg .= '</table>';
		//ha e-mail vagy mindketto
        if ($type == '1' || $type == '3') {
            $hdrs = array(
                'From'    => $_SESSION['site_sitemail'],
                'Subject' => $locale->get('mail_subject')." - ".$ftitle
            );
            $charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
				$tpl->assign('mail_body', $msg);
				$msg = $tpl->fetch('mail/mail_html.tpl');
			}

			$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
			$mime->setHTMLBody($msg);

			// Karakterkeszlet beallitasok
			$mime_params = array(
				"text_encoding" => "8bit",
				"text_charset"  => $charset,
				"head_charset"  => $charset,
				"html_charset"  => $charset,
			);

			$body = $mime->get($mime_params);
			$hdrs = $mime->headers($hdrs);
			$mail =& Mail::factory('mail');
			if (!empty($emails)) {
				$emails = explode(";", $emails);
				foreach ($emails as $em) {
					$mail->send($em, $hdrs, $body);
				}
			}
		}
        //ha db vagy mindketto
        if ($type == '2' || $type == '3') {
			$letter_body = $row_form['letter'];
		}

		// ha ki kell tolteni a nev, email mezoket is, es ker levelet, csak akkor kuldjuk el a levelet
		if ($datafields == '1' && $copymail->getChecked()) {
		    $letter_body .= $msg;
    		$mime->setTXTBody(html_entity_decode(strip_tags($letter_body)));
    		$mime->setHTMLBody($letter_body);

    		// KarakterkĂŠszlet beĂĄllĂ­tĂĄsok
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
		}

		//"fagyasztjuk" a form-ot
		$form_builder->freeze();

		if (!empty($backtocnt)) {
		    $self = $backtocnt;
		}

		$tpl->assign('form_success_msg', $form_after);
		$tpl->assign('form_back_link',   $self);
		//header("Location: index.php?".$self);
		//header('Location: index.php?success='.$row_form['form_after'].'&link='.$self);
		//exit;
	}

	$form_builder->addElement('submit', 'submit', $locale->get('submit_button'), 'class="submit"');
	$form_builder->addElement('reset',  'reset',  $locale->get('reset_button'),  'class="submit"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form_builder->accept($renderer);

	$forms[$form_id] = array("form_id" => $renderer->toArray(), "title" => $row_form["form_title"], "lead" => $row_form["form_lead"]);
	$tpl->assign('forms',   $forms);
	$tpl->assign('form_id', $form_id);

	// capture the array stucture
	ob_start();
	$form[$form_id] = $renderer->toArray();
	print_r($form);
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	$acttpl = 'forms';
}

?>