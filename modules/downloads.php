<?php

// K�zvetlen�l ezt az �llom�nyt k�rte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("K�zvetlen�l nem lehet az �llom�nyhoz hozz�f�rni...");
}

$module_name = "downloads";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

$tpl->assign('self', $module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst');

//menu azonosito vizsgalata
if (isset($_GET['mid'])) {
	$menu_id = intval($_GET['mid']);
}

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, NULL, 0, 'downloads', 'index')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

//modulhoz tartozo beallitasok lekerdezese
$query = "
	SELECT c.is_ftpdir AS isftp, c.ftpdir AS fdir, c.downdir AS ddir, c.maxdir AS mdir, c.allow_filetypes AS types, 
		c.maxsize AS msize 
	FROM iShark_Configs c
";
$result = $mdb2->query($query);
while ($row = $result->fetchRow())
{
	$isftp = $row['isftp'];
	$fdir  = $row['fdir'];
	$ddir  = $row['ddir'];
	$mdir  = $row['mdir'];
	$types = $row['types'];
	$msize = $row['msize'];
}

/**
 * ha a file-t akarjuk letolteni
 */
if (isset($_GET['did']) && is_numeric($_GET['did'])) {
	$did = intval($_GET['did']);

	$query = "
		SELECT name, realname 
		FROM iShark_Downloads 
		WHERE download_id = $did
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		return;
	} else {
		while ($row = $result->fetchRow())
		{
			$name     = $row['name'];
			$filename = $row['realname'];
		}

		//noveljuk a szamlalot
		$query = "
			UPDATE iShark_Downloads 
			SET amount = amount + 1 
			WHERE download_id = $did
		";
		$mdb2->exec($query);

		$mime = 'application/octet-stream';
		header("Content-type: $mime");
		header('Content-Disposition: attachment; filename="'.$name.'"');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		readfile($ddir."/".$filename);
		exit;
	}
} //file letoltes vege

/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
	include_once $include_dir.'/function.downloads.php';

	if (isset($_REQUEST['parent'])) {
		$parent = intval($_REQUEST['parent']);
	} else {
		$parent = 0;
	}

	//kiszamoljuk, hogy hany mappa van a rendszerben
	$query = "
		SELECT *
		FROM iShark_Downloads 
		WHERE type = 'D' AND is_active = 1
	";
	$result = $mdb2->query($query);
	$cdir = $result->numRows();

	//kiszamoljuk, hogy hany file van a rendszerben
	$query = "
		SELECT *
		FROM iShark_Downloads 
		WHERE type = 'F' AND is_active = 1
	";
	$result = $mdb2->query($query);
	$cfile = $result->numRows();

	$dir = get_aktdir($parent);

	//atadjuk a smarty-nak a valtozokat
	$tpl->assign('dirlist',    filelist($dir['dir'], 'name', $parent, 1));
	$tpl->assign('menu_id',    $menu_id);
	$tpl->assign('act_dir',    $dir['dir']);
	$tpl->assign('cdir',       $cdir);
	$tpl->assign('cfile',      $cfile);
	$tpl->assign('dirsumsize', get_dirsumsize());

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'download_list';
}

?>
