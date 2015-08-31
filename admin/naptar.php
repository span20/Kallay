<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

$module_name = "naptar";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'naptar' => $locale->get('tabs_title')
);

$acts = array(
    'naptar' => array('lst', 'add', 'mod', 'del', 'fields_lst', 'fields_add', 'fields_del')
);

//aktualis ful beallitasa
$page = 'naptar';
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
if ($sub_act == "mod" || $sub_act == "add") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

    if ($sub_act == "mod") {

        $query = "
            SELECT * FROM iShark_Naptar
            WHERE id = '".$_REQUEST["id"]."'
        ";
        $result = $mdb2->query($query);
        $row = $result->fetchRow();

        //$content_picture = $filename = $row['pic'];

        $defaults = array(
            'title' => $row['title'],
            'desc' => $row['text'],
            'period' => $row['period']
        );
    }

	$titles = array('add' => $locale->get('main_title_add'), 'mod' => $locale->get('main_title_mod'));

	$form =& new HTML_QuickForm('frm_naptar', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('main_form_required_note'));

	$form->addElement('header', 'naptar', $locale->get('main_form_header'));

    //title
    $form->addElement('text', 'title', $locale->get('main_field_title'));

    $periodusok = array(
        "1" => "heti",
        "4" => "havi",
        "12" => "negyedéves",
        "24" => "féléves",
        "52" => "éves"
    );

    $tag_select =& $form->addElement('select', 'period', $locale->get('news_field_tags'), $periodusok);

	//leiras
	$form->addElement('textarea', 'desc', $locale->get('main_field_description'), 'style="width: 500px; height: 200px;"');

	$form->addElement('submit', 'submit', $locale->get('main_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('main_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('title', $locale->get('main_error_desc'), 'required');
    $form->addRule('desc', $locale->get('main_error_desc'), 'required');

    //form-hoz elemek hozzaadasa - csak modositasnal
    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);
    if ($sub_act == "mod") {
        $form->addElement('hidden', 'id', $_REQUEST["id"]);
    }

    //file betöltése
    //$filecontents = file_get_contents($theme_dir."/".$theme."/templates/".$_REQUEST["filename"].".tpl");
    if ($sub_act == "mod") {
        $form->setDefaults($defaults);
    }

    if ($form->validate()) {
        //$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $title = $form->getSubmitValue('title');
        $desc = $form->getSubmitValue('desc');
        $period = $form->getSubmitValue('period');

        if ($sub_act == "mod") {

            $query = "
                UPDATE iShark_Naptar
                SET title = '".$title."',
                    text = '".$desc."',
                    period = '".$period."'
                WHERE id = '".$_REQUEST["id"]."'
            ";
            $mdb2->exec($query);
        } else {
            $query = "
                INSERT INTO iShark_Naptar
                (title, text, period)
                VALUES
                ('".$title."', '".$desc."', '".$period."')
            ";
            $mdb2->exec($query);
        }

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

if ($sub_act == "del") {

    $del = "DELETE FROM iShark_Naptar WHERE id = '".$_REQUEST["id"]."'";
    $mdb2->exec($del);

    header('Location: admin.php?p='.$module_name.'&act='.$page);
    exit;
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {

	//atadjuk a smarty-nak a kiirando cuccokat
	$query = "
		SELECT * FROM iShark_Naptar
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    $add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('news_title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('add_new',    $add_new);
	$tpl->assign('back_arrow', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'naptar2_list';
}

if ($sub_act == "fields_lst") {
    $query = "
		SELECT * FROM iShark_Naptar_Dates WHERE esemeny_id = '".$_REQUEST["nid"]."'
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    $add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=fields_add&amp;nid='.$_REQUEST["nid"],
			'title' => $locale->get('news_title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('add_new',    $add_new);
	$tpl->assign('back_arrow', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'naptar_fields_list';
}

if ($sub_act == "fields_add") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$titles = array('fields_add' => $locale->get('main_title_add'));

	$form =& new HTML_QuickForm('frm_naptar_dates', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('main_form_required_note'));

	$form->addElement('header', 'naptar', $locale->get('main_form_header'));

    //title
    $form->addElement('text', 'title', $locale->get('main_field_title'));

	$form->addElement('submit', 'submit', $locale->get('main_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('main_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('title', $locale->get('main_error_desc'), 'required');

    //form-hoz elemek hozzaadasa - csak modositasnal
    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'nid',     $_REQUEST["nid"]);
    $form->addElement('hidden', 'sub_act', $sub_act);

    //file betöltése
    //$filecontents = file_get_contents($theme_dir."/".$theme."/templates/".$_REQUEST["filename"].".tpl");

    if ($form->validate()) {
        //$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $title = $form->getSubmitValue('title');
        $nid = $form->getSubmitValue('nid');

        $query = "
            INSERT INTO iShark_Naptar_Dates
            (name, esemeny_id)
            VALUES
            ('".$title."', '".$nid."')
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

if ($sub_act == "fields_del") {

    $del = "DELETE FROM iShark_Naptar_Dates WHERE id = '".$_REQUEST["id"]."'";
    $mdb2->exec($del);

    header('Location: admin.php?p='.$module_name.'&act='.$page);
    exit;
}

?>