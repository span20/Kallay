<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$javascripts[] = "javascripts";

$groupnums = 0;
//lekerdezzuk, hogy mennyi kategoria van jelenleg
$query = "
	SELECT * 
	FROM iShark_Shop_Category 
";
$result =& $mdb2->query($query);
$groupnums = $result->numRows();

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
    $titles = array('add' => $locale->get('category_title_add'), 'mod' => $locale->get('category_title_mod'));

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';
	require_once $include_dir.'/function.shop.php';

	$form_shop =& new HTML_QuickForm('frm_shop', 'post', 'admin.php?p='.$module_name);
	$form_shop->removeAttribute('name');

	$form_shop->setRequiredNote($locale->get('category_form_required_note'));

	$form_shop->addElement('header', 'category', $locale->get('category_header'));
	//ha kereses volt, akkor bele kell tenni hidden-be a mezoket
	if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
		$form_shop->addElement('hidden', 's',          intval($_REQUEST['s']));
		$form_shop->addElement('hidden', 'searchtext', $_REQUEST['searchtext']);
		$form_shop->addElement('hidden', 'searchtype', $_REQUEST['searchtype']);
	}

	//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
	if (!empty($_SESSION['site_multilang'])) {
		$form_shop->addElement('select', 'languages', $locale->get('category_field_lang'), $locale->getLocales());
	}

	//kategoria neve
	$form_shop->addElement('text', 'name', $locale->get('category_field_name'));

	//idozito
	$form_shop->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start')),
	        HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('category_field_timerstart'), null, false
	);
	$form_shop->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end')),
	        HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('category_field_timerend'), null, false
	);

	//kategoria kep
	if (!empty($_SESSION['site_shop_mainpic'])) {
		$file =& $form_shop->addElement('file', 'picture', $locale->get('category_field_picture'));
	}

	//kategoria leirasa
	$description =& $form_shop->addElement('textarea', 'desc', $locale->get('category_field_description'));

	//ha csoportokba rendezzuk a termekeket, kategoriakat
	if (!empty($_SESSION['site_shop_groupuse'])) {
		//lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a termeket
		$query = "
			SELECT g.group_id AS gid, g.group_name AS gname 
			FROM iShark_Shop_Groups g 
			WHERE g.is_active = 1 
			ORDER BY g.group_name
		";
		$result =& $mdb2->query($query);
		$select =& $form_shop->addElement('select', 'groups', $locale->get('category_field_groups'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);
	}
	//ha csak termeket adhatunk hozza
	else {
		//lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a termeket
		$query = "
			SELECT p.product_id AS pid, p.product_name AS pname 
			FROM iShark_Shop_Products p 
			WHERE p.is_active = 1 AND p.is_deleted = 0
			ORDER BY p.product_name
		";
		$result =& $mdb2->query($query);
		$select =& $form_shop->addElement('select', 'prods', $locale->get('category_field_products'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);
	}

	$form_shop->addElement('submit', 'submit', $locale->get('category_form_submit'), 'class="submit"');
	$form_shop->addElement('reset',  'reset',  $locale->get('category_form_reset'),  'class="reset"');

	//szurok beallitasa
	$form_shop->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form_shop->addRule('name', $locale->get('category_error_name'),        'required');
	$form_shop->addRule('desc', $locale->get('category_error_description'), 'required');

	//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
	if ($form_shop->isSubmitted() && ($form_shop->getSubmitValue('timer_start') != "" || $form_shop->getSubmitValue('timer_end') != "")) {
		$form_shop->addFormRule('check_timer');
	}

	/**
	 * Ha uj kategoriat adunk hozza
	 */
	if ($sub_act == "add") {
		if (!empty($_SESSION['site_shop_maincat']) && $_SESSION['site_shop_maincat'] <= $groupnums) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('category_error_groupnum'));
			return;
		} else {
			//breadcrumb
			$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add');

			//form-hoz elemek hozzaadasa - csak hozzaadasnal
			$form_shop->addElement('hidden', 'sub_act', $sub_act);

			//ha van parent, akkor alkategoria
			if (isset($_REQUEST['par']) && is_numeric($_REQUEST['par'])) {
				$form_shop->addElement('hidden', 'par', $_REQUEST['par']);
			} else {
				$form_shop->addElement('hidden', 'par', 0);
			}

			//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
			$form_shop->setDefaults(array(
				'languages' => $_SESSION['site_deflang']
				)
			);

			$form_shop->addFormRule('check_addcategory');
			if ($form_shop->validate()) {
				$form_shop->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name        = $form_shop->getSubmitValue('name');
				$desc        = $form_shop->getSubmitValue('desc');
				$parent      = intval($form_shop->getSubmitValue('par'));
				$filename    = "";
				$timer_start = $form_shop->getSubmitValue('timer_start');
				$timer_end   = $form_shop->getSubmitValue('timer_end');

				//kep feltoltese
				if (!empty($_SESSION['site_shop_mainpic'])) {
					if ($file->isUploadedFile()) {
						$filevalues = $file->getValue();
						$sdir = preg_replace('|/$|','', $_SESSION['site_shop_mainpicdir']).'/';
						$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
						$tn_name = 'tn_'.$filename;

						//kep atmeretezese
						include_once 'includes/function.images.php';
						if (($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_shop_mainpicwidth'], $_SESSION['site_shop_mainpicheight'])) && 
						($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_shop_mainpicswidth'], $_SESSION['site_shop_mainpicsheight']))) {
							@chmod($sdir.$filename,0664);
							@chmod($sdir.$tn_name,0664);

							@unlink($filevalues['tmp_name']);
						}
						$form_shop->setElementError('picture', $locale->get('category_error_upload'));
					}
				}

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (!empty($_SESSION['site_multilang'])) {
					$languages = $form_shop->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				//lekerdezzuk a legmagasabb sorszamokat
				$maxorder = 0;
				$query = "
					SELECT MAX(sortorder) AS sortorder 
					FROM iShark_Shop_Category 
				";
				$result =& $mdb2->query($query);
				while ($row = $result->fetchRow())
				{
					$maxorder = $row['sortorder'];
				}

				$category_id = $mdb2->extended->getBeforeID('iShark_Shop_Category', 'category_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Shop_Category 
					(category_id, category_name, category_desc, parent, sortorder, add_user_id, add_date, mod_user_id, mod_date, 
					 is_active, timer_start, timer_end, is_preferred, picture, lang) 
					VALUES 
					($category_id, '".$name."', '".$desc."', $parent, $maxorder+1, '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', NOW(), 
					 1, '$timer_start', '$timer_end', 0, '".$filename."', '".$languages."')
				";
				$mdb2->exec($query);
				$last_cat_id = $mdb2->extended->getAfterID($category_id, 'iShark_Shop_Category', 'category_id');

				//felvisszuk a kategoria(k)hoz a csoportot
				if (!empty($_SESSION['site_shop_groupuse'])) {
					$groups   = $form_shop->getSubmitValue('groups');
					if (is_array($groups) && count($groups) > 0) {
						foreach ($groups as $key => $value) {
							//osszekapcsoljuk a csoportokat a kategoriaval
							$query = "
								INSERT INTO iShark_Shop_Category_Groups 
								(category_id, group_id) 
								VALUES 
								($last_cat_id, $value)
							";
							$mdb2->exec($query);

							//berakjuk a kategoriakhoz a csoportokban levo termekeket
							$query = "
								SELECT product_id 
								FROM iShark_Shop_Products_Groups 
								WHERE group_id = $value
							";
							$result =& $mdb2->query($query);
							if ($result->numRows() > 0) {
								while ($row = $result->fetchRow())
								{
									$prod_id = $row['product_id'];
									$query2 = "
										INSERT INTO iShark_Shop_Products_Category 
										(product_id, category_id) 
										VALUES 
										($prod_id, $last_cat_id)
									";
									$mdb2->exec($query2);
								}
							}
						}
					}
				}
				//felvisszuk a kategoriahoz a termekeket
				else {
					$prods = $form_shop->getSubmitValue('prods');

					if (is_array($prods) && count($prods) > 0) {
						foreach ($prods as $key => $value) {
							$query = "
								INSERT INTO iShark_Shop_Products_Category 
								(product_id, category_id) 
								VALUES 
								($value, $last_cat_id)
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
				header('Location: admin.php?p='.$module_name.'&act='.$page);
				exit;
			}
		}
	} //hozzaadas vege

	/**
	 * Ha modositunk egy kategoriat
	 */
	if ($sub_act == "mod") {
		if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid']) && isset($_REQUEST['par']) && is_numeric($_REQUEST['par'])) {
			$cid = intval($_REQUEST['cid']);
			$par = intval($_REQUEST['par']);

			//breadcrumb
			$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;cid='.$cid);

			//lekerdezzuk, hogy tenyleg letezik-e a kategoria
			$query = "
				SELECT c.category_name AS cname, c.category_desc AS cdesc, c.timer_start AS timer_start, c.timer_end AS timer_end, 
					c.picture AS picture, c.lang AS lang 
				FROM iShark_Shop_Category c 
				WHERE c.category_id = $cid
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				//form-hoz elemek hozzaadasa - csak hozzaadasnal
				$form_shop->addElement('hidden', 'sub_act', $sub_act);
				$form_shop->addElement('hidden', 'cid',     $cid);
				$form_shop->addElement('hidden', 'par',     $par);

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
					//beallitjuk az alapertelmezett ertekeket, csak modositasnal
					$form_shop->setDefaults(array(
						'languages'   => $row['lang'],
						'name'        => $row['cname'],
						'desc'        => $row['cdesc'],
						'timer_start' => $timer_start,
						'timer_end'   => $timer_end
						)
					);

					$tpl->assign('picture',  $_SESSION['site_shop_mainpicdir'].'/'.$row['picture']);
					$tpl->assign('filename', $row['picture']);
					$filename = $row['picture'];

					//lekerdezzuk a mar rogzitett csoportokat vagy termekeket
					if (!empty($_SESSION['site_shop_groupuse'])) {
						$query = "
							SELECT group_id 
							FROM iShark_Shop_Category_Groups 
							WHERE category_id = $cid
						";
					} else {
						$query = "
							SELECT product_id 
							FROM iShark_Shop_Products_Category 
							WHERE category_id = $cid
						";
					}
					$result =& $mdb2->query($query);
					$select->setSelected($result->fetchCol());

					//ellenorzes, vegso muveletek
					$form_shop->addFormRule('check_modcategory');
					if ($form_shop->validate()) {
						$form_shop->applyFilter('__ALL__', array(&$mdb2, 'escape'));

						//ha ki akarjuk torolni a regi kepet
						if (!empty($_SESSION['site_shop_mainpic'])) {
							if ((isset($_POST['delpic']) && isset($_POST['oldpic_name']) && $_POST['oldpic_name'] != "")) {
								$filename = "";
								if (file_exists($_SESSION['site_shop_mainpicdir'].'/'.$_POST['oldpic_name'])) {
									@unlink($_SESSION['site_shop_mainpicdir'].'/'.$_POST['oldpic_name']);
									@unlink($_SESSION['site_shop_mainpicdir'].'/tn_'.$_POST['oldpic_name']);
								}
							}

							//kep feltoltese
							if ($file->isUploadedFile()) {
								$filevalues = $file->getValue();
								$sdir = preg_replace('|/$|','', $_SESSION['site_shop_mainpicdir']).'/';
								$filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
								$tn_name = 'tn_'.$filename;

								//kep atmeretezese
								include_once 'includes/function.images.php';
								if (($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, $_SESSION['site_shop_mainpicwidth'], $_SESSION['site_shop_mainpicheight'])) && 
								($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_shop_mainpicswidth'], $_SESSION['site_shop_mainpicsheight']))) {
									@chmod($sdir.$filename,0664);
									@chmod($sdir.$tn_name,0664);

									@unlink($filevalues['tmp_name']);
									//ha volt regi kep, akkor toroljuk
									if (isset($_POST['oldpic_name']) && $_POST['oldpic_name'] != "") {
										if (file_exists($_SESSION['site_shop_mainpicdir'].'/'.$_POST['oldpic_name'])) {
											@unlink($_SESSION['site_shop_mainpicdir'].'/'.$_POST['oldpic_name']);
											@unlink($_SESSION['site_shop_mainpicdir'].'/tn_'.$_POST['oldpic_name']);
										}
									} //regi kep torlesenek vege
								}
								$form_shop->setElementError('picture', $locale->get('category_error_upload'));
							}
						}

						$name        = $form_shop->getSubmitValue('name');
						$desc        = $form_shop->getSubmitValue('desc');
						$timer_start = $form_shop->getSubmitValue('timer_start');
						$timer_end   = $form_shop->getSubmitValue('timer_end');

						//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
						if (!empty($_SESSION['site_multilang'])) {
							$languages = $form_shop->getSubmitValue('languages');
						} else {
							$languages = $_SESSION['site_deflang'];
						}

						$query = "
							UPDATE iShark_Shop_Category 
							SET category_name = '".$name."', 
								category_desc = '".$desc."', 
								mod_user_id   = '".$_SESSION['user_id']."', 
								mod_date      = NOW(),
								timer_start   = '$timer_start',
								timer_end     = '$timer_end',
								picture       = '".$filename."',
								lang          = '".$languages."'
							WHERE category_id = $cid
						";
						$mdb2->exec($query);

						//ha csoportokat hasznalunk
						if (!empty($_SESSION['site_shop_groupuse'])) {
							//lekerdezzuk, hogy milyen csoportok tartoztak ehhez a kategoriahoz
							$query = "
								SELECT group_id 
								FROM iShark_Shop_Category_Groups 
								WHERE category_id = $cid
							";
							$result =& $mdb2->query($query);
							if ($result->numRows() > 0) {
								while ($row = $result->fetchRow())
								{
									$group_id = $row['group_id'];

									//lekerdezzuk, hogy a regi kapcsolatoknal milyen termekek voltak
									$query2 = "
										SELECT product_id 
										FROM iShark_Shop_Products_Groups 
										WHERE group_id = $group_id
									";
									$result2 = $mdb2->query($query2);
									if ($result2->numRows() > 0) {
										while ($row2 = $result2->fetchRow())
										{
											$prod_id = $row2['product_id'];

											//kitoroljuk a termekeket, amik ehhez a csoporthoz tartoznak
											$query3 = "
												DELETE FROM iShark_Shop_Products_Category 
												WHERE product_id = $prod_id
											";
											$mdb2->exec($query3);
										}
									}
									//toroljuk a regi csoport-kategoria kapcsolatokat
									$query4 = "
										DELETE FROM iShark_Shop_Category_Groups 
										WHERE group_id = $group_id
									";
									$mdb2->exec($query4);
								}
							}
							//hozzaadjuk az uj csoportokhoz tartozo termekeket
							$groups = $form_shop->getSubmitValue('groups');
							if (is_array($groups) && count($groups) > 0) {
								foreach ($groups as $key => $value) {
									//osszekapcsoljuk a csoportokat a kategoriaval
									$query = "
										INSERT INTO iShark_Shop_Category_Groups 
										(category_id, group_id) 
										VALUES 
										($cid, $value)
									";
									$mdb2->exec($query);

									//berakjuk a kategoriakhoz a csoportokban levo termekeket
									$query = "
										SELECT g.product_id AS product_id 
										FROM iShark_Shop_Products_Groups g 
										WHERE g.group_id = $value
									";
									$result =& $mdb2->query($query);
									if ($result->numRows() > 0) {
										while ($row = $result->fetchRow())
										{
											$prod_id = $row['product_id'];
											$query2 = "
												SELECT * 
												FROM iShark_Shop_Products_Category 
												WHERE product_id = $prod_id AND category_id = $cid
											";
											$result2 = $mdb2->query($query2);
											if ($result2->numRows() == 0) {
												$query3 = "
													INSERT INTO iShark_Shop_Products_Category 
													(product_id, category_id) 
													VALUES 
													($prod_id, '$cid')
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
								WHERE category_id = $cid
							";
							$mdb2->exec($query);

							$prods = $form_shop->getSubmitValue('prods');

							if (is_array($prods) && count($prods) > 0) {
								foreach ($prods as $key => $value) {
									$query = "
										INSERT INTO iShark_Shop_Products_Category 
										(product_id, category_id) 
										VALUES 
										($value, $cid)
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
							header('Location: admin.php?p='.$module_name.'&act='.$page);
							exit;
						}
					}
				}
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('category_error_notexists'));
				return;
			}
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('category_error_notexists'));
			return;
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form_shop->accept($renderer);

	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form_shop',   $renderer->toArray());
	$tpl->assign('tiny_fields', 'desc');

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_category';
}

/**
 * ha sorrendet modositunk
 */
if ($sub_act == "ord") {
	if (isset($_GET['par']) && is_numeric($_GET['par']) && isset($_GET['cid']) && is_numeric($_GET['cid'])) {
		$par  = intval($_GET['par']);
		$cid  = intval($_GET['cid']);

		if (isset($_GET['way']) && ($_GET['way'] == "up" || $_GET['way'] == "down")) {
			// Attól függ, hogy lefelé vagy felfelé akarjuk mozgatni
			$gt_lt  = ($_GET['way'] == 'up' ? '<' : '>');
			$order  = ($_GET['way'] == 'up' ? 'DESC' : '');
			$query  = "
				SELECT sortorder 
				FROM iShark_Shop_Category 
				WHERE category_id = $cid
			";
			$result =& $mdb2->query($query);
			while ($regihely = $result->fetchRow()) {
				// Kicserélendõ elem kiválasztása, gt_lt tõl függõen az alatta, vagy felette levõ elem
				$query = "
					SELECT category_id, sortorder 
					FROM iShark_Shop_Category 
					WHERE parent = $par AND sortorder $gt_lt $regihely[sortorder]
					ORDER BY sortorder $order
				";
				$mdb2->setLimit(1);
				$csere = $mdb2->query($query);
				// sorrend adatok cseréje:
				while ($ujhely = $csere->fetchRow()) {
					$query = "
						UPDATE iShark_Shop_Category 
						SET sortorder = $ujhely[sortorder] 
						WHERE category_id = $cid
					";
					$mdb2->exec($query);
					$query = "
						UPDATE iShark_Shop_Category 
						SET sortorder = $regihely[sortorder] 
						WHERE category_id = $ujhely[category_id]
					";
					$mdb2->exec($query);
				}
			}
		}
	}

	//loggolas
	logger($page.'_'.$sub_act);

	//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
	if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
		header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
		exit;
	} else {
		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}
} //sorrend modositas vege

/**
 * ha aktivalunk vagy inaktivalunk egy kategoriat
 */
if ($sub_act == "act") {
	if (isset($_REQUEST['cid']) && is_numeric($_REQUEST['cid'])) {
		include_once $include_dir.'/function.check.php';
		$cid = intval($_REQUEST['cid']);

		check_active('iShark_Shop_Category', 'category_id', $cid);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('category_error_notexists'));
		return;
	}
}

/**
 * ha torlunk egy kategoriat
 */
if ($sub_act == "del") {
	if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
		$cid = intval($_GET['cid']);

		$query = "
			DELETE FROM iShark_Shop_Category 
			WHERE category_id = $cid OR parent = $cid
		";
		$mdb2->exec($query);

		//kitoroljuk a bejegyzest a termek - kategoria kapcsolotablabol is
		$query = "
			DELETE FROM iShark_Shop_Products_Category 
			WHERE category_id = $cid
		";
		$mdb2->exec($query);

		//kitoroljuk a bejegyzest a termek - csoport tablabol is, ha hasznaljuk a csoportokat
		if (!empty($_SESSION['site_shop_groupuse'])) {
			$query = "
				DELETE FROM iShark_Shop_Category_Groups 
				WHERE category_id = $cid
			";
			$mdb2->exec($query);
		}

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=sea&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('category_error_notexists'));
		return;
	}
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	include_once $include_dir.'/function.shop.php';

	if ($_SESSION['site_shop_maincat'] > $groupnums || $_SESSION['site_shop_maincat'] == 0) {
		$add_new = array (
			array(
				'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
				'title' => $locale->get('category_title_add'),
				'pic'   => "add.jpg"
			)
		);
		$tpl->assign('add_new', $add_new);
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('category_list', categories(TRUE, 0, '', 'all', 0));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = "shop_categories_list";
}

?>