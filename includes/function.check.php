<?php

/**
 * Ellenorzi, hogy a megadott felhasznaloinevvel, e-mail cimmel letezik-e felhasznalo
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_userlostpass($values)
{
	global $mdb2, $locale;

	$errors = array();

	$query = "
		SELECT user_id 
		FROM iShark_Users 
		WHERE name = '".$values['name']."' AND email = '".$values['email']."'
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$errors['name'] = $locale->get('error_lostpass_notexists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * check_oldpassword - jelszï¿½ mï¿½dosï¿½tï¿½s esetï¿½n rï¿½gi jelszï¿½ ellenï¿½rzï¿½se 
 * 
 * @param mixed $values 
 * @access public
 * @return mixed
 */
function check_oldpassword($values)
{
	global $mdb2, $locale;

	$errors = array();

	if (isset($values['modpass']) && $values["modpass"] == '1') {
		$op = md5($values['oldpass']);
		$query = "
			SELECT COUNT(*) AS cnt 
			FROM iShark_Users 
			WHERE user_id = ".$_SESSION['user_id']." AND password = '$op'
		";
		$result =& $mdb2->query($query);
		if ($row = $result->fetchRow()) {
			if ($row['cnt'] == '0') {
				$errors['oldpass'] = $locale->get('error_wrong_pass');
			}
		} else {
			header("Location: index.php");
			exit;
		}
		if (strlen($values['pass1']) < $_SESSION['site_minpass']) {
			$errors['pass1'] = $locale->get('error_minpass');
		}
	}
	return empty($errors) ? TRUE : $errors;
}

