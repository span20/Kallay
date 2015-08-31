<?php

/**
 * Top statisztikak
 *
 * @param	int		kliens azonosito
 * @param	int		limit
 * @param	bool	start
 * @param	bool	end
 */
function top($clientID, $limit, $start = false, $end = false) {
	global $libs_dir, $pear_dir;

	$batchKeys = array(
		'pages',
		'entry_pages',
		'exit_pages',
		'exit_targets',
		'hosts',
		'referers',
		'operating_systems',
		'user_agents'
	);

	$batchWhat = array(
		'document',
		'entry_document',
		'exit_document',
		'exit_target',
		'host',
		'referer',
		'operating_system',
		'user_agent'
	);

	$batchResult = array();
	$batch       = array();

	// Loop through $batchKeys / $batchWhat
	for ($i = 0; $i < sizeof($batchKeys); $i++) {
		// Query Top <$limit> items of category <$batchWhat[$i]>
		$result = phpOpenTracker::get(
			array(
				'client_id' => $clientID,
				'api_call'  => 'top',
				'what'      => $batchWhat[$i],
				'start'     => $start,
				'end'       => $end,
				'limit'     => $limit
			)
		);

		$batchResult[$batchKeys[$i]]['top_items']    = $result['top_items'];
		$batchResult[$batchKeys[$i]]['unique_items'] = $result['unique_items'];
	}

	return $batchResult;
}

?>