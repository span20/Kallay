<?php
require_once 'includes/config.php';

require_once 'HTML/AJAX/Server.php';
$server = new HTML_AJAX_Server();
$server->handleRequest();

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "show_gallery" && isset($_REQUEST['gal_id']) && is_numeric($_REQUEST['gal_id'])) {
	$query = "
		SELECT p.realname
		FROM iShark_Galleries_Pictures AS gp
		LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
		WHERE gp.gallery_id = '".intval($_REQUEST['gal_id'])."'
		ORDER BY gp.orders ASC
	";
	$result =& $mdb2->query($query);
	$gals = $result->fetchAll();
		
	echo json_encode($gals);
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "saveResult" && isset($_SESSION['teszt_email']) && !empty($_SESSION['teszt_email']) && isset($_SESSION['teszt_name']) && !empty($_SESSION['teszt_name'])) {
	$answers = array("d", "c", "c", "c", "a", "b", "b", 7, "d", "c", "c", "b", "a", "a");
	$result = 0;
	
	if (is_array($_REQUEST["a"])) {
		foreach ($_REQUEST["a"] as $key => $value) {
			if ($value == $answers[$key]) {
				$result++;
			}
		}
	}
	
	if (intval($_REQUEST["secs"]) == -1) {
		$secs = 0;
	} else {
		$secs = intval($_REQUEST["secs"]);
	}
	
	$uemail = $mdb2->escape($_SESSION['teszt_email']);
	$uname = $mdb2->escape($_SESSION['teszt_name']);
	
	$q = "
		SELECT *
		FROM iShark_Users
		WHERE email = '".$uemail."'
	";
	$res = $mdb2->query($q);
	if ($res->numRows() > 0) {
		$t_user = $res->fetchRow();
		$_SESSION["test_user_id"] = $t_user['user_id'];
		
		$q = "
			INSERT INTO iShark_Users_Results
			(user_id, result, datum, speed)
			VALUES
			('".$t_user["user_id"]."', '".$result."', NOW(), '".$secs."')
		";
		$mdb2->exec($q);
	} else {
		$q = "
			INSERT INTO iShark_Users
			(name, email, ip_address)
			VALUES
			('".$uname."', '".$uemail."', '".$_SERVER["REMOTE_ADDR"]."')
		";
		$mdb2->exec($q);
		
		$last_user_id = $mdb2->lastInsertId();
		$_SESSION["test_user_id"] = $last_user_id;
		
		$q = "
			INSERT INTO iShark_Users_Results
			(user_id, result, datum, speed)
			VALUES
			('".$last_user_id."', '".$result."', NOW(), '".$secs."')
		";
		$mdb2->exec($q);
	}
	
	echo $result;

}

//jogosultsaghoz tartozo ajax
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "rights" && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$mid = intval($_REQUEST['id']);

	$query = "
		SELECT f.function_id AS fid, f.function_name AS fname, f.function_alias AS falias, rf.function_id AS rfid 
		FROM iShark_Functions f 
		LEFT JOIN iShark_Rights_Functions rf ON rf.function_id = f.function_id 
		WHERE f.module_id = $mid 
		ORDER BY f.function_id
	";
	$result = $mdb2->query($query);
	$i = 0;
	$functionchk = array();
	while ($row = $result->fetchRow()) {
		$functionchk[$i]['fid']    = $row['fid'];
		$functionchk[$i]['fname']  = $row['fname'];
		$functionchk[$i]['falias'] = $row['falias'];
		$functionchk[$i]['rfid']   = $row['rfid'];
		$i++;
	}

	foreach ($functionchk as $key => $item) {
		if ($item['rfid'] != 0) {
			$checked = "checked";
		} else {
			$checked = "";
		}
		echo '<input type="checkbox" name="functions[]" value="'.$item['fid'].'" checked="'.$checked.'">'.htmlentities($item['falias']).'<br>';
	}
}

