<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "calendar";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'calendar' => $locale->get('title')
);

$acts = array(
    'calendar' => array('add', 'mod', 'del')
);

//aktualis ful beallitasa
$page = 'calendar';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 1, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
}

$tpl->assign('self',         $module_name);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);
$tpl->assign('title_module', $title_module);
$tpl->assign('page_id',      $page_id);

// If the GET variables are not set in the URL, set now
if (!isset($_GET['y'])) $_GET['y'] = date('Y');
if (!isset($_GET['m'])) $_GET['m'] = date('m');
if (!isset($_GET['d'])) $_GET['d'] = date('d');

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

/**
 * ha telepitjuk a modult
 */
/*if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Calendar` (
			`calendar_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`title` VARCHAR( 255 ) NOT NULL ,
			`event` TEXT NOT NULL ,
			`is_major` CHAR( 1 ) NOT NULL ,
			`start_date` DATETIME NOT NULL ,
			`end_date` DATETIME NOT NULL ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
		INDEX ( `calendar_id` , `add_user_id` , `mod_user_id` )
		);
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}*/

/**
 * ha toroljuk a modult
 */
/*if ($act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Calendar`
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
} //torles vege
*/
/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($sub_act == "add" || $sub_act == "mod") {
	$javascripts[] = "javascripts";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_calendar', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'calendar', $locale->get('form_header'));
	$form->addElement('hidden', 'act',      $page);
	$form->addElement('hidden', 'sub_act',  $sub_act);
	$form->addElement('hidden', 'y',        $_REQUEST['y']);
	$form->addElement('hidden', 'm',        $_REQUEST['m']);
	$form->addElement('hidden', 'd',        $_REQUEST['d']);

	//kiemelt esemeny
	$major = array();
	$major[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$major[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'), '0');
	$form->addGroup($major, 'major', $locale->get('field_major'));

	//esemeny cime
	$form->addElement('text', 'title', $locale->get('field_title'));

	//idozites
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('id' => 'timer_start', 'readonly'=>'readonly')),
			HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
		'date_start', $locale->get('field_timerstart'), null, false
	);
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('id' => 'timer_end', 'readonly' => 'readonly')),
			HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('field_timerend'), null, false
	);

	//esemeny leirasa
	$event =& $form->addElement('textarea', 'event', $locale->get('field_event'));
	$event->setCols(30);
	$event->setRows(30);

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('major', $locale->get('error_major'), 'required');
	$form->addRule('title', $locale->get('error_title'), 'required');
	$form->addRule('event', $locale->get('error_event'), 'required');
	$form->addGroupRule('date_start', array(
		'timer_start' => array(
			array($locale->get('error_timerstart'), 'required')
			)
		)
	);
	$form->addGroupRule('date_end', array(
		'timer_end' => array(
			array($locale->get('error_timerend'), 'required')
			)
		)
	);
	$form->addFormRule('check_timer');

	/**
	 * ha uj esemenyt adunk hozza
	 */
	if ($sub_act == "add") {
		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;y='.$_REQUEST['y'].'&amp;m='.$_REQUEST['m'].'&amp;d='.$_REQUEST['d']);

		//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
		$form->setDefaults(array(
			'major' => 0
			)
		);

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$major       = intval($form->getSubmitValue('major'));
			$title       = $form->getSubmitValue('title');
			$event       = $form->getSubmitValue('event');
			$empty_time  = '0000-00-00 00:00:00';
			$timer_start = $form->getSubmitValue('timer_start');
			$timer_end   = $form->getSubmitValue('timer_end');
			$timer_start = empty($timer_start) ? $empty_time : $timer_start;
			$timer_end   = empty($timer_end) ? $empty_time : $timer_end;

			$calendar_id = $mdb2->extended->getBeforeID('iShark_Calendar', 'calendar_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Calendar
				(calendar_id, title, event, is_major, start_date, end_date, add_user_id, add_date, mod_user_id, mod_date)
				VALUES
				($calendar_id, '".$title."', '".$event."', '$major', '$timer_start', '$timer_end', ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW())
			";
			$mdb2->exec($query);

			//loggolas
			logger($page.'_'.$sub_act);

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&y='.$_POST['y'].'&m='.$_POST['m'].'&d='.$_POST['d']);
			exit;
		}
	}

	/**
	 * ha modositunk egy esemenyt
	 */
	if ($sub_act == "mod") {
		$cid = intval($_REQUEST['cid']);

		//breadcrumb
		$breadcrumb->add($titles[$sub_act], 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=mod&amp;cid=$cid&amp;y='.$_REQUEST['y'].'&amp;m='.$_REQUEST['m'].'&amp;d='.$_REQUEST['d']);

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'cid', $cid);

		//beallitjuk az alapertelmezett ertekeket - modositasnal
		$query = "
			SELECT *
			FROM iShark_Calendar
			WHERE calendar_id = $cid
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			while ($row = $result->fetchRow()) {
				$form->setDefaults(array(
					'title'       => $row['title'],
					'event'       => $row['event'],
					'major'       => $row['is_major'],
					'timer_start' => $row['start_date'],
					'timer_end'   => $row['end_date']
					)
				);
			}
		} else {
			$acttpl = "error";
			$tpl->assign('errormsg', $locale->get('error_no_event'));
			return;
		}

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$major = intval($form->getSubmitValue('major'));
			$title = $form->getSubmitValue('title');
			$event = $form->getSubmitValue('event');
			$start_date = $form->getSubmitValue('timer_start');
			$end_date   = $form->getSubmitValue('timer_end');

			$query = "
				UPDATE iShark_Calendar
				SET title       = '".$title."',
					event       = '".$event."',
					is_major    = '$major',
					start_date  = '$start_date',
					end_date    = '$end_date',
					mod_user_id = '".$_SESSION['user_id']."',
					mod_date    = NOW()
				WHERE calendar_id = $cid
			";
			$mdb2->exec($query);

			//loggolas
			logger($page.'_'.$sub_act);

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&act='.$page.'&y='.$_REQUEST['y'].'&m='.$_REQUEST['m'].'&d='.$_REQUEST['d']);
			exit;
		}
	}

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('tiny_fields', 'event');
	$tpl->assign('lang_title',  $titles[$sub_act]);
	$tpl->assign('form',        $renderer->toArray());
	$tpl->assign('back_arrow',  'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;y='.$_REQUEST['y'].'&amp;m='.$_REQUEST['m'].'&amp;d='.$_REQUEST['d']);


	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha toroljuk az esemenyt
 */
