<?php

//modul neve
$module_name = "rss";
$where = array();

if(isModule('contents')) {
    //hirek rss resze
	if(!empty($_SESSION['site_is_news']) && file_exists("modules/rss_news.php")) {
		$where[] = "url='rss_news.php'";
		//ha hasznaljuk a kategoriakat, akkor lekerdezzuk oket
		if (!empty($_SESSION['site_category'])) {
			$query_cats = "
				SELECT category_id 
				FROM iShark_Category 
				WHERE is_active = 1 AND is_deleted = 0
			";
			$result_cats =& $mdb2->query($query_cats);
			if ($result_cats->numRows() > 0) {
				while ($row_cats = $result_cats->fetchRow())
				{
					$where[] = "url='rss_news.php?cat=".$row_cats['category_id']."'";
				}
			}
		}
	}

	//tartalmak rss resze
	if(!empty($_SESSION['site_is_other']) && file_exists("modules/rss_contents.php")) {
		$where[] = "url='rss_contents.php'";
	}
}

if(!empty($where)){
	$where = implode(" OR ", $where);
	$query = "
		SELECT * 
		FROM iShark_Rss 
		WHERE lang = '".$_SESSION['site_lang']."' AND is_active = '1' AND ($where)
	";
	$result = $mdb2->query($query);
	while ($row = $result->fetchRow())
	{
		$rss[] = array(
			'name' => $row['rss_name'],
			'desc' => $row['description'],
			'url'  => $row['url'],
		);
	}
}

if (!empty($rss)) {
	$tpl->assign('rss', $rss);
}

$acttpl = 'rss';

?>