/**
 * Ellenorzi, hogy a megadott neven, e-mail cimmel letezik-e mar felhasznalo
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_adduser($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	/*$query = "
		SELECT name 
		FROM iShark_Users 
		WHERE name = '".$values['name']."'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['name'] = $locale->get('error_name_exists');
	}
*/
	//ellenorizzuk, hogy letezik-e mar ilyen e-mail cim
	$query = "
		SELECT email 
		FROM iShark_Users 
		WHERE email = '".$values['email']."' AND is_deleted = 0
	";
	$email_check =& $mdb2->query($query);
	if ($email_check->numRows() != 0) {
		$errors['email'] = "Ez az email cím már regisztrálva van!";
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy a megadott neven, e-mail cimmel, az adott felhasznalon kivul, letezik-e mar felhasznalo
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_moduser($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT name 
		FROM iShark_Users 
		WHERE name = '".$values['name']."' AND user_id != '".$values['uid']."'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['name'] = $locale->get('error_name_exists');
	}

	//ellenorizzuk, hogy letezik-e mar ilyen e-mail cim
	$query = "
		SELECT email 
		FROM iShark_Users 
		WHERE email = '".$values['email']."' AND user_id != '".$values['uid']."'
	";
	$email_check =& $mdb2->query($query);
	if ($email_check->numRows() != 0) {
		$errors['email'] = $locale->get('error_email_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy a megadott neven letezik-e mar csoport
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_addgroup($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT group_name 
		FROM iShark_Groups 
		WHERE group_name = '".$values['name']."'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['name'] = $locale->get('error_name_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy a megadott neven, az adott csoporton kivul, letezik-e mar csoport
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_modgroup($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT group_name 
		FROM iShark_Groups 
		WHERE group_name = '".$values['name']."' AND group_id != '".$values['gid']."'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['name'] = $locale->get('error_name_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Aktivalja, deaktivalja az adott tartalmat
 *
 * @param	string	a tabla neve, ahol keressuk az ativalando, deaktivalando mezot
 * @param	string	egyedi azonosito mezojenek neve
 * @param	string	azonosito, ami megmutatja, hogy melyik sort keressuk
 *
 * @return	int		ha aktivaltunk, akkor 1, ha deaktivaltunk, akkor 0
 */
function check_active($table, $row, $id)
{
	global $mdb2;

	$query = "
		SELECT is_active AS act 
		FROM $table 
		WHERE $row = $id
	";
	$result = $mdb2->query($query);
	$active = $result->fetchRow();
	if ($active['act'] == 0 || $active['act'] == 2) {
		$query = "
			UPDATE $table 
			SET is_active = 1 
			WHERE $row = $id
		";
	} else {
		$query = "
			UPDATE $table 
			SET is_active = 0 
			WHERE $row = $id
		";
	}
	$mdb2->exec($query);
}

/**
 * Ellenorzi, hogy a letezik-e mar ez a jogosultsag
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_addrights($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ezen a neven jogosultsag
	$query = "
		SELECT r.right_name AS rname 
		FROM iShark_Rights r 
		WHERE r.right_name = '".$values['name']."'
	";
	$right_check =& $mdb2->query($query);
	if ($right_check->numRows() != 0) {
		$errors['name'] = $locale->get('admin_rights', 'error_name_exists');
	}

	//megcsinaljuk a tombbol a mezok listajat
	if (isset($values['group']) && is_array($values['group']) && count($values['group']) != 0) {
		$groups = implode(',', $values['group']);
	}

	//ellenorizzuk, hogy ez a csoport ehhez a modulhoz kapott-e mar jogot
	if ($values['modules'] != 0) {
		$modules = $values['modules'];
	}
	if ($values['modulesadm'] != 0) {
		$modules = $values['modulesadm'];
	}

	if (isset($groups) && $groups != "") {
		$query = "
			SELECT r.right_id AS rid 
			FROM iShark_Rights r, iShark_Groups_Rights gr 
			WHERE r.module_id = '$modules' AND r.content_id = '".$values['contents']."' 
				AND gr.right_id = r.right_id AND gr.group_id IN ('".$groups."')
			";
			$group_check =& $mdb2->query($query);
			if ($group_check->numRows() != 0) {
				$errors['name'] = $locale->get('admin_rights', 'error_group_exists');
			}
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy a letezik-e mar ez a jogosultsag, a megadott azonositojun kivul
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_modrights($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ezen a neven jogosultsag
	$query = "
		SELECT r.right_name AS rname 
		FROM iShark_Rights r 
		WHERE r.right_name = '".$values['name']."' AND r.right_id != '".$values['rid']."'
	";
	$right_check =& $mdb2->query($query);
	if ($right_check->numRows() != 0) {
		$errors['name'] = $locale->get('admin_rights', 'error_name_exists');
	}

	//megcsinaljuk a tombbol a mezok listajat
	if (isset($values['group']) && is_array($values['group']) && count($values['group']) != 0) {
		$groups = implode(',', $values['group']);
	}
	//ellenorizzuk, hogy ez a csoport ehhez a modulhoz kapott-e mar jogot
	if ($values['modules'] != 0) {
		$modules = $values['modules'];
	}
	if ($values['modulesadm'] != 0) {
		$modules = $values['modulesadm'];
	}

	if (isset($groups) && $groups != "") {
		$query = "
			SELECT r.right_id AS rid 
			FROM iShark_Rights r, iShark_Groups_Rights gr 
			WHERE r.module_id = '$modules' AND r.content_id = '".$values['contents']."' 
				AND gr.right_id = r.right_id AND gr.group_id IN ('".$groups."') AND r.right_id != '".$values['rid']."'
			";
			$group_check =& $mdb2->query($query);
			if ($group_check->numRows() != 0) {
				$errors['name'] = $locale->get('admin_rights', 'error_group_exists');
			}
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, az idozito beallitasait
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_timer($values)
{
	global $locale;

	$errors = array();

	if (isset($values['timer_start']) && $values['timer_start'] != "") {
		//szetszedjuk a checkdate-nek a kezdo datumokat
		$dateexp_start  = explode(" ", $values['timer_start']);
		$date_start     = explode("-", $dateexp_start[0]);
		$time_start     = explode(":", $dateexp_start[1]);
		$date_def_start = array(
			'Y' => $date_start[0],
			'm' => $date_start[1],
			'd' => $date_start[2],
			'H' => $time_start[0],
			'i' => $time_start[1],
			's' => $time_start[2],
		);
		//ha van idozites kezdet, de nincs idozites vege
		if (isset($values['timer_start']) && (!isset($values['timer_end']) || $values['timer_end'] == "")) {
			$errors['date_end'] = $locale->get('admin', 'function_error_timer1');
		}
		//ha hibas a kezdo datum (februar 30 pl.)
		elseif (checkdate($date_def_start['m'], $date_def_start['d'], $date_def_start['Y']) === FALSE) {
			$errors['date_start'] = $locale->get('admin', 'function_error_timer2');
		}
		else {
			//ha a kezdeti idopont kisebb mint a jelenlegi ido, akkor hiba
			if ($values['timer_start'] < date($_SESSION['site_dateformat'])) {
				$errors['date_start'] = $locale->get('admin', 'function_error_timer3');
			}
		}
	}

	if (isset($values['timer_end']) && $values['timer_end'] != "") {
		//szetszedjuk a checkdate-nek a vege datumokat
		$dateexp_end   = explode(" ", $values['timer_end']);
		$date_end      = explode("-", $dateexp_end[0]);
		$time_end      = explode(":", $dateexp_end[1]);
		$date_def_end  = array(
			'Y' => $date_end[0],
			'm' => $date_end[1],
			'd' => $date_end[2],
			'H' => $time_end[0],
			'i' => $time_end[1],
			's' => $time_end[2],
		);
		//ha van idozites vege, de nincs idozites kezdet
		if (isset($values['timer_end']) && (!isset($values['timer_start']) || $values['timer_start'] == "")) {
			$errors['date_start'] = $locale->get('admin', 'function_error_timer4');
		}
		//ha hibas a vegso datum (februar 30 pl.)
		elseif (checkdate($date_def_end['m'], $date_def_end['d'], $date_def_end['Y']) === FALSE) {
			$errors['date_end'] = $locale->get('admin', 'function_error_timer2');
		}
		else {
			//ha a vege idopont kisebb, mint a kezdeti idopont, akkor hiba
			if ($values['timer_end'] < $values['timer_start']) {
				$errors['date_end'] = $locale->get('admin', 'function_error_timer5');
			}
		}
	}

	return empty($errors) ? true: $errors;
}

/**
 * check_link - ellenorzi a megadott linket, levagja a bevezeto http://reszt
 *
 * @param	string	ellenorzendo link
 *
 * @access	public
 *
 * @return	string
 */
function check_link($link)
{
	if (substr($link, 0, 7) == 'http://') {
		$link = substr($link, 7);
		return $link;
	} else {
		return $link;
	}
}

/**
 * checkUserExtraFields - ellenorzi, hogy ne legyen ket ugyanolyan nevu mezo a tablaban
 * 
 * @param	array	a formban szereplo mezoket tartalmazza
 * 
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function checkUserExtraFields($values)
{
    global $mdb2, $locale;

    $errors = array();

    $query = "
		DESCRIBE ".DB_USERS.".iShark_Users
	";
    $result =& $mdb2->query($query);
    while ($row = $result->fetchRow())
    {
        if ($row['field'] == $values['value']) {
            $errors['value'] = $locale->get('admin_users', 'system_error_value_exists');
        }
    }

    return empty($errors) ? true: $errors;
}

?>