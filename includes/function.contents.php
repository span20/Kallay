<?php

	/**
	 * Ellenorzi, hogy a megadott neven letezik-e mar kategoria
	 *
	 * @param	array	a formban szereplo mezoket tartalmazza
	 *
	 * @return	array	ha volt hiba, akkor visszaadja a hibauzeneteket
	 */
	function check_contents_addcategory($values)
	{
		global $mdb2, $locale;

		$errors = array();

		//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
		$query = "
			SELECT category_name
			FROM iShark_Category
			WHERE category_name = '".$values['name']."'
		";
		$name_check =& $mdb2->query($query);
		if ($name_check->numRows() != 0) {
			$errors['name'] = $locale->get('function_error_category_exists');
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
	function check_contents_modcategory($values)
	{
		global $mdb2, $locale;

		$errors = array();

		//ellenorizzuk, hogy letezik-e mar ilyen nevu felhasznalo
		$query = "
			SELECT category_name
			FROM iShark_Category
			WHERE category_name = '".$values['name']."' AND is_deleted != '1' AND category_id != ".$values['cid']."
		";
		$name_check =& $mdb2->query($query);
		if ($name_check->numRows() != 0) {
			$errors['name'] = $locale->get('function_error_category_exists');
		}

		return empty($errors) ? true: $errors;
	}

	/**
	 * ellenorzi, hogy van-e joga a usernek az adott tartalmat modositani
	 *
	 * @param	int		a tartalom azonositoja, amit ellenorzunk
	 *
	 * @return	bool	visszaadja, hogy van-e a usernek a tartalomhoz
	 */
	function check_contents_perm($content)
	{
		global $mdb2;

		//ha be van allitva az opcio, hogy csak sajat csoport modosithat, csak akkor ellenorzunk
		if (isset($_SESSION['site_contedit']) && $_SESSION['site_contedit'] == 1) {
			//lekerdezzuk a tartalmat letrehozo user azonositojat
			$query = "
				SELECT c.add_user_id AS user_id
				FROM iShark_Contents c
				WHERE content_id = $content
			";
			$result = $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$user_id = $row['user_id'];
			}

			//megnezzuk, hogy melyik csoport(ok)ba tartozik a user, aki letrehozta a tartalmat
			$query = "
				SELECT g.group_id AS gid
				FROM iShark_Groups g, iShark_Groups_Users gu
				WHERE gu.group_id = g.group_id AND gu.user_id = $user_id
			";
			$result = $mdb2->query($query);
			if ($result->numRows() == 0) {
				return false;
			} else {
				while ($row = $result->fetchRow())
				{
					$creator_groups[] = $row['gid'];
				}
			}

			//lekerdezzuk, hogy melyik csoport(ok)ba tartozik a user, aki modositani akarja a tartalmat
			$modify_groups = explode(" ", $_SESSION['user_groups']);

			//vesszuk a ket csoport metszetet, ha az eredmeny nem 0, akkor modosithatja a tartalmat
			$intersect = array_intersect($creator_groups, $modify_groups);
			if (count($intersect) == 0) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	/**
	 * ellenorzi, hogy hany hirt akarunk fooldalon tartani
	 * ha elertuk a max. fooldalon tartando hirek szamat, akkor az utolsot levesszuk a fooldalrol
	 */
	function change_index($values)
	{
		global $mdb2;

		if ($values['indexpage'] == 1) {
			//ha vezeto hir, akkor megnezzuk, hogy hany kiemelt vezeto hir van
			if ($values['mainnews'] == 1) {
				$query = "
					SELECT COUNT(c.content_id) AS ccont, MIN(c.content_id) AS mcont
					FROM iShark_Contents c
					WHERE c.type = 0 AND is_mainnews = 1
					ORDER BY c.content_id
				";
				$result = $mdb2->query($query);
				if ($result->numRows() > 0) {
					while ($row = $result->fetchRow())
					{
						$ccont = $row['ccont'];
						$mcont = $row['mcont'];
						//ha a fooldalon tartando kiemelt hirek szama = a fooldalon jelenleg levo kiemelt hirek szamaval
						if ($ccont == $_SESSION['site_leadnum']) {
							//a legkisebb id-ju kiemelt hir levesszuk a fooldalrol
							$query = "
								UPDATE iShark_Contents
								SET is_index = 0
								WHERE content_id = $mcont
							";
							$mdb2->exec($query);
						}
					}
				}
			}
			//ha egyeb hir
			if ($values['mainnews'] == 0) {
				$query = "
					SELECT COUNT(c.content_id) AS ccont, MIN(c.content_id) AS mcont
					FROM iShark_Contents c
					WHERE c.type = 0 AND is_mainnews = 0
					ORDER BY c.content_id
				";
				$result = $mdb2->query($query);
				if ($result->numRows() > 0) {
					while ($row = $result->fetchRow())
					{
						$ccont = $row['ccont'];
						$mcont = $row['mcont'];
						//ha a fooldalon tartando hirek szama = a fooldalon jelenleg levo hirek szamaval
						if ($ccont == $_SESSION['site_newsnum']) {
							//a legkisebb id-ju kiemelt hir levesszuk a fooldalrol
							$query = "
								UPDATE iShark_Contents
								SET is_index = 0
								WHERE content_id = $mcont
							";
							$mdb2->exec($query);
						}
					}
				}
			}
		}

		return empty($errors) ? true: $errors;
	}

	/**
	 * olvasottsag szamlalo
	 *
	 * @param	int	a tartalom vagy hir azonositoja
	 *
	 * @return
	 */
	function view_counter($id)
	{
		global $mdb2;

		//ha nem letezik meg a cookie, akkor letrehozzuk
		if (empty($_COOKIE['iShark_Cnt_View_Counter'])) {
			//letrehozzuk a cookie-t, alapertelmezetten 10 perc az elevulesi ido
			setcookie("iShark_Cnt_View_Counter[$id]", $id, time() + 600);

			$query = "
				UPDATE iShark_Contents
				SET view_counter = view_counter + 1
				WHERE content_id = $id
			";
			$mdb2->exec($query);

			return;
		}
		//ha mar letetzik a cookie
		else {
			//megkeressuk, hogy az adott azonositoval van-e mar cookie
			$content = array_search($id, $_COOKIE['iShark_Cnt_View_Counter']);

			//ha talalt ilyet, akkor kilepunk
			if ($content != FALSE) {
				return;
			}
			//ha meg nincs ilyen, akkor letrehozzuk
			else {
				//letrehozzuk a cookie-t, alapertelmezetten 10 perc az elevulesi ido
				setcookie("iShark_Cnt_View_Counter[$id]", $id, time() + 600);

				$query = "
					UPDATE iShark_Contents
					SET view_counter = view_counter + 1
					WHERE content_id = $id
				";
				$mdb2->exec($query);

				return;
			}
		}
	}

	function getDownloadDirs($parent = 0, $dir = '') {
		global $mdb2;

		$rows = array();

		$query = "
			SELECT download_id, name 
			FROM iShark_Downloads 
			WHERE parent=$parent AND type='D' 
			ORDER BY name
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$rows[$row['download_id']] = $dir.$row['name'].'/';
			$rows += getDownloadDirs($row['download_id'], $dir.$row['name'].'/');
		}

		return $rows;
	}
?>
