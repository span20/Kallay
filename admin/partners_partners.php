<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('admin\.php', $_SERVER['PHP_SELF']) || $_REQUEST['p'] != $module_name || !isset($sub_act)) {
    die('Hozzaferes megtagadva');
}

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT U.name as name, U.user_name as user_name, U.email as email, P.phone as phone,
            P.company as company, P.website as website, P.fax as fax, P.address as address
        FROM iShark_Partners P, iShark_Users U
        WHERE P.partner_id = $id AND U.user_id = P.partner_id AND U.is_deleted = 0
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('partners_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del' || $sub_act == 'act') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('partners_error_none_selected'));
    return;
}

// Aktivalas
if ($sub_act == 'act') {
    include_once $include_dir.'/function.check.php';

    check_active('iShark_Users', 'user_id', $id);

    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Torles
if ($sub_act == 'del') {
    $query = "
		UPDATE iShark_Users 
		SET is_deleted = 1 
		WHERE user_id = $id
	";
    $mdb2->exec($query);

    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}


// Hozzaadas, modositas
if ($sub_act == 'add' || $sub_act == 'mod') {
    include_once 'HTML/QuickForm.php';
    include_once $include_dir.'/function.check.php';

    $title = $locale->get($page.'_act_'.$sub_act);
    
    $form =& new HTML_QuickForm('partners_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    // Requirednote:
    $form->setRequiredNote($locale->get('partners_form_required_note'));

    // Hiddenek:
    $form->addElement('header', 'discounts_header', $title);
    $form->addElement('hidden', 'act',              $page);
    $form->addElement('hidden', 'sub_act',          $sub_act);
    $form->addElement('hidden', 'id',               $id);
    $form->addElement('hidden', 'uid',              $id);

    // nickname
    $form->addElement('text', 'name', $locale->get('partners_field_name'), 'maxlength="255"');

    //partner neve
    $form->addElement('text', 'user_name', $locale->get('partners_field_user_name'), 'maxLength="255" size="30"');

    //jelszavak, csak hozzaadasnal
    if ($sub_act == 'add') {
        $form->addElement('password', 'pass1', $locale->get('partners_field_pass1'), 'maxlength="30" size="10"');
        $form->addElement('password', 'pass2', $locale->get('partners_field_pass2'), 'maxlength="30" size="10"');
    }

    //cegnev
    $form->addElement('text', 'company', $locale->get('partners_field_company'), 'maxlength="255" size="40"');

    //cim
    $form->addElement('text', 'address', $locale->get('partners_field_address'), 'maxlength="255" size="60"');

    //e-mail
    $form->addElement('text', 'email', $locale->get('partners_field_email'), 'maxlength="255" size="30"');

    //telefon
    $form->addElement('text', 'phone', $locale->get('partners_field_phone'), 'maxlength="30" size="20"');

    //fax
    $form->addElement('text', 'fax', $locale->get('partners_field_fax'), 'maxlength="30" size="20"');

    //weboldal
    $form->addElement('text', 'website', $locale->get('partners_field_website'), 'maxlength="255" size="50"');

    // Groupok:
    $query = "
        SELECT pg_id, group_name 
		FROM iShark_Partners_Groups
    ";
    $result =& $mdb2->query($query);
    $groups =& $form->addElement('select', 'pgroups', $locale->get('partners_field_pgroups'), $result->fetchAll(0, TRUE));
    $groups->setMultiple(TRUE);
    $groups->setSize(5);


    // Filter:
    $form->applyFilter('__ALL__', 'trim');

    // Rule-ok:
    $form->addRule('name',      $locale->get('partners_error_required_name'),      'required');
    $form->addRule('user_name', $locale->get('partners_error_required_user_name'), 'required');
    $form->addRule('company',   $locale->get('partners_error_required_company'),   'required');
    $form->addRule('address',   $locale->get('partners_error_required_address'),   'required');
    $form->addRule('email',     $locale->get('partners_error_required_email'),     'required');
    $form->addRule('email',     $locale->get('partners_error_invalid_email'),      'email');
    $form->addRule('phone',     $locale->get('partners_error_required_phone'),     'required');
    if ($sub_act == 'add') {
        $form->addRule('pass1', $locale->get('partners_error_required_pass1'),     'required');
        $form->addRule('pass2', $locale->get('partners_error_required_pass2'),     'required');
        $form->addRule('pass1', $locale->getBySmarty('partners_error_minlength_pass'), 'minlength', $_SESSION['site_minpass']);
        $form->addFormRule('check_adduser');
        $form->addRule(array('pass1', 'pass2'), $locale->get('partners_error_compare_pass'), 'compare');
    } else {
        $form->addFormRule('check_moduser');
    }

    // Validalas:
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        // mentes
        $name      = $form->getSubmitValue('name');
        $user_name = $form->getSubmitValue('user_name');
        $pass1     = $form->getSubmitValue('pass1');
        $password  = md5($pass1);
        $email     = $form->getSubmitValue('email');

        $company   = $form->getSubmitValue('company');
        $address   = $form->getSubmitValue('address');
        $phone     = $form->getSubmitValue('phone');
        $fax       = $form->getSubmitValue('fax');
        $website   = $form->getSubmitValue('website');

        if ($sub_act == 'mod') {
            // users tablaba
            $query1 = "
                UPDATE iShark_Users 
				SET name      = '$name', 
					user_name = '$user_name',
                    email     = '$email'
                WHERE user_id = $id
            ";
            $mdb2->exec($query1);

            // partners tablabla
            $query2 = "
                UPDATE iShark_Partners 
				SET phone   = '$phone', 
					company = '$company', 
					website = '$website', 
					fax     = '$fax', 
					address = '$address'
                WHERE partner_id = $id
            ";
            $mdb2->exec($query2);

            // group beallitasok torlese
            $query3 = "
                DELETE FROM iShark_Partners_Partner_Groups
				WHERE partner_id = $id
            ";
            $mdb2->exec($query3);

        } else {
			$user_id = $mdb2->extended->getBeforeID('iShark_Users', 'user_id', TRUE, TRUE);
            $query1 = "
                INSERT INTO iShark_Users 
				(user_id, name, user_name, password, email, is_active, register_date, is_deleted, is_public, is_public_mail) 
				VALUES 
				($user_id, '$name', '$user_name', '$password', '$email', '1', NOW(), '0', '0', '0')
			";
            $mdb2->exec($query1);
			$last_user_id = $mdb2->extended->getAfterID($user_id, 'iShark_Users', 'user_id');

            $query2 = "
                INSERT INTO iShark_Partners 
				(phone, company, website, fax, address, partner_id) 
				VALUES 
				('$phone', '$company', '$website', '$fax', '$address', $last_user_id)
			";
            $mdb2->exec($query2);

            // EMAIL:
            include_once 'Mail.php';
            include_once 'Mail/mime.php';

            $headers = array(
                'From'    => $_SESSION['site_sitemail'],
                'To'      => $email,
                'Subject' => $locale->get('partners_email_subject')
            );

            $tpl->assign('langvar_partner_name', $name);
            $tpl->assign('langvar_partner_pass', $pass1);
       
            $message = $locale->getBySmarty('partners_email_body');
            $charset = $locale->getCharset();
             // Karakterkeszlet beallitasok
            $mime_params = array(
                "text_encoding" => "8bit",
                "text_charset"  => $charset,
                "head_charset"  => $charset,
                "html_charset"  => $charset,
            );

            error_reporting(E_ERROR); 
            $mime =& new Mail_mime();
            $mime->setTXTBody(strip_tags($message));
            $mime->setHTMLBody('<html><title>'.$headers['Subject'].'</title><body>'.$message.'</body></html>');

	        $mime_body    = $mime->get($mime_params);
            $mime_headers = $mime->headers($headers);

            // EMAIL KULDES
            $mail =& Mail::factory('mail');
            $mail->send($email, $mime_headers, $mime_body);
        }

        // group beallitasok mentese
        $grps = $groups->getSelected();
        if (!empty($grps)) {
            $values = "";
            foreach ($grps as $pg_id) {
                $values .= (empty($values) ? '' : ',')."('$pg_id', '$last_user_id')";
            }
            $query = "
				INSERT INTO iShark_Partners_Partner_Groups
				(pg_id, partner_id) 
				VALUES 
				$values
			";
            $mdb2->exec($query);
        }

        header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
        exit;
    }

    // Defaultok:
    if ($sub_act == 'mod' && !$form->isSubmitted()) {
        $query = "
			SELECT pg_id 
			FROM iShark_Partners_Partner_Groups
			WHERE partner_id = $id
		";
        $result =& $mdb2->query($query);
        $form->setDefaults($selected + array('pgroups' => $result->fetchCol()));
    }

    // Submitok:
    $form->addElement('submit', 'submit', $locale->get('partners_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('partners_form_reset'),  'class="reset"');

    // Array renderer:
    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    // Breadcrumb:
    $breadcrumb->add($title, '#');

    // Tpl hozzarendeles:
    $tpl->assign('lang_title', $title);
    $tpl->assign('form',       $renderer->toArray());

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';

}

if ($sub_act == 'lst') {
    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('partners_act_add'),
			'pic'   => 'add.jpg'
		)
	);

    $table_headers = array(
        'name'      => $locale->get('partners_field_name'),
        'address'   => $locale->get('partners_field_address'),
        'phone'     => $locale->get('partners_field_phone'),
        'company'   => $locale->get('partners_field_company'),
        'email'     => $locale->get('partners_field_email'),
        '__act__'   => $locale->get('partners_actions'),
    );

    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('partners_warning_no_partners'),
        'strAdminConfirm' => $locale->get('partners_confirm_partner')
    );

    $actions_dynamic = array(
        'act' => array($locale->get('partners_act_activate'), $locale->get('partners_act_deactivate')),
        'mod' => $locale->get('partners_act_mod'),
        'del' => $locale->get('partners_act_del'),
    );

    $query = "  
        SELECT 
            U.user_name as name, 
            P.address as address, 
            P.phone as phone, 
            P.company as company, 
            U.email as email,
            P.partner_id as id,
            U.is_active as is_active
        FROM iShark_Partners P, iShark_Users U
        WHERE U.user_id = P.partner_id AND U.is_deleted = 0
        ORDER BY U.name
    ";

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    $tpl->assign('page_data',       $paged_data['data']);
    $tpl->assign('page_list',       $paged_data['links']);
    $tpl->assign('add_new',         $add_new);
    $tpl->assign('lang_dynamic',    $lang_dynamic);
    $tpl->assign('actions_dynamic', $actions_dynamic);
    $tpl->assign('table_headers', $table_headers);

    $acttpl = 'dynamic_list';
}
?>