<?php

/**
 * Ellenorzi, hogy a megadott neven letezik-e mar cimke
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_tags_addtag($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT tag_name
		FROM iShark_Tags
		WHERE tag_name = '".strtolower($values['tagname'])."'
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['tagname'] = $locale->get('error_tag_exists');
	}

	return empty($errors) ? true: $errors;
}

/**
 * Ellenorzi, hogy a megadott neven, az adott kategorian kivul, letezik-e mar kategoria
 *
 * @param	array	a formban szereplo mezoket tartalmazza
 *
 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
 */
function check_tags_modtag($values)
{
	global $mdb2, $locale;

	$errors = array();

	//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
	$query = "
		SELECT tag_name
		FROM iShark_Tags
		WHERE tag_name = '".strtolower($values['tagname'])."' AND tag_id != ".$values['id']."
	";
	$name_check =& $mdb2->query($query);
	if ($name_check->numRows() != 0) {
		$errors['tagname'] = $locale->get('error_tag_exists');
	}

	return empty($errors) ? true: $errors;
}

?>
