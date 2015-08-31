<?php

// Kozvetlenul ezt az allomanyt kerte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Kozvetlenul nem lehet az allomanyhoz hozzaferni...");
}

//modul neve
$module_name = "shop";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//egyedi css betoltese
$css[] = "shop";

//breadcrumb
/*if (!empty($_SESSION['site_shop_is_breadcrumb'])) {
	$shop_breadcrumb->add($locale->get('main_breadcrumb_index'), 'index.php?p=shop');
}*/

//ezek az elfogadhato muveleti hivasok ($act)
$is_act = array('lst', 'bsk', 'ebsk', 'prd', 'ord', 'reg', 'del', 'dwn', 'ajax', 'act', 'addr', 'sea', 'ser', 'delcom');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('main_error_no_permission'));
	return;
}

/**
 * dokumentum letoltese
 */
if ($act == "dwn" && !empty($_SESSION['site_shop_attach']) && isset($_GET['did']) && is_numeric($_GET['did'])) {
	$did = intval($_GET['did']);

	$query = "
		SELECT document_gen, document_real 
		FROM iShark_Shop_Products_Document 
		WHERE document_id = $did
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		return;
	} else {
		while ($row = $result->fetchRow())
		{
			$document_gen  = $row['document_gen'];
			$document_real = $row['document_real'];
		}

		$ddir = $_SESSION['site_shop_attachdir'];
		$mime = 'application/octet-stream';
		header("Content-type: $mime");
		header('Content-Disposition: attachment; filename="'.$document_real.'"');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		readfile($ddir."/".$document_gen);
		exit;
	}
}

/**
 * kosar szerkesztese
 */
if ($act == "bsk") {
	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	//elinditjuk a form-ot
	$form_basket =& new HTML_QuickForm('frm_basket', 'post', 'index.php?p='.$module_name.'&act=bsk');

	//ha frissites utan van torlesre jelolt termek
	if ($form_basket->isSubmitted() && isset($_POST['delete']) && is_array($_POST['delete']) && count($_POST['delete']) > 0) {
		foreach($_POST['delete'] as $key => $value) {
			$key = intval($key);

			$query = "
				DELETE FROM iShark_Shop_Basket 
				WHERE product_id = $key AND
			";
			if (isset($_SESSION['user_id'])) {
				$query .= "
					user_id = ".$_SESSION['user_id']."
				";
			} else {
				$query .= "
					session_id = '".session_id()."'
				";
			}
			$mdb2->exec($query);
		}
	}

	//ha frissites utan van modositasra jelolt termek
	if ($form_basket->isSubmitted() && isset($_POST['amount']) && is_array($_POST['amount']) && count($_POST['amount']) > 0) {
		foreach($_POST['amount'] as $key => $value) {
			if (is_numeric($key) && is_numeric($value)) {
				$key   = intval($key);
				$value = intval($value);

				$query = "
					UPDATE iShark_Shop_Basket 
					SET amount = $value 
					WHERE product_id = $key AND
				";
				if (isset($_SESSION['user_id'])) {
					$query .= "
						user_id = ".$_SESSION['user_id']."
					";
				} else {
					$query .= "
						session_id = '".session_id()."'
					";
				}
				$mdb2->exec($query);
			}
		}
	}

	//a szukseges szoveget jelzo resz beallitasa
	$form_basket->setRequiredNote($locale->get('basket_form_required_note'));

	//form-hoz elemek hozzadasa
	$form_basket->addElement('header', 'basket', $locale->get('basket_form_header'));

	//kosar karbantarto lekerdezes
	//ha van olyan session azonositoval rendelkezo kosar bejegyzes, amely azonosito nem szerepel a session tablaban, akkor azt toroljuk
	//vizsgaljuk az nuser_id-t is, mert ha az nem ures, akkor nem kell regisztralni a vasarlashoz, viszont meg lehet, hogy nem jart le az aktivalasra engedelyezett ido
	//ezt kulon ellenorizzuk
	$query = "
		SELECT b.basket_id AS bid 
		FROM iShark_Shop_Basket b 
		LEFT JOIN iShark_Sessions s ON s.session_id = b.session_id 
		WHERE s.session_id IS NULL AND b.user_id = '' AND b.nuser_id = ''
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$query2 = "
				DELETE FROM iShark_Shop_Basket 
				WHERE basket_id = ".$row['bid']."
			";
			$mdb2->exec($query2);
		}
	}
	//ha kozben toroltre allitottak egy termeket, akkor azt is toroljuk a kosarbol
	$query = "
		SELECT b.basket_id AS bid 
		FROM iShark_Shop_Basket b 
		LEFT JOIN iShark_Shop_Products p ON p.product_id = b.product_id 
		WHERE p.is_deleted = 1
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$query2 = "
				DELETE FROM iShark_Shop_Basket 
				WHERE basket_id = ".$row['bid']."
			";
			$mdb2->exec($query2);
		}
	}
	//ha nem kell regisztracio a vasarlashoz, akkor megnezzuk, hgy van-e nem aktivalt vasarlo. ha van, akkor azokat toroljuk
	if (isset($_SESSION['site_shop_reguserbuy']) && $_SESSION['site_shop_reguserbuy'] == 0) {
	    //TODO - be kell allitani, hogy mennyi ideje van az aktivalasra, ha ez az ido letelt, akkor lehet torolni
	}

	//karbantartas vege

	//termekek listaja
	$query = "
		SELECT b.basket_id AS bid, b.product_id AS pid, b.amount AS amount, ROUND(b.price) AS price, p.product_name AS pname, 
			p.netto AS orig_price, s.state_name AS sname, b.attributes AS attr, a.afa_percent AS afa 
		FROM iShark_Shop_Basket b 
		LEFT JOIN iShark_Shop_Products p ON p.product_id = b.product_id 
		LEFT JOIN iShark_Shop_State s ON s.state_id = p.state_id 
		LEFT JOIN iShark_Shop_Afa a ON a.afa_id = p.afa 
		WHERE p.is_deleted = 0
	";
	if (isset($_SESSION['user_id']) || session_id()) {
		$query .= " AND (";
		if (isset($_SESSION['user_id'])) {
			$query .= "b.user_id = ".$_SESSION['user_id']." ";
			if (session_id()) {
				$query .= " OR ";
			}
		}
		if (session_id()) {
			$query .= "(b.session_id = '".session_id()."' AND b.user_id = '')";
		}
		$query .= ")";
	}
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$basket_list = array();
		$price       = null;
		$afa         = null;
		while($row = $result->fetchRow())
		{
			$basket_list[$row['bid']]['pid']    = $row['pid'];
			$basket_list[$row['bid']]['price']  = $row['price'];
			$basket_list[$row['bid']]['pname']  = $row['pname'];
			$basket_list[$row['bid']]['amount'] = $row['amount'];
			$basket_list[$row['bid']]['sname']  = $row['sname'];
			//ha hasznaljuk az extra attributumokat
			if (!empty($_SESSION['site_shop_is_extra_attr'])) {
				$basket_list[$row['bid']]['attr'] = $row['attr'];
			}
			$price = $price + ($row['amount'] * $row['price']);
			$afa   = $afa + (($row['amount'] * $row['price']) / 100 * $row['afa']);
		}
		$tpl->assign('basket_list', $basket_list);
		$tpl->assign('price',       $price);
		$tpl->assign('afa',         $afa);
		$tpl->assign('sum_price',   $price+$afa);
	}

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form_basket->accept($renderer);

	$tpl->assign('form_basket', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_basket';
} //kosar szerkesztes vege

/**
 * megrendeles
 */
