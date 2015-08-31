<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('admin\.php', $_SERVER['PHP_SELF']) || $_REQUEST['p'] != $module_name || !isset($sub_act)) {
    die('Hozzáférés megtagadva');
}

$updir = ltrim($_SESSION['site_partners_pricesdir'], '/');

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT * 
		FROM iShark_Partners_Prices_Lists 
		WHERE price_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('prices_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('prices_error_none_selected'));
    return;
}

// Torles
if ($sub_act == 'del') {
    $query = "
        DELETE FROM iShark_Partners_Prices_Lists 
		WHERE price_id = $id
    ";
    $mdb2->exec($query);

    $query = "
        DELETE FROM iShark_Partners_Prices_Groups 
		WHERE price_id = $id
    ";
    $mdb2->exec($query);
    @unlink($updir.'/'.$selected['file_name']);
    logger($page.'_'.$sub_act);
    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Hozzaadas
if ($sub_act == 'add' || $sub_act == 'mod') {

    include_once 'HTML/QuickForm.php';

    $titles = array('add' => $locale->get('prices_act_add'), 'mod' => $locale->get('prices_act_mod'));

    $form =& new HTML_QuickForm('prices_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('prices_form_required_note'));

    $form->addElement('header', 'prices_header', $titles[$sub_act]);
    $form->addElement('hidden', 'act',           $page);
    $form->addElement('hidden', 'sub_act',       $sub_act);
    $form->addElement('hidden', 'id',            $id);

    //nev
    $form->addElement('text', 'name', $locale->get('prices_field_name'), 'maxlength="255"');

    //file
    $file =& $form->addElement('file', 'pricefile', $locale->get('prices_field_file'));

    // Csoportok:
    $query = "
        SELECT pg_id, group_name 
		FROM iShark_Partners_Groups 
		ORDER BY iShark_Partners_Groups.group_name
    ";
    $result =& $mdb2->query($query);
    $groups = array(0 => $locale->get('prices_field_everybody')) + $result->fetchAll(0, TRUE);

    $select =& $form->addElement('select', 'multi', $locale->get('prices_field_select'), $groups);
    $select->setMultiple(TRUE);
    $select->setSize(10);

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('name', $locale->get('prices_error_required_name'), 'required');

    // Validalas
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $name = $form->getSubmitValue('name');

        $fileName = '';
        $fileOrig = '';
        if ($sub_act == 'mod') {
            $fileName = $selected['file_name'];
            $fileOrig = $mdb2->escape($selected['file_orig']);
        }
        if ($file->isUploadedFile()) {
            $fileValues = $file->getValue();
            $fileName   = time().preg_replace('/[^a-zA-Z\d\._]/', '_', $fileValues['name']);
            $fileOrig   = $mdb2->escape($fileValues['name']);
            if (!@$file->moveUploadedFile($updir,$fileName)) {
                $form->setElementError('pricefile', $locale->get('prices_error_file_upload'));
                $fileName = '';
            }
        } elseif ($sub_act == 'add') {
            $form->setElementError('pricefile', $locale->get('prices_error_no_file'));
            $fileName = '';
        }
        if (!empty($fileName)) {
            // Adatrekord menese
            if ($sub_act == 'mod') {
                $query = "
					DELETE FROM iShark_Partners_Prices_Groups 
					WHERE price_id = $id
				"; 
                $mdb2->exec($query);

                $query = "
					UPDATE iShark_Partners_Prices_Lists 
					SET name      = '$name', 
						file_name = '$fileName', 
						file_orig = '$fileOrig' 
					WHERE price_id = $id
				";
                $mdb2->exec($query);
            } else {
				$price_id = $mdb2->extended->getBeforeID('iShark_Partners_Prices_Lists', 'price_id', TRUE, TRUE);
                $query = "
					INSERT INTO iShark_Partners_Prices_Lists 
					(price_id, name, file_name, file_orig) 
					VALUES 
					($price_id, '$name', '$fileName', '$fileOrig')
				";
                $mdb2->exec($query);
				$last_price_id = $mdb2->extended->getAfterID($price_id, 'iShark_Partners_Prices_Lists', 'price_id');
            }

            // Csoportok mentese
            $selects = $select->getSelected();
            if (!empty($selects) && in_array('0', $selects)) {
                $query = "
					INSERT INTO iShark_Partners_Prices_Groups 
					(price_id, group_id) 
					VALUES 
					($last_price_id, 0)
				";
                $mdb2->exec($query);
            } elseif(!empty($selects)) {
                $query = "
					INSERT INTO iShark_Partners_Prices_Groups 
					(price_id, group_id) 
					VALUES 
				";
                $i = FALSE;
                foreach ($selects as $key) {
                    $query .= ($i ? ',' : '') . "($last_price_id, $key)";
                    $i = TRUE;
                }
                $mdb2->exec($query);
            }
            logger($page.'_'.$sub_act);

            header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
            exit;
        }
    }

    // default ertekek:
    if ($sub_act == 'mod' && !$form->isSubmitted()) {
        $query = "
			SELECT group_id 
			FROM iShark_Partners_Prices_Groups 
			WHERE price_id = $id
		";
        $result =& $mdb2->query($query);
        $multis = $result->fetchCol();
        $form->setDefaults(array('name' => $selected['name'], 'multi' => $multis));
    }

    $form->addElement('submit', 'submit', $locale->get('prices_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('prices_form_reset'),  'class="reset"');

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
			'title' => $locale->get('prices_act_add'),
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'name'      => $locale->get('prices_field_name'),
        'file_orig' => $locale->get('prices_field_file'),
        '__act__'   => $locale->get('prices_actions'),
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('prices_no_pricelist'),
        'strAdminConfirm' => $locale->get('prices_confirm_pricelist')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $locale->get('prices_act_mod'),
        'del' => $locale->get('prices_act_del'),
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT price_id as id, name, file_orig 
        FROM iShark_Partners_Prices_Lists
        ORDER BY name
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