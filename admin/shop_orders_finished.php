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
			$fieldorder   = " ss.shipping_text ";
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
		$userfilter          = " AND of.user_id = '$usr_fil' ";
		$tpl->assign('usrselect', $usrselect);
	} else {
		$usr_fil    = "";
		$userfilter = "";
	}

	$all_select = array('all' => $locale->get('finished_all_orders'));

	$query = "
		SELECT u.user_id, u.user_name, o.user_id 
		FROM iShark_Users u 
		LEFT JOIN iShark_Shop_Orders_Finished o ON o.user_id = u.user_id 
		LEFT JOIN iShark_Shop_Orders_Products op ON op.finished_id = o.finished_id 
		WHERE o.user_id IS NOT NULL AND op.status != 1 
		ORDER BY u.user_name
	";
	$result =& $mdb2->query($query);
	$row = $result->fetchAll('', $rekey = true);

	$user_select = $all_select + $row;
	$tpl->assign('user_select', $user_select);*/
	$userfilter = "";
	//felhasznaloszûrés vége

/**
 * ha megnezzuk a rendelest
 */
if ($sub_act == "mod") {
	if (isset($_REQUEST['fid']) && is_numeric($_REQUEST['fid']) && isset($_REQUEST['oid']) && is_numeric($_REQUEST['oid'])) {
		$oid = intval($_REQUEST['oid']);
		$fid = intval($_REQUEST['fid']);

		//breadcrumb
		$breadcrumb->add($locale->get('finished_title'), 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;fid='.$fid);

		$query = "
			SELECT of.finished_id AS fid, of.finished_date AS odate, u.user_name AS uname, nu.user_name AS nuname, 
				of.phone_mobile AS mphone, of.post_address AS paddr, of.ship_address AS saddr, of.comment AS ocom, 
				of.shipping AS shipping, u.user_id AS uid 
			FROM iShark_Shop_Orders_Finished of 
			LEFT JOIN iShark_Users u ON u.user_id = of.user_id 
			LEFT JOIN iShark_Shop_Users_Notreg nu ON of.nuser_id = nu.nuser_id 
			WHERE of.finished_id = $fid
		";
		$result =& $mdb2->query($query);
		$order_data = array();
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();
			$order_data[0]['oid']    = $oid;
			$order_data[0]['odate']  = $row['odate'];
			$order_data[0]['uname']  = $row['uname'];
			$order_data[0]['nuname'] = $row['nuname'];
			$order_data[0]['mphone'] = $row['mphone'];
			$order_data[0]['ocom']   = $row['ocom'];
			$order_data[0]['paddr']  = $row['paddr'];
			$order_data[0]['saddr']  = $row['saddr'];
			$order_data[0]['ship']   = $row['shipping'];

			//lekerdezzuk a rendeleshez tartozo termekek listajat
			$query = "
				SELECT op.op_id AS opid, op.product_id AS pid, op.amount AS amount, op.price AS price, of.finished_date AS adate, 
					p.product_name AS pname, p.item_id AS item, op.order_id AS oid, op.attributes AS attr, 
					CASE status 
						WHEN '2' THEN '".$locale->get('finished_field_finished')."' 
						ELSE '".$locale->get('finished_field_deleted')."'
					END AS status
				FROM iShark_Shop_Orders_Finished of, iShark_Shop_Orders o, iShark_Shop_Orders_Products op 
				LEFT JOIN iShark_Shop_Products p ON p.product_id = op.product_id 
				WHERE of.finished_id = op.finished_id AND o.order_id = $oid AND o.order_id = op.order_id AND op.status != 1 
				ORDER BY p.product_name
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
			    $tpl->assign('products', $result->fetchAll('', $rekey = true));
			}

			//lekerdezzuk a tobbi rendeleshez tartozo termekek listajat, amiket ugyanekkor teljesitettek
			$query = "
				SELECT op.op_id AS opid, op.product_id AS pid, op.amount AS amount, op.price AS price, of.finished_date AS adate, 
					p.product_name AS pname, p.item_id AS item, op.order_id AS oid, op.attributes AS attr, 
					CASE status 
						WHEN '2' THEN '".$locale->get('finished_field_finished')."' 
						ELSE '".$locale->get('finished_field_deleted')."'
					END AS status
				FROM iShark_Shop_Orders_Finished of, iShark_Shop_Orders o, iShark_Shop_Orders_Products op 
				LEFT JOIN iShark_Shop_Products p ON p.product_id = op.product_id 
				WHERE of.finished_id = op.finished_id AND op.finished_id = $fid AND o.order_id != $oid AND o.order_id = op.order_id AND op.status != 1 
				ORDER BY op.order_id, p.product_name
			";
			$result =& $mdb2->query($query);
			if ($result->numRows() > 0) {
			    $tpl->assign('oldproducts', $result->fetchAll('', $rekey = true));
			}

			//valtozok atadasa a template-nek
			$tpl->assign('lang_title2', $strAdminShopHeaderOrdersmod2);
			$tpl->assign('order_data',  $order_data);

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'shop_orders_finished';
		} else {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('finished_error_not_exists'));
			return;
		}
	} else {
		$acttpl = 'error';
		$tpl->assign('errormsg', $locale->get('finished_error_not_exists'));
		return;
	}
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	$query = "
		(
			SELECT op.order_id AS oid, of.finished_id AS fid, u.user_name AS uname, of.finished_date AS odate, of.shipping AS stext 
			FROM iShark_Shop_Orders_Finished of 
			LEFT JOIN iShark_Shop_Orders_Products op ON op.finished_id = of.finished_id 
			LEFT JOIN iShark_Users u ON u.user_id = of.user_id 
			WHERE op.status != 1 AND u.user_name IS NOT NULL $userfilter 
			GROUP BY op.order_id, op.finished_id 
		) 
		UNION 
		(
			SELECT op.order_id AS oid, of.finished_id AS fid, nu.user_name AS uname, of.finished_date AS odate, of.shipping AS stext 
			FROM iShark_Shop_Orders_Finished of 
			LEFT JOIN iShark_Shop_Orders_Products op ON op.finished_id = of.finished_id 
			LEFT JOIN iShark_Users u ON u.user_id = of.user_id 
			LEFT JOIN iShark_Shop_Users_Notreg nu ON of.nuser_id = nu.nuser_id 
			WHERE op.status != 1 AND nu.user_name IS NOT NULL $userfilter 
			GROUP BY op.order_id, op.finished_id
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
	$acttpl = 'shop_orders_finished_list';
}

?>