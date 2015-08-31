<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('index\.php', $_SERVER['PHP_SELF']) || !isset($sub_act) || !isset($_SESSION['user_id'])) {
    die('Hozzáférés megtagadva');
}

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT * 
		FROM iShark_Partner_News 
		WHERE news_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('news_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'show') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('news_error_not_selected'));
    return;
}

/* Hir megnezese */
if ($sub_act == 'show') {
    $tpl->assign('partner_content', $selected);
    $acttpl = 'partners_contents';
    $tpl->assign('back', 'index.php?'.$self);
}

// Dinamikus lista
if ($sub_act == 'lst') {
    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'title'    => $locale->get('news_field_title'),
        'add_date' => $locale->get('news_add_date')
    );

    $table_links = array(
        'title' => 'index.php?'.$self.'&amp;act='.$page.'&amp;sub_act=show'
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strListEmpty' => $locale->get('news_warning_empty')
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT news_id as id, title, add_date, timer_start, timer_end 
        FROM iShark_Partner_News
        WHERE timer_start='0000-00-00 00:00:00' OR (timer_end>=NOW() AND timer_start<=NOW())
        ORDER BY news_id desc
    ";

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Smarty hozzarendelesek
    $tpl->assign('page_data',     $paged_data['data']);
    $tpl->assign('page_list',     $paged_data['links']);
    $tpl->assign('lang_dynamic',  $lang_dynamic);
    $tpl->assign('table_headers', $table_headers);
    $tpl->assign('table_links',   $table_links);

    // Dynamic tabla templatejenek kivalasztasa
    $acttpl = 'dynamic_list';
}
?>