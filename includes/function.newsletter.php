<?php

function html_to_text($html) {
	return html_entity_decode(strip_tags(preg_replace('#<br ?/?>|<p( [^>]*)?>#', "\n", $html)));
}

/**
 * is_sent - Smarty függvény, megvizsgálja, hogy az adott hírlevél el lett e küldve.
 *
 * @param mixed $params
 * @param mixed $smarty
 * @access public
 * @return void
 */
function is_sent($params, &$smarty)
{
    global $mdb2;
    if (isset($params['nid'])) {
        $query = "
			SELECT COUNT(*) AS cnt
			FROM iShark_Newsletter_Sends_Dates
			WHERE newsletter_id = ".$params['nid']."
		";
        $result =& $mdb2->query($query);
        if ($row = $result->fetchRow()) {
            return $row['cnt'];
        }
    }
    return 0;
}

/**
 * check_addNewsletterUser - Ellenőrzi hogy létezik e már a megadott e-mail cím. 
 * 
 * @param mixed $values 
 * @access public
 * @return void
 */
function check_addNewsletterUser($values)
{
	global $mdb2, $locale;

	$errors = array();
		
	$email = $mdb2->escape($values['email']);

	$query = "
		SELECT email 
		FROM iShark_Newsletter_Users
		WHERE email = '$email'
	";

	$mail_check =& $mdb2->query($query);
	if ($mail_check->numRows() != 0) {
		$errors['email'] = $locale->get('users_error_mail_exists', 'admin_newsletter');
	}
	return empty($errors) ? true : $errors;
}
	
/**
 * check_modNewsletterUser 
 * 
 * @param mixed $values 
 * @access public
 * @return void
 */
function check_modNewsletterUser($values)
{
	global $mdb2, $locale;

	$errors = array();

	$email = $mdb2->escape($values['email']);
	$uid   = intval($values['uid']);
		
	//ellenorizzuk, hogy letezik-e mar ilyen e-mail cim
	$query = "
		SELECT email 
		FROM iShark_Newsletter_Users 
		WHERE email = '$email' AND newsletter_user_id != '$uid'
	";
	$email_check =& $mdb2->query($query);
	if ($email_check->numRows() != 0) {
		$errors['email'] = $locale->get('users_error_mail_exists', 'admin_newsletter');
	}

	return empty($errors) ? true: $errors;
}
	
/**
 * check_addNewslettergroup 
 * 
 * @param mixed $values 
 * @access public
 * @return void
 */
function check_addNewslettergroup($values)
{
	global $mdb2, $locale;


	$errors = array();

	$name = $mdb2->escape($values['name']);

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT group_name 
		FROM iShark_Newsletter_Groups 
		WHERE group_name = '$name'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['name'] = $locale->get('groups_error_name_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * check_modNewslettergroup 
 * 
 * @param mixed $values 
 * @access public
 * @return void
 */
function check_modNewslettergroup($values)
{
	global $mdb2, $locale;

	$errors = array();

	$name = $mdb2->escape($values['name']);
	$gid  = $mdb2->escape($values['gid']);

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT group_name 
		FROM iShark_Newsletter_Groups 
		WHERE group_name = '$name' AND newsletter_group_id != '$gid'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['name'] = $locale->get('groups_error_name_exists');
	}

	return empty($errors) ? true: $errors;
}

?>