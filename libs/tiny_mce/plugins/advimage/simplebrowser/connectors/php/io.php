<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: io.php
 * 	This is the File Manager Connector for ASP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

/**
 * GetUrlFromPath
 * Modified by Tibcsi for iShark CMS
 * 
 * @param mixed $resourceType 
 * @param mixed $folderPath 
 * @access public
 * @return void
 */
function GetUrlFromPath( $resourceType, $folderPath )
{  	
	global $libs_dir;
	$ldir = preg_quote($libs_dir, '!');
	$akt_dir = preg_replace("!/$ldir/.*!", '/'.trim($GLOBALS["UserFilesPath"],'/'), $_SERVER['PHP_SELF']);
	if ( $resourceType == '' ) {
//		$ret = $akt_dir . $folderPath;
		$ret = RemoveFromEnd( $GLOBALS["UserFilesPath"], '/' ) . $folderPath ;
	} else {
//		$ret = $akt_dir . '/' . $resourceType . $folderPath;
		$ret = $GLOBALS["UserFilesPath"] . $resourceType . $folderPath ;
	}
//	$f = fopen('/home/tibor/public_html/www.medikemia.hu/files/teszt.log', 'a');
//	fwrite($f, "Url: $ret\n");
//	fclose($f);
	return $ret;
}

function RemoveExtension( $fileName )
{
	return substr( $fileName, 0, strrpos( $fileName, '.' ) ) ;
}

function ServerMapFolder( $resourceType, $folderPath )
{
	// Get the resource type directory.
	$sResourceTypePath = $GLOBALS["UserFilesDirectory"] . $resourceType . '/' ;

	// Ensure that the directory exists.
	CreateServerFolder( $sResourceTypePath ) ;

	// Return the resource type directory combined with the required path.
	return $sResourceTypePath . RemoveFromStart( $folderPath, '/' ) ;
}

function GetParentFolder( $folderPath )
{
	$sPattern = "-[/\\\\][^/\\\\]+[/\\\\]?$-" ;
	return preg_replace( $sPattern, '', $folderPath ) ;
}

function CreateServerFolder( $folderPath )
{
	$sParent = GetParentFolder( $folderPath ) ;

	// Check if the parent exists, or create it.
	if ( !file_exists( $sParent ) )
	{
		$sErrorMsg = CreateServerFolder( $sParent ) ;
		if ( $sErrorMsg != '' )
			return $sErrorMsg ;
	}

	if ( !file_exists( $folderPath ) )
	{
		// Turn off all error reporting.
		error_reporting( 0 ) ;
		// Enable error tracking to catch the error.
		ini_set( 'track_errors', '1' ) ;

		// To create the folder with 0777 permissions, we need to set umask to zero.
		$oldumask = umask(0) ;
		mkdir( $folderPath, 0777 ) ;
		umask( $oldumask ) ;

		$sErrorMsg = $php_errormsg ;

		// Restore the configurations.
		ini_restore( 'track_errors' ) ;
		ini_restore( 'error_reporting' ) ;

		return $sErrorMsg ;
	}
	else
		return '' ;
}

function GetRootPath()
{
	global $libs_dir;
	$sRealPath = realpath( './' ) ;

	$sSelfPath = $_SERVER['PHP_SELF'] ;
	$sSelfPath = substr( $sSelfPath, 0, strrpos( $sSelfPath, '/' ) ) ;
	
	$ldir = preg_quote($libs_dir,'!');
	$RootPath = preg_replace("!/$ldir/.*$!", '/', $sRealPath);
		//$RootPath = substr( $sRealPath, 0, strlen( $sRealPath ) - strlen( $sSelfPath ) ) ;
/*	$f = fopen('/home/tibor/public_html/www.medikemia.hu/files/teszt.log', 'a');
	fwrite($f, "RootPath: $RootPath\n");
	fclose($f); */
	return $RootPath;
}
?>
