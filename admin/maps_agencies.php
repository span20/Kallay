<?php
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
if (!eregi('admin\.php', $_SERVER['PHP_SELF']) || $_REQUEST['p'] != $module_name || !isset($sub_act)) {
    die('Hozzáférés megtagadva');
}

$id = 0;
if (isset($_REQUEST['id']) && $_REQUEST['id'] != 0) {
    $id = (int) $_REQUEST['id'];
    $query = "
        SELECT R.*, S.county_id as county_id
        FROM iShark_Maps_Agencies R 
        LEFT JOIN iShark_Maps_Settlements S ON S.settlement_id=R.settlement_id
        WHERE R.agency_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $strAdminMapsErrorNotExists);
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $strAdminMapsErrorNotSelected);
    return;
}

// Torles
if ($sub_act == 'del') {
    $query = "
        DELETE FROM iShark_Maps_Agencies 
		WHERE agency_id = $id
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Hozzaadas
if ($sub_act == 'add' || $sub_act == 'mod') {

    include_once 'HTML/QuickForm.php';

    $titles = array('add' => $strAdminMapsAgenciesAdd, 'mod' => $strAdminMapsAgenciesMod);

    $form =& new HTML_QuickForm('agencies_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($strAdminMapsRequiredNote);

    $form->addElement('header', 'agencies_header', $titles[$sub_act]);

    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
    $form->addElement('hidden', 'id',      $id);

    // Varosok:
    $sel =& $form->addElement('hierselect', 'county', $strAdminMapsAgenciesCounty, 'style="width:200px;"', ' - <span style="font-weight:bold;">'.$strAdminMapsAgenciesSettlement.'</span> ');
	$query = "
		SELECT county_id, name 
		FROM iShark_Maps_Counties 
		ORDER BY name
	";
    $result =& $mdb2->query($query);
    $mainOptions = $result->fetchAll(0, TRUE);

	$query = "
		SELECT county_id, settlement_id, name 
		FROM iShark_Maps_Settlements 
		ORDER BY county_id, name
	";
    $result =& $mdb2->query($query);
    while ($row = $result->fetchRow()) {
        $secOptions[$row['county_id']][$row['settlement_id']]=$row['name'];
    }
    $sel->setOptions(array($mainOptions, $secOptions));

    $form->addElement('text', 'name',  $strAdminMapsAgenciesName,  'maxlength="255" size="30"');
    $form->addElement('text', 'phone', $strAdminMapsAgenciesPhone, 'maxlength="30" size="15"');
    $form->addElement('text', 'fax',   $strAdminMapsAgenciesFax,   'maxlength="30" size="15"');
    $form->addElement('text', 'email', $strAdminMapsAgenciesEmail, 'maxlength="255" size="30"');

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('name',  $strAdminMapsAgenciesErrorName,   'required');
    $form->addRule('email', $strAdminMapsAgenciesErrorEmail1, 'required');
    $form->addRule('email', $strAdminMapsAgenciesErrorEmail2, 'email');

    // Validalas
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $varos          = $form->getSubmitValue('county');
        $settlement_id  = intval($varos[1]);
        $name           = $form->getSubmitValue('name');
        $phone          = $form->getSubmitValue('phone');
        $fax            = $form->getSubmitValue('fax');
        $email          = $form->getSubmitValue('email');

        // Adatrekord menese
        if ($sub_act == 'mod') {
            $query = "
                UPDATE iShark_Maps_Agencies SET 
                    settlement_id = $settlement_id,
                    name          = '$name',
                    phone         = '$phone',
                    fax           = '$fax',
                    email         = '$email'
                WHERE agency_id = $id
			"; 
        } else {
			$agency_id = $mdb2->extended->getBeforeID('iShark_Maps_Agencies', 'agency_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Maps_Agencies 
				(agency_id, settlement_id, name, phone, fax, email) 
				VALUES 
				($agency_id, $settlement_id, '$name', '$phone', '$fax', '$email')
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
            'name'   => $selected['name'],
            'phone'  => $selected['phone'],
            'fax'    => $selected['fax'],
            'email'  => $selected['email'],
            'county' => array($selected['county_id'], $selected['settlement_id'])
        ));
    }

    $form->addElement('submit', 'submit', $strAdminMapsSubmit, 'class="submit"');
    $form->addElement('reset',  'reset',  $strAdminMapsReset,  'class="reset"');

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
			'title' => $strAdminMapsAgenciesNew,
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'name'    => $strAdminMapsAgenciesName,
        'phone'   => $strAdminMapsAgenciesPhone,
        'email'   => $strAdminMapsAgenciesEmail,
        '__act__' => $strAdminMapsActions,
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $strAdminMapsAgenciesEmpty,
        'strAdminConfirm' => $strAdminMapsAgenciesDeleteConfirm
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $strAdminMapsModify,
        'del' => $strAdminMapsDelete,
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT 
            agency_id as id,
            name, phone, email 
        FROM iShark_Maps_Agencies
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
