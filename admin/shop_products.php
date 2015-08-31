<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//rendezes
$fieldselect1 = "";
$fieldselect2 = "";
$fieldselect3 = "";
$fieldselect4 = "";
$fieldselect5 = "";
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field = intval($_REQUEST['field']);
	$ord   = $_REQUEST['ord'];

	switch ($field) {
		case 1:
			$fieldorder   = " pname ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = " ausr ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = " adate ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   = " musr ";
			$fieldselect4 = "selected";
			break;
		case 5:
			$fieldorder   = " mdate ";
			$fieldselect5 = "selected";
			break;
	}

	switch ($ord) {
		case "asc":
			$order      = "ASC";
			$ordselect1 = "selected";
			break;
		case "desc":
			$order      = "DESC";
			$ordselect2 = "selected";
			break;
	}
} else {
	$field       = "";
	$ord         = "";
	$fieldorder  = " pname";
	$order       = "ASC";
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('fieldselect3', $fieldselect3);
$tpl->assign('fieldselect4', $fieldselect4);
$tpl->assign('fieldselect5', $fieldselect5);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
//rendezes vége

//kategóriaszûrés
if (isset($_SESSION['site_shop_groupuse']) && $_SESSION['site_shop_groupuse'] == 1){
	$mely_mezo 	= "group_id, group_name";
	$table 		= "iShark_Shop_Groups";
	$sorr 		= "group_name";
	$join_table = "LEFT JOIN iShark_Shop_Products_Groups AS pg ON p.product_id = pg.product_id";
	$where		= " AND pg.group_id = ";
} else {
	$mely_mezo 	= "category_id, category_name";
	$table 		= "iShark_Shop_Category";
	if (isset($_SESSION['site_shop_ordertype']) && $_SESSION['site_shop_ordertype'] == 2) {
		$sorr = "sortorder";
	} else {
		$sorr = "category_name";
	}
	$join_table = "LEFT JOIN iShark_Shop_Products_Category AS pc ON p.product_id = pc.product_id";
	$where		= " AND pc.category_id = ";
}

if (isset($_REQUEST['cat_fil']) && is_numeric($_REQUEST['cat_fil'])) {
	$cat_fil             = intval($_REQUEST['cat_fil']);
	$catselect[$cat_fil] = "selected";
	$catszur             = $where.$cat_fil;
	$tpl->assign('catselect', $catselect);
} else {
	$cat_fil    = "";
	$catszur    = "";
	$join_table = "";
}

$all_select = array('all' => $locale->get('products_fields_all_category'));

$query = "
	SELECT $mely_mezo 
	FROM $table 
	ORDER BY $sorr
";
$result =& $mdb2->query($query);
$row = $result->fetchAll('', $rekey = true);

$catsel = $all_select + $row;

$tpl->assign('katok', $catsel);
//kategóriaszûrés vége

$javascripts[] = "javascripts";

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
    $titles = array('add' => $locale->get('products_title_add'), 'mod' => $locale->get('products_title_mod'));

	if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid'])) {
		$pid = intval($_REQUEST['pid']);

		$bodyonload[] = "proplist($pid);";
	} else {
		$bodyonload[] = "proplist();";
	}

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';
	require_once $include_dir.'/function.shop.php';

	$form =& new HTML_QuickForm('frm_shop', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('products_form_required_note'));

	$form->addElement('header', 'products', $locale->get('products_form_header'));
	//ha kereses volt, akkor bele kell tenni hidden-be a mezoket
	if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
		$form->addElement('hidden', 's',          intval($_REQUEST['s']));
		$form->addElement('hidden', 'searchtext', $_REQUEST['searchtext']);
		$form->addElement('hidden', 'searchtype', $_REQUEST['searchtype']);
	}

	//kiemelt-e
	$pref = array();
	$pref[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('products_form_yes'), '1');
	$pref[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('products_form_no'), '0');
	$form->addGroup($pref, 'pref', $locale->get('products_field_preferred'));

	//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
	if (!empty($_SESSION['site_multilang'])) {
		$form->addElement('select', 'languages', $locale->get('products_field_lang'), $locale->getLocales());
	}

	//cikkszam
	$form->addElement('text', 'item', $locale->get('products_field_item'));

	//termeknev
	$form->addElement('text', 'name', $locale->get('products_field_name'));

	//ha hasznaljuk az allapot mezot
	if (!empty($_SESSION['site_shop_stateuse'])) {
		$query = "
			SELECT state_id AS sid, state_name AS sname 
			FROM iShark_Shop_State 
			ORDER BY state_id
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$selectstate =& $form->addElement('select', 'state', $locale->get('products_field_state'), $result->fetchAll('', $rekey = true));
		}
	}

	//ha a felhasznalok vasarolhatnak, akkor kirakjuk az ar es afa mezoket
	if (!empty($_SESSION['site_shop_userbuy'])) {
		$form->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'netto',   null, array('size' => 7, 'maxlength' => 7)),
				HTML_QuickForm::createElement('text', 'percent', null, array('size' => 2, 'maxlength' => 2, 'value' => '00'))
			),
			'price', $locale->get('products_field_price'), null, false
		);
		$query = "
			SELECT afa_id, afa_percent 
			FROM iShark_Shop_Afa
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$select2 =& $form->addElement('select', 'afa', $locale->get('products_field_vat'), $result->fetchAll('', $rekey = true));
		}
	}

	//idozito
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start')),
	        HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('products_deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('products_field_timerstart'), null, false
	);
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end')),
	        HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('products_deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('products_field_timerend'), null, false
	);

	//ha csatolhat dokumentumot
	if (!empty($_SESSION['site_shop_attach'])) {
		for ($a = 1; $a <= $_SESSION['site_shop_attachnum']; $a++) {
			$form->addElement('text', 'attname'.$a, $locale->get('products_field_attachdoc'));
			${'att'.$a} =& $form->addElement('file', 'attfile'.$a, null);
		}
		//modositas eseten a jelenlegi dokumentumok kiirasa
		if ($sub_act == 'mod' && isset($pid)) {
			//lekerdezzuk a jelenlegi dokumentum(ok)at
			$query = "
				SELECT document, document_gen, document_real 
				FROM iShark_Shop_Products_Document 
				WHERE product_id = $pid
			";
			$mdb2->setLimit($_SESSION['site_shop_prodpicnum']);
			$result =& $mdb2->query($query);
			//megnezzuk jelenleg hany dokumetum van feltoltve a termekhez
			$doc_num = $result->numRows();
			if ($doc_num > 0) {
				$q = 1;
				while ($row = $result->fetchRow())
				{
					${'olddoc'.$q}      = $row['document'];
					${'olddoc_gen'.$q}  = $row['document_gen'];
					${'olddoc_real'.$q} = $row['document_real'];

					$form->addElement('text', 'doc'.$q, $locale->get('products_field_currentdoc'), array('value' => ${'olddoc'.$q}));
					${'deldoc'.$q} =& $form->addElement('checkbox', 'deldoc'.$q, '', $locale->get('products_field_deldoc'));
					$q++;
				}
			}
		}
	}

	//ha tolthet fel kepet
	if (!empty($_SESSION['site_shop_mainpic'])) {
		for ($p = 1; $p <= $_SESSION['site_shop_prodpicnum']; $p++) {
			${'file'.$p} =& $form->addElement('file', 'picture'.$p, $locale->get('products_field_picture'));
		}
		//modositas eseten jelenlegi kep kirajzolasa
		if ($sub_act == 'mod' && isset($pid)) {
			//lekerdezzuk a jelenlegi kep(ek)et
			$query = "
				SELECT picture 
				FROM iShark_Shop_Products_Picture 
				WHERE product_id = $pid 
			";
			$mdb2->setLimit($_SESSION['site_shop_prodpicnum']);
			$result =& $mdb2->query($query);
			//megnezzuk jelenleg hany kep van feltoltve a termekhez
			$pic_num = $result->numRows();
			if ($pic_num > 0) {
				$q = 1;
				while ($row = $result->fetchRow())
				{
					${'oldpic'.$q} = $row['picture'];

					$form->addElement('static', 'pic'.$q, $locale->get('products_field_curpic'), '<img src="'.$_SESSION['site_shop_prodpicdir'].'/'.${'oldpic'.$q}.'" alt="'.${'oldpic'.$q}.'" />' );
					${'delpic'.$q} =& $form->addElement('checkbox', 'delpic'.$q, '', $locale->get('products_field_delpic'));
					$q++;
				}
			}
		}
	}

	//ha csoportokba rendezhetjuk, akkor azt a listat mutatjuk
	if (!empty($_SESSION['site_shop_groupuse'])) {
		//lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a termeket
		$query = "
			SELECT g.group_id AS gid, g.group_name AS gname 
			FROM iShark_Shop_Groups g 
			WHERE g.is_active = 1 
			ORDER BY g.group_name
		";
		$result =& $mdb2->query($query);
		$select =& $form->addElement('select', 'groups', $locale->get('products_field_groups'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);
	}
	//ha nem csoportok szerint rendezzuk, akkor a kategoriakat mutatjuk
	else {
		include_once $include_dir.'/function.shop.php';
		$category = get_category();
		$category = explode(";", $category);
		$cat = array();
		foreach ($category as $key => $value) {
			$cati = explode(",", $value);
			if ($cati[0] != "") {
				$cat[$cati[0]] = $cati[1];
			}
		}
		//atadjuk a $pid erteket, mert ez alapjan dolgozik az ajax-os script, ha nincs, akkor 0
		if (isset($pid)) {
			$product_id = $pid;
		} else {
			$product_id = 0;
		}
		$select =& $form->addElement('select', 'category', $locale->get('products_field_category'), $cat, array('id' => 'category', 'onclick' => 'proplist('.$product_id.');'));
		$select->setSize(5);
		$select->setMultiple(true);

		//ajax-hoz szukseges infok
		$ajax_script    = "
			function proplist(pid) {
				var id  = document.getElementById('category');
				var idk = '';
				for (i = 0; i < id.length; i++) {
					if (id[i].selected == true) {
						idk += id[i].value+',';
					}
				}
				HTML_AJAX.replace('target','ajax.php?act=shopprod&cid='+idk);
		";
		if (!empty($_SESSION['site_shop_joinprod']) && !empty($_SESSION['site_shop_joinourcat'])) {
			$ajax_script .= "
				HTML_AJAX.replace('target', 'ajax.php?act=joinprod&cid='+idk+'&pid='+pid);
			";
		}
		$ajax_script .= "
			}
		";
		$ajax['link']   = "ajax.php?client=all&stub=all";
		$ajax['script'] = $ajax_script;
	}

	//ha kapcsolhatunk hozza mas termekeket
	if (!empty($_SESSION['site_shop_joinprod'])) {
		//lekerdezzuk, hogy milyen termekeket lehet hozza kapcsolni
		if (isset($_SESSION['site_shop_joinourcat']) && $_SESSION['site_shop_joinourcat'] == 0) {
			$query = "
				SELECT p.product_id AS pid, p.product_name AS pname 
				FROM iShark_Shop_Products p 
				WHERE p.is_active = 1 AND p.is_deleted = 0 
			";
			//ha modositas, akkor kiszedjuk a pont modositott termeket
			if ($sub_act == "mod" && isset($pid) && is_numeric($pid)) {
				$query .= "
					AND p.product_id != $pid
				";
			}
			$query .= "
				ORDER BY p.product_name
			";
			$result =& $mdb2->query($query);
			$select2 =& $form->addElement('select', 'joinprod', $locale->get('products_field_joinprod'), $result->fetchAll('', $rekey = true), array('id' => 'joinprod'));
		} else {
			$select2 =& $form->addElement('select', 'joinprod', $locale->get('products_field_joinprod'), null, array('id' => 'joinprod'));
		}
		$select2->setSize(5);
		$select2->setMultiple(true);
	}

	//extra attributumok kezelese
	if (!empty($_SESSION['site_shop_is_extra_attr'])) {
		$form->addElement('text',   'attr',          $locale->get('products_field_attributes'), array('size' => 50));
		$form->addElement('static', 'example', null, $locale->get('products_field_attributes_example'));
	}

	//leiras
	$description =& $form->addElement('textarea', 'desc', $locale->get('products_field_description'));

	//az extra mezok hozzaadasa resz
	if (!empty($_SESSION['site_shop_groupuse'])) {
		$query = "
			SELECT p.prop_id AS prop_id, p.prop_type AS prop_type, p.prop_value AS prop_value, p.prop_display AS prop_display 
			FROM iShark_Shop_Properties p 
	";
	} else {
		$query = "
			SELECT p.prop_id AS prop_id, p.prop_type AS prop_type, p.prop_value AS prop_value, p.prop_display AS prop_display, 
				pc.category_id AS pccat
			FROM iShark_Shop_Properties p, iShark_Shop_Properties_Category pc 
			WHERE p.prop_id = pc.prop_id 
			GROUP BY p.prop_id
		";
	}
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$prop_id    = $row['prop_id'];
			$prop_value = $row['prop_value'];
			if ($row['pccat'] == 0) {
				$form->addElement($row['prop_type'], $row['prop_value'], $row['prop_display'], array('id' => $row['prop_id']));
			} else {
				$form->addElement($row['prop_type'], $row['prop_value'], $row['prop_display'], array('id' => $row['prop_id'], 'disabled' => 'disabled'));
			}

			//lekerdezzuk, hogy van-e hozza ellenorzes
			if ($form->isSubmitted() && isset($_POST['category'])) {
				$query2 = "
					SELECT p.error_txt AS error_txt, p.error_check AS error_check 
					FROM iShark_Shop_Properties_Check p, iShark_Shop_Properties_Category pc 
					WHERE p.prop_id = $prop_id AND p.prop_id = pc.prop_id 
				";
				if (is_array($_POST['category']) && count($_POST['category']) > 0) {
					$where = "";
					foreach ($_POST['category'] as $key => $value) {
						if (!empty($value)) {
							$where .= $value.",";
						}
					}
					$where .= "0";
					$query2 .= "	
						AND pc.category_id IN ($where)
					";
				}
				$result2 = $mdb2->query($query2);
				if ($result2->numRows() > 0) {
					while ($row2 = $result2->fetchRow())
					{
						$form->addRule($prop_value, $row2['error_txt'], $row2['error_check']);
					}
				}
			}
		}
	}

	//ajax miatt kirakunk egy static elemet, benne egy div-vel
	$form->addElement('static', 'cat', '<div id="target"></div>');

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form->addRule('pref', $locale->get('products_error_preferred'),   'required');
	$form->addRule('name', $locale->get('products_error_name'),        'required');
	$form->addRule('item', $locale->get('products_error_item'),        'required');
	$form->addRule('desc', $locale->get('products_error_description'), 'required');
	//ha a felhasznalok vasarolhatnak
	if (!empty($_SESSION['site_shop_userbuy'])) {
		$form->addGroupRule('price', $locale->get('products_error_price1'), 'required');
		$form->addGroupRule('price', $locale->get('products_error_price2'), 'numeric');
		$form->addRule('afa',        $locale->get('products_error_vat'),    'required');
	}
	//ha hasznaljuk az allapotokat
	if (!empty($_SESSION['site_shop_stateuse'])) {
		$form->addRule('state', $locale->get('products_error_state'), 'required');
	}
	//ha csoportokat hasznalunk
	if (!empty($_SESSION['site_shop_groupuse'])) {
		$form->addGroupRule('groups', $locale->get('products_error_groups'), 'required');
	} else {
		$form->addGroupRule('category', $locale->get('products_error_category'), 'required');
	}
	//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
	if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
		$form->addFormRule('check_timer');
	}

	/**
	 * Ha uj termeket adunk hozza
	 */
	if ($sub_act == "add") {
		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add');

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);

		//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
		$form->setDefaults(array(
			'pref'      => 0,
			'languages' => $_SESSION['site_deflang']
			)
		);

		$form->addFormRule('check_addproduct');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$pref        = intval($form->getSubmitValue('pref'));
			$name        = $form->getSubmitValue('name');
			$item        = $form->getSubmitValue('item');
			$desc        = $form->getSubmitValue('desc');
			$timer_start = $form->getSubmitValue('timer_start');
			$timer_end   = $form->getSubmitValue('timer_end');
			$price       = $form->getSubmitValue('netto').".".$form->getSubmitValue('percent');
			$afa         = intval($form->getSubmitValue('afa'));
			$state       = intval($form->getSubmitValue('state'));

			//ha hasznaljuk az extra attributumokat
			if (isset($_SESSION['site_shop_is_extra_attr']) && $_SESSION['site_shop_is_extra_attr'] == 1) {
				$attr = $form->getSubmitValue('attr');
			} else {
				$attr = "";
			}

			//dokumentum(ok) feltoltese
			if (!empty($_SESSION['site_shop_attach'])) {
				for ($a = 1; $a <= $_SESSION['site_shop_attachnum']; $a++) {
					if (${'att'.$a}->isUploadedFile()) {
						$filevalues          = ${'att'.$a}->getValue();
						$sdir                = preg_replace('|/$|','', $_SESSION['site_shop_attachdir']).'/';
						${'docname_real'.$a} = $filevalues['name'];
						${'docname_gen'.$a}  = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
						${'docname'.$a}      = $form->getSubmitValue('attname'.$a);
						//feltoltjuk a file-t
						${'att'.$a}->moveUploadedFile($sdir, ${'docname_gen'.$a});
						//beallitjuk a jogosultsagot
						@chmod($sdir.${'docname_gen'.$a}, 0664);
					}
				}
			}

			//kep(ek) feltoltese
			if (!empty($_SESSION['site_shop_prodpic'])) {
				for ($p = 1; $p <= $_SESSION['site_shop_prodpicnum']; $p++) {
					if (${'file'.$p}->isUploadedFile()) {
						$filevalues      = ${'file'.$p}->getValue();
						$sdir            = preg_replace('|/$|','', $_SESSION['site_shop_prodpicdir']).'/';
						${'filename'.$p} = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
						$tn_name         = 'tn_'.${'filename'.$p};

						//kep atmeretezese
						include_once 'includes/function.images.php';
						if (($pic = img_resize($filevalues['tmp_name'], $sdir.${'filename'.$p}, $_SESSION['site_shop_prodpicwidth'], $_SESSION['site_shop_prodpicheight'])) && ($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_shop_prodpicswidth'], $_SESSION['site_shop_prodpicsheight']))) {
							//beallitjuk a jogosultsagot
							@chmod($sdir.${'filename'.$p}, 0664);
							@chmod($sdir.$tn_name, 0664);

							@unlink($filevalues['tmp_name']);
						} else {
							$form->setElementError('picture'.$p, $locale->get('products_error_upload'));
						}
					}
				}
			}

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (!empty($_SESSION['site_multilang'])) {
				$languages = $form->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			//lekerdezzuk a legmagasabb sorszamokat
			$maxorder = 0;
			$query = "
				SELECT MAX(sortorder) AS sortorder 
				FROM iShark_Shop_Products 
			";
			$result =& $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$maxorder = $row['sortorder'];
			}

			$product_id = $mdb2->extended->getBeforeID('iShark_Shop_Products', 'product_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Shop_Products 
				(product_id, product_name, item_id, product_desc, netto, afa, lang, add_user_id, add_date, 
				mod_user_id, mod_date, is_active, timer_start, timer_end, is_preferred, sortorder, state_id, attributes) 
				VALUES 
				($product_id, '".$name."', '".$item."', '".$desc."', '$price', $afa, '".$languages."', '".$_SESSION['user_id']."', NOW(),
				'".$_SESSION['user_id']."', NOW(), '1', '$timer_start', '$timer_end', $pref, $maxorder+1, $state, '".$attr."')
			";
			$mdb2->exec($query);

			//utolsonak felvitt termek azonositoja
			$last_prod_id = $mdb2->extended->getAfterID($product_id, 'iShark_Shop_Products', 'product_id');;

			//dokumentum(ok) felvitele
			if (!empty($_SESSION['site_shop_attach'])) {
				for ($p = 1; $p <= $_SESSION['site_shop_prodpicnum']; $p++) {
					if (isset(${'docname'.$p}) && isset(${'docname_real'.$p})) {
						$query = "
							INSERT INTO iShark_Shop_Products_Document 
							(product_id, document, document_gen, document_real) 
							VALUES 
							($last_prod_id, '".${'docname'.$p}."', '".${'docname_gen'.$p}."', '".${'docname_real'.$p}."')
						";
						$mdb2->exec($query);
					}
				}
			}

			//kepek felvitele
			if (!empty($_SESSION['site_shop_prodpic'])) {
				for ($p = 1; $p <= $_SESSION['site_shop_prodpicnum']; $p++) {
					if (isset(${'filename'.$p})) {
						$query = "
							INSERT INTO iShark_Shop_Products_Picture 
							(product_id, picture) 
							VALUES 
							($last_prod_id, '".${'filename'.$p}."')
						";
						$mdb2->exec($query);
					}
				}
			}

			//ha vannak plusz mezok, akkor azokhoz is feltoltjuk az adatokat
			$query = "
				SELECT prop_value 
				FROM iShark_Shop_Properties
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					$fields = $row['prop_value'];
					if (is_numeric($form->getSubmitValue($fields))) {
						$load = intval($form->getSubmitValue($fields));
					} else {
						$load = $form->getSubmitValue($fields);
					}
					$query2 = "
						UPDATE iShark_Shop_Products 
						SET $fields = '$load'
						WHERE product_id = $last_prod_id
					";
					$mdb2->exec($query2);
				}
			}

			//ha csoportokba rendezzuk a termekeket
			if (!empty($_SESSION['site_shop_groupuse'])) {
				$groups = $form->getSubmitValue('groups');
				if (is_array($groups) && count($groups) > 0) {
					foreach ($groups as $key => $value) {
						$query = "
							INSERT INTO iShark_Shop_Products_Groups 
							(product_id, group_id) 
							VALUES 
							($last_prod_id, $value)
						";
						$mdb2->exec($query);

						//lekerdezzuk, hogy a csoport hozza van-e mar kapcsolva kategoriahoz
						//ha igen, akkor a termeket hozzaadjuk a kategoriahoz
						$query2 = "
							SELECT category_id 
							FROM iShark_Shop_Category_Groups 
							WHERE group_id = $value
						";
						$result2 = $mdb2->query($query2);
						if ($result2->numRows() > 0) {
							while ($row2 = $result2->fetchRow())
							{
								$cat_id = $row2['category_id'];

								$query3 = "
									SELECT * 
									FROM iShark_Shop_Products_Category 
									WHERE product_id = $last_prod_id AND category_id = $cat_id
								";
								$result3 = $mdb2->query($query3);
								if ($result3->numRows() == 0) {
									$query4 = "
										INSERT INTO iShark_Shop_Products_Category 
										(product_id, category_id) 
										VALUES 
										($last_prod_id, $cat_id)
									";
									$mdb2->exec($query4);
								}
							}
						}
					}
				}
			}
			//ha kategoriakba rendezzuk egybol a termekeket
			else {
				$category = $form->getSubmitValue('category');
				if (is_array($category) && count($category) > 0) {
					foreach ($category as $key => $value) {
						$query = "
							INSERT INTO iShark_Shop_Products_Category 
							(product_id, category_id) 
							VALUES 
							($last_prod_id, $value)
						";
						$mdb2->exec($query);
					}
				}
			}

			//ha vannak kapcsolodo termekek
			if (!empty($_SESSION['site_shop_joinprod'])) {
				$joinprod = $form->getSubmitValue('joinprod');
				if (is_array($joinprod) && count($joinprod) > 0) {
					foreach ($joinprod as $key => $value) {
						$query = "
							INSERT INTO iShark_Shop_Products_Join 
							(product_id, join_id) 
							VALUES 
							($last_prod_id, $value)
						";
						$mdb2->exec($query);
					}
				}
			}

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
			exit;
		}
	} //hozzadas vege

	/**
	 * modositas
	 */
	if ($sub_act == "mod") {
		if (isset($pid)) {
			//breadcrumb
			$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;pid='.$pid);

			//ha vannak plusz mezok, akkor azokbol csinalunk egy tombot, amit belerakunk a lekerdezesbe
			$query = "
				SELECT prop_value 
				FROM iShark_Shop_Properties
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$plusfields = array();
				while ($row = $result->fetchRow())
				{
					$plusfields[] = $row['prop_value'];
				}
			}

			//lekerdezzuk, hogy tenyleg letezik-e a termek
			$query = "
				SELECT p.item_id AS item, p.product_name AS pname, p.product_desc AS pdesc, p.lang AS lang, 
					p.timer_start AS timer_start, p.timer_end AS timer_end, p.is_preferred AS ispref, p.netto AS netto, 
					p.afa AS afa, p.state_id AS state, p.attributes AS attr 
			";
			//ha vannak plusz mezok
			if (isset($plusfields) && is_array($plusfields) && count($plusfields) > 0) {
				$plusquery = implode(",", $plusfields);
				$query .= ", ".$plusquery;
			}
			$query .= "
				FROM iShark_Shop_Products p 
				WHERE p.product_id = $pid AND p.is_deleted = 0
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				//form-hoz elemek hozzaadasa - csak modositasnal
				$form->addElement('hidden', 'act',     $page);
				$form->addElement('hidden', 'sub_act', $sub_act);
				$form->addElement('hidden', 'pid',     $pid);
				$form->addElement('hidden', 'field',   $field);
				$form->addElement('hidden', 'ord',     $ord);
				$form->addElement('hidden', 'cat_fil', $cat_fil);

				while ($row = $result->fetchRow())
				{
					if ($row['timer_start'] == "0000-00-00 00:00:00") {
						$timer_start = "";
					} else {
						$timer_start = $row['timer_start'];
					}
					if ($row['timer_end'] == "0000-00-00 00:00:00") {
						$timer_end = "";
					} else {
						$timer_end = $row['timer_end'];
					}

					//kiszedjuk a netto mezobol a forint es filler ertekeket
					$price = explode('.', $row['netto']);

					//beallitjuk az alapertelmezett ertekeket, csak modositasnal
					$form->setDefaults(array(
						'languages'   => $row['lang'],
						'pref'        => $row['ispref'],
						'name'        => $row['pname'],
						'item'        => $row['item'],
						'desc'        => $row['pdesc'],
						'timer_start' => $timer_start,
						'timer_end'   => $timer_end,
						'netto'       => $price[0],
						'percent'     => $price[1],
						'afa'         => $row['afa'],
						'state'       => $row['state'],
						'attr'        => $row['attr']
						)
					);
					//ha vannak plusz mezok
					if (isset($plusfields) && is_array($plusfields) && count($plusfields) > 0) {
						$plus = array();
						foreach ($plusfields as $key => $value) {
							$plus[$value] = $row[$value];
						}
						$form->setDefaults($plus);
					}

					//lekerdezzuk a mar rogzitett csoportokat vagy termekeket
					if (!empty($_SESSION['site_shop_groupuse'])) {
						$query = "
							SELECT group_id 
							FROM iShark_Shop_Products_Groups 
							WHERE product_id = $pid
						";
						$result =& $mdb2->query($query);
						$select->setSelected($result->fetchCol());
					} else {
						$query = "
							SELECT category_id 
							FROM iShark_Shop_Products_Category 
							WHERE product_id = $pid
						";
						$result =& $mdb2->query($query);
						$select->setSelected($result->fetchCol());
					}

					//lekerdezzuk a mar rogzitett kapcsolt termekeket
					if (!empty($_SESSION['site_shop_joinprod']) && isset($_SESSION['site_shop_joinourcat']) && $_SESSION['site_shop_joinourcat'] == 0) {
						$query = "
							SELECT join_id 
							FROM iShark_Shop_Products_Join 
							WHERE product_id = $pid
						";
						$result =& $mdb2->query($query);
						$select2->setSelected($result->fetchCol());
					}

					//ellenorzes, vegso muveletek
					$form->addFormRule('check_modproduct');

					//ha dokumentumot is lehet feltolteni
					if (!empty($_SESSION['site_shop_attach'])) {
						//megszamoljuk hany dokumentumot akarnak most feltolteni
						$newdoc_num = 0;
						for ($s = 1; $s <= $_SESSION['site_shop_attachnum']; $s++) {
							if (${'att'.$s}->isUploadedFile()) {
								$newdoc_num++;
							}
						}
						//megszamoljuk hany dokumentumot akarnak most kitorolni
						$deldoc_num = 0;
						for ($q = 1; $q <= $_SESSION['site_shop_attachnum']; $q++) {
							if (isset(${'deldoc'.$q}) && ${'deldoc'.$q}->getChecked()) {
								$deldoc_num++;
							}
						}
						//ha a regi dokumentumok szama + az uj dokumentumok szama - torlesre jelolet dokumentumok szama 
						//meghaladja a feltoltheto dokumentumok szamat, akkor hibauzenet
						if ($doc_num+$newdoc_num-$deldoc_num > $_SESSION['site_shop_attachnum']) {
							$form->setElementError('attname1', $locale->getBySmarty('products_error_maxdoc'));
						}
					}

					//ha kepet is lehet feltolteni
					if (!empty($_SESSION['site_shop_prodpic'])) {
						//megszamoljuk hany kepet akarnak most feltolteni
						$newpic_num = 0;
						for ($s = 1; $s <= $_SESSION['site_shop_prodpicnum']; $s++) {
							if (${'file'.$s}->isUploadedFile()) {
								$newpic_num++;
							}
						}
						//megszamoljuk hany kepet akarnak kitorolni
						$delpic_num = 0;
						for ($q = 1; $q <= $_SESSION['site_shop_prodpicnum']; $q++) {
							if (isset(${'delpic'.$q}) && ${'delpic'.$q}->getChecked()) {
								$delpic_num++;
							}
						}
						//ha a regi kepek szama + az uj kepek szama - torlesre jelolet kepek szama 
						//meghaladja a feltoltheto kepek szamat, akkor hibauzenet
						if ($pic_num+$newpic_num-$delpic_num > $_SESSION['site_shop_prodpicnum']) {
							$form->setElementError('picture1', $locale->getBySmarty('products_error_maxpic'));
						}
					}

					//form validalas
					if ($form->validate()) {
						$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

						//ha tolthet fel dokumentumot
						if (!empty($_SESSION['site_shop_attach'])) {
							//dokumentum(ok) frissitese
							for ($q = 1; $q <= $_SESSION['site_shop_attachnum']; $q++) {
								if (isset(${'doc'.$q}) && !empty(${'doc'.$q}) && isset(${'olddoc_gen'.$q})) {
									$query = "
										UPDATE iShark_Shop_Products_Document 
										SET document = '".${'doc'.$q}."' 
										WHERE product_id = $pid AND document_gen = '".${'olddoc_gen'.$q}."' AND document_real = '".${'olddoc_real'.$q}."'
									";
									$mdb2->query($query);
								}
							}

							//ha ki akarjuk torolni a regi dokumentumot - de semmi mast nem csinalunk
							for ($q = 1; $q <= $_SESSION['site_shop_attachnum']; $q++) {
								if (isset(${'deldoc'.$q}) && ${'deldoc'.$q}->getChecked()) {
									if (file_exists($_SESSION['site_shop_attachdir'].'/'.${'olddoc_gen'.$q})) {
										@unlink($_SESSION['site_shop_attachdir'].'/'.${'olddoc_gen'.$q});
									}
									//kitoroljuk a tablabol a torlendo bejegyzeseket
									$query = "
										DELETE FROM iShark_Shop_Products_Document 
										WHERE product_id = $pid AND document = '".${'olddoc'.$q}."' AND document_gen = '".${'olddoc_gen'.$q}."' AND document_real = '".${'olddoc_real'.$q}."'
									";
									$mdb2->exec($query);
								}
							}

							//dokumentum(ok) feltoltese
							for ($a = 1; $a <= $_SESSION['site_shop_attachnum']; $a++) {
								if (${'att'.$a}->isUploadedFile()) {
									$filevalues          = ${'att'.$a}->getValue();
									$sdir                = preg_replace('|/$|','', $_SESSION['site_shop_attachdir']).'/';
									${'docname_real'.$a} = $filevalues['name'];
									${'docname_gen'.$a}  = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
									${'docname'.$a}      = $form->getSubmitValue('attname'.$a);
									//feltoltjuk a file-t
									${'att'.$a}->moveUploadedFile($sdir, ${'docname_gen'.$a});
									//beallitjuk a jogosultsagot
									@chmod($sdir.${'docname_gen'.$a}, 0664);

									$query = "
										INSERT INTO iShark_Shop_Products_Document 
										(product_id, document, document_gen, document_real) 
										VALUES 
										($pid, '".${'docname'.$a}."', '".${'docname_gen'.$a}."', '".${'docname_real'.$a}."')
									";
									$mdb2->exec($query);
								}
							}
						}

						//ha tolthet fel kepet
						if (!empty($_SESSION['site_shop_prodpic'])) {
							//ha ki akarjuk torolni a regi kepet - de semmi mast nem csinalunk
							for ($q = 1; $q <= $_SESSION['site_shop_prodpicnum']; $q++) {
								if (isset(${'delpic'.$q}) && ${'delpic'.$q}->getChecked()) {
									if (file_exists($_SESSION['site_shop_prodpicdir'].'/'.${'oldpic'.$q})) {
										@unlink($_SESSION['site_shop_prodpicdir'].'/'.${'oldpic'.$q});
										@unlink($_SESSION['site_shop_prodpicdir'].'/tn_'.${'oldpic'.$q});
									}
									//kitoroljuk a tablabol a torlendo bejegyzeseket
									$query = "
										DELETE FROM iShark_Shop_Products_Picture 
										WHERE product_id = $pid AND picture = '".${'oldpic'.$q}."'
									";
									$mdb2->exec($query);
								}
							}

							//kep(ek) feltoltese
							for ($p = 1; $p <= $_SESSION['site_shop_prodpicnum']; $p++) {
								if (${'file'.$p}->isUploadedFile()) {
									$filevalues = ${'file'.$p}->getValue();
									$sdir = preg_replace('|/$|','', $_SESSION['site_shop_prodpicdir']).'/';
									${'filename'.$p} = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
									$tn_name = 'tn_'.${'filename'.$p};

									//kep atmeretezese
									include_once 'includes/function.images.php';
									if (($pic = img_resize($filevalues['tmp_name'], $sdir.${'filename'.$p}, $_SESSION['site_shop_prodpicwidth'], $_SESSION['site_shop_prodpicheight'])) && 
									($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_shop_prodpicswidth'], $_SESSION['site_shop_prodpicsheight']))) {
										@chmod($sdir.${'filename'.$p},0664);
										@chmod($sdir.$tn_name,0664);

										@unlink($filevalues['tmp_name']);
										//beszurjuk az uj file-okat a tablaba
										$query = "
											INSERT INTO iShark_Shop_Products_Picture 
											(product_id, picture) 
											VALUES 
											($pid, '".${'filename'.$p}."')
										";
										$mdb2->exec($query);
									} else {
										$form->setElementError('picture'.$p, $locale->get('products_error_upload'));
									}
								}
							}
						}

						$name        = $form->getSubmitValue('name');
						$item        = $form->getSubmitValue('item');
						$desc        = $form->getSubmitValue('desc');
						$pref        = intval($form->getSubmitValue('pref'));
						$timer_start = $form->getSubmitValue('timer_start');
						$timer_end   = $form->getSubmitValue('timer_end');
						$price       = $form->getSubmitValue('netto').".".$form->getSubmitValue('percent');
						$afa         = intval($form->getSubmitValue('afa'));
						$state       = intval($form->getSubmitValue('state'));
						$field       = intval($form->getSubmitValue('field'));
						$ord 		 = $form->getSubmitValue('ord');
						$cat_fil	 = $form->getSubmitValue('cat_fil');

						//ha hasznaljuk az extra attributumokat
						if (!empty($_SESSION['site_shop_is_extra_attr'])) {
							$attr = $form->getSubmitValue('attr');
						} else {
							$attr = "";
						}

						//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
						if (!empty($_SESSION['site_multilang'])) {
							$languages = $form->getSubmitValue('languages');
						} else {
							$languages = $_SESSION['site_deflang'];
						}

						$query = "
							UPDATE iShark_Shop_Products 
							SET item_id      = '".$item."',
								product_name = '".$name."', 
								product_desc = '".$desc."', 
								mod_user_id  = '".$_SESSION['user_id']."', 
								mod_date     = NOW(),
								timer_start  = '$timer_start',
								timer_end    = '$timer_end',
								lang         = '".$languages."',
								is_preferred = $pref,
								netto        = '$price',
								afa          = $afa,
								state_id     = $state,
								attributes   = '$attr'
							WHERE product_id = $pid
						";
						$mdb2->exec($query);

						//ha vannak plusz mezok, akkor azokhoz is feltoltjuk az adatokat
						$query = "
							SELECT prop_value 
							FROM iShark_Shop_Properties
						";
						$result =& $mdb2->query($query);
						if ($result->numRows() > 0) {
							while ($row = $result->fetchRow())
							{
								$field = $row['prop_value'];
								if (is_numeric($form->getSubmitValue($field))) {
									$load = intval($form->getSubmitValue($field));
								} else {
									$load = $form->getSubmitValue($field);
								}
								$query2 = "
									UPDATE iShark_Shop_Products 
									SET $field = '$load'
									WHERE product_id = $pid
								";
								$mdb2->exec($query2);
							}
						}

						//ha csoportokat hasznalunk
						if (!empty($_SESSION['site_shop_groupuse'])) {
							//lekerdezzuk, hogy milyen csoportok tartoztak ehhez a termekhez
							$query = "
								SELECT group_id 
								FROM iShark_Shop_Products_Groups 
								WHERE product_id = $pid
							";
							$result =& $mdb2->query($query);
							if ($result->numRows() > 0) {
								while ($row = $result->fetchRow())
								{
									$group_id = $row['group_id'];

									//lekerdezzuk, hogy a regi csoportok milyen kategoriaknal voltak
									$query2 = "
										SELECT category_id 
										FROM iShark_Shop_Category_Groups 
										WHERE group_id = $group_id
									";
									$result2 = $mdb2->query($query2);
									if ($result2->numRows() > 0) {
										while ($row2 = $result2->fetchRow())
										{
											$cat_id = $row2['category_id'];

											//kitoroljuk a termekeket, amik ehhez a csoporthoz tartoznak
											$query3 = "
												DELETE FROM iShark_Shop_Products_Category 
												WHERE product_id = $pid AND category_id = $cat_id
											";
											$mdb2->exec($query3);
										}
									}
								}
							}

							//kitoroljuk a termek - csoport kapcsolatokat
							$query = "
								DELETE FROM iShark_Shop_Products_Groups 
								WHERE product_id = $pid
							";
							$mdb2->exec($query);

							//hozzaadjuk az uj csoportokhoz tartozo termekeket
							$groups = $form->getSubmitValue('groups');
							if (is_array($groups) && count($groups) > 0) {
								foreach ($groups as $key => $value) {
									//osszekapcsoljuk a csoportokat a kategoriaval
									$query = "
										INSERT INTO iShark_Shop_Products_Groups 
										(product_id, group_id) 
										VALUES 
										($pid, $value)
									";
									$mdb2->exec($query);

									//berakjuk a kategoriakhoz a csoportokban levo termekeket
									$query = "
										SELECT cg.category_id AS category_id 
										FROM iShark_Shop_Products_Groups g, iShark_Shop_Category_Groups cg 
										WHERE g.group_id = cg.group_id AND g.group_id = $value
									";
									$result =& $mdb2->query($query);
									if ($result->numRows() > 0) {
										while ($row = $result->fetchRow())
										{
											$cat_id = $row['category_id'];
											$query2 = "
												SELECT * 
												FROM iShark_Shop_Products_Category 
												WHERE product_id = $pid AND category_id = $cat_id
											";
											$result2 = $mdb2->query($query2);
											if ($result2->numRows() == 0) {
												$query3 = "
													INSERT INTO iShark_Shop_Products_Category 
													(product_id, category_id) 
													VALUES 
													($pid, $cat_id)
												";
												$mdb2->exec($query3);
											}
										}
									}
								}
							}
						}
						//ha termekeket kozvetlenul kapcsoljuk
						else {
							//kitoroljuk a jelenlegi termek-kategoria kapcsolatokat
							$query = "
								DELETE FROM iShark_Shop_Products_Category 
								WHERE product_id = $pid
							";
							$mdb2->exec($query);

							$cat = $form->getSubmitValue('category');

							if (is_array($cat) && count($cat) > 0) {
								foreach ($cat as $key => $value) {
									$query = "
										INSERT INTO iShark_Shop_Products_Category 
										(product_id, category_id) 
										VALUES 
										($pid, $value)
									";
									$mdb2->exec($query);
								}
							}
						}

						//ha vannak kapcsolodo termekek
						if (!empty($_SESSION['site_shop_joinprod'])) {
							//kitoroljuk a jelenlegi kapcsolatokat
							$query = "
								DELETE FROM iShark_Shop_Products_Join 
								WHERE product_id = $pid
							";
							$mdb2->exec($query);

							$joinprod = $form->getSubmitValue('joinprod');

							if (is_array($joinprod) && count($joinprod) > 0) {
								foreach ($joinprod as $key => $value) {
									$query = "
										INSERT INTO iShark_Shop_Products_Join 
										(product_id, join_id) 
										VALUES 
										($pid, $value)
									";
									$mdb2->exec($query);
								}
							}
						}

						//loggolas
						logger($page.'_'.$sub_act);

						//"fagyasztjuk" a form-ot
						$form->freeze();

						//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
						if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
							header('Location: admin.php?p='.$module_name.'&act=search&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
							exit;
						} else {
							header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
							exit;
						}
					}
				}
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('products_error_notexists'));
				return;
			}
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('products_error_notexists'));
			return;
		}
	} //modositas vege

	$form->addElement('submit', 'submit', $locale->get('products_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('products_form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('tiny_fields', 'desc');
	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form',        $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_form', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha aktivalunk vagy inaktivalunk egy termeket
 */
if ($sub_act == "act") {
	if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid'])) {
		include_once $include_dir.'/function.check.php';
		$pid = intval($_REQUEST['pid']);

		check_active('iShark_Shop_Products', 'product_id', $pid);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=search&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('products_error_notexists'));
		return;
	}
}

