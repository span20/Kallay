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
				 '�' => 'a', '�' => 'a', '�' => 'e', '�' => 'e', '�' => 'i', '�' => 'i',
				 '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o',
				 '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'u', ' ' => '_'
	)) );
	return $output;
}

/* vim: set expandtab: */

?>