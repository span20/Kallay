<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "polls";

//nyelvi file betoltese
$locale->useArea($module_name);

$tpl->assign('self', $module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst');

//jogosultsag ellenorzes
if (isset($_REQUEST['pact']) && in_array($_REQUEST['pact'], $is_act)) {
	$pact = $_REQUEST['pact'];
} else {
	$pact = "lst";
}
if (!check_perm($pact, '', 0, $module_name)) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_premission'));
	return;
}

//lekerdezzuk a modulhoz tartozo beallitasokat
$query = "
	SELECT * 
	FROM iShark_Polls_Configs
";
$result = $mdb2->query($query);
while ($row = $result->fetchRow())
{
	$poll_captcha = $row['captcha'];
	$poll_ismenu  = $row['is_menu'];
	$poll_reuse   = $row['reuse_time'];
	$poll_oldpoll = $row['oldpoll_view'];
}

//lekerdezzuk az idozitett szavazasokat, ha lejart az idozites, akkor lezarjuk oket
$query = "
	SELECT p.poll_id AS pid 
	FROM iShark_Polls p 
	WHERE p.is_active = 1 AND p.end_date != '0000-00-00 00:00:00' AND p.timer_start != '0000-00-00 00:00:00' AND p.timer_end < NOW()
";
$result = $mdb2->query($query);
if ($result->numRows() > 0) {
	while ($row = $result->fetchRow())
	{
		$pid = $row['pid'];
		//lezarjuk a szavazasokat
		$query = "
			UPDATE iShark_Polls 
			SET end_date = NOW() 
			WHERE poll_id = $pid
		";
		$mdb2->exec($query);
	}
}

//lekerdezzuk azoknak a szavazasoknak a listajat, amik aktivak, idozitve vannak, de meg nincsenek elinditva
//ha van ilyen, akkor azt elinditjuk
$query = "
	SELECT p.poll_id AS pid 
	FROM iShark_Polls p 
	WHERE p.is_active = 1 AND p.start_date = '0000-00-00 00:00:00' AND p.timer_start >= NOW()
";
$result = $mdb2->query($query);
if ($result->numRows() > 0) {
	while ($row = $result->fetchRow())
	{
		$pid = $row['pid'];
		//megnyitjuk a szavazasokat
		$query = "
			UPDATE iShark_Polls 
			SET start_date = NOW() 
			WHERE poll_id = $pid
		";
		$mdb2->exec($query);
	}
}

//ha lathatoak a lezart szavazasok eredmenyei, akkor kirakjuk a linket
if ($poll_oldpoll == 1) {
	$tpl->assign('old_poll', 'index.php?p=polls_old');
}