//shop - termek kosarba rakasa
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "basket" && isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid']) && isset($_REQUEST['amount']) && is_numeric($_REQUEST['amount'])) {
	$pid    = intval($_REQUEST['pid']);
	$amount = intval($_REQUEST['amount']);

	$attributes = "";
	if (isset($_REQUEST['attrs']) && is_array($_REQUEST['attrs'])) {
		foreach ($_REQUEST['attrs'] as $key => $value) {
			foreach ($value as $key2 => $value2) {
				$attributes .= trim($key2).":".trim($value2).";";
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

	//lekerdezzuk az arat
	$query = "
		SELECT p.netto AS netto $action_query_fields
		FROM iShark_Shop_Products p 
		$action_join_tables 
		WHERE p.product_id = $pid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();
		//kiszamoljuk a fizetendo osszeget
		if (!empty($_SESSION['site_shop_actionuse'])) {
			if ($row['actiontstart'] == "0000-00-00 00:00:00" || $row['actiontstart'] == "NULL") {
				if ($row['actionprice'] != NULL && $row['actionprice'] != 0) {
					$price = $row['actionprice'];
				}
				elseif ($row['actionpercent'] != NULL && $row['actionpercent'] != 0) {
					$price = $row['netto']-($row['netto']*($row['actionpercent']/100));
				}
				else {
					$price = $row['netto'];
				}
			} else {
			    $price = $row['netto'];
			}
		} else {
			$price = $row['netto'];
		}

		//lekerdezzuk, hogy ez a termek ne legyen meg benne a kosarban
		if (session_id()) {
			if (isset($_SESSION['user_id'])) {
				$query2 = "
					SELECT * 
					FROM iShark_Shop_Basket 
					WHERE user_id = ".$_SESSION['user_id']." AND product_id = $pid
				";
			} else {
				$query2 = "
					SELECT * 
					FROM iShark_Shop_Basket 
					WHERE session_id = '".session_id()."' AND product_id = $pid
				";
			}
			$result2 = $mdb2->query($query2);
			//ha nincs meg ilyen azonositoval termek
			if ($result2->numRows() == 0) {
				if (isset($_SESSION['user_id'])) {
					$uid = $_SESSION['user_id'];
				} else {
					$uid = 0;
				}

				$query3 = "
					INSERT INTO iShark_Shop_Basket 
					(product_id, session_id, user_id, amount, price, add_date, mod_date, attributes) 
					VALUES 
					($pid, '".session_id()."', $uid, $amount, '$price', NOW(), NOW(), '".$attributes."')
				";
				$mdb2->exec($query3);
			}
		}
	}

	//lekerdezzuk, hogy a termekbol hany darab van a kosarban
	if (session_id()) {
		if (isset($_SESSION['user_id'])) {
			$query = "
				SELECT amount 
				FROM iShark_Shop_Basket 
				WHERE user_id = ".$_SESSION['user_id']." AND product_id = $pid
			";
		} else {
			$query = "
				SELECT amount 
				FROM iShark_Shop_Basket 
				WHERE session_id = '".session_id()."' AND product_id = $pid
			";
		}
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			require_once $lang_dir.'/modules/shop/'.$_SESSION['site_lang'].'.php';
			$row = $result->fetchRow();
			echo '<p class="amount">'.htmlentities($strShopBasketAmount1).' <span class="shop_extra">'.$row['amount'].'</span> '.htmlentities($strShopBasketAmount2).'</p>';
		}
	}
}

//shop - kosar
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "bskblock") {
	$sum = $_REQUEST['amount']*$_REQUEST['price'];

	$query = "
		SELECT count(*) AS ossz 
		FROM iShark_Shop_Basket b 
	";

	if (isset($_SESSION['user_id']) || session_id()) {
		$query .= " WHERE (";
		if (isset($_SESSION['user_id'])) {
			$query .= "b.user_id = ".$_SESSION['user_id']." ";
			if (session_id()) {
				$query .= " OR ";
			}
		}
		if (session_id()) {
			$query .= "(b.session_id = '".session_id()."' AND b.user_id = 0)";
		}
		$query .= ")";
	}

	$result =& $mdb2->query($query);
	$row = $result->fetchRow();
	if ($result->numRows() > 0) {
	    if ($row['ossz'] == 0) {
	        $row['ossz'] = 1;
	    }
	    echo "<b>".$row['ossz'].".</b> ".htmlentities($_REQUEST['name'])."<br />".htmlentities($locale->get('index_shop', 'block_basket_amount'))." ".$_REQUEST['amount']."db<br />".htmlentities($locale->get('index_shop', 'block_basket_price'))." ".$_REQUEST['price']."<br />".htmlentities($locale->get('index_shop', 'block_basket_netto'))." ".$sum."<br />";
	}
}

//shop - kosar osszr
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "osszarszam") {
	$query = "
		SELECT * 
		FROM iShark_Shop_Basket
	";
	if(isset($_SESSION['user_id'])){
		$query .= " WHERE user_id = ".$_SESSION['user_id']." ";
	}else{
		$query .= " WHERE session_id = '".session_id()."' ";
	}

	$osszar = 0;

	$result = $mdb2->query($query);
	while($row = $result->fetchRow()){
		$osszar = $osszar+($row['amount']*$row['price']);
	}

	echo "<br />".$osszar;
}