if ($act == "ord") {
	//ha be van jelentkezve
	if (isset($_SESSION['user_id']) || isset($_SESSION['nuser_id'])) {
		//szukseges fuggvenykonyvtarak betoltese
		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		//elinditjuk a form-ot
		$form_ordersend =& new HTML_QuickForm('frm_order', 'post', 'index.php?p='.$module_name.'&act=ord');

		//a szukseges szoveget jelzo resz beallitasa
		$form_ordersend->setRequiredNote($locale->get('orders_form_required_note'));

		//form-hoz elemek hozzadasa
		$form_ordersend->addElement('header', 'orders', $locale->get('orders_form_header'));

		//lekerdezzuk a szallitasi modokat
		$query = "
			SELECT shipping_id, shipping_text, shipping_price 
			FROM iShark_Shop_Configs_Shipping 
			WHERE shipping_text != '' 
			ORDER BY shipping_id
		";
		$result =& $mdb2->query($query);
		$shipping = array();
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow())
			{
				$shipping[] = &HTML_QuickForm::createElement('radio', null, null, $row['shipping_text']." (".$locale->get('orders_field_postal').": ".$row['shipping_price'].")", $row['shipping_id']);
			}
			$form_ordersend->addGroup($shipping,  'shipping', $locale->get('orders_field_paymethod'), '<br />');
		}

		//megjegyzes
		$comment =& $form_ordersend->addElement('textarea', 'comment', $locale->get('orders_field_comment'));
		$comment->setCols(70);
		$comment->setRows(5);

		$form_ordersend->addElement('submit', 'submit', $locale->get('orders_form_send'),  array('class' => 'submit2'));
		$form_ordersend->addElement('button', 'reset',  $locale->get('orders_form_reset'), array('class' => 'reset'));

		$form_ordersend->applyFilter('__ALL__', 'trim');

		if (is_array($shipping) && count($shipping) > 0) {
			$form_ordersend->addGroupRule('shipping', $locale->get('orders_error_paymethod'), 'required');
		}

		if ($form_ordersend->validate()) {
			//levelbe kulon megjegyzes kell, ami nincs escape-elve
			$mail_comment = $form_ordersend->getSubmitValue('comment');

			$form_ordersend->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$shipping = intval($form_ordersend->getSubmitValue('shipping'));
			$comment  = $form_ordersend->getSubmitValue('comment');

			//letrehozzuk az uj rendelest
			$order_id = $mdb2->extended->getBeforeID('iShark_Shop_Orders', 'order_id', TRUE, TRUE);
			if (isset($_SESSION['user_id'])) {
				$query = "
					INSERT INTO iShark_Shop_Orders 
					(order_id, user_id, order_date, comment, shipping) 
					VALUES 
					($order_id, '".$_SESSION['user_id']."', NOW(), '".$comment."', '$shipping')
				";
			}
			if (isset($_SESSION['nuser_id'])) {
				$query = "
					INSERT INTO iShark_Shop_Orders 
					(order_id, nuser_id, order_date, comment, shipping) 
					VALUES 
					($order_id, '".$_SESSION['nuser_id']."', NOW(), '".$comment."', '$shipping')
				";
			}
			$mdb2->exec($query);

			//utolso rendeles azonositoja
			$last_order_id = $mdb2->extended->getAfterID($order_id, 'iShark_Shop_Orders', 'order_id');

			if (isset($_SESSION['user_id'])) {
				//kiszedjuk a shop_users tablabol a szukseges infokat
				$query = "
					SELECT u.user_name AS uname, u.email AS umail, su.phone_mobile AS mphone, 
						a1.city AS scity, a1.zipcode AS szip, a1.address AS saddr, c1.country_name AS scname, 
						a2.city AS pcity, a2.zipcode AS pzip, a2.address AS paddr, c2.country_name AS pcname
					FROM iShark_Users u, iShark_Shop_Users su
					LEFT JOIN iShark_Shop_Address a1 ON a1.address_id = su.ship_address 
					LEFT JOIN iShark_Shop_Address a2 ON a2.address_id = su.post_address 
					LEFT JOIN iShark_Country c1 ON a1.country_id = c1.country_id 
					LEFT JOIN iShark_Country c2 ON a2.country_id = c2.country_id 
					WHERE su.user_id = ".$_SESSION['user_id']." AND u.user_id = su.user_id
				";
			}
			if (isset($_SESSION['nuser_id'])) {
				$query = "
					SELECT su.user_name AS uname, su.email AS umail, su.phone_mobile AS mphone, 
						su.ship_city AS scity, su.ship_zipcode AS szip, su.ship_address AS saddr, c1.country_name AS scname, 
						su.post_city AS pcity, su.post_zipcode AS pzip, su.post_address AS paddr, c2.country_name AS pcname
					FROM iShark_Shop_Users_Notreg su
					LEFT JOIN iShark_Country c1 ON su.ship_country_id = c1.country_id 
					LEFT JOIN iShark_Country c2 ON su.post_country_id = c2.country_id 
					WHERE su.nuser_id = ".$_SESSION['nuser_id']."
				";
			}
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					$uname  = $row['uname'];
					$umail  = $row['umail'];
					$mphone = $row['mphone'];
					$saddr  = $row['szip']." ".$row['scity'].", ".$row['saddr']." - ".$row['scname'];
					$paddr  = $row['pzip']." ".$row['pcity'].", ".$row['paddr']." - ".$row['pcname'];
				}

				$query = "
					UPDATE iShark_Shop_Orders 
					SET post_address = '".$paddr."', 
						ship_address = '".$saddr."', 
						phone_mobile = '".$mphone."'
					WHERE order_id = $last_order_id
				";
				$mdb2->exec($query);
			}

			//rendelt termekek listaja
			if (isset($_SESSION['user_id'])) {
				$query = "
					SELECT b.product_id AS product_id, b.amount AS amount, ROUND(b.price) AS rprice, b.price AS price, p.product_name AS pname,
						p.state_id AS sid, p.item_id AS item_id, b.attributes AS attr, a.afa_percent AS afa 
					FROM iShark_Shop_Basket b, iShark_Shop_Products p 
					LEFT JOIN iShark_Shop_Afa a ON a.afa_id = p.afa 
					WHERE b.user_id = ".$_SESSION['user_id']." AND b.product_id = p.product_id
				";
			}
			if (isset($_SESSION['nuser_id'])) {
				$query = "
					SELECT b.product_id AS product_id, b.amount AS amount, ROUND(b.price) AS rprice, b.price AS price, p.product_name AS pname,
						p.state_id AS sid, p.item_id AS item_id, b.attributes AS attr, a.afa_percent AS afa 
					FROM iShark_Shop_Basket b, iShark_Shop_Products p 
					LEFT JOIN iShark_Shop_Afa a ON a.afa_id = p.afa 
					WHERE b.nuser_id = ".$_SESSION['nuser_id']." AND b.product_id = p.product_id
				";
			}
			$result =& $mdb2->query($query);
			$orders_array = array();
			$i = 0;
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					$prod_id = $row['product_id'];
					$amount  = $row['amount'];
					$price   = $row['price'];
					$sid     = $row['sid'];
					$attr    = $row['attr'];
					//a levelkuldeshez feltoltjuk a tombot
					$orders_array[$i]['pname']  = $row['pname'];
					$orders_array[$i]['attr']   = $attr;
					$orders_array[$i]['amount'] = $amount;
					$orders_array[$i]['price']  = $row['rprice'];
					$orders_array[$i]['afa']    = $row['afa'];
					$orders_array[$i]['item']   = $row['item_id'];
					$i++;

					$query2 = "
						INSERT INTO iShark_Shop_Orders_Products 
						(order_id, product_id, amount, price, status, state_id, attributes) 
						VALUES 
						($last_order_id, $prod_id, $amount, '$price', 1, $sid, '".$attr."')
					";
					$mdb2->exec($query2);
				}
			}

			//kiuritjuk a kosarat, mert mindent atraktunk rendelesbe
			if (isset($_SESSION['user_id'])) {
				$query = "
					DELETE FROM iShark_Shop_Basket 
					WHERE user_id = ".$_SESSION['user_id']."
				";
			}
			if (isset($_SESSION['nuser_id'])) {
				$query = "
					DELETE FROM iShark_Shop_Basket 
					WHERE nuser_id = ".$_SESSION['nuser_id']."
				";
			}
			$mdb2->exec($query);

			//lekerdezzuk a fizetesi modot (kell a levelkuldeshez)
			$query = "
				SELECT shipping_text, shipping_price 
				FROM iShark_Shop_Configs_Shipping 
				WHERE shipping_id = $shipping
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$row = $result->fetchRow();
				$shipping_text  = $row['shipping_text'];
				$shipping_price = $row['shipping_price'];
			}

			//level kuldese a rendelesrol
			ini_set('display_errors', 0);
			include_once 'Mail.php';
			include_once 'Mail/mime.php';

			$hdrs = array(
				'From'    => $_SESSION['site_sitemail'],
				'Subject' => $locale->get('orders_mail_subject')
			);
			$mime =& new Mail_mime("\n");
			$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			$msg =  $locale->get('orders_mail_header').' '.$uname.'!<br /><br />';
			$msg .= $locale->get('orders_mail_msg1')." <a href='".$_SESSION['site_sitehttp']."' title='".$_SESSION['site_sitename']."'>".$_SESSION['site_sitename']."</a> ".$locale->get('orders_mail_msg2')."<br /><br />";
			//megrendeles adatai
			$msg .= '<table style="width: 100%; text-align: left;">';
			$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->get('orders_mail_msg3').'</th></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg4').'</td><td>'.$last_order_id.'</td></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg5').'</td><td>'.get_date().'</td></tr>';
			$msg .= '</table><br />';
			//megrendelo informacioi
			$msg .= '<table style="width: 100%; text-align: left;">';
			$msg .= '<tr><th colspan="2" style="text-align: left;">'.$locale->get('orders_mail_msg6').'</th></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg7').'</td><td>'.$uname.'</td></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg8').'</td><td>'.$mphone.'</td></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg9').'</td><td>'.$paddr.'</td></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg10').'</td><td>'.$saddr.'</td></tr>';
			$msg .= '<tr><td>'.$locale->get('orders_mail_msg11').'</td><td>'.$shipping_text.'</td></tr>';
			$msg .= '<tr><td valign="top">'.$locale->get('orders_mail_msg12').'</td><td>'.nl2br($mail_comment).'</td></tr>';
			$msg .= '</table><br />';
			//megrendelt tetelek
			$msg .= '<table style="width: 100%; text-align: left;">';
			$msg .= '<tr><th colspan="5" style="text-align: left;">'.$locale->get('orders_mail_msg13').'</th></tr><tr>';
			$msg .= '<td><strong>'.$locale->get('orders_mail_msg14').'</strong></td><td><strong>'.$locale->get('orders_mail_msg15').'</strong></td><td><strong>'.$locale->get('orders_mail_msg16').'</strong></td><td><strong>'.$locale->get('orders_mail_msg17').'</strong></td><td><strong>'.$locale->get('orders_mail_msg18').'</strong></td></tr>';
			$sumprice = 0;
			$price    = 0;
			$afa      = 0;
			for ($i = 0; $i <= count($orders_array); $i++) {
				if (!empty($orders_array[$i]['amount'])) {
					$msg .= "<tr><td>".$orders_array[$i]['pname'];
					//ha hasznaljuk az extra attributumokat
					if (isset($_SESSION['site_shop_is_extra_attr']) && $_SESSION['site_shop_is_extra_attr'] == 1) {
						$msg .= "<br />".$orders_array[$i]['attr'];
					}
					$msg .= "</td><td>".$orders_array[$i]['item']."</td><td>".$orders_array[$i]['amount']."</td><td>".$orders_array[$i]['price']."</td>";
					$msg .= "<td>".$orders_array[$i]['amount']*$orders_array[$i]['price']."</td></tr>";

					$price = $price + ($orders_array[$i]['amount'] * $orders_array[$i]['price']);
					$afa   = $afa + (($orders_array[$i]['amount'] * $orders_array[$i]['price']) / 100 * $orders_array[$i]['afa']);
				}
			}
			$sumprice = $price + $afa + $shipping_price;
			$msg .= '<tr><td>&nbsp;</td></tr>';
			$msg .= '<tr><td colspan="4" style="text-align: right">'.$locale->get('orders_mail_msg19').': </td><td>'.$price.'</td></tr>';
			$msg .= '<tr><td colspan="4" style="text-align: right">'.$locale->get('orders_mail_msg20').': </td><td>'.$shipping_price.'</td></tr>';
			$msg .= '<tr><td colspan="4" style="text-align: right">'.$locale->get('orders_mail_msg21').': </td><td>'.$afa.'</td></tr>';
			$msg .= '<tr><td colspan="4" style="text-align: right"><b>'.$locale->get('orders_mail_msg22').': </b></td><td>'.$sumprice.'</td></tr>';
			$msg .= "</table><br /><br />";
			$msg .= "<p>".$locale->get('orders_mail_msg23')."</p><p><a href='".$_SESSION['site_sitehttp']."' title='".$_SESSION['site_sitename']."'>".$_SESSION['site_sitename']."</a></p>";

			if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
				$tpl->assign('mail_body', $msg);
				$msg = $tpl->fetch('mail/mail_html.tpl');
			}

			$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
			$mime->setHTMLBody($msg);

			// Karakterkeszlet beallitasok
			$mime_params = array(
				"text_encoding" => "8bit",
				"text_charset"  => $charset,
				"head_charset"  => $charset,
				"html_charset"  => $charset,
			);

			$body = $mime->get($mime_params);
			$hdrs = $mime->headers($hdrs);

			$mail =& Mail::factory('mail');
			//level a megrendelonek
			$mail->send($umail, $hdrs, $body);

			//kiszedjuk az e-mail cimet
			$query = "
				SELECT shop_ordermail 
				FROM iShark_Shop_Configs
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$row = $result->fetchRow();

				$hdrs2 = array(
					'From'    => $umail,
					'Subject' => $locale->get('orders_mail_subject')
				);
				$mime2 =& new Mail_mime("\n");

				$msg2 =  $locale->get('orders_mail_msg24')."<br /><br />";
				$msg2 .= $locale->get('orders_mail_msg25')."<br />";
				$msg2 .= "<a href='".$_SESSION['site_sitehttp']."/admin.php?p=".$module_name."&amp;act=ord&amp;ord_act=mod&amp;iod=".$last_order_id."' title='".$locale->get('orders_mail_msg26')."'>".$locale->get('orders_mail_msg26')."</a>";

				if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
				    $tpl->assign('mail_body', $msg2);
				    $msg2 = $tpl->fetch('mail/mail_html.tpl');
				}

				$mime2->setTXTBody(html_entity_decode(strip_tags($msg2)));
				$mime2->setHTMLBody($msg2);

				$body2 = $mime2->get($mime_params);
				$hdrs2 = $mime2->headers($hdrs2);

				$mail2 =& Mail::factory('mail');
				//level a cegnek
				$mail2->send($row['shop_ordermail'], $hdrs2, $body2);
			}

			//toroljuk a sessiont
			if (isset($_SESSION['nuser_id'])) {
				unset($_SESSION['nuser_id']);
			}

			header('Location: index.php?p=success&code=013');
			exit;
		}

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_ordersend->accept($renderer);

		$tpl->assign('form_ordersend', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'shop_ordersend';
	}
} //megrendeles vege

