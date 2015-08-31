<?php 
	// Most ezt is meghekkelem - Tibcsi
	ini_set('include_path', '../../../../../libs/pear'.PATH_SEPARATOR.
						'../../../../../'.PATH_SEPARATOR.
						ini_get('include_path'));
	include_once 'includes/config.php';
/*	include_once 'connectors/php/config.php'; */
	$ldir = preg_quote($libs_dir, '!');	
	$akt_dir = preg_replace("!/$ldir/.*!", '/', $_SERVER['PHP_SELF']);
?>
<!--
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
 * File Name: frmresourceslist.html
 * 	This page shows all resources available in a folder in the File Browser.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 *	Andrew Tetlaw - 2006/02 - for TinyMCE 2.0.3 and above
 *	A port of the FCKEditor file browser as a TinyMCE plugin.
 *	http://tetlaw.id.au/view/blog/fckeditor-file-browser-plugin-for-tinymce-editor/
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<link href="browser.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="js/common.js"></script>
		<script language="javascript">

var oListManager = new Object() ;

oListManager.Init = function()
{
	this.Table = document.getElementById('tableFiles') ;
}

oListManager.Clear = function()
{
	// Remove all other rows available.
	while ( this.Table.rows.length > 0 )
		this.Table.deleteRow(0) ;
}

oListManager.AddFolder = function( folderName, folderPath )
{
	// Create the new row.
	var oRow = this.Table.insertRow(-1) ;

	// Build the link to view the folder.
	var sLink = '<a href="#" onclick="OpenFolder(\'' + folderPath + '\');return false;">' ;

	// Add the folder icon cell.
	var oCell = oRow.insertCell(-1) ;
	oCell.width = 16 ;
	oCell.innerHTML = sLink + '<img alt="" src="images/Folder.gif" width="16" height="16" border="0"></a>' ;

	// Add the folder name cell.
	oCell = oRow.insertCell(-1) ;
	oCell.noWrap = true ;
	oCell.colSpan = 2 ;
	oCell.innerHTML = '&nbsp;' + sLink + folderName + '</a>' ;
}

oListManager.AddFile = function( fileName, fileUrl, fileSize )
{
	// Create the new row.
	var oRow = this.Table.insertRow(-1) ;

	// Build the link to view the folder.
	var sLink = '<a href="#" onclick="OpenFile(\'' + fileUrl + '\');return false;">' ;

	// Get the file icon.
	var sIcon = oIcons.GetIcon( fileName ) ;

	// Add the file icon cell.
	var oCell = oRow.insertCell(-1) ;
	oCell.width = 16 ;
	oCell.innerHTML = sLink + '<img alt="" src="images/icons/' + sIcon + '.gif" width="16" height="16" border="0"></a>' ;

	// Add the file name cell.
	oCell = oRow.insertCell(-1) ;
	//oCell.innerHTML = '&nbsp;' + sLink + fileName + '</a>' ;
	var imgwidth = 0;
	var imgheight = 0;
	oCell.innerHTML = '&nbsp;' + sLink + '<img name="' + fileName + '" align="middle" src="' + '<?php print $akt_dir;?>' + fileUrl + '" border="0" alt="' + fileName + '"></a>';
	origwidth  = document.images[fileName].width;
	origheight = document.images[fileName].height;
	if (origwidth > 100) {
		percent   = 100/origwidth;
		imgwidth  = 100;
		imgheight = origheight * percent;
	}
	else if (origheight > 100) {
		percent   = 100/origheight;
		imgheight  = 100;
		imgwidth = origwidth * percent;
	}
	else {
		imgwidth = origwidth;
		imgheight = origheight;
	}
	document.images[fileName].width = imgwidth;
	document.images[fileName].height = imgheight;
	
	// Add the file size cell.
	oCell = oRow.insertCell(-1) ;
	oCell.noWrap = true ;
	oCell.align = 'right' ;
	oCell.innerHTML = '<b>' + fileName + ':</b>&nbsp;' + fileSize + ' KB, ' + origwidth + 'x' + origheight + ' px<br><input type="button" value="T�rl�s" onClick="window.open(\'delete.php?file=' + fileUrl + '\', \'T�rl�s\', \'scrollbars=no, resizable=no,width=300, height=100, toolbar=no, status=no,menubar=no,copyhistory=no\');">';
}

function OpenFolder( folderPath )
{
	// Load the resources list for this folder.
	window.parent.frames['frmFolders'].LoadFolders( folderPath ) ;
}

function OpenFile( fileUrl )
{
	window.top.opener.TinyMCE_SimpleBrowserPlugin.browserCallback(escape(fileUrl)) ;
	window.top.close() ;
	//window.top.opener.focus() ; //AT 20060217 - causing focus problems, link dialog would loose focus
}

function LoadResources( resourceType, folderPath )
{
	oListManager.Clear() ;
	oConnector.ResourceType = resourceType ;
	oConnector.CurrentFolder = folderPath
	oConnector.SendCommand( 'GetFoldersAndFiles', null, GetFoldersAndFilesCallBack ) ;
}

function Refresh()
{
	LoadResources( oConnector.ResourceType, oConnector.CurrentFolder ) ;
}

function GetFoldersAndFilesCallBack( fckXml )
{
	if ( oConnector.CheckError( fckXml ) != 0 )
		return ;

	// Get the current folder path.
	var oNode = fckXml.SelectSingleNode( 'Connector/CurrentFolder' ) ;
	var sCurrentFolderPath	= oNode.attributes.getNamedItem('path').value ;
	var sCurrentFolderUrl	= oNode.attributes.getNamedItem('url').value ;

	// Add the Folders.	
	var oNodes = fckXml.SelectNodes( 'Connector/Folders/Folder' ) ;
	for ( var i = 0 ; i < oNodes.length ; i++ )
	{
		var sFolderName = oNodes[i].attributes.getNamedItem('name').value ;
		oListManager.AddFolder( sFolderName, sCurrentFolderPath + sFolderName + "/" ) ;
	}
	
	// Add the Files.	
	var oNodes = fckXml.SelectNodes( 'Connector/Files/File' ) ;
	for ( var i = 0 ; i < oNodes.length ; i++ )
	{
		var sFileName = oNodes[i].attributes.getNamedItem('name').value ;
		var sFileSize = oNodes[i].attributes.getNamedItem('size').value ;
		oListManager.AddFile( sFileName, sCurrentFolderUrl + sFileName, sFileSize ) ;
	}
}

window.onload = function()
{
	oListManager.Init() ;
	window.top.IsLoadedResourcesList = true ;
}
		</script>
	</head>
	<body class="FileArea" bottomMargin="10" leftMargin="10" topMargin="10" rightMargin="10">
		<table id="tableFiles" cellSpacing="1" cellPadding="0" width="100%" border="0">
		</table>
	</body>
</html>
