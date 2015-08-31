<?php
//
// +---------------------------------------------+
// |     IP2MORE :: IP2COUNTRY V3                |
// |     http://www.SysTurn.com                  |
// +---------------------------------------------+
//
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; either version 2, or (at your option)
//   any later version.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software Foundation,
//   Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
//
//
/**
 * IP2MORE class
 *
 * PHP class to simplify ip2country functions
 * and also let you know more info about the IP address
 * like Country, ISO2 Code, ISO3 Code, FIPS104 Code, ISO Number,
 * Region, Capital, Currency, Currency Code and flag (small & big)
 * If you also have registeration form (or similar forms)
 * where user should select his country from list, ip2more class
 * has a built-in function which will print dropdown list options
 * and (optionaly) selects user's country based on his IP address
 *
 * The class doesn't need any database, as it depends on flat files
 * which can be updated later easily and works very fast because
 * it uses indexies in the search
 *
 * In this version, if the IP is not found in the local flat database,
 * it will be fetched from WHOIS server
 *
 * @author   Bakr Alsharif <bakr AT systurn DOT com>
 * @website  http://systurn.com/ip2more/
 * @example  http://systurn.com/ip2more/demo.php
 * @version  3.0.0   7 September 2005
 */
class ip2more
{

  ///////////////////
  // CLASS OPTIONS //
  ///////////////////
  
  /**
  * IP Address
  */
  var $ip              = '';
  
  /*
  * Country Info Array
  * Holds each piece of information about current IP's Country <br />
  *         'name'                  : country name <br />
  *         'iso2'                  : ISO2 code <br />
  *         'iso3'                  : ISO3 code <br />
  *         'fibs104'               : FIBS104 code <br />
  *         'isono'                 : ISO Number <br />
  *         'capital'               : Capital City <br />
  *         'region'                : Region <br />
  *         'currency'              : Currency <br />
  *         'currency_code'         : Currency Code <br />
  *         'flag_small'            : Small Flag Picture <br />
  *         'flag_big'              : Big Flag Picture <br />
  */
  var $country         = array();
  
  /*
  * Flags Dir
  */
  var $flags_dir       = '../../flags/';
  
  /*
  * IPs Database Index File
  */
  var $idx_file        = '../../conf/ips.idx';
  
  /*
  * IPs Database File
  */
  var $db_file         = '../../conf/ips.db';
  
  /*
  * Countries List File
  */
  var $countries_file  = '../../conf/countries.php';
  
  /*
  * Countries List Array
  */
  var $countries_list  = array();
  
  /*
  * What Should Be Returned If Couldn't Find IP's Country
  */
  var $null_country = array('name'=>"UNKOWN", 'iso2'=>"--", 'iso3'=>"--", 'fips104'=>"--", 'isono'=>"--", 'capital'=>"UNKOWN", 'region'=>"UNKOWN", 'currency'=>"UNKOWN", 'currency_code'=>"UNKOWN");
  
  /*
  * WHOIS Service URL
  */
  var $whois_url = 'http://www.dnsstuff.com/tools/whois.ch?ip=%s';
  
  /*
  * WHOIS Service Request Method (GET OR POST)
  */
  var $whois_method = 'GET';
  
  /*
  * WHOIS Service Timeout
  */
  var $whois_timeout = '20';
  
  /*
  * WHOIS Regex Which Will Match Country
  */
  var $whois_regex = '/Location\<\/A\>:\s+([^\r\n\<\[\(\{]+)/i';
  
  /*
  * WHOIS Return Type (name OR iso2 OR iso3 OR fips104 OR isono)
  */
  var $whois_return = 'name';
  


  /////////////////
  // CONSTRUCTOR //
  /////////////////
  
  /**
  * CONSTRUCTOR Functions
  *
  * IF IP address not passed, Current user's IP will be used
  *
  * @param      string      $ip             If null, current user's ip will be used. If false, no IP lookup will be made
  * @param      bool        $whois_lookup   If true, and the IP couldn't be found in the local database, a search will be done by using Whois Service
  * @see        get_ip
  * @see        whois_lookup
  */
  function ip2more($ip = null, $whois_lookup = false)
  {
    if(file_exists(dirname(__FILE__).'/'.$this->countries_file)){
      include(dirname(__FILE__).'/'.$this->countries_file);
      $this->countries_list = $i2m_countries_list;
      unset($i2m_countries_list);
    } else {
		echo "nem talalom a file-t!";
	}
    
    if($ip === null) $this->set_ip($this->get_ip(), $whois_lookup);
    elseif($ip !== false && $this->is_valid_ip($ip)) $this->set_ip($ip, $whois_lookup);
  }
  
  
  //////////////////////
  // PUBLIC FUNCTIONS //
  //////////////////////

