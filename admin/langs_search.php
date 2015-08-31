<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

/**
 * ha a kereso form-ot mutatjuk
 */
if ($sub_act == "lst") {
    //szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form_search =& new HTML_QuickForm('frm_search', 'post', 'admin.php?p='.$module_name);
	$form_search->removeAttribute('name');

	$form_search->setRequiredNote($locale->get('search_form_required_note'));

	$form_search->addElement('header', 'search',  $locale->get('search_form_header'));
	$form_search->addElement('hidden', 'act',     $page);
	$form_search->addElement('hidden', 'sub_act', $sub_act);

	//keresett szoveg
	$form_search->addElement('text', 'searchtext', $locale->get('search_field_text'));

	//hol keressen
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_all'),   'all');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_name'),  'name');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_value'), 'item');
	$form_search->addGroup($radio, 'searchtype', $locale->get('search_field_type'), '<br />');

	$form_search->addElement('submit', 'submit', $locale->get('search_form_submit'), array('class' => 'submit'));

	//szurok beallitasa
	$form_search->applyFilter('__ALL__', 'trim');

	//alapertelmezett ertekek
	if (!empty($_GET['searchtext']) && !empty($_GET['searchtype'])) {
	    $form_search->setDefaults(
	        array(
	            'searchtext' => $_GET['searchtext'],
	            'searchtype' => $_GET['searchtype']
	        )
	    );
	} else {
	    $form_search->setDefaults(
	        array(
	            'searchtype' => 'all'
	        )
	    );
	}

	//szabalyok beallitasa
	$form_search->addRule('searchtext', $locale->get('search_error_searhctext'), 'required');
	$form_search->addRule('searchtype', $locale->get('search_error_searchtype'), 'required');

	if ($form_search->validate() || (!empty($_GET['searchtext']) && !empty($_GET['searchtext']))) {
		$form_search->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		if (empty($_GET['searchtext']) && empty($_GET['searchtext'])) {
		    $searchtext = $form_search->getSubmitValue('searchtext');
		    $searchtype = $form_search->getSubmitValue('searchtype');
		} else {
		    $searchtext = $_GET['searchtext'];
		    $searchtype = $_GET['searchtext'];
		}

		if (!empty($searchtext) && !empty($searchtype)) {
			//ha a beirt karakterszam kisebb, mint az engedelyezett minimum
			if (strlen($searchtext) < 3) {
				$acttpl = "error";
				$tpl->assign('errormsg', $locale->get('search_error_minsearch'));
				return;
			}

			//megnezzuk, hogy hol szeretne keresni - ha reszletes keresesbol jon
			if (!empty($searchtype)) {
				if ($searchtype != "all" && $searchtype != "name" && $searchtype != "item") {
					$searchtype = "all";
				}
				//ha mindenhol keres
				if ($searchtype == "all") {
					$type_query = "
						(v.variable_name LIKE ('%".$searchtext."%') OR e.expression LIKE ('%".$searchtext."%'))
					";
				}
				//ha termeknevben keres keres
				if ($searchtype == "name") {
					$type_query = "
						(v.variable_name LIKE ('%".$searchtext."%'))
					";
				}
				//ha cikkszamban keres
				if ($searchtype == "item") {
					$type_query = "
						(e.expression LIKE ('%".$searchtext."%'))
					";
				}
			} else {
				$type_query = "
					(v.variable_name LIKE ('%".$searchtext."%') OR e.expression LIKE ('%".$searchtext."%'))
				";
			}

			$query = "
				SELECT a.area_name AS aname, v.variable_id AS vid, v.variable_name AS vname, e.expression AS exp, e.locale_id AS lid 
				FROM iShark_Locales_Areas a, iShark_Locales_Variables v, iShark_Locales_Expressions e
				WHERE a.area_id = e.area_id AND v.variable_id = e.variable_id AND $type_query 
				ORDER BY a.area_name
			";

			//lapozo
			require_once 'Pager/Pager.php';
			$pagerOptions['extraVars'] = array(
			    'act'        => $page,
			    'sub_act'    => $sub_act,
			    'searchtext' => $searchtext, 
			    'searchtype' => $searchtype
			);
			$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

			$tpl->assign('page_data', $paged_data['data']);
			$tpl->assign('page_list', $paged_data['links']);
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

	$form_search->accept($renderer);
	$tpl->assign('form_search', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az index.php-nek
	$acttpl = 'langs_search';
}

?>