if ($pact == "lst") {
	$query = "
		SELECT p.poll_id AS pid, p.title AS ptitle 
		FROM iShark_Polls p 
		WHERE p.is_active = 1 AND p.start_date != '0000-00-00 00:00:00' AND p.end_date = '0000-00-00 00:00:00' 
			AND (p.timer_start = '0000-00-00 00:00:00' OR (p.timer_start < NOW() AND p.timer_end > NOW())) 
	";
	//ha tartozhat menuhoz szavazas
	if (isset($_REQUEST['mid']) && is_numeric($_REQUEST['mid']) && ($_REQUEST['mid'] != "" || $_REQUEST['mid'] != 0) &&  $poll_ismenu == 1) {
		$mid = intval($_REQUEST['mid']);

		//lekerdezzuk, hogy tartozik-e a menuhoz szavazas, ha igen, akkor azt jelenitjuk meg
		$query_menu = "
			SELECT menu_id 
			FROM iShark_Polls 
			WHERE menu_id = $mid AND is_active = 1
		";
		$result_menu = $mdb2->query($query_menu);
		if ($result_menu->numRows() > 0) {
			$menu = $result_menu->fetchRow();
			$query .= "
				AND p.menu_id = ".$menu['menu_id']."
			";
		} else {
			$query .= "
				AND p.menu_id = 0
			";
		}
	} else {
		$query .= "
			AND p.menu_id = 0 
		";
	}
	$query .= "
		ORDER BY p.start_date
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		while ($row = $result->fetchRow())
		{
			$pid    = $row['pid'];
			$ptitle = $row['ptitle'];
		}

		//megnezzuk, hogy a jelenlegi szavazasra adott-e mar szavazatot
		$query = "
			SELECT pv.add_date AS add_date, pd.poll_text AS poll_text 
			FROM iShark_Polls_Datas pd, iShark_Polls_Votes pv 
			WHERE pd.poll_id = $pid AND pv.data_id = pd.data_id 
		";
		if (isset($_SESSION['user_id'])) {
			$query .= "
				AND user_id = ".$_SESSION['user_id']."
			";
		} else {
			$query .= "
				AND user_ip = '".get_ip()."' AND pv.add_date > NOW()-".$poll_reuse."
			";
		}
		$result = $mdb2->query($query);
		//ha mar szavazott ra, akkor csak az utolso szavazatat, idejet mutatjuk
		if ($result->numRows() > 0) {
			$polls_voted = $result->fetchAll();

			$tpl->assign('polls_voted',   $polls_voted);
			$tpl->assign('ptitle',        $ptitle);
			$tpl->assign('is_poll_voted', 1);
		}
		//ha meg nem szavazott ra, akkor kirakjuk a szavazo formot
		else {
			$form_polls =& new HTML_QuickForm('frm_polls', 'post', 'index.php?p=polls_block');

			$form_polls->addElement('header', 'polls', $ptitle);
			$form_polls->addElement('hidden', 'pid',   $pid);
			if (isset($_REQUEST['mid']) && is_numeric($_REQUEST['mid'])) {
				$mid = intval($_REQUEST['mid']);
				$form_polls->addElement('hidden', 'mid', $mid);
			}

			//lekerdezzuk es letrehozzuk a valaszokhoz a form elemeket
			$query = "
				SELECT pd.poll_text AS ptext, pd.data_id AS pdid 
				FROM iShark_Polls_Datas pd 
				WHERE pd.poll_id = $pid 
				ORDER BY pd.sortorder
			";
			$result = $mdb2->query($query);
			$numdata = $result->numRows();
			$answer = array();
			$k = 0;
			while ($row = $result->fetchRow())
			{
				$answer[] =& HTML_QuickForm::createElement('radio', null, null, $row['ptext'], $row['pdid'], array('class' => 'noborder'));
				$k++;
			}
			$form_polls->addGroup($answer, 'answer', '', '<br />');

			//ha captcha-t hasznalunk a szavazashoz
			if ($poll_captcha == 1) {
				require_once 'Text/CAPTCHA.php';

				$form_polls->addElement('text', 'recaptcha', $locale->get('field_block_captcha'), 'class="input_box"');
				$form_polls->addRule('recaptcha', $locale->get('error_block_captcha'), 'required');
				if ($form_polls->isSubmitted() && $form_polls->getSubmitValue('recaptcha') != $_SESSION['phrase']) {
					$form_polls->setElementError('recaptcha', $locale->get('error_block_captcha2'));
				}

				$options = array(
					'font_size' => 12,
					'font_path' => $libs_dir.'/',
					'font_file' => 'arial.ttf'
				);

				// Generate a new Text_CAPTCHA object, Image driver
				$c = Text_CAPTCHA::factory('Image');
				$retval = $c->init(140, 40, null, $options);

				// Get CAPTCHA secret passphrase
				$_SESSION['phrase'] = $c->getPhrase();

				// Get CAPTCHA image (as PNG)
				$png = $c->getCAPTCHAAsPNG();

				if (!function_exists('file_put_contents')) {
					function file_put_contents($filename, $content) {
						if (!($file = fopen('files/'.$filename, 'w'))) {
							return false;
						}
						$n = fwrite($file, $content);
						fclose($file);
						return $n ? $n : false;
					}
				}
				file_put_contents(md5(session_id()) . '.png', $png);
				$tpl->assign('captcha', 'files/'.md5(session_id()).'.png');
			}

			$form_polls->addElement('submit', 'submit', $locale->get('form_submit_poll'), 'class="submit"');

			if ($form_polls->validate()) {
				$pid    = intval($form_polls->getSubmitValue('pid'));
				$answer = intval($form_polls->getSubmitValue('answer'));

				if (isset($_SESSION['user_id'])) {
					$user_id = $_SESSION['user_id'];
					$user_ip = "";
				} else {
					$user_id = "";
					$user_ip = get_ip();
				}

				$query = "
					INSERT INTO iShark_Polls_Votes 
					(data_id, user_id, user_ip, add_date) 
					VALUES 
					('$answer', $user_id, '$user_ip', NOW())
				";
				$mdb2->exec($query);

				if ($poll_captcha == 1) {
					@unlink('files/'.md5(session_id()).'.png');
				}

				if (isset($_REQUEST['mid'])) {
					header('Location: index.php?mid='.$_REQUEST['mid']);
					exit;
				} else {
					header('Location: index.php');
					exit;
				}
			}

			$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);

			$form_polls->accept($renderer);

			$tpl->assign('form_polls', $renderer->toArray());
			$tpl->assign('polls_yes',  1);

			// capture the array stucture
			ob_start();
			print_r($renderer->toArray());
			$tpl->assign('static_array', ob_get_contents());
			ob_end_clean();
		}
	}
	$acttpl = 'polls';
}

?>