/**
 * cim kivalasztas - csak regisztralt felhasznaloknak
 */
if ($act == "addr") {
	if (isset($_SESSION['user_id'])) {
		include_once $include_dir.'/function.shop.php';

		//ha ures a kosar, akkor vissza a kosarhoz
		if (is_empty_basket() === true) {
			header('Location: index.php?p='.$module_name.'&act=bsk');
			exit;
		} else {
			$javascripts[] = "javascript.shop";

			//szukseges fuggvenykonyvtarak betoltese
			require_once 'HTML/QuickForm.php';
			require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

			//elinditjuk a form-ot - cimek
			$form_address =& new HTML_QuickForm('frm_basketaddr', 'post', 'index.php?p=shop&act=addr');

			//a szukseges szoveget jelzo resz beallitasa
			$form_address->setRequiredNote($locale->get('address_form_required_note'));

			$form_address->addElement('header', 'address',  $locale->get('address_form_header'));

			//szallitasi cim
			$form_address->addElement('text', 'shipaddr', $locale->get('address_field_shipaddress'), array('id' => 'shipaddr'));

			//iranyitoszam
			$form_address->addElement('text', 'shipzip', $locale->get('address_field_zipcode'),  array('id' => 'shipzip'));

			//varos
			$form_address->addElement('text', 'shipcity', $locale->get('address_field_city'), array('id' => 'shipcity'));

			//uj cim hozzaadasa
			$add_address =& $form_address->addElement('checkbox', 'new_address', $locale->get('address_field_new'), null, array("id" => "add_address", "onClick" => "addAddressActivate()"));

			//lekerdezzuk az orszagok listajat
			$query = "
				SELECT c.country_id AS cid, c.country_name AS cname 
				FROM iShark_Country c 
				ORDER BY c.country_name
			";
			$result = $mdb2->query($query);
			$select =& $form_address->addElement('select', 'country', $locale->get('address_field_country'), $result->fetchAll('', $rekey = true), array('id' => 'country'));
			//Magyarorszagot rakjuk az alapertelmezettnek
			$select->setSelected(array(
				'country' => 108
				)
			);

			$form_address->applyFilter('__ALL__', 'trim');

			//Javascript miatt kellett
			if ($add_address->getChecked()) {
				$tpl->assign('none_block', 'block');
			} else {
				$tpl->assign('none_block', 'none');
			}

			$form_address->addElement('submit', 'submit', $locale->get('address_form_submit'), array('class' => 'submit'));
			$form_address->addElement('button', 'reset',  $locale->get('address_form_reset'),  array('class' => 'reset', 'onclick' => 'removeJS()'));

			//ha uj cimet adunk hozza, akkor ezek az ellenorzesek kellenek
			if ($add_address->getChecked()) {
				$form_address->addFormRule('check_add_address');
				$form_address->addRule('shipzip',  $locale->get('address_error_zipcode'),  'required');
				$form_address->addRule('shipcity', $locale->get('address_error_city'),     'required');
				$form_address->addRule('country',  $locale->get('address_error_country'),  'required');
				$form_address->addRule('shipaddr', $locale->get('address_error_shipaddr'), 'required');
			}

			//ha modositunk egy cimet
			if ($form_address->isSubmitted() && $form_address->getSubmitValue('aid') != '' && is_numeric($form_address->getSubmitValue('aid'))) {
				$form_address->addFormRule('check_mod_address');
				$a = check_mod_address($form_address->getSubmitValues());
				if ($a !== true) {
					$bodyonload[] = "document.getElementById('addaddress').style.display = 'block'";
				}
			}

			if ($form_address->validate()) {
				$form_address->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				//ha uj cimet veszunk fel
				if ($add_address->getChecked()) {
					$zip     = $form_address->getSubmitValue('shipzip');
					$city    = $form_address->getSubmitValue('shipcity');
					$country = intval($form_address->getSubmitValue('country'));
					$address = $form_address->getSubmitValue('shipaddr');

					$address_id = $mdb2->extended->getBeforeID('iShark_Shop_Address', 'address_id', TRUE, TRUE);
					$query = "
						INSERT INTO iShark_Shop_Address
						(address_id, user_id, country_id, city, zipcode, address) 
						VALUES 
						($address_id, '".$_SESSION['user_id']."', '$country', '".$city."', '".$zip."', '".$address."')
					";
					$mdb2->exec($query);

					header('Location: index.php?p='.$module_name.'&act=addr');
					exit;
				}

				//ha modositjuk a cimet
				if ($form_address->getSubmitValue('aid') != '' && is_numeric($form_address->getSubmitValue('aid'))) {
					$aid     = intval($form_address->getSubmitValue('aid'));
					$zip     = $form_address->getSubmitValue('shipzip');
					$city    = $form_address->getSubmitValue('shipcity');
					$country = intval($form_address->getSubmitValue('country'));
					$address = $form_address->getSubmitValue('shipaddr');

					$query = "
						UPDATE iShark_Shop_Address
						SET user_id    = '".$_SESSION['user_id']."', 
							country_id = '$country', 
							city       = '".$city."', 
							zipcode    = '".$zip."', 
							address    = '".$address."' 
						WHERE address_id = '$aid'
					";
					$mdb2->exec($query);

					header('Location: index.php?p='.$module_name.'&act=addr');
					exit;
				}
			}

			$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
			$form_address->accept($renderer);

			$tpl->assign('form_address', $renderer->toArray());

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_array', ob_get_contents());
			ob_end_clean();
			/**
			 * cim hozzaadas, modositas - vege
			 */

			//elinditjuk a form-ot - teljes
			$form_basket =& new HTML_QuickForm('frm_addr', 'post', 'index.php?p='.$module_name.'&act=addr');

			//a szukseges szoveget jelzo resz beallitasa
			$form_basket->setRequiredNote($locale->get('address_form_required_note'));

			//form-hoz elemek hozzadasa
			$form_basket->addElement('header', 'address', $locale->get('address_form_header'));

			//telefon
			$form_basket->addElement('text', 'mobilephone', $locale->get('address_field_phone'));

			//lekerdezzuk a user alap infoit
			$query = "
				SELECT u.name AS user, u.user_name AS user_name, u.email AS email
				FROM iShark_Users u
				WHERE u.user_id = ".$_SESSION['user_id']." AND is_active = 1 AND is_deleted = 0
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$tpl->assign('userdata', $result->fetchAll('', $rekey = true));

				//lekerdezzuk a felhasznalo egyeb adatait, amelyek kellenek a rendeleshez - telefon
				$query = "
					SELECT su.phone_mobile AS phone_mobile, su.ship_address AS saddr, su.post_address AS paddr 
					FROM iShark_Shop_Users su 
					WHERE su.user_id = '".$_SESSION['user_id']."'
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
					$row = $result->fetchRow();
					$form_basket->setDefaults(array(
						'mobilephone' => $row['phone_mobile'],
						'shipselect'  => $row['saddr'],
						'postselect'  => $row['paddr']
						)
					);
				}
			}

			//lekerdezzuk, hogy az extra infokat kitoltotte-e mar, amik a megrendeleshez szuksegesek
			$query = "
				SELECT a.address_id AS aid, a.city AS city, a.zipcode AS zip, a.address AS address, c.country_name AS cname, 
					c.country_id AS cid 
				FROM iShark_Shop_Address a, iShark_Country c 
				WHERE a.country_id = c.country_id AND a.user_id = ".$_SESSION['user_id']."
			";
			$result =& $mdb2->query($query);
			//ha van mar rogzitett cime, akkor kiirjuk a cimeket, hogy valaszthasson kozuluk vagy modosithassa azokat
			$address_select = array();
			$address_list = "address_list = new Array();\n";
			if ($result->numRows() > 0) {
				//atadjuk a teljes listat is, ez fog kelleni a cimek modositasahoz
				while ($row = $result->fetchRow())
				{
					$aid = $row['aid'];
					$address_list .= "address_list[$aid] = new Array();\n";
					$address_list .= "address_list[$aid]['aid']     = '".$row['aid']."';\n";
					$address_list .= "address_list[$aid]['city']    = '".$row['city']."';\n";
					$address_list .= "address_list[$aid]['zip']     = '".$row['zip']."';\n";
					$address_list .= "address_list[$aid]['address'] = '".$row['address']."';\n";
					$address_list .= "address_list[$aid]['cname']   = '".$row['cname']."';\n";
					$address_list .= "address_list[$aid]['cid']     = '".$row['cid']."';\n";
					$address_select[$row['aid']] = $row['zip']." ".$row['city'].", ".$row['address']." - ".$row['cname'];
				}
			}
			$shipselect = $form_basket->addElement('select', 'shipselect', $locale->get('address_field_shipselect'),   $address_select, array('id' => 'shipselect'));
			$postselect = $form_basket->addElement('select', 'postselect', $locale->get('address_field_postalselect'), $address_select, array('id' => 'postselect'));

			$form_basket->applyFilter('__ALL__', 'trim');

			$form_basket->addRule('mobilephone', $locale->get('address_error_phone'),      'required');
			$form_basket->addRule('shipselect',  $locale->get('address_error_shipselect'), 'required');
			$form_basket->addRule('postselect',  $locale->get('address_error_postselect'), 'required');

			if ($form_basket->validate()) {
				$form_basket->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$mobilephone = $form_basket->getSubmitValue('mobilephone');
				$shipaddress = intval($form_basket->getSubmitValue('shipselect'));
				$postaddress = intval($form_basket->getSubmitValue('postselect'));

				//frissitjuk a telefonszamokat
				$query = "
					SELECT * 
					FROM iShark_Shop_Users 
					WHERE user_id = ".$_SESSION['user_id']."
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
					$query = "
						UPDATE iShark_Shop_Users 
						SET phone_mobile = '".$mobilephone."',
							ship_address = '".$shipaddress."',
							post_address = '".$postaddress."'
						WHERE user_id = '".$_SESSION['user_id']."'
					";
				} else {
					$query = "
						INSERT iShark_Shop_Users 
						(user_id, phone_mobile, ship_address, post_address) 
						VALUES 
						('".$_SESSION['user_id']."', '".$mobilephone."', '".$shipaddress."', '".$postaddress."')
					";
				}
				$mdb2->exec($query);

				//"fagyasztjuk" a form-ot
				$form_basket->freeze();

				header('Location: index.php?p='.$module_name.'&act=ord');
				exit;
			}

			$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
			$form_basket->accept($renderer);

			$tpl->assign('address_list', $address_list);
			$tpl->assign('country_list', $country);
			$tpl->assign('form_basket',  $renderer->toArray());

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_array', ob_get_contents());
			ob_end_clean();

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'shop_basket_address';
		}
	}
}