  /**
  * Check if IP Address Is Valid Or Not
  *
  * This function will validate the passed IP address. It returns true if the IP is valid. and false if not valid
  *
  * @return     bool
  * @param      string      $ip         The IP address to check
  * @access     public
  */
  function is_valid_ip($ip)
  {
    $ip = explode('.', $ip);
    if(count($ip) != 4) return false;
    for($i=0;$i<4;$i++)
      if(!is_numeric($ip[$i]) || $ip[$i] > 255) return false;
    return true;
  }

  /**
  * Get User's Real IP
  *
  * This function will try to find user's real ip address
  * even if he is behind proxy server or firewall
  * NOTE: Almost all anonymous proxies will hide real user's ip
  *
  * @return     string              User's Real IP Address
  * @access     public
  * @see        set_ip
  */
  function get_ip()
  {
   // No IP found (will be overwritten by for
   // if any IP is found behind a firewall)
   $ip = FALSE;

   // If HTTP_CLIENT_IP is set, then give it priority
   if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
       $ip = $_SERVER["HTTP_CLIENT_IP"];
   }

   // User is behind a proxy and check that we discard RFC1918 IP addresses
   // if they are behind a proxy then only figure out which IP belongs to the
   // user.  Might not need any more hackin if there is a squid reverse proxy
   // infront of apache.
   if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