/**
 * ha torlunk egy termeket
 */
if ($sub_act == "del") {
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$pid = intval($_GET['pid']);

		$query = "
			UPDATE iShark_Shop_Products 
			SET is_deleted = 1 
			WHERE product_id = $pid
		";
		$mdb2->exec($query);

		//kitoroljuk a kategoriakbol a termeket
		$query = "
			DELETE FROM iShark_Shop_Products_Category 
			WHERE product_id = $pid
		";
		$mdb2->exec($query);

		//ha hasznaljuk a csoportokat, akkor kitoroljuk a csoportbol
		if (!empty($_SESSION['site_shop_groupuse'])) {
			$query = "
				DELETE FROM iShark_Shop_Products_Groups 
				WHERE product_id = $pid
			";
			$mdb2->exec($query);
		}

		//kitoroljuk az akcios termekek kozul a termeket
		$query = "
			DELETE FROM iShark_Shop_Actions_Products 
			WHERE product_id = $pid
		";
		$mdb2->exec($query);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=search&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&field='.$field.'&ord='.$ord.'&pageID='.$page_id.'&cat_fil='.$cat_fil);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('products_error_notexists'));
		return;
	}
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	$query = "
		SELECT p.item_id AS item, p.product_id AS pid, p.product_name AS pname, u1.name AS ausr, p.add_date AS adate, 
			u2.name AS musr, p.mod_date AS mdate, p.is_active AS isact, p.lang AS plang, p.is_preferred AS ispref 
		FROM iShark_Shop_Products p 
		LEFT JOIN iShark_Users u1 ON u1.user_id = p.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = p.mod_user_id 
		$join_table
		WHERE p.is_deleted = 0 $catszur
	";
	//sorbarendezes tipusa - ABC
	if (!empty($_SESSION['site_shop_ordertype'])) {
		$query .= "
			ORDER BY p.is_preferred DESC, $fieldorder $order, p.product_name
		";
	}
	//sorbarendezes tipusa - egyedi
	if (isset($_SESSION['site_shop_ordertype']) && $_SESSION['site_shop_ordertype'] == 2) {
		$query .= "
			ORDER BY p.is_preferred DESC, $fieldorder $order, p.sortorder
		";
	}

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;field='.$field.'&amp;ord='.$ord.'&amp;pageID='.$page_id.'&amp;cat_fil='.$cat_fil,
			'title' => $locale->get('products_title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('add_new',   $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_products_list';
} //lista vege

?>