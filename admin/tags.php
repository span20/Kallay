<?php

if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
    die('Hozzáférés megtagadva');
}

//modul neve
$module_name = "tags";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'tags' => $locale->get('title_tags')
);

$acts = array(
    'tags' => array('add', 'mod', 'del')
);

//aktualis ful beallitasa
$page = 'tags';
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
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);

$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);
$breadcrumb->add($tabs[$page], 'admin.php?p='.$module_name.'&amp;act='.$page);

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = intval($_REQUEST['id']);

    $query = "
        SELECT t.tag_id AS tag_id, t.tag_name AS tag_name 
        FROM iShark_Tags t 
        WHERE t.tag_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('error_no_tags'));
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_tags'));
    return;
}

// Hozzaadas, modositas
if ($sub_act == 'add' || $sub_act == 'mod') {
	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';
	include_once $include_dir.'/function.tags.php';

    $titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

    $form =& new HTML_QuickForm('tags_frm', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'agencies_header', $titles[$sub_act]);

	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);
	$form->addElement('hidden', 'id',      $id);

	$form->addElement('text',   'tagname', $locale->get('form_tagname'));
	$form->addElement('submit', 'submit',  $locale->get('form_submit'), array('class' => 'submit'));
    $form->addElement('reset',  'reset',   $locale->get('form_reset'),  array('class' => 'reset'));

	$form->applyFilter('__ALL__', 'trim');

    $form->addRule('tagname', $locale->get('error_tagname'), 'required');

	//ellenorzo fuggvenyek
	if ($sub_act == 'add') {
		$form->addFormRule('check_tags_addtag');
	}
	if ($sub_act == 'mod') {
		$form->addFormRule('check_tags_modtag');
	}

	//validalas
	if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$tagname = strtolower($form->getSubmitValue('tagname'));

		// Adatrekord menese
        if ($sub_act == 'mod') {
            $query = "
                UPDATE iShark_Tags 
				SET tag_name    = '".$tagname."', 
					mod_user_id = ".$_SESSION['user_id'].", 
					mod_date    = NOW()
                WHERE tag_id = $id
			"; 
        } else {
			$tag_id = $mdb2->extended->getBeforeID('iShark_Tags', 'tag_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Tags 
				(tag_id, tag_name, add_user_id, add_date, mod_user_id, mod_date) 
				VALUES 
				($tag_id, '$tagname', ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW())
            ";
        }
        $mdb2->exec($query);

        logger($page.'_'.$sub_act);
        header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
        exit;
	}

	//default ertekek
	if ($sub_act == 'mod' && !$form->isSubmitted()) {
        $form->setDefaults(array(
            'tagname'   => $selected['tag_name']
        ));
    }

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('lang_title', $titles[$sub_act]);
    $tpl->assign('form',       $renderer->toArray());

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';
}

// torles
if ($sub_act == 'del') {
	//kitoroljuk a tag-et
	$query = "
		DELETE FROM iShark_Tags 
		WHERE tag_id = $id
	";
	$mdb2->exec($query);

	//kitoroljuk a tag-et a moduloktol is
	$query = "
		DELETE FROM iShark_Tags_Modules
		WHERE tag_id = $id
	";
	$mdb2->exec($query);

	logger($page.'_'.$sub_act);

    header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
    exit;
}

//listazas
if ($sub_act == 'lst') {
	// Hozzaadas ikon
    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'name'    => $locale->get('list_name'),
		'aname'   => $locale->get('list_add_name'),
		'adate'   => $locale->get('list_add_date'),
		'mname'   => $locale->get('list_mod_name'),
		'mdate'   => $locale->get('list_mod_date'),
        '__act__' => $locale->get('list_actions'),
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('warning_empty'),
        'strAdminConfirm' => $locale->get('confirm_del')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $locale->get('title_mod'),
        'del' => $locale->get('title_del'),
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT t.tag_id AS id, t.tag_name AS name, u1.name AS aname, t.add_date AS adate, u2.name AS mname, t.mod_date AS mdate
        FROM iShark_Tags t 
		LEFT JOIN iShark_Users u1 ON u1.user_id = t.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = t.mod_user_id 
        ORDER BY t.tag_name
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
