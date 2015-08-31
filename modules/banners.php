<?php

/**
 * a flash-ben a kovetkezo kodot kell megadni a kattintasok meresehez:
 * 
 * on(release){
 *     getURL(_root.clickTAG,"_root.clickTARGET");
 * }
 * 
 */
//include_once $lang_dir.'/modules/banners/'.$_SESSION['site_lang'].'.php';;

if (isset($_GET['bid']) && is_numeric($_GET['bid']) && isset($_GET['mid']) && is_numeric($_GET['mid']) && isset($_GET['pid']) && is_numeric($_GET['pid'])) {
	$bid = intval($_GET['bid']);
	$mid = intval($_GET['mid']);
	$pid = intval($_GET['pid']);

	$query = "
		SELECT banner_link 
		FROM iShark_Banners 
		WHERE banner_id = $bid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$banner_link = $row['banner_link'];
		}
		//noveljuk a kattintasok szamat
		$query = "
			UPDATE iShark_Banners_Menus_Places 
			SET click_count = click_count + 1 
			WHERE banner_id = $bid AND place_id = $pid AND menu_id = $mid
		";
		$mdb2->exec($query);

		//beirjuk a banner stat tablaba az adatokat
		if (!empty($_SESSION['user_id'])) {
		    $user_id = intval($_SESSION['user_id']);
		} else {
		    $user_id = 0;
		}
		$clicks_id = $mdb2->extended->getBeforeID('iShark_Banners_Clicks', 'clicks_id', TRUE, TRUE);
		$query = "
			INSERT INTO iShark_Banners_Clicks
			(clicks_id, banner_id, owner_id, menu_id, ip, date, user_id) 
			VALUES 
			($clicks_id, $bid, $pid, $mid, '".get_ip()."', NOW(), $user_id)
		";
		$mdb2->exec($query);

		header('Location: '.$banner_link);
		exit;
	} else {
		$acttpl = "error";
		$tpl->assign('errormsg', $strBannersErrorNotBanner);
		return;
	}
} else {
	$acttpl = "error";
	$tpl->assign('errormsg', $strBannersErrorNotBanner);
	return;
}

?>
