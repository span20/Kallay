<?php
/**
 * Todo:
 * - Három szintes fórum
 * - Színek orvos/páciens
 * - címkézések
 * - Feliratkozás témára
 * - levél küldése ha a témát kommentelték.
 * - rendszerezés: kedvencek - legfrissebbek - címkék és legtöbbet kommentelt felhasználók szerint.
 * - kedvencekhez adás
 * - értékelés
 */
// --------- $locale-ra való átállásnál törölni a következõt:
$strForumTopicNotGiven = "Nincs megadva a téma";
$strForumActiveUsers   = "Legaktívabb felhasználók";
$strForumAddForum      = "Fórum létrehozása";
// --------- eddig

$forum_files_dir       = "files/forum";
$embed                 = TRUE;

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "forum";
$locale->useArea("index_".$module_name);
// nyelvi fájl betöltése
require_once $lang_dir.'/modules/forum/'.$_SESSION['site_lang'].'.php';
require_once $include_dir.'/classes.php';
// Breadcrumb készítése
$forum_breadcrumb =& new Breadcrumb();


//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('lst', 'add', 'mod', 'del', 'addmsg', 'modmsg', 'lstmsg', 'act', 'block', 'delmsg', 'censor', 'censoradd', 'censordel', 'censormod', 'bookmark', 'delbookmark', 'active_users', 'fadd', 'fdel');

//menu azonosito vizsgalata
$menu_id = 0;
if (isset($_GET['mid'])) {
	$menu_id = intval($_GET['mid']);
	$self = "mid=".$menu_id;
} else {
	$self = "p=$module_name";
}
$tpl->assign('back', NULL);
$tpl->assign('self', $self);


$parent = 0;
if (isset($_REQUEST["parent"])) {
    $parent = intval($_REQUEST["parent"]);
    if ($parent != 0) {
        $query = "SELECT * FROM iShark_Forum_Topics WHERE topic_id=$parent";
        $res =& $mdb2->query($query);
        if (!($forum_data = $res->fetchRow())) {
            $acttpl = "error";
            $tpl->assign("errormsg", $strForumErrorPermissionDenied);
            return;
        }
        makeBreadcrumb($parent, $forum_breadcrumb);
    }
}
$tpl->assign('parent', $parent);
$forum_breadcrumb->insertBefore($strForumHeader, "index.php?$self");

//lekerdezzuk a modulhoz tartozo beallitasokat
$query = "
	SELECT * FROM iShark_Forum_Configs
";
$result = $mdb2->query($query);
if ($result->numRows() == 0) {
	die($strForumMissingTable);
} else {
	$settings = $result->fetchRow();
}

// Értékelési jog meghatározása
$rate_right = $settings['forum_rating'] &&
      (isset($_SESSION['user_id']) || (!$settings['forum_rating_only_reg']));


// TOPIC id lekérdezése, ha van kiválasztott topic, akkor $tid!=0
$tid = 0;
if (isset($_REQUEST['tid'])) {
	$tid = (int) $_REQUEST['tid'];
	if (!isset($_REQUEST['act'])) {
		$_REQUEST['act'] = 'lstmsg';
	}
}

if ($tid != 0) {
	$query = "
		SELECT * 
		FROM iShark_Forum_Topics 
		WHERE topic_id = $tid
	";
	$result =& $mdb2->query($query);
	if (!$topic_data = $result->fetchRow()) {
		header("Location: index.php?$self");
		exit;
	}
	$tpl->assign('tid', $tid);
}

$msgid = 0;
if (isset($_REQUEST['msgid']) && $_REQUEST['msgid'] != '0') {
	if ($tid > 0) {
		$msgid = (int) $_REQUEST['msgid'];
		$query = "
			SELECT * 
			FROM iShark_Forum_Messages 
			WHERE topic_id = $tid AND message_id = $msgid
		";
		$result =& $mdb2->query($query);
		if (!$message_data = $result->fetchRow()) {
			header("Location: index.php?$self");
			exit;
		}
	} 
}

