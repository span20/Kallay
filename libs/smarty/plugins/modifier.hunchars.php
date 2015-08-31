<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     hunchars<br>
 * Purpose:  simple search/replace
 * @link http://smarty.php.net/manual/en/language.modifier.replace.php
 *          replace (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_hunchars($string)
{

	$output = strtolower( strtr( $string, array(
				 'á' => 'a', 'Á' => 'a', 'é' => 'e', 'É' => 'e', 'í' => 'i', 'Í' => 'i',
				 'ó' => 'o', 'Ó' => 'o', 'õ' => 'o', 'Õ' => 'o', 'ö' => 'o', 'Ö' => 'o',
				 'ú' => 'u', 'Ú' => 'u', 'ü' => 'u', 'Ü' => 'u', 'û' => 'u', 'Û' => 'u', ' ' => '_'
	)) );
	return $output;
}

/* vim: set expandtab: */

?>