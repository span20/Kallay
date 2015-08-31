<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('admin\.php', $_SERVER['PHP_SELF']) || $_REQUEST['p'] != $module_name || !isset($sub_act)) {
    die('Hozzáférés megtagadva');
}

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT * 
		FROM iShark_Partners_Mails 
		WHERE mail_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('mail_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del' || $sub_act == 'send' || $sub_act == 'sendinfo') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('mail_error_none_selected'));
    return;
}

// Kuldesi adatok 
if ($sub_act == 'sendinfo') {
    $query = "
        SELECT S.title as title, S.send_date as send_date, U.user_name as sender
        FROM iShark_Partners_Mails_Sends S 
        LEFT JOIN iShark_Users U ON S.sender_user_id = U.user_id
        WHERE S.mail_id = $id
        ORDER BY send_date desc
    ";

    $table_headers = array(
        'title'     => $locale->get('mail_send_title'),
        'send_date' => $locale->get('mail_send_date'),
        'sender'    => $locale->get('mail_send_sender'),
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('mail_send_not_sent'),
        'strAdminConfirm' => $locale->get('mail_send_confirm')
    );

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Smarty hozzarendelesek
    $tpl->assign('page_data',    $paged_data['data']);
    $tpl->assign('page_list',    $paged_data['links']);
    $tpl->assign('lang_dynamic', $lang_dynamic);

    //$tpl->assign('actions_dynamic', $actions_dynamic);
    //
    $tpl->assign('table_headers', $table_headers);

    $acttpl = 'dynamic_list';
}

