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
        FROM iShark_Maps_Resellers R 
        LEFT JOIN iShark_Maps_Settlements S ON S.settlement_id=R.settlement_id
        WHERE R.reseller_id = $id
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
        DELETE FROM iShark_Maps_Resellers 
		WHERE reseller_id = $id
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);
    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Hozzaadas
if ($sub_act == 'add' || $sub_act == 'mod') {

    include_once 'HTML/QuickForm.php';

    $titles = array('add' => $strAdminMapsResellersAdd, 'mod' => $strAdminMapsResellersMod);
    $form =& new HTML_QuickForm('discounts_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($strAdminMapsRequiredNote);

    $form->addElement('header', 'resellers_header', $titles[$sub_act]);

    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
    $form->addElement('hidden', 'id',      $id);

    // Varosok:
    $sel =& $form->addElement('hierselect', 'county', $strAdminMapsResellersCounty, 'style="width:200px;"', ' - <span style="font-weight:bold;">'.$strAdminMapsResellersSettlement.'</span> ');
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

    $form->addElement('text', 'name',    $strAdminMapsResellersName,    'maxlength="255" size="30"');
    $form->addElement('text', 'address', $strAdminMapsResellersAddress, 'maxlength="255" size="60"');
    $form->addElement('text', 'phone',   $strAdminMapsResellersPhone,   'maxlength="30" size="15"');
    $form->addElement('text', 'fax',     $strAdminMapsResellersFax,     'maxlength="30" size="15"');
    $form->addElement('text', 'email',   $strAdminMapsResellersEmail,   'maxlength="255" size="30"');
    $form->addElement('text', 'website', $strAdminMapsResellersWebsite, 'maxlength="255" size="30"');

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('name',    $strAdminMapsResellersErrorName,    'required');
    $form->addRule('address', $strAdminMapsResellersErrorAddress, 'required');
    $form->addRule('email',   $strAdminMapsResellersErrorEmail1,  'required');
    $form->addRule('email',   $strAdminMapsResellersErrorEmail2,  'email');


    // Validalas
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $varos          = $form->getSubmitValue('county');
        $settlement_id  = intval($varos[1]);
        $name           = $form->getSubmitValue('name');
        $address        = $form->getSubmitValue('address');
        $phone          = $form->getSubmitValue('phone');
        $fax            = $form->getSubmitValue('fax');
        $email          = $form->getSubmitValue('email');
        $website        = $form->getSubmitValue('website');

        // Adatrekord menese
        if ($sub_act == 'mod') {
            $query = "
                UPDATE iShark_Maps_Resellers SET 
                    settlement_id = $settlement_id,
                    name          = '$name',
                    address       = '$address',
                    phone         = '$phone',
                    fax           = '$fax',
                    email         = '$email',
                    website       = '$website'
                WHERE reseller_id = $id
			"; 
        } else {
			$reseller_id = $mdb2->extended->getBeforeID('iShark_Maps_Resellers', 'reseller_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Maps_Resellers 
				(reseller_id, settlement_id, name, address, phone, fax, email, website) 
				VALUES 
				($reseller_id, $settlement_id, '$name', '$address', '$phone', '$fax', '$email', '$website')
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
            'name'      => $selected['name'],
            'address'   => $selected['address'],
            'phone'     => $selected['phone'],
            'fax'       => $selected['fax'],
            'email'     => $selected['email'],
            'website'   => $selected['website'],
            'county'    => array($selected['county_id'], $selected['settlement_id'])
        ));
    }

    $form->addElement('submit', 'submit', $strAdminMapsSubmit, 'class="submit"');
    $form->addElement('reset',  'reset',  $strAdminMapsReset,  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('lang_title', $titles[$sub_act]);
    $tpl->assign('form', $renderer->toArray());

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';
}

// Dinamikus lista
if ($sub_act == 'lst') {

    // Hozzaadas ikon
    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $strAdminMapsResellersNew,
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'name'    => $strAdminMapsResellersName,
        'address' => $strAdminMapsResellersAddress,
        'phone'   => $strAdminMapsResellersPhone,
        'email'   => $strAdminMapsResellersEmail,
        '__act__' => $strAdminMapsActions,
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $strAdminMapsResellersEmpty,
        'strAdminConfirm' => $strAdminMapsResellersDeleteConfirm
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $strAdminMapsModify,
        'del' => $strAdminMapsDelete,
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT 
            reseller_id as id,
            name, address, phone, email 
        FROM iShark_Maps_Resellers
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

    $tpl->assign('table_headers', $table_headers);

    // Dynamic tabla templatejenek kivalasztasa
    $acttpl = 'dynamic_list';
}
?>
