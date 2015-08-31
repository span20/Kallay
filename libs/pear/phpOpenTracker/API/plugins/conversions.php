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
 * phpOpenTracker API - Conversions
 *
 * @author      Daniel Kehoe <kehoe@fortuity.com>
 * @author      Adrian Lanning <alanning55@hotmail.com>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     Conversions
 * @since       phpOpenTracker-Conversions 1.0.0
 */
class phpOpenTracker_API_conversions extends phpOpenTracker_API_Plugin {
  /**
  * API Calls
  *
  * @var array $apiCalls
  */
  var $apiCalls = array(
    'num_conversions',
    'conversions',
    'conversions_by_source',
  );

  /**
  * API Type
  *
  * @var string $apiType
  */
  var $apiType = 'get';

  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_API_conversions() {
    parent::phpOpenTracker_API_Plugin();

    $this->config['plugins']['conversions']['table'] = isset($this->config['plugins']['conversions']['table']) ? $this->config['plugins']['conversions']['table'] : 'pot_conversions';
  }

  /**
  * Runs the phpOpenTracker API call.
  *
  * @param  array $parameters
  * @return mixed
  * @access public
  */
  function run($parameters) {
    $parameters['session_lifetime'] = isset($parameters['session_lifetime']) ? $parameters['session_lifetime'] : 3;

    switch ($parameters['api_call']) {
      case 'num_conversions': {
        return $this->_numConversions($parameters);
      }
      break;

      case 'conversions': {
        return $this->_conversions($parameters);
      }
      break;

      case 'conversions_by_source': {
        return $this->_conversionsBySource($parameters);
      }
      break;
    }
  }

