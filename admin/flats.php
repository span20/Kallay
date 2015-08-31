<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

$module_name = "flats";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'flats' => $locale->get('tabs_title')
);

$acts = array(
    'flats' => array('lst', 'mod')
);

//aktualis ful beallitasa
$page = 'flats';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//jogosultsag ellenorzes
if (!check_perm($page, 0, 0, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$titles = array('add' => $locale->get('main_title_add'), 'mod' => $locale->get('main_title_mod'));
    
    $tpl->assign('tiny_fields', 'desc, desc_en, desc_de');

	$form =& new HTML_QuickForm('frm_flats', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('main_form_required_note'));

	$form->addElement('header', 'flats', $locale->get('main_form_header'));

	//lakas neve
	$form->addElement('static', 'flat_name', $locale->get('flats_field_flat'));
    $form->addElement('hidden', 'flat', $locale->get('flats_field_flat'));

    $form->addElement('select', 'status', $locale->get('flat_status'), array('' => '--', '0' => 'szabad', '1' => 'lefoglalva', '2' => 'eladva'));
	//leiras
	$form->addElement('textarea', 'desc', $locale->get('main_field_description'));
    $form->addElement('textarea', 'desc_en', $locale->get('main_field_description_en'));
    $form->addElement('textarea', 'desc_de', $locale->get('main_field_description_de'));

	$form->addElement('submit', 'submit', $locale->get('main_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('main_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('desc', $locale->get('main_error_desc'), 'required');
    $form->addRule('desc_en', $locale->get('main_error_desc'), 'required');
    $form->addRule('desc_de', $locale->get('main_error_desc'), 'required');
    $form->addRule('status', $locale->get('main_error_status'), 'required');

	/**
	 * mappa modositas
	 */
    $flat_id = intval($_REQUEST['flat_id']);

    //form-hoz elemek hozzaadasa - csak modositasnal
    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
    $form->addElement('hidden', 'flat_id', $flat_id);

    //lekerdezzuk a flats tablat, es az eredmenyeket beallitjuk alapertelmezettnek
    $query = "
        SELECT *
        FROM iShark_Flats 
        WHERE id = $flat_id
    ";
    $result = $mdb2->query($query);
    if ($result->numRows() > 0) {
        while ($row = $result->fetchRow()) {
            $form->setDefaults(array(
                'flat' => $row['flat'],
                'desc' => $row['description'],
                'desc_en' => $row['desc_en'],
                'desc_de' => $row['desc_de'],
                'status' => $row['status']
                )
            );
        }
    } else {
        header('Location: admin.php?p='.$module_name);
        exit;
    }

    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $flat = $form->getSubmitValue('flat');
        $desc = $form->getSubmitValue('desc');
        $status = $form->getSubmitValue('status');
        $flat_id = $form->getSubmitValue('flat_id');
        
        $filename = str_replace(' ', '', $flat);
        $filename = str_replace('.', '', $filename);
        
        $xml_hu = '<?xml version="1.0" encoding="utf-8"?><flat><name>'.$filename.'</name><desc><![CDATA['.utf8_encode($desc).']]></desc><status>'.$status.'</status></flat>';
        $xml_en = '<?xml version="1.0" encoding="utf-8"?><flat><name>'.$filename.'</name><desc><![CDATA['.utf8_encode($desc_en).']]></desc><status>'.$status.'</status></flat>';
        $xml_de = '<?xml version="1.0" encoding="utf-8"?><flat><name>'.$filename.'</name><desc><![CDATA['.utf8_encode($desc_de).']]></desc><status>'.$status.'</status></flat>';
        
        $handle = fopen('files/flat_xmls/hu/'.$filename.'.xml', 'w+');
        fwrite($handle, $xml_hu);
        fclose($handle);
        
        $handle = fopen('files/flat_xmls/en/'.$filename.'.xml', 'w+');
        fwrite($handle, $xml_en);
        fclose($handle);
        
        $handle = fopen('files/flat_xmls/de/'.$filename.'.xml', 'w+');
        fwrite($handle, $xml_de);
        fclose($handle);

        $query = "
            UPDATE iShark_Flats 
            SET description = '".$desc."',
                desc_en = '".$desc_en."',
                desc_de = '".$desc_de."',
                status = '".$status."'
            WHERE id = $flat_id
        ";
        $mdb2->exec($query);

        //loggolas
        logger($sub_act, '', '');

        $form->freeze();

        header('Location: admin.php?p='.$module_name.'&act='.$page);
        exit;
    }

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('form',       $renderer->toArray());

	//capture the array stucture
	/*ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();*/

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
    
    $query = "
        SELECT *
        FROM iShark_Flats
        ORDER BY flat
    ";
    require_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
    $tpl->assign('page_list',  $paged_data['links']);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'flats_list';
}

?>
