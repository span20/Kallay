<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
	die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

/**
 * hozzaadas, modositas
 */
if ($sub_act == "add" || $sub_act == "mod") {
    $titles = array('add' => $locale->get('actions_title_add'), 'mod' => $locale->get('actions_title_mod'));

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';
	require_once $include_dir.'/function.shop.php';

	$javascripts[] = "javascripts";
	$javascripts[] = "javascript.shop";

	$form =& new HTML_QuickForm('frm_action', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('actions_form_required_note'));

	$form->addElement('header', 'actions', $locale->get('actions_form_header'));
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);

	//akcio neve
	$form->addElement('text', 'name', $locale->get('actions_field_name'));

	//idozito
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('readonly' => 'readonly', 'id' => 'timer_start')),
	        HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('actions_field_deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('actions_field_timerstart'), null, false
	);
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('readonly' => 'readonly', 'id' => 'timer_end')),
	        HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('actions_field_deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('actions_field_timerend'), null, false
	);

	//akciohoz kapcsolhato termekek listaja - ha modositas, akkor mas
	if ($sub_act == "mod" && isset($_REQUEST['aid']) && is_numeric($_REQUEST['aid'])) {
		$aid = intval($_REQUEST['aid']);

		//lekerdezzuk az akcioban szereplo termekeket
		$query2 = "
			SELECT CONCAT(ap.product_id, '_', p.product_name) AS apid, ap.percent AS percent, ROUND(ap.price) AS price 
			FROM iShark_Shop_Products p, iShark_Shop_Actions_Products ap 
			WHERE ap.action_id = $aid AND p.product_id = ap.product_id AND p.is_deleted = 0 AND p.is_active = 1
		";
		$result2 =& $mdb2->query($query2);
		$prods_array = array();
		$price = "";
		if ($result2->numRows() > 0) {
			while($row2 = $result2->fetchRow())
			{
				$prods_array[$row2['apid']] = $row2['apid'];
				if ($row2['percent'] != 0) {
					$actionradio = 0;
				} else {
					$actionradio = 1;
				}
				$percent = $row2['percent'];
				$price .= $row2['apid'].",".$row2['price'].",";
			}
			//automatikusan futtatjuk a scriptet
			$bodyonload = "fixpercent('$price')";
		}

		$query = "
			SELECT p.product_id AS pid, p.product_name AS pname 
			FROM iShark_Shop_Products p 
			LEFT JOIN iShark_Shop_Actions_Products ap ON ap.product_id = p.product_id 
			WHERE (ap.product_id IS NULL OR ap.action_id = $aid) AND p.is_active = 1 AND p.is_deleted = 0 
		";
	} else {
		$query = "
			SELECT p.product_id AS pid, p.product_name AS pname 
			FROM iShark_Shop_Products p 
			LEFT JOIN iShark_Shop_Actions_Products ap ON ap.product_id = p.product_id 
			WHERE ap.product_id IS NULL AND p.is_active = 1 AND p.is_deleted = 0 
		";
	}
	$result =& $mdb2->query($query);
	$products = array();
	while ($row = $result->fetchRow())
	{
		$products[$row['pid']."_".$row['pname']] = $row['pname'];
	}
	if ($sub_act == "mod") {
		$select =& $form->addElement('select', 'products', $locale->get('actions_field_products'), $products, array('id' => 'products', 'onclick' => 'fixpercent(\''.$price.'\');'));
	} else {
		$select =& $form->addElement('select', 'products', $locale->get('actions_field_products'), $products, array('id' => 'products', 'onclick' => 'fixpercent(\'null\');'));
	}
	$select->setSize(5);
	$select->setMultiple(true);
	if ($sub_act == "mod") {
		$select->setSelected($prods_array);
		$form->setDefaults(array(
			'actionradio' => $actionradio,
			'percent'     => $percent
			)
		);
	}

	//szazalekos vagy egyedi ar
	$action = array();
	$action[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('actions_field_yes'), '1', array('onclick' => 'fixpercent(\'null\');'));
	$action[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('actions_field_no'),  '0', array('onclick' => 'fixpercent(\'null\');'));
	$form->addGroup($action, 'actionradio', $locale->get('actions_field_unique_price'));

	//szazalekos ar
	$form->addElement('text', 'percent', $locale->get('actions_field_percent'), array('size' => '5'));

	//fix ar
	$form->addElement('static', 'fix', $locale->get('actions_field_fix'));

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	$form->addRule(     'name',     $locale->get('actions_error_name'),     'required');
	$form->addGroupRule('products', $locale->get('actions_error_products'), 'required');
	//ha %-ban akarjuk megadni a kedvezmenyeket
	if ($form->isSubmitted() && $form->getSubmitValue('actionradio') == 0) {
		$form->addRule('percent', $locale->get('actions_error_percent1'), 'required');
		$form->addRule('percent', $locale->get('actions_error_percent2'), 'numeric');
	}
	//ha fix arat akarunk megadni a kedvezmenynel - a price valtozo nem quickform-os, ezert van kulon kezelve
	if ($form->isSubmitted() && $form->getSubmitValue('actionradio') == 1 && isset($_POST['price']) && is_array($_POST['price'])) {
		$price_error    = 0;
		$pricenum_error = 0;
		foreach ($_POST['price'] as $key => $value) {
			if (empty($value)) {
				$price_error = 1;
			}
			elseif (!is_numeric($value)) {
				$pricenum_error = 1;
			}
			else {
				$_SESSION['price'][$key] = $value;
			}
		}
		if ($price_error == 1) {
			$form->setElementError('fix', $locale->get('actions_error_fix1'));
		}
		if ($pricenum_error == 1) {
			$form->setElementError('fix', $locale->get('actions_error_fix2'));
		}
	}

	//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
	if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
		$form->addFormRule('check_timer');
	}

	/**
	 * Ha uj akciot adunk hozza
	 */
	if ($sub_act == "add") {
		//automatikusan futtatjuk a scriptet
		$bodyonload = "fixpercent('null')";

		//hozzaadasnal alapertelmezett ertek
		$form->setDefaults(array(
			'actionradio' => 1
			)
		);

		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add');

		$form->addFormRule('check_addaction');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name        = $form->getSubmitValue('name');
			$timer_start = $form->getSubmitValue('timer_start');
			$timer_end   = $form->getSubmitValue('timer_end');
			$products    = $form->getSubmitValue('products');
			$actionradio = intval($form->getSubmitValue('actionradio'));
			$percent     = intval($form->getSubmitValue('percent'));
			$price       = $form->getSubmitValue('price');

			//beszurjuk az uj akciot
			$action_id = $mdb2->extended->getBeforeID('iShark_Shop_Actions', 'action_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Shop_Actions 
				(action_id, action_name, add_user_id, add_date, mod_user_id, mod_date, timer_start, timer_end, is_active) 
				VALUES 
				($action_id, '".$name."', '".$_SESSION['user_id']."', NOW(), '".$_SESSION['user_id']."', NOW(), '".$timer_start."', '".$timer_end."', 1)
			";
			$mdb2->exec($query);
			$last_action_id = $mdb2->extended->getAfterID($action_id, 'iShark_Shop_Actions', 'action_id');

			//beszurjuk az akciohoz tartozo termekeket
			if (is_array($products) && count($products) > 0) {
				//ha szazalekot adunk meg
				if ($actionradio == 0) {
					foreach($products as $key => $value) {
						$prods = explode("_", $value);
						$query2 = "
							INSERT INTO iShark_Shop_Actions_Products 
							(action_id, product_id, percent) 
							VALUES 
							($last_action_id, ".$prods[0].", $percent)
						";
						$mdb2->exec($query2);
					}
				}
				//ha fix arat adunk meg
				else {
					foreach($price as $key => $value) {
						$prods = explode("_", $key);
						$query2 = "
							INSERT INTO iShark_Shop_Actions_Products 
							(action_id, product_id, price) 
							VALUES 
							($last_action_id, ".$prods[0].", $value)
						";
						$mdb2->exec($query2);
					}
				}
			}

			//loggolas
			logger($page.'_'.$sub_act);

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page);
			exit;
		}
	} //hozzadas vege

	/**
	 * modositas
	 */
	if ($sub_act == "mod") {
		if (isset($_REQUEST['aid']) && is_numeric($_REQUEST['aid'])) {
			$aid = intval($_REQUEST['aid']);

			$form->addElement('hidden', 'aid', $aid);

			//breadcrumb
			$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;aid='.$aid);

			//lekerdezzuk az akcio tartalmat
			$query = "
				SELECT a.action_name AS aname, a.timer_start AS timer_start, a.timer_end AS timer_end 
				FROM iShark_Shop_Actions a 
				WHERE a.action_id = $aid
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
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

					$form->setDefaults(array(
						'name'        => $row['aname'],
						'timer_start' => $timer_start,
						'timer_end'   => $timer_end
						)
					);
				}

				$form->addFormRule('check_modaction');
				if ($form->validate()) {
					$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

					$name        = $form->getSubmitValue('name');
					$timer_start = $form->getSubmitValue('timer_start');
					$timer_end   = $form->getSubmitValue('timer_end');
					$products    = $form->getSubmitValue('products');
					$actionradio = intval($form->getSubmitValue('actionradio'));
					$percent     = intval($form->getSubmitValue('percent'));
					$price       = $form->getSubmitValue('price');

					$query = "
						UPDATE iShark_Shop_Actions 
						SET action_name = '".$name."', 
							timer_start = '".$timer_start."', 
							timer_end   = '".$timer_end."', 
							mod_user_id = ".$_SESSION['user_id'].", 
							mod_date    = NOW()
						WHERE action_id = $aid
					";
					$mdb2->exec($query);

					//kitoroljuk az akciohoz tartozo termekeket
					$query = "
						DELETE FROM iShark_Shop_Actions_Products 
						WHERE action_id = $aid
					";
					$mdb2->exec($query);

					//beszurjuk az akciohoz tartozo termekeket
					if (is_array($products) && count($products) > 0) {
						//ha szazalekot adunk meg
						if ($actionradio == 0) {
							foreach($products as $key => $value) {
								$prods = explode("_", $value);
								$query2 = "
									INSERT INTO iShark_Shop_Actions_Products 
									(action_id, product_id, percent) 
									VALUES 
									($aid, ".$prods[0].", $percent)
								";
								$mdb2->exec($query2);
							}
						}
						//ha fix arat adunk meg
						else {
							foreach($price as $key => $value) {
								$prods = explode("_", $key);
								$query2 = "
									INSERT INTO iShark_Shop_Actions_Products 
									(action_id, product_id, price) 
									VALUES 
									($aid, ".$prods[0].", $value)
								";
								$mdb2->exec($query2);
							}
						}
					}

					//loggolas
					logger($page.'_'.$sub_act);

					//"fagyasztjuk" a form-ot
					$form->freeze();

					//visszadobjuk a lista oldalra
					header('Location: admin.php?p='.$module_name.'&act='.$page);
					exit;
				}
			} else {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('actions_error_notexists'));
				return;
			}
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('actions_error_notexists'));
			return;
		}
	}

	$form->addElement('submit', 'submit', $locale->get('actions_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('actions_form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page);
	$tpl->assign('form',       $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_form', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_actions';
} //hozzaadas, modositas vege

/**
 * ha aktivaljuk az akciot
 */
if ($sub_act == "act") {
	//ha van aid valtozo es az szam
	if (isset($_GET['aid']) && is_numeric($_GET['aid'])) {
		$aid = intval($_GET['aid']);

		include_once $include_dir.'/function.check.php';

		check_active('iShark_Shop_Actions', 'action_id', $aid);

		//loggolas
		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
		exit;
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('actions_error_notexists'));
		return;
	}
}

/**
 * ha toroljuk az akciot
 */
if ($sub_act == "del") {
	if (isset($_GET['aid']) && is_numeric($_GET['aid'])) {
		$aid = intval($_GET['aid']);

		$query = "
			DELETE FROM iShark_Shop_Actions_Products 
			WHERE action_id = $aid
		";
		$mdb2->exec($query);

		$query = "
			DELETE FROM iShark_Shop_Actions 
			WHERE action_id = $aid
		";
		$mdb2->exec($query);

		//loggolas
		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&pageID='.$page_id);
		exit;
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('actions_error_notexists'));
		return;
	}
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	$query = "
		SELECT a.action_id AS aid, a.action_name AS aname, a.timer_start AS astart, a.timer_end AS aend, 
			a.is_active AS isact, a.mod_date AS mdate, u.name AS musr 
		FROM iShark_Shop_Actions a 
		LEFT JOIN iShark_Users u ON u.user_id = a.mod_user_id
		ORDER BY a.action_id DESC
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
			'title' => $locale->get('actions_field_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data',    $paged_data['data']);
	$tpl->assign('page_list',    $paged_data['links']);
	$tpl->assign('add_new',      $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = "shop_actions_list";
}

?>