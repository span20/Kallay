<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('index\.php', $_SERVER['PHP_SELF']) || !isset($sub_act) || !isset($_SESSION['user_id'])) {
    die('Hozzáférés megtagadva');
}

$updir = ltrim($_SESSION['site_partners_pricesdir'], '/');

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT * 
		FROM iShark_Partner_Prices_Lists 
        WHERE price_id = $id 
        ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('prices_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'download') {
    $tpl->assign('errormsg', $locale->get('prices_error_not_selected'));
    $acttpl = 'error';
    return;
} 

if ($sub_act == 'download') {

    $mime = 'application/octet-stream';
	header("Content-type: $mime");
	header('Content-Disposition: attachment; filename="'.$selected['file_orig'].'"');
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	readfile($updir."/".$selected['file_name']);
	exit;
}

// Dinamikus lista
if ($sub_act == 'lst') {
    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'name' => $locale->get('prices_field_name'),
    );

    $table_links = array(
        'name' => 'index.php?'.$self.'&amp;act='.$page.'&amp;sub_act=download'
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strListEmpty' => $locale->get('prices_warning_empty'),
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT L.price_id as id, L.name as name, L.file_orig AS file_orig 
        FROM iShark_Partners_Prices_Lists L, iShark_Partners_Prices_Groups G, iShark_Partners_Partner_Groups PG
        WHERE L.price_id=G.price_id AND ((G.group_id=PG.pg_id AND PG.partner_id=$_SESSION[user_id]) OR G.group_id=0)
        GROUP BY L.name,L.price_id
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