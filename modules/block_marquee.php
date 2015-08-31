<?php

//futócsík
if (isModule('news', 'index') && !empty($_SESSION['site_cnt_is_marquee'])) {
	$query = "
		SELECT content_id, title 
		FROM iShark_Contents 
		WHERE is_active = '1' AND type = 0 
		ORDER BY add_date DESC
	";
	$mdb2->setLimit($_SESSION['site_cnt_marquee_num']);
	$result = $mdb2->query($query);

	$tpl->assign('marquee', $result->fetchAll('', $rekey = true));

	$acttpl = "block_marquee";
}

?>