       // Put the IP's into an array which we shall work with shortly.
       $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
       if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }

       for ($i = 0; $i < count($ips); $i++) {
           // Skip RFC 1918 IP's 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16
           if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
               if (version_compare(phpversion(), "5.0.0", ">=")) {
                   if (ip2long($ips[$i]) != false) {
                       $ip = $ips[$i];
                       break;
                   }
               } else {
                   if (ip2long($ips[$i]) != -1) {
                       $ip = $ips[$i];
                       break;
                   }
               }
           }
       }
   }

   return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
  }
  
  /**
  * Set Active IP Address
  *
  * This will set the active ip address, and fetches
  * all available informations about it.
  * after calling this function, the public variable 'caountry'
  * will hold all the info about the ip address
  *
  * @param      string      $ip             User's Real IP Address
  * @param      bool        $whois_lookup   If true, and the IP couldn't be found in the local database, a search will be done by using Whois Service
  * @return     bool                        always return true
  * @access     public
  * @see        get_ip
  * @see        whois_lookup
  */
  function set_ip($ip, $whois_lookup=false)
  {
    $this->ip = $ip;
    if($ip == '127.0.0.1') $this->country = $this->null_country;
    else $this->_get_country($whois_lookup);
    $this->_set_flags();
    return true;
  }

  /**
  * Sort Countries List
  *
  * This will sort countries list on the passed order
  *
  * @param      int         $order      COUNTRY_ORDER default , REGION_ORDER, ISO2_ORDER, ISO3_ORDER
  * @return     bool                    always returns true
  * @access     public
  * @see        print_countries_list
  * @see        return_countries_list
  */
  function sort_countries_list($order)
  {
    if($order == COUNTRY_ORDER){
      uasort($this->countries_list, array($this, '_sort_by_country'));
    }
    elseif($order == REGION_ORDER){
      uasort($this->countries_list, array($this, '_sort_by_region'));
    }
    elseif($order == ISO2_ORDER){
      uasort($this->countries_list, array($this, '_sort_by_iso2'));
    }
    elseif($order == ISO3_ORDER){
      uasort($this->countries_list, array($this, '_sort_by_iso3'));
    }
    return true;
  }
  
  /**
  *
  * Print Countries List
  *
  * This will print countries list as drop down list options
  * the value of the option can passed in the first param
  * and can be any valid key in $this->country array (like iso2 , iso3 or name)
  *
  * @param      string      $value          Drop Down List Options values key (default iso3)
  * @param      bool        $auto_select    if true , the active country will be selected (default true)
  * @param      string      $selected_value if $auto_select is false, then the option having its value equal to $selected_value will be selected
  * @return     bool                        always returns true
  * @access     public
  * @see        return_countries_list
  * @see        sort_countries_list
  *
  */
  function print_countries_list($value='iso3', $auto_select=true, $selected_value='')
  {
    if($auto_select) // more faster than checking it in the loop
    {
      foreach($this->countries_list as $key=>$val)
      {
        echo '<option value="' . $this->countries_list[$key][$value] . '"' . (($this->countries_list[$key]['iso3']==$this->country['iso3']) ? ' SELECTED' : '') . '>' . $val['name'] . "</option>\r\n";
      }
    }
    else
    {
      foreach($this->countries_list as $key=>$val)
      {
        echo '<option value="' . $this->countries_list[$key][$value] . '"' . (($this->countries_list[$key]['iso3']==$selected_value) ? ' SELECTED' : '') . '>' . $val['name'] . "</option>\r\n";
      }
    }
    return true;
  }
  
  /**
  *
  * Return Countries List
  *
  * This will return countries list as <strong>string</strong> containing drop down list options
  * the value of the option can passed in the first param
  * and can be any valid key in $this->country array (like iso2 , iso3 or name)
  *
  * @param      string      $value          Drop Down List Options values key (default iso3)
  * @param      bool        $auto_select    if true , the active country will be selected (default true)
  * @param      string      $selected_value if $auto_select is false, then the option having its value equal to $selected_value will be selected
  * @return     string                      Drop Down List Options
  * @access     public
  * @see        print_countries_list
  * @see        sort_countries_list
  *
  */
  function return_countries_list($value='iso3', $auto_select=true, $selected_value='')
  {
    $return = '';
    if($auto_select) // more faster than checking it in the loop
    {
      foreach($this->countries_list as $key=>$val)
      {
        $return .= '<option value="' . $this->countries_list[$key][$value] . '"' . (($this->countries_list[$key]['iso3']==$this->country['iso3']) ? ' SELECTED' : '') . '>' . $val['name'] . "</option>\r\n";
      }
    }
    else
    {
      foreach($this->countries_list as $key=>$val)
      {
        $return .= '<option value="' . $this->countries_list[$key][$value] . '"' . (($this->countries_list[$key]['iso3']==$selected_value) ? ' SELECTED' : '') . '>' . $val['name'] . "</option>\r\n";
      }
    }
    return $return;
  }
  
  /**
  * Search for a country
  *
  * This function was suggested by 'Nizam <info AT singlesburg DOT com>'. It can be used to find a country by providing any info about it
  * like name, iso2, iso3, fips104 or even the Capital
  *
  * @param      string      $needle     The info you know about the country
  * @param      string      $type       Type of needle. it must be on of the following: (name, iso2, iso3, fips104, isono, capital)
  * @return     array                   An array containing all the info about the found country.
  * @access     public
  */
  function find_country($needle, $type)
  {
    $type = strtolower($type);
    if(!in_array($type, array('name','iso2','iso3','fips104','isono','capital'))) return false;

    $needle = strtolower($needle);
    foreach($this->countries_list as $key=>$val)
    {
      if(strtolower($val[$type]) == $needle) return $val;
    }
    return $this->null_country;
  }
  
  /**
  * Whois Service Lookup
  *
  * This function will open a socket to WHOIS service server and fetches all available info about the passed IP address.
  * It's useful if the we are looking for IP address that's not stored in the local flat database
  *
  * @param      string      $ip         The IP address to find its country
  * @return     array                   An array containing all the info about the found country.
  * @access     public
  */
  function whois_lookup($ip)
  {
    $URL = parse_url( sprintf($this->whois_url, $ip) );
    if(($this->whois_method = strtoupper($this->whois_method)) && ($this->whois_method == 'GET' || $this->whois_method == 'POST'))
    {
        ;
    }
    else
    {
      return $this->null_country;
    }
    $req = '';
    if ($this->whois_method == 'GET')
    {
      $URL['path'] .= '?' . $URL['query'];
    }
    $req .= "GET $URL[path] HTTP/1.1\r\n".
            "Connection: Close\r\n".
            "Accept: */*\r\n".
            "Accept-Language: en-gb\r\n".
            "Host: $URL[host]\r\n".
            "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.0; en-GB; rv:0.9.4) Gecko/20011019 Netscape6/6.2\r\n";
    if($this->whois_method == 'POST')
    {
      $req .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $req .= 'Content-Length: '.strlen($URL['query']);
      $req .= "\r\n\r\n$URL[query]";
    }
    else
    {
      $req .= "\r\n";
    }

    if(empty($URL['port'])) $URL['port'] = 80;
    $fp = @fsockopen($URL['host'], $URL['port'], $errno, $errstr, $this->whois_timeout);
    if(!$fp)
    {
      //echo "Whois Lookup Failed To Open $URL[host]:$URL[port] ($errno - $errstr)";
      return $this->null_country;
    }
    
    fwrite($fp, $req);
    $result = '';
    while(!feof($fp))
    {
      $result .= fgets($fp, 1024);
    }
      
    @fclose($fp);

    if(!preg_match($this->whois_regex, $result, $match)) return $this->null_country;
    else return $this->find_country(trim($match[1]), $this->whois_return);
  }
  
  ///////////////////////
  // PRIVATE FUNCTIONS //
  ///////////////////////
  
  /**
  * Get IP's Info
  *
  * This is the main function which translate IP address
  * to country's ISO3 code and then sets $this->country
  * if country not found, or IP in not listed in database,
  * UNKOWN country with UNKOWN info and -- ISO Codes will be used instead
  *
  * @param      bool        $whois_lookup   If true, and the IP couldn't be found in the local database, a search will be done by using Whois Service
  * @return     bool        always return true
  * @access     private
  * @see        _search_in_index
  * @see        _search_in_db
  * @see        whois_lookup
  */
  function _get_country($whois_lookup=false)
  {

   // Convert the IP number to some useable form for searching
   $ipn = (float) sprintf("%u", ip2long($this->ip));

   // Find the index to start search from
   $idx = $this->_search_in_index($ipn);

   // If we were unable to find any helpful entry
   // in the index do not search, as it would take
   // a very long time. It is an error, if we have
   // not found anything in the index
   if ($idx !== FALSE) {
       $country = $this->_search_in_db($ipn, $idx);
   } else {
       $country = 'NA';
   }
   if($country != 'NA'){
       if(isset($this->countries_list[$country]))
         $this->country = $this->countries_list[$country];
       else
         $this->country = $this->null_country;
   }
   elseif($whois_lookup) $this->country = $this->whois_lookup($this->ip);
   else $this->country = $this->null_country;
   return true;
  }

  /**
   * Find Nearest Database Index
   *
   * Find nearest index entry for IP number (not address)
   *
   * @param     long        $ip         IP address in long format see ip2long
   * @return    array                   containing the min & max record numbers IP address could be between
   * @access    private
   * @see       _search_in_db
   */
  function _search_in_index($ip)
  {
   // Indexed part and record number to jump to
   $idxpart = 0; $recnum = 0;

   // Open the index file for reading
   $dbidx = fopen(dirname(__FILE__).'/'.$this->idx_file, "r");
   if (!$dbidx) { return FALSE; }

   // Read in granularity from index file and
   // convert current IP to something useful
   $granularity = intval(fgets($dbidx, 64));
   $ip_chunk = intval($ip / $granularity);

   // Loop till we can read the file
   while (!feof($dbidx)) {

       // Get CSV data from index file
       $data = fgetcsv($dbidx, 100);

       // Compare current index part with our IP
       if ($ip_chunk >= $idxpart && $ip_chunk < (int) $data[0]) {
           return array($recnum, (int) $data[1]);
       }

       // Store for next compare
       $idxpart = (int) $data[0];
       $recnum  = (int) $data[1];
   }

   // Return record number found
   return array($recnum, -1);
  }

  /**
   * Find Country ISO3 Code IN Database
   *
   * Find the ISO3 Code by searching from record $idx[0] to record $idx[1]
   *
   * @param     long        $ip         IP address in long format see ip2long
   * @param     array       $idx        containing the min & max record numbers IP address could be between
   * @return    string                  ISO3 Code  OR  NA  if not found
   * @access    private
   * @see       _search_in_index
   */
  function _search_in_db($ip, $idx)
  {
   // Default range and country
   $range_start = 0; $range_end = 0;
   $country = "NA";

   // Open DB for reading
   $ipdb = fopen(dirname(__FILE__).'/'.$this->db_file,"r");

   // Return with "NA" in case of we cannot open the db
   if (!$ipdb) { return $country; }

   // Jump to record $idx
   fseek($ipdb, ($idx[0]-1)*24);

   // Read records until we hit the end of the file,
   // or we find the range where this IP is, or we
   // reach the next indexed part [where the IP should
   // not be found, so there is no point in searching further]
   while (!feof($ipdb) && !($range_start <= $ip && $range_end >= $ip)) {

       // We had run out of the indexed region,
       // where we expected to find the IP
       if ($idx[1] != -1 && $idx[0] > $idx[1]) {
           $country = "NA"; break;
       }

       // Try to read record
       $record = fread($ipdb, 24);

       // Unable to read the record => error
       if (strlen($record) != 24) { $country = "NA"; break; }

       // Split the record to it's parts
       $range_start = (float) substr($record, 0, 10);
       $range_end  = (float) substr($record, 10, 10);
       $country    = substr($record, 20, 3);

       // Getting closer to the end of the indexed region
       $idx[0] += 1;
   }

   // Close datafile
   fclose($ipdb);

   // Return with the country found
   return $country;
  }
  
  /**
  * Set Country Flags Pictures
  *
  * After calling this function, $this->country['flag_small']
  * and $this->country['flag_small'] will be set to pictures files
  * NOTE: if the flags dir has been changed, you have to set it first
  *       $i2m->flags_dir = './flags/';
  *       $i2m->flags_dir = 'http://domain-name/flags/';
  *
  * @return     bool                always returns true
  * @access     private
  */
  function _set_flags()
  {
    // Add trailing slash to flags_dir if there's no 1 already
    if(substr($this->flags_dir,-1) != '/') $this->flags_dir .= '/';

    $this->country['flag_small'] = $this->flags_dir . 's/' . strtolower($this->country['iso2']) . '.png';
    $this->country['flag_big']   = $this->flags_dir . 'b/' . strtolower($this->country['iso2']) . '.png';
    return true;
  }

  /**
  * Sort Countries List By Country Name
  *
  * Private function used in uasort to sort countries list based on country name
  *
  * @param      array           $country1       an array containing country info
  * @param      array           $country2       an array containing country info
  * @return     int                             the returned value will be used by uasort function to sort countries list
  * @access     private
  * @see        sort_countries_list
  * @see        print_countries_list
  */
  function _sort_by_country($country1, $country2)
  {
    return strcmp($country1['name'], $country2['name']);
  }
  
  /**
  * Sort Countries List By Region
  *
  * Private function used in uasort to sort countries list based on region
  *
  * @param      array           $country1       an array containing country info
  * @param      array           $country2       an array containing country info
  * @return     int                             the returned value will be used by uasort function to sort countries list
  * @access     private
  * @see        sort_countries_list
  * @see        print_countries_list
  */
  function _sort_by_region($country1, $country2)
  {
    return strcmp($country1['region'], $country2['region']);
  }
  
  /**
  * Sort Countries List By ISO2 Code
  *
  * Private function used in uasort to sort countries list based on ISO2 Code
  *
  * @param      array           $country1       an array containing country info
  * @param      array           $country2       an array containing country info
  * @return     int                             the returned value will be used by uasort function to sort countries list
  * @access     private
  * @see        sort_countries_list
  * @see        print_countries_list
  */
  function _sort_by_iso2($country1, $country2)
  {
    return strcmp($country1['iso2'], $country2['iso2']);
  }
  
  /**
  * Sort Countries List By ISO3 Code
  *
  * Private function used in uasort to sort countries list based on ISO3 Code
  *
  * @param      array           $country1       an array containing country info
  * @param      array           $country2       an array containing country info
  * @return     int                             the returned value will be used by uasort function to sort countries list
  * @access     private
  * @see        sort_countries_list
  * @see        print_countries_list
  */
  function _sort_by_iso3($country1, $country2)
  {
    return strcmp($country1['iso3'], $country2['iso3']);
  }

}

define('COUNTRY_ORDER', 1);
define('REGION_ORDER', 2);
define('ISO3_ORDER', 3);
define('ISO2_ORDER', 4);
?>