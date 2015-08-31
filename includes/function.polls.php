<?php

	/**
	 * Ellenorzi, hogy a megadott neven letezik-e a mar kerdes
	 *
	 * @param	array	a formban szereplo mezoket tartalmazza
	 *
	 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
	 */
	function check_addquestion($values)
	{
		global $mdb2, $locale;

		$errors = array();

		//ellenorizzuk, hogy letezik-e mar ilyen neven kerdes
		$query = "
			SELECT title 
			FROM iShark_Polls 
			WHERE title = '".$values['question']."'
		";
		$name_check =& $mdb2->query($query);
		if ($name_check->numRows() != 0) {
			$errors['question'] = $locale->get('error_name_exists');
		}

		return empty($errors) ? true: $errors;
	}

	/**
	 * Ellenorzi, hogy a megadott neven letezik-e a mar kerdes
	 *
	 * @param	array	a formban szereplo mezoket tartalmazza
	 *
	 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
	 */
	function check_modquestion($values)
	{
		global $mdb2, $locale;

		$errors = array();

		//ellenorizzuk, hogy letezik-e mar ilyen neven kerdes
		$query = "
			SELECT title 
			FROM iShark_Polls 
			WHERE title = '".$values['question']."' AND poll_id != ".$values['pid']."
		";
		$name_check =& $mdb2->query($query);
		if ($name_check->numRows() != 0) {
			$errors['question'] = $locale->get('error_name_exists');
		}

		return empty($errors) ? true: $errors;
	}

?>
