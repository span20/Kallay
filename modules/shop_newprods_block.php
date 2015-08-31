<?php

//modul neve
$module_name = "shop";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//lekerdezzuk a szukseges beallitasokat
$query = "
	SELECT shop_newprodsnum 
	FROM iShark_Shop_Configs 
";
$result =& $mdb2->query($query);
if ($result->numRows() > 0) {
	$row = $result->fetchRow();
	$newprodsnum = $row['shop_newprodsnum'];
} else {
	$newprodsnum = 0;
}

if ($newprodsnum != 0) {
	$query = "
		SELECT p.product_id AS pid, p.product_name AS pname, pp.picture AS pic, p.netto AS netto, a.afa_percent AS afa 
		FROM iShark_Shop_Products p 
		LEFT JOIN iShark_Shop_Afa a ON a.afa_id = p.afa
		LEFT JOIN iShark_Shop_Products_Picture pp ON pp.product_id = p.product_id 
		WHERE is_active = 1 AND is_deleted = 0 
		ORDER BY p.add_date DESC
	";
	$mdb2->setLimit($newprodsnum);
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$tpl->assign('newprods', $result->fetchAll('', $rekey = true));
	}
}

$tpl->assign('newprodsnum', $newprodsnum);

//megadjuk a tpl file nevet, amit atadunk az index.php-nek
$acttpl = 'shop_newprods_block';

?>