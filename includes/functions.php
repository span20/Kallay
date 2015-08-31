<?php

/**
 * kilistazza egy konyvtar tartalmat a megadott file-ok tipusa alapjan
 *
 * @param	string	mappa helye
 * @param	string	file tipusa
 * @param	array	kihagyando file-ok listaja
 * @param	string	ha 0, akkor normal modon kesziti a tombot, ha 1, akkor a tomb azonosito is a nev lesz
 *
 * @return	array	file-ok listaja ABC sorrendben
 */
function directory_list($dir, $type = "php", $excl = array(), $sort = 0)
{
    $directory_array = array();
    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($file = readdir($handle))
        {
            $file_arr = explode(".", $file);
            if (!is_dir($file)) {
                if (isset($file_arr[1]) && $file_arr[1] == $type && !in_array($file_arr[0], $excl)) {
                    //array_push($directory_array, $file_arr[0]); //eroforras igenyesebb
                    if ($sort == 0) {
                        $directory_array[] = $file_arr[0];
                    }
                    if ($sort == 1) {
                        $directory_array[$file_arr[0]] = $file_arr[0];
                    }
                }
            }
        }
        closedir($handle);
        if ($sort == 0) {
            sort($directory_array);
        }
        if ($sort == 1) {
            ksort($directory_array);
        }
    }
    return $directory_array;
}

/**
 * Helper method - Rewrite the query into a "SELECT COUNT(*)" query.
 * @param string $sql query
 * @return string rewritten query OR false if the query can't be rewritten
 * @access private
 */
function rewriteCountQuery($sql)
{
    if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $sql) || preg_match('/\s+GROUP\s+BY\s+/is', $sql)) {
        return false;
    }
    $open_parenthesis = '(?:\()';
    $close_parenthesis = '(?:\))';
    $subquery_in_select = $open_parenthesis.'.*\bFROM\b.*'.$close_parenthesis;
    $pattern = '/(?:.*'.$subquery_in_select.'.*)\bFROM\b\s+/Uims';
    if (preg_match($pattern, $sql)) {
        return false;
    }
    $subquery_with_limit_order = $open_parenthesis.'.*\b(LIMIT|ORDER)\b.*'.$close_parenthesis;
    $pattern = '/.*\bFROM\b.*(?:.*'.$subquery_with_limit_order.'.*).*/Uims';
    if (preg_match($pattern, $sql)) {
        return false;
    }
    $queryCount = preg_replace('/(?:.*)\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $sql, 1);
    list($queryCount, ) = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
    list($queryCount, ) = preg_split('/\bLIMIT\b/is', $queryCount);
    return trim($queryCount);
}

/**
 * @param object PEAR::MDB2 instance
 * @param string db query
 * @param array  PEAR::Pager options
 * @param boolean Disable pagination (get all results)
 * @param integer fetch mode constant
 * @return array with links and paged data
 */
function Pager_Wrapper_MDB2(&$db, $query, $pager_options = array(), $disabled = false, $fetchMode = MDB2_FETCHMODE_ASSOC)
{
    if (!array_key_exists('totalItems', $pager_options)) {
        //be smart and try to guess the total number of records
        if ($countQuery = rewriteCountQuery($query)) {
            $totalItems = $db->queryOne($countQuery);
            if (PEAR::isError($totalItems)) {
                return $totalItems;
            }
        } else {
            //GROUP BY => fetch the whole resultset and count the rows returned
            $res = $db->queryCol($query);
            if (PEAR::isError($res)) {
                return $res;
            }
            $totalItems = count($res);
        }
        $pager_options['totalItems'] = $totalItems;
    }
    require_once 'Pager/Pager.php';
    $pager = Pager::factory($pager_options);

    $page = array();
    $page['links'] = $pager->links;
    $page['totalItems'] = $pager_options['totalItems'];
    $page['page_numbers'] = array(
        'current' => $pager->getCurrentPageID(),
        'total'   => $pager->numPages()
    );
    list($page['from'], $page['to']) = $pager->getOffsetByPageId();
    $page['limit'] = $page['to'] - $page['from'] +1;
    if (!$disabled) {
        $db->setLimit($pager_options['perPage'], $page['from']-1);
    }
    $page['data'] = $db->queryAll($query, null, $fetchMode);
    if (PEAR::isError($page['data'])) {
        return $page['data'];
    }
    if ($disabled) {
        $page['links'] = '';
        $page['page_numbers'] = array(
            'current' => 1,
            'total'   => 1
        );
    }
    return $page;
}

