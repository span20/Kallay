<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//szukseges fuggvenykonyvtarak betoltese
require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

//elinditjuk a form-ot
$form_search =& new HTML_QuickForm('frm_search', 'post', 'admin.php?p='.$module_name);

//a szukseges szoveget jelzo resz beallitasa
$form_search->setRequiredNote('&nbsp;');

//form-hoz elemek hozzadasa
$form_search->addElement('header', $locale->get('search_form_header'));
$form_search->addElement('hidden', 's',          '1');
$form_search->addElement('hidden', 'act',        $page);

//keresett szoveg
$form_search->addElement('text',   'searchtext', $locale->get('search_field_search_text'));

//tipus
$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_search_all'),  'all');
$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_search_id'),   'id');
$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_search_name'), 'name');
$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_search_desc'), 'desc');
$form_search->addGroup($radio, 'searchtype', $locale->get('search_field_search_type'), '<br />');

//szurok beallitasa
$form_search->applyFilter('__ALL__', 'trim');

//szabalyok beallitasa
$form_search->addRule('searchtext', $locale->get('search_error_search_text'), 'required');
$form_search->addRule('searchtype', $locale->get('search_error_search_type'), 'required');

if (isset($_REQUEST['s']) && $_REQUEST['s'] == "1") {
	$form_search->setDefaults(array(
		'searchtext' => $_REQUEST['searchtext'],
		'searchtype' => $_REQUEST['searchtype']
		)
	);

	//ha a beirt karakterszam kisebb, mint az engedelyezett minimum
	if (isset($_SESSION['site_class_searchminchar']) && strlen($_REQUEST['searchtext']) < $_SESSION['site_class_searchminchar']) {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->getBySmarty('search_error_search_minchar'));
		return;
	}

	//megnezzuk hol szeretne keresni
	if (isset($_REQUEST['searchtype'])) {
		if ($_REQUEST['searchtype'] != "all" || $_REQUEST['searchtype'] != "id" || $_REQUEST['searchtype'] != "name" || $_REQUEST['searchtype'] != "desc") {
			$_REQUEST['searchtype'] = "all";
		}

		//ha mindenhol keres
		if ($_REQUEST['searchtype'] == "all") {
			$searchtype = "
				(a.name LIKE('%".$_REQUEST['searchtext']."%') OR a.description LIKE('%".htmlentities($_REQUEST['searchtext'])."%'))
			";
		}
		//ha azonositokban keres
		if ($_REQUEST['searchtype'] == "id") {
			$searchtype = "
				a.advert_id LIKE('%".$_REQUEST['searchtext']."%')
			";
		}
		//ha nevben keres
		if ($_REQUEST['searchtype'] == "name") {
			$searchtype = "
				a.name LIKE('%".$_REQUEST['searchtext']."%')
			";
		}
		//ha leirasban keres
		if ($_REQUEST['searchtype'] == "desc") {
			$searchtype = "
				a.description LIKE('%".htmlentities($_REQUEST['searchtext'])."%')
			";
		}
	}
	//ez ugyanaz, mintha mindenben keresne
	else {
		$searchtype = "
			(a.name LIKE('%".$_REQUEST['searchtext']."%') OR a.description LIKE('%".htmlentities($_REQUEST['searchtext'])."%'))
		";
	}

	//lekerdezzuk az adatbazisbol az aprohirdetesek listajat
	$query = "
		SELECT a.advert_id AS id, a.name AS name, a.phone AS phone, a.email AS email, a.is_active AS is_active, a.lang AS lang, 
			a.timer_end AS timer_end
		FROM iShark_Classifieds_Advert a 
		WHERE $searchtype
		ORDER BY add_date DESC 
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('s', 1);
} else {
	$form_search->setDefaults(array(
		'searchtype' => 'id'
		)
	);
}

$form_search->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
$form_search->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
$form_search->accept($renderer);

$tpl->assign('form_search', $renderer->toArray());
$tpl->assign('back_arrow',  'admin.php');

// capture the array stucture
ob_start();
print_r($renderer->toArray());
$tpl->assign('static_array', ob_get_contents());
ob_end_clean();

$acttpl = 'classifieds_search';

?>