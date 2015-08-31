<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('index\.php', $_SERVER['PHP_SELF']) || !isset($sub_act) || !isset($_SESSION['user_id'])) {
    die('Hozzáférés megtagadva');
}

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT S.title as title, S.content AS description, S.send_date as send_date, U.user_name as user_name
        FROM iShark_Partners_Mails_Tos T, iShark_Partners_Mails_Sends S
        LEFT JOIN iShark_Users U ON S.sender_user_id = U.user_id
        WHERE T.partner_id = ".$_SESSION['user_id']." AND T.send_id = $id
            AND T.is_deleted=0 AND T.send_id=S.send_id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('mails_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'show') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('mails_error_not_selected'));
    return;
}

// dynamic listhez szukseges nyelvi mezok
$lang_dynamic = array(
    'strListEmpty'  => $locale->get('mails_warning_empty'),
    'strDelete'     => $locale->get('mails_field_delete'),
    'strMailSender' => $locale->get('mails_field_sender'),
    'strMailDate'   => $locale->get('mails_field_date')
);

/* Torles */
if ($sub_act == 'del') {
    if (!isset($_POST['mailids'])) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('mails_error_not_selected'));
        return;
    }
    foreach ($_POST['mailids'] as $item) {
        $send_id = (int) $item;
        $query = "
            UPDATE iShark_Partners_Mails_Tos 
			SET is_deleted = '1' 
            WHERE partner_id = ".$_SESSION['user_id']." AND send_id = $send_id
        ";
        $mdb2->exec($query);
    }
    header("Location: index.php?".$self.'&act='.$page); 
    exit; 
}
 
/* Hir megnezese */
if ($sub_act == 'show') {
    $tpl->assign('partner_content', $selected);
	$tpl->assign('back',            'index.php?'.$self);

    $acttpl = 'partners_contents';
}

// Dinamikus lista
if ($sub_act == 'lst') {
    $javascripts[] = 'javascript.partners';

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'title'        => $locale->get('mails_field_title'),
        'user_name'    => $locale->get('mails_field_sender'),
        'send_date'    => $locale->get('mails_field_date'),
        '__checkbox__' => $locale->get('mails_field_checkall')
    );
    $table_links = array(
        'title' => 'index.php?'.$self.'&amp;act='.$page.'&amp;sub_act=show'
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT 
            T.is_read as is_read, 
            T.send_id as id, 
            S.title as title, 
            REPLACE(S.send_date, '-', '.') as send_date,
            U.user_name as user_name 
        FROM iShark_Partners_Mails_Tos T, iShark_Partners_Mails_Sends S
        LEFT JOIN iShark_Users U ON S.sender_user_id=U.user_id
        WHERE T.partner_id=$_SESSION[user_id] AND T.is_deleted='0' AND S.send_id=T.send_id 
        ORDER BY T.send_id DESC
	";

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Smarty hozzarendelesek
    $tpl->assign('page_data',     $paged_data['data']);
    $tpl->assign('page_list',     $paged_data['links']);
    $tpl->assign('table_headers', $table_headers);
    $tpl->assign('table_links',   $table_links);

    // Dynamic tabla templatejenek kivalasztasa
    $acttpl = 'dynamic_list';
}
$tpl->assign('lang_dynamic', $lang_dynamic);

?>