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

	//kereses tipusa
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_free'),     'all');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_name'),     'name');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_item'),     'item');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_category'), 'cat');
	//ha csoportositast hasznalunk
	if (!empty($_SESSION['site_shop_groupuse'])) {
		$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_groups'), 'group');
	}
	$form_search->addGroup($radio, 'searchtype', $locale->get('search_field_type'), '<br />');

	$form_search->addElement('submit', 'submit', $locale->get('search_form_submit'), array('class' => 'submit'));

	//szurok beallitasa
	$form_search->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form_search->addRule('searchtext', $locale->get('search_error_text'), 'required');
	$form_search->addRule('searchtype', $locale->get('search_error_type'), 'required');

	if ($form_search->validate() || (isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype']))) {
		$form_search->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		if (isset($_REQUEST['searchtext']) && $_REQUEST['searchtext'] != "") {
			//ha a beirt karakterszam kisebb, mint az engedelyezett minimum
			if (isset($_SESSION['site_shop_searchminchar']) && strlen($_REQUEST['searchtext']) < $_SESSION['site_shop_searchminchar']) {
				$acttpl = "error";
				$tpl->assign('errormsg', $locale->getBySmarty('search_error_minchar'));
				return;
			}

			//megnezzuk, hogy hol szeretne keresni - ha reszletes keresesbol jon
			if (isset($_REQUEST['searchtype'])) {
				if ($_REQUEST['searchtype'] != "all" && $_REQUEST['searchtype'] != "name" && $_REQUEST['searchtype'] != "item" && $_REQUEST['searchtype'] != "group" && $_REQUEST['searchtype'] != "cat") {
					$_REQUEST['searchtype'] = "all";
				}
				//ha mindenhol keres
				if ($_REQUEST['searchtype'] == "all") {
					$searchtype = "
						(p.product_name LIKE ('%".$_REQUEST['searchtext']."%') 
						OR p.product_desc LIKE ('%".htmlentities($_REQUEST['searchtext'])."%') 
						OR p.item_id LIKE ('%".$_REQUEST['searchtext']."%'))
					";
				}
				//ha termeknevben keres keres
				if ($_REQUEST['searchtype'] == "name") {
					$searchtype = "
						p.product_name LIKE ('%".$_REQUEST['searchtext']."%')
					";
				}
				//ha cikkszamban keres
				if ($_REQUEST['searchtype'] == "item") {
					$searchtype = "
						p.item_id LIKE ('%".$_REQUEST['searchtext']."%')
					";
				}
				//ha csoportban keres
				if (!empty($_SESSION['site_shop_groupuse']) && $_REQUEST['searchtype'] == "group") {
					$tpl->assign('this_page', 'grp');

					$query = "
						SELECT g.group_id AS gid, g.group_name AS pname, g.lang AS plang, u1.name AS ausr, g.add_date AS adate, 
							u2.name AS musr, g.mod_date AS mdate, g.is_active AS isact 
						FROM iShark_Shop_Groups g 
						LEFT JOIN iShark_Users u1 ON u1.user_id = g.add_user_id 
						LEFT JOIN iShark_Users u2 ON u2.user_id = g.mod_user_id 
						WHERE (g.group_name LIKE ('%".$_REQUEST['searchtext']."%') OR 
							g.group_desc LIKE ('%".htmlentities($_REQUEST['searchtext'])."%')
							)
						ORDER BY g.group_name, lang
					";
				}
				//ha kategoriakban keres
				if ($_REQUEST['searchtype'] == "cat") {
					$tpl->assign('this_page', 'cat');

					$query = "
						SELECT c.category_id AS cid, c.category_name AS pname, c.lang AS plang, u1.name AS ausr, c.add_date AS adate, 
							u2.name AS musr, c.mod_date AS mdate, c.is_active AS isact, c.parent AS par 
						FROM iShark_Shop_Category c 
						LEFT JOIN iShark_Users u1 ON u1.user_id = c.add_user_id 
						LEFT JOIN iShark_Users u2 ON u2.user_id = c.mod_user_id 
						WHERE (c.category_name LIKE ('%".$_REQUEST['searchtext']."%') OR 
							c.category_desc LIKE ('%".htmlentities($_REQUEST['searchtext'])."%')
							)
						ORDER BY c.is_preferred DESC, c.category_name, c.lang
					";
				}
			} else {
				$searchtype = "
					(p.product_name LIKE ('%".$_REQUEST['searchtext']."%') 
						OR p.product_desc LIKE ('%".htmlentities($_REQUEST['searchtext'])."%') 
						OR p.item_id LIKE ('%".$_REQUEST['searchtext']."%'))
				";
			}

			//csak akkor fut le ez a resz, ha nem csoportra vagy kategoriara keresunk
			if (!isset($_REQUEST['searchtype']) || ($_REQUEST['searchtype'] != "group" && $_REQUEST['searchtype'] != "cat")) {
				$tpl->assign('this_page', 'prod');

				$query = "
					SELECT p.item_id AS item, p.product_id AS pid, p.product_name AS pname, u1.name AS ausr, p.add_date AS adate, 
						u2.name AS musr, p.mod_date AS mdate, p.is_active AS isact, p.lang AS plang, p.is_preferred AS ispref 
					FROM iShark_Shop_Products p 
					LEFT JOIN iShark_Users u1 ON u1.user_id = p.add_user_id 
					LEFT JOIN iShark_Users u2 ON u2.user_id = p.mod_user_id 
					WHERE $searchtype AND p.is_deleted = 0 
				";
				//ha ABC szerint rendezzuk sorba
				if ($_SESSION['site_shop_ordertype'] == 1) {
					$query .= "
						ORDER BY p.is_preferred DESC, product_name, p.lang
					";
				}
				//ha egyedi sorrend szerint rendezzuk
				if ($_SESSION['site_shop_ordertype'] == 2) {
					$query .= "
						ORDER BY p.is_preferred DESC, p.sortorder, p.lang
					";
				}
			}

			//lapozo
			require_once 'Pager/Pager.php';
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
	$acttpl = 'shop_search';
}

?>