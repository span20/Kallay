<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "polls";

//nyelvi file betoltese
$locale->useArea($module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);
$tpl->assign('title_module', $title_module);
$tpl->assign('self',         $module_name);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

// ezek a megengedett muveletek
$is_act = array('add', 'mod', 'del', 'lst', 'act', 'res', 'ins', 'unins');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, NULL, 1, $module_name)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

//modulhoz tartozo beallitasok lekerdezese
$query = "
	SELECT pc.is_menu AS ismenu 
	FROM iShark_Polls_Configs pc
";
$result = $mdb2->query($query);
while ($row = $result->fetchRow())
{
	$ismenu = $row['ismenu'];
}

require_once $include_dir.'/function.polls.php';

/**
 * ha telepitjuk a modult
 */
if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Polls` (
			`poll_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`title` VARCHAR( 255 ) NOT NULL ,
			`menu_id` INT NOT NULL ,
			`add_user_id` INT NOT NULL ,
			`add_date` DATETIME NOT NULL ,
			`mod_user_id` INT NOT NULL ,
			`mod_date` DATETIME NOT NULL ,
			`is_active` CHAR( 1 ) NOT NULL ,
			`timer_start` DATETIME NOT NULL ,
			`timer_end` DATETIME NOT NULL ,
			`start_date` DATETIME NOT NULL ,
			`end_date` DATETIME NOT NULL ,
			`is_reg_poll` CHAR( 1 ) NOT NULL ,
		INDEX ( `menu_id` , `add_user_id` , `mod_user_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Polls_Datas` (
			`data_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`poll_id` INT NOT NULL ,
			`poll_text` VARCHAR( 255 ) NOT NULL ,
			`sortorder` INT NOT NULL ,
		INDEX ( `poll_id`)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Polls_Votes` (
			`data_id` INT NOT NULL ,
			`user_id` INT NOT NULL ,
			`user_ip` VARCHAR( 15 ) NOT NULL ,
			`add_date` DATETIME NOT NULL ,
		INDEX ( `data_id` , `user_id` )
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Polls_Configs` (
			`captcha` CHAR( 1 ) NOT NULL ,
			`is_menu` CHAR( 1 ) NOT NULL ,
			`reuse_time` INT( 4 ) NOT NULL ,
			`oldpoll_view` CHAR( 1 ) NOT NULL
		);
	";
	$mdb2->exec($query);
	//ha nem ures, akkor beszurunk egy sort
	$query = "
		SELECT * FROM iShark_Polls_Configs
	";
	$result = $mdb2->query($query);
	if ($result->numRows() == 0) {
		$query = "
			INSERT INTO iShark_Polls_Configs 
			SET captcha = '1', is_menu = '1', reuse_time = '900', oldpoll_view = '1'
		";
		$mdb2->exec($query);
	}

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha toroljuk a modult
 */
if ($act == "unins") {
	$query = "
		DROP TABLE IF EXISTS `iShark_Polls`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Polls_Datas`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Polls_Votes`
	";
	$mdb2->exec($query);

	$query = "
		DROP TABLE IF EXISTS `iShark_Polls_Configs`
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
} //torles vege

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($act == "add" || $act == "mod") {
	$javascripts[] = "javascripts";

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_polls', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', $locale->get('form_header'));

	//csak regisztralt felhasznalok szavazhatnak
	$regpoll = array();
	$regpoll[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$regpoll[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
	$form->addGroup($regpoll, 'regpoll', $locale->get('field_regpoll'), '&nbsp;');

	//szavazas kerdese
	$form->addElement('text', 'question', $locale->get('field_question'));

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

	//uj valasz
	$form->addElement('link',   'link',   '', 'javascript:;', $locale->get('field_answer'), 'onClick="addEvent(\''.$locale->get('field_delanswer').'\');"');
	$form->addElement('static', 'answer', $locale->get('field_answertext'));

	$form->addElement('submit', 'submit', $locale->get('field_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('field_reset'),  'class="reset"');

	//ha menuhoz is hozza lehet adni a szavazast
	if ($ismenu == 1) {
		//kirakunk egy ures option-t az elejere
		$empty_array = array('' => '');

		//lekerdezzuk, hogy milyen menupontokat lehet hozzaadni
		$query = "
			SELECT m.menu_id AS mid, CONCAT(m.type, ' | ', m.menu_name) 
			FROM iShark_Menus m 
			WHERE m.is_active = 1 AND (m.timer_start = '0000-00-00 00:00:00' OR (m.timer_start < NOW() AND m.timer_end > NOW()))
			ORDER BY m.type, m.menu_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'menulist', $locale->get('field_menus'), $empty_array + $result->fetchAll('', $rekey = true));
	}

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('regpoll',  $locale->get('error_regpoll'), 'required');
	$form->addRule('question', $locale->get('error_name'),    'required');
	$form->addGroupRule('answer', $locale->get('error_answer'),   'required');
	if ($form->isSubmitted() && (empty($_POST['answer']) || !is_array($_POST['answer']))) {
		$form->setElementError('answer', $locale->get('error_answer'));
	}
	//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
	if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
		$form->addFormRule('check_timer');
	}

	/**
	 * hozzaadas
	 */
	if ($act == "add") {
		$form->addElement('hidden', 'act', $act);

		$form->setDefaults(array(
			'regpoll' => 0
			)
		);

		$form->addFormRule('check_addquestion');
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$regpoll  = intval($form->getSubmitValue('regpoll'));
			$question = $form->getSubmitValue('question');

			//ha engedelyezve van a menuponthoz adas
			if ($ismenu == 1) {
				$menulist = intval($form->getSubmitValue('menulist'));
			} else {
				$menulist = 0;
			}

			$query = "
				INSERT INTO iShark_Polls 
				(title, add_user_id, add_date, mod_user_id, mod_date, is_active, timer_start, timer_end, 
				is_reg_poll, menu_id) 
				VALUES 
				('".$question."', ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW(), '0', '$timer_start', '$timer_end', 
				'$regpoll', '$menulist')
			";
			$mdb2->exec($query);

			$last_poll_id = $mdb2->lastInsertId('iShark_Polls');

			//lekerdezzuk a legmagasabb sorszamokat
			$maxorder = 0;
			$query = "
				SELECT MAX(sortorder) AS sortorder 
				FROM iShark_Polls_Datas 
			";
			$result = $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$maxorder = $row['sortorder'];
			}

			foreach($_POST['answer'] as $kerdes) {
				if (trim($kerdes) != "") {
					$query = "
						INSERT INTO iShark_Polls_Datas 
						(poll_id, poll_text, sortorder) 
						VALUES 
						($last_poll_id, '".$kerdes."', '$maxorder'+1)
					";
					$mdb2->exec($query);
					$maxorder ++;
				}
			}

			//loggolas
			logger($act, '', '');

			//form "fagyasztasa"
			$form->freeze();

			header('Location: admin.php?p='.$module_name);
			exit;
		}

		//breadcrumb
		$breadcrumb->add($titles[$act], 'admin.php?p='.$module_name.'&amp;act=add');

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'polls_add';
	} //hozzaadas vege

	/**
	 * modositas
	 */
	if ($act == "mod") {
		$pid = intval($_REQUEST['pid']);

		$form->addElement('hidden', 'act', $act);
		$form->addElement('hidden', 'pid', $pid);

		//lekerdezzuk, hogy a szavazasra erkezett-e mar szavazat, ha igen, akkor nem modosithato
		$query = "
			SELECT pv.data_id, p.end_date AS end_date
			FROM iShark_Polls_Votes pv, iShark_Polls_Datas pd, iShark_Polls p 
			WHERE pv.data_id = pd.data_id AND pd.poll_id = $pid AND p.poll_id = pd.poll_id 
		";
		$result = $mdb2->query($query);
		$row = $result->fetchRow();
		//ha mar le van zarva a szavazas
		if (isset($row['end_date']) && $row['end_date'] != '0000-00-00 00:00:00') {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('error_poll_closed'));
			return;
		}
		//ha mar erkezett ra szavazat
		elseif ($result->numRows() > 0) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $locale->get('error_notempty'));
			return;
		}
		else {
			//lekerdezzuk a szavazas tabla tartalmat, es beallitjuk alapertelmezettnek
			$query = "
				SELECT p.title AS ptitle, p.is_reg_poll AS preg, p.timer_start AS timer_start, p.timer_end AS timer_end, p.menu_id AS pmid 
				FROM iShark_Polls p 
				WHERE poll_id = $pid
			";
			$result = $mdb2->query($query);
			if ($result->numRows() == 0) {
				$acttpl = 'error';
				$tpl->assign('errormsg', $locale->get('error_notexists'));
				return;
			} else {
				while ($row = $result->fetchRow())
				{
					$form->setDefaults(array(
						'regpoll'     => $row['preg'],
						'question'    => $row['ptitle'],
						'timer_start' => $row['timer_start'],
						'timer_end'   => $row['timer_end'],
						'menulist'    => $row['pmid']
						)
					);
				}
			}

			//lekerdezzuk a valaszok tabla tartalmat, es beallitjuk alapertelmezettnek
			$query = "
				SELECT poll_text 
				FROM iShark_Polls_Datas 
				WHERE poll_id = $pid 
				ORDER BY sortorder
			";
			$result = $mdb2->query($query);
			if ($result->numRows() > 0) {
				$answer_array = array();
				$i = 0;
				while ($row = $result->fetchRow())
				{
					$answer_array[$i]['answer'] = $row['poll_text'];
					$i++;
				}
				$tpl->assign('answer', $answer_array);
			}

			$form->addFormRule('check_modquestion');
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$regpoll  = intval($form->getSubmitValue('regpoll'));
				$question = $form->getSubmitValue('question');

				//ha engedelyezve van a menuponthoz adas
				if ($ismenu == 1) {
					$menulist = intval($form->getSubmitValue('menulist'));
				} else {
					$menulist = 0;
				}

				$query = "
					UPDATE iShark_Polls 
					SET title       = '".$question."', 
						mod_user_id = '".$_SESSION['user_id']."', 
						mod_date    = NOW(), 
						timer_start = '$timer_start', 
						timer_end   = '$timer_end', 
						is_reg_poll = '$regpoll', 
						menu_id     = '$menulist' 
					WHERE poll_id = $pid
				";
				$mdb2->exec($query);

				//eldobjuk azokat a valasz mezoket, amik ehhez a szavazshoz tartoznak
				$query = "
					DELETE FROM iShark_Polls_Datas 
					WHERE poll_id = $pid
				";
				$mdb2->exec($query);

				//lekerdezzuk a legmagasabb sorszamokat
				$maxorder = 0;
				$query = "
					SELECT MAX(sortorder) AS sortorder 
					FROM iShark_Polls_Datas 
				";
				$result = $mdb2->query($query);
				while ($row = $result->fetchRow())
				{
					$maxorder = $row['sortorder'];
				}

				foreach($_POST['answer'] as $kerdes) {
					if (trim($kerdes) != "") {
						$query = "
							INSERT INTO iShark_Polls_Datas 
							(poll_id, poll_text, sortorder) 
							VALUES 
							($pid, '".$kerdes."', '$maxorder'+1)
						";
						$mdb2->exec($query);
						$maxorder ++;
					}
				}

				//loggolas
				logger($act, '', '');

				//form "fagyasztasa"
				$form->freeze();

				header('Location: admin.php?p='.$module_name);
				exit;
			}

			//breadcrumb
			$breadcrumb->add($titles[$act], 'admin.php?p='.$module_name.'&amp;act=mod&amp;pid='.$pid);

			//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
			$acttpl = 'polls_mod';
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	$form->accept($renderer);

	$tpl->assign('lang_title', $titles[$act]);
	$tpl->assign('ismenu',     $ismenu);
	$tpl->assign('form',       $renderer->toArray());

	//capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('static_array', ob_get_contents());
	ob_end_clean();
}

/**
 * ha torlunk
 */
if ($act == "del") {
	$pid = intval($_REQUEST['pid']);

	$query = "
		SELECT data_id 
		FROM iShark_Polls_Datas 
		WHERE poll_id = $pid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			$data_id = $row['data_id'];
		}

		$query = "
			DELETE FROM iShark_Polls_Votes 
			WHERE data_id = $data_id
		";
		$mdb2->exec($query);

		$query = "
			DELETE FROM iShark_Polls_Datas 
			WHERE poll_id = $pid
		";
		$mdb2->exec($query);
	}

	$query = "
		DELETE FROM iShark_Polls 
		WHERE poll_id = $pid
	";
	$mdb2->exec($query);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}

/**
 * ha aktivalunk
 */
if ($act == "act") {
	include_once $include_dir.'/function.check.php';
	$pid = intval($_GET['pid']);

	//lekerdezzuk, hogy milyen statuszu a szavazas, ha mar le van zarva, akkor nem lehet ujraaktivalni
	$query = "
		SELECT is_active, end_date, menu_id 
		FROM iShark_Polls 
		WHERE poll_id = $pid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			//ha nem aktiv es a befejezo datum nem ures, akkor mar egyszer le lett zarva
			if ($row['is_active'] == 0 && $row['end_date'] != "0000-00-00 00:00:00") {
				$acttpl = "error";
				$tpl->assign('errormsg', $locale->get('error_finished'));
				return;
			}
			//ha a szavazas aktiv, de meg nincs lezarva, akkor lezarjuk, inaktivaljuk
			elseif ($row['is_active'] == 1 && $row['end_date'] == "0000-00-00 00:00:00") {
				$query = "
					UPDATE iShark_Polls 
					SET end_date = NOW() 
					WHERE poll_id = $pid
				";
				$mdb2->exec($query);

				check_active('iShark_Polls', 'poll_id', $pid);

				//loggolas
				logger($act, '', '');
			}
			else {
				$menu = $row['menu_id'];

				$query = "
					UPDATE iShark_Polls 
					SET start_date = NOW() 
					WHERE poll_id = $pid
				";
				$mdb2->exec($query);

				check_active('iShark_Polls', 'poll_id', $pid);

				//lekerdezzuk, hogy van-e ezen kivul lezaratlan szavazas, ha van, akkor azt lezarjuk
				$query = "
					SELECT poll_id AS pid 
					FROM iShark_Polls 
					WHERE menu_id = $menu AND is_active = 1 AND poll_id != $pid
				";
				$result = $mdb2->query($query);
				if ($result->numRows() > 0) {
					while ($row = $result->fetchRow())
					{
						$pid = $row['pid'];
						$query = "
							UPDATE iShark_Polls 
							SET end_date = NOW(), is_active = 0 
							WHERE poll_id = $pid
						";
						$mdb2->exec($query);
					}
				}

				//loggolas
				logger($act, '', '');
			}
		}
	} else {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('poll_notexists'));
		return;
	}

	header('Location: admin.php?p='.$module_name);
	exit;
} //aktivalas vege

/**
 * ha az eredmenyt nezzuk
 */
if ($act == "res") {
	$pid = intval($_REQUEST['pid']);

	//lekerdezzuk a szavazast
	$query = "
		SELECT p.title AS ptitle, p.timer_start AS timer_start, p.timer_end AS timer_end, p.start_date AS start_date, 
			p.end_date AS end_date
		FROM iShark_Polls p 
		WHERE p.poll_id = $pid
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		$poll = $result->fetchAll();
	} else {
		$acttpl = "error";
		$tpl->assign('errormsg', $locale->get('error_notexists'));
		return;
	}

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
		$poll_text[$i]['text']     = $row['text'];
		$poll_text[$i]['polldata'] = $row['polldata'];
		if ($poll_num['polldata'] == 0) {
			$poll_text[$i]['percent'] = 100;
		} else {
			$poll_text[$i]['percent'] = substr(100 * $row['polldata'] / $poll_num['polldata'], 0, 6);
		}
		$i++;
	}

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('poll_data',  $poll);
	$tpl->assign('poll_text',  $poll_text);
	$tpl->assign('poll_num',   $poll_num);
	$tpl->assign('pid',        $pid);

	//breadcrumb
	$breadcrumb->add($locale->get('field_result_title'), 'admin.php?p='.$module_name.'&amp;act=res&amp;pid='.$pid);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl= "polls_result";
} //eredmenylista vege

/**
 * Ha a listát mutatjuk
 */
if ($act == "lst") {
	$query = "
		SELECT p.poll_id AS pid, p.title AS ptitle, p.is_active AS pact, p.add_date AS add_date, p.start_date AS start_date, 
			p.end_date AS end_date, u.name AS add_name, p.timer_start AS pstart, p.timer_end AS pend
		FROM iShark_Polls p 
		LEFT JOIN iShark_Users u ON u.user_id = p.add_user_id 
		ORDER BY p.add_date DESC
	";

	//lapozo
	require_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	//lekerdezzuk a valaszok listajat, a kerdesre allva ezek fognak megjelenni
	foreach ($paged_data['data'] as $key => $adat) {
		$answerlist = "";
		$query = "
			SELECT pd.poll_text AS polltext 
			FROM iShark_Polls_Datas pd 
			WHERE pd.poll_id = '".$adat['pid']."' 
			ORDER BY sortorder
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow())
		{
			$answerlist .= $row['polltext']."<br />";
		}
		$adat['answerlist'] = $answerlist;
		$data[] = $adat;
	}

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act=add',
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	if (!empty($data)) {
		$tpl->assign('page_data', $data);
	}
	$tpl->assign('page_list', $paged_data['links']);
	$tpl->assign('add_new',   $add_new);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'polls_list';
}

?>
