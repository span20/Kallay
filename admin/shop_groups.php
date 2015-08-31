<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
    $titles = array('add' => $locale->get('groups_title_add'), 'mod' => $locale->get('groups_title_mod'));

	//js beszurasa
	$javascripts[] = "javascripts";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';
	require_once $include_dir.'/function.shop.php';

	$form_shop =& new HTML_QuickForm('frm_shop', 'post', 'admin.php?p='.$module_name);
	$form_shop->removeAttribute('name');

	$form_shop->setRequiredNote($locale->get('groups_form_required_note'));

	$form_shop->addElement('header', 'groups', $locale->get('groups_form_header'));
	//ha kereses volt, akkor bele kell tenni hidden-be a mezoket
	if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
		$form_shop->addElement('hidden', 's',          intval($_REQUEST['s']));
		$form_shop->addElement('hidden', 'searchtext', $_REQUEST['searchtext']);
		$form_shop->addElement('hidden', 'searchtype', $_REQUEST['searchtype']);
	}

	//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
	if (!empty($_SESSION['site_multilang'])) {
		$form_shop->addElement('select', 'languages', $locale->get('groups_field_lang'), $locale->getLocales());
	}

	//csoport neve
	$form_shop->addElement('text', 'name', $locale->get('groups_field_name'));

	//csoport leirasa
	$description =& $form_shop->addElement('textarea', 'desc', $locale->get('groups_field_description'));

	//kategoriak listaja
	$category = array();
	$cats = explode(";", get_category());
	foreach ($cats as $key => $value) {
		$cats2[$key] = explode(",", $value);
	}
	if (is_array($cats2) && count($cats2) > 0) {
		foreach ($cats2 as $key2 => $value2) {
			if (!empty($value2[1])) {
				$category[$value2[0]] = trim($value2[1]);
			}
		}
	}
	$select2  =& $form_shop->addElement('select', 'category', $locale->get('groups_field_category'), $category);
	$select2->setSize(10);
	$select2->setMultiple(true);

	//termekek listaja
	$query = "
		SELECT p.product_id AS pid, p.product_name AS pname 
		FROM iShark_Shop_Products p 
		WHERE p.is_active = 1 AND p.is_deleted = 0 
		ORDER BY p.product_name
	";
	$result =& $mdb2->query($query);
	$select =& $form_shop->addElement('select', 'srcList', $locale->get('groups_field_products'), $result->fetchAll('', $rekey = true), 'id="srcList"');
	$select->setSize(10);
	$select->setMultiple(true);

	$form_shop->addElement('submit', 'submit', $locale->get('groups_form_submit'), 'class="submit"');
	$form_shop->addElement('reset',  'reset',  $locale->get('groups_form_reset'),  'class="reset"');

	//szurok beallitasa
	$form_shop->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form_shop->addRule(     'name',     $locale->get('groups_error_name'), 'required');
	$form_shop->addGroupRule('category', $locale->get('groups_error_category'),  'required');

	/**
	 * Ha uj csoportot adunk hozza
	 */
	if ($sub_act == "add") {
		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name);

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form_shop->addElement('hidden', 'act',     $page);
		$form_shop->addElement('hidden', 'sub_act', $sub_act);

		//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
		$form_shop->setDefaults(array(
			'languages' => $_SESSION['site_deflang']
			)
		);

		$form_shop->addFormRule('check_addgroups');
		if ($form_shop->validate()) {
			$form_shop->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name = $form_shop->getSubmitValue('name');
			$desc = $form_shop->getSubmitValue('desc');
			$cat  = $form_shop->getSubmitValue('category');

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (!empty($_SESSION['site_multilang'])) {
				$languages = $form_shop->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			$group_id = $mdb2->extended->getBeforeID('iShark_Shop_Groups', 'group_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Shop_Groups 
				(group_id, group_name, group_desc, add_user_id, add_date, mod_user_id, mod_date, is_active, lang) 
				VALUES 
				($group_id, '".$name."', '".$desc."', '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', NOW(), 1, '".$languages."')
			";
			$mdb2->exec($query);

			//utolsokent felvitt csoport azonositoja
			$last_grp_id = $mdb2->extended->getAfterID($group_id, 'iShark_Shop_Groups', 'group_id');

			//felvisszuk a kategoria(k)hoz a csoportot
			if (is_array($cat) && count($cat) > 0) {
				foreach ($cat as $key => $value) {
					//kategoria - csoport osszekapcsolas
					$query = "
						INSERT INTO iShark_Shop_Category_Groups 
						(category_id, group_id) 
						VALUES 
						($value, $last_grp_id)
					";
					$mdb2->exec($query);
				}
			}

			//felvisszuk a csoporthoz a termekeket
			if (isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
				foreach ($_POST['destList0'] as $key => $value) {
					//beszurjuk a csoportba a termeket
					$query = "
						INSERT INTO iShark_Shop_Products_Groups 
						(group_id, product_id) 
						VALUES 
						($last_grp_id, $value)
					";
					$mdb2->exec($query);
				}
			}

			//beszurjuk a termekeket a kategoriaba ha van kivalasztva termek
			if (is_array($cat) && count($cat) > 0 && isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
				foreach ($cat as $key => $value) {
					$cat_id = $value;

					//kitoroljuk ezeket a termekeket a kategoriabol
					foreach ($_POST['destList0'] as $key => $value) {
						$query = "
							DELETE FROM iShark_Shop_Products_Category 
							WHERE product_id = $value AND category_id = $cat_id
						";
						$mdb2->exec($query);
					}

					//beszurjuk a csoportba a termeket
					foreach ($_POST['destList0'] as $key => $value) {
						$query = "
							INSERT INTO iShark_Shop_Products_Category 
							(product_id, category_id) 
							VALUES 
							($value, $cat_id)
						";
						$mdb2->exec($query);
					}
				}
			}

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form_shop->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
			exit;
		}
	} //hozzaadas vege

	/**
	 * Ha modositunk egy csoportot
	 */
	if ($sub_act == "mod") {
		if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
			$gid = intval($_REQUEST['gid']);

			//breadcrumb
			$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;gid='.$gid);

			//lekerdezzuk, hogy tenyleg letezik-e a kategoria
			$query = "
				SELECT g.group_name AS gname, g.group_desc AS gdesc, g.lang AS lang 
				FROM iShark_Shop_Groups g 
				WHERE g.group_id = $gid
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				//form-hoz elemek hozzaadasa - csak hozzaadasnal
				$form_shop->addElement('hidden', 'act',     $page);
				$form_shop->addElement('hidden', 'sub_act', $sub_act);
				$form_shop->addElement('hidden', 'gid',     $gid);

				while ($row = $result->fetchRow())
				{
					//beallitjuk az alapertelmezett ertekeket, csak modositasnal
					$form_shop->setDefaults(array(
						'languages' => $row['lang'],
						'name'      => $row['gname'],
						'desc'      => $row['gdesc']
						)
					);

					//lekerdezzuk a mar rogzitett kategoriakat
					$query = "
						SELECT category_id 
						FROM iShark_Shop_Category_Groups 
						WHERE group_id = $gid
					";
					$result =& $mdb2->query($query);
					$select2->setSelected($result->fetchCol());

					//lekerdezzuk a mar rogzitett termekeket
					$query = "
						SELECT p.product_id AS pid, p.product_name AS pname 
						FROM iShark_Shop_Products_Groups g, iShark_Shop_Products p 
						WHERE group_id = $gid AND p.product_id = g.product_id
					";
					$result =& $mdb2->query($query);
					$prod_array = array();
					while ($row = $result->fetchRow()) {
						$prod_array[$row['pid']] = $row['pname'];
					}
					$tpl->assign('destList', $prod_array);

					//ellenorzes, vegso muveletek
					$form_shop->addFormRule('check_modgroups');
					if ($form_shop->validate()) {
						$form_shop->applyFilter('__ALL__', array(&$mdb2, 'escape'));

						$name = $form_shop->getSubmitValue('name');
						$desc = $form_shop->getSubmitValue('desc');
						$cat  = $form_shop->getSubmitValue('category');

						//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
						if (!empty($_SESSION['site_multilang'])) {
							$languages = $form->getSubmitValue('languages');
						} else {
							$languages = $_SESSION['site_deflang'];
						}

						$query = "
							UPDATE iShark_Shop_Groups 
							SET group_name  = '".$name."', 
								group_desc  = '".$desc."', 
								mod_user_id = '".$_SESSION['user_id']."', 
								mod_date    = NOW(),
								lang        = '".$languages."'
							WHERE group_id  = $gid
						";
						$mdb2->exec($query);

						//kitoroljuk az eddig ehhez a csoporthoz tartozo kategoriak termekeit
						$query = "
							SELECT pg.product_id AS pid, cg.category_id AS cid 
							FROM iShark_Shop_Products_Groups pg 
							LEFT JOIN iShark_Shop_Category_Groups cg ON cg.group_id = pg.group_id 
							WHERE pg.group_id = $gid
						";
						$result =& $mdb2->query($query);
						if ($result->numRows() > 0) {
							while ($row = $result->fetchRow())
							{
								$pid = $row['pid'];
								$cid = $row['cid'];

								$query2 = "
									DELETE FROM iShark_Shop_Products_Category 
									WHERE product_id = $pid AND category_id = $cid
								";
								$mdb2->exec($query2);
							}
						}

						//kitoroljuk az eddig kapcsolt kategoriakat
						$query = "
							DELETE FROM iShark_Shop_Category_Groups 
							WHERE group_id = $gid
						";
						$mdb2->exec($query);

						//kitoroljuk az eddig ehhez a csoporthoz tartozo termekeket
						$query = "
							DELETE FROM iShark_Shop_Products_Groups 
							WHERE group_id = $gid
						";
						$mdb2->exec($query);

						//felvisszuk a kategoria(k)hoz a csoportot
						if (is_array($cat) && count($cat) > 0) {
							foreach ($cat as $key => $value) {
								//kategoria - csoport osszekapcsolas
								$query = "
									INSERT INTO iShark_Shop_Category_Groups 
									(category_id, group_id) 
									VALUES 
									($value, $gid)
								";
								$mdb2->exec($query);
							}
						}

						//felvisszuk a csoporthoz a termekeket
						if (isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
							foreach ($_POST['destList0'] as $key => $value) {
								//beszurjuk a csoportba a termeket
								$query = "
									INSERT INTO iShark_Shop_Products_Groups 
									(group_id, product_id) 
									VALUES 
									($gid, $value)
								";
								$mdb2->exec($query);
							}
						}

						//beszurjuk a termekeket a kategoriaba ha van kivalasztva termek
						if (is_array($cat) && count($cat) > 0 && isset($_POST['destList0']) && is_array($_POST['destList0']) && count($_POST['destList0']) > 0) {
							foreach ($cat as $key => $value) {
								$cat_id = $value;

								//beszurjuk a csoportba a termeket
								foreach ($_POST['destList0'] as $key => $value) {
									$query = "
										INSERT INTO iShark_Shop_Products_Category 
										(product_id, category_id) 
										VALUES 
										($value, $cat_id)
									";
									$mdb2->exec($query);
								}
							}
						}

						//loggolas
						logger($page.'_'.$sub_act);

						//"fagyasztjuk" a form-ot
						$form_shop->freeze();

						//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
						if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
							header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
							exit;
						} else {
							header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
							exit;
						}
					}
				}
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('groups_error_not_exists'));
				return;
			}
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('groups_error_not_exists'));
			return;
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form_shop->accept($renderer);

	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form_shop',   $renderer->toArray());
	$tpl->assign('tiny_fields', 'desc');

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_form', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_groups';
}

