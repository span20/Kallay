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

/**
 * phpOpenTracker Logging Engine - Conversions
 *
 * @author      Daniel Kehoe <kehoe@fortuity.com>
 * @author      Adrian Lanning <alanning55@hotmail.com>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     Conversions
 * @since       phpOpenTracker-Conversions 1.0.0
 */
class phpOpenTracker_LoggingEngine_Plugin_conversions extends phpOpenTracker_LoggingEngine_Plugin {
  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_LoggingEngine_Plugin_conversions($parameters) {
    parent::phpOpenTracker_LoggingEngine_Plugin($parameters);

    $this->config['plugins']['conversions']['table'] = isset($this->config['plugins']['conversions']['table']) ? $this->config['plugins']['conversions']['table'] : 'pot_conversions';
  }

  /**
  * Collect any data on conversions to sales, inquiries or sign-ups.
  *
  * @return array
  * @access public
  */
  function pre() {
    if (!empty($this->container['plugin_data']['conversions']['sale']) ||
        !empty($this->container['plugin_data']['conversions']['inquiry']) ||
        !empty($this->container['plugin_data']['conversions']['signup'])) {
      $this->db->query(
        sprintf(
          "INSERT INTO %s
                       (accesslog_id, client_id, sale,
                        inquiry, signup, email, zip,
                        country, name)
                VALUES ('%d', '%d', '%d',
                        '%d', '%d', '%s', '%s',
                        '%s', '%s')",

          $this->config['plugins']['conversions']['table'],
          $this->container['accesslog_id'],
          $this->container['client_id'],
          $this->container['plugin_data']['conversions']['sale'],
          $this->container['plugin_data']['conversions']['inquiry'],
          $this->container['plugin_data']['conversions']['signup'],
          $this->db->prepareString($this->container['plugin_data']['conversions']['email']),
          $this->db->prepareString($this->container['plugin_data']['conversions']['zip']),
          $this->db->prepareString($this->container['plugin_data']['conversions']['country']),
          $this->db->prepareString($this->container['plugin_data']['conversions']['name'])
        )
      );

      $this->container['plugin_data']['conversions']['sale'] = '';
        $this->container['plugin_data']['conversions']['inquiry'] = '';
        $this->container['plugin_data']['conversions']['signup'] = '';
        $this->container['plugin_data']['conversions']['email'] = '';
        $this->container['plugin_data']['conversions']['zip'] = '';
        $this->container['plugin_data']['conversions']['country'] = '';
        $this->container['plugin_data']['conversions']['name'] = '';
    }

    return true;
  }
}
?>
