<?php

/**
 * Muveletek kezelese
 */

$locale_id = $locale->lang;

if (isset($_REQUEST['locale_id']) && array_key_exists($_REQUEST['locale_id'], $locales_array)) {
    $locale_id = $_REQUEST['locale_id'];
}

$area_id     = 0;
$variable_id = 0;

/* Parameterben megadott valtozo lekerdezese */
if (isset($_REQUEST['variable_id']) && $_REQUEST['variable_id'] != 0) {
    $variable_id = intval($_REQUEST['variable_id']);
    if (!$variable = $locale->getVariable($variable_id)) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('error_not_exists'));
        return;
    }
    $_REQUEST['area_id'] = $variable['area_id'];
} elseif (in_array($sub_act, array('w_mod', 'w_del'))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_none_selected'));
    return;
}

/* Parameterben megadott modul lekerdezese */
if (isset($_REQUEST['area_id']) && $_REQUEST['area_id']!=0) {
    $area_id = intval($_REQUEST['area_id']);
    if (!$area = $locale->getArea($area_id)) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('error_not_exists'));
        return;
    }
} elseif (in_array($sub_act, array('del', 'mod', 'w_lst', 'w_add', 'export'))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_none_selected'));
    return;
}

/* Nyelvi adatok exportalasa xml-be */
if ($sub_act == 'export') {
    $expressions =& $locale->getExpressions($locale_id, $area_id);
    $charset = $locale->getCharset($locale_id);
    //print_r($locale->locales_array);
    $tpl->assign('_charset',     $charset);
    $tpl->assign('_module_name', $area['area_name']);
    $tpl->assign('_locale_id',   $locale_id);
    $tpl->assign('_expressions', $expressions);

    header("Content-Type: text/xml");
    header('Content-Disposition: attachment; filename="locale_'.$area['area_name'].'_'.$locale_id.'.xml"');
	header("Pragma: public");
    header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

    $tpl->display('admin/langs_export.tpl');
    exit;
}