if ($sub_act == "del") {
	$cid = intval($_GET['cid']);

	//megnezzuk, hogy tenyleg letezik-e az esemeny
	$query = "
		SELECT *
		FROM iShark_Calendar
		WHERE calendar_id = $cid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('error_no_event'));
		return;
	}

	//kitoroljuk az esemenyt
	$query = "
		DELETE FROM iShark_Calendar
		WHERE calendar_id = $cid
	";
	$mdb2->exec($query);

	//loggolas
	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&y='.$_GET['y'].'&m='.$_GET['m'].'&d='.$_GET['d']);
	exit;
}

/**
 * ha a listat mutatjuk
 */
if ($sub_act == "lst") {
	//egyedi css
	$css[] = "calendar";

	require_once 'Calendar/Calendar.php';
	require_once 'Calendar/Year.php';
	require_once 'Calendar/Month.php';
	require_once 'Calendar/Day.php';
	require_once 'Calendar/Month/Weekdays.php';
	require_once 'Calendar/Decorator.php';

	$query = "
		SELECT calendar_id AS cid, start_date, end_date, title, is_major,
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
	$prev = 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;y='.date('Y',$prevStamp).'&amp;m='.date('n',$prevStamp).'&amp;d='.date('j',$prevStamp);
	$nextStamp = $month->nextMonth(true);
	$next = 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;y='.date('Y',$nextStamp).'&amp;m='.date('n',$nextStamp).'&amp;d='.date('j',$nextStamp);

	$Year      = new Calendar_Year($_GET['y']);
	$Month     = new Calendar_Month($_GET['y'],$_GET['m']);
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

	$add_new = array(
		array (
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add&amp;y='.$_GET['y'].'&amp;m='.$_GET['m'].'&amp;d='.$_GET['d'],
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

	$fyear     = date('Y', $month->getTimeStamp());
	$fmonth    = date('m', $month->getTimeStamp());
	$monthName = get_date(date('Y-m-d', $month->getTimeStamp()), 'year_month');
	//print $dtum;
	$today     = date('Y-m-d', strtotime($_GET['y']."-".$_GET['m']."-".$_GET['d']));

	$tpl->assign_by_ref('month', $weeksInMonth);

	$tpl->assign('fyear',       $fyear);
	$tpl->assign('fmonth',      $fmonth);
	$tpl->assign('monthName',   $monthName);
	$tpl->assign('prevMonth',   $prev);
	$tpl->assign('nextMonth',   $next);
	$tpl->assign('today_event', $today_event);
	$tpl->assign('today',       $today);
	$tpl->assign('month_array', $month_array);
	$tpl->assign('add_new',     $add_new);
	$tpl->assign('back_arrow',  'admin.php');

	$acttpl = "calendar_list";
}

?>