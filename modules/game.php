<?php
//modul neve
$module_name = "game";
if (isset($_SESSION["user_id"])) {
	$bodyonload[]= "init()";
}

include_once "block_account.php";
if (isset($_REQUEST["newgame"])) {
	$acttpl = 'game2';
} else {
	$acttpl = 'game';
}
?>