/**
 * ha valamilyen muveletet hajtunk vegre
 */
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
    

    if ($_REQUEST["act"] == "active_users") {
        $query = "
            SELECT U.user_id as user_id, count(M.add_user_id) AS message_count, U.name as name, U.user_name as user_name
            FROM iShark_Forum_Messages M
            LEFT JOIN ".DB_USERS.".iShark_Users U on M.add_user_id = U.user_id
            GROUP BY add_user_id
            ORDER BY message_count DESC
        ";
        
        include_once 'Pager/Pager.php';
		$pd = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		$tpl->assign('forum_userpages',     $pd['data']);
		$tpl->assign('forum_userlinks',     $pd['links']);
		
		$tpl->assign('lang_forum', array(
		      'strForumActiveUsers'   => $strForumActiveUsers,
		      'strForumActiveUsersName' => "Név",
		      'strForumActiveUsersMessageCount' => "Hozzászólások száma"
		));
		$acttpl = "forum_active_users";
		return;
    }
    
    /**
     * Könyvjelzõ hozzáadása
     */
    if ($_REQUEST['act'] == 'bookmark') {
        if ($tid == 0 || !isset($_SESSION["user_id"])) {
            $acttpl = "error";
            $tpl->assign("errormsg", $strForumTopicNotGiven);
            return;
        }
        $res = $mdb2->query("SELECT * FROM iShark_Forum_Bookmarks WHERE user_id=$_SESSION[user_id] AND topic_id=$tid");
        if ($res->fetchCol()) {
            $acttpl = "error";
            $tpl->assign("errormsg", $strForumBookmarkAlreadySet);
            return;
        }
        $mdb2->exec("INSERT INTO iShark_Forum_Bookmarks (topic_id, user_id) VALUES ($tid, $_SESSION[user_id])");
        header("Location: $_SERVER[PHP_SELF]?$self&parent=$parent");
        exit;
    }
    
    /**
     * Könyvjelzõ kivétele
     */
    if ($_REQUEST['act'] == 'delbookmark') {
        if ($tid == 0 || !isset($_SESSION["user_id"])) {
            $acttpl = "error";
            $tpl->assign("errormsg", "Hozzáférés megtagadva!");
            return;
        }
        $mdb2->exec("DELETE FROM iShark_Forum_Bookmarks WHERE topic_id=$tid AND user_id=$_SESSION[user_id]");
        header("Location: $_SERVER[PHP_SELF]?$self&parent=$parent");
        exit;
    }
    
	/**
	 * Cenzúrázott kifejezés törlése
	 */
	if ($_REQUEST['act'] == 'censordel') {
		if (!check_perm('censordel', $menu_id, 1, 'forum', 'index') || !isset($_REQUEST['cid'])) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
		$cid = (int) $_REQUEST['cid'];
		$query = "
			DELETE FROM iShark_Forum_Censor 
			WHERE cens_id = $cid
		";
		$mdb2->exec($query);
		header("Location: index.php?$self&act=censor");
		exit;
	}
	
	/**
	 *  Cenzúrázott kifejezés hozzáadása / módosítása
	 */
	if ($_REQUEST['act'] == 'censoradd' || $_REQUEST['act'] == 'censormod') {
		$act = $_REQUEST['act'];
		if (!check_perm($act, $menu_id, 1, 'forum', 'index')) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
		
		include_once 'HTML/QuickForm.php';
		include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		
		$form_forum =& new HTML_QuickForm('forum_frm', 'post', 'index.php?'.$self);
		$form_forum->setRequiredNote($strForumRequiredNote);
		
		$form_forum->addElement('hidden', 'act', $act);
		$form_forum->addElement('hidden', 'parent', $parent);
		$form_forum->addElement('text', 'word', $strForumCensorWord, 'maxlength="255" class="wholeline"');
		$form_forum->addElement('header', 'headername', $act == 'censoradd' ? $strForumCensorAddHeader: $strForumCensorModHeader);
		
		if ($act == 'censormod') {
			if (!isset($_REQUEST['cid'])) {
				$acttpl = 'error';
				$tpl->assign('errormsg', $strForumErrorPermissionDenied);
				return;
			}
			$cid = (int) $_REQUEST['cid'];
			$query = "
				SELECT word 
				FROM iShark_Forum_Censor 
				WHERE cens_id = $cid
			";
			$result =& $mdb2->query($query);
			if (!$defaults = $result->fetchRow()) {
				$acttpl = 'error';
				$tpl->assign('errormsg', $strForumErrorPermissionDenied);
				return;
			}
			$form_forum->addElement('hidden', 'cid', $cid);
			$form_forum->setDefaults($defaults);
		}
		
		$form_forum->applyFilter('__ALL__', 'trim');
		$form_forum->addRule('word', $strForumCensorErrorWord, 'required');

		if ($form_forum->validate()) {
			$form_forum->applyFilter('__ALL__', array(&$mdb2, 'escape'));
			$_word = $form_forum->getSubmitValue('word');
			if ($act == 'censormod') {
				$query = "
					UPDATE iShark_Forum_Censor 
					SET word = '$_word' 
					WHERE cens_id = $cid
				";
			} else {
				$cens_id = $mdb2->extended->getBeforeID('iShark_Forum_Censor', 'cens_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Forum_Censor 
					(cens_id, word) 
					VALUES 
					($cens_id, '$_word')
				";
			}
			$mdb2->exec($query);
			header("Location: index.php?$self&act=censor");
			exit;
		}
		
		$form_forum->addElement('submit', 'submit', $strForumSubmit, 'class="submit"');
		$form_forum->addElement('reset', 'reset', $strForumReset, 'class="submit"');
		
		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_forum->accept($renderer);

		$tpl->assign('form_forum', $renderer->toArray());

		$lang_forum = array('strForumBack' => $strForumBack);
		$tpl->assign('lang_forum', $lang_forum);
		$acttpl = 'forum_censorword';
	}

	/**
	 * Cenzúrázott kifejezések listája
	 */

	if ($_REQUEST['act'] == 'censor') {
		if (!check_perm('censor', $menu_id, 1, 'forum', 'index')) { 
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}

		$lang_forum = array(
			'strForumCensorHeader'			=> $strForumCensorHeader,
			'strForumCensorAdd'				=> $strForumCensorAdd,
			'strForumCensorMod'				=> $strForumCensorMod,
			'strForumCensorDel'				=> $strForumCensorDel,
			'strForumCensorTotal'			=> $strForumCensorTotal,
			'strForumCensorDeleteConfirm'	=> $strForumCensorDeleteConfirm,
			'strForumCensorWord'			=> $strForumCensorWord,
			'strForumCensorActions'			=> $strForumCensorActions,
			'strForumCensorEmptyList'		=> $strForumCensorEmptyList,
			'strForumBack'					=> $strForumBack,
		);

		$query = "
			SELECT * 
			FROM iShark_Forum_Censor 
			ORDER BY word
		";

		include_once 'Pager/Pager.php';
		$pd = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

		$tpl->assign('pd_forum',     $pd['data']);
		$tpl->assign('pl_forum',     $pd['links']);
		$tpl->assign('censor_total', $pd['totalItems']);

		$tpl->assign('lang_forum', $lang_forum);

		$tpl->assign('censoradd_right', check_perm('censoradd', $menu_id, 1, 'forum', 'index'));
		$tpl->assign('censormod_right', check_perm('censormod', $menu_id, 1, 'forum', 'index'));
		$tpl->assign('censordel_right', check_perm('censordel', $menu_id, 1, 'forum', 'index'));

		$acttpl = 'forum_censored_words';
	}

	/**
	 * Blokkolás 
	 */
	if ($_REQUEST['act'] == 'block' && $msgid != 0) {
		if (!check_perm('block', $menu_id, 1, 'forum', 'index')) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}

		$query = "
			UPDATE iShark_Forum_Messages 
			SET is_blocked=(CASE is_blocked WHEN '1' THEN '0' ELSE '1' END) 
			WHERE topic_id = $tid AND message_id = $msgid
		";
		$mdb2->exec($query);

		logger('block', $menu_id);

		header("Location: index.php?$self&parent=$parent&tid=$tid");
		exit;
	}

	/**
	 * Üzenet törlése
	 */
	if ($_REQUEST['act'] == 'delmsg' && $msgid != 0) {
		if (!check_perm('delmsg', $menu_id, 1, 'forum', 'index')) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}

		$query = "
			DELETE FROM iShark_Forum_Messages 
			WHERE topic_id = $tid AND message_id = $msgid
		";
		$mdb2->exec($query);

		logger('delmsg', $menu_id);

		header("Location: index.php?$self&parent=$parent&tid=$tid");
		exit;
	}

	/**
	 * ha uj bejegyzest adunk hozza
	 */
	if (($_REQUEST['act'] == "addmsg" || $_REQUEST['act'] == 'modmsg') && $tid!=0) {
		$act = $_REQUEST['act'];
		if ($topic_data['write_everybody'] == '0' && !isset($_SESSION['user_id'])) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strErrorForumOnlyReg);
			return;
		} 
		if ($act == 'modmsg' && $msgid==0) {
			header("Location: index.php?$self&parent=$parent&tid=$tid");
			exit;
		}
		if ($act == 'modmsg' && !check_perm('modmsg', $menu_id, 1, 'forum', 'index')) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
		$ip = get_ip();

		$is_flood = 0;
		//ha figyeljuk a floodolast
		if ($act == 'addmsg' && $settings['flood'] == 1) {
			$query = "
				SELECT ((TO_DAYS(add_date)*24*60*60)+TIME_TO_SEC(add_date)) AS date_last, ((TO_DAYS(NOW())*24*60*60)+TIME_TO_SEC(NOW())) AS date_now 
				FROM iShark_Forum_Messages
				WHERE ip = '$ip' 
				ORDER BY add_date DESC 
			";
			$mdb2->setLimit(1);
			$result = $mdb2->query($query);
			if ($result->numRows() != 0) {
				while ($row = $result->fetchRow())
				{
					if ($row['date_last'] + $settings['flood_time'] > $row['date_now']) {
						$is_flood = 1;
						$acttpl = 'error';
						$tpl->assign('errormsg', $strErrorForumFlood);
						return;
					} else {
						$is_flood = 0;
					}
				}
			}
		}

		require_once 'HTML/QuickForm.php';
		require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		//elinditjuk a form-ot
		$form_forum =& new HTML_QuickForm('frm_forum', 'post', 'index.php?'.$self);

		//a szukseges szoveget jelzo resz beallitasa
		$form_forum->setRequiredNote($strForumRequiredNote);

		//form-hoz elemek hozzadasa
		$form_forum->addElement('header', 'headername', $strForumAddMessageHeader);
		$form_forum->addElement('hidden', 'parent', $parent);
		$form_forum->addElement('hidden', 'act',        $act);
		$form_forum->addElement('hidden', 'tid',        $tid);
		$form_forum->addElement('hidden', 'msgid',      $msgid);
		if (!isset($_SESSION['user_id'])) {		
			$form_forum->addElement('hidden', 'add_user_id', '0');
		} else {
			$form_forum->addElement('hidden', 'add_user_id', $_SESSION['user_id']);
		}
		$form_forum->addElement('text', 'subject', $strForumMessageSubject, 'class="wholeline"');
		$_message =& $form_forum->addElement('textarea', 'message', $strForumMessage, 'id="forum_text" onfocus="bbcode_help();"');
		$_message->setCols(35);
		$_message->setRows(10);

		// Módosítás esetén az alapértékek beállítása
		$defaults = array();
		if ($act == 'modmsg') {
			$defaults = array(
				'message' => $message_data['message'],
				'subject' => $message_data['subject'],
				'embed'   => $message_data['embed']
			);
		} else {
			if (isset($_REQUEST['re_id'])) {
				$reid = (int) $_REQUEST['re_id'];
				$query = "
					SELECT M.message AS message,
						   M.subject AS subject,
					(CASE M.add_user_id WHEN '0' THEN '$strForumGuest' ELSE U.name END) AS user_name
					FROM iShark_Forum_Messages M 
					LEFT JOIN ".DB_USERS.".iShark_Users U on M.add_user_id=U.user_id
					WHERE topic_id='$tid' AND message_id='$reid'
				";
				$result =& $mdb2->query($query);
				if ($row = $result->fetchRow()) {
					$defaults = array(
						'subject' => preg_match('|^Re: |',$row['subject']) ? $row['subject'] : 'Re: '.$row['subject'],
						'message' => '[quote][b]'.$row['user_name'].' '.$strForumMessageWrittenBy."[/b]\n".$row['message'].'[/quote]'
					);
				}
			}
			$tpl->assign('forum_pics', 1);
			$forum_pics = array();
    		for ($i=0; $i<3; $i++) {
	       	   $forum_pics[$i] =& $form_forum->addElement('file', "msgpic$i", ($i+1).". kép csatolása");
	       	   $form_forum->addRule('msgpic'.$i, "Túl nagy csatolt kép", "maxfilesize", 1024 * 1024); // 1MB
    		}
		}
		$form_forum->addElement('textarea', 'embed', "Embed videó csatolása", 'cols="30" rows="3"');
		
		$form_forum->setDefaults($defaults);
		$form_forum->addElement('submit', 'submit', $strForumSubmit, 'class="submit"');
		$form_forum->addElement('reset', 'reset', $strForumReset, 'class="submit"');
		//ha a captcha engedelyezve van

		if ($act == 'addmsg' && $settings['captcha'] == 1 && !isset($_SESSION['user_id'])) {
			require_once 'Text/CAPTCHA.php';

			$form_forum->addElement('text', 'forum_recaptcha', $strForumRecaptcha);
			$form_forum->addRule('forum_recaptcha', $strForumErrorRecaptcha1, 'required');
			if ($form_forum->isSubmitted() && $form_forum->getSubmitValue('forum_recaptcha') != $_SESSION['forum_phrase']) {
				$form_forum->setElementError('forum_recaptcha', $strForumErrorRecaptcha2);
			}

			$options = array(
				'font_size' => 18,
				'font_path' => $libs_dir.'/',
				'font_file' => 'arial.ttf'
			);

			// Generate a new Text_CAPTCHA object, Image driver
			$cf = Text_CAPTCHA::factory('Image');
			$retvalf = $cf->init(200, 60, null, $options);

			// Get CAPTCHA secret passphrase
			$_SESSION['forum_phrase'] = $cf->getPhrase();

			// Get CAPTCHA image (as PNG)
			$pngf = $cf->getCAPTCHAAsJpeg();

			if (!function_exists('file_put_contents')) {
				function file_put_contents($filename, $contentf) {
					if (!($filef = fopen('files/'.$filename, 'w'))) {
						return false;
					}
					$nf = fwrite($filef, $contentf);
					fclose($filef);
					return $nf ? $nf : false;
				}
			}
			file_put_contents('forum_'.md5(session_id()) . '.jpg', $pngf);
			$tpl->assign('forum_captcha', 'files/forum_'.md5(session_id()).'.jpg?'.time());
		}

		$form_forum->applyFilter('__ALL__', 'trim');

		$form_forum->addRule('message', $strForumErrorMessage, 'required');
		
		// Feltöltött képek ellenõrzése
		$pic_values = array();
        if (isset($_SESSION['user_id']) && $act == "addmsg" && $form_forum->isSubmitted()) {
            for ($i = 0; $i<3; $i++) {
                if ($forum_pics[$i]->isUploadedFile()) {
                    $pic_values[$i] = $forum_pics[$i]->getValue();
                    if (!($pic_values[$i]["image"] = @GetImageSize($pic_values[$i]["tmp_name"])) || !in_array($pic_values[$i]["image"][2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
                        $form_pics[$i]->setElementError("msgpic$i", "Nem kép, vagy hibás formátum. Megengedett formátumok: GIF, JPEG vagy PNG");
                        @unlink($pic_values[$i]["tmp_name"]);
                    }
                }
            }
        }
        
        // adatok mentése
		if ($form_forum->validate()) {
			$form_forum->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$message = $form_forum->getSubmitValue('message');
			$subject = $form_forum->getSubmitValue('subject');
			$embed   = $form_forum->getSubmitValue('embed');
			
			//ha be van allitva a user_id session, akkor csak azt irjuk be a tablaba
			if (isset($_SESSION['user_id'])) {
				$uid   = $_SESSION['user_id'];
			} else {
				$uid   = "0";
			}

			if ($act == 'addmsg') {
				$message_id = $mdb2->extended->getBeforeID('iShark_Forum_Messages', 'message_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Forum_Messages 
					(message_id, topic_id, subject, message, add_user_id, add_date, ip, is_blocked, embed) 
					VALUES 
					($message_id, $tid, '$subject', '$message', $uid, NOW(), '$ip', '$topic_data[default_blocked]', '$embed')
				";
    			$mdb2->exec($query);
    			$message_id = $mdb2->extended->getAfterID($message_id, 'iShark_Forum_Messages', 'message_id');
    			if (!empty($pic_values)) {
    			    foreach ($pic_values as $key => $pic) {
                        $name     = $mdb2->quote($pic['name']);
                        $realname = time().preg_replace('/[^a-z\d_\.]/i', '_', $pic['name']);
                        $forum_pics[$key]->moveUploadedFile($forum_files_dir, $realname);
                        $width    = $pic['image']['0'];
                        $height   = $pic['image']['1'];
    			        $picture_id = $mdb2->extended->getBeforeID('iShark_Forum_Pictures', 'picture_id', TRUE, TRUE);
    			        $mdb2->exec("INSERT INTO iShark_Forum_Pictures (picture_id, message_id, name, realname, width, height)
    		      	        VALUES ($picture_id, $message_id, $name, '$realname', $width, $height)");
    			    }
    			}
			} else {
				$query = "
					UPDATE iShark_Forum_Messages 
					SET subject = '$subject', 
						message = '$message' 
					WHERE topic_id = $tid AND message_id = $msgid
				";
    			$mdb2->exec($query);
			}

			if ($act == 'addmsg') { 
				$query = "
					UPDATE iShark_Forum_Topics 
					SET last_message_date = NOW(), 
						last_user_id      = $uid 
					WHERE topic_id = $tid
				";
				$mdb2->exec($query);
			} else {
				logger('modmsg', $menu_id);
			}

			//ha csak adminisztratori engedellyel lehet irni es kell errol mail-t kuldeni
			if ($act == 'addmsg' && $settings['captcha'] == 1) {
				unset($_SESSION['forum_phrase']);
				@unlink('files/forum_'.md5(session_id()).'.jpg');
			}
			$urlending = ($topic_data['default_blocked'] == '1' && $act=='addmsg' ? '&page=thankyou' : '');
			header("Location: index.php?$self&parent=$parent&tid=$tid".$urlending);
			exit;
		}

		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_forum->accept($renderer);

		$tpl->assign('form_forum', $renderer->toArray());


		$lang_forum = array(
			"strForumNoJavascript" => $strForumNoJavascript,
			"strForumBack" => $strForumBack
		);
		$tpl->assign('lang_forum', $lang_forum);
		// BBCode javascript hozzáadása a fejléchez
		$javascripts[] = 'bbcode';
		//megadjuk a tpl file nevet, amit atadunk az index.php-nek
		$acttpl = "forum_addmsg";
		
	} //uj bejegyzes vege

	/**
	 * Üzenetek listája 
	 */
	 
	if ($_REQUEST['act'] == 'lstmsg') {
	    $forum_breadcrumb->add($topic_data["topic_name"], "#");
	    $act = $_REQUEST["act"];
		if (!isset($_REQUEST['tid'])) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
		if (!is_listright()) {
			$acttpl = 'error';
			$tpl->assign('errormsg', 'A téma nem aktív! Nincs joga a megtekintéséhez!');
			return;
		}

		if (isset($_GET['page']) && $_GET['page'] == 'thankyou') {
			$acttpl = 'forum_thankyou';
			$lang_forum = array (
				"strForumThankYou" => $strForumThankYou,	
				"strForumThankYou2" => $strForumThankYou2,
				"strForumBack" => $strForumBack
			);
			$tpl->assign('lang_forum', $lang_forum);
			return;
		}

		// Értékelés ha van hozzá jog.
		
		if ($rate_right) {
		    $tpl->assign("rate_right", 1);
		    
    		require_once 'HTML/QuickForm.php';
	       	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

		    //elinditjuk a form-ot
		    $form_forum =& new HTML_QuickForm('frm_forum', 'post', 'index.php?'.$self);

		    //a szukseges szoveget jelzo resz beallitasa
		    $form_forum->setRequiredNote($strForumRequiredNote);
		    

            //form-hoz elemek hozzadasa
            $form_forum->addElement('header', 'headername', $strForumAddMessageHeader);
            $form_forum->addElement('hidden', 'parent',     $parent);
            $form_forum->addElement('hidden', 'act',        $act);
            $form_forum->addElement('hidden', 'tid',        $tid);
            $rateButtons = array();
            for ($i = 1; $i<=5; $i++) {
                $rateButtons[$i]  =& HTML_QuickForm::createElement("radio", "", $i);
            }
            $form_forum->addGroup($rateButtons);
            $form_forum->addElement("submit", "sbmt", "Értékelés");
            
            $renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
	       	$form_forum->accept($renderer);
            $tpl->assign('form_forum', $renderer->toArray());
		}

		$block_right = check_perm('block', $menu_id, 1, 'forum', 'index');
		$whereClause = '';
		if (!$block_right) {
			$whereClause = "AND is_blocked='0'";
		}
		
		$query = "
			SELECT 
				M.topic_id as tid, 
				M.message_id as mid,
				M.embed as embed,
				M.subject as subject, 
				M.message as message,
				M.re_message_id as message_id,
				M.add_date as add_date,
				M.add_user_id as user_id,
				(CASE M.add_user_id 
					WHEN '0' THEN '$strForumGuest'
					ELSE U.name
				END) as user_name,
				(CASE U.is_public_mail 
					WHEN '1' THEN U.email
					ELSE ''
				END) as user_email,
				U.sign as user_sign,
				M.is_blocked as is_blocked
			FROM iShark_Forum_Messages M
			LEFT JOIN ".DB_USERS.".iShark_Users U ON M.add_user_id=U.user_id
			WHERE M.topic_id='$tid' $whereClause
			ORDER BY M.add_date DESC
		";
		
		include_once 'Pager/Pager.php';
		$pd = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);
		
		// Képek lekérdezése
		foreach ($pd["data"] as $key=>$value) {
   		    $pd["data"][$key]["pics"] = array();
		    $res =& $mdb2->query("SELECT * FROM iShark_Forum_Pictures WHERE message_id=".$pd["data"][$key]["mid"]);
		    while ($row = $res->fetchRow()) {
		        $pd["data"][$key]["pics"][] = $row;
		    }
		}
		/*print '<pre>';
		print_r($pd['data']);
		print '</pre>'; */
		$tpl->assign("forum_files_dir", rtrim($forum_files_dir, '/'));
		
		
		$tpl->assign('pd_forum', $pd['data']);
		$tpl->assign('pl_forum', $pd['links']);
		
		$lang  = array (
			'strForumHeader'				=> $strForumHeader,
			'strForumNewMessage'			=> $strForumNewMessage,
			'strForumTopicName'				=> $strForumTopicName,
			'strForumTopicMessageCount'		=> $strForumTopicMessageCount,
			'strForumMessageDeleteConfirm'	=> $strForumMessageDeleteConfirm,
			'strForumMessageBlocked'		=> $strForumMessageBlocked,
			'strForumMessageBlock'			=> $strForumMessageBlock,
			'strForumMessageUnBlock'		=> $strForumMessageUnBlock,
			'strForumMessageModify'			=> $strForumMessageModify,
			'strForumMessageDelete'			=> $strForumMessageDelete,
			'strForumMessageEmptyList'		=> $strForumMessageEmptyList,
			'strForumMessageReply'			=> $strForumMessageReply,
			'strForumBack'					=> $strForumBack
		);
		
		error_reporting(E_ALL);
		include_once 'PEAR.php';
		include_once 'HTML/BBCodeParser.php';

		$config = parse_ini_file('includes/BBCodeParser.ini', true);
		$options = &PEAR::getStaticProperty('HTML_BBCodeParser', '_options');
		$options = $config['HTML_BBCodeParser'];
		unset($options);

		$parser =& new HTML_BBCodeParser();
		
		// Cenzúrázott kifejezések lekérése
		$censor_regexp = array();
		$censor_changed = array();

		$query = "
			SELECT word 
			FROM iShark_Forum_Censor
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$censor_regexp[] = '/'.preg_quote($row['word'],'/').'/i';
			$censor_changed[]  = str_repeat('*', strlen($row['word']));
		}
	
		// BBCode parseoló függvény definiálása smartyhoz
		$tpl->register_function('bbcode', 'get_bbcode');
		$tpl->register_function('censor', 'censor');
	
		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('del_right', check_perm('delmsg', $menu_id, 1, 'forum', 'index'));
		$tpl->assign('mod_right', check_perm('modmsg', $menu_id, 1, 'forum', 'index'));
		$tpl->assign('block_right', $block_right);
		$tpl->assign('is_addright', $topic_data['write_everybody'] == '1' || isset($_SESSION['user_id']));
		
		$tpl->assign('topic_name', $topic_data['topic_name']);
		$tpl->assign('topic_subject', $topic_data['topic_subject']);

		$tpl->assign('total', $pd['totalItems']);
		$tpl->assign('lang_forum', $lang);

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'forum_message_list';
	}

	/**
	 * Új topic nyitása 
	 */
	if ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'mod') {
		$act = $_REQUEST['act'];
		if (($act == 'add' && !is_addright()) || 
			($act == 'mod' && !check_perm('mod', $menu_id, 1, 'forum', 'index'))) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
		include_once 'HTML/QuickForm.php';
		include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		
		$form_forum =& new HTML_QuickForm('newtopic', 'post', 'index.php?'.$self);
		$form_forum->setRequiredNote($strForumRequiredNote);
		if ($act == 'add') {
			$form_forum->addElement('header', 'headername', $strForumNewTopic);
		} else {
			$form_forum->addElement('header', 'headername', $strForumModTopic);
		}
		$form_forum->addElement('hidden', 'act', $act);
		$form_forum->addElement('hidden', 'tid', $tid);
		$form_forum->addElement('hidden', 'parent', $parent);
		$form_forum->addElement('text', 'topic_name', $strForumTopicName, array('maxlength'=>255, 'class'=>'wholeline'));
		$form_forum->addElement('textarea', 'topic_subject', $strForumTopicSubject);

		$reszletes = check_perm('pub', $menu_id, 1, 'forum', 'index');

		$defaults = array();

		if ($act == 'mod') {
			$defaults = array(
				'topic_name' => $topic_data['topic_name'],
				'topic_subject' => $topic_data['topic_subject']
			);
		}

		if ($reszletes) {
			$w =& $form_forum->addElement('checkbox', 'write_everybody', null, $strForumTopicsPublicWrite);
			$r =& $form_forum->addElement('checkbox', 'read_everybody', null, $strForumTopicsPublicRead);
			$s =& $form_forum->addElement('checkbox', 'is_sticky', null, $strForumTopicAlwaysOnTop);
			$da =& $form_forum->addElement('checkbox', 'default_blocked', null, $strForumTopicDefaultBlocked);
			$defaults['read_everybody'] = 'checked';
			if ($act == 'mod') {
				$defaults['read_everybody'] = $topic_data['read_everybody'] == '1' ? 'checked' : '';
				$defaults['write_everybody'] = $topic_data['write_everybody'] == '1' ? 'checked' : '';
				$defaults['is_sticky'] = $topic_data['is_sticky'] == '1' ? 'checked' : '';
				$defaults['default_blocked'] = $topic_data['default_blocked'] == '1' ? 'checked' : '';
			}
		}
		$form_forum->setDefaults($defaults);
		$form_forum->addRule('topic_name', $strForumTopicNameError, 'required');
		$form_forum->addRule('topic_subject', $strForumTopicSubjectError, 'required');

		if ($form_forum->validate()) {
			$form_forum->applyFilter('__ALL__', array(&$mdb2, 'escape'));
			$topic_name    = $form_forum->getSubmitValue('topic_name');
			$topic_subject = $form_forum->getSubmitValue('topic_subject');
			
			$append = '';
			if ($reszletes) {
				$write_everybody = $w->getChecked() ? '1' : '0';
				$read_everybody = $r->getChecked() ? '1' : '0';
				$is_sticky = $s->getChecked() ? '1' : '0';
				$def_b = $da->getChecked() ? '1' : '0';
				$append = ", write_everybody='$write_everybody', read_everybody='$read_everybody', is_sticky='$is_sticky', default_blocked='$def_b'";
			}
			if ($act == 'add') {
				$topic_id = $mdb2->extended->getBeforeID('iShark_Forum_Topics', 'topic_id', TRUE, TRUE);
				$query = "
					INSERT INTO iShark_Forum_Topics
					SET topic_id          = $topic_id, 
						topic_name        = '$topic_name', 
						topic_subject     = '$topic_subject', 
						add_user_id       = '".$_SESSION['user_id']."', 
						add_date          = NOW(), 
						last_message_date = NOW(), 
						is_active         = '1',
						parent_id         = $parent 
						$append
					";
			} else {
				$query = "
					UPDATE iShark_Forum_Topics
					SET	topic_name    = '$topic_name', 
						topic_subject = '$topic_subject' 
						$append
					WHERE topic_id = $tid
				";
			}
			$mdb2->exec($query);
			header("Location: index.php?$self&parent=$parent");
			exit;
		}
		
		$form_forum->addElement('submit', 'submit', $strForumSubmit);
		$form_forum->addElement('reset', 'reset', $strForumReset);
		
		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_forum->accept($renderer);

		$tpl->assign('form_forum', $renderer->toArray());

		$acttpl = 'forum_topic';
	}

	/**
	 * ha torlunk egy topikot, vagy forumot
	 */
	 
	if ($_REQUEST['act'] == "del" || $_REQUEST['act'] == 'fdel') {
		if (!check_perm($_REQUEST['act'], $menu_id, 1, 'forum', 'index') || !isset($_REQUEST['tid'])) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
        $operator = $_REQUEST["act"] == "del" ? '<>' : '=';
		$query = "
			UPDATE iShark_Forum_Topics 
			SET is_deleted = '1' 
			WHERE topic_id = $tid AND parent_id $operator 0
		";
		$mdb2->exec($query);

		//loggolas
		logger('del', $menu_id);

		header('Location: index.php?'.$self.'&parent='.$parent);
		exit;
	} //torles vege


	/* Új fórum hozzáadása */
	if ($_REQUEST["act"] == "fadd") {
	    if (!check_perm('fadd', $menu_id, 1, 'forum', 'index')) {
	        $acttpl = 'error';
	        $tpl->assign('errormsg', $strForumErrorPermissionDenied);
	        return;
	    }
	    
	    $act = $_REQUEST["act"];
	    
	    include_once 'HTML/QuickForm.php';
		include_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
		
		$form_forum =& new HTML_QuickForm('newforum', 'post', 'index.php?'.$self);
		$form_forum->setRequiredNote($strForumRequiredNote);
		$form_forum->addElement('header', 'headername', $strForumAddForum);
		$form_forum->addElement('hidden', 'act', $act);
		$form_forum->addElement('text', 'topic_name', $strForumTopicName, array('maxlength'=>255, 'class'=>'wholeline'));
		$form_forum->addElement('textarea', 'topic_subject', $strForumTopicSubject);

		$reszletes = check_perm('pub', $menu_id, 1, 'forum', 'index');

		$form_forum->addRule('topic_name', $strForumTopicNameError, 'required');
		$form_forum->addRule('topic_subject', $strForumTopicSubjectError, 'required');

		if ($form_forum->validate()) {
			$form_forum->applyFilter('__ALL__', array(&$mdb2, 'escape'));
			$topic_name    = $form_forum->getSubmitValue('topic_name');
			$topic_subject = $form_forum->getSubmitValue('topic_subject');
			
			$topic_id = $mdb2->extended->getBeforeID('iShark_Forum_Topics', 'topic_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Forum_Topics
				(topic_id, topic_name, topic_subject, add_user_id, add_date, last_message_date, is_active)
				VALUES
				($topic_id, '$topic_name', '$topic_subject', $_SESSION[user_id], NOW(), NOW(), 1)
			";
			
			$mdb2->exec($query);
			header("Location: index.php?$self");
			exit;
		}
		
		$form_forum->addElement('submit', 'submit', $strForumSubmit);
		$form_forum->addElement('reset', 'reset', $strForumReset);
		
		$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
		$form_forum->accept($renderer);

		$tpl->assign('form_forum', $renderer->toArray());
		$acttpl = "forum_forum";
	    
	}
	/**
	 * Topic aktiválás 
	 */
	 
	if ($_REQUEST['act'] == 'act') {
		if (!check_perm('act', $menu_id, 1, 'forum', 'index') || !isset($_REQUEST['tid'])) {
			$acttpl = 'error';
			$tpl->assign('errormsg', $strForumErrorPermissionDenied);
			return;
		}
		include_once 'includes/function.check.php';	
		check_active('iShark_Forum_Topics', 'topic_id', $tid);
		logger('act', $menu_id);
		header("Location: index.php?$self&parent=$parent");
		exit;
	}
	
	
}