/**
 * lekerdezi a rendszer alapbeallitasat, majd a jelzo session erteket beallitja 0-re, hogy mar nem kell frissiteni
 * lekerdezesnel be kell allitani a MDB2_FETCHMODE_ORDERED modot, mert egyebkent csak nevvel lehetne hivatkozni a tabla mezoire
 * igy nem kell tudni a neveket, eleg a sorszamukkal hivatkozni rajuk
 * ez az alapertelmezett beallitas, de kenyelmesebb ha nevvel hivatkozunk a tabla mezoire, ezert at lett allitva a config.php file-ban erre
 */
function site_properties()
{
	global $mdb2;

	$query = "
		SELECT *
		FROM iShark_Configs
	";
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow(MDB2_FETCHMODE_ORDERED)) {
		foreach ($result->getColumnNames($result) as $colname => $key) {
			$_SESSION['site_'.$colname] = $row[$key];
		}
	}
	//a session erteket beallitjuk 0-ra, hogy ne kerdezze le ujra a tablat
	unset($_SESSION['is_siteprop_update']);
	$_SESSION['is_siteprop_update'] = 0;
}

/**
 * Visszaad egy megadott datumot a nyelvi file-ban meghatarozott formatum szerint,
 *
 * @param string $date  Egy mysql date, vagy datetime form�j� adat.
 *        ha az �rt�ke �res, akkor az aktu�lis d�tummal dolgozik.
 * @param string $type
 *        alap�rtelmezett: 'text'
 * 	      Ennek �rt�ke a 'config' nyelvi modulban defini�lt 'date_format_'.$type-nak felel meg,
 * 		  teh�t alap�rtelmez�sben
 * 		  a form�tumot a 'config' modul 'date_format_text' nev� nyelvi valtozobol veszi.
 *
 * 		- A nyelvi valtozo formazasi szabalyai:
 * 			{yy}    - evszam 2 jeggyel
 *          {yyyy}  - evszam 4 jegyen
 *          {month} - honap betuvel kiirva
 *          {mm}    - honap 2 szamjegyen
 *          {m}     - honap 1 vagy 2 szamjegyen az ertektol fuggoen
 *          {dd}    - nap 2 szamjegyen
 *          {d}     - nap 1 vagy 2 szamjegyen
 *          {dow}   - a het napja betuvel kiirva
 *          {hh}    - ora 2 szamjegyen
 *          {h}     - ora 1 vagy 2 szamjegyen
 *          {ii}    - perc 2 szamjegyen
 *          {i}     - perc 1 vagy 2 szamjegyen
 *          {ss}    - masodperc 2 szamjegyen
 *          {s}     - masodperc 1 vagy 2 szamjegyen
 *
 * 		pl.:         {yyyy}. {month} {d}, {dow}
 *      kimenet:     2007. febru�r 1, cs�t�rt�k
 *
 * @return	string	napi datum megfeleloen formazva
 */
function get_date($date = NULL, $type='text')
{
	global $locale;
	if (empty($date) || !preg_match('/^\d{4}.\d{2}.\d{2}(.\d{2}.\d{2}.\d{2})?$/', $date)) {
		$date = date('Y-m-d H:i:s');
	}


	$format = $locale->get('config', 'date_format_'.$type);

	// Ha sql formaban keri a mai datumot, vagy ha nincs formatum megadva:
	if ($type == 'sql' || $format == 'date_format_'.$type) {
		return $date;
	}

	$year4 = substr($date, 0, 4);
	$year2 = substr($date, 2, 2);
	$month = substr($date, 5, 2);
	$day   = substr($date, 8, 2);

	$hour  = substr($date, 11,2);
	$min   = substr($date, 14,2);
	$sec   = substr($date, 17,2);

	if (empty($hour)) $hour = '00';
	if (empty($min)) $min = '00';
	if (empty($sec)) $sec = '00';

	$month_txt = $locale->get('config', 'date_month_'.$month);
	if ($month_txt == 'date_month_'.$month) {
		$month_txt = $month;
	}

	$timestamp = strtotime($year4.'-'.$month.'-'.$day.'Z');

	$downum = date('w', $timestamp);
	$dow = $locale->get('config', 'date_dow_'.($downum == '0' ? 7 : $downum));

	$content[0] = "/\{yyyy\}/";	 $csere[0] = $year4;
	$content[]  = "/\{yy\}/";    $csere[]  = $year2;
	$content[]  = "/\{mm\}/";    $csere[]  = $month;
	$content[]  = "/\{m\}/";     $csere[]  = "".intval($month);
	$content[]  = "/\{month\}/"; $csere[]  = $month_txt;
	$content[]  = "/\{dd\}/";    $csere[]  = $day;
	$content[]  = "/\{d\}/";     $csere[]  = "".intval($day);
	$content[]  = "/\{dow\}/";   $csere[]  = $dow;
	$content[]  = "/\{hh\}/";    $csere[]  = $hour;
	$content[]  = "/\{h\}/";     $csere[]  = "".intval($hour);
	$content[]  = "/\{ii\}/";    $csere[]  = $min;
	$content[]  = "/\{i\}/";     $csere[]  = "".intval($min);
	$content[]  = "/\{ss\}/";    $csere[]  = $sec;
	$content[]  = "/\{s\}/";     $csere[]  = "".intval($sec);

	$retval = preg_replace($content, $csere, $format);

	return $retval;
}

