<?php

$dir = getcwd();
chdir('..');
require_once 'includes/config.php';

// nyelvi allomany betoltese
$locale->useArea("admin_linklist");
$mdb2->query("SET NAMES 'utf8'");
chdir($dir);

?>	var tinyMCELinkList = new Array(
	["--------- <?=$locale->get('field_menus')?> ----------", ""]
<?php
	/* Menüpontok */
	print get_menus();

?>  ,["----------- <?=$locale->get('field_contents')?> ----------", ""] 
<?php

	/* Tartalmak */
	$result =& $mdb2->query("
		SELECT content_id, title, type FROM iShark_Contents WHERE type = 1 ORDER BY title
	");
	for ($i=1; $row = $result-> fetchRow(); $i++) {
		?>,["<?=$row['title']?>", "index.php?p=contents&amp;cid=<?=$row['content_id']?>"]<?php
	}

?>  ,["----------- <?=$locale->get('field_news')?> ----------", ""]
<?php

	/* Hirek */
	$result =& $mdb2->query("
		SELECT content_id, title, type FROM iShark_Contents WHERE type = 0 ORDER BY title
	");
	for ($i=1; $row = $result-> fetchRow(); $i++) {
		?>,["<?=$row['title']?>", "index.php?p=news&amp;act=lst&amp;cid=<?=$row['content_id']?>"]<?php
	}

	/**
	 * get_menus - Rekurzív menükérés smarty linklist javascripthez
	 * 
	 * @param int $parent 
	 * @param string $path 
	 * @access public
	 * @return void
	 */
	function get_menus($parent = 0, $path = '')
	{
		global $mdb2;
		$ret = '';
		$result =& $mdb2->query("
			SELECT menu_id, parent, menu_name 
			FROM iShark_Menus 
			WHERE type='index' AND parent='$parent'
			ORDER BY position_id,sortorder
		");
		while ($row = $result->fetchRow()) {
			$ret .= ',["'.(empty($path) ? '' : $path.'/').$row['menu_name'].'", "index.php?mid='.$row['menu_id'].'"]';
			$ret .= get_menus($row['menu_id'], (empty($path) ? '' : $path.'/').htmlspecialchars($row['menu_name']));
		}
		return $ret;
	}
?>
);
