<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "polls";

//nyelvi file betoltese
$locale->useArea($module_name);

$tpl->assign('self', $module_name);

//lekerdezzuk a modulhoz tartozo beallitasokat
$query = "
	SELECT * 
	FROM iShark_Polls_Configs
";
$result = $mdb2->query($query);
while ($row = $result->fetchRow())
{
	$poll_captcha = $row['captcha'];
	$poll_ismenu  = $row['is_menu'];
	$poll_reuse   = $row['reuse_time'];
	$poll_oldpoll = $row['oldpoll_view'];
}

//ha engedelyezve vannak a regi szavazasok
if ($poll_oldpoll == 1) {
	//ha a regi szavazasok listajat mutatjuk
	if (!isset($_GET['pid'])) {
		$query = "
			SELECT p.poll_id AS pid, p.title AS ptitle, p.start_date AS pstart, p.end_date AS pend 
			FROM iShark_Polls p 
			WHERE p.is_active = 0 AND p.end_date != '0000-00-00 00:00:00' 
			ORDER BY p.end_date DESC
		";
		$result = $mdb2->query($query);

		require_once 'Pager/Pager.php';
		$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('page_data', $paged_data['data']);
		$tpl->assign('page_list', $paged_data['links']);

		//megadjuk a tpl file nevet, amit atadunk az index.php-nek
		$acttpl = "polls_oldlist";
	}
	//ha egy konkret lezart szavazas adatait akarjuk megnezni
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$pid = intval($_GET['pid']);

		//lekerdezzuk a szavazast
		$query = "
			SELECT p.title AS ptitle, p.timer_start AS timer_start, p.timer_end AS timer_end, p.start_date AS start_date, 
				p.end_date AS end_date
			FROM iShark_Polls p 
			WHERE p.poll_id = $pid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			$poll = $result->fetchAll();
		} else {
			$acttpl = "error";
			$tpl->assign('errormsg', $locale->get('error_main_no_poll'));
			return;
		}

		//lekerdezzuk a szavazatok szamat
		$query = "
			SELECT COUNT(pv.data_id) AS polldata 
			FROM iShark_Polls_Votes pv, iShark_Polls_Datas pd 
			WHERE pd.poll_id = $pid AND pd.data_id = pv.data_id 
		";
		$result = $mdb2->query($query);
		$poll_num = $result->fetchRow();

		//lekerdezzuk a szavazasra adhato valaszokat es az eredmenyeket
		$query = "
			SELECT pd.data_id AS pid, pd.poll_text AS text, COUNT(pv.data_id) AS polldata 
			FROM iShark_Polls_Datas pd 
			LEFT JOIN iShark_Polls_Votes pv ON pd.data_id = pv.data_id 
			WHERE pd.poll_id = $pid 
			GROUP BY pv.data_id 
			ORDER BY pd.sortorder
		";
		$result = $mdb2->query($query);
		$poll_text = array();
		$i = 0;
		while ($row = $result->fetchRow())
		{
			$poll_text[$i]['text']     = $row['text'];
			$poll_text[$i]['polldata'] = $row['polldata'];

			if ($poll_num['polldata'] == 0) {
				$poll_text[$i]['percent'] = 100;
			} else {
				$poll_text[$i]['percent'] = substr(100 * $row['polldata'] / $poll_num['polldata'], 0, 6);
			}
			$i++;
		}

		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('poll_data', $poll);
		$tpl->assign('poll_text', $poll_text);
		$tpl->assign('poll_num',  $poll_num);
		$tpl->assign('pid',       $pid);

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl= "polls_result";
	}
} else {
	$acttpl = "error";
	$tpl->assign('errormsg', $locale->get('error_oldpoll_view'));
}

?>
