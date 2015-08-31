<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "calendar";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

$tpl->assign('self_calendar', $module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('add', 'old', 'lst');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

if ($act == "lst") {
	$query = "
		SELECT calendar_id AS cid, start_date, end_date, title, is_major, 
			DATE_FORMAT(start_date, '%Y-%m-%d') AS sdate, DATE_FORMAT(end_date, '%Y-%m-%d') AS edate, 
			EXTRACT(YEAR FROM start_date) AS year, EXTRACT(MONTH FROM start_date) AS month, 
			EXTRACT(DAY FROM start_date) AS day
		FROM iShark_Calendar 
		WHERE DATE_FORMAT(start_date, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d') OR 
			DATE_FORMAT(end_date, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d') OR 
			(start_date >= NOW() AND start_date <= NOW() + INTERVAL 25 DAY) OR 
			(end_date >= NOW() AND end_date <= NOW() + INTERVAL 25 DAY) 
		ORDER BY is_major DESC, start_date
	";
	$result = $mdb2->query($query);
	$dateArray = $result->fetchAll();

	$tpl->assign('dateArray', $dateArray);

	$acttpl = "calendar_block";
}

?>
