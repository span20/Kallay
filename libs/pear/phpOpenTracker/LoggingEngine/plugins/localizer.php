<?php
//
// +---------------------------------------------------------------------+
// | phpOpenTracker - The Website Traffic and Visitor Analysis Solution  |
// +---------------------------------------------------------------------+
// | Copyright (c) 2000-2003 Sebastian Bergmann. All rights reserved.    |
// +---------------------------------------------------------------------+
// | This source file is subject to the phpOpenTracker Software License, |
// | Version 1.0, that is bundled with this package in the file LICENSE. |
// | If you did not receive a copy of this file, you may either read the |
// | license online at http://phpOpenTracker.de/license/1_0.txt, or send |
// | a note to license@phpOpenTracker.de, so we can mail you a copy.     |
// +---------------------------------------------------------------------+
// | Author: Sebastian Bergmann <sebastian@phpOpenTracker.de>            |
// +---------------------------------------------------------------------+
//
// $Id: localizer.php,v 1.1 2002/12/22 09:22:22 bergmann Exp $
//

require_once POT_INCLUDE_PATH . 'LoggingEngine/Plugin.php';

/**
* phpOpenTracker Logging Engine Plugin for localizer.
*
* @author   Sebastian Bergmann <sebastian@phpOpenTracker.de>
* @version  $Revision: 1.1 $
* @since    phpOpenTracker-Localizer 1.0.0
*/
class phpOpenTracker_LoggingEngine_Plugin_localizer extends phpOpenTracker_LoggingEngine_Plugin {
 /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_LoggingEngine_Plugin_localizer($parameters) {
    parent::phpOpenTracker_LoggingEngine_Plugin($parameters);

    $this->config['plugins']['localizer']['table'] = isset($this->config['plugins']['localizer']['table']) ? $this->config['plugins']['localizer']['table'] : 'pot_localizer';
  }

  /**
  * @return array
  * @access public
  */
  function post() {
    if ($this->container['first_request']) {
	  if (!@file(POT_CONFIG_PATH . 'countries.php')) {
        return phpOpenTracker::handleError(
          sprintf(
            'Cannot open "%s".',
            POT_CONFIG_PATH . 'countries.php'
          ),
          E_USER_ERROR
        );
      }

	  if (!@file(POT_CONFIG_PATH . 'ips.db')) {
        return phpOpenTracker::handleError(
          sprintf(
            'Cannot open "%s".',
            POT_CONFIG_PATH . 'ips.db'
          ),
          E_USER_ERROR
        );
      }

	  if (!@file(POT_CONFIG_PATH . 'ips.idx')) {
        return phpOpenTracker::handleError(
          sprintf(
            'Cannot open "%s".',
            POT_CONFIG_PATH . 'ips.idx'
          ),
          E_USER_ERROR
        );
      }

	  if (!@file(POT_INCLUDE_PATH . '/LoggingEngine/plugins/i2m.class.php')) {
        return phpOpenTracker::handleError(
          sprintf(
            'Cannot open "%s".',
            POT_INCLUDE_PATH . '/LoggingEngine/plugins/i2m.class.php'
		  ),
          E_USER_ERROR
        );
      }

	  require_once 'i2m.class.php';
	  $i2m = new ip2more(null, true);

	  $ip            = $i2m->ip;
	  $country       = $i2m->country['name'];
	  $iso2          = $i2m->country['iso2'];
	  $iso3          = $i2m->country['iso3'];
	  $fips104       = $i2m->country['fips104'];
	  $iso_number    = $i2m->country['isono'];
	  $flag          = substr($i2m->country['flag_small'], -6);
	  $region        = $i2m->country['region'];
	  $capital       = $i2m->country['capital'];
	  $currency      = $i2m->country['currency'];
	  $currency_code = $i2m->country['currency_code'];

	  if (isset($ip) && isset($country) && isset($iso2) && isset($iso3) && isset($fips104) && isset($iso_number) && isset($flag) && isset($region) && isset($capital) && isset($currency) && isset($currency_code)) {
		  $this->db->query(
          sprintf(
            "INSERT INTO %s
               (accesslog_id, client_id, ip, country, iso2, iso3, fips104, iso_number, flag, region, capital, currency, currency_code)
             VALUES ('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
			",

            $this->config['plugins']['localizer']['table'],
            $this->container['accesslog_id'],
			$this->container['client_id'],
            $this->db->prepareString($ip),
            $this->db->prepareString($country),
			$this->db->prepareString($iso2),
			$this->db->prepareString($iso3),
			$this->db->prepareString($fips104),
			$this->db->prepareString($iso_number),
			$this->db->prepareString($flag),
			$this->db->prepareString($region),
			$this->db->prepareString($capital),
			$this->db->prepareString($currency),
			$this->db->prepareString($currency_code)
          )
        );

	  }
    }

    return array();
  }
}
?>
