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

include_once 'includes/config_mdb2.php';

$PHPOPENTRACKER_CONFIGURATION = &phpOpenTracker_Config::getConfig();

/**
* phpOpenTracker Configuration File
*
* This file contains global configuration settings for phpOpenTracker.
* Values may be safely edited by hand.
* Uncomment only values that you intend to change.
*
* Strings should be enclosed in 'quotes'.
* Integers should be given literally (without quotes).
* Boolean values may be true or false (never quotes).
*/

// Database
$PHPOPENTRACKER_CONFIGURATION['db_type']      = $dsn['phptype']; // Available values: 'mssql', 'mysql', 'mysql_merge', 'oci8', 'pgsql'
$PHPOPENTRACKER_CONFIGURATION['db_host']      = $dsn['hostspec'];
// $PHPOPENTRACKER_CONFIGURATION['db_port']   = 'default'; // The port your database server listens on
// $PHPOPENTRACKER_CONFIGURATION['db_socket'] = 'default'; // The socket your database server uses
$PHPOPENTRACKER_CONFIGURATION['db_user']      = $dsn['username'];
$PHPOPENTRACKER_CONFIGURATION['db_password']  = $dsn['password'];
$PHPOPENTRACKER_CONFIGURATION['db_database']  = $dsn['database'];

// Tables
$PHPOPENTRACKER_CONFIGURATION['additional_data_table']   = 'iShark_Stat_Add_Data'; // Name of the Additional Data Table
$PHPOPENTRACKER_CONFIGURATION['accesslog_table']         = 'iShark_Stat_Accesslog'; // Name of the Access Log Table
$PHPOPENTRACKER_CONFIGURATION['documents_table']         = 'iShark_Stat_Documents'; // Name of the Documents Table
$PHPOPENTRACKER_CONFIGURATION['exit_targets_table']      = 'iShark_Stat_Exit_Targets'; // Name of the Exit Targets Table
$PHPOPENTRACKER_CONFIGURATION['hostnames_table']         = 'iShark_Stat_Hostnames'; // Name of the Hostnames Table
$PHPOPENTRACKER_CONFIGURATION['operating_systems_table'] = 'iShark_Stat_Operating_Systems'; // Name of the Operating Systems Table
$PHPOPENTRACKER_CONFIGURATION['referers_table']          = 'iShark_Stat_Referers'; // Name of the Referers Table
$PHPOPENTRACKER_CONFIGURATION['user_agents_table']       = 'iShark_Stat_User_Agents'; // Name of the User Agents Table
$PHPOPENTRACKER_CONFIGURATION['visitors_table']          = 'iShark_Stat_Visitors'; // Name of the Visitors Table

// With this directive you can define the names, separated by commas,
// of plugins for the phpOpenTracker Logging Engine, that should be
// loaded.
$PHPOPENTRACKER_CONFIGURATION['logging_engine_plugins'] = 'conversions, search_engines, localizer';

// Plugin Tables
$PHPOPENTRACKER_CONFIGURATION['plugins']['conversions']['table']    = 'iShark_Stat_Conversions'; // Name of the Conversions Table
$PHPOPENTRACKER_CONFIGURATION['plugins']['search_engines']['table'] = 'iShark_Stat_Search_Engines'; // Name of the Search Engines Table
$PHPOPENTRACKER_CONFIGURATION['plugins']['localizer']['table']      = 'iShark_Stat_Localizer'; // Name of the Localizer Table

// Resolution for Merge Tables backend
// 'day':   One pot_accesslog/pot_visitors table per day.
// 'month': One pot_accesslog/pot_visitors table per month.
// Default: 'month'
$PHPOPENTRACKER_CONFIGURATION['merge_tables_mode'] = 'month';

// Name of the environment variable to be used to 
// determine the current document.
// For instance, 'PATH_INFO' or 'REQUEST_URI' are possible here.
$PHPOPENTRACKER_CONFIGURATION['document_env_var'] = 'REQUEST_URI';

// When enabled, phpOpenTracker will strip away all HTTP GET
// parameters from the referer's URL before it gets stored
// in the database.
$PHPOPENTRACKER_CONFIGURATION['clean_referer_string'] = false;

