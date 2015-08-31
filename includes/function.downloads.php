<?php

	function get_aktdir($parent = 0, $akt_szint = 1)
	{
		global $mdb2;

		$ret = array();
		$dir = '';

		while ($parent != 0)
		{
			$akt_szint++;
			$query = "
				SELECT * 
				FROM iShark_Downloads 
				WHERE download_id = $parent
			";
			$result = $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$dir = '/'.$row['name'].$dir;
				$parent = $row['parent'];
			}
		}
		if (empty($dir)) {
			$dir = '/';
		}

		$ret['dir']   = $dir;
		$ret['szint'] = $akt_szint;

		return $ret;
	}

	/**
	 * filelist - File lista generálása
	 * 
	 * @access public
	 * @return string
	 */
	function filelist($dir, $order = 'name', $parent = 0, $isact = 0)
	{
		global $mdb2;

		$dir_array  = array();
		$dir_array2 = array();
		$i = 0;
		$j = 0;

		$query = "
			SELECT * 
			FROM iShark_Downloads 
			WHERE parent = $parent 
		";
		if ($isact == 1) {
			$query .= "
				AND is_active = 1 
			";
		}
		$query .= "
			ORDER BY type, $order
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			$dir_array[$i]['did']      = $row['download_id'];
			$dir_array[$i]['name']     = $row['name'];
			$dir_array[$i]['size']     = number_format($row['size']/1024, 0, ',', ' ');
			$dir_array[$i]['type']     = $row['type'];
			$dir_array[$i]['add_date'] = $row['add_date'];
			$dir_array[$i]['mod_date'] = $row['mod_date'];
			$dir_array[$i]['is_act']   = $row['is_active'];
			$dir_array[$i]['dir']      = $dir;
			$dir_array[$i]['parent']   = $row['parent'];
			$dir_array[$i]['desc']     = $row['description'];
			$dir_array[$i]['amount']   = $row['amount'];
			$dir_array[$i]['up']       = "";
			$i++;
		}

		if ($parent != 0) {
			$query = "
				SELECT * 
				FROM iShark_Downloads 
				WHERE download_id = $parent
			";
			$result = $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$dir_array2[$j]['did']      = $row['download_id'];
				$dir_array2[$j]['name']     = $row['name'];
				$dir_array2[$j]['size']     = $row['size'];
				$dir_array2[$j]['type']     = $row['type'];
				$dir_array2[$j]['add_date'] = $row['add_date'];
				$dir_array2[$j]['mod_date'] = $row['mod_date'];
				$dir_array2[$j]['is_act']   = $row['is_active'];
				$dir_array2[$j]['dir']      = $dir;
				$dir_array2[$j]['parent']   = $row['parent'];
				$dir_array2[$j]['desc']     = $row['description'];
				$dir_array2[$j]['amount']   = $row['amount'];
				$dir_array2[$j]['up']       = $row['parent'];
				$j++;
			}
			$dir_array = array_merge($dir_array2, $dir_array);
		}

		return $dir_array;
	}

	/**
	 * torol - Bejegyzés törlése (Rekurzív); 
	 *
	 * @param int $id 
	 * @access public
	 * @return void
	 */

	function delete($id)
	{
		global $mdb2, $ddir;

		// Gyermekek törlése
		$query = "
			SELECT * 
			FROM iShark_Downloads 
			WHERE parent = $id
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			delete($row['download_id']);
		}

		// kiválasztott file/mappa elem lekérdezése
		$query = "
			SELECT * 
			FROM iShark_Downloads 
			WHERE download_id = $id
		";
		$result = $mdb2->query($query);
		if ($result->numRows() == 0) {
			return;
		} else {
			// Filetörlés, ha van jog, és az elem típusa file
			while ($row = $result->fetchRow())
			{
				if ($row['type'] == 'F') {
					@unlink($ddir.$row['realname']);
				}
			}
		}

		// Mappa vagy File bejegyzés törlése az adatbázisból
		$query = "
			DELETE FROM iShark_Downloads 
			WHERE download_id = $id
		";
		$mdb2->exec($query);
	}

	/**
	 * Ellenorzi, hogy a megadott neven, e-mail cimmel letezik-e az adott mappa vagy file
	 *
	 * @param	array	a formban szereplo mezoket tartalmazza
	 *
	 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
	 */
	function check_adddownloads($values)
	{
		global $mdb2, $locale;

		if (!isset($values['type']) || $values['type'] == "") {
			$type = "D";
		} else {
			$type = $values['type'];
		}

		//ellenorizzuk, hogy letezik-e ilyen nevu file vagy mappa, tipustol fuggoen
		$query = "
			SELECT name 
			FROM iShark_Downloads 
			WHERE name = '".$values['dirname']."' AND type = '".$type."' AND parent = ".$values['parent']."
		";
		$name_check = $mdb2->query($query);
		if ($name_check->numRows() != 0) {
			$errors['dirname'] = $locale->get('function_error_dir_exists');
		}

		return empty($errors) ? true: $errors;
	 }

	 /**
	 * Ellenorzi, hogy a megadott nevvel letezik-e mar mappa vagy file
	 *
	 * @param	array	a formban szereplo mezoket tartalmazza
	 *
	 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
	 */
	function check_moddownloads($values)
	{
		global $mdb2, $locale;

		if (!isset($values['type']) || $values['type'] == "") {
			$type = "D";
		} else {
			$type = $values['type'];
		}

		$query = "
			SELECT name 
			FROM iShark_Downloads 
			WHERE name = '".$values['dirname']."' AND type = '".$type."' AND parent = ".$values['parent']." AND download_id != ".$values['did']."
		";
		$result = $mdb2->query($query);
		if ($result->numRows() != 0) {
			$errors['dirname'] = $locale->get('function_error_dir_exists');
		}

		return empty($errors) ? true: $errors;
	}

	/**
	 * getftpdir - ftp könyvtár beolvasása
	 *
	 * @param	string	az ftp konyvatr eleresi utja
	 * @access public
	 * @return string
	 */
	function get_ftpdir($ftpdir)
	{
		$ret = array();
		if (!is_dir($ftpdir)) {
			return $ret;
		}

		if (($dir = opendir($ftpdir)) === FALSE) {
			return $ret;
		}

		$i = 0;
		while (($file = readdir($dir)) !== FALSE)
		{
			if ((is_file($ftpdir.'/'.$file)) && filetype($ftpdir.'/'.$file) == 'file') {
				$ret[$i] = $file;
				$i++;
			}
		}
		closedir($dir);
		return $ret;
	}

	/**
	 * set_active_r - Rekurzív aktiválást végrehajtó fv.
	 *
	 * @param int $download_id
	 * @param char $active
	 * @access public
	 * @return void
	 */
	function set_active_r($download_id, $active)
	{
		global $mdb2;

		$query = "
			SELECT * 
			FROM iShark_Downloads 
			WHERE parent = $download_id
		";
		$result =& $mdb2->query($query);
		while ($sor = $result->fetchRow()) {
			set_active_r($sor['download_id'], $active);
		}
		$query = "
			UPDATE iShark_Downloads 
			SET is_active = '$active' 
			WHERE download_id = $download_id
		";
		$mdb2->exec($query);
	}

	/**
	 * kiszamolja az aktiv file-ok, mappak ossz meretet
	 *
	 * @return	int		file-ok, mappak ossz merete
	 */
	function get_dirsumsize()
	{
		global $mdb2;

		//kiszamoljuk a mappak ossz meretet
		$query = "
			SELECT SUM(size) AS size 
			FROM iShark_Downloads 
			WHERE is_active = 1
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow())
			{
				$dirsumsize = $row['size'];
			}
		} else {
			$dirsumsize = 0;
		}

		$dirsumsize = number_format($dirsumsize/1024, 0, ',', ' ');

		return $dirsumsize;
	}
?>
