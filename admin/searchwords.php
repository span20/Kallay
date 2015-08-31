<?php
/**
 * SearchWords modul iShark rendszerhez 
 */

// Csak adminból hívható
if (!eregi('admin\.php', $_SERVER['PHP_SELF'])) {
	die('Közvetlenül nem férhet hozzá ehhez az állományhoz!');
}

$module_name = "searchwords";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('main_title')
);

// fulek definialasa
$tabs = array(
    'searchwords' => $locale->get('tabs_title')
);

$acts = array(
    'searchwords' => array('lst', 'mod')
);

//aktualis ful beallitasa
$page = 'searchwords';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

// Van e jogosultság a kért mûvelet végrehajtásához.
if (!check_perm($page, 0, 0, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_permission_denied'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

$menu_data = array();
if (isset($_REQUEST['m_mid']) && is_numeric($_REQUEST['m_mid'])) {
	$m_mid  = intval($_REQUEST['m_mid']);

	//ha nem fooldal
	if ($_REQUEST['m_mid'] != 0) {
    	$query  = "
    		SELECT menu_name 
    		FROM iShark_Menus 
    		WHERE menu_id = $m_mid AND type = 'index'
    	";
    	$result =& $mdb2->query($query);
    	if (!$menu_data = $result->fetchRow()) {
    	    $acttpl = 'error';
    		$tpl->assign('error', $locale->get('error_menu_not_exists'));
    		return;
    	}
	}

	//ha fooldal
	if ($_REQUEST['m_mid'] == 0) {
	    $menu_data['menu_name'] = $locale->get('list_indexpage');
	}
}

/**
 * Új kifejezés vagy módosítás
 */
if ($sub_act == 'mod') {
    $titles = array('mod' => $locale->get('title_mod'));

    //szukseges fuggvenykonyvtarak betoltese
	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';

	//elinditjuk a form-ot
	$form =& new HTML_QuickForm('add_frm', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	//a szukseges szoveget jelzo resz beallitasa
	$form->setRequiredNote($locale->get('form_required_note'));

	//form-hoz elemek hozzadasa
	$form->addElement('header', 'searchwords', $locale->get('form_header'));
	$form->addElement('hidden', 'act',         $page);
	$form->addElement('hidden', 'sub_act',     $sub_act);
	$form->addElement('hidden', 'm_mid',       $m_mid);

	//description
	$form->addElement('text', 'description', $locale->get('field_description'), array('style' => 'width: 90%;'));

	//keywords
	$form->addElement('text', 'keywords', $locale->get('field_keywords'), array('style' => 'width: 90%;'));

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//alapertelmezett ertekek
	$query_mid = "
		SELECT * 
		FROM iShark_Searchwords
		WHERE menu_id = $m_mid
	";
	$result_mid =& $mdb2->query($query_mid);
	$row_mid = $result_mid->fetchRow();
	$form->setDefaults(array(
	    'description' => $row_mid['description'],
	    'keywords'    => $row_mid['keywords']
	    )
	);	

	$form->applyFilter('__ALL__', 'trim');
	$form->applyFilter('__ALL__', 'strip_tags');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$description = $form->getSubmitValue('description');
		$keywords    = $form->getSubmitValue('keywords');

		//ha meg nem volt ilyen bejegyzes, akkor beszurjuk
		if ($result_mid->numRows() == 0) {
		    $query = "
				INSERT INTO iShark_Searchwords 
				(menu_id, description, keywords, add_user_id, add_date, mod_user_id, mod_date) 
				VALUES
				($m_mid, '".$description."', '".$keywords."', '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', NOW())
			";
		}
		//ha mar volt, akkor csak update
		else {
		    $query = "
				UPDATE iShark_Searchwords
				SET description = '".$description."',
					keywords    = '".$keywords."',
					mod_user_id = '".$_SESSION['user_id']."', 
					mod_date    = NOW()
				WHERE menu_id = $m_mid
			";
		}
		$mdb2->exec($query);

		logger($sub_act);

		header('Location: admin.php?p='.$module_name.'&m_mid='.$m_mid);
		exit;
	}

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	$tpl->assign('lang_title', $titles[$sub_act]." (".$menu_data['menu_name'].")");
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&act='.$page);
	$tpl->assign('form',       $renderer->toArray());

	$acttpl = 'dynamic_form';
}

/**
 * Listázás 
 */
if ($sub_act == 'lst') {
	include_once 'includes/function.menu.php';

	//menupontok listaja
	$menu_array = menu(0, TRUE, 0, 1, 0, 'index');

	//kifejezesek listaja
	$words_array = array();
	$query = "
		SELECT s.menu_id AS menu_id, u1.name AS add_name, s.add_date AS add_date, 
			u2.name AS mod_name, s.mod_date AS mod_date
		FROM iShark_Searchwords s
		LEFT JOIN iShark_Users u1 ON s.add_user_id = u1.user_id
		LEFT JOIN iShark_Users u2 ON s.mod_user_id = u2.user_id
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
	    $words_array = $result->fetchAll('', $rekey = true);
	}

	//megvizsgaljuk a menupontokat, ha van olyan menupont, amihez mar letezik kereoskifejezes, akkor par infot meg belerakunk
	foreach ($menu_array as $key => $value) {
	    if (array_key_exists($value['menu_id'], $words_array)) {
	        $menu_array[$key]['add_name'] = $words_array[$value['menu_id']]['add_name'];
	        $menu_array[$key]['add_date'] = $words_array[$value['menu_id']]['add_date'];
	        $menu_array[$key]['mod_name'] = $words_array[$value['menu_id']]['mod_name'];
	        $menu_array[$key]['mod_date'] = $words_array[$value['menu_id']]['mod_date'];
	    }
	}

	//a fooldali keresokifejezeseket kulon kezeljuk
    if (array_key_exists('0', $words_array)) {
        $tpl->assign('index_data', $words_array[0]);
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('menu_array',  $menu_array);
	$tpl->assign('back_arrows', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'searchwords_list';
}

?>
