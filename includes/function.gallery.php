<?php


/**
 * getftpdir - ftp konyvtar beolvasasa
 *
 * @param	string	az ftp konyvtar eleresi utja
 * @param	string	a vizsgalt file-ok tipusa (picture vagy video lehet)
 * @access public
 * @return string
 */
function get_ftpdir($ftpdir, $type)
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
	    if ($type == "picture") {
	        if (preg_match("/\.(jpe?g|gif|png)$/i", $file) && filetype($ftpdir.$file) == 'file') {
	            $ret[$i] = $file;
	            $i++;
	        }
	    }
	    if ($type == "video") {
	        if (preg_match("/\.(mpe?g|avi|wmv)$/i", $file) && filetype($ftpdir.$file) == 'file') {
	            $ret[$i] = $file;
	            $i++;
	        }
	    }
	}
	closedir($dir);
	return $ret;
}

function picCount($params, &$smarty) {
	global $mdb2;

	$query  = "
		SELECT count(*) AS cnt 
		FROM iShark_Galleries_Pictures 
		WHERE gallery_id = ".$params["gallery_id"]."
	";
	$result =& $mdb2->query($query);
	if ($row = $result->fetchRow()) {
		return $row['cnt'];
	}

	return 0;
}

function galleries($onlyactive = TRUE, $parent = 0, $level = 1, $type = '')
{
	global $mdb2;

	$menuk = array();
	$i = 0;
	if (!empty($type)) {
		$type_q = " AND type='".$type."' ";
	} else {
		$type_q = "";
	}
	$query = "
		SELECT gallery_id as gid, name, description, is_active, add_date, parent
		FROM iShark_Galleries
		WHERE is_active != 2 ".$type_q." AND parent = '$parent' 
		ORDER BY add_date
	";

	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$query2 = "
			SELECT parent 
			FROM iShark_Galleries 
			WHERE parent = '".$row['gid']."'
		";
		$result2 = $mdb2->query($query2);

		$almenuk = galleries($onlyactive, $row['gid'], $level+1);
		
		if ($result2->numRows() > 0){
			$menuk[$i]['is_sub'] = '1';
		}
		
		/*$hunnev = change_hunchar($row['menu_name']);
		$hunnev = eregi_replace(" ", "", $hunnev);*/
		
		$menuk[$i]['gid']   = $row['gid'];
		$menuk[$i]['name'] = $row['name'];
		//$menuk[$i]['menu_name_no'] = $hunnev;		
		$menuk[$i]['level']     = $level;
		$menuk[$i]['parent']    = $row['parent'];
		$menuk[$i]['description']    = $row['description'];
		$menuk[$i]['is_active']    = $row['is_active'];
		$menuk[$i]['add_date']    = $row['add_date'];
		if (!empty($almenuk)) {
			$menuk[$i]['element'] = $almenuk;
		}
		$i++;
    }

	return $menuk;
}

?>