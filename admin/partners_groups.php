<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('admin\.php', $_SERVER['PHP_SELF']) || $_REQUEST['p'] != $module_name || !isset($sub_act)) {
    die('Hozzáférés megtagadva');
}

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT group_name 
		FROM iShark_Partners_Groups 
		WHERE pg_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('groups_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('groups_error_none_selected'));
    return;
}

// Torles
if ($sub_act == 'del') {
    $query = "
        DELETE FROM iShark_Partners_Groups 
		WHERE pg_id = $id
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Hozzaadas
if ($sub_act == 'add' || $sub_act == 'mod') {

    include_once 'HTML/QuickForm.php';

    $titles = array('add' => $locale->get('groups_act_add'), 'mod' => $locale->get('groups_act_mod'));

    $form =& new HTML_QuickForm('group_frm', 'post', 'admin.php?p='.$module_name);

    $form->setRequiredNote($locale->get('groups_form_required_note'));

    $form->addElement('header', 'groups_header', $titles[$sub_act]);
    $form->addElement('hidden', 'act',           $page);
    $form->addElement('hidden', 'sub_act',       $sub_act);
    $form->addElement('hidden', 'id',            $id);

    //csoport neve
    $form->addElement('text', 'group_name', $locale->get('groups_field_group_name'), 'maxlength="255"');

    // default ertekek:
    if ($sub_act == 'mod') {
        $form->setDefaults($selected);
    }

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('group_name', $locale->get('groups_error_required_group_name'), 'required');

    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $group_name = $form->getSubmitValue('group_name');

        if ($sub_act == 'mod') {
            $query = "
				UPDATE iShark_Partners_Groups 
				SET group_name = '$group_name' 
				WHERE pg_id = $id
			";
        } else {
			$pg_id = $mdb2->extended->getBeforeID('iShark_Partners_Groups', 'pg_id', TRUE, TRUE);
            $query = "
				INSERT INTO iShark_Partners_Groups 
				(pg_id, group_name) 
				VALUES 
				($pg_id, '$group_name')
			";
        }
        $mdb2->exec($query);

        logger($page.'_'.$sub_act);

        header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
        exit;
    }

    $form->addElement('submit', 'submit', $locale->get('groups_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('groups_form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('lang_title', $titles[$sub_act]);
    $tpl->assign('form',       $renderer->toArray());

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';
}

// Dinamikus lista
if ($sub_act == 'lst') {

    // Hozzaadas ikon
    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('groups_act_add'),
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'group_name' => $locale->get('groups_field_group_name'),
        '__act__'    => $locale->get('groups_actions'),
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('groups_warning_no_groups'),
        'strAdminConfirm' => $locale->get('groups_confirm_group')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $locale->get('groups_act_mod'),
        'del' => $locale->get('groups_act_del'),
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT pg_id as id, group_name 
        FROM iShark_Partners_Groups 
        ORDER BY group_name
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