/**
 * belepes/regisztracio
 */
if ($act == "reg") {
	include_once $include_dir.'/function.shop.php';

	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	include_once $include_dir.'/function.check.php';

	//ha a felhasznalok regisztralhatnak az oldalon, csak akkor latszodik a regisztracios resz
	if (!empty($_SESSION['site_userlogin']) && !isset($_POST['nreg'])) {
		$form_shop =& new HTML_QuickForm('frm_shopreg', 'post', 'index.php?p=shop');

		$form_shop->setRequiredNote($locale->get('account_form_required_note'));

		$form_shop->addElement('header', 'account', $locale->get('account_form_header'));
		$form_shop->addElement('hidden', 'act',     'reg');

		//nick
		$form_shop->addElement('text', 'name', $locale->get('account_form_name'), array("maxlength" => 255));

		//nev
		$form_shop->addElement('text', 'user_name', $locale->get('account_form_username'),  array("maxlength" => 255));

		//email
		$form_shop->addElement('text', 'email', $locale->get('account_form_email'), array("maxlength" => 255));

		//publikus email
		$ispublicmail =& $form_shop->addElement('checkbox', 'is_public_mail', $locale->get('account_form_publicmail'));

		//jelszo
		$form_shop->addElement('password', 'pass1', $locale->getBySmarty('account_form_pass1'), array("id" => "pass1", "maxlength" => 30));
		$form_shop->addElement('password', 'pass2', $locale->get('account_form_pass2'), array("id" => "pass2", "maxlength" => 30));

		//szurok beallitasa
		$form_shop->applyFilter('__ALL__', 'trim');

		//szabalyok beallitasa
		$form_shop->addRule('name',      $locale->get('account_error_name'),     'required');
		$form_shop->addRule('user_name', $locale->get('account_error_username'), 'required');
		$form_shop->addRule('email',     $locale->get('account_error_email'),    'required');
		$form_shop->addRule('email',     $locale->get('account_error_email2'),   'email');
		$form_shop->addRule('pass1',     $locale->get('account_error_minpass'),  'minlength', $_SESSION['site_minpass']);
		$form_shop->addRule('pass1',     $locale->get('account_error_pass1'),    'required');
		$form_shop->addRule('pass2',     $locale->get('account_error_pass2'),    'required');
		$form_shop->addRule(array('pass1', 'pass2'), $locale->get('account_error_cmppass'), 'compare');

		//ha active a hirlevel modul
		if (isModule('newsletter') === true) {
			$subscribe_nl =& $form_shop->addElement('checkbox', 'subscribe', $locale->get('account_form_subscribe'));
		}

		$form_shop->addFormRule('check_adduser');
		if ($form_shop->validate()) {
			$form_shop->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name      = $form_shop->getSubmitValue('name');
			$email     = $form_shop->getSubmitValue('email');
			$user_name = $form_shop->getSubmitValue('user_name');
			$is_pmail  = $ispublicmail->getChecked() ? '1' : '0';

			$password  = md5($form_shop->getSubmitValue('pass1'));
			require_once "Text/Password.php";
			$activate = Text_Password::create(8, 'unpronounceable', 'alphanumeric');

			$user_id = $mdb2->extended->getBeforeID('iShark_Users', 'user_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Users 
				(user_id, name, user_name, email, password, is_deleted, activate, is_active, is_public_mail, is_public)
				VALUES 
				($user_id, '$name', '$user_name', '$email', '$password', '0', '$activate', '0', '$is_pmail', '1')
			";
			$mdb2->exec($query);

			// Hirlevel feliratkozas, ha aktiv a hirlevel modul
			if (isModule('newsletter') === true) {
				if ($subscribe_nl->getChecked()) {
					$query = "
						SELECT count(*) AS cnt 
						FROM iShark_Newsletter_Users 
						WHERE email = '$email'
					";
					$result =& $mdb2->query($query);

					if ($row = $result->fetchRow()) {
						// Ha meg nem iratkozott fel a hirlevelre:
						if ((int)$row['cnt'] == 0) {
						    $newsletter_user_id = $mdb2->extended->getBeforeID('iShark_Newsletter_Users', 'newsletter_user_id', TRUE, TRUE);
							$query = "
								INSERT INTO iShark_Newsletter_Users 
								(newsletter_user_id, name, email, activate, is_active, is_deleted)
								VALUES 
								($newsletter_user_id, '$name', '$email', '$activate', '0', '0')
							";
							$mdb2->exec($query);
						}
					}
				}
			}

			//kikuldunk a megadott e-mail cimre egy levelet
			ini_set('display_errors', 0);
			include_once 'Mail.php';
			include_once 'Mail/mime.php';

			$hdrs = array(
				'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
				'Subject' => $locale->get('mail_activate_subject')
			);
			$mime = new Mail_mime("\n");
			$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			$msg = $locale->get('account_mail_activate_header').' '.$name.'!<br /><br />';
			$msg .= $locale->get('account_mail_activate_text1').'<br />';
			$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p=account&act=account_act&uname='.$name.'&gc='.$activate.'&sid='.session_id().'" title="'.$locale->get('account_mail_activate_text2').'">'.$locale->get('account_mail_activate_text2').'</a><br /><br />';
			$msg .= $locale->get('account_mail_activate_text3').'<br />';
			$msg .= $_SESSION['site_sitehttp'].'/index.php?p=account&act=account_act&uname='.$name.'&gc='.$activate.'&sid='.session_id().'<br /><br />';
			$msg .= $locale->get('account_mail_activate_text4').'<br />';
			$msg .= '<a href="'.$_SESSION['site_sitehttp'].'/index.php?p=account&act=account_del&uname='.$name.'&gc='.$activate.'&sid='.session_id().'" title="'.$locale->get('account_mail_activate_text5').'">'.$locale->get('account_mail_activate_text5').'</a><br /><br />';
			$msg .= $locale->get('account_mail_activate_text6').'<br /><a href="'.$_SESSION['site_sitehttp'].'" title="'.$_SESSION['site_sitename'].'">'.$_SESSION['site_sitename'].'</a>';

			if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
				$tpl->assign('mail_body', $msg);
				$msg = $tpl->fetch('mail/mail_html.tpl');
			}

			$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
			$mime->setHTMLBody($msg);

			// Karakterk�szlet be�ll�t�sok
			$mime_params = array(
				"text_encoding" => "8bit",
				"text_charset"  => $charset,
				"head_charset"  => $charset,
				"html_charset"  => $charset,
			);

			$body = $mime->get($mime_params);
			$hdrs = $mime->headers($hdrs);

			$mail =& Mail::factory('mail');
			$mail->send($email, $hdrs, $body);

			//"fagyasztjuk" a form-ot
			$form_shop->freeze();

			//visszadobjuk a lista oldalra
			header('Location: index.php?p=success&code=005');
			exit;
		}

		$form_shop->addElement('submit', 'submit', $locale->get('account_form_submit'), 'class="submit"');
		$form_shop->addElement('reset',  'reset',  $locale->get('account_form_reset'),  'class="reset"');

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

		$form_shop->accept($renderer);

		$tpl->assign('form_shop', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();
	}

	//ha belephetnek a userek az oldalon
	if (!empty($_SESSION['site_userlogin'])) {
		//belepes form
		$form_shop_login =& new HTML_QuickForm('frm_shoplogin', 'post', 'index.php?p=account');

		$form_shop_login->addElement('header',   'login', $locale->get('account_login_header'));
		$form_shop_login->addElement('hidden',   'act',   'in');
		$form_shop_login->addElement('hidden',   'shop',  '1');

		//nev
		$form_shop_login->addElement('text', 'login_name', $locale->get('account_login_name'));

		//jelszo
		$form_shop_login->addElement('password', 'login_pass', $locale->get('account_login_pass'));

		$form_shop_login->addElement('submit', 'submit', $locale->get('account_login_submit'), 'class="submit"');

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

		$form_shop_login->accept($renderer);

		$tpl->assign('form_shop_login', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();
	}

	//ha nem csak regisztralt felhasznalo vasarolhat
	if (isset($_SESSION['site_shop_reguserbuy']) && $_SESSION['site_shop_reguserbuy'] == 0) {
		$javascripts[] = "javascript.shop";

		//belepes form
		$form_shop_notreg =& new HTML_QuickForm('frm_shopnoreg', 'post', 'index.php?p=shop');

		$form_shop_notreg->setRequiredNote($locale->get('account_notreg_form_required_note'));

		$form_shop_notreg->addElement('header', 'account', $locale->get('account_notreg_form_header'));
		$form_shop_notreg->addElement('hidden', 'act',     'reg');
		$form_shop_notreg->addElement('hidden', 'nreg',    '1');

		//nev
		$form_shop_notreg->addElement('text', 'user_name', $locale->get('account_notreg_field_name'), array("maxlength" => 255));

		//email
		$form_shop_notreg->addElement('text', 'email', $locale->get('account_notreg_field_mail'), array("maxlength" => 255));

		//telefon
		$form_shop_notreg->addElement('text', 'phone', $locale->get('account_notreg_field_phone'), array("maxlength" => 255));

		//szamlazasi cim
		$form_shop_notreg->addElement('static', 'shipaddress', $locale->get('account_notreg_field_shipaddress'));

		//iranyitoszam
		$form_shop_notreg->addElement('text', 'shipzip', $locale->get('account_notreg_field_zipcode'));

		//varos
		$form_shop_notreg->addElement('text', 'shipcity', $locale->get('account_notreg_field_city'));

		//cim
		$form_shop_notreg->addElement('text', 'shipaddr', $locale->get('account_notreg_field_addr'));

		//szallitasi cim
		$form_shop_notreg->addElement('static', 'postaddress', $locale->get('account_notreg_field_postaddress'));

		//iranyitoszam
		$form_shop_notreg->addElement('text', 'postzip', $locale->get('account_notreg_field_zipcode'));

		//varos
		$form_shop_notreg->addElement('text', 'postcity', $locale->get('account_notreg_field_city'));

		//cim
		$form_shop_notreg->addElement('text', 'postaddr', $locale->get('account_notreg_field_addr'));

		//ugyanaz a ket cim
		$form_shop_notreg->addElement('checkbox', 'copyaddr', $locale->get('account_notreg_field_copyaddress'), null, array('onclick' => 'javascript: copyAddress(this.form)'));

		//lekerdezzuk az orszagok listajat
		$query = "
			SELECT c.country_id AS cid, c.country_name AS cname 
			FROM iShark_Country c 
			ORDER BY c.country_name
		";
		$result = $mdb2->query($query);
		$country = $result->fetchAll('', $rekey = true);
		$selectship =& $form_shop_notreg->addElement('select', 'shipcountry', $locale->get('account_notreg_field_country'), $country, array('id' => 'shipcountry'));
		$selectpost =& $form_shop_notreg->addElement('select', 'postcountry', $locale->get('account_notreg_field_country'), $country, array('id' => 'postcountry'));
		//Magyarorszagot rakjuk az alapertelmezettnek
		$selectship->setSelected(array(
			'shipcountry' => 108
			)
		);
		$selectpost->setSelected(array(
			'postcountry' => 108
			)
		);

		$form_shop_notreg->addElement('submit', 'submit', $locale->get('account_notreg_form_submit'), 'class="submit"');
		$form_shop_notreg->addElement('reset',  'reset',  $locale->get('account_notreg_form_reset'),  'class="reset"');

		//szurok beallitasa
		$form_shop_notreg->applyFilter('__ALL__', 'trim');

		//szabalyok beallitasa
		$form_shop_notreg->addRule('user_name',   $locale->get('account_notreg_error_name'),          'required');
		$form_shop_notreg->addRule('email',       $locale->get('account_notreg_error_mail1'),         'required');
		$form_shop_notreg->addRule('email',       $locale->get('account_notreg_error_mail2'),         'email');
		$form_shop_notreg->addRule('phone',       $locale->get('account_notreg_error_phone'),         'required');
		$form_shop_notreg->addRule('shipzip',     $locale->get('account_notreg_error_shipzip'),       'required');
		$form_shop_notreg->addRule('shipcity',    $locale->get('account_notreg_error_shipcity'),      'required');
		$form_shop_notreg->addRule('shipaddr',    $locale->get('account_notreg_error_shipaddr'),      'required');
		$form_shop_notreg->addRule('shipcountry', $locale->get('account_notreg_error_shipcountry'),   'required');
		$form_shop_notreg->addRule('postzip',     $locale->get('account_notreg_error_postalzip'),     'required');
		$form_shop_notreg->addRule('postcity',    $locale->get('account_notreg_error_postalcity'),    'required');
		$form_shop_notreg->addRule('postaddr',    $locale->get('account_notreg_error_postaladdr'),    'required');
		$form_shop_notreg->addRule('postcountry', $locale->get('account_notreg_error_postalcountry'), 'required');

		if ($form_shop_notreg->validate()) {
			$form_shop_notreg->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$user_name   = $form_shop_notreg->getSubmitValue('user_name');
			$email       = $form_shop_notreg->getSubmitValue('email');
			$phone       = $form_shop_notreg->getSubmitValue('phone');
			$shipzip     = $form_shop_notreg->getSubmitValue('shipzip');
			$shipcity    = $form_shop_notreg->getSubmitValue('shipcity');
			$shipaddr    = $form_shop_notreg->getSubmitValue('shipaddr');
			$shipcountry = intval($form_shop_notreg->getSubmitValue('shipcountry'));
			$postzip     = $form_shop_notreg->getSubmitValue('postzip');
			$postcity    = $form_shop_notreg->getSubmitValue('postcity');
			$postaddr    = $form_shop_notreg->getSubmitValue('postaddr');
			$postcountry = intval($form_shop_notreg->getSubmitValue('postcountry'));

			require_once "Text/Password.php";
			$activate = Text_Password::create(8, 'unpronounceable', 'alphanumeric');

			$nuser_id = $mdb2->extended->getBeforeID('iShark_Shop_Users_Notreg', 'nuser_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Shop_Users_Notreg 
				(nuser_id, user_name, email, phone_mobile, ship_zipcode, ship_city, ship_country_id, ship_address, 
				post_zipcode, post_city, post_country_id, post_address, is_active, activate, add_date) 
				VALUES 
				($nuser_id, '".$user_name."', '".$email."', '".$phone."', '".$shipzip."', '".$shipcity."', '$shipcountry', '".$shipaddr."', 
				'".$postzip."', '".$postcity."', '$postcountry', '".$postaddr."', '0', '".$activate."', NOW())
			";
			$mdb2->exec($query);
			$last_nuser_id = $mdb2->extended->getAfterID($nuser_id, 'iShark_Shop_Users_Notreg', 'nuser_id');

			//frissitjuk a kosarat is, mert beallitjuk az nuser_id valtozot
			$query = "
				UPDATE iShark_Shop_Basket 
				SET nuser_id = $last_nuser_id 
				WHERE session_id = '".session_id()."'
			";
			$mdb2->exec($query);

			//elkuldjuk a levelet
			ini_set('display_errors', 0);
			include_once 'Mail.php';
			include_once 'Mail/mime.php';

			$hdrs = array(
				'From'    => '"'.preg_replace('|"|', '\"', $_SESSION['site_sitename']).'" <'.$_SESSION['site_sitemail'].'>',
				'Subject' => $locale->get('account_notreg_mail_subject')
			);
			$mime =& new Mail_mime("\n");
			$charset = $locale->getCharset() ? 'ISO-8859-2' : $locale->getCharset();

			$msg =  $locale->get('account_notreg_mail_header')." ".$user_name."<br /><br />";
			$msg .= $locale->get('account_notreg_mail_msg1')." <a href='".$_SESSION['site_sitehttp']."' title='".$_SESSION['site_sitename']."'>".$_SESSION['site_sitename']."</a> ".$locale->get('account_notreg_mail_msg2')."'<br />";
			$msg .= $locale->get('account_notreg_mail_msg3')."<br />";
			$msg .= "<a href='".$_SESSION['site_sitehttp']."/index.php?p=shop&amp;act=act&amp;nid=".$last_nuser_id."&amp;code=".$activate."&amp;sid=".session_id()."' title='".$locale->get('account_notreg_mail_msg4')."'>".$locale->get('account_notreg_mail_msg4')."</a><br /><br />";
			$msg .= $locale->get('account_notreg_mail_msg5')."<br />";
			$msg .= "<a href='".$_SESSION['site_sitehttp']."' title='".$_SESSION['site_sitename']."'>".$_SESSION['site_sitename']."</a>";

			if (is_file($tpl->template_dir.'/mail/mail_html.tpl')) {
				$tpl->assign('mail_body', $msg);
				$msg = $tpl->fetch('mail/mail_html.tpl');
			}

			$mime->setTXTBody(html_entity_decode(strip_tags($msg)));
			$mime->setHTMLBody($msg);

			// Karakterkeszlet beallitasok
			$mime_params = array(
				"text_encoding" => "8bit",
				"text_charset"  => $charset,
				"head_charset"  => $charset,
				"html_charset"  => $charset,
			);

			$body = $mime->get($mime_params);
			$hdrs = $mime->headers($hdrs);

			$mail =& Mail::factory('mail');
			$mail->send($email, $hdrs, $body);

			//"fagyasztjuk" a form-ot
			$form_shop_notreg->freeze();

			//visszadobjuk a lista oldalra
			header('Location: index.php?p=success&code=014');
			exit;
		}

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

		$form_shop_notreg->accept($renderer);

		$tpl->assign('form_shop_notreg', $renderer->toArray());

		// capture the array stucture
		ob_start();
		print_r($renderer->toArray());
		$tpl->assign('static_array', ob_get_contents());
		ob_end_clean();
	}

	//megadjuk a tpl file nevet, amit atadunk az index.php-nek
	$acttpl = 'shop_account';
}

/**
 * ha nem kell regisztralni a vasarlashoz - aktivalas
 */
if ($act == "act") {
	if (isset($_GET['nid']) && is_numeric($_GET['nid']) && isset($_GET['code']) && isset($_GET['sid'])) {
		$nid  = intval($_GET['nid']);
		$code = $_GET['code'];
		$sid  = $_GET['sid'];

		//lekerdezzuk, hogy van-e ilyen rendeles
		$query = "
			SELECT u.is_active AS is_active 
			FROM iShark_Shop_Users_Notreg u 
			WHERE nuser_id = $nid AND activate = '".$code."'
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();
			//ha mar egyszer aktivalva van, akkor hiba
			if ($row['is_active'] == 1) {
			    $site_errors[] = array('text' => $locale->get('account_error_activate'), 'link' => 'javascript:history.back(-1)');
			    return;
			} else {
				//letrehozzuk a session-t
				$_SESSION['nuser_id'] = $nid;

				//aktivaljuk a user-t
				$query2 = "
					UPDATE iShark_Shop_Users_Notreg 
					SET is_active = 1 
					WHERE nuser_id = $nid AND activate = '".$code."'
				";
				$mdb2->exec($query2);

				//frissitjuk a kosarat a regisztralt user adataira
				$query2 = "
					UPDATE iShark_Shop_Basket 
					SET nuser_id = $nid 
					WHERE session_id = '".$sid."'
				";
				$mdb2->exec($query2);

				header('Location: index.php?p='.$module_name.'&act=ord');
				exit;
			}
		} else {
		    $site_errors[] = array('text' => $locale->get('account_error_activate_notexists'), 'link' => 'javascript:history.back(-1)');
			return;
		}
	} else {
		$site_errors[] = array('text' => $locale->get('account_error_activate_notexists'), 'link' => 'javascript:history.back(-1)');
		return;
	}
}

/**
 * torles
 */
if ($act == "del") {
	if (isset($_SESSION['user_id'])) {
		if (isset($_REQUEST['aid']) && is_numeric($_REQUEST['aid'])) {
			$aid = intval($_REQUEST['aid']);

			$query = "
				DELETE FROM iShark_Shop_Address 
				WHERE address_id = $aid
			";
			$mdb2->exec($query);
		}
	}

	header('Location: index.php?p='.$module_name.'&act=reg');
	exit;
} //torles vege

/**
 * kosar uritese
 */
if ($act == "ebsk") {
	if (isset($_SESSION['user_id'])) {
		$query = "
			DELETE FROM iShark_Shop_Basket 
			WHERE user_id = ".$_SESSION['user_id']."
		";
	} else {
		$query = "
			DELETE FROM iShark_Shop_Basket 
			WHERE session_id = '".session_id()."'
		";
	}
	$mdb2->exec($query);

	header('Location: index.php');
	exit;
}

/**
 * reszletes kereses
 */
if ($act == "sea") {
	//szukseges fuggvenykonyvtarak betoltese
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form_search_details =& new HTML_QuickForm('frm_searchdetails', 'post', 'index.php?p='.$module_name.'&act=ser');

	$form_search_details->setRequiredNote($locale->get('search_form_required_note'));

	$form_search_details->addElement('header', 'search', $locale->get('search_form_header'));

	//keresett sz�veg
	$form_search_details->addElement('text', 'searchtext', $locale->get('search_field_text'));

	//kereses tipusa
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_all'),  'all');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_name'), 'name');
	$radio[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('search_field_type_item'), 'item');
	$form_search_details->addGroup($radio, 'searchtype', $locale->get('search_field_type'), '<br />');

	$form_search_details->addElement('submit', 'submit', $locale->get('search_form_submit'), array('class' => 'submit'));

	$form_search_details->setDefaults(array(
		'searchtype' => 'all'
		)
	);

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

	$form_search_details->accept($renderer);
	$tpl->assign('form_search_details', $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az index.php-nek
	$acttpl = 'shop_search_details';
}

/**
 * kereses talalatok
 */
if ($act == "ser") {
	$search_result = array();

	if (isset($_REQUEST['searchtext']) && $_REQUEST['searchtext'] != "") {
		//ha a beirt karakterszam kisebb, mint az engedelyezett minimum
		if (isset($_SESSION['site_shop_searchminchar']) && strlen($_REQUEST['searchtext']) < $_SESSION['site_shop_searchminchar']) {
		    $site_errors[] = array('text' => $locale->getBySmarty('search_error_minchar'), 'link' => 'javascript:history.back(-1)');
		    return;
		}

		//megnezzuk, hogy hol szeretne keresni - ha reszletes keresesbol jon
		if (isset($_REQUEST['searchtype'])) {
			if ($_REQUEST['searchtype'] != "all" && $_REQUEST['searchtype'] != "name" && $_REQUEST['searchtype'] != "item") {
				$_REQUEST['searchtype'] == "all";
			}
			//ha mindenhol keres
			if ($_REQUEST['searchtype'] == "all") {
				$searchtype = "
					(p.product_name LIKE ('%".$_REQUEST['searchtext']."%') 
					OR p.product_desc LIKE ('%".htmlentities($_REQUEST['searchtext'])."%') 
					OR p.item_id LIKE ('%".$_REQUEST['searchtext']."%'))
				";
			}
			//ha termeknevben keres
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
			$searchbreadcrumb = $_REQUEST['searchtype'];
			$pager_searchtype = $_REQUEST['searchtype'];
		} else {
			$searchtype = "
				(p.product_name LIKE ('%".$_REQUEST['searchtext']."%') 
				OR p.product_desc LIKE ('%".htmlentities($_REQUEST['searchtext'])."%') 
				OR p.item_id LIKE ('%".$_REQUEST['searchtext']."%'))
			";
			//breadcrumbhoz kell
			$searchbreadcrumb = "";
			$pager_searchtype = "all";
		}

		//ha vannak plusz mezok, akkor azokbol csinalunk egy tombot, amit belerakunk a lekerdezesbe
		$query = "
			SELECT prop_value, prop_display 
			FROM iShark_Shop_Properties 
			WHERE prop_is_list = 1
		";
		$result = $mdb2->query($query);
		$plusfields = array();
		$tplfields  = array();
		if ($result->numRows() > 0) {
			$i = 0;
			while ($row = $result->fetchRow())
			{
				$plusfields[] = $row['prop_value'];
				$tplfields[$i]['value'] = $row['prop_value'];
				$tplfields[$i]['display'] = $row['prop_display'];
				$i++;
			}
		}

		//ha lehet ertekelni a termekeket
		if (isset($_SESSION['site_shop_is_rating']) && $_SESSION['site_shop_is_rating'] == 1) {
			$rating_query_fields = "
				, ROUND(AVG(rating)) AS avg_rating, COUNT(rating) AS cnt_rating 
			";
			$rating_join_tables = "
				LEFT JOIN iShark_Shop_Products_Rating r ON r.product_id = p.product_id 
			";
			$rating_group = "
				GROUP BY p.product_id
			";
		} else {
			$rating_query_fields = "";
			$rating_join_tables  = "";
			$rating_group        = "";
		}

		//ha van akcios ar kezeles
		if (isset($_SESSION['site_shop_actionuse']) && $_SESSION['site_shop_actionuse'] == 1) {
			$action_query_fields = "
				, ap.percent AS actionpercent, ROUND(ap.price) AS actionprice, sa.timer_start AS actiontstart, sa.timer_end AS actiontend 
			";
			$action_join_tables = "
				LEFT JOIN iShark_Shop_Actions_Products ap ON ap.product_id = p.product_id 
				LEFT JOIN iShark_Shop_Actions sa ON sa.action_id = ap.action_id AND 
					(sa.timer_start = '0000-00-00 00:00:00' OR (sa.timer_start < NOW() AND sa.timer_end > NOW()))
			";
		} else {
			$action_query_fields = "";
			$action_join_tables  = "";
		}

		$query = "
			SELECT p.product_id AS pid, product_name AS pname, p.item_id AS item, p.product_desc AS pdesc, 
				ROUND(p.netto) AS netto, a.afa_percent AS afa, b.amount AS amount $rating_query_fields $action_query_fields 
		";
		//ha vannak plusz mezok
		if (is_array($plusfields) && count($plusfields) > 0) {
			$plusquery = implode(",", $plusfields);
			$query .= ", ".$plusquery;
		}
		$query .= "
			FROM iShark_Shop_Products p 
			LEFT JOIN iShark_Shop_Afa a ON p.afa = a.afa_id 
			LEFT JOIN iShark_Shop_Basket b ON b.product_id = p.product_id 
			$rating_join_tables 
			$action_join_tables 
		";
		if (isset($_SESSION['user_id']) || session_id()) {
			$query .= " AND (";
			if (isset($_SESSION['user_id'])) {
				$query .= "b.user_id = ".$_SESSION['user_id']." ";
				if (session_id()) {
					$query .= " OR ";
				}
			}
			if (session_id()) {
				$query .= "(b.session_id = '".session_id()."' AND b.user_id = '')";
			}
			$query .= ")";
		}
		$query .= "
			WHERE $searchtype AND  p.is_active = 1 AND p.is_deleted = 0 
				AND (p.timer_start = '0000-00-00 00:00:00' OR (p.timer_start < NOW() AND p.timer_end > NOW())) 
				$rating_group
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

		//lapozohoz hozzatesszuk a ket szukseges valtozot
		$pagerOptions['extraVars'] = array(
			'searchtext' => $_REQUEST['searchtext'],
			'searchtype' => $pager_searchtype
		);

		//lapozo
		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		//kepeket berakjuk a tombbe
		foreach ($paged_data['data'] as $key => $adat) {
			$pictures = array();
			$query = "
				SELECT picture 
				FROM iShark_Shop_Products_Picture 
				WHERE product_id = ".$adat['pid']."
			";
			$mdb2->setLimit($_SESSION['site_shop_prodpiclistnum']);
			$result =& $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$pictures[] = $row['picture'];
			}
			$adat['pictures'] = $pictures;
			$data[] = $adat;
		}

		//breadcrumb
		if (!empty($_SESSION['site_shop_is_breadcrumb'])) {
			$shop_breadcrumb->add($locale->get('search_breadcrumb'), 'index.php?p='.$module_name.'&amp;act=ser&amp;searchtext='.$_REQUEST['searchtext'].'&amp;searchtype='.$_REQUEST['searchtype']);
		}

		$tpl->assign('page_data', $data);
		$tpl->assign('page_list', $paged_data['links']);
	}
	//ha nem adott meg keresendo szoveget, akkor hiba
	else {
	    $site_errors[] = array('text' => $locale->get('search_error_empty'), 'link' => 'javascript:history.back(-1)');
		return;
	}

	$tpl->assign('tplfields', $tplfields);
	//mivel ugyanazt a tpl file-t hasznaljuk, mint a sima termeklista, ezert ebbe a valtozoba rakjuk be a cimet
	$tpl->assign('cat_name',  $locale->get('search_result_header'));

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop';
}

