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
		FROM iShark_Partners_Discounts 
		WHERE discount_id = $id
    ";
    $result =& $mdb2->query($query);
    if (!($selected = $result->fetchRow())) {
        $acttpl = 'error';
        $tpl->assign('errormsg', $locale->get('discounts_error_not_exists'));
        return;
    }
} elseif ($sub_act == 'mod' || $sub_act == 'del') {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('discounts_error_none_selected'));
    return;
}

// Torles
if ($sub_act == 'del') {
    $query = "
        DELETE FROM iShark_Partners_Discounts 
		WHERE discount_id = $id
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: '.$_SERVER['PHP_SELF'].'?p='.$module_name.'&act='.$page);
    exit;
}

// Hozzaadas
if ($sub_act == 'add' || $sub_act == 'mod') {
    $javascripts[] = 'javascripts';
    include_once 'HTML/QuickForm.php';
    include_once 'HTML/QuickForm/jscalendar.php';
    include_once $include_dir.'/function.check.php';

    $titles = array('add' => $locale->get('discounts_act_add'), 'mod' => $locale->get('discounts_act_mod'));

    $form =& new HTML_QuickForm('discounts_frm', 'post', 'admin.php?p=partners');

    // XHTML:
    $form->removeAttribute('name');

    $form->setRequiredNote($locale->get('discounts_form_required_note'));

    $form->addElement('header', 'discounts_header', $titles[$sub_act]);
    $form->addElement('hidden', 'act',              $page);
    $form->addElement('hidden', 'sub_act',          $sub_act);
    $form->addElement('hidden', 'id',               $id);

    //akcio cime
    $form->addElement('text', 'title', $locale->get('discounts_field_title'), 'maxlength="255"');

    //idozites
    $form->addGroup(
        array(
            HTML_QuickForm::createElement('text', 'timer_start', null, array('readonly' => 'readonly', 'id' => 'timer_start')),
            HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
            HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('discounts_deltimer'), 'onclick="deltimer(\'timer_start\')"')
            ),
       'date_start', $locale->get('discounts_field_date_start'), null, false
    );
    $form->addGroup(
        array(
            HTML_QuickForm::createElement('text', 'timer_end', null, array('readonly' => 'readonly', 'id' => 'timer_end')),
            HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
            HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('discounts_deltimer'), 'onclick="deltimer(\'timer_end\')"')
        ),
        'date_end', $locale->get('discounts_field_date_end'), null, false
    );

    //leiras
    $form->addElement('textarea', 'description', $locale->get('discounts_field_description'), 'id="description"');

    $form->applyFilter('__ALL__', 'trim');

    $form->addRule('title',       $locale->get('discounts_error_required_title'),       'required');
    $form->addRule('description', $locale->get('discounts_error_required_description'), 'required');

    if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
		$form->addFormRule('check_timer');
	}

    // Validalas
    if ($form->validate()) {
        $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

        $title       = $form->getSubmitValue('title');
        $desc        = $form->getSubmitValue('description');
        $timer_start = $form->getSubmitValue('timer_start');
        $timer_end   = $form->getSubmitValue('timer_end');
        $timer_start = empty($timer_start) ? '0000-00-00 00:00:00' : $timer_start;
        $timer_end   = empty($timer_end) ? '0000-00-00 00:00:00' : $timer_end;

        // Adatrekord mentese
        if ($sub_act == 'mod') {
            $query = "
                UPDATE iShark_Partners_Discounts 
				SET title       = '$title', 
					timer_start = '$timer_start', 
					timer_end   = '$timer_end',
                    description = '$desc'
                WHERE discount_id = $id
			"; 
        } else {
			$discount_id = $mdb2->extended->getBeforeID('iShark_Partners_Discounts', 'discount_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Partners_Discounts 
				(discount_id, title, timer_start, timer_end, description) 
				VALUES 
				($discount_id, '$title', '$timer_start', '$timer_end', '$desc')
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
            'title'       => $selected['title'],
            'description' => $selected['description'],
            'timer_start' => preg_match('/^0000/', $selected['timer_start']) ? '' : $selected['timer_start'],
            'timer_end'   => preg_match('/^0000/', $selected['timer_end'])   ? '' : $selected['timer_end']
        ));
    }

    $form->addElement('submit', 'submit', $locale->get('discounts_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('discounts_form_reset'),  'class="reset"');

    include_once 'HTML/QuickForm/Renderer/Array.php';
    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('lang_title', $titles[$sub_act]);
    $tpl->assign('form',       $renderer->toArray());

    // Tinymce betöltése
    $tpl->assign('tiny_fields', 'description');

    // dynamic_form kivalasztasa
    $acttpl = 'dynamic_form';
}

// Dinamikus lista
if ($sub_act == 'lst') {

    // Hozzaadas ikon
    $add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('discounts_act_add'),
			'pic'   => 'add.jpg'
		)
	);

    // Tablazat fejlecek dynamic list_hez
    $table_headers = array(
        'title'       => $locale->get('discounts_field_title'),
        'timer_start' => $locale->get('discounts_field_date_start'),
        'timer_end'   => $locale->get('discounts_field_date_end'),
        '__act__'     => $locale->get('discounts_actions'),
    );

    // dynamic listhez szukseges nyelvi mezok
    $lang_dynamic = array(
        'strAdminEmpty'   => $locale->get('discounts_no_discount'),
        'strAdminConfirm' => $locale->get('discounts_confirm_discount')
    );

    // dynamic listhez szukseges mezomuveletek
    $actions_dynamic = array(
        'mod' => $locale->get('discounts_act_mod'),
        'del' => $locale->get('discounts_act_del'),
    );

    // Adatbazis lekerdezes
    $query = "  
        SELECT discount_id as id, title, timer_start, timer_end 
        FROM iShark_Partners_Discounts
        ORDER BY title 
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