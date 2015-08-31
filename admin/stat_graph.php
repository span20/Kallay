<?php
//
// phpOpenTracker - The Website Traffic and Visitor Analysis Solution
//
// Copyright 2000 - 2005 Sebastian Bergmann. All rights reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//

include_once '../includes/config_paths.php';
ini_set('include_path', '../'.$libs_dir.'/'.$pear_dir.PATH_SEPARATOR.
						'../'.PATH_SEPARATOR.
						ini_get('include_path'));
include_once 'includes/config.php';
require_once 'phpOpenTracker.php';

switch ($_GET['what']) {
	case 'access_statistics': {
		$time = time();

		if ($_GET['when'] == "monthly") {
			phpOpenTracker::plot(
				array(
					'api_call' => 'access_statistics',
					'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
					'range'    => 'current_year',
					'interval' => 'month',
					'mode'     => 'line'
					)
				);
		}
		elseif ($_GET['when'] == "dayly") {
			phpOpenTracker::plot(
				array(
					'api_call'  => 'access_statistics',
					'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
					'start'     => isset($_GET['start'])     ? $_GET['start']     : mktime( 0, 0, 0, date('m', $time),   1, date('Y', $time)),
					'end'       => isset($_GET['end'])       ? $_GET['end']       : mktime( 0, 0, 0, date('m', $time)+1, 0, date('Y', $time)),
					'interval'  => isset($_GET['interval'])  ? $_GET['interval']  : 'day',
					'width'     => isset($_GET['width'])     ? $_GET['width']     : 640,
					'height'    => isset($_GET['height'])    ? $_GET['height']    : 480
					)
				);
		}
		elseif ($_GET['when'] == "hourly") {
			phpOpenTracker::plot(
				array(
					'api_call' => 'access_statistics',
					'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
					'range'    => 'today',
					'interval' => 'hour',
					'mode'     => 'line'
					)
				);
		}
	}
	break;

	case 'top': {
		$types = array('document', 'entry_document', 'exit_document', 'exit_target', 'host', 'referer', 'operating_system', 'user_agent');

		if (isset($_GET['type']) && in_array($_GET['type'], $types)) {
			if (isset($_GET['range']) && $_GET['range'] != "current_day") {
				phpOpenTracker::plot(
					array(
						'api_call'  => 'top',
						'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
						'what'      => isset($_GET['type'])  ? $_GET['type']  : 'document',
						'limit'     => isset($_GET['limit']) ? $_GET['limit'] : 20,
						'range'     => isset($_GET['range']) ? $_GET['range'] : 'current_year'
						)
					);
			} else {
				phpOpenTracker::plot(
					array(
						'api_call'  => 'top',
						'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
						'what'      => isset($_GET['type'])  ? $_GET['type']  : 'document',
						'limit'     => isset($_GET['limit']) ? $_GET['limit'] : 20,
						'start'     => mktime(0,   0,  0, date('n'), date('j'), date('Y')),
						'end'       => mktime(23, 59, 59, date('n'), date('j'), date('Y'))
						)
					);
			}
		}
	}
	break;

	case 'localizer': {
		$time = time();

		if (isset($_GET['range']) && $_GET['range'] != "current_day") {
			phpOpenTracker::plot(
				array(
					'api_call'  => 'localizer',
					'what'      => 'top_localizer',
					'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
					'range'     => isset($_GET['range']) ? $_GET['range'] : 'current_year',
					'limit'     => isset($_GET['limit']) ? $_GET['limit'] : 20
					)
				);
		} else {
			phpOpenTracker::plot(
				array(
					'api_call'  => 'localizer',
					'what'      => 'top_localizer',
					'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
					'range'     => isset($_GET['range']) ? $_GET['range'] : 'current_year',
					'start'     => mktime(0,   0,  0, date('n'), date('j'), date('Y')),
					'end'       => mktime(23, 59, 59, date('n'), date('j'), date('Y'))
					)
				);
		}
	}
	break;
}
?>