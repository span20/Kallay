<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

if ($sub_act = "lst") {
	$visitors = array();

	foreach ($config['clients'] as $clientID => $client) {
		$resultSet = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'visitors_online'
			)
		);

		if ($resultSet) {
			foreach ($resultSet as $resultItem) {
				$index = sizeof($visitors);

				$visitors[$index]['last_access']  = date($locale->get('date_format'), $resultItem['last_access']);
				$visitors[$index]['site']         = $client;
				$visitors[$index]['document']     = $resultItem['clickpath']->documents[sizeof($resultItem['clickpath']->documents)-1];
				$visitors[$index]['document_url'] = $resultItem['clickpath']->document_urls[sizeof($resultItem['clickpath']->document_urls)-1];
				$visitors[$index]['host']         = $resultItem['host'];
				$visitors[$index]['referer']      = $resultItem['referer'];
			}
		}
	}

	$tpl->assign('visitors', $visitors);

	$acttpl = "stat_current_list";
}

?>