  /**
  * Returns the number of visitors who have made a conversion (placed an order or
  * inquiry or signed up for something).
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _numConversions($parameters) {
    $this->db->query(
      sprintf(
        "SELECT COUNT(DISTINCT(accesslog.accesslog_id)) AS num_conversions
           FROM %s accesslog,
                %s conversions
          WHERE conversions.client_id    = '%d'
            AND conversions.accesslog_id = accesslog.accesslog_id
            AND accesslog.timestamp   >= '%d'
                %s",

        $this->config['accesslog_table'],
        $this->config['plugins']['conversions']['table'],
        $parameters['client_id'],
        time() - ($parameters['session_lifetime'] * 60),
        $this->_constraint($parameters['constraints'])
      )
    );

    if ($row = $this->db->fetchRow()) {
      return intval($row['num_conversions']);
    } else {
      return 0;
    }
  }

  /**
  * Returns detailed information about the visitors who have made a conversion
  * (placed an order or inquiry or signed up for something).
  *
  * @param  array $parameters
  * @return mixed
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _conversions($parameters) {
    switch ($parameters['result_format']) {
      case 'xml':
      case 'xml_object': {
        $tree     = new XML_Tree;
        $root     = &$tree->addRoot('visitorsonline');
        $children = array();
      }
      break;

      default: {
        $result = array();
      }
    }

    $accesslogIDs = array();

  // get a list of all accesslogIDs where there was a conversion
    $this->db->query(
      sprintf(
        "SELECT DISTINCT(accesslog.accesslog_id) AS accesslog_id
           FROM %s accesslog,
                %s conversions
          WHERE conversions.client_id    = '%d'
            AND conversions.accesslog_id = accesslog.accesslog_id
            AND accesslog.timestamp  >= '%d'",

        $this->config['accesslog_table'],
        $this->config['plugins']['conversions']['table'],
        $parameters['client_id'],
        time() - ($parameters['session_lifetime'] * 60)
      )
    );

    while ($row = $this->db->fetchRow()) {
      $accesslogIDs[] = $row['accesslog_id'];
    }

  // for each conversion, get details
    for ($i = 0, $max = sizeof($accesslogIDs); $i < $max; $i++) {

      // get the clickpaths for each conversion
      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $visitorNode = &$root->addChild('visitor');

          $visitorNode->addChild(
            phpOpenTracker::get(
              array(
                'client_id'     => $parameters['client_id'],
                'api_call'      => 'individual_clickpath',
                'accesslog_id'  => $accesslogIDs[$i],
                'result_format' => 'xml_object'
              )
            )
          );
        }
        break;

        default: {
          $result[$i]['clickpath'] = phpOpenTracker::get(
            array(
              'client_id'    => $parameters['client_id'],
              'api_call'     => 'individual_clickpath',
              'accesslog_id' => $accesslogIDs[$i]
            )
          );
        }
      }

    // get time of last access
      $this->db->query(
        sprintf(
          "SELECT MAX(timestamp) as last_access
             FROM %s
            WHERE accesslog_id = '%s'",

          $this->config['accesslog_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        switch ($parameters['result_format']) {
          case 'xml':
          case 'xml_object': {
            $visitorNode->addChild('last_access', $row['last_access']);
          }
          break;

          default: {
            $result[$i]['last_access'] = $row['last_access'];
          }
        }
      } else {
        return phpOpenTracker::handleError(
          'Database query failed. A'
        );
      }

     // get host and user agent
      $this->db->query(
        sprintf(
          "SELECT hosts.string       AS host,
                  user_agents.string AS user_agent
             FROM %s visitors,
                  %s hosts,
                  %s user_agents
            WHERE visitors.accesslog_id  = '%d'
              AND visitors.host_id       = hosts.data_id
              AND visitors.user_agent_id = user_agents.data_id",

          $this->config['visitors_table'],
          $this->config['hostnames_table'],
          $this->config['user_agents_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        switch ($parameters['result_format']) {
          case 'xml':
          case 'xml_object': {
            $visitorNode->addChild('host',       $row['host']);
            $visitorNode->addChild('user_agent', $row['user_agent']);
          }
          break;

          default: {
            $result[$i]['host']       = $row['host'];
            $result[$i]['user_agent'] = $row['user_agent'];
          }
        }
      } else {
        return phpOpenTracker::handleError(
          'Database query failed. B'
        );
      }

    // to determine source, first look for a record of
    // the use of a search engine on the first visit,
    // then a referrer on the first visit, then
    // the use of a search engine on the most recent visit,
    // then a referrer on the most recent visit

      // get visitor_id and number of visits
      $this->db->query(
        sprintf(
          "SELECT visitors.visitor_id AS visitor_id
             FROM %s visitors
            WHERE visitors.accesslog_id = '%d'",

          $this->config['visitors_table'],
          $accesslogIDs[$i]
        )
      );

    if ($row = $this->db->fetchRow()) {
        $visitor_id = intval($row['visitor_id']);
    } else {
        $visitor_id = 0;
    }

      $this->db->query(
        sprintf(
          "SELECT COUNT(visitors.visitor_id) AS num_visits
             FROM %s visitors
            WHERE visitors.visitor_id = '%d'",

          $this->config['visitors_table'],
          $visitor_id
        )
      );

      if ($row = $this->db->fetchRow()) {
        $num_visits = intval($row['num_visits']);
      } else {
        $num_visits = 0;
      }

      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $visitorNode->addChild('num_visits', $num_visits);
        }
        break;

        default: {
          $result[$i]['num_visits'] = $num_visits;
        }
      } // end query for visitor_id and number of visits

      // get accesslog_id of first visit
      $this->db->query(
        sprintf(
          "SELECT visitors.accesslog_id AS first_access
             FROM %s visitors
            WHERE visitors.visitor_id = '%d'
      ORDER BY visitors.timestamp",

          $this->config['visitors_table'],
          $visitor_id
        )
      );
      if ($row = $this->db->fetchRow()) {
        $first_access = $row['first_access'];
      } // end query for accesslog_id of first visit


    // get first search engine and search engine keywords
     if ($num_visits > 1) {
      $this->db->query(
            sprintf(
              "SELECT search_engines.search_engine AS search_engine,
                 search_engines.keywords AS keywords
                 FROM %s search_engines
                WHERE search_engines.accesslog_id = '%d'",
              $this->config['plugins']['search_engines']['table'],
              $first_access
            )
          );
          if ($row = $this->db->fetchRow()) {
            $source = $row['search_engine'].': "'.$row['keywords'].'"';
          }

      } // end query for first search engine and search engine keywords

      // get first referrer
      if (!isset($source)) {
        if ($num_visits > 1) {
          $this->db->query(
            sprintf(
              "SELECT referers.string AS first_referer
                 FROM %s visitors,
                      %s referers
                WHERE visitors.accesslog_id = '%d'
                  AND visitors.referer_id   = referers.data_id
                  ORDER BY visitors.timestamp",

              $this->config['visitors_table'],
              $this->config['referers_table'],
              $first_access
            )
          );

          if ($row = $this->db->fetchRow()) {
            $source = $row['first_referer'];
          }
        }
      }  // end query for first referrer

   // get most recent search engine and search engine keywords
   if (!isset($source)) {
    $this->db->query(
          sprintf(
            "SELECT search_engines.search_engine AS search_engine,
               search_engines.keywords AS keywords
               FROM %s search_engines,
                    %s visitors
              WHERE visitors.visitor_id = '%d'
                AND visitors.accesslog_id   = search_engines.accesslog_id
                ORDER BY visitors.timestamp",

            $this->config['plugins']['search_engines']['table'],
            $this->config['visitors_table'],
            $visitor_id
          )
        );
        if ($row = $this->db->fetchRow()) {
          $source = $row['search_engine'].': "'.$row['keywords'].'"';
        }
      } // end query for most recent search engines and search engine keywords

    // get most recent referrer
    if (!isset($source)) {
        $this->db->query(
          sprintf(
            "SELECT referers.string AS referer
               FROM %s visitors,
                    %s referers
              WHERE visitors.accesslog_id = '%d'
                AND visitors.referer_id   = referers.data_id",

            $this->config['visitors_table'],
            $this->config['referers_table'],
            $accesslogIDs[$i]
          )
        );
        if ($row = $this->db->fetchRow()) {
          $source = $row['referer'];
        } else {
          $source = '';
        }
    } // end query for most recent referrer

    // store source as a result
      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $visitorNode->addChild('source', $source);
        }
        break;

        default: {
          $result[$i]['source'] = $source;
        }
      } // done storing source
      unset($source);

  // query for conversions type, amount, and details
  $this->db->query(
        sprintf(
          "SELECT conversions.sale AS sale,
             conversions.inquiry AS inquiry,
             conversions.signup AS signup,
             conversions.email AS email,
             conversions.zip AS zip,
             conversions.country AS country,
             conversions.name AS name
             FROM %s conversions,
                  %s visitors
            WHERE visitors.accesslog_id = '%d'
              AND visitors.accesslog_id   = conversions.accesslog_id",

          $this->config['plugins']['conversions']['table'],
          $this->config['visitors_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        $sale = $row['sale'];
        $inquiry = $row['inquiry'];
        $signup = $row['signup'];
        $email = $row['email'];
        $zip = $row['zip'];
        $country = $row['country'];
        $name = $row['name'];
      } else {
        $sale = '';
        $inquiry = '';
        $signup = '';
        $email = '';
        $zip = '';
        $country = '';
        $name = '';
      }

      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $visitorNode->addChild('sale', $sale);
          $visitorNode->addChild('inquiry', $inquiry);
          $visitorNode->addChild('signup', $signup);
          $visitorNode->addChild('email', $email);
          $visitorNode->addChild('zip', $zip);
          $visitorNode->addChild('country', $country);
          $visitorNode->addChild('name', $name);
        }
        break;

        default: {
          $result[$i]['sale'] = $sale;
          $result[$i]['inquiry'] = $inquiry;
          $result[$i]['signup'] = $signup;
          $result[$i]['email'] = $email;
          $result[$i]['zip'] = $zip;
          $result[$i]['country'] = $country;
          $result[$i]['name'] = $name;
        }
      } // end query for conversions type, amount, etc.
    } // end loop

   // return results
    switch ($parameters['result_format']) {
      case 'xml': {
        return $root->get();
      }
      break;

      case 'xml_object': {
        return $root;
      }
      break;

      default: {
        return $result;
      }
    }
  } // end _conversions

 /**
  * Shows the value amd number of conversions aggregated by source.
  *
  * @param  array $parameters
  * @return mixed
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _conversionsBySource($parameters) {

  $timerange = $this->_whereTimerange(
      $parameters['start'],
      $parameters['end']
    );

    switch ($parameters['result_format']) {
      case 'xml':
      case 'xml_object': {
        $tree     = new XML_Tree;
        $root     = &$tree->addRoot('visitorsonline');
        $children = array();
      }
      break;

      default: {
        $result = array();
      }
    }

    $accesslogIDs = array();

  // get a list of all accesslogIDs where there was a conversion
    $this->db->query(
      sprintf(
        "SELECT DISTINCT(accesslog.accesslog_id) AS accesslog_id
           FROM %s accesslog,
                %s conversions
          WHERE conversions.client_id    = '%d'
            AND conversions.accesslog_id = accesslog.accesslog_id
            %s",

        $this->config['accesslog_table'],
        $this->config['plugins']['conversions']['table'],
        $parameters['client_id'],
        $timerange
      )
    );

    while ($row = $this->db->fetchRow()) {
      $accesslogIDs[] = $row['accesslog_id'];
    }

  // for each conversion, get details
    for ($i = 0, $max = sizeof($accesslogIDs); $i < $max; $i++) {
    unset($source);
    // to determine source, first look for a record of
    // the use of a search engine on the first visit,
    // then a referrer on the first visit, then
    // the use of a search engine on the most recent visit,
    // then a referrer on the most recent visit

      // get visitor_id and number of visits
      $this->db->query(
        sprintf(
          "SELECT visitors.visitor_id AS visitor_id
             FROM %s visitors
            WHERE visitors.accesslog_id = '%d'",

          $this->config['visitors_table'],
          $accesslogIDs[$i]
        )
      );

    if ($row = $this->db->fetchRow()) {
        $visitor_id = intval($row['visitor_id']);
    } else {
        $visitor_id = 0;
    }

      $this->db->query(
        sprintf(
          "SELECT COUNT(visitors.visitor_id) AS num_visits
             FROM %s visitors
            WHERE visitors.visitor_id = '%d'",

          $this->config['visitors_table'],
          $visitor_id
        )
      );

      if ($row = $this->db->fetchRow()) {
        $num_visits = intval($row['num_visits']);
      } else {
        $num_visits = 0;
      } // end query for visitor_id and number of visits

      // get accesslog_id of first visit
      $this->db->query(
        sprintf(
          "SELECT visitors.accesslog_id AS first_access
             FROM %s visitors
            WHERE visitors.visitor_id = '%d'
      ORDER BY visitors.timestamp",

          $this->config['visitors_table'],
          $visitor_id
        )
      );
      if ($row = $this->db->fetchRow()) {
        $first_access = $row['first_access'];
      } // end query for accesslog_id of first visit


    // get first search engine and search engine keywords
     if ($num_visits > 1) {
      $this->db->query(
            sprintf(
              "SELECT search_engines.search_engine AS search_engine,
                 search_engines.keywords AS keywords
                 FROM %s search_engines
                WHERE search_engines.accesslog_id = '%d'",
              $this->config['plugins']['search_engines']['table'],
              $first_access
            )
          );
          if ($row = $this->db->fetchRow()) {
            $source = $row['search_engine'].': "'.$row['keywords'].'"';
          }

      } // end query for first search engine and search engine keywords

      // get first referrer
      if (!isset($source)) {
        if ($num_visits > 1) {
          $this->db->query(
            sprintf(
              "SELECT referers.string AS first_referer
                 FROM %s visitors,
                      %s referers
                WHERE visitors.accesslog_id = '%d'
                  AND visitors.referer_id   = referers.data_id
                  ORDER BY visitors.timestamp",

              $this->config['visitors_table'],
              $this->config['referers_table'],
              $first_access
            )
          );

          if ($row = $this->db->fetchRow()) {
            $source = $row['first_referer'];
          }
        }
      }  // end query for first referrer

   // get most recent search engine and search engine keywords
   if (!isset($source)) {
    $this->db->query(
          sprintf(
            "SELECT search_engines.search_engine AS search_engine,
               search_engines.keywords AS keywords
               FROM %s search_engines,
                    %s visitors
              WHERE visitors.visitor_id = '%d'
                AND visitors.accesslog_id   = search_engines.accesslog_id
                ORDER BY visitors.timestamp",

            $this->config['plugins']['search_engines']['table'],
            $this->config['visitors_table'],
            $visitor_id
          )
        );
        if ($row = $this->db->fetchRow()) {
          $source = $row['search_engine'].': "'.$row['keywords'].'"';
        }
      } // end query for most recent search engines and search engine keywords

    // get most recent referrer
    if (!isset($source)) {
        $this->db->query(
          sprintf(
            "SELECT referers.string AS referer
               FROM %s visitors,
                    %s referers
              WHERE visitors.accesslog_id = '%d'
                AND visitors.referer_id   = referers.data_id",

            $this->config['visitors_table'],
            $this->config['referers_table'],
            $accesslogIDs[$i]
          )
        );
        if ($row = $this->db->fetchRow()) {
          $source = $row['referer'];
        } else {
          $source = 'unknown';
        }
    } // end query for most recent referrer

    // store source as a result
      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          // ??
        }
        break;

        default: {
          $result[$source]['source'] = $source;
        }
      } // done storing source


  // query for conversions type, amount, and details
  $this->db->query(
        sprintf(
          "SELECT conversions.sale AS sale,
             conversions.inquiry AS inquiry,
             conversions.signup AS signup
             FROM %s conversions,
                  %s visitors
            WHERE visitors.accesslog_id = '%d'
              AND visitors.accesslog_id   = conversions.accesslog_id",

          $this->config['plugins']['conversions']['table'],
          $this->config['visitors_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        $sale = $row['sale'];
        $inquiry = $row['inquiry'];
        $signup = $row['signup'];
      } else {
        $sale = '';
        $inquiry = '';
        $signup = '';
      }


      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          // ??
        }
        break;

        default: {
          $result[$source]['sale'] = $result[$source]['sale'] + $sale;
          $result[$source]['inquiry'] = $result[$source]['inquiry'] + $inquiry;
          $result[$source]['signup'] = $result[$source]['signup'] + $signup;
        }
      }
    // end query for conversions type, amount, etc.

     } // end loop

   // return results
    switch ($parameters['result_format']) {
      case 'xml': {
        return $root->get();
      }
      break;

      case 'xml_object': {
        return $root;
      }
      break;

      default: {
        return $result;
      }
    }
  } // end _conversionsBySource
} // end class

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>
