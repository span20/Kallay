<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "shop";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod');

$menu_id = 0;
//menu azonosito vizsgalata
if (isset($_GET['mid'])) {
	$menu_id = intval($_GET['mid']);
}

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, $menu_id, 1, 'settings')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('settings_no_permission'));
	return;
}

/**
 *ha modositjuk a beallitasokat
 */
if ($act == "mod") {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_shop', 'post', 'admin.php?p=settings&file='.$_GET['file']);

	$form->setRequiredNote($locale->get('settings_form_required_note'));

	$form->addElement('header', 'settings',  $locale->get('settings_form_header'));
	$form->addElement('hidden', 'act',       $act);

	//rendeles kimeno e-mail cim
	if (!empty($_SESSION['site_shop_userbuy'])) {
	    $form->addElement('text', 'ordermail', $locale->get('settings_field_ordermail'));
	}

	//ha a felhasznalok ertekelhetik a termekeket
	if (!empty($_SESSION['site_shop_is_rating'])) {
		//csak regisztralt felhasznalo ertekelheti-e a termeket
		$regrate = array();
		$regrate[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_yes'), '1');
		$regrate[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('settings_form_no'),  '0');
		$form->addGroup($regrate, 'regrate', $locale->get('settings_field_reguser'));

		//ertekeles minimum, maximum karakterszama
		$ratechar = array();
		$ratechar['min'] =& HTML_QuickForm::createElement('text', 'ratemin', $locale->get('settings_field_ratemin'), array('size' => 5));
		$ratechar['max'] =& HTML_QuickForm::createElement('text', 'ratemax', $locale->get('settings_field_ratemax'), array('size' => 5));
		$form->addGroup($ratechar, 'ratechar', $locale->get('settings_field_ratechar'));
	}

	//ujdonsagok block-ban hany termek latszodik
	$form->addElement('text', 'newprods', $locale->get('settings_field_newsprodsnum'));

	//levelhez az automatikusan hozzarakott targy
	$form->addElement('text', 'mailsubject', $locale->get('settings_field_mailsubject'));

	//szallitasi modok, arak, csak akkor, ha a felhasznalok vasarolhatnak
	if (!empty($_SESSION['site_shop_userbuy'])) {
    	if (!empty($_SESSION['site_shop_shipping_max'])) {
            $max_shipping = $_SESSION['site_shop_shipping_max'];
        } else {
            $max_shipping = 3;
        }
    	for ($i = 1; $i <= $max_shipping; $i++) {
    	    ${'ship'.$i}['text']  =& HTML_QuickForm::createElement('text', 'shiptext1',  null, array('size' => 50));
    	    ${'ship'.$i}['price'] =& HTML_QuickForm::createElement('text', 'shipprice1', null, array('size' => 5));
    	    $form->addGroup(${'ship'.$i}, 'shipping'.$i, $i.'. '.$locale->get('settings_field_shipping'));
    	}
	}

	$form->addElement('submit', 'submit', $locale->get('settings_form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('settings_form_reset'),  'class="reset"');

	//lekerdezzuk a shop config tablat
	$query = "
		SELECT shop_ordermail, shop_is_reguser_rating, shop_rate_minchar, shop_rate_maxchar, shop_newprodsnum, 
			shop_mailsubject
		FROM iShark_Shop_Configs
	";
	$az = 0;
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$form->setDefaults(array(
				'ordermail'   => $row['shop_ordermail'],
				'regrate'     => $row['shop_is_reguser_rating'],
				'ratechar'    => array(
					'ratemin'   => $row['shop_rate_minchar'],
					'ratemax'   => $row['shop_rate_maxchar']
					),
				'newprods'    => $row['shop_newprodsnum'],
				'mailsubject' => $row['shop_mailsubject']
				)
			);
			$az = 1;
		}
	}

	//lekerdezzuk a shop shipping config tablat es beallitjuk alapertelmezettnek
	if (!empty($_SESSION['site_shop_userbuy'])) {
    	$query = "
    		SELECT * 
    		FROM iShark_Shop_Configs_Shipping
    	";
    	$mdb2->setLimit($max_shipping);
    	$result = $mdb2->query($query);
    	$shipping = array();
    	while ($row = $result->fetchRow())
    	{
    		$shipping['shipping'.$row['shipping_id']]['shiptext'.$row['shipping_id']]  = $row['shipping_text'];
    		$shipping['shipping'.$row['shipping_id']]['shipprice'.$row['shipping_id']] = $row['shipping_price'];
    	}
    	$form->setDefaults($shipping);
	}

	$form->applyFilter('__ALL__', 'trim');

	if (!empty($_SESSION['site_shop_userbuy'])) {
	    $form->addGroupRule('shipping1', $locale->get('settings_error_shipping'),  'required');
	    $form->addRule(     'ordermail', $locale->get('settings_error_mail1'),     'required');
	    $form->addRule(     'ordermail', $locale->get('settings_error_mail2'),     'email');
	}
	if (!empty($_SESSION['site_shop_is_rating'])) {
		$form->addRule('regrate', $locale->get('settings_error_regrate'), 'required');
		$form->addGroupRule('ratechar', array(
			'ratemin' => array(
				array($locale->get('settings_error_ratemin1'), 'required'),
				array($locale->get('settings_error_ratemin2'), 'numeric')
			),
			'ratemax' => array(
				array($locale->get('settings_error_ratemax1'), 'required'),
				array($locale->get('settings_error_ratemax2'), 'numeric')
			)
		));
		if ($form->isSubmitted()) {
			$rates = $form->getSubmitValue('ratechar');
			if ($rates['ratemin'] > $rates['ratemax']) {
				$form->setElementError('ratechar', $locale->get('settings_error_ratechar'));
			}
		}
	}
	$form->addRule('newprods', $locale->get('settings_error_newprods1'), 'required');
	$form->addRule('newprods', $locale->get('settings_error_newprods2'), 'numeric');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$ordermail   = $form->getSubmitValue('ordermail');
		$newprods    = intval($form->getSubmitValue('newprods'));
		$mailsubject = $form->getSubmitValue('mailsubject');
		if (!empty($_SESSION['site_shop_is_rating'])) {
			$regrate = intval($form->getSubmitValue('regrate'));
			$rates   = $form->getSubmitValue('ratechar');
			$ratemin = intval($rates['ratemin']);
			$ratemax = intval($rates['ratemax']);
		} else {
			$regrate = 1;
			$ratemin = 0;
			$ratemax = 0;
		}

		if ($az == 1) {
			$query = "
				UPDATE iShark_Shop_Configs 
				SET shop_ordermail         = '$ordermail',
					shop_is_reguser_rating = $regrate,
					shop_rate_minchar      = $ratemin,
					shop_rate_maxchar      = $ratemax,
					shop_newprodsnum       = $newprods,
					shop_mailsubject       = '$mailsubject'
			";
		} else {
			$query = "
				INSERT INTO iShark_Shop_Configs 
				(shop_ordermail, shop_is_reguser_rating, shop_rate_minchar, shop_rate_maxchar, shop_newprodsnum, shop_mailsubject) 
				VALUES 
				('$ordermail', $regrate, $ratemin, $ratemax, $newprods, '$mailsubject')
			";
		}
		$mdb2->exec($query);

		if (!empty($_SESSION['site_shop_userbuy'])) {
    		for ($i = 1; $i <= $max_shipping; $i++) {
    			$shipping  = $form->getSubmitValue('shipping'.$i);
    			$shipprice = intval($form->getSubmitValue('shipprice'.$i));

    			$query = "
    				SELECT shipping_id, shipping_text, shipping_price 
    				FROM iShark_Shop_Configs_Shipping 
    				WHERE shipping_id = $i
    			";
    			$result = $mdb2->query($query);
    			if (!empty($shipping['shiptext'.$i])) {
    				if ($result->numRows() > 0) {
    					$query2 = "
    						UPDATE iShark_Shop_Configs_Shipping 
    						SET shipping_text  = '".$shipping['shiptext'.$i]."', 
    							shipping_price = ".$shipping['shipprice'.$i]."
    						WHERE shipping_id = $i
    					";
    				} else {
    					$query2 = "
    						INSERT INTO iShark_Shop_Configs_Shipping 
    						(shipping_text, shipping_price) 
    						VALUES 
    						('".$shipping['shiptext'.$i]."', ".$shipping['shipprice'.$i].")
    					";
    				}
    				$mdb2->exec($query2);
    			}
    		}
		}

		//loggolas
		logger($act, $menu_id);

		$form->freeze();

		header('Location: admin.php?p=settings');
		exit;
	}

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_form', ob_get_contents());
	ob_end_clean();

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('lang_title', $locale->get('settings_title'));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

?>