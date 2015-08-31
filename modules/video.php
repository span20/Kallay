<?php
//modul neve
$module_name = "video";

include_once "block_account.php";

if (isset($_REQUEST["submitted"]) && isset($_SESSION["user_id"])) {
	if (!empty($_FILES["vidfile"]["tmp_name"])) {
		$ext = substr($_FILES["vidfile"]["name"], -3);
		if ($ext == "avi" || $ext == "mpg" || $ext == "flv" || $ext == "mp4" || $ext == "wmv") {
			$file_name_safe = substr(preg_replace("/[^a-zA-Z0-9_]/", "", $_FILES["vidfile"]["name"]), 0, -3);
			$filename = $file_name_safe."_".time().".".$ext;
			if (move_uploaded_file($_FILES["vidfile"]["tmp_name"], 'files/videos/'.$filename)) {
				
				$q = "
					INSERT INTO iShark_User_Videos
					(user_id, videofile, datum)
					VALUES
					('".$_SESSION["user_id"]."', '".$filename."', NOW())
				";
				$mdb2->exec($q);
				header('Location: index.php?p=video&error=4');
			} else {
				header('Location: index.php?p=video&error=2');
			}
		} else {
			header('Location: index.php?p=video&error=3');
		}
	} else {
		header('Location: index.php?p=video&error=1');
	}
}

if (isset($_SESSION["user_id"])) {
	$q = "
		SELECT *
		FROM iShark_User_Videos
		WHERE user_id = '".intval($_SESSION["user_id"])."'		
	";
	$res = $mdb2->query($q);
	$tpl->assign('uvids', $res->fetchAll());
}

$acttpl = 'video';
?>