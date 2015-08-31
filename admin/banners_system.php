<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "banners";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod');

//ezek az elfogadhato almuveleti hivasok ($_REQUEST['type'])
$is_type = array('lst', 'add', 'mod', 'del');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, NULL, 1, 'system')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], $is_type)) {
	$type = $_REQUEST['type'];

	if ($type == "add" || $type == "mod") {
		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		include_once $include_dir.'/function.banners.php';

		$form =& new HTML_QuickForm('frm_banners', 'post', 'admin.php?p=banners_system');
		$form->removeAttribute('name');

		$form->setRequiredNote($locale->get('form_system_required_note'));

		$form->addElement('header', 'banners',   $locale->get('form_system_header'));
		$form->addElement('text',   'placename', $locale->get('form_system_name'));
		$form->addElement('text',   'maxwidth',  $locale->get('form_system_maxwidth'));
		$form->addElement('text',   'maxheight', $locale->get('form_system_maxheight'));

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('placename', $locale->get('error_system_name'),    'required');
		$form->addRule('maxwidth',  $locale->get('error_system_width1'),  'required');
		$form->addRule('maxwidth',  $locale->get('error_system_width2'),  'numeric');
		$form->addRule('maxwidth',  $locale->get('error_system_width3'),  'nonzero');
		$form->addRule('maxheight', $locale->get('error_system_height1'), 'required');
		$form->addRule('maxheight', $locale->get('error_system_height2'), 'numeric');
		$form->addRule('maxheight', $locale->get('error_system_height3'), 'nonzero');

		if ($type == "add") {
			$lang_title = $locale->get('title_system_add');

			//form-hoz elemek hozzaadasa - csak hozzaadasnal
			$form->addElement('hidden', 'act',  'mod');
			$form->addElement('hidden', 'type', $type);

			$form->addFormRule('check_bannerplace_add');
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$placename = $form->getSubmitValue('placename');
				$maxwidth  = intval($form->getSubmitValue('maxwidth'));
				$maxheight = intval($form->getSubmitValue('maxheight'));

				$place_id = $mdb2->extended->getBeforeID('iShark_Banners_Places', 'place_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Banners_Places 
					(place_id, place_name, max_width, max_height, add_user_id, add_date, mod_user_id, mod_date) 
					VALUES 
					('$place_id', '".$placename."', '$maxwidth', '$maxheight', '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', NOW())
				";
				$mdb2->exec($query);

				//loggolas
				logger($act);

				//"fagyasztjuk" a form-ot
				$form->freeze();

				//visszadobjuk a lista oldalra
				header('Location: admin.php?p=banners_system');
				exit;
			}
		}

		if ($type == "mod") {
			$lang_title = $locale->get('title_system_mod');

			if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid'])) {
				$pid = intval($_REQUEST['pid']);

				//lekerdezzuk az adatokat
				$query = "
					SELECT place_name, max_width, max_height 
					FROM iShark_Banners_Places 
					WHERE place_id = $pid
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
					//form-hoz elemek hozzaadasa - csak modositasnal
					$form->addElement('hidden', 'act',  'mod');
					$form->addElement('hidden', 'type', 'mod');
					$form->addElement('hidden', 'pid',  $pid);

					$row = $result->fetchRow();

					$form->setDefaults(array(
						'placename' => $row['place_name'],
						'maxwidth'  => $row['max_width'],
						'maxheight' => $row['max_height']
						)
					);

					$form->addFormRule('check_bannerplace_mod');
					if ($form->validate()) {
						$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

						$placename = $form->getSubmitValue('placename');
						$maxwidth  = intval($form->getSubmitValue('maxwidth'));
						$maxheight = intval($form->getSubmitValue('maxheight'));

						$query = "
							UPDATE iShark_Banners_Places 
							SET place_name = '".$placename."', 
								max_width  = '$maxwidth', 
								max_height = '$maxheight' 
							WHERE place_id = $pid
						";
						$mdb2->exec($query);

						//loggolas
						logger($act, '', '');

						//"fagyasztjuk" a form-ot
						$form->freeze();

						//visszadobjuk a lista oldalra
						header('Location: admin.php?p=banners_system');
						exit;
					}
				} else {
					$acttpl = 'error';
					$tpl->assign('errormsg', $locale->get('error_no_place'));
					return;
				}
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('error_no_place'));
				return;
			}
		}

		$form->addElement('submit', 'submit', $locale->get('form_system_submit'), array('class' => 'submit'));
		$form->addElement('reset',  'reset',  $locale->get('form_system_reset'),  array('class' => 'reset'));

		$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
		$form->accept($renderer);

		$tpl->assign('form',  $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('dynamic_form', ob_get_contents());
		$tpl->assign('lang_title',   $lang_title);
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = "dynamic_form";
	}

	/**
	 * ha torlunk egy mezot
	 */
	if ($type == "del") {
		if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid'])) {
			$pid = intval($_REQUEST['pid']);

			//lekerdezzuk az adatokat
			$query = "
				SELECT place_name, max_width, max_height 
				FROM iShark_Banners_Places 
				WHERE place_id = $pid
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				//kitoroljuk a bannerhelyet
				$query = "
					DELETE FROM iShark_Banners_Places 
					WHERE place_id = $pid
				";
				$mdb2->exec($query);

				//kitoroljuk azokat a bannerkapcsolatokat, amik ezen a bannerhelyen voltak
				$query = "
					DELETE FROM iShark_Banners_Menus_Places 
					WHERE place_id = $pid
				";
				$mdb2->exec($query);

				//kitoroljuk a fooldal osszerakobol is
				$query = "
					DELETE FROM iShark_Builder_BoxContents 
					WHERE banner_pos = $pid
				";
				$mdb2->exec($query);
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('error_no_place'));
				return;
			}
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('error_no_place'));
			return;
		}
	} //torles vege
}

/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
	$query = "
		SELECT bp.place_id AS pid, bp.place_name AS pname, bp.max_width AS pwidth, bp.max_height AS pheight, 
			u1.name AS aname, bp.add_date AS adate, u2. name AS mname, bp.mod_date AS mdate
		FROM iShark_Banners_Places bp 
		LEFT JOIN iShark_Users u1 ON u1.user_id = bp.add_user_id 
		LEFT JOIN iShark_Users u2 ON u2.user_id = bp.add_user_id 
		ORDER BY bp.place_name
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array(
		array(
			'link'  => "admin.php?p=banners_system&amp;act=mod&amp;type=add",
			'title' => $locale->get('field_list_system_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('add_new',   $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'banners_system_list';
}

?>