// Level kuldese 
if ($sub_act == 'send') {
    include_once 'HTML/QuickForm.php';
    include_once $include_dir.'/function.check.php';

    $form =& new HTML_QuickForm('send_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('mail_form_required_note'));

    $form->addElement('header', 'mailsend_header', $locale->get('mail_send_header'));
    $form->addElement('hidden', 'act',             $page);
    $form->addElement('hidden', 'sub_act',         $sub_act);
    $form->addElement('hidden', 'id',              $id);

    // GROUP select:
    $query    = "
		SELECT pg_id, group_name 
		FROM iShark_Partners_Groups 
		ORDER BY group_name
	";
    $result =& $mdb2->query($query);
    $toGroups =& $form->addElement('select', 'to_groups', $locale->get('mail_send_groups'), $result->fetchAll(0, TRUE));
	$toGroups->setMultiple(TRUE);
	$toGroups->setSize(5);

    // USER select
    $query    = "
         SELECT P.partner_id as partner_id, U.user_name as user_name
         FROM iShark_Partners P, iShark_Users U
         WHERE P.partner_id = U.user_id AND U.is_deleted = 0 AND U.is_active = 1
         ORDER BY user_name
    ";
    $result =& $mdb2->query($query);
    $toUsers =& $form->addElement('select', 'to_users', $locale->get('mail_send_users'), $result->fetchAll(0, TRUE));
    $toUsers->setMultiple(TRUE);
    $toUsers->setSize(10);

    // Adatok mentese
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));
        $groups = $toGroups->getSelected();
        $users  = $toUsers->getSelected();
        if (empty($users) && empty($groups)) {
            $form->setElementError('to_users',  $locale->get('mail_send_error_users'));
            $form->setElementError('to_groups', $locale->get('mail_send_error_users'));
        } else {
            if (empty($users)) {
                $users = array();
            }

            // CSOPORT FELHASZNALOINAK HOZZAADASA
            if (!empty($groups)) {
                $gr = '';
                foreach ($groups as $key) {
                    $gr .= (empty($gr) ? "" : " OR ")."PG.pg_id = $key";
                }
                $query = "
                    SELECT PG.partner_id as partner_id FROM 
                    iShark_Partner_PG PG, iShark_Partners P, iShark_Users U
                    WHERE PG.partner_id = P.partner_id AND U.user_id = P.partner_id 
                    AND U.is_deleted = 0 AND U.is_active = 1 AND ($gr)
                ";
                $result =& $mdb2->query($query);
                while ($row = $result->fetchRow()) {
                    if (!in_array($row['partner_id'], $users)) {
                        array_push($users, $row['partner_id']);
                    }
                }
            }
            $selected['title']   = $mdb2->escape($selected['title']);
            $selected['content'] = $mdb2->escape($selected['content']);

			$send_id = $mdb2->extended->getBeforeID('iShark_Partners_Mails_Sends', 'send_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Partners_Mails_Sends 
				(send_id, title, content, send_date, sender_user_id, mail_id) 
                VALUES 
				($send_id, '$selected[title]', '$selected[content]', now(), '$_SESSION[user_id]', '$selected[mail_id]')
			";
            $mdb2->exec($query);
			$last_send_id = $mdb2->extended->getAfterID($send_id, 'iShark_Partners_Mails_Sends', 'send_id');

            // Mail queue
            include_once 'Mail/Queue.php'; 
            $mail_queue =& new Mail_Queue($mail_queue_db_options, $mail_queue_mail_options);
            $from = $_SESSION['site_sitemail'];
            $hdrs = array(
                'From'    => $from,
                'Subject' => $locale->get('mail_send_email_subject')
            );
            $charset = $locale->getCharset();

            // Üzenet szövege:
        	$message = $locale->getBySmarty('mail_send_email_body');
            $message_text = strip_tags($message);

            $mime_params = array(
                "text_encoding" => "8bit",
                "text_charset"  => $charset,
                "head_charset"  => $charset,
                "html_charset"  => $charset,
            );

            foreach($users as $partner_id) {

                $query = "
					SELECT user_name, email 
					FROM iShark_Users 
					WHERE user_id = ".intval($partner_id)."
				";

                $result =& $mdb2->query($query);
                if ($row=$result->fetchRow()) {
                    $hdrs['To'] = $row['email']; 
                    $mime =& new Mail_mime();
                    $mime->setTXTBody($message_text);
                    $mime->setHTMLBody('<html><head><title>'.$hdrs['Subject'].'</title></head><body>'.$message.'</body></html>');

		            $mime_body = $mime->get($mime_params);
				    $mime_headers = $mime->headers($hdrs);
                    $mail_queue->put( $from, $row['email'], $mime_headers, $mime_body );

                    $query = "
                        INSERT INTO iShark_Partners_Mails_Tos 
						(partner_id, send_id) 
						VALUES 
						($partner_id, $last_send_id)
                    ";
                    $mdb2->exec($query);
                }
            }
            $query = "
				UPDATE iShark_Partners_Mails 
				SET send_date = NOW() 
				WHERE mail_id = $id
			";
            $mdb2->exec($query);

            logger($page.'_'.$sub_act);
            header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
            exit;
        }
    }

    $form->addElement('submit', 'submit', $locale->get('mail_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('mail_form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($locale->get('mail_send_header'), '#');

    $tpl->assign('lang_title', $locale->get('mail_send_header'));
    $tpl->assign('form',       $renderer->toArray());

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';

    $toGroups->setMultiple(true);
    $toUsers->setMultiple(true);
    $toGroups->setSize(5);
    $toUsers->setSize(5);
}

// Torles
if ($sub_act == 'del') {
    $query = "
        DELETE FROM iShark_Partners_Mails 
		WHERE mail_id = $id
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Hozzaadas
if ($sub_act == 'add' || $sub_act == 'mod') {
    include_once 'HTML/QuickForm.php';
    include_once $include_dir.'/function.check.php';

    $titles = array('add' => $locale->get('mail_act_add'), 'mod' => $locale->get('mail_act_mod'));

    $form =& new HTML_QuickForm('mails_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('mail_form_required_note'));

    $form->addElement('header',   'mail_header', $titles[$sub_act]);
    $form->addElement('hidden',   'act',         $page);
    $form->addElement('hidden',   'sub_act',     $sub_act);
    $form->addElement('hidden',   'id',          $id);

    //targy
    $form->addElement('text', 'title', $locale->get('mail_field_title'), 'maxlength="255"');

    //level
    $form->addElement('textarea', 'content', $locale->get('mail_field_content'), 'id="content"');

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('title',   $locale->get('mail_error_required_title'),   'required');
    $form->addRule('content', $locale->get('mail_error_required_content'), 'required');

    // Validalas
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $title   = $form->getSubmitValue('title');
        $content = $form->getSubmitValue('content');

        // Adatrekord menese
        if ($sub_act == 'mod') {
            $query = "
                UPDATE iShark_Partners_Mails 
				SET title       = '$title',
                    content     = '$content',
                    mod_user_id = ".$_SESSION['user_id']."
                WHERE mail_id = $id
			";
        } else {
			$mail_id = $mdb2->extended->getBeforeID('iShark_Partners_Mails', 'mail_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Partners_Mails 
				(mail_id, title, content, mod_user_id, add_user_id) 
				VALUES 
				($mail_id, '$title', '$content', ".$_SESSION['user_id'].", ".$_SESSION['user_id'].")
            ";
        }
        $mdb2->exec($query);

        logger($page.'_'.$sub_act);

        header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
        exit;
    }

    // default ertekek:
    if ($sub_act == 'mod' && !$form->isSubmitted()) {
        $form->setDefaults(array(
            'title'   => $selected['title'],
            'content' => $selected['content'],
        ));
    }

    $form->addElement('submit', 'submit', $locale->get('mail_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('mail_form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('lang_title', $titles[$sub_act]);
    $tpl->assign('form',       $renderer->toArray());

    // Tinymce betöltése
    $tpl->assign('tiny_fields', 'content');

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';
}

// Dinamikus lista
if ($sub_act == 'lst') {

    // Hozzaadas ikon
    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('mail_act_add'),
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'title'     => $locale->get('mail_field_title'),
        'send_date' => $locale->get('mail_send_date'),
        'adduser'   => $locale->get('mail_field_add_user'),
        'moduser'   => $locale->get('mail_field_mod_user'),
        '__act__'   => $locale->get('mail_actions'),
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('mail_warning_no_mails'),
        'strAdminConfirm' => $locale->get('mail_confirm_mail')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod'      => $locale->get('mail_act_mod'),
        'send'     => $locale->get('mail_act_send'),
        'sendinfo' => $locale->get('mail_act_sendinfo'),
        'del'      => $locale->get('mail_act_del'),
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT 
            M.mail_id as id,
            M.title as title,
            M.send_date as send_date,
            U.user_name as adduser,
            U.user_name as moduser
        FROM iShark_Partners_Mails M
        LEFT JOIN iShark_Users U on M.add_user_id = U.user_id
        LEFT JOIN iShark_Users U2 on M.mod_user_id = U2.user_id
        ORDER BY title 
    ";

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Smarty hozzarendelesek
    $tpl->assign('add_new',         $add_new);
    $tpl->assign('page_data',       $paged_data['data']);
    $tpl->assign('page_list',       $paged_data['links']);
    $tpl->assign('lang_dynamic',    $lang_dynamic);
    $tpl->assign('actions_dynamic', $actions_dynamic);
    $tpl->assign('table_headers',   $table_headers);

    // Dynamic tabla templatejenek kivalasztasa
    $acttpl = 'dynamic_list';
}
?>