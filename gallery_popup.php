<?php

include_once 'includes/config.php';
include_once 'includes/functions.php';

// Szavazatok figyelese cookival
$cookie_tmp = array();
if (!empty($_SESSION['site_gallery_is_rating'])) {
	if ( !empty( $_COOKIE['pic_is_rated'] ) ) {
		foreach ( $_COOKIE['pic_is_rated'] as $key => $value ) {
			$cookie_tmp[$key] = $value;
		}
	}

	//ha ertekeli a kepet
	if ( !empty($_POST['picrate']) && is_numeric($_POST['picrate']) && !empty($_POST['kid']) && is_numeric($_POST['kid']) && empty( $cookie_tmp[$_POST['kid']] ) ) {
		if ( empty( $_SESSION['user_id'] ) ) {
			$userid = 0;
		}
		else {
			$userid = $_SESSION['user_id'];
		}
		$kid     = intval($_POST['kid']);
		$picrate = intval($_POST['picrate']);
	
		$query = "
			INSERT INTO iShark_Pictures_Ratings 
			(picture_id, rate, user_id) 
			VALUES 
			($kid, $picrate, ".$userid.")
		";
		$mdb2->exec($query);
		$query = "
			UPDATE iShark_Pictures 
			SET rate_sum = rate_sum + $picrate 
			WHERE picture_id = $kid
		";
		$mdb2->exec($query);
		setcookie( "pic_is_rated[".$kid."]", 1, time() + 900 );
		$cookie_tmp[$kid] = 1;
	}
}

if (isset($_REQUEST['gid']) && is_numeric($_REQUEST['gid'])) {
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
			SELECT gp.*, p.name AS name, p.realname AS realname 
			FROM iShark_Galleries_Pictures gp
			LEFT JOIN iShark_Pictures as p ON gp.picture_id = p.picture_id
			WHERE gp.gallery_id = $gid 
			ORDER BY p.add_date ASC
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
		ORDER BY p.picture_id ASC
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
			ORDER BY p.picture_id ASC
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
			SELECT COUNT(rate) AS cntrate, ROUND(AVG(rate), 2) AS avgrate 
			FROM iShark_Pictures_Ratings 
			WHERE picture_id = ".$akt_kep['picture_id']."
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
		$tpl->assign('avgrate', $rating['avgrate']);
	}

	//kiskepek listaja
	$osszkepszel = 0;

	$keplek = "
		SELECT iShark_Galleries_Pictures.*, iShark_Pictures.* 
		FROM iShark_Galleries_Pictures
		LEFT JOIN iShark_Pictures ON iShark_Galleries_Pictures.picture_id = iShark_Pictures.picture_id
		WHERE iShark_Galleries_Pictures.gallery_id = $gid
	";
	$kep_res = $mdb2->query($keplek);
	while($kepek = $kep_res->fetchRow()) {
		$aktkepszel  = getImageSize($_SESSION['site_galerydir']."/tn_".$kepek['realname']);
		$osszkepszel = $osszkepszel+$aktkepszel[0];
		$hivatkozas  = 'gallery_popup.php?gid='.$gid.'&amp;kid='.$kepek['picture_id'];
		$kiskepek[] = array(
				"name" => $kepek['realname'],
				"pid"  => $kepek['picture_id'],
				"gid"  => $gid,
			);
		
	}

	//ajax-hoz szukseges infok
    $ajax['link']   = "ajax.php?client=all&stub=all";
    $ajax['script'] = "
        function pic_change(kid,gid) {
            HTML_AJAX.replace('target','ajax.php?act=gallery_pic_change&kid='+kid+'&gid='+gid);
        }
    ";
	
	$tpl->assign('ajax',           $ajax);
	$tpl->assign('aktkep',         $akt_kep['realname']);
	$tpl->assign('aktkep_nev',     $akt_kep['name']);
	$tpl->assign('gid',            $gid);
	$tpl->assign('kid',            $akt_kep['picture_id']);
	$tpl->assign('kovkep',         $next_kep['picture_id']);
	$tpl->assign('prevkep',        $prev_kep['picture_id']);
	$tpl->assign('galeria_nev',    $row['name']);
	$tpl->assign('galeria_leiras', $row['description']);
	$tpl->assign('kiskepek',       $kiskepek);
	$tpl->assign('osszkepszel',    $osszkepszel);
}

$tpl->display('gallery_popup.tpl');

?>