//shop - admin termek: extra mezok
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "shopprod" && isset($_REQUEST['cid'])) {
	$cids  = explode(",", $_REQUEST['cid']);
	$cids2 = array_unique($cids);

	$full_array = "
		var full_array = new Array();\n
	";

	$js_array = "
		var js_array = new Array();\n
		js_array['val'] = new Array();\n
		js_array['cat'] = new Array();\n
	";

	//lekerdezzuk az osszes plusz mezo listajat
	$query = "
		SELECT p.prop_value AS prop_value 
		FROM iShark_Shop_Properties p, iShark_Shop_Properties_Category pc 
		WHERE p.prop_id = pc.prop_id AND pc.category_id != 0 
		GROUP BY p.prop_value
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$i = 0;
		while ($row = $result->fetchRow())
		{
			$full_array .= "
				full_array[$i] = '".$row['prop_value']."';\n
			";
			$i++;
		}
	}

	$query = "
		SELECT p.prop_id AS prop_id, p.prop_type AS prop_type, p.prop_value AS prop_value, p.prop_display AS prop_display, 
			pc.category_id AS category_id 
		FROM iShark_Shop_Properties p, iShark_Shop_Properties_Category pc 
		WHERE p.prop_id = pc.prop_id 
	";
	if (is_array($cids2) && count($cids2) > 0) {
		$where = "";
		foreach ($cids2 as $key => $value) {
			if (!empty($value)) {
				$where .= $value.",";
			}
		}
		$where .= "0";
		$query .= "	
			AND pc.category_id IN ($where)
		";
	}
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$i = 0;
		while ($row = $result->fetchRow())
		{
			//echo htmlentities($row['prop_display']).'<input name="'.$row['prop_value'].'" type="'.$row['prop_type'].'"><br />';
			$js_array .= "
				js_array['val'][$i] = '".$row['prop_value']."';\n
				js_array['cat'][$i] = '".$row['category_id']."';\n
			";
			$i++;
		}
		echo '
			<script type="text/javascript">
				function enable_field()
				{
					var full_cat = document.forms[0].category;
					var temp = "";
					'.$js_array.';
					'.$full_array.';
					for (k = 0; k < full_cat.length; k++) {
						for (j = 0; j <= js_array[\'cat\'].length; j++) {
							if (full_cat.options[k].value == js_array[\'cat\'][j]) {
								temp += js_array[\'val\'][j]+",";
							}
						}
					}

					for (l = 0; l < full_array.length; l++) {
						document.forms[0].elements[full_array[l]].disabled = true;
					}

					if (temp != "null") {
						var temps = temp.split(",");
					} else {
						var temps = new Array();
					}

					for (i = 0; i < temps.length-1; i++) {
						if (document.forms[0].elements[temps[i]].disabled == true) {
							document.forms[0].elements[temps[i]].disabled = false;
						}
					}
				}
				enable_field();
			</script>
		';
	}
}

//shop - admin termek: kapcsolodo termekek
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "joinprod" && isset($_REQUEST['cid'])) {
	$cids  = $_REQUEST['cid'];
	$cids2 = substr($cid, 0, -1);

	if (isset($_REQUEST['pid']) && is_numeric($_REQUEST['pid'])) {
		$pid   = intval($_REQUEST['pid']);

		//lekerdezzuk, hogy melyik termekek vannak kivalasztva
		$query = "
			SELECT join_id 
			FROM iShark_Shop_Products_Join 
			WHERE product_id = $pid
		";
		$result =& $mdb2->query($query);
		$i = 0;
		$join_array = "
			var join_array = new Array();\n
		";
		while ($row = $result->fetchRow()) {
			$join_array .= "
				join_array[$i] = '".$row['join_id']."';\n
			";
			$i++;
		}
	}

	if (!empty($cids2)) {
		$query = "
			SELECT p.product_id AS pid, p.product_name AS pname 
			FROM iShark_Shop_Products p, iShark_Shop_Products_Category pc 
			WHERE p.product_id = pc.product_id AND p.is_active = 1 AND p.is_deleted = 0 AND pc.category_id IN ($cids2) 
		";
		//ha $pid != 0, akkor a $pid-et kivesszuk a lekerdezesbol
		if ($pid != 0) {
			$query .= "
				AND p.product_id != $pid
			";
		}
		$query .= "
			GROUP BY p.product_id
		";
	} else {
		$query = "
			SELECT p.product_id AS pid, p.product_name AS pname 
			FROM iShark_Shop_Products p 
			WHERE p.is_active = 1 AND p.is_deleted = 0 
		";
		//ha $pid != 0, akkor a $pid-et kivesszuk a lekerdezesbol
		if ($pid != 0) {
			$query .= "
				AND p.product_id != $pid
			";
		}
	}
	$query .= "
		ORDER BY p.product_name
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$options = '
			<script type="text/javascript">
				function joinprod()
				{
					var oSelect = document.getElementById(\'joinprod\');
					oSelect.remove(0);

					for(i = oSelect.options.length-1; i >= 0; i--) {
						oSelect.remove(i);
					}
		';
		while ($row = $result->fetchRow())
		{
			$options .= '
				var oOption = new Option(\''.$row['pname'].'\',\''.$row['pid'].'\');
				oSelect.add(oOption,oSelect.options[oSelect.options.length]);
			';
		}
		$options .= '
				}
				function selectprod()
				{
					'.$join_array.';
					var oSelect = document.getElementById(\'joinprod\');

					for (j = 0; j < oSelect.options.length; j++) {
						for (i = 0; i < join_array.length; i++) {
							if (join_array[i] == oSelect.options[j].value) {
								oSelect.options[j].selected = true;
							}
						}
					}
				}
				joinprod();
				selectprod();
			</script>
		';
	} else {
		$options = '
			<script type="text/javascript">
				function joinprod()
				{
					var oSelect = document.getElementById(\'joinprod\');
					oSelect.remove(0);

					for(i = oSelect.options.length-1; i >= 0; i--) {
						oSelect.remove(i);
					}
				}
				joinprod();
			</script>
		';
	}
	echo $options;
}

// galeriahoz tartozo ajax
if (isset($_REQUEST['act']) && $_REQUEST['act'] == "gallery_pic_change" && isset($_REQUEST['kid']) && is_numeric($_REQUEST['kid']) && isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
	$kid = intval($_REQUEST['kid']);
	$gid = intval($_REQUEST['gid']);
	
	$query = "
		SELECT name, description 
		FROM iShark_Galleries 
		WHERE gallery_id = $gid
	";
	$result = $mdb2->query($query);
	$row = $result->fetchRow();

	if (isset($_REQUEST['kid']) && is_numeric($_REQUEST['kid'])) {
		$kid = intval($_REQUEST['kid']);

		$aktkep_lek = "
			SELECT * 
			FROM iShark_Pictures WHERE picture_id = $kid
		";
	} else {
		$aktkep_lek = "
			SELECT gp.*, p.name AS name, p.realname AS realname , p.orders AS orders
			FROM iShark_Galleries_Pictures gp
			LEFT JOIN iShark_Pictures as p ON gp.picture_id = p.picture_id
			WHERE gp.gallery_id = $gid 
		";
		$mdb2->setLimit(1);
	}

	$akt_kep_res = $mdb2->query($aktkep_lek);
	$akt_kep     = $akt_kep_res->fetchRow();

	//kovetkezo kep
	$nextkep_lek = "
		SELECT gp.*, p.* 
		FROM iShark_Galleries_Pictures gp
		LEFT JOIN iShark_Pictures AS p ON gp.picture_id = p.picture_id
		WHERE gp.gallery_id = $gid AND p.picture_id > ".$akt_kep['picture_id']."
	";
	$mdb2->setLimit(1);
	$next_kep_res = $mdb2->query($nextkep_lek);
	if ($next_kep_res->numRows() > 0) {
		$next_kep = $next_kep_res->fetchRow();
	} else {
		$n_kep_lek = "
			SELECT gp.*, p.* 
			FROM iShark_Galleries_Pictures gp
			LEFT JOIN iShark_Pictures p ON gp.picture_id = p.picture_id
			WHERE gp.gallery_id = $gid
		";
		$mdb2->setLimit(1);
		$n_kep_res = $mdb2->query($n_kep_lek);
		$next_kep  = $n_kep_res->fetchRow();
	}

	//elozo kep
	$prevkep_lek = "
		SELECT gp.*, p.* 
		FROM iShark_Galleries_Pictures gp
		LEFT JOIN iShark_Pictures p ON gp.picture_id = p.picture_id
		WHERE gp.gallery_id = $gid AND p.picture_id < ".$akt_kep['picture_id']."
		ORDER BY p.picture_id DESC
	";
	$mdb2->setLimit(1);
	$prev_kep_res = $mdb2->query($prevkep_lek);
	if ($prev_kep_res->numRows() > 0) {
		$prev_kep = $prev_kep_res->fetchRow();
	} else {
		$p_kep_lek = "
			SELECT gp.*, p.* 
			FROM iShark_Galleries_Pictures gp
			LEFT JOIN iShark_Pictures p ON gp.picture_id = p.picture_id
			WHERE gp.gallery_id = $gid
			ORDER BY p.picture_id DESC
		";
		$mdb2->setLimit(1);
		$p_kep_res = $mdb2->query($p_kep_lek);
		$prev_kep  = $p_kep_res->fetchRow();
	}

	//ha lehet ertekelni a kepeket
	if (!empty($_SESSION['site_gallery_is_rating'])) {
		//ossz. ertekeles, ertekeles atlaga
		$rating_query = "
			SELECT COUNT(pr.rate) AS cntrate, p.rate_sum AS sumrate 
			FROM iShark_Pictures_Ratings pr, iShark_Pictures p
			WHERE p.picture_id = pr.picture_id AND pr.picture_id = ".$akt_kep['picture_id']." 
			GROUP BY p.picture_id
		";
		$result_rating = $mdb2->query($rating_query);
		$rating        = $result_rating->fetchRow();

		//felhasznalo ertekelese
		if (!empty($_SESSION['user_id'])) {
			$userrate_query = "
				SELECT rate 
				FROM iShark_Pictures_Ratings 
				WHERE user_id = ".$_SESSION['user_id']." AND picture_id = ".$akt_kep['picture_id']."
			";
			$result_userrate = $mdb2->query($userrate_query);
			$usrrate         = $result_userrate->fetchRow();

			$tpl->assign('usrrate', $usrrate['rate']);
		}

		$tpl->assign('cntrate', $rating['cntrate']);
		$tpl->assign('sumrate', $rating['sumrate']);
	}
	
	echo '
	<div style="float: left;">
		<div style="float: left; padding: 150px 0 0 50px; width: 59px; vertical-align: middle;">
			<a href="javascript:void(0);" onclick="pic_change('.$prev_kep['picture_id'].','.$gid.')" title=""><img src="'.$theme_dir.'/'.$theme.'/images/galeria_bal_nyil_nagy.gif" alt="" /></a>
		</div>
		<div style="float: left; width: 400px;">
			<img style="text-align: center;" id="nagykep" src="files/gallery/'.$akt_kep['realname'].'" alt="'.htmlentities($akt_kep['name']).'" />
			<div style="font-weight: bold;" id="nagykep_nev">'.htmlentities($akt_kep['name']).'</div>
		</div>
		<div style="float: left; padding: 150px 50px 0 0; width: 59px; vertical-align: middle;">
			<a href="javascript:void(0);" onclick="pic_change('.$next_kep['picture_id'].','.$gid.')" title=""><img src="'.$theme_dir.'/'.$theme.'/images/galeria_jobb_nyil_nagy.gif" alt="" /></a>
		</div>
	</div>
	';
	if (!empty($_SESSION['site_gallery_is_rating'])) {
	echo '
	<div>
		<tr>
			<td colspan="3" align="center" style="height: 60px;">
				Eddig <b>'.$rating['cntrate'].'</b> felhasználó értékelte ezt a képet. Értékelések átlaga: <b>'.$rating['sumrate'].'</b>';
				if ($usrrate['rate']){
					echo '<br />Az Ön értékelése: <b>'.$usrrate['rate'].'</b>';
				}else{
					echo '
					<br />
					<form method="post" action="gallery_popup.php" style="margin: 0;">
						<input type="hidden" name="gid" value="'.$gid.'">
						<input type="hidden" name="kid" value="'.$akt_kep['picture_id'].'">';
						for($i = 1; $i <= 11; $i++) {
							echo '<input type="radio" id="picrate_{$smarty.section.rateval.index}" name="picrate" value="{$smarty.section.rateval.index}" onclick="document.forms[0].submit()" />
							<label for="picrate_{$smarty.section.rateval.index}">{$smarty.section.rateval.index}</label>&nbsp;';
						}
					echo '
					</form>';
				}
			echo '
			</td>
		</tr>
	</div>';
	}
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "getVideo" && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$pic_id = intval($_REQUEST['id']);
	
	$query = "
		SELECT description 
		FROM iShark_Pictures 
		WHERE picture_id = $pic_id
	";
	$result = $mdb2->query($query);
	$row = $result->fetchRow();
	
	$row['description'] = str_replace('width="480"', 'width="650"', $row['description']);
	$row['description'] = str_replace('height="385"', 'height="432"', $row['description']);
	
	echo $row['description'];
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "getPicture" && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$pic_id = intval($_REQUEST['id']);
	
	$actGalQ = "
		SELECT gallery_id, orders
		FROM iShark_Galleries_Pictures 
		WHERE picture_id = $pic_id
	";
	$actGalR = $mdb2->query($actGalQ);
	$actGal = $actGalR->fetchRow();
	
	$nextPicQ = "
		SELECT picture_id
		FROM iShark_Galleries_Pictures 
		WHERE gallery_id = '".$actGal['gallery_id']."' AND orders > '".$actGal['orders']."'
		ORDER BY orders ASC
		LIMIT 1
	";
	$nextPicR = $mdb2->query($nextPicQ);
	$nextPic = $nextPicR->fetchRow();
	
	$query = "
		SELECT realname 
		FROM iShark_Pictures 
		WHERE picture_id = $pic_id
	";
	$result = $mdb2->query($query);
	$row = $result->fetchRow();

	echo $nextPic['picture_id'].'#files/gallery/'.$row['realname'];
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "slidePage" && isset($_REQUEST['way']) && isset($_REQUEST['actPage']) && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	
	$limit = $_REQUEST['actPage']*4;
	
	$query_pics = "
		SELECT p.picture_id, p.realname, p.name, p.description, p.width, p.height
		FROM iShark_Galleries_Pictures AS gp
		LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
		WHERE gp.gallery_id = '".$_REQUEST['id']."'
		ORDER BY gp.orders
		LIMIT ".$limit.", 4
	";
	$result_pics = $mdb2->query($query_pics);
	$allRows = $result_pics->numRows();
	$ret = "";
	$i = 1;
	while($row = $result_pics->fetchRow()) {
		if ($i < $allRows) {
			$padding = "padding-bottom: 28px;";
		} else {
			$padding = "";
		}
		$ret .= '
		<div class="slide_pic_list" style="clear: both; '.$padding.'">
			<img class="pic_link" onclick="ajaxRequest(\'slide_container\', \'ajax.php?act=getVideo&id='.$row["picture_id"].'\', 150)" src="files/gallery/tn_'.$row["realname"].'" />
		</div>
		';
		$i++;
	}
	
	echo $ret;
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "picPage" && isset($_REQUEST['way']) && isset($_REQUEST['actPage']) && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	
	$limit = $_REQUEST['actPage']*24;
	
	$query_pics = "
		SELECT p.picture_id, p.realname, p.name, p.description, p.width, p.height
		FROM iShark_Galleries_Pictures AS gp
		LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
		WHERE gp.gallery_id = '".$_REQUEST['id']."'
		ORDER BY gp.orders
		LIMIT ".$limit.", 24
	";
	$result_pics = $mdb2->query($query_pics);
	$allRows = $result_pics->numRows();
	$ret = "";
	$i = 0;
	while($row = $result_pics->fetchRow()) {
		if ($i % 3 == 0) {
			$cb = 'style="margin-left: 0px; clear: both;"';
		} else {
			$cb = "";
		}
		$ret .= '
		<div class="gal_pic_list" '.$cb.'>
			<img class="pic_link" onclick="ajaxRequest(\'big_pic\', \'ajax.php?act=getPicture&id='.$row['picture_id'].'\', \'img\')" src="files/gallery/tn_'.$row['realname'].'" />
		</div>
		';
		$i++;
	}
	
	echo $ret;
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "getContent" && isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$mdb2->query("SET NAMES 'utf8'");
	$mid = $_REQUEST["id"];
	
	$query_menu = "
		SELECT m.module_id AS modid, m.content_id AS conid, m.category_id AS catid, m.link AS link, m.is_protected AS mprot, m.picture AS mpic 
		FROM iShark_Menus m 
		WHERE m.menu_id = $mid
	";
	$result_menu =& $mdb2->query($query_menu);
	$row_menu = $result_menu->fetchRow();
	
	$query = "
		SELECT content 
		FROM iShark_Contents  
		WHERE content_id = '".$row_menu["conid"]."' AND is_active = 1
	";
	$result = $mdb2->query($query);
	$row = $result->fetchRow();
	
	echo '<div>'.$row["content"].'</div>';
}
?>