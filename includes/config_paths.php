<?php

	$dot = "";
	if (!empty($rss)) {
		$dot = "../";
	}

	//konyvtar beallitasok
	$include_dir = $dot."includes";
	$lang_dir    = $dot."languages"; //nyelvi file-ok konyvtara
	$theme_dir   = $dot."themes"; //tema file-ok konyvtara
	$libs_dir    = $dot."libs"; //kulso lib-ek konyvtara
	$pear_dir    = "pear"; //ahol a pear-hez szukseges dolgok talalhatoak
	$smarty_dir  = "smarty"; //smarty konyvtara

	//egyeb beallitasok (amig at nem rakom db-be)
	$theme    = "focus";

/******************************************************************************
*** RENDSZER BEALLITASOK - CSAK AKKOR NYULJ HOZZA, HA TUDOD MIT CSINALSZ!!! ***
******************************************************************************/

	//pear beallitasok
	ini_set('include_path', $libs_dir.'/'.$pear_dir . PATH_SEPARATOR . ini_get('include_path'));

?>