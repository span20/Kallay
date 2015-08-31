<?php
// Kozvetlenul ezt az allomanyt kerte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

$module_name = "carousel";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'carousel' => $locale->get('tabs_title')
);

$acts = array(
    'carousel' => array('lst', 'add', 'mod', 'del')
);

//aktualis ful beallitasa
$page = 'carousel';
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
            SELECT * FROM iShark_Carousel
            WHERE id = '".$_REQUEST["id"]."'
        ";
        $result = $mdb2->query($query);
        $row = $result->fetchRow();

        $content_picture = $filename = $row['pic'];

        $defaults = array(
            'title' => $row['title'],
            'desc' => $row['text'],
            'content' => $row['content_id']
        );
    }

	$titles = array('add' => $locale->get('main_title_add'), 'mod' => $locale->get('main_title_mod'));

	$form =& new HTML_QuickForm('frm_carousel', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('main_form_required_note'));

	$form->addElement('header', 'carousel', $locale->get('main_form_header'));

    //title
    $form->addElement('text', 'title', $locale->get('main_field_title'));

    $file =& $form->addElement('file', 'pic', $locale->get('carousel_picture'));

    //modositas eseten jelenlegi kep kirajzolasa
    if ($sub_act == 'mod' && !empty($content_picture)) {
        $form->addElement('static', 'oldpic', $locale->get('news_field_currentpic'), '<img width="200" src="main_pics/'.$content_picture.'" alt="'.$content_picture.'" />' );
        $delpic =& $form->addElement('checkbox', 'delpic', '', $locale->get('news_field_delpic'));
    }

    $query_tags = "
        SELECT content_id, title
        FROM iShark_Contents
        WHERE type = '1' AND is_active = '1'
        ORDER BY title
    ";
    $result_tags = $mdb2->query($query_tags);

    $tag_select =& $form->addElement('select', 'content', $locale->get('news_field_tags'), $result_tags->fetchAll('', $rekey = true));

	//leiras
	$form->addElement('textarea', 'desc', $locale->get('main_field_description'), 'style="width: 500px; height: 200px;"');

	$form->addElement('submit', 'submit', $locale->get('main_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('main_form_reset'),  'class="reset"');

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('title', $locale->get('main_error_desc'), 'required');
    $form->addRule('desc', $locale->get('main_error_desc'), 'required');
    $form->addRule('pic', $locale->get('main_error_desc'), 'required');

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
        $content_id = $form->getSubmitValue('content');

        if ($sub_act == "mod") {
            if (isset($delpic) && $delpic->getChecked()) {
                $filename = "";
                if (file_exists("main_pics/".$content_picture)) {
                    @unlink("main_pics/".$content_picture);
                }
            }
        } else {
            $filename = "";    
        }

        $pic = TRUE;
        //kep feltoltese
        if ($file->isUploadedFile()) {
            $filevalues = $file->getValue();
            $sdir = preg_replace('|/$|','', 'main_pics').'/';
            $filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));

            //kep atmeretezese
            include_once 'includes/function.images.php';
            if (is_array($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, 461, 272))) {
                @chmod($sdir.$filename,0664);
                @unlink($filevalues['tmp_name']);
            }
            
            if (!$pic) {
                $form->setElementError('pic', $locale->get('news_error_picupload'));
            }
        }

        if ($pic) {
            if ($sub_act == "mod") {

                $query = "
                    UPDATE iShark_Carousel
                    SET title = '".$title."',
                        text = '".$desc."',
                        content_id = '".$content_id."',
                        pic = '".$filename."'
                    WHERE id = '".$_REQUEST["id"]."'
                ";
                $mdb2->exec($query);
                
                if ($content_picture != "") {
                    if (file_exists($_SESSION['site_cnt_picdir']."/".$content_picture)) {
                        @unlink($_SESSION['site_cnt_picdir']."/".$content_picture);
                    }
                }
            } else {
                $query = "
                    INSERT INTO iShark_Carousel
                    (title, text, content_id, pic)
                    VALUES
                    ('".$title."', '".$desc."', '".$content_id."', '".$filename."')
                ";
                $mdb2->exec($query);
            }
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
    $query = "
        SELECT * FROM iShark_Carousel WHERE id = '".$_REQUEST['id']."'
    ";
    $result = $mdb2->query($query);
    $row = $result->fetchRow();

    $del = "DELETE FROM iShark_Carousel WHERE id = '".$_REQUEST["id"]."'";
    $mdb2->exec($del);

    @unlink("main_pics/".$row['pic']);

    header('Location: admin.php?p='.$module_name.'&act='.$page);
    exit;
}
/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {

	//atadjuk a smarty-nak a kiirando cuccokat
	$query = "
		SELECT * FROM iShark_Carousel
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
	$acttpl = 'carousel_list';
}

?>