/**
 * ha a termek adatlapot mutatjuk
 */
if ($act == "prd") {
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$pid = intval($_GET['pid']);

		//ha vannak plusz mezok, akkor azokbol csinalunk egy tombot, amit belerakunk a lekerdezesbe
		if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
			$cid = intval($_GET['cid']);

			$query = "
				SELECT p.prop_value, p.prop_display 
				FROM iShark_Shop_Properties p, iShark_Shop_Properties_Category pc 
				WHERE (pc.category_id = $cid OR pc.category_id = 0) AND p.prop_id = pc.prop_id
			";
		} else {
			//lekerdezzuk, hogy az adott termek milyen kategoriakhoz tartozhat, ezek alapjan jelenitjuk meg a plusz mezoket
			$query_cat = "
				SELECT pc.category_id AS category_id 
				FROM iShark_Shop_Products_Category pc 
				WHERE pc.product_id = $pid 
				GROUP BY pc.category_id 
			";
			$result_cat =& $mdb2->query($query_cat);
			if ($result_cat->numRows() > 0) {
				$where = "";
				while ($row_cat = $result_cat->fetchRow())
				{
					$where .= $row_cat['category_id'].",";
				}
				$where .= "0";
				$query_category = "
					AND pc.category_id IN ($where) 
					GROUP BY p.prop_value
				";
			} else {
				$query_category = "";
			}

			$query = "
				SELECT p.prop_value, p.prop_display 
				FROM iShark_Shop_Properties p, iShark_Shop_Properties_Category pc 
				WHERE p.prop_id = pc.prop_id AND p.prop_is_list = 1 $query_category
			";
		}
		$result = $mdb2->query($query);
		$plusfields = array();
		$tplfields  = array();
		if ($result->numRows() > 0) {
			$i = 0;
			while ($row = $result->fetchRow())
			{
				$plusfields[] = $row['prop_value'];
				$tplfields[$i]['value'] = $row['prop_value'];
				$tplfields[$i]['display'] = $row['prop_display'];
				$i++;
			}
		}

		//ha hasznaljuk az extra attributumokat, akkor azokat kulon lekerdezzuk
		if (!empty($_SESSION['site_shop_is_extra_attr'])) {
			$query_attr = "
				SELECT attributes 
				FROM iShark_Shop_Products 
				WHERE product_id = $pid
			";
			$result_attr =& $mdb2->query($query_attr);
			if ($result_attr->numRows() > 0) {
				$row_attr = $result_attr->fetchRow();

				//ha nem ures, csak akkor dolgozunk vele
				if (!empty($row_attr['attributes'])) {
					$attributes = array();
					$attr_i = 0;

					//kiszedjuk az egyedi attributumokat
					$attrs = explode(";", $row_attr['attributes']);
					//szetszedjuk az attributumokbol a cimet es az infokat
					foreach($attrs as $key => $value) {
						$attr_array = explode(":", $value);
						$attributes[$attr_i]['title'] = $attr_array[0];
						if (is_array($attr_array)) {
							$attr_values = explode(",", $attr_array[1]);
							$attributes[$attr_i]['values'] = $attr_values;
						}
						$attr_i++;
					}
					$tpl->assign('attributes', $attributes);
				}
			}
		}

		//ha van akcios ar kezeles
		if (!empty($_SESSION['site_shop_actionuse'])) {
			$action_query_fields = "
				, ap.percent AS actionpercent, ROUND(ap.price) AS actionprice, sa.timer_start AS actiontstart, sa.timer_end AS actiontend 
			";
			$action_join_tables = "
				LEFT JOIN iShark_Shop_Actions_Products ap ON ap.product_id = p.product_id 
				LEFT JOIN iShark_Shop_Actions sa ON sa.action_id = ap.action_id AND 
					(sa.timer_start = '0000-00-00 00:00:00' OR (sa.timer_start < NOW() AND sa.timer_end > NOW()))
			";
		} else {
			$action_query_fields = "";
			$action_join_tables  = "";
		}

		//lekerdezzuk a termek adatait
		$query = "
			SELECT p.product_id AS pid, p.product_name AS pname, p.item_id AS item, p.product_desc AS pdesc, 
				ROUND(p.netto) AS netto, a.afa_percent AS afa, s.state_name AS state, b.amount AS amount $action_query_fields 
		";
		//ha vannak plusz mezok
		if (isset($plusfields) && is_array($plusfields) && count($plusfields) > 0) {
			$plusquery = implode(",", $plusfields);
			$query .= ", ".$plusquery;
		}
		$query .= "
			FROM iShark_Shop_Products p 
			$action_join_tables 
			LEFT JOIN iShark_Shop_State s ON p.state_id = s.state_id 
			LEFT JOIN iShark_Shop_Afa a ON p.afa = a.afa_id 
			LEFT JOIN iShark_Shop_Basket b ON b.product_id = p.product_id
			WHERE p.product_id = $pid AND p.is_active = 1 AND p.is_deleted = 0 
				AND (p.timer_start = '0000-00-00 00:00:00' OR (p.timer_start < NOW() AND p.timer_end > NOW()))
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$result = $result->fetchAll('', $rekey = true);

			//lekerdezzuk, hogy ebbol a termekbol van-e a usernek a kosaraban
			$query4 = "
				SELECT b.amount AS amount 
				FROM iShark_Shop_Basket b 
				WHERE b.product_id = $pid
			";
			if (isset($_SESSION['user_id']) || session_id()) {
				$query4 .= " AND (";
				if (isset($_SESSION['user_id'])) {
					$query4 .= "b.user_id = ".$_SESSION['user_id']." ";
					if (session_id()) {
						$query4 .= " OR ";
					}
				}
				if (session_id()) {
					$query4 .= "(b.session_id = '".session_id()."' AND b.user_id = '')";
				}
				$query4 .= ")";
			}
			$result4 = $mdb2->query($query4);
			$row4 = $result4->fetchRow();

			//lekerdezzuk a termekhez tartozo kepeket
			$query2 = "
				SELECT picture 
				FROM iShark_Shop_Products_Picture 
				WHERE product_id = $pid
			";
			$mdb2->setLimit($_SESSION['site_shop_prodpicnum']);
			$result2 =& $mdb2->query($query2);
			$pictures = array();
			if ($result2->numRows() > 0) {
				while ($row2 = $result2->fetchRow())
				{
					$pictures[] = $row2['picture'];
				}
			}

			//lekerdezzuk a termekhez tartozo dokumentumokat
			$query3 = "
				SELECT document_id AS did, document 
				FROM iShark_Shop_Products_Document 
				WHERE product_id = $pid
			";
			$mdb2->setLimit($_SESSION['site_shop_attachnum']);
			$result3 =& $mdb2->query($query3);
			if ($result3->numRows() > 0) {
			    $tpl->assign('documents', $result3->fetchAll());
			}

			//ha hasznaljuk a kapcsolhato termekeket
			if (!empty($_SESSION['site_shop_joinprod'])) {
				$query4 = "
					SELECT pj.join_id AS jid, p.product_name AS jpname, pp.picture AS jpic, p.netto AS netto, a.afa_percent AS afa 
						$action_query_fields 
					FROM iShark_Shop_Products_Join pj, iShark_Shop_Products p 
					$action_join_tables 
					LEFT JOIN iShark_Shop_Afa a ON a.afa_id = p.afa 
					LEFT JOIN iShark_Shop_Products_Picture pp ON pp.product_id = pj.join_id 
					WHERE pj.join_id = p.product_id AND pj.product_id = $pid AND p.is_active = 1 AND p.is_deleted = 0 
					GROUP BY pj.join_id 
					ORDER BY p.product_name 
				";
				$result4 =& $mdb2->query($query4);
				if ($result4->numRows() > 0) {
					$tpl->assign('joinprods', $result4->fetchAll('', $rekey = true));
				}
			}

			//ha lehet ertekelni a termeket
			if (!empty($_SESSION['site_shop_is_rating'])) {
				if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
					$cid = intval($_GET['cid']);
				} else {
					$cid = "";
				}

				//lekerdezzuk, hogy regisztralt user irhat-e
				$query_config = "
					SELECT shop_is_reguser_rating, shop_rate_minchar, shop_rate_maxchar 
					FROM iShark_Shop_Configs
				";
				$result_config =& $mdb2->query($query_config);
				if ($result_config->numRows() > 0) {
					$row_config = $result_config->fetchRow();
					$tpl->assign('shop_is_reguser_rating', $row_config['shop_is_reguser_rating']);
					$tpl->assign('shop_ratemin',           $row_config['shop_rate_minchar']);
					$tpl->assign('shop_ratemax',           $row_config['shop_rate_maxchar']);

					//ha csak regisztralt felhasznalo ertekelhet
					if (($row_config['shop_is_reguser_rating'] == 1 && isset($_SESSION['user_id'])) || $row_config['shop_is_reguser_rating'] == 0) {
						require_once 'HTML/QuickForm.php';
						require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

						$form_rating =& new HTML_QuickForm('frm_rating', 'post', 'index.php?p=shop&act=prd&cid='.$cid.'&pid='.$pid);
						$form_rating->removeAttribute('name');

						$form_rating->addElement('header', 'rating', 'rating');

						//ertekeles mezoi
						$form_rating->addElement('select',   'ratingnum', $locale->get('main_field_ratingnum'), array('1' => 1, 2, 3, 4, 5));

						//ertekeles megjegyzese
						$form_rating->addElement('textarea', 'ratingcom', $locale->get('main_field_rating_comment'));

						$form_rating->addElement('submit', 'submit', $locale->get('main_field_submit'), array('class' => "submit"));
						$form_rating->addElement('reset',  'reset',  $locale->get('main_field_reset'),  array('class' => "reset"));

						//szurok beallitasa
						$form_rating->applyFilter('__ALL__', 'trim');

						//szabalyok beallitasa
						$form_rating->addRule('ratingnum', $locale->get('main_error_ratingnum'),   'required');
						$form_rating->addRule('ratingcom', $locale->get('main_error_ratingcomm1'), 'required');
						$form_rating->addRule('ratingcom', $locale->get('main_error_ratingcomm2'), 'rangelength', array($row_config['shop_rate_minchar'], $row_config['shop_rate_maxchar']));

						if ($form_rating->validate()) {
							$form_rating->applyFilter('__ALL__', array(&$mdb2, 'escape'));

							$rating  = intval($form_rating->getSubmitValue('ratingnum'));
							$comment = $form_rating->getSubmitValue('ratingcom');
							if (isset($_SESSION['user_id'])) {
								$rating_user = $_SESSION['user_id'];
							} else {
								$rating_user = 0;
							}

							$rating_id = $mdb2->extended->getBeforeID('iShark_Shop_Products_Rating', 'rating_id', TRUE, TRUE);
							$query_insert = "
								INSERT INTO iShark_Shop_Products_Rating 
								(rating_id, product_id, user_id, rating, comment, add_date) 
								VALUES 
								($rating_id, $pid, $rating_user, $rating, '$comment', NOW())
							";
							$mdb2->exec($query_insert);

							header('Location: index.php?p='.$module_name.'&act=prd&cid='.$cid.'&pid='.$pid);
							exit;
						}

						$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
						$form_rating->accept($renderer);

						$tpl->assign('form_rating', $renderer->toArray());

						// capture the array stucture
						ob_start();
						print_r($renderer->toArray());
						$tpl->assign('static_form', ob_get_contents());
						ob_end_clean();
					}
				}

				//lekerdezzuk az eddigi ertekeleseket
				$query_ratings = "
					SELECT r.rating_id AS rid, r.rating AS rating, r.comment AS comment, r.add_date AS add_date, u.user_name AS user_name 
					FROM iShark_Shop_Products_Rating r 
					LEFT JOIN iShark_Users u ON u.user_id = r.user_id
					WHERE r.product_id = $pid 
					ORDER BY add_date
				";
				$result_ratings =& $mdb2->query($query_ratings);
				if ($result_ratings->numRows() > 0) {
					$tpl->assign('shop_ratings', $result_ratings->fetchAll('', $rekey = true));
				}

				//ha van joga torolni az ertekelest
				if (check_perm('delcom', NULL, 1, 'shop', 'index')) {
					$tpl->assign('delcom_link', 'index.php?p=shop&amp;act=delcom&amp;cid='.$cid.'&amp;pid='.$pid);
				}
			}

			//breadcrumb
			//ha van cid, akkor lekerdezzuk hozza a kategoriakat is
			if (isset($_SESSION['site_shop_is_breadcrumb']) && $_SESSION['site_shop_is_breadcrumb'] == 1) {
				if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
					$cid = intval($_GET['cid']);
					include_once $include_dir.'/function.shop.php';
					$category = get_breadcrumb_category($cid);
					$cat1 = explode(";", $category);
					$cat2 = array();
					foreach ($cat1 as $key => $value) {
						if (!empty($value)) {
							$robbant = explode("#@#", $value);
							$cat2[$robbant[0]] = $robbant[1];
						}
					}
					$cat_array = array_reverse($cat2, true);
					foreach ($cat_array as $key => $value) {
						$shop_breadcrumb->add($value, 'index.php?p='.$module_name.'&amp;act=lst&amp;cid='.$key);
					}
				}
				$shop_breadcrumb->add($result[$pid]['pname'], 'index.php?p='.$module_name.'&amp;act=prd&amp;pid='.$pid);
			}

			//atadjuk a smarty-nak a kiirando cuccokat
			$tpl->assign('tplfields', $tplfields);
			$tpl->assign('prod_data', $result);
			$tpl->assign('pictures',  $pictures);
			$tpl->assign('amount',    $row4['amount']);
			if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
				$tpl->assign('cid', intval($_GET['cid']));
			}
		} else {
		    $site_errors[] = array('text' => $locale->get('main_error_notexists'), 'link' => 'javascript:history.back(-1)');
		    return;
		}
	} else {
	    $site_errors[] = array('text' => $locale->get('main_error_notexists'), 'link' => 'javascript:history.back(-1)');
		return;
	}

	//ajax-hoz szukseges infok - ha vasarolhatnak a userek
	if (!empty($_SESSION['site_shop_userbuy'])) {
    	$ajax['link']   = "ajax.php?client=all&stub=all";
    	$ajax['script'] = "
    		function bsksend(id, name, price, attr) {
    			amount = document.getElementById('amount_'+id).value;
    			var attrs = new Array();
    			for(i = 1; i <= attr; i++) {
    				attributes = document.getElementById('attr_select_'+i);
    				attrs += '&attrs['+i+']['+attributes.name+'] = '+attributes.value;
    			}
    			HTML_AJAX.replace('target_'+id,'ajax.php?act=basket&pid='+id+'&amount='+amount+attrs);
    			HTML_AJAX.append('bsktarget','ajax.php?act=bskblock&name='+name+'&amount='+amount+'&price='+price);
    		}
    	";
	}

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_product';
} //termek adatlap vege