/* Nyelvi adatok betoltese xml-bol */
if ($sub_act == 'import') {
    $acttpl = 'dynamic_form';
    include_once 'HTML/QuickForm.php';

    $title = $locale->get('title_'.$sub_act);
    $form =& new HTML_QuickForm('import_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('form_required_note'));
    $form->addElement('header', 'lang_header', $title);
    $form->addElement('hidden', 'locale_id',   $locale_id);
    $form->addElement('hidden', 'act',         $page);
    $form->addElement('hidden', 'sub_act',     $sub_act);
    $file =& $form->addElement('file', 'field_xml_file', $locale->get('field_xml_file'));

//    $form->addRule('field_xml_file',   $locale->get('error_required_xml_file'),   'required');

    /* form validalas */
    if ($form->validate()) {
        if ($file->isUploadedFile()) {
            $filevalues = $file->getValue();

            $extension = strtolower(strrchr($filevalues['name'],"."));
            if ($extension == ".xml") {
                $parsed = $locale->parseXML($filevalues['tmp_name']);
                @unlink($filevalues['tmp_name']);
                if (PEAR::isError($parsed)) {
                    die($parsed->getMessage());
                    $form->setElementError('field_xml_file', $locale->get('error_parsing_xml').' ('.$parsed->getMessage()).')';
                } else {
                    header("Location: $_SERVER[PHP_SELF]?p=$module_name&locale_id=$locale_id");
                    exit;
                }
            } else {
                $form->setElementError('field_xml_file', $locale->get('error_not_an_xml'));
            }
        } else {
            $form->setElementError('field_xml_file', $locale->get('error_no_uploaded_file'));
        }
    }

    $form->addElement('submit', 'form_submit', $locale->get('form_submit'), 'class="submit"');
    $form->addElement('reset',  'form_reset',  $locale->get('form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($title, '#');
    $tpl->assign('lang_title', $title);
    $tpl->assign('form',       $renderer->toArray());
    $tpl->assign('back_arrow', $_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;locale_id='.$locale_id);
}


/* nyelv torlese */
if ($sub_act == 'del_lang') {
    if ($locale_id != $locale->getFallback()) {
        $locale->delLocale($locale_id);
    }
    header("Location: $_SERVER[PHP_SELF]?p=$module_name");
    exit;
}


/* Nyelv hozzaadasa, modositasa */
if ($sub_act == 'add_lang' || $sub_act == 'mod_lang') {
    $acttpl = 'dynamic_form';
    include_once 'HTML/QuickForm.php';

    $title = $locale->get('title_'.$sub_act);
    $form =& new HTML_QuickForm('lang_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('form_required_note'));
    $form->addElement('header', 'lang_header', $title);
    $form->addElement('hidden', 'locale_id',   $locale_id);
    $form->addElement('hidden', 'act',         $page);
    $form->addElement('hidden', 'sub_act',     $sub_act);
    $f_id =& $form->addElement('text', 'field_locale_id', $locale->get('field_locale_id'),  'maxlength="2" size="2"');
    $form->addElement('text', 'field_locale_name',    $locale->get('field_locale_name'),    'maxlength="20"');
    $form->addElement('text', 'field_locale_charset', $locale->get('field_locale_charset'), 'maxlength="20"');

    $form->applyFilter('__ALL__', 'trim');
    $form->addRule('field_locale_id',   $locale->get('error_required_locale_id'),   'required');
    $form->addRule('field_locale_name', $locale->get('error_required_locale_name'), 'required');

    if ($sub_act == 'mod_lang') {
        $f_id->freeze();
    }

    /* form validalas */
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $f_locale_id      = $form->getSubmitValue('field_locale_id');
        $f_locale_name    = $form->getSubmitValue('field_locale_name');
        $f_locale_charset = $form->getSubmitValue('field_locale_charset');
        if (empty($f_locale_charset)) {
            $f_locale_charset = "UTF-8";
        }
        if ($sub_act == 'add_lang' && isset($locales_array[$f_locale_id])) {
            $form->setElementError('field_locale_id', $locale->get('error_exists_locale_id'));
        } else {
            $locale->addLocale($f_locale_id, $f_locale_name, $f_locale_charset);
            logger($module_name.'_'.$sub_act);
            header("Location: $_SERVER[PHP_SELF]?p=$module_name&locale_id=$locale_id");
            exit;
        }
    }

    /* default ertekek: */
    if ($sub_act == "mod_lang" && !$form->isSubmitted()) {
        $form->setDefaults(array(
            'field_locale_id'      => $locale_id,
            'field_locale_name'    => $locales_array[$locale_id],
            'field_locale_charset' => $locale->getCharset($locale_id)
        ));
    }

    $form->addElement('submit', 'form_submit', $locale->get('form_submit'), 'class="submit"');
    $form->addElement('reset',  'form_reset',  $locale->get('form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($title, '#');
    $tpl->assign('lang_title', $title);
    $tpl->assign('form',       $renderer->toArray());
    $tpl->assign('back_arrow', $_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;locale_id='.$locale_id);
}

/* Kifejezes torlese */
if ($sub_act == 'w_del') {
    $locale->delVariable($variable_id);

    if (isset($_GET['s']) && is_numeric($_GET['s']) && $_GET['s'] == 1 && isset($_GET['searchtext']) && isset($_GET['searchtype'])) {
        header("Location: $_SERVER[PHP_SELF]?p=$module_name&act=search&sub_act=lst&searchtext=".$_GET['searchtext']."&searchtype=".$_GET['searchtype']."&pageID=".$_GET['pageID']);
        exit;
    } else {
        header("Location: $_SERVER[PHP_SELF]?p=${module_name}&sub_act=w_lst&area_id=${area_id}&locale_id=${locale_id}");
        exit;
    }
}

/* Kifejezes hozzaadasa - modosi�tasa */
if ($sub_act == 'w_add' || $sub_act == 'w_mod') {
    $acttpl = 'dynamic_form';
    include_once 'HTML/QuickForm.php';

    $title = $area['area_name'].' - '.$locale->get('title_variable_'.$sub_act);
    $form =& new HTML_QuickForm('lang_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('form_required_note'));
    $form->addElement('header', 'lang_header', $title);
    $form->addElement('hidden', 'locale_id',   $locale_id);
    $form->addElement('hidden', 'act',         $page);
    $form->addElement('hidden', 'sub_act',     preg_replace('|^w_|','', $sub_act));
    $form->addElement('hidden', 'variable_id', $variable_id);
    $form->addElement('hidden', 'area_id',     $area_id);
    if (!empty($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
        $form->addElement('hidden', 's',          intval($_REQUEST['s']));
        $form->addElement('hidden', 'page_id',    $page_id);
        $form->addElement('hidden', 'searchtext', $_REQUEST['searchtext']);
        $form->addElement('hidden', 'searchtype', $_REQUEST['searchtype']);
    }

    $fvname =& $form->addElement('text', 'variable_name', $locale->get('field_variable_name'), 'maxlength="255"');

    if ($sub_act == 'w_mod') {
        $fvname->freeze();
    }
    foreach ($locale->getLocales() as $key => $value) {
        $form->addElement('textarea', 'expression_'.$key, $locale->get('field_expression')." (".$value.")", 'rows="7" cols="95"');
    }

    $form->applyFilter('variable_name', 'trim');

    $form->addRule('variable_name',                      $locale->get('error_required_variable_name'), 'required');
    $form->addRule('expression_'.$locale->getFallback(), $locale->get('error_required_expression'),    'required');

    if ($sub_act == 'w_add' && $form->isSubmitted() && $locale->variableExists($area['area_name'], $form->getSubmitValue('variable_name'))) {
        $form->setElementError('variable_name', $locale->get('error_variable_exists'));
    }

    /* form validalas */
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $variable_name = $form->getSubmitValue('variable_name');
        //$expression    = $form->getSubmitValue('expression');

        foreach ($locale->getLocales() as $key => $value) {
            $expression = $form->getSubmitValue('expression_'.$key);
            if (!empty($expression)) {
                $locale->addExpression($key, $area['area_name'], $variable_name, $expression);
            }
        }

        logger($module_name.'_'.$sub_act);

        if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
            header("Location: $_SERVER[PHP_SELF]?p=$module_name&act=search&sub_act=lst&searchtext=".$_REQUEST['searchtext']."&searchtype=".$_REQUEST['searchtype']."&pageID=".$_REQUEST['page_id']);
            exit;
        } else {
            header("Location: $_SERVER[PHP_SELF]?p=$module_name&sub_act=w_lst&area_id=$area_id&locale_id=$locale_id");
            exit;
        }
    }

    if ($sub_act == 'w_mod' && !$form->isSubmitted()) {
        unset($variable['variable_id']);
        foreach ($locale->getLocales() as $key => $value) {
            $variable['expression_'.$key] = $locale->getExpression($key, $variable_id);
            /*if (empty($variable['expression_'.$key])) {
                $variable['expression_'.$key] = $locale->get($area['area_name'], $variable['variable_name']);
            }*/
        }
        $form->setDefaults($variable);
    }

    $form->addElement('submit', 'form_submit', $locale->get('form_submit'), 'class="submit"');
    $form->addElement('reset',  'form_reset',  $locale->get('form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($title, '#');
    $tpl->assign('lang_title', $title);
    $tpl->assign('form',       $renderer->toArray());
    $tpl->assign('back_arrow', $_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;sub_act=w_lst&amp;area_id='.$area_id.'&amp;locale_id='.$locale_id);
}

/* Kifejezesek listaja */
if ($sub_act == 'w_lst') {
    $acttpl = 'dynamic_list';

    // Hozzaadas ikon
    $add_new = array (
		array(
		    'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=export&amp;area_id='.$area_id.'&amp;locale_id='.$locale_id,
		    'title' => $locale->get('act_export'),
		    'pic'   => 'langs_export.jpg'
		),
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=w_add&amp;area_id='.$area_id.'&amp;locale_id='.$locale_id,
			'title' => $locale->get('act_w_add'),
			'pic'   => 'add.jpg'
		)
	);

    $table_headers = array(
        'variable_name' => $locale->get('field_variable_name'),
        'expression'    => $locale->get('field_expression'),
        '__act__'       => $locale->get('actions'),
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod'   => $locale->get('act_mod'),
        'del'   => $locale->get('act_del'),
    );

	// dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('warning_no_variables'),
        'strAdminConfirm' => $locale->get('confirm_variable')
    );

	$variables =& $locale->getExpressions($locale_id, $area_id);

	$tpl->assign('id',              'variable_id');
	$tpl->assign('add_new',         $add_new);
	$tpl->assign('page_list',       lang_links('sub_act=w_lst&amp;area_id='.$area_id));
	$tpl->assign('page_data',       $variables);
	$tpl->assign('table_headers',   $table_headers);
	$tpl->assign('actions_dynamic', $actions_dynamic);
	$tpl->assign('lang_dynamic',    $lang_dynamic);
	$tpl->assign('link_additional', 'locale_id='.$locale_id);
    $tpl->assign('back_arrow',      $_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;locale_id='.$locale_id);
    $tpl->assign('lang_title',      $area['area_name']);
}

/* Modul torlese */
if ($sub_act == 'del') {
    $locale->delArea($area_id);
    header("Location: $_SERVER[PHP_SELF]?p=$module_name&locale_id=$locale_id");
    exit;
}

/* Modul hozzaadasa, modositasa */
if ($sub_act == 'add' || $sub_act == 'mod') {
    $acttpl = 'dynamic_form';
    include_once 'HTML/QuickForm.php';

    $title = $locale->get('title_area_'.$sub_act);
    $form =& new HTML_QuickForm('lang_frm', 'post', 'admin.php?p='.$module_name);

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('form_required_note'));
    $form->addElement('header', 'lang_header', $title);
    $form->addElement('hidden', 'locale_id',   $locale_id);
    $form->addElement('hidden', 'act',         $page);
    $form->addElement('hidden', 'sub_act',     $sub_act);
    $form->addElement('hidden', 'area_id',     $area_id);
    $form->addElement('text',   'area_name',   $locale->get('field_area_name'), 'maxlength="255"');

    $form->applyFilter('__ALL__', 'trim');
    $form->addRule('area_name', $locale->get('error_required_area_name'), 'required');

    /* form validalas */
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));
        $area_name = $form->getSubmitValue('area_name');
        if ($sub_act == 'mod') {
            $locale->modArea($area_id, $area_name);
        } else {
            $locale->addArea($area_name);
        }
        logger($module_name.'_'.$sub_act);
        header("Location: $_SERVER[PHP_SELF]?p=$module_name&locale_id=$locale_id");
        exit;
    }

    if ($sub_act == 'mod' && !$form->isSubmitted()) {
        $form->setDefaults($area);
    }

    $form->addElement('submit', 'form_submit', $locale->get('form_submit'), 'class="submit"');
    $form->addElement('reset',  'form_reset',  $locale->get('form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($title, '#');

    $tpl->assign('lang_title', $title);
    $tpl->assign('form',       $renderer->toArray());
    $tpl->assign('back_arrow', $_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;locale_id='.$locale_id);
}

/* modulok listaja */
if ($sub_act == 'lst') {
    $acttpl = 'dynamic_list';

    // Hozzaadas ikon
    $add_new = array (
		array(
		    'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add_lang&amp;locale_id='.$locale_id,
		    'title' => $locale->get('act_add_lang'),
		    'pic'   => 'langs_add.jpg'
		),
		array(
		    'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=import&amp;locale_id='.$locale_id,
		    'title' => $locale->get('act_import'),
		    'pic'   => 'langs_import.jpg'
		),
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;locale_id='.$locale_id,
			'title' => $locale->get('act_add'),
			'pic'   => 'add.jpg'
		)
	);

	// Tablazat fejlec
    $table_headers = array(
        'area_name' => $locale->get('field_area_name'),
        '__act__'   => $locale->get('actions'),
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod'   => $locale->get('act_mod'),
        'del'   => $locale->get('act_del'),
        'w_lst' => $locale->get('act_w_lst'),
    );

	// dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('warning_no_areas'),
        'strAdminConfirm' => $locale->get('confirm_area')
    );

	$areas =& $locale->getAreas('area_name', 'ASC');

	$tpl->assign('id',              'area_id');
	$tpl->assign('add_new',         $add_new);
	$tpl->assign('page_list',       lang_links());
	$tpl->assign('page_data',       $areas);
	$tpl->assign('table_headers',   $table_headers);
	$tpl->assign('actions_dynamic', $actions_dynamic);
	$tpl->assign('lang_dynamic',    $lang_dynamic);
	$tpl->assign('link_additional', 'locale_id='.$locale_id);
}

function lang_links($additional='') {
    global $locales_array, $locale_id, $module_name, $theme_dir, $theme, $locale;
    // Locale_linkek
	$links = "";
	$style = 'style="font-weight:bold;text-decoration:underline;" ';
	$i = FALSE;
	foreach ($locales_array as $key => $value) {
	    $actions = '';
	    if ($locale_id == $key && $locale->getFallback() != $key) {
	        $actions = ' <a href="'.$_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;sub_act=mod_lang&amp;locale_id='.$locale_id.'" title="'.$locale->get('act_mod').'">
	           <img src="'.$theme_dir.'/'.$theme.'/images/admin/modify.gif" alt="'.$locale->get('act_mod').'" />
	        </a>
	         <a href="javascript:if(confirm(\''.$locale->get('confirm_lang').'\')) document.location.href=\''.$_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;sub_act=del_lang&amp;locale_id='.$locale_id.'\';" title="'.$locale->get('act_del').'">
	           <img src="'.$theme_dir.'/'.$theme.'/images/admin/delete.gif" alt="'.$locale->get('act_del').'" />
	        </a>
	        ';
	    }
	    $links .= ($i ? ' &nbsp;&nbsp;|&nbsp;&nbsp; ' : '').'<a '.($locale_id==$key ? $style : '' ).'href="'.$_SERVER['PHP_SELF'].'?p='.$module_name.'&amp;locale_id='.$key.'&amp;'.$additional.'">'.$value.'</a>'.$actions;
	    $i=TRUE;
	}
	return $links;
}

?>