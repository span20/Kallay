<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "calendar";

//nyelvi file betoltese
$locale->useArea("index_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('add', 'old', 'lst');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 0, $module_name, 'index')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

// If the GET variables are not set in the URL, set now
if (!isset($_GET['y'])) $_GET['y'] = date('Y');
if (!isset($_GET['m'])) $_GET['m'] = date('m');
if (!isset($_GET['d'])) $_GET['d'] = date('d');

if ($act == "lst") {
	$javascripts[] = "javascript.calendar";

	require_once 'Calendar/Calendar.php';
	require_once 'Calendar/Year.php';
	require_once 'Calendar/Month.php';
	require_once 'Calendar/Day.php'; 
	require_once 'Calendar/Month/Weekdays.php';
	require_once 'Calendar/Decorator.php';

	$query = "
		SELECT calendar_id AS cid, start_date, end_date, title, is_major, event, 
			DATE_FORMAT(start_date, '%Y-%m-%d') AS sdate, DATE_FORMAT(end_date, '%Y-%m-%d') AS edate
		FROM iShark_Calendar
	";
	$result = $mdb2->query($query);
	$dateArray = $result->fetchAll();

	// The events array will contain the dates we will add to the calendar object
	$events = array();
	// Loop through the dates array, adding each to the event array
	foreach ($dateArray as $id => $workshop) {
		$events[] = array(
			'start' => strtotime($workshop['sdate']),
			'end'   => strtotime($workshop['edate']),
			'title' => $workshop['title'],
			'time'  => date("Y-m-d g:i A",strtotime($workshop['start_date'])),
			'id'    => $workshop['cid'] );
	}

	class DiaryEvent extends Calendar_Decorator { 
		var $entries = array();

		function addEntry($entry) { 
			$this->entries[] = $entry; 
		}

		function getEntry() { 
			$entry = each($this->entries); 

			if ($entry) { 
				return $entry['value']; 
			} else { 
				reset($this->entries); 
				return false; 
			}
		}

		function entryCount() {
			return count($this->entries);
		}
	} 

	class MonthPayload_Decorator extends Calendar_Decorator {
		function build($events=array()) { 
	// comment the following line out if you want to call "$month->build($selectedDays);" below!! Otherwise you'll get strange results!
			parent::build(); 
			foreach ($this->calendar->children as $i=> $child) { 
				$this->calendar->children[$i] = &new DiaryEvent($this->calendar->children[$i]);
			}
			if (count($events) > 0) { 
				$this->setSelection($events); 
			}
			return true; 
		}
		function setSelection($events) {
			foreach ($this->calendar->children as $i=> $child) { 
				$stamp1 = $this->calendar->cE->dateToStamp($child->thisYear(), $child->thisMonth(), $child->thisDay());
				$stamp2 = $this->calendar->cE->dateToStamp($child->thisYear(), $child->thisMonth(), $child->nextDay());
				foreach ($events as $event) {
					if (($stamp1 >= $event['start'] && $stamp1 <= $event['end'])) {
							$this->calendar->children[$i]->addEntry($event);
							$this->calendar->children[$i]->setSelected();
					}
				}
			}
		}
	}

	//utolso parameter megmondja, hogy a hetnek melyik az elso napja, 0 = vasarnap, 1 = hetfo
	$month = new Calendar_Month_Weekdays($_GET['y'], $_GET['m'], 1);
	//Add the workshop events to the decorator
	$monthDecorator = new MonthPayload_Decorator($month);
	$monthDecorator->build($events);
	// Set the current day as a Selected Day and put it in the array
	$selectedDays = array (
					new Calendar_Day(date('Y'), date('m'), date('d'))); 

	//$this->yearOfMonth[] = $month->thisYear();
	// build the month
	//$month->build($selectedDays);

	// Fetch all days in the month object
	$daysInMonth = $monthDecorator->fetchAll();

	// Split the month into weeks
	$weeksInMonth = array_chunk($daysInMonth, 7);

	// Create links
	$prevStamp = $month->prevMonth(true);
	$prev = 'index.php?p=calendar&amp;y='.date('Y',$prevStamp).'&amp;m='.date('n',$prevStamp).'&amp;d='.date('j',$prevStamp);
	$nextStamp = $month->nextMonth(true);
	$next = 'index.php?p=calendar&amp;y='.date('Y',$nextStamp).'&amp;m='.date('n',$nextStamp).'&amp;d='.date('j',$nextStamp); 

	$Year = new Calendar_Year($_GET['y']);
	$Month = new Calendar_Month($_GET['y'],$_GET['m']);
	$selection = array($Month);
	$Year->build($selection);
	$month_array = array();
	$m = 0;
	while ($Child = $Year->fetch()) {
		$month_array[$m]['option'] = $Child->thisMonth();
		if ($Child->isSelected()) {
			$month_array[$m]['selected'] = "selected";
		}
		$m++;
	}

	//megmutatjuk a kivalasztott napra szolo esemenyeket, ha nincs kivalasztott, akkor a mai napit
	$query .= "
		WHERE DATE_FORMAT(start_date, '%Y-%m-%d') = DATE_FORMAT('".$_GET['y']."-".$_GET['m']."-".$_GET['d']."', '%Y-%m-%d') OR 
			DATE_FORMAT(end_date, '%Y-%m-%d') = DATE_FORMAT('".$_GET['y']."-".$_GET['m']."-".$_GET['d']."', '%Y-%m-%d') OR 
			(DATE_FORMAT(start_date, '%Y-%m-%d') <= DATE_FORMAT('".$_GET['y']."-".$_GET['m']."-".$_GET['d']."', '%Y-%m-%d') AND 
			DATE_FORMAT(end_date, '%Y-%m-%d') >= DATE_FORMAT('".$_GET['y']."-".$_GET['m']."-".$_GET['d']."', '%Y-%m-%d')) 
		ORDER BY is_major DESC, start_date, end_date
	";
	$result = $mdb2->query($query);
	$today_event = $result->fetchAll();

	$fyear     = date('Y', $month->getTimeStamp());
	$fmonth    = date('m', $month->getTimeStamp());
	$monthName = date('Y F', $month->getTimeStamp());
	$today     = date('Y-m-d', strtotime($_GET['y']."-".$_GET['m']."-".$_GET['d']));

	$tpl->assign_by_ref('month', $weeksInMonth);
	$tpl->assign('fyear',       $fyear);
	$tpl->assign('fmonth',      $fmonth);
	$tpl->assign('monthName',   $monthName);
	$tpl->assign('prevMonth',   $prev);
	$tpl->assign('nextMonth',   $next);
	$tpl->assign('today_event', $today_event);
	$tpl->assign('cal_today',   $today);
	$tpl->assign('month_array', $month_array);

	$acttpl = "calendar";
}

?>
