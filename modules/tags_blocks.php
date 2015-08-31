<?php

if (isModule('tags', 'admin')) {
	$query_tags = "
		SELECT t.tag_id AS tag_id, t.tag_name AS tag_name 
		FROM iShark_Tags t, iShark_Tags_Modules tm 
		WHERE tm.tag_id = t.tag_id 
		GROUP BY tag_id 
		ORDER BY tm.add_date DESC
	";
	$mdb2->setLimit(20);
	$result_tags =& $mdb2->query($query_tags);

	$tpl->assign('taglist', $result_tags->fetchAll('', $rekey = true));

	$acttpl = "tags_block";
}

?>
