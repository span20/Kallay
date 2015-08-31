<?php
    require_once 'Translator_MDB2.php';
	$tr_options = array(
	   'table_locales'     => 'iShark_Locales',
	   'table_variables'   => 'iShark_Locales_Variables',
	   'table_expressions' => 'iShark_Locales_Expressions',
	   'table_areas'       => 'iShark_Locales_Areas',
	   'conn'              => &$mdb2,
	   'lang_name'         => $_SESSION['site_lang'],
	   'fallback'          => 'hu'
    );
    $locale =& Translator_MDB2::factory($tr_options);
?>