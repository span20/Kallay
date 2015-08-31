<?php

//modul neve
$module_name = "shop";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//lekerdezzuk a kosar tartalmat
$query = "
	SELECT b.amount AS amount, ROUND(b.price) AS price, p.product_name AS pname, ROUND((b.amount*b.price)) AS sum 
	FROM iShark_Shop_Basket b, iShark_Shop_Products p 
	WHERE p.product_id = b.product_id AND p.is_deleted = 0 
";
if (isset($_SESSION['user_id']) || session_id()) {
	$query .= " AND (";
	if (isset($_SESSION['user_id'])) {
		$query .= "b.user_id = '".$_SESSION['user_id']."' ";
		if (session_id()) {
			$query .= " OR ";
		}
	}
	if (session_id()) {
		$query .= "(b.session_id = '".session_id()."' AND b.user_id = '')";
	}
	$query .= ")";
}

$result = $mdb2->query($query);
if ($result->numRows() > 0) {
	$basket = array();
	$allsum = NULL;
	$i = 0;
	while($row = $result->fetchRow())
	{
		$allsum = $allsum+$row['sum'];
		$basket[$i]['amount'] = $row['amount'];
		$basket[$i]['price']  = $row['price'];
		$basket[$i]['pname']  = $row['pname'];
		$basket[$i]['sum']    = $row['sum'];
		$i++;
	}
	$tpl->assign('basket', $basket);
	$tpl->assign('allsum', $allsum);
}

//megadjuk a tpl file nevet, amit atadunk az index.php-nek
$acttpl = 'shop_basket_block';

?>