/**
 * ha nincs semmilyen muvelet, akkor a listat mutatjuk
 */
else {
	if (check_perm('lst', $menu_id, 0, 'forum', 'index') === true) {
		$act_right = $parent>0 && check_perm('act', $menu_id, 1, 'forum', 'index');
		$del_right = check_perm($parent>0 ? 'del' : 'fdel', $menu_id, 1, 'forum', 'index');
	    
		$whereClause="AND FT.is_active='1' ";
		if (!isset($_SESSION['user_id'])) {
			$whereClause .= "AND FT.read_everybody='1'";
		}
		if (isset($_SESSION['user_id']) && $act_right) {
			$whereClause = "";
		}
		$kedvencekJoin = "";
		$kedvencekSelect = "";
		if (isset($_SESSION['user_id'])) {
		    $kedvencekJoin = "LEFT JOIN iShark_Forum_Bookmarks BM ON BM.user_id=$_SESSION[user_id] AND BM.topic_id=FT.topic_id";
		    $kedvencekSelect = ", IF(BM.topic_id IS NULL, 0, 1) AS bookmarked";
		}
		$query = "
			SELECT 
				FT.topic_id as tid,
				FT.topic_name as topic_name,
				FT.topic_subject as topic_subject,
				FT.add_date as topic_add_date,
				FT.is_sticky as is_sticky,
				FT.count_visited as count_visited,
				FT.is_active as is_active,
				(CASE FT.last_user_id 
					WHEN NULL THEN '-'
					WHEN '0' THEN '$strForumGuest'
					ELSE U1.name
				END) as last_user_name,
				FT.last_message_date as last_message_date,
				U2.name as add_user_name
				$kedvencekSelect
			FROM iShark_Forum_Topics FT
			LEFT JOIN ".DB_USERS.".iShark_Users U1 ON U1.user_id=FT.last_user_id
			LEFT JOIN ".DB_USERS.".iShark_Users U2 ON U2.user_id=FT.add_user_id
			$kedvencekJoin
			WHERE FT.is_deleted='0' AND FT.parent_id = '$parent' $whereClause
			ORDER BY is_sticky DESC, last_message_date DESC
		";
		include_once 'Pager/Pager.php';
		$pd = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);
		
		$tpl->assign('pd_forum', $pd['data']);
		$tpl->assign('pl_forum', $pd['links']);
		
		$tpl->assign('is_addright', is_addright());
		$tpl->assign('add_forum_right', isset($_SESSION["user_id"]) && check_perm("fadd", $menu_id, 1, "forum", "index"));
	
		$lang  = array (
			'strForumHeader' => $strForumHeader,
			'strForumNewTopic' => $strForumNewTopic,
			'strForumTopicName' => $parent == 0 ? "Fórum" : $strForumTopicName,
			'strForumTopicOwner' => $strForumTopicOwner,
			'strForumTopicLastMessage' => $strForumTopicLastMessage,
			'strForumTopicActions' => $strForumTopicActions,
			'strForumTopicEmptyList' => $strForumTopicEmptyList,
			'strForumTopicActivate' => $strForumTopicActivate,
			'strForumTopicModify' => $strForumTopicModify,
			'strForumTopicDelete' => $strForumTopicDelete,
			'strForumTopicDeleteConfirm' => $strForumTopicDeleteConfirm,
			'strForumTotalItems' => $parent == 0 ? "Összes fórum" : $strForumTotalItems,
			'strForumTopicMessageCount' => $strForumTopicMessageCount,
			'strForumTopicNumViews' => $strForumTopicNumViews,
			'strForumTopicActive' => $strForumTopicActive,
			'strForumTopicInactive' => $strForumTopicInactive,
			'strForumCensor'	=> $strForumCensor,
			'strForumTopicBookmark' => "Kedvencekhez adás",
			'strForumTopicUnBookmark' => "Törlés a kedvencek közül",
			'strForumActiveUsers' => $strForumActiveUsers,
			'strForumAddForum' => $strForumAddForum,
		);
		//atadjuk a smarty-nak a kiirando cuccokat
		$tpl->assign('del_right', $del_right);
		$tpl->assign('act_right', $act_right);
		$tpl->assign('mod_right', ($parent > 0 && check_perm('mod', $menu_id, 1, 'forum', 'index')));
		$tpl->assign('censor_right', check_perm('censor', $menu_id, 1, 'forum', 'index'));

		$tpl->assign('total', $pd['totalItems']);
		$tpl->assign('lang_forum', $lang);
		
		$tpl->register_function('num_messages', 'get_num_messages');

		//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
		$acttpl = 'forum_topic_list';
	}
}


