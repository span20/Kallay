<?php

// K�zvetlen�l ezt az �llom�nyt k�rte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("K�zvetlen�l nem lehet az �llom�nyhoz hozz�f�rni...");
}

require_once $include_dir.'/function.menu.php';

//modul neve
$module_name = "sitemap";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

$menu_array = menu(0, FALSE, 0, 1, $_SESSION['site_lang'], 'index');

$tpl->assign('sitemap', $menu_array);

$acttpl = "sitemap";

?>
