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
		FROM iShark_Partners_Discounts 
		WHERE discount_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('discounts_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'show') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('discounts_error_not_selected'));
    return;
}

/* Hir megnezese */
if ($sub_act == 'show') {
    $tpl->assign('partner_content', $selected);
	$tpl->assign('back',            'index.php?'.$self);

    $acttpl = 'partners_contents';
}

// Dinamikus lista
if ($sub_act == 'lst') {
    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'title'       => $locale->get('discounts_field_title'),
        'timer_start' => $locale->get('discounts_field_start'),
        'timer_end'   => $locale->get('discounts_field_end')
    );

    $table_links = array(
        'title' => 'index.php?'.$self.'&amp;act='.$page.'&amp;sub_act=show'
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strListEmpty' => $locale->get('discounts_warning_empty')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT discount_id as id, title, 
            IF(timer_start='0000-00-00 00:00:00','',REPLACE(timer_start,'-','.')) as timer_start, 
            IF(timer_end ='0000-00-00 00:00:00','',REPLACE(timer_end,'-','.')) as timer_end
        FROM iShark_Partners_Discounts
        WHERE (timer_start='0000-00-00 00:00:00' and timer_end='0000-00-00 00:00:00') OR 
              (timer_end>=NOW() AND timer_start<=NOW())
        ORDER BY discount_id desc
    ";

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Smarty hozzarendelesek
    $tpl->assign('page_data',       $paged_data['data']);
    $tpl->assign('page_list',       $paged_data['links']);
    $tpl->assign('lang_dynamic',    $lang_dynamic);
    $tpl->assign('actions_dynamic', $actions_dynamic);

    $tpl->assign('table_headers', $table_headers);
    $tpl->assign('table_links',   $table_links);

    // Dynamic tabla templatejenek kivalasztasa
    $acttpl = 'dynamic_list';
}
?>