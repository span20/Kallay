<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

/**
 * ha modositjuk az aprohirdetest
 */
if ($sub_act == "mod") {
	if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
		$aid = intval($_REQUEST['id']);

		$titles = array('mod' => $locale->get('adverts_field_modify'));

		$javascripts[] = "javascripts";

		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/jscalendar.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		require_once $include_dir.'/function.check.php';
		require_once $include_dir.'/function.classifieds.php';

		$form =& new HTML_QuickForm('frm_class', 'post', 'admin.php?p='.$module_name);
		$form->removeAttribute('name');

		$form->setRequiredNote($locale->get('adverts_form_required_note'));

		$form->addElement('header', $locale->get('adverts_form_header'));
		$form->addElement('hidden', 'act',     $page);
		$form->addElement('hidden', 'sub_act', $sub_act);
		$form->addElement('hidden', 'id',      $aid);

		//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
		if (!empty($_SESSION['site_multilang'])) {
			include_once $include_dir.'/functions.php';
			$form->addElement('select', 'languages', $locale->get('adverts_field_main_languages'), $locale->getLocales());
		}

		//ha hasznaljuk a tipusokat kulon (eladas, vetel, csere)
		if (!empty($_SESSION['site_class_autocategory'])) {
			$autocat = array();
			$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('adverts_field_sell'), '0');
			$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('adverts_field_buy'),  '1');
			$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('adverts_field_swap'), '2');
			$form->addGroup($autocat, 'class_autocat', $locale->get('adverts_field_main_autocat'));

			//ha hozzaadas, akkor beallitjuk az alapertelmezettet
			if ($act == "add") {
				$form->setDefaults(array(
					'class_autocat' => 0
					)
				);
			}
		}

		//kategoriak listaja
		$category = array();
		$cats = explode(";", get_classifieds_category());
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
		$form->addElement('select', 'class_category', $locale->get('adverts_field_main_category'), $category);

		//korzetek listaja
		$query = "
			SELECT county_id, name 
			FROM iShark_Classifieds_Counties 
			ORDER BY name
		";
		$result =& $mdb2->query($query);
		$select =& $form->addElement('select', 'class_section', $locale->get('adverts_field_main_section'), $result->fetchAll('', $rekey = true));
		$select->setSize(5);
		$select->setMultiple(true);

		//felado neve
		$form->addElement('text', 'class_name', $locale->get('adverts_field_main_name'));

		//felado telefonszama
		$form->addElement('text', 'class_phone', $locale->get('adverts_field_main_phone'));

		//felado e-mail cime
		$form->addElement('text', 'class_mail', $locale->get('adverts_field_main_mail'));

		//ar
		$form->addElement('text', 'class_price', $locale->get('adverts_field_main_price'));

		//idozito
		$form->addGroup(
			array(
				HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
				HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
				HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('adverts_deltimer'), 'onclick="deltimer(\'timer_end\')"')
			),
			'date_end', $locale->get('adverts_field_main_timerend'), null, false
		);

		//hirdetes szovege
		$form->addElement('textarea', 'class_desc', $locale->get('adverts_field_main_description'));

		//kepek a hirdeteshez
		if (!empty($_SESSION['site_class_is_advpic'])) {
			for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
				${'file'.$p} =& $form->addElement('file', 'picture'.$p, $locale->get('adverts_field_main_picture'));
			}

			//lekerdezzuk a jelenlegi kep(ek)et
			$query = "
				SELECT picture 
				FROM iShark_Classifieds_Advert_Pictures 
				WHERE advert_id = $aid 
			";
			$mdb2->setLimit($_SESSION['site_class_advpicnum']);
			$result =& $mdb2->query($query);
			//megnezzuk jelenleg hany kep van feltoltve a termekhez
			$pic_num = $result->numRows();
			if ($pic_num > 0) {
				$q = 1;
				while ($row = $result->fetchRow())
				{
					${'oldpic'.$q} = $row['picture'];

					$form->addElement('static', 'pic'.$q, $locale->get('adverts_field_main_currentpic'), '<img src="'.$_SESSION['site_class_advpicdir'].'/'.${'oldpic'.$q}.'" alt="'.${'oldpic'.$q}.'" />' );
					${'delpic'.$q} =& $form->addElement('checkbox', 'delpic'.$q, '', $locale->get('adverts_field_main_delpic'));
					$q++;
				}
			}
		}

		//form gombok
		$form->addElement('submit', 'submit', $locale->get('adverts_form_submit'), 'class="submit"');
		$form->addElement('reset',  'reset',  $locale->get('adverts_form_reset'),  'class="reset"');

		//szurok beallitasa
		$form->applyFilter('__ALL__', 'trim');

		if (!empty($_SESSION['site_class_autocategory'])) {
			$form->addRule('class_autocat', $locale->get('adverts_error_main_autocat'), 'required');
		}
		$form->addRule(     'class_category', $locale->get('adverts_error_main_category'), 'required');
		$form->addGroupRule('class_section',  $locale->get('adverts_error_main_section'),  'required');
		$form->addRule(     'class_name',     $locale->get('adverts_error_main_name'),     'required');
		$form->addRule(     'class_phone',    $locale->get('adverts_error_main_phone'),    'required');
		$form->addRule(     'class_mail',     $locale->get('adverts_error_main_email'),    'required');
		$form->addGroupRule('date_end', array(
			'timer_end' => array(
				array($locale->get('adverts_error_main_timerend'), 'required')
				)
			)
		);
		if (isset($_POST['timer_end']) && $_POST['timer_end'] < date("Y-m-d H:i:s")) {
			$form->setElementError('date_end', $locale->get('adverts_error_main_timerend2'));
		}
		$form->addRule('class_desc',  $locale->get('adverts_error_main_desc'),   'required');
		$form->addRule('class_price', $locale->get('adverts_error_main_price'),  'required');
		$form->addRule('class_price', $locale->get('adverts_error_main_price2'), 'numeric');

		//beallitjuk az alapertelmezett ertekeket
		$query = "
			SELECT a.category_id AS category_id, a.name AS name, a.phone AS phone, a.email AS email, 
				a.description AS description, a.timer_end AS timer_end, a.lang AS lang, a.type AS type, 
				a.price AS price
			FROM iShark_Classifieds_Advert a 
			WHERE a.advert_id = ".$aid."
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();

			//lekerdezzuk a teruleteket is
			$query2 = "
				SELECT c.county_id AS county_id
				FROM iShark_Classifieds_Counties c, iShark_Classifieds_Advert_Counties ac 
				WHERE ac.advert_id = ".$aid." AND ac.county_id = c.county_id
			";
			$result2 =& $mdb2->query($query2);
			$counties = array();
			if ($result2->numRows() > 0) {
				while ($row2 = $result2->fetchRow())
				{
					$counties[$row2['county_id']] = $row2['county_id'];
				}
			}

			$defaults = array();

			//ha tobbnyelvu az oldal, akkor kirakunk egy select mezot, ahol beallithatja a nyelvet
			if (!empty($_SESSION['site_multilang'])) {
				$defaults['languages'] = $row['lang'];
			}
			//ha hasznaljuk a tipusokat kulon (eladas, vetel, csere)
			if (!empty($_SESSION['site_class_autocategory'])) {
				$defaults['class_autocat'] = $row['type'];
			}
			$defaults['class_category'] = $row['category_id'];
			$defaults['class_name']     = $row['name'];
			$defaults['class_phone']    = $row['phone'];
			$defaults['class_mail']     = $row['email'];
			$defaults['class_desc']     = $row['description'];
			$defaults['timer_end']      = $row['timer_end'];
			$defaults['class_section']  = $counties;
			$defaults['class_price']    = $row['price'];

			$form->setDefaults($defaults);
		}

		//ha kepet is lehet feltolteni
		if (!empty($_SESSION['site_class_is_advpic'])) {
			//megszamoljuk hany kepet akarnak most feltolteni
			$newpic_num = 0;
			for ($s = 1; $s <= $_SESSION['site_class_advpicnum']; $s++) {
				if (${'file'.$s}->isUploadedFile()) {
					$newpic_num++;
				}
			}
			//megszamoljuk hany kepet akarnak kitorolni
			$delpic_num = 0;
			for ($q = 1; $q <= $_SESSION['site_class_advpicnum']; $q++) {
				if (isset(${'delpic'.$q}) && ${'delpic'.$q}->getChecked()) {
					$delpic_num++;
				}
			}
			//ha a regi kepek szama + az uj kepek szama - torlesre jelolet kepek szama 
			//meghaladja a feltoltheto kepek szamat, akkor hibauzenet
			if ($pic_num+$newpic_num-$delpic_num > $_SESSION['site_class_advpicnum']) {
				$form->setElementError('picture1', $locale->getBySmarty('adverts_error_main_maxpicupload'));
			}
		}

		//form validalas
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			//ha tolthet fel kepet
			if (!empty($_SESSION['site_class_is_advpic'])) {
				//ha ki akarjuk torolni a regi kepet - de semmi mast nem csinalunk
				for ($q = 1; $q <= $_SESSION['site_class_advpicnum']; $q++) {
					if (isset(${'delpic'.$q}) && ${'delpic'.$q}->getChecked()) {
						if (file_exists($_SESSION['site_class_advpicdir'].'/'.${'oldpic'.$q})) {
							@unlink($_SESSION['site_class_advpicdir'].'/'.${'oldpic'.$q});
							@unlink($_SESSION['site_class_advpicdir'].'/tn_'.${'oldpic'.$q});
						}
						//kitoroljuk a tablabol a torlendo bejegyzeseket
						$query = "
							DELETE FROM iShark_Classifieds_Advert_Pictures 
							WHERE advert_id = $aid AND picture = '".${'oldpic'.$q}."'
						";
						$mdb2->exec($query);
					}
				}

				//kep(ek) feltoltese
				for ($p = 1; $p <= $_SESSION['site_class_advpicnum']; $p++) {
					if (${'file'.$p}->isUploadedFile()) {
						$filevalues = ${'file'.$p}->getValue();
						$sdir = preg_replace('|/$|','', $_SESSION['site_class_advpicdir']).'/';
						${'filename'.$p} = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
						$tn_name = 'tn_'.${'filename'.$p};

						//kep atmeretezese
						include_once 'includes/function.images.php';
						if (($pic = img_resize($filevalues['tmp_name'], $sdir.${'filename'.$p}, $_SESSION['site_class_advpicwidth'], $_SESSION['site_class_advpicheight'])) && ($tn = img_resize($filevalues['tmp_name'], $sdir.$tn_name, $_SESSION['site_class_advpictwidth'], $_SESSION['site_class_advpictheight']))) {
							@chmod($sdir.${'filename'.$p},0664);
							@chmod($sdir.$tn_name,0664);

							@unlink($filevalues['tmp_name']);
							//beszurjuk az uj file-okat a tablaba
							$query = "
								INSERT INTO iShark_Classifieds_Advert_Pictures 
								(advert_id, picture) 
								VALUES 
								($aid, '".${'filename'.$p}."')
							";
							$mdb2->exec($query);
						} else {
							$form->setElementError('picture'.$p, $locale->get('adverts_error_main_picupload'));
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

			if (!empty($_SESSION['site_class_autocategory'])) {
				$autocat = intval($form->getSubmitValue('class_autocat'));
			} else {
				$autocat = 9999;
			}
			$category  = intval($form->getSubmitValue('class_category'));
			$name      = $form->getSubmitValue('class_name');
			$phone     = $form->getSubmitValue('class_phone');
			$usermail  = $form->getSubmitValue('class_mail');
			$timer_end = $form->getSubmitValue('timer_end');
			$desc      = strip_tags($form->getSubmitValue('class_desc'));
			$price     = intval($form->getSubmitValue('class_price'));

			$query = "
				UPDATE iShark_Classifieds_Advert
				SET name        = '".$name."',
					phone       = '".$phone."',
					email       = '".$usermail."',
					description = '".$desc."',
					category_id = $category,
					type        = $autocat,
					mod_user_id = ".$_SESSION['user_id'].",
					mod_date    = NOW(),
					timer_end   = '".$timer_end."',
					price       = '".$price."'
				WHERE advert_id = $aid
			";
			$mdb2->exec($query);

			//korzetek felvitele
			$query = "
				DELETE FROM iShark_Classifieds_Advert_Counties 
				WHERE advert_id = $aid
			";
			$mdb2->exec($query);
			$section = $form->getSubmitValue('class_section');
			if (is_array($section) && count($section) > 0) {
				foreach ($section as $key => $value) {
					$query = "
						INSERT INTO iShark_Classifieds_Advert_Counties 
						(advert_id, county_id) 
						VALUES 
						($aid, $value)
					";
					$mdb2->exec($query);
				}
			}

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form->freeze();

			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('adverts_error_no_activate'));
		return;
	}

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name);

	$tpl->assign('tiny_fields', 'class_desc');
	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form',        $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha aktivalunk vagy inaktivalunk egy aprohirdetest
 */
if ($sub_act == "act") {
	if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
		include_once $include_dir.'/function.check.php';
		$aid = intval($_REQUEST['id']);

		check_active('iShark_Classifieds_Advert', 'advert_id', $aid);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=search&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('adverts_error_advert_noexists'));
		return;
	}
}

