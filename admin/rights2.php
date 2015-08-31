<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */

if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzáférés megtagadva');
}

//modul neve
$module_name  = "rights2";

// nyelvi allomany betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
	'rights2'   => $locale->get('title'),
);

$acts = array(
	'rights2'   => array('mod', 'del'),
);

//aktualis ful beallitasa
$page = 'rights2';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//aktualis lapszam beallitasa
if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
    $page_id = intval($_GET['pageID']);
} else {
    $page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) ||
($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_permission_denied'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
//$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);


$module_id = 0;
if (isset($_REQUEST["module_id"])) {
    $module_id = intval($_REQUEST["module_id"]);
    $query = "SELECT * FROM iShark_Modules WHERE module_id=$module_id AND is_active='1' AND is_installed='1'";
    $res =& $mdb2->query($query);
    if (!($module_data = $res->fetchRow())) {
        $acttpl = "error";
        $tpl->assign('errormsg', $locale->get('error_not_found'));
        return;
    }
} elseif ($sub_act == "mod") {
    $acttpl = "error";
    $tpl->assign("errormsg", $locale->get('error_none_selected'));
    return;
}

// Módosítás
if ($sub_act == "mod") {
    // Csoportok listaja
    $res =& $mdb2->query("SELECT group_id, group_name FROM iShark_Groups WHERE is_deleted<>'1' AND is_active='1'");
    $groups_array = $res->fetchAll(0, TRUE);
    
    // Funkciok listaja
    $res =& $mdb2->query("SELECT * FROM iShark_Functions WHERE module_id=$module_id");
    
	require_once 'HTML/QuickForm.php';
    include_once "HTML/QuickForm/Renderer/Array.php";
    
    $form =& new HTML_QuickForm("righs_frm", "post", "admin.php?p=".$module_name);
    $form->removeAttribute("name");
    $form->setRequiredNote("");
    $form->addElement("header", "frm_rights_hdr", $locale->get("title"));
    $form->addElement("hidden", "act", $page);
    $form->addElement("hidden", "sub_act", $sub_act);
    $form->addElement("hidden", "module_id", $module_id);
    
    
    $checkboxes = array();
    /* Form kirajzolás */
    while ($row = $res->fetchRow()) {
        //$form->addElement("static", "stat_".$row["function_name"], $row["function_alias"]);
        $fn_id = $row["function_id"];
        $checkboxes[$fn_id] = array();
        foreach ($groups_array as $key => $value) {
            $checkboxes[$fn_id][$key] =& HTML_QuickForm::createElement("checkbox", $key, "", $value);
        }
        $form->addGroup($checkboxes[$fn_id], "fn_".$fn_id, $row["function_alias"], "", 'id="fn_'.$fn_id.'"');
    }

    /* MENTÉS */
    if ($form->validate()) {
        $mdb2->exec("DELETE FROM iShark_Rights2 WHERE module_id=$module_id");
        foreach ($checkboxes as $fn_id => $group) {
            foreach ($group as $group_id => $value) {
                if ($value->getChecked()) {
                    $mdb2->exec("INSERT INTO iShark_Rights2 (function_id, group_id, module_id) VALUES ($fn_id, $group_id, $module_id)");
                }
            }
        }
        header("Location: $_SERVER[PHP_SELF]?p=$module_name&act=$page");
        exit;
    }
    
    /* Default értékek: */
    if (!$form->isSubmitted()) {
        $result =& $mdb2->query("SELECT function_id, group_id FROM iShark_Rights2 WHERE module_id=$module_id");
        $defaults = array();
        while ($row = $result->fetchRow()) {
            $defaults["fn_".$row["function_id"]][$row["group_id"]] = 1;
        }
        $form->setDefaults($defaults);
    }
    
    /* Submit */
    $form->addElement("submit", "sbmt", $locale->get("form_submit"), 'class="submit"');
    $form->addElement("reset",  "rst", $locale->get("form_reset"), 'class="reset"');
    
	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('back_arrow',  'admin.php?p='.$module_name.'&amp;act='.$page);
	$tpl->assign('form',        $renderer->toArray());
    
    $tpl->assign('lang_title', $module_data["module_name"]);
    $acttpl = "dynamic_form";
} 


if ($sub_act == "lst") {
    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'module_name' => $locale->get('field_module_name'),
        'type'        => $locale->get('field_module_type'),
        'description' => $locale->get('field_description'),
        '__act__'     => $locale->get('field_actions')
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('rights_warning_no_modules'),
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $locale->get('rights_act_mod'),
    );

    $query = "SELECT * FROM iShark_Modules WHERE is_installed='1' AND is_active='1' ORDER BY type, module_name";

    include_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Smarty hozzarendelesek
    $tpl->assign('page_data',       $paged_data['data']);
    $tpl->assign('page_list',       $paged_data['links']);
    $tpl->assign('lang_dynamic',    $lang_dynamic);
    $tpl->assign('actions_dynamic', $actions_dynamic);
    $tpl->assign('table_headers',   $table_headers);
    $tpl->assign('id',              'module_id');

    // Dynamic tabla templatejenek kivalasztasa
    $acttpl = 'dynamic_list';// Tablazat fejlecek dynamic list_hez
}

?>