/**
 * ha megjegyzest akarjuk torolni
 */
if ($act == "delcom" && check_perm('delcom', NULL, 1, 'shop', 'index') && isset($_SESSION['site_shop_is_rating']) && $_SESSION['site_shop_is_rating'] == 1) {
	if (isset($_GET['rid']) && is_numeric($_GET['rid']) && isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$rid = intval($_GET['rid']);
		$pid = intval($_GET['pid']);
		if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
			$cid = intval($_GET['cid']);
		} else {
			$cid = "";
		}

		$query = "
			DELETE FROM iShark_Shop_Products_Rating 
			WHERE rating_id = $rid
		";
		$mdb2->exec($query);

		header('Location: index.php?p='.$module_name.'&act=prd&cid='.$cid.'&pid='.$pid);
		exit;
	}
}

/**
 * ha a termekek listajat mutatjuk
 */
if ($act == "lst") {
	//kategoriak listaja
	$query = "
		SELECT category_id AS cid, category_name AS cname, picture AS cpic 
		FROM iShark_Shop_Category 
		WHERE is_active = 1 AND (timer_start = '0000-00-00 00:00:00' OR (timer_start < NOW() AND timer_end > NOW())) 
	";
	if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
		$cid = intval($_GET['cid']);
		$query .= "
			AND parent = $cid 
		";
	} else {
		$query .= "
			AND parent = 0 
		";
	}
	//ha ABC szerint rendezzuk sorba
	if ($_SESSION['site_shop_ordertype'] == 1) {
		$query .= "
			ORDER BY is_preferred DESC, category_name, parent, lang
		";
	}
	//ha egyedi sorrend szerint rendezzuk
	if ($_SESSION['site_shop_ordertype'] == 2) {
		$query .= "
			ORDER BY is_preferred DESC, sortorder, parent, lang
		";
	}
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$tpl->assign('category', $result->fetchAll());
	}

	//termekek listaja
	if (isset($cid)) {
		//breadcrumb
		include_once $include_dir.'/function.shop.php';
		$category = get_breadcrumb_category($cid);
		$cat1 = explode(";", $category);
		$cat2 = array();
		foreach ($cat1 as $key => $value) {
			if (!empty($value)) {
				$robbant = explode("#@#", $value);
				$cat2[$robbant[0]] = $robbant[1];
			}
		}
		$cat_array = array_reverse($cat2, true);
		foreach ($cat_array as $key => $value) {
			if (!empty($_SESSION['site_shop_is_breadcrumb'])) {
				$shop_breadcrumb->add($value, 'index.php?p='.$module_name.'&amp;act=lst&amp;cid='.$key);
			}
		}
		$category_name = $value;

		//ha vannak plusz mezok, akkor azokbol csinalunk egy tombot, amit belerakunk a lekerdezesbe
		$query = "
			SELECT p.prop_value AS prop_value, p.prop_display AS prop_display 
			FROM iShark_Shop_Properties p, iShark_Shop_Properties_Category pc 
			WHERE (pc.category_id = $cid OR pc.category_id = 0) AND p.prop_id = pc.prop_id AND p.prop_is_list = 1
		";
		$result = $mdb2->query($query);
		$plusfields = array();
		$tplfields  = array();
		if ($result->numRows() > 0) {
			$i = 0;
			while ($row = $result->fetchRow())
			{
				$plusfields[] = $row['prop_value'];
				$tplfields[$i]['value'] = $row['prop_value'];
				$tplfields[$i]['display'] = $row['prop_display'];
				$i++;
			}
		}

		//ha lehet ertekelni a termekeket
		if (!empty($_SESSION['site_shop_is_rating'])) {
			$rating_query_fields = "
				, ROUND(AVG(rating)) AS avg_rating, COUNT(rating) AS cnt_rating 
			";
			$rating_join_tables = "
				LEFT JOIN iShark_Shop_Products_Rating r ON r.product_id = p.product_id 
			";
			$rating_group = "
				GROUP BY p.product_id
			";
		} else {
			$rating_query_fields = "";
			$rating_join_tables  = "";
			$rating_group        = "";
		}

		//ha van akcios ar kezeles
		if (!empty($_SESSION['site_shop_actionuse'])) {
			$action_query_fields = "
				, ap.percent AS actionpercent, ROUND(ap.price) AS actionprice, sa.timer_start AS actiontstart, sa.timer_end AS actiontend 
			";
			$action_join_tables = "
				LEFT JOIN iShark_Shop_Actions_Products ap ON ap.product_id = p.product_id 
				LEFT JOIN iShark_Shop_Actions sa ON sa.action_id = ap.action_id AND 
					(sa.timer_start = '0000-00-00 00:00:00' OR (sa.timer_start < NOW() AND sa.timer_end > NOW()))
			";
		} else {
			$action_query_fields = "";
			$action_join_tables  = "";
		}

		//lekerdezzuk a termekek adatait
		$query = "
			SELECT p.product_id AS pid, product_name AS pname, p.item_id AS item, p.product_desc AS pdesc, 
				ROUND(p.netto) AS netto, a.afa_percent AS afa, b.amount AS amount $rating_query_fields $action_query_fields
		";
		//ha vannak plusz mezok
		if (isset($plusfields) && is_array($plusfields) && count($plusfields) > 0) {
			$plusquery = implode(",", $plusfields);
			$query .= ", ".$plusquery;
		}
		$query .= "
			FROM iShark_Shop_Products p, iShark_Shop_Products_Category pc 
			$rating_join_tables 
			$action_join_tables 
			LEFT JOIN iShark_Shop_Afa a ON p.afa = a.afa_id 
			LEFT JOIN iShark_Shop_Basket b ON b.product_id = p.product_id 
		";
		if (isset($_SESSION['user_id']) || session_id()) {
			$query .= " AND (";
			if (isset($_SESSION['user_id'])) {
				$query .= "b.user_id = ".$_SESSION['user_id']." ";
				if (session_id()) {
					$query .= " OR ";
				}
			}
			if (session_id()) {
				$query .= "(b.session_id = '".session_id()."' AND b.user_id = '')";
			}
			$query .= ")";
		}
		$query .= "
			WHERE p.is_active = 1 AND p.is_deleted = 0 AND pc.category_id = $cid AND pc.product_id = p.product_id 
				AND (p.timer_start = '0000-00-00 00:00:00' OR (p.timer_start < NOW() AND p.timer_end > NOW())) 
				$rating_group 
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

		//lapozo
		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		//kepeket berakjuk a tombbe
		foreach ($paged_data['data'] as $key => $adat) {
			$pictures = array();
			$query = "
				SELECT picture 
				FROM iShark_Shop_Products_Picture 
				WHERE product_id = ".$adat['pid']."
			";
			$mdb2->setLimit($_SESSION['site_shop_prodpiclistnum']);
			$result =& $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$pictures[] = $row['picture'];
			}
			$adat['pictures'] = $pictures;
			$data[] = $adat;
		}

		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('tplfields', $tplfields);
		if (isset($data)) {
			$tpl->assign('page_data', $data);
		} else {
			$tpl->assign('page_data', '');
		}
		$tpl->assign('page_list', $paged_data['links']);
		$tpl->assign('cat_name',  $category_name);
		$tpl->assign('cid',       $cid);
	}

	//ajax-hoz szukseges infok - ha vasarolhatnak a userek
	if (!empty($_SESSION['site_shop_userbuy'])) {
    	$ajax['link']   = "ajax.php?client=all&stub=all";
    	$ajax['script'] = "
    		function bsksend(id, name, price) {
    			amount = document.getElementById('amount_'+id).value;
    			HTML_AJAX.replace('target_'+id,'ajax.php?act=basket&pid='+id+'&amount='+amount);
    			HTML_AJAX.append('bsktarget','ajax.php?act=bskblock&name='+name+'&amount='+amount+'&price='+price);
    			HTML_AJAX.replace('osszar','ajax.php?act=osszarszam');
    		}
    	";
	}

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop';
}

?>