$tpl->assign("forum_breadcrumb", $forum_breadcrumb->getArray());


/**
 * censor - Cenzúra függvény 
 * 
 * @param mixed $params 
 * @param mixed $smarty 
 * @access public
 * @return void
 */
function censor($params, &$smarty)
{
	global $censor_regexp, $censor_changed;
	return htmlspecialchars(preg_replace($censor_regexp, $censor_changed, $params['text']));
}

/**
 * is_addright 
 * 
 * @access public
 * @return void
 */
function is_addright() 
{
	global $settings,$menu_id;
	$is_addright = FALSE;
	if (isset($_SESSION['user_id'])) {
		if ($settings['admin_addtopic'] == '1') {
			$is_addright = check_perm('add', $menu_id, 1, 'forum', 'index');
		} else {
			$is_addright = TRUE;
		}
	}
	return $is_addright;
}

/**
 * get_num_messages 
 * 
 * @param mixed $params 
 * @param mixed $smarty 
 * @access public
 * @return void
 */
function get_num_messages($params, &$smarty) 
{
	global $mdb2;
	if (isset($params['tid'])) {
		$query = "SELECT COUNT(*) as cnt FROM iShark_Forum_Messages WHERE topic_id=$params[tid] AND is_blocked='0'";
		$result =& $mdb2->query($query);
		if ($sor = $result->fetchRow()) {
			return $sor['cnt'];
		}
	}
	return 0;
}

function get_bbcode($params, &$smarty)
{
	global $parser, $censor_regexp, $censor_changed;
	if (isset($params['text'])) {
		$text = preg_replace($censor_regexp, $censor_changed, $params['text']);
		$parser->setText(nl2br(htmlspecialchars($text)));
		$parser->parse();
		$parsed = $parser->getParsed(); 
		return $parsed; 
	} 
	return '';
}

function is_listright() 
{
	global $topic_data;
	if (!isset($_SESSION['user_id'])) {
		return ($topic_data['read_everybody'] == '1' && $topic_data['is_deleted'] == '0' && $topic_data['is_active'] == '1');
	}
	return ($topic_data['is_deleted'] == '0' && $topic_data['is_active'] == '1');
}


function makeBreadcrumb($parent, &$bc) {
    global $mdb2, $self;
    
    while ($parent != 0) {
        $result =& $mdb2->query("SELECT * FROM iShark_Forum_Topics WHERE topic_id=$parent");
        $row = $result->fetchRow();
        $bc->insertBefore($row["topic_name"], "index.php?$self&amp;parent=${parent}");
        $parent = $row["parent_id"];
    }
}


?>
