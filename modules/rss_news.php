<?php
/*
Project:     playRSSwriter: RSS 2.0 writer with images support
File:        playRSSwriter.php
Author:      hombrelobo <minilobo@gmail.com>
Version:     0.95

For the latest version of the writer, questions, help, comments,
etc., please visit:
http://wolfb.com/2006/01/playrsswriter-rss-20-feed-writer-with_01.html

What is playRSSwriter ?
When working on creating a rss feed for our site http://playlingerie.com,
we realized that all the existing php writers were either for rss 1.0 or for
rss 2.0 but without support for embeded images. So I created this code.
You can see it in action in:
http://feeds.feedburner.com/sexy-lingerie

Enjoy it !!

*************************************
This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE.

This work is hereby released into the Public Domain. To view a copy of
the public domain dedication, visit
http://creativecommons.org/licenses/publicdomain/ or send a letter to
Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*************************************
*/

$rss = 1;
include_once "../includes/config.php";
include_once "../includes/functions.php";

//modul neve
$module_name = "rss";

//nyelvi file betoltese
$locale->useArea($module_name);

$query = "
	SELECT * 
	FROM iShark_Rss 
	WHERE url LIKE ('rss_news.php%')
";
$result =& $mdb2->query($query);
$row = $result->fetchRow();
$rss_description = $row['description'];

switch ($row['lang']){
	case "magyar":
		$rss_language = "hu";
		break;
	case "english":
		$rss_language = "en";
		break;
}

$sitename = $_SESSION['site_sitename'];
$sitehttp = $_SESSION['site_sitehttp'];
$sitemail = $_SESSION['site_sitemail'];
$lang     = $row['lang'];
$charset  = $locale->getCharset();
$mainnews = $locale->get('main_news');

//ha vannak kategoriak kezelve
$cat_join  = "";
$cat_where = "";
if (!empty($_SESSION['site_category']) && !empty($_GET['cat']) && is_numeric($_GET['cat'])) {
	$cat_id = intval($_GET['cat']);

	//kategoria nevet kulon kiszedjuk, mert kell az RSS fejlecbe (nem kell, de igy jobban meg lehet kulonboztetni)
	$query_cat = "
		SELECT category_name 
		FROM iShark_Category 
		WHERE category_id = $cat_id AND is_active = 1 AND is_deleted = 0
	";
	$result_cat =& $mdb2->query($query_cat);
	$row_cat = $result_cat->fetchRow();
	$category_name = $row_cat['category_name'];

	$cat_join = "
		LEFT JOIN iShark_Category ca ON ca.category_id = $cat_id 
		LEFT JOIN iShark_Contents_Category cc ON cc.category_id = ca.category_id AND cc.content_id = c.content_id 
	";

	$cat_where = "
		AND cc.category_id IS NOT NULL 
	";
}

$query2 = "
	SELECT c.title AS ctitle, c.lead AS clead, c.content AS content, DATE_FORMAT(c.mod_date,'%Y.%m.%d') AS mod_date, 
		c.content_id AS ccid, c.picture, c.type
	FROM iShark_Contents AS c 
	$cat_join 
	WHERE c.type = 0 AND c.is_mainnews != 1 AND c.is_active = 1 AND c.lang = '".$lang."' $cat_where 
	ORDER BY c.is_index DESC, c.is_mainnews DESC, c.mod_date DESC
";
$result2 = $mdb2->query($query2);

// set the file's content type and character set
// this must be called before any output
header("Content-Type: text/xml;charset=$charset");

//set the beginning of the xml file
//ha van kategoria, akkor mas fejlec megy ki
if (!empty($category_name)) {
ECHO <<<END
<?xml version="1.0" encoding="$charset"?>
<rss version="2.0" xml:base="$sitehttp">
 <channel>
  <title>$sitename - $mainnews [$category_name]</title>
  <link>$sitehttp</link>
  <description>$rss_description</description>
  <language>$rss_language</language>
END;
} else {
ECHO <<<END
<?xml version="1.0" encoding="$charset"?>
<rss version="2.0" xml:base="$sitehttp">
 <channel>
  <title>$sitename - $mainnews</title>
  <link>$sitehttp</link>
  <description>$rss_description</description>
  <language>$rss_language</language>
END;
}

while ($row2 = $result2->fetchRow())
{
	$ccid    = intval($row2['ccid']);
	$title   = $row2['ctitle'];
	$pubdate = $row2['mod_date'];
	$lead    = $row2['clead'];

	$content = "
		<p>$lead</p>
	";
	$content = preg_replace(array('/</', '/>/', '/"/'), array('&lt;', '&gt;', '&quot;'), $content);

// display an item
ECHO <<<END
   <item>
    <title>$title</title>
    <link>$sitehttp/index.php?p=news&amp;act=show&amp;cid=$ccid</link>
    <description>$content</description>
    <author>$sitemail</author>
    <pubDate>$pubdate</pubDate>
   </item>
END;
}

ECHO <<<END
   </channel>
</rss>
END;
?>