/**
 * utolso latogatas idopontjat ellenorizzuk, modositjuk
 */
function lastvisit()
{
	global $mdb2;
	$query = "
		SELECT lastvisit_date
		FROM iShark_Users
		WHERE user_id = '".$_SESSION['user_id']."' AND is_active = '1'
	";
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		//ha meg ures a mezo es nincs meg a session, akkor elso latogatas, feltoltjuk
		if (($row['lastvisit_date'] == '' || $row['lastvisit_date'] == '0000-00-00 00:00:00') && !isset($_SESSION['lastvisit'])) {
			$_SESSION['lastvisit'] = date($_SESSION['site_dateformat'], time());
			$query = "
				UPDATE iShark_Users
				SET lastvisit_date = NOW()
				WHERE user_id = '".$_SESSION['user_id']."' AND is_active = '1'
			";
			$mdb2->exec($query);
		}
		//ha nincs meg a session, viszont van adat a mezoben, akkor a session erteke a mezo lesz
		if (!isset($_SESSION['lastvisit']) && $row['lastvisit_date'] > '0000-00-00 00:00:00') {
			$_SESSION['lastvisit'] = $row['lastvisit_date'];
			$query = "
				UPDATE iShark_Users
				SET lastvisit_date = NOW()
				WHERE user_id = '".$_SESSION['user_id']."' AND is_active = '1'
			";
			$mdb2->exec($query);
		}
	}
}