/**
 * ha aktivalunk vagy inaktivalunk egy csoportot
 */
if ($sub_act == "act") {
	if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
		include_once $include_dir.'/function.check.php';
		$gid = intval($_REQUEST['gid']);

		check_active('iShark_Shop_Groups', 'group_id', $gid);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('groups_error_not_exists'));
		return;
	}
}

/**
 * ha torlunk egy csoportot
 */
if ($sub_act == "del") {
	if (isset($_GET['gid']) && is_numeric($_GET['gid'])) {
		$gid = intval($_GET['gid']);

		//kitoroljuk a csoportot
		$query = "
			DELETE FROM iShark_Shop_Groups 
			WHERE group_id = $gid
		";
		$mdb2->exec($query);

		//kitoroljuk a kategoria-csoport kapcsolatokat
		$query = "
			DELETE FROM iShark_Shop_Category_Groups 
			WHERE group_id = $gid
		";
		$mdb2->exec($query);

		//kitoroljuk a termek-csoport kapcsolatokat
		$query = "
			DELETE FROM iShark_Shop_Products_Groups 
			WHERE group_id = $gid
		";
		$mdb2->exec($query);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p=shop&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('groups_error_not_exists'));
		return;
	}
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	$query = "
		SELECT g.group_id AS gid, g.lang AS glang, g.group_name AS gname, u1.name AS ausr, g.add_date AS adate, u2.name AS musr, 
			g.mod_date AS mdate, g.is_active AS isact 
		FROM iShark_Shop_Groups g 
		LEFT JOIN iShark_Users u1 ON u1.user_id = g.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = g.mod_user_id 
		ORDER BY g.group_name
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('groups_title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('add_new',   $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = "shop_groups_list";
}

?>