// When enabled, phpOpenTracker will strip away all HTTP GET
// parameters from the URL, before it gets stored in the
// database.
// A Session ID will be stripped from the URL in either case.
$PHPOPENTRACKER_CONFIGURATION['clean_query_string'] = false;

// While enabling clean_query_string to will clean the
// document's URL of any HTTP GET parameters, you can define
// with the get_parameter_filter array a list of HTTP GET
// parameters that you would like to be stripped from the URL.
$PHPOPENTRACKER_CONFIGURATION['get_parameter_filter'] = '';

// Resolving of the hostname can be turned off.
$PHPOPENTRACKER_CONFIGURATION['resolve_hostname'] = true;

// Grouping of hostnames can be turned off.
$PHPOPENTRACKER_CONFIGURATION['group_hostnames'] = true;

// Grouping and parsing of user agents can be turned off.
$PHPOPENTRACKER_CONFIGURATION['group_user_agents'] = true;

// Detect and log returning visitors.
if (!empty($_SESSION['site_stat_return_visitor'])) {
	$PHPOPENTRACKER_CONFIGURATION['track_returning_visitors'] = true;
} else {
	$PHPOPENTRACKER_CONFIGURATION['track_returning_visitors'] = false;
}

// Name of the cookie to use for returning visitors detection.
$PHPOPENTRACKER_CONFIGURATION['returning_visitors_cookie'] = 'iShark_Stat_Visitor_ID';

// The 'returning_visitors_cookie' cookie expires after
// 'returning_visitors_cookie_lifetime' days.
if (!empty($_SESSION['site_stat_cookie_lifetime']) && is_numeric($_SESSION['site_stat_cookie_lifetime'])) {
	$PHPOPENTRACKER_CONFIGURATION['returning_visitors_cookie_lifetime'] = intval($_SESSION['site_stat_cookie_lifetime']);
} else {
	$PHPOPENTRACKER_CONFIGURATION['returning_visitors_cookie_lifetime'] = 1;
}

// With this directive you can turn on or off the locking of
// certain IPs and/or user agents.
$PHPOPENTRACKER_CONFIGURATION['locking'] = false;

// With this directive you can turn on or off the logging of
// reloaded documents.
if (!empty($_SESSION['site_stat_reload'])) {
	$PHPOPENTRACKER_CONFIGURATION['log_reload'] = true;
} else {
	$PHPOPENTRACKER_CONFIGURATION['log_reload'] = false;
}

// The path to your JPGraph installation.
$PHPOPENTRACKER_CONFIGURATION['jpgraph_path'] = 'jpgraph/';

// When enabled, the result of a phpOpenTracker API query which is
// limited to a timerange that lies completely in the past will be
// stored in a cache.
// $PHPOPENTRACKER_CONFIGURATION['query_cache'] = false;

// The directory where the phpOpenTracker API Query Cache should
// store its files.
$PHPOPENTRACKER_CONFIGURATION['query_cache_dir'] = '/tmp';

// The lifetime of a phpOpenTracker API Query Cache entry in seconds.
$PHPOPENTRACKER_CONFIGURATION['query_cache_lifetime'] = 3600;

// 0: Don't output error and warning messages.
// 1: Output error and warning messages. (default)
// 2: Output additional debugging messages.
$PHPOPENTRACKER_CONFIGURATION['debug_level'] = 1;

// When enabled, phpOpenTracker will exit on fatal errors.
$PHPOPENTRACKER_CONFIGURATION['exit_on_fatal_errors'] = true;

// When enabled, phpOpenTracker will log debugging, error and warning
// messages to a logfile.
$PHPOPENTRACKER_CONFIGURATION['log_errors'] = false;

// Path/Filename for the above logfile.
$PHPOPENTRACKER_CONFIGURATION['logfile'] = 'error.log';

// Mapping of client ids to client names.
// Currently only used by the simple_report example application.
$PHPOPENTRACKER_CONFIGURATION['clients'][1] = $_SERVER['HTTP_HOST'];

?>