function check_perm($act = NULL, $mid, $need = 0, $module = NULL, $type = "admin")
{
    static $righs = array();
    static $menu_module = array();
    global $mdb2;
    
    // Ha van moduln궺
    if (isset($_REQUEST["p"]) || !empty($module)) {
        $module_name = !empty($module) ? $module : $mdb2->escape($_REQUEST["p"]);
        
        
    // Ha men𰯮t azonos van:
    } elseif (isset($_REQUEST['mid']) || !empty($mid)) {
        $mid = !empty($mid) ? $mid : intval($_REQUEST["mid"]);
        
        if (!isset($menu_module[$type][$mid])) {
            $result =& $mdb2->query("
                SELECT M.file_name as file_name
                FROM iShark_Menus Me, iShark_Modules M 
                WHERE Me.menu_id = $mid AND 
                    M.module_id=Me.module_id AND 
                    Me.is_active='1' AND 
                    M.is_active='1' AND 
                    M.is_installed='1' AND
                    M.type='$type'
                ");
            if ($row = $result->fetchRow()) {
                $menu_module[$type][$mid] = $row["file_name"];
            } else {
                return (!$need);
            }
        } 
        $module_name = $menu_module[$type][$mid];
    } else {
        return ($need==0);
    }
    
    if (!isset($rights[$type][$module_name])) {
        $rights[$type][$module_name] = array();
        if (isset($_SESSION['user_id'])) {
             $result =& $mdb2->query($query = "
                    SELECT F.function_name
                    FROM iShark_Modules M, iShark_GroupUsers GU, iShark_Groups G, iShark_Rights2 R
                    LEFT JOIN iShark_Functions F ON R.function_id = F.function_id 
                    WHERE 
                        M.file_name = '$module_name' AND M.type='$type' AND M.is_active='1' AND M.is_installed='1'
                        AND R.module_id=M.module_id
                        AND R.group_id=GU.group_id
                        AND GU.group_id=G.group_id
                        AND GU.user_id=$_SESSION[user_id]
                        AND G.is_active='1'
                        AND G.is_deleted<>'1'
                    GROUP BY R.function_id
             ");
             //print $query;
             $rights[$type][$module_name] = $result->fetchCol();
        }
    } 
    return (!$need) || in_array($act, $rights[$type][$module_name]);
}

/**
 * ellenorzi a jogosultsagokat az adott felhasznalohoz
 *
 * @param	string	megmondja, hogy milyen funkcional akarjuk ellenorizni a jogosultsagot
 * @param	int		menu azonositoja
 * @param	int		szukseges-e a jogosultsag ellenorzes vagy sem
 * @param	string	megadhatjuk a modul nevet, ekkor nev alapjan keresi ki, hogy mire vizsgalja a jogosultsagot
 * @param	string	megadjuk, hogy fooldali (index), vagy adminisztracios (admin) modult akarunk vizsgalni
 *
 * @return	bool	ha true, akkor megnezheti az oldalt, ha false, akkor nem
 */
function check_perm_old($act = NULL, $mid, $need = 0, $module = NULL, $type = "admin")
{
	global $mdb2;

	if (isset($_REQUEST['p']) || !empty($module)) {
		$p = (!empty($module) ? $module : $mdb2->escape($_REQUEST['p']));
		$query = "
			SELECT m.module_id AS mmodid, '0' AS mcontid
			FROM iShark_Modules m
			WHERE m.file_name = '$p' AND is_active = '1' AND type = '".$type."'
		";
	} elseif (isset($_REQUEST['mid']) || !empty($mid)) {
		$mid = (!empty($mid) ? $mid : (int) $_REQUEST['mid']);
		$query = "
			SELECT m.module_id AS mmodid, m.content_id AS mcontid
			FROM iShark_Menus m
			WHERE menu_id = '$mid'
		";
	} else {
	    return FALSE;
	}
	$result =& $mdb2->query($query);
	if (!$row = $result->fetchRow()) {
		return FALSE;
	}
	$module_id  = $row['mmodid'];
	$content_id = $row['mcontid'];

	//lekerdezzuk, hogy milyen funkciok erhetoek el az adott modulhoz
	$func_array = array();
	$query = "
		SELECT f.function_id AS fid, f.function_name AS fname
		FROM iShark_Functions f
		WHERE f.module_id = '$module_id'
	";

	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$func_array[$row['fname']] = $row['fid'];
		}

		if ($act != NULL) {
			$func = $func_array[$act];
		}
	} else {
		return true;
	}

	//lekerdezzuk a csoportokat, akik hozzaferhetnek az adott menuponthoz az adott jogosultsaggal
	if (!empty($func)) {
		$group_array = array();
		$query = "
			SELECT gr.group_id AS gid
			FROM iShark_Rights r, iShark_Rights_Functions rf, iShark_Groups_Rights gr
			WHERE r.right_id = rf.right_id AND rf.function_id = '$func' AND gr.right_id = r.right_id AND
		";
		if ($module_id != 0) {
			$query .= "
				r.module_id = '$module_id'
			";
		}
		if ($content_id != 0) {
			$query .= "
				r.content_id = '$content_id'
			";
		}
		$result = $mdb2->query($query);
		//ha nincs talalat, akkor nincs beallitva hozza semmilyen jog, ezert true-val terunk vissza
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow())
			{
				$group_array[] = $row['gid'];
			}
		} else {
			if ($need == 0) {
				return true;
			} else {
				return false;
			}
		}
	}
	else if (empty($func) && $need != 0) {
		return false;
	}
	else {
		return true;
	}

	//ha letezik a session, csak akkor vizsgaljuk
	if (isset($_SESSION['user_groups'])) {
		//letrehozzuk a user session-bol a tombot, hogy milyen csoportok tagja
		$usergroup_array = explode(" ", $_SESSION['user_groups']);

		//megnezzuk, hogy a ket tombben vannak-e megegyezo azonositok
		if (count(array_intersect($usergroup_array, $group_array)) > 0) {
			return true;
		} else {
			if ($need == 0) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		if ($need == 0) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * az ekezets karaktereket csereli ekezet nelkuliekre
 *
 * @param	string	a szoveg, amiben cserelni kell a karaktereket
 *
 * @return	string	a helyes szoveg
 */
function change_hunchar($text)
{
	
	$text = htmlentities($text);
	
	$mit    = array("í", "é", "á", "ú", "ő", "ü", "ö", "ű", "ó", "Í", "É", "Á", "Ú", "Ó", "Ü", "Ö", "Ű", "Ő", " ", ",");
	$mire   = array("i", "e", "a", "u", "o", "u", "o", "u", "o", "i", "e", "a", "u", "o", "u", "o", "u", "o", "_", "");
	$szoveg = str_replace($mit, $mire, strtolower($text));
	

	return $szoveg;
}


/**
 * megnezi, hogy a felhasznalo hozzaferhet-e admin menukhoz
 *
 * @return 	bool	ha hozzafer true, ha nem, akkor false
 */
function is_adminlink()
{
	global $mdb2;

	if (isset($_SESSION['user_id'])) {
		$query = "
			SELECT m.menu_id AS mid, m.menu_name AS mname
			FROM iShark_Menus m
			LEFT JOIN iShark_Modules mo ON mo.module_id = m.module_id
			LEFT JOIN iShark_Rights r ON r.module_id = mo.module_id
			LEFT JOIN iShark_Groups_Rights gr ON gr.right_id = r.right_id
			LEFT JOIN iShark_Groups g ON g.group_id = gr.group_id
			LEFT JOIN iShark_Groups_Users gu ON gu.group_id = g.group_id
			WHERE mo.is_active = 1 AND m.type = 'admin' AND gu.user_id = '".$_SESSION['user_id']."'
			ORDER BY sortorder
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
/**
 * logger - Napl�ozas funkcio
 *
 * Beallitastol fuggoen menti a parameterben
 * megadott menuponthoz tartozo modul akciojat
 * az iShark_Logs tablaba.
 *
 * @param mixed $act
 * @param int $mid
 * @param string $description
 * @access public
 * @return void
 */
function logger($act, $mid=0, $description='')
{
	global $mdb2;

	if ($_SESSION['site_is_logging'] != '1') {
		return;
	}

	$mid = 0;
	$p = '';
	if (isset($_REQUEST['mid'])) {
		$mid = (int) $_REQUEST['mid'];
		$query = "
			SELECT module_id FROM iShark_Menus WHERE menu_id = '$mid'
		";
	} elseif (isset($_REQUEST['p'])) {
		$p = $mdb2->escape($_REQUEST['p']);
		$query = "
			SELECT module_id FROM iShark_Modules WHERE file_name = '$p'
		";
	} else {
		return;
	}

	$result =& $mdb2->query($query);
	if (!$row = $result->fetchRow()) {
		return;
	}

	$module_id  = $row['module_id'];
	if (!empty($module_id)) {
		if (isset($_SESSION['user_id'])) {
			$user_id = $_SESSION['user_id'];
		} else {
			$user_id = "";
		}

		$insert = "
			INSERT INTO iShark_Logs
			(time, user_id, module_id, function_name, description)
			VALUES
			(NOW(), '".$user_id."', '$module_id', '$act', '".$description."')
		";
		$affected = $mdb2->exec($insert);
	}
	return;
}

/**
 * visszaadja a lekerdezett felhasznalo ip cimet
 *
 * @return	string	ip cim
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
		if ($ip) {
			array_unshift($ips, $ip);
			$ip = FALSE;
		}

		for ($i = 0; $i < count($ips); $i++)
		{
			// Skip RFC 1918 IP's 10.0.0.0/8, 172.16.0.0/12 and
			// 192.168.0.0/16 -- jim kill me later with my regexp pattern
			// below.
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

	// Return with the found IP or the remote address
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * isModule - leellenorzi, hogy a parameterkent megadott modul aktiv-e
 *
 * @param	string	modul neve
 * @param	string	modul tipusa (index vagy admin)
 *
 * @access public
 *
 * @return void
 */
function isModule($name, $type = "admin")
{
	global $mdb2;
	static $mods = array();

	if (!isset($mods[$type])) {
		$query = "
			SELECT file_name
			FROM iShark_Modules
			WHERE type = '".$type."' AND is_active='1'
		";
		$result = $mdb2->query($query);
		$mods[$type] = $result->fetchCol();
	}

	return in_array($name, $mods[$type]);
}

/**
 * addFlood - letrehoz egy cookie-t, amiben letarolja a modulhoz, hogy mikor volt az utolso bejegyzes hozza
 * 
 * @param string   module neve
 * 
 * @access public
 * 
 * @return void
 */
function addFlood($module)
{
    setcookie("iShark_Flood[".$module."]", time(), 0);
}

/**
 * checkFlood - ellenorzi, hogy beallitasoktol fuggoen lehet-e ujabb hozzaszolast fuzni
 * 
 * @param string   module neve
 * @param string   aktualis datum
 * 
 * @return boolean
 */
function checkFlood($module, $time)
{
    // ha letezik a cookie
    if (!empty($_COOKIE['iShark_Flood'][$module])) {
        // ha a cookie letrehozasa es a beallitott ido kozott meg nem telt el a megfelelo ido, akkor nem lehet irni
        if ($_COOKIE['iShark_Flood'][$module] + $time > time()) {
            return false;
        } else {
            return true;
        }
	} else {
	    return true;
	}
}

/**
 * getMetaTags - meta description, meta keywords adatokat adja vissza
 * 
 * @param integer   menu azonosito
 * 
 * @return void
 */
function getMetaTags($mid = NULL)
{
    global $mdb2;

    $meta = array();

    //fooldal
    if ($mid == NULL) {
        $meta['description'] = $_SESSION['site_sys_meta_description'];
        $meta['keywords']    = $_SESSION['site_sys_meta_keywords'];
    }

    //ha a menu_id nem NULL es engedelyezve van a modul
    if ($mid != NULL && isModule('searchwords', 'admin')) {
        $query = "
			SELECT description, keywords 
			FROM iShark_Searchwords 
			WHERE menu_id = $mid
		";
        $result =& $mdb2->query($query);
        if ($result->numRows() > 0) {
            $row = $result->fetchRow();

            $meta['description'] = $row['description'];
            $meta['keywords']    = $row['keywords'];
        }
        //ha nincs egyetlen talalat sem, akkor eloszor megprobaljuk a fooldali beallitasokat
        else {
            $query = "
				SELECT description, keywords 
				FROM iShark_Searchwords 
				WHERE menu_id = 0
			";
            $result =& $mdb2->query($query);
            if ($result->numRows() > 0) {
                $row = $result->fetchRow();

                if (!empty($row['description'])) {
                    $meta['description'] = $row['description'];
                } else {
                    $meta['description'] = $_SESSION['site_sys_meta_description'];
                }

                if (!empty($row['keywords'])) {
                    $meta['keywords'] = $row['keywords'];
                } else {
                    $meta['keywords'] = $_SESSION['site_sys_meta_keywords'];
                }
            } else {
                $meta['description'] = $_SESSION['site_sys_meta_description'];
                $meta['keywords']    = $_SESSION['site_sys_meta_keywords'];
            }
        }
    }

    return $meta;
}

/**
 * clearCaptcha - kitorli a captcha altal gyartott fajlokat, amik mar felslegesek
 */
function clearCaptcha()
{
    global $mdb2;

    $files = directory_list('files/', 'png');

    //kiszedjuk a session id-ket a file-okbol
    $search_sess = array();
    if (is_array($files) && !empty($files)) {
        foreach ($files as $key => $file) {
            $sess_id = explode('_', $file);
            //ha tobb elemu a tomb, akkor a masodik elem kell (elso elem a modulra utal)
	        if (count($sess_id) > 1) {
	            $search_sess[$key]['type'] = $sess_id[0];
	            $search_sess[$key]['id']   = $sess_id[1];
	        } else {
	            $search_sess[$key]['type'] = '';
	            $search_sess[$key]['id']   = $sess_id[0];
	        }
        }
    }

    //vegigmegyunk a session id tombon, es megnezzuk, hogy letezik-e a session a tablaban, ha nem, akkor toroljuk
	if (is_array($search_sess) && !empty($search_sess)) {
	    foreach ($search_sess as $sess) {
	        $query = "
				SELECT session_id
				FROM iShark_Sessions
				WHERE md5(session_id) = '".$sess['id']."'
			";
	        $result =& $mdb2->query($query);
	        //ha nincs ilyen mezo, akkor torolheto  file
	        if ($result->numRows() == 0) {
	            if ($sess['type'] == '') {
	                @unlink('files/'.$sess['id'].'.png');
	            } else {
	                @unlink('files/'.$sess['type'].'_'.$sess['id'].'.png');
	            }
	        }
	    }
	}
}

/*
 * changeRelativeAbsolute - kicsereli a href es az src tag-eket, berakja ele a beallitott teljes eleresi utat
 * 
 * @param string	a szoveg, amiben keressuk a cserelendo dolgokat
 * 
 * @return void
 */
function changeRelativeAbsolute($string)
{
    $data = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="'.$_SESSION['site_sitehttp'].'/$2$3', $string);
    return $data;
}

?>