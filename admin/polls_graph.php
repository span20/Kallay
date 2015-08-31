<?php
	ini_set('include_path', '../libs/pear'.PATH_SEPARATOR.
						'../'.PATH_SEPARATOR.
						ini_get('include_path'));
	include_once 'includes/config.php';
	error_reporting(E_ERROR);
	$pid = intval($_GET['pid']);

	//grafikon
	include 'Image/Graph.php';
	$Graph =& Image_Graph::factory('graph', array(400, 300));

	//lekerdezzuk a szavazast
	$query = "
		SELECT p.title AS ptitle, p.timer_start AS timer_start, p.timer_end AS timer_end, p.start_date AS start_date, 
			p.end_date AS end_date
		FROM iShark_Polls p 
		WHERE p.poll_id = $pid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$poll = $result->fetchRow();
	} else {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('error_notexists'));
		return;
	}

	$Graph->add(
		Image_Graph::vertical(
			Image_Graph::factory('title', array($poll['ptitle'], 12)), 
			Image_Graph::vertical(
				$Plotarea = Image_Graph::factory('plotarea'),
				$Legend = Image_Graph::factory('legend'),
				90
			),
			5 
		)
	);
	$Legend->setPlotarea($Plotarea);
	$Dataset =& Image_Graph::factory('dataset');

	//lekerdezzuk a szavazatok szamat
	$query = "
		SELECT COUNT(pv.data_id) AS polldata 
		FROM iShark_Polls_Votes pv, iShark_Polls_Datas pd 
		WHERE pd.poll_id = $pid AND pd.data_id = pv.data_id 
	";
	$result = $mdb2->query($query);
	$poll_num = $result->fetchRow();

	//lekerdezzuk a szavazasra adhato valaszokat es az eredmenyeket
	$query = "
		SELECT pd.data_id AS pid, pd.poll_text AS text, COUNT(pv.data_id) AS polldata 
		FROM iShark_Polls_Datas pd 
		LEFT JOIN iShark_Polls_Votes pv ON pd.data_id = pv.data_id 
		WHERE pd.poll_id = $pid 
		GROUP BY pd.data_id 
		ORDER BY pd.sortorder
	";
	$result = $mdb2->query($query);
	$poll_text = array();
	$i = 0;
	while ($row = $result->fetchRow())
	{
		$poll_text[$i]['text'] = $row['text'];
		$poll_text[$i]['polldata'] = $row['polldata'];
		if ($poll_num['polldata'] == 0) {
			$poll_text[$i]['percent'] = 100;
		} else {
			$poll_text[$i]['percent'] = substr(100 * $row['polldata'] / $poll_num['polldata'], 0, 6);
		}
		//grafikonhoz hozzaadjuk az eredmenyeket
		$Dataset->addPoint($poll_text[$i]['text'], $poll_text[$i]['percent']);
		$i++;
	}

	$Plot =& $Plotarea->addNew('bar', &$Dataset);
	$Plot->setFillColor('blue@0.2');
	// create a Y data value marker
	$Marker =& $Plot->addNew('Image_Graph_Marker_Value', IMAGE_GRAPH_VALUE_Y);
	// create a pin-point marker type
	$PointingMarker =& $Plot->addNew('Image_Graph_Marker_Pointing_Angular', array(20, &$Marker));
	// and use the marker on the 1st plot
	$Plot->setMarker($PointingMarker);
	$Graph->done();

?>