/**
 * ha torlunk egy kategoriat
 */
if ($sub_act == "del") {
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$aid = intval($_GET['id']);

		$query = "
			DELETE FROM iShark_Classifieds_Advert 
			WHERE advert_id = $aid
		";
		$mdb2->exec($query);

		//kitoroljuk a bejegyzest az aprohirdetes - terulet kapcsolotablabol is
		$query = "
			DELETE FROM iShark_Classifieds_Advert_Counties 
			WHERE advert_id = $aid
		";
		$mdb2->exec($query);

		//kapcsolodo kepek kitorlese
		$query = "
			SELECT picture 
			FROM iShark_Classifieds_Advert_Pictures 
			WHERE advert_id = $aid
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			@unlink($_SESSION['site_class_advpicdir'].'/'.$row['picture']);
		}
		$query = "
			DELETE FROM iShark_Classifieds_Advert_Pictures 
			WHERE advert_id = $aid
		";
		$mdb2->exec($query);

		//loggolas
		logger($page.'_'.$sub_act);

		//visszadobjuk a lista oldalra - ha keresesbol jon, akkor oda
		if (isset($_REQUEST['s']) && is_numeric($_REQUEST['s']) && $_REQUEST['s'] == 1 && isset($_REQUEST['searchtext']) && isset($_REQUEST['searchtype'])) {
			header('Location: admin.php?p='.$module_name.'&act=search&pageID='.$page_id.'&searchtext='.$_REQUEST['searchtext'].'&searchtype='.$_REQUEST['searchtype']);
			exit;
		} else {
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('adverts_error_advert_noexists'));
		return;
	}
} //torles vege

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	// Tablazat fejlecek dynamic list_hez
	$table_headers = array(
		'__lang__'  => $locale->get('adverts_field_advert_lang'),
		'id'        => $locale->get('adverts_field_advert_id'),
		'name'      => $locale->get('adverts_field_advert_name'),
		'phone'     => $locale->get('adverts_field_advert_phone'),
		'email'     => $locale->get('adverts_field_advert_email'),
		'timer_end' => $locale->get('adverts_field_advert_timerend'),
		'__act__'   => $locale->get('adverts_field_advert_action'),
	);

	// dynamic listhez szukseges nyelvi mezok
	$lang_dynamic = array(
		'strAdminEmpty'   => $locale->get('adverts_warning_advert_empty'),
		'strAdminConfirm' => $locale->get('adverts_confirm_advert_delete')
	);

	// dynamic listhez szukseges mezomuveletek
	$actions_dynamic = array(
		'act' => array($locale->get('adverts_title_advert_activate'), $locale->get('adverts_title_advert_inactivate')),
		'mod' => $locale->get('adverts_title_advert_modify'),
		'del' => $locale->get('adverts_title_advert_delete'),
	);

	$query = "
		SELECT a.advert_id AS id, a.name AS name, a.phone AS phone, a.email AS email, a.is_active AS is_active, a.lang AS lang, 
			a.timer_end AS timer_end
		FROM iShark_Classifieds_Advert a 
		ORDER BY add_date DESC 
	";

	include_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	// Smarty hozzarendelesek
	$tpl->assign('page_data',       $paged_data['data']);
	$tpl->assign('page_list',       $paged_data['links']);
	$tpl->assign('lang_dynamic',    $lang_dynamic);
	$tpl->assign('actions_dynamic', $actions_dynamic);
	$tpl->assign('table_headers',   $table_headers);

	// Dynamic tabla templatejenek kivalasztasa
	$acttpl = 'dynamic_list';
}

?>