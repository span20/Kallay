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
$ordselect1   = "";
$ordselect2   = "";
if (isset($_REQUEST['field_order']) && is_numeric($_REQUEST['field_order']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
	$field_order = intval($_REQUEST['field_order']);
	$ord   		 = $_REQUEST['ord'];

	switch ($field_order) {
		case 1:
			$fieldorder   = " oid ";
			$fieldselect1 = "selected";
			break;
		case 2:
			$fieldorder   = " uname ";
			$fieldselect2 = "selected";
			break;
		case 3:
			$fieldorder   = " odate ";
			$fieldselect3 = "selected";
			break;
		case 4:
			$fieldorder   = " stext ";
			$fieldselect4 = "selected";
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
	$field_order = "";
	$ord         = "";
	$fieldorder  = " oid";
	$order       = "DESC";
}

$tpl->assign('fieldselect1', $fieldselect1);
$tpl->assign('fieldselect2', $fieldselect2);
$tpl->assign('fieldselect3', $fieldselect3);
$tpl->assign('fieldselect4', $fieldselect4);
$tpl->assign('ordselect1',   $ordselect1);
$tpl->assign('ordselect2',   $ordselect2);
//rendezes vege

	//felhasznalok szurese
	/*if (isset($_REQUEST['usr_fil']) && is_numeric($_REQUEST['usr_fil'])) {
		$usr_fil             = intval($_REQUEST['usr_fil']);
		$usrselect[$usr_fil] = "selected";
		$userfilter          = " AND u.user_id = '$usr_fil' ";
		$tpl->assign('usrselect', $usrselect);
	} else {
		$usr_fil    = "";
		$userfilter = "";
	}

	$all_select = array('all' => $locale->get('orders_field_all_order'));

	$query = "
		(
			SELECT u.user_id AS uid, u.user_name AS uname 
			FROM iShark_Users u 
			LEFT JOIN iShark_Shop_Orders o ON o.user_id = u.user_id 
			LEFT JOIN iShark_Shop_Orders_Products op ON op.order_id = o.order_id 
			WHERE o.user_id IS NOT NULL AND op.status = 1 
		) 
		UNION 
		(
			SELECT u.nuser_id AS nuid, u.user_name AS uname 
			FROM iShark_Shop_Users_Notreg u 
			LEFT JOIN iShark_Shop_Orders o ON o.nuser_id = u.nuser_id 
			LEFT JOIN iShark_Shop_Orders_Products op ON op.order_id = o.order_id 
			WHERE o.nuser_id IS NOT NULL AND op.status = 1 
		)
		ORDER BY uname
	";
	$result =& $mdb2->query($query);
	$row = $result->fetchAll('', $rekey = true);
	print_r($row);

	$user_select = $all_select + $row;
	$tpl->assign('user_select', $user_select);*/
	$usr_fil    = "";
	$userfilter = "";
	//felhasznaloszûrés vége

/**
 * termek hozzadasa a rendeleshez
 */
if ($sub_act == "add") {
	if (isset($_GET['oid']) & is_numeric($_GET['oid']) && isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$pid         = intval($_GET['pid']);
		$oid         = intval($_GET['oid']);
		$field_order = (isset($_GET['field_order'])) ? $_GET['field_order'] : '';
		$ord         = (isset($_GET['ord'])) ? $_GET['ord'] : '';
		$page_id     = (isset($_GET['pageID'])) ? $_GET['pageID'] : 1;
		$usr_fil     = (isset($_GET['usr_fil'])) ? $_GET['usr_fil'] : '';

		//lekerdezzuk a termek adatait
		$query = "
			SELECT p.product_id AS pid, p.netto AS netto, p.state_id AS state 
			FROM iShark_Shop_Products p 
			WHERE p.product_id = $pid AND p.is_active = 1 AND p.is_deleted = 0
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();

			//hozzaadjuk a termeket a rendeleshez
			$query2 = "
				INSERT INTO iShark_Shop_Orders_Products 
				(order_id, product_id, amount, price, status, state_id) 
				VALUES 
				($oid, $pid, 1, '".$row['netto']."', 1, '".$row['state']."')
			";
			$mdb2->exec($query2);
		}

		header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=mod&oid='.$oid.'&field_order='.$field_order.'&ord='.$ord.'&pageID='.$page_id.'&usr_fil='.$usr_fil);
		exit;
	}
}

/**
 * ha megnezzuk a rendelest
 */
if ($sub_act == "mod") {
	if (isset($_REQUEST['oid']) && is_numeric($_REQUEST['oid'])) {
		$oid = intval($_REQUEST['oid']);

		//breadcrumb
		$breadcrumb->add($locale->get('orders_title'), 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;oid='.$oid);

		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		$form_orders =& new HTML_QuickForm('frm_shop', 'post', 'admin.php?p='.$module_name);
		$form_orders->removeAttribute('name');

		$form_orders->setRequiredNote($locale->get('orders_form_required_note'));

		$form_orders->addElement('header', 'orders', $locale->get('orders_form_header'));
		$form_orders->addElement('hidden', 'act',     $page);
		$form_orders->addElement('hidden', 'sub_act', $sub_act);
		$form_orders->addElement('hidden', 'oid',     $oid);

		//szurok beallitasa
		$form_orders->applyFilter('__ALL__', 'trim');

		$query = "
			SELECT o.order_id AS oid, o.order_date AS odate, u.user_id AS uid, o.nuser_id AS nuid, nu.user_name AS nuname, u.user_name AS uname, 
				o.phone_mobile AS mphone, o.post_address AS paddr, o.ship_address AS saddr, o.comment AS ocom, sp.shipping_id AS sid, u.user_id AS uid, 
				u.email AS email, nu.email AS nuemail
			FROM iShark_Shop_Orders o 
			LEFT JOIN iShark_Users u ON u.user_id = o.user_id 
			LEFT JOIN iShark_Shop_Users_Notreg nu ON o.nuser_id = nu.nuser_id 
			LEFT JOIN iShark_Shop_Configs_Shipping sp ON sp.shipping_id = o.shipping 
			WHERE o.order_id = $oid
		";
		$result =& $mdb2->query($query);
		$order_data = array();
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();
			if (is_numeric($row['uid'])) {
				$uid = $row['uid'];
				$order_data[0]['uname'] = $row['uname'];
				$order_data[0]['email'] = $row['email'];
			} else {
				$uid = 0;
			}
			if (is_numeric($row['nuid'])) {
				$nuid = $row['nuid'];
				$order_data[0]['uname'] = $row['nuname'];
				$order_data[0]['email'] = $row['nuemail'];
			} else {
				$nuid = 0;
			}
			$comment                 = $mdb2->escape($row['ocom']);
			$pmobile                 = $row['mphone'];
			$order_data[0]['oid']    = $row['oid'];
			$order_data[0]['odate']  = $row['odate'];
			$order_data[0]['email']  = $row['email'];
			$order_data[0]['mphone'] = $pmobile;
			$order_data[0]['ocom']   = $comment;

			//alapertelmezett ertekek beallitasa
			$form_orders->setDefaults(array(
				'shipselect' => $row['saddr'],
				'postselect' => $row['paddr'],
				'shipping'   => $row['sid']
				)
			);

			//lekerdezzuk, hogy e-mail cimhez keruljon-e automatikusan targy is
			$query_mailsubj = "
				SELECT shop_mailsubject AS mailsubj
				FROM iShark_Shop_Configs
			";
			$result_mailsubj =& $mdb2->query($query_mailsubj);
			$row_mailsubj = $result_mailsubj->fetchRow();
			if (!empty($row_mailsubj['mailsubj'])) {
				$order_data[0]['mailsubj'] = $row_mailsubj['mailsubj'];
			}

			//lekerdezzuk a szallitasi cimeket - ha regisztralt felhasznalo
			if (isset($uid) && is_numeric($uid)) {
				$query2 = "
					SELECT a.address_id AS aid, a.city AS city, a.zipcode AS zip, a.address AS address, c.country_name AS cname, 
						c.country_id AS cid 
					FROM iShark_Shop_Address a, iShark_Country c 
					WHERE a.country_id = c.country_id AND a.user_id = $uid
				";
				$result2 =& $mdb2->query($query2);
				//ha van mar rogzitett cime, akkor kiirjuk a cimeket, hogy valaszthasson kozuluk vagy modosithassa azokat
				$address_select = array();
				if ($result2->numRows() > 0) {
					while ($row2 = $result2->fetchRow())
					{
						$address_select[$row2['zip']." ".$row2['city'].", ".$row2['address']." - ".$row2['cname']] = $row2['zip']." ".$row2['city'].", ".$row2['address']." - ".$row2['cname'];
					}
				}
			}

			//lekerdezzuk a szallitasi cimeket - ha nem regisztralt felhasznalo
			if (isset($nuid) && is_numeric($nuid)) {
				$address_select = array();
				$address_select[$row['saddr']] = $row['saddr'];
				$address_select[$row['paddr']] = $row['paddr'];
			}
			$shipselect = $form_orders->addElement('select', 'shipselect', $locale->get('orders_field_address_shipping'), $address_select, array('id' => 'shipselect'));
			$postselect = $form_orders->addElement('select', 'postselect', $locale->get('orders_field_address_postal'), $address_select, array('id' => 'postselect'));

			//lekerdezzuk a szallitasi modokat
			$query3 = "
				SELECT shipping_id, shipping_text 
				FROM iShark_Shop_Configs_Shipping 
				WHERE shipping_text != '' 
				ORDER BY shipping_id
			";
			$result3 =& $mdb2->query($query3);
			if ($result3->numRows() > 0) {
				$form_orders->addElement('select', 'shipping', $locale->get('orders_field_paymethod'), $result3->fetchAll('', $rekey = true));
			}

			//allapotok listaja, ha engedelyezve van az allapot hasznalata
			if (!empty($_SESSION['site_shop_stateuse'])) {
				$query = "
					SELECT state_id, state_name 
					FROM iShark_Shop_State 
					ORDER BY state_id
				";
				$result_state =& $mdb2->query($query);
				$tpl->assign('state_array', $result_state->fetchAll('', $rekey = true));
			}

			//lekerdezzuk a rendeleshez tartozo termekek listajat
			$query = "
				SELECT op.op_id AS opid, op.product_id AS pid, op.amount AS amount, op.price AS price, o.order_date AS adate, 
					p.product_name AS pname, p.item_id AS item, op.state_id AS sid, op.attributes AS attr 
				FROM iShark_Shop_Orders o, iShark_Shop_Orders_Products op 
				LEFT JOIN iShark_Shop_Products p ON p.product_id = op.product_id 
				LEFT JOIN iShark_Shop_State s ON s.state_id = p.state_id 
				WHERE op.order_id = $oid AND o.order_id = op.order_id AND op.status = 1 AND p.is_active = 1 AND p.is_deleted = 0 
				ORDER BY p.product_name
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
			    $tpl->assign('products', $result->fetchAll('', $rekey = true));
			}

			//lekerdezzuk a termekek listajat, ezeket adhatjuk hozza a rendeleshez
			$query = "
				SELECT p.product_id AS pid, p.product_name AS pname 
				FROM iShark_Shop_Products p 
				LEFT JOIN iShark_Shop_Orders_Products op ON op.order_id = $oid AND op.product_id = p.product_id 
				WHERE op.product_id IS NULL AND p.is_active = 1 AND p.is_deleted = 0
				ORDER BY p.product_name
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
				$tpl->assign('products_add',   $result->fetchAll('', $rekey = true));
				$tpl->assign('order_add_link', 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;oid='.$oid.'&amp;actfield_order='.$field_order.'&amp;ord='.$ord.'&amp;pageID='.$page_id.'&amp;user_fil='.$usr_fil);
			}

			//ha regisztralt a user, akkor megnezzuk, hogy van-e regebbi megrendelesbol termeke
			if (isset($uid) && $uid != NULL && $uid = 0) {
				$query = "
					SELECT o.order_id AS oid, op.op_id AS opid, op.product_id AS pid, op.amount AS amount, op.price AS price, o.order_date AS adate, 
						p.product_name AS pname, p.item_id AS item, op.state_id AS sid, op.attributes AS attr 
					FROM iShark_Shop_Orders o, iShark_Shop_Orders_Products op 
					LEFT JOIN iShark_Shop_Products p ON p.product_id = op.product_id 
					LEFT JOIN iShark_Shop_State s ON s.state_id = p.state_id 
					WHERE o.user_id = $uid AND o.order_id= op.order_id AND op.order_id != $oid AND op.status = 1 AND p.is_active = 1 
						AND p.is_deleted = 0 
					ORDER BY o.order_date, o.order_id, p.product_name
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
				    $tpl->assign('oldproducts', $result->fetchAll(', $rekey = true'));
				}
			}

			if ($form_orders->validate()) {
				$form_orders->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$oid        = intval($form_orders->getSubmitValue('oid'));
				$shipselect = $form_orders->getSubmitValue('shipselect');
				$postselect = $form_orders->getSubmitValue('postselect');
				$shipping   = intval($form_orders->getSubmitValue('shipping'));

				$query = "
					UPDATE iShark_Shop_Orders 
					SET post_address = '".$postselect."',
						ship_address = '".$shipselect."',
						shipping     = $shipping
					WHERE order_id = $oid
				";
				$mdb2->exec($query);

				//lekerdezzuk a fizetesi modot (mashogy nem tudjuk atadni)
				$query = "
					SELECT shipping_text 
					FROM iShark_Shop_Configs_Shipping 
					WHERE shipping_id = $shipping
				";
				$result =& $mdb2->query($query);
				if ($result->numRows() > 0) {
					$row = $result->fetchRow();
					$shiptext = $row['shipping_text'];
				} else {
					$shiptext = "";
				}

				//beszurjuk a lezart rendelesek tablajaba
				$finished_id = $mdb2->extended->getBeforeID('iShark_Shop_Orders_Finished', 'finished_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Shop_Orders_Finished 
					(finished_id, user_id, nuser_id, finished_date, comment, post_address, ship_address, phone_mobile, shipping) 
					VALUES 
					($finished_id, $uid, $nuid, NOW(), '".$comment."', '".$postselect."', '".$shipselect."', '".$pmobile."', '".$shiptext."')
				";
				$mdb2->exec($query);
				$last_finished_id = $mdb2->extended->getAfterID($finished_id, 'iShark_Shop_Orders_Finished', 'finished_id');

				if ($form_orders->getSubmitValue('amount') && is_array($form_orders->getSubmitValue('amount'))) {
					$state  = $form_orders->getSubmitValue('state');
					$delete = $form_orders->getSubmitValue('delete');
					$finish = $form_orders->getSubmitValue('finish');
					foreach ($form_orders->getSubmitValue('amount') as $key => $value) {
						if (is_numeric($key) && is_numeric($value)) {
							//modositjuk a megrendelt termekeket
							$query = "
								UPDATE iShark_Shop_Orders_Products 
								SET amount   = $value,
									state_id = '".$state[$key]."'
							";
							//ha legalabb az egyik checkbox be van pipalva
							if (isset($finish[$key]) || isset($delete[$key])) {
								//ha teljesitettre allitjuk a termeket
								if (isset($finish[$key])) {
									$query .= "
										, status = 2, finished_id = $last_finished_id
									";
								} else {
									$query .= "
										, status = 3, finished_id = $last_finished_id
									";
								}
							}
							$query .= "
								WHERE op_id = $key
							";
							$mdb2->exec($query);
						}
					}
				}

				//loggolas
				logger($page.'_'.$sub_act);

				//"fagyasztjuk" a form-ot
				$form_orders->freeze();

				//visszadobjuk a lista oldalra
				header('Location: admin.php?p='.$module_name.'&act='.$page.'&field_order='.$field_order.'&ord='.$ord.'&pageID='.$page_id.'&usr_fil='.$usr_fil);
				exit;
			}

			$form_orders->addElement('submit', 'submit', $locale->get('orders_form_submit'), 'class="submit"');
			$form_orders->addElement('reset',  'reset',  $locale->get('orders_form_reset'),  'class="reset"');

			$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
			$form_orders->accept($renderer);

			//valtozok atadasa a template-nek
			$tpl->assign('order_data',  $order_data);
			$tpl->assign('form_orders', $renderer->toArray());

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_array', ob_get_contents());
			ob_end_clean();

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'shop_orders';
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('orders_error_notexists'));
			return;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('orders_error_notexists'));
		return;
	}
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	$query = "
		(
			SELECT o.order_id AS oid, u.user_name AS uname, o.order_date AS odate, ss.shipping_text AS stext 
			FROM iShark_Shop_Orders o 
			LEFT JOIN iShark_Shop_Orders_Products op ON op.order_id = o.order_id 
			LEFT JOIN iShark_Users u ON u.user_id = o.user_id 
			LEFT JOIN iShark_Shop_Configs_Shipping ss ON o.shipping = ss.shipping_id 
			WHERE op.status = 1 AND u.user_name IS NOT NULL $userfilter 
			GROUP BY o.order_id
		) 
		UNION 
		(
			SELECT o.order_id AS oid, nu.user_name AS uname, o.order_date AS odate, ss.shipping_text AS stext 
			FROM iShark_Shop_Orders o 
			LEFT JOIN iShark_Shop_Orders_Products op ON op.order_id = o.order_id 
			LEFT JOIN iShark_Shop_Users_Notreg nu ON o.nuser_id = nu.nuser_id
			LEFT JOIN iShark_Shop_Configs_Shipping ss ON o.shipping = ss.shipping_id 
			WHERE op.status = 1 AND nu.user_name IS NOT NULL $userfilter 
			GROUP BY o.order_id
		) 
		ORDER BY $fieldorder $order 
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('page_data', $paged_data['data']);
	$tpl->assign('page_list', $paged_data['links']);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'shop_orders_list';
}

?>