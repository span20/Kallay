<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

if (!empty($_GET['statpage'])) {
	$statpage = $_GET['statpage'];

	switch ($statpage) {
		case 'total': {
			$start = false;
			$end   = false;
		}
		break;

		case 'month': {
			$start = mktime(0,   0,  0, $month, 1, $year);
			$end   = mktime(23, 59, 59, $month, date('t', $start), $year);
		}
		break;

		case 'day': {
			$start = mktime(0,   0,  0, $month, $day, $year);
			$end   = mktime(23, 59, 59, $month, $day, $year);
		}
		break;
	}
} else {
	$statpage  = "total";
	$start = false;
	$end   = false;
}

if ($sub_act == "lst") {
	/**
	 * Altalanos statisztika
	 */
	//total page impressions
	$totalPageImpressions = phpOpenTracker::get(
		array(
			'client_id' => $clientID,
			'api_call'  => 'page_impressions',
			'start'     => $start,
			'end'       => $end
		)
	);

	//total visits
	$totalVisits = phpOpenTracker::get(
		array(
			'client_id' => $clientID,
			'api_call'  => 'visits',
			'start'     => $start,
			'end'       => $end
		)
	);

	//total impression this month
	$totalPageImpressionsCurrentMonth = phpOpenTracker::get(
		array(
			'client_id' => $clientID,
			'api_call'  => 'page_impressions',
			'range'     => 'current_month'
		)
	);

	//total visits this month
	$totalVisitsThisMonth =	phpOpenTracker::get(
		array(
			'client_id' => $clientID,
			'api_call'  => 'visits',
			'range'     => 'current_month'
		)
	);

	$first = date($locale->getCharset(), isset($firstAccess) ? $firstAccess : $time);
	$last  = date($locale->getCharset(), isset($lastAccess)  ? $lastAccess  : $time);

	/**
	 * Teljes statisztika
	 */
	if ($statpage == "total") {
		/**
		 * Eves statisztika
		 */
		// Query first and last date for this client
		$query = "
			SELECT MIN(timestamp) AS first_access
			FROM ".$config['visitors_table']."
			WHERE client_id = $clientID
		";
		$result = $mdb2->query($query);

		$row = $result->fetchRow();

		$month = $firstMonth = date('n', $row['first_access']);
		$year  = $firstYear  = date('Y', $row['first_access']);
		$lastMonth           = date('n', time());
		$lastYear            = date('Y', time());

		$monthly_stat = array();
		$i = 0;

		// Loop through months from first to last access
		while ($year <= $lastYear) {
			// Get start and end timestamp for this month
			$start = mktime( 0,  0,  0, $month, 1, $year);
			$end   = mktime(23, 59, 59, $month, date('t', $start), $year);

			// Query Page Impressions for this client and month
			$pi = phpOpenTracker::get(
				array(
					'client_id' => $clientID,
					'api_call'  => 'page_impressions',
					'start'     => $start,
					'end'       => $end
				)
			);

			// Query Visits for this client and month
			$visits = phpOpenTracker::get(
				array(
					'client_id' => $clientID,
					'api_call'  => 'visits',
					'start'     => $start,
					'end'       => $end
				)
			);

			$monthly_stat[$i]['pi_number']      = $pi;
			$monthly_stat[$i]['pi_percent']     = $totalPageImpressions ? number_format(((100 * $pi) / $totalPageImpressions), 2) : 0;
			$monthly_stat[$i]['visits_number']  = $visits;
			$monthly_stat[$i]['visits_percent'] = $totalVisits ? number_format(((100 * $visits) / $totalVisits), 2) : 0;
			$monthly_stat[$i]['link']           = '<a href="?p='.$module_name.'&amp;act='.$page.'&amp;sub_act='.$sub_act.'&amp;statpage=month&amp;client_id='.$clientID.'&amp;month='.$month.'&amp;year='.$year.'">'.$year.'. '.$monthNames[$month].'</a>';

			if ($month == $lastMonth && $year == $lastYear) {
				break;
			}

			if ($month < 12) {
				$month++;
			} else {
				$month = 1;
				$year++;
			}
			$i++;
		}

		//ha grafikonokat hasznalunk
		if (!empty($_SESSION['site_stat_is_graph'])) {
			$tpl->assign('graph_link_monthly', 'admin/stat_graph.php?what=access_statistics&amp;when=monthly&amp;client_id='.$clientID);
		}

		$tpl->assign('back_arrow', 'admin.php');
	}

	/**
	 * Adott honap statisztikaja
	 */
	if ($statpage == "month") {
		// Query Page Impressions for this client and each day of this month
		$pi = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'page_impressions',
				'start'     => $start,
				'end'       => $end,
				'interval'  => 86400
			)
		);

		// Query visits for this client and each day of this month
		$visits = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'visits',
				'start'     => $start,
				'end'       => $end,
				'interval'  => 86400
			)
		);

		// Loop through days
		for ($i = 0; $i < sizeof($pi); $i++) {
			$dayly_stat[$i]['pi_number']      = $pi[$i]['value'];
			$dayly_stat[$i]['pi_percent']     = $totalPageImpressions ? number_format(((100 * $pi[$i]['value']) / $totalPageImpressions), 2) : '0';
			$dayly_stat[$i]['visits_number']  = $visits[$i]['value'];
			$dayly_stat[$i]['visits_percent'] = $totalVisits ? number_format(((100 * $visits[$i]['value']) / $totalVisits), 2) : '0';
			$k = $i+1;
			$dayly_stat[$i]['link']           = '<a href="?p='.$module_name.'&amp;act='.$page.'&amp;sub_act='.$sub_act.'&amp;statpage=day&amp;client_id='.$clientID.'&amp;day='.$k.'&amp;month='.$month.'&amp;year='.$year.'">'.$k.'</a>';
		}

		//ha grafikonokat hasznalunk
		if (!empty($_SESSION['site_stat_is_graph'])) {
			$tpl->assign('graph_link_dayly', 'admin/stat_graph.php?what=access_statistics&amp;when=dayly&amp;client_id='.$clientID.'&amp;start='.$start.'&amp;end='.$end);
		}

		$tpl->assign('year',       $year);
		$tpl->assign('month',      $monthNames[$month]);
		$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act='.$sub_act.'&amp;statpage=total&amp;client_id='.$clientID);
	}

	/**
	 * Adott nap statisztikaja
	 */
	if ($statpage == "day") {
		// Query Page Impressions for this client and each day of this day
		$pi = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'page_impressions',
				'start'     => $start,
				'end'       => $end,
				'interval'  => 3600
			)
		);

		// Query Visits for this client and each day of this day
		$visits = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'visits',
				'start'     => $start,
				'end'       => $end,
				'interval'  => 3600
			)
		);

		// Loop through hours
		for ($i = 0; $i < sizeof($pi); $i++) {
			$hour = sprintf('%02d:00 - %02d:00', $i, (($i + 1) < 24) ? ($i + 1) : 0);

			$hourly_stat[$i]['hour']           = $hour;
			$hourly_stat[$i]['pi_number']      = $pi[$i]['value'];
			$hourly_stat[$i]['pi_percent']     = $totalPageImpressions ? number_format(((100 * $pi[$i]['value']) / $totalPageImpressions), 2) : '0';
			$hourly_stat[$i]['visits_number']  = $visits[$i]['value'];
			$hourly_stat[$i]['visits_percent'] = $totalVisits ? number_format(((100 * $visits[$i]['value']) / $totalVisits), 2) : '0';
		}

		//ha grafikonokat hasznalunk
		if (!empty($_SESSION['site_stat_is_graph'])) {
			$tpl->assign('graph_link_hourly', 'admin/stat_graph.php?what=access_statistics&amp;when=hourly&amp;client_id='.$clientID);
		}

		$tpl->assign('year',       $year);
		$tpl->assign('month',      $monthNames[$month]);
		$tpl->assign('day',        $day);
		$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act='.$sub_act.'&amp;statpage=month&amp;client_id='.$clientID.'&amp;month='.$month.'&amp;year='.$year);
	}

	//Top oldalak
	include_once $include_dir.'/function.stat.php';
	$top = top($clientID, $limit, $start, $end);

	//Keresok
	if (!empty($_SESSION['site_stat_search'])) {
		include_once $libs_dir.'/'.$pear_dir.'/phpOpenTracker/API.php';

		if (phpOpenTracker_API::pluginLoaded('search_engines')) {
			$engines = phpOpenTracker::get(
				array(
					'client_id' => $clientID,
					'api_call'  => 'search_engines',
					'what'      => 'top_search_engines',
					'start'     => $start,
					'end'       => $end,
					'limit'     => $limit
				)
			);

			$keywords = phpOpenTracker::get(
				array(
					'client_id' => $clientID,
					'api_call'  => 'search_engines',
					'what'      => 'top_search_keywords',
					'start'     => $start,
					'end'       => $end,
					'limit'     => $limit
				)
			);
		}

		$tpl->assign('search_engines',  $engines['top_items']);
		$tpl->assign('search_keywords', $keywords['top_items']);
	}

	//orszagok listaja
	if (!empty($_SESSION['site_stat_country'])) {
		if (phpOpenTracker_API::pluginLoaded('localizer')) {
			$countries = phpOpenTracker::get(
				array(
					'client_id' => $clientID,
					'api_call'  => 'localizer',
					'what'      => 'top_localizer',
					'start'     => $start,
					'end'       => $end,
					'limit'     => $limit
				)
			);
		}

		$tpl->assign('countries', $countries['top_items']);
	}

	//visszatero latogatok statisztikaja
	if (!empty($_SESSION['site_stat_return_visitor'])) {
		$num_unique_visitors = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'num_unique_visitors',
				'start'     => $start,
				'end'       => $end
			)
		);

		$num_one_time_visitors = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'num_one_time_visitors',
				'start'     => $start,
				'end'       => $end
			)
		);

		$num_returning_visitors = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'num_returning_visitors',
				'start'     => $start,
				'end'       => $end
			)
		);

		$num_return_visits = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'num_return_visits',
				'start'     => $start,
				'end'       => $end
			)
		);

		$average_visits = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'average_visits',
				'start'     => $start,
				'end'       => $end
			)
		);

		$average_time_between_visits = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'average_time_between_visits',
				'start'     => $start,
				'end'       => $end
			)
		);

		$tpl->assign('num_unique_visitors',         $num_unique_visitors);
		$tpl->assign('num_one_time_visitors',       $num_one_time_visitors);
		$tpl->assign('num_returning_visitors',      $num_returning_visitors);
		$tpl->assign('num_return_visits',           $num_return_visits);
		$tpl->assign('average_visits',              $average_visits);
		$tpl->assign('average_time_between_visits', $average_time_between_visits);
	}

	$tpl->assign('start',            $start);
	$tpl->assign('end',              $end);
	//Altalanos statisztika
	$tpl->assign('client',           $config['clients'][$clientID]);
	$tpl->assign('client_id',        $clientID);
	$tpl->assign('pi_total',         $totalPageImpressions);
	$tpl->assign('pi_month',         $totalPageImpressionsCurrentMonth);
	$tpl->assign('visits_total',     $totalVisits);
	$tpl->assign('visits_month',     $totalVisitsThisMonth);
	//Havi statisztika
	$tpl->assign('first',            $first);
	$tpl->assign('last',             $last);
	if (!empty($monthly_stat)) {
		$tpl->assign('monthly_stat', $monthly_stat);
	}
	if (!empty($dayly_stat)) {
		$tpl->assign('dayly_stat', $dayly_stat);
	}
	if (!empty($hourly_stat)) {
		$tpl->assign('hourly_stat', $hourly_stat);
	}
	//Top statisztikak
	$tpl->assign('top_total_pages',  $top['pages']['top_items']);
	$tpl->assign('top_entry_pages',  $top['entry_pages']['top_items']);
	$tpl->assign('top_hosts',        $top['hosts']['top_items']);
	$tpl->assign('top_referers',     $top['referers']['top_items']);
	$tpl->assign('top_op_systems',   $top['operating_systems']['top_items']);
	$tpl->assign('top_user_agents',  $top['user_agents']['top_items']);

	$acttpl = "stat_total_list";

}

?>