<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "menus";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);
$tpl->assign('title_module', $title_module);
$tpl->assign('self',         $module_name);

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act  = array('add', 'mod', 'del', 'lst', 'ord', 'act');

if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}

// Menu ful beallitasok
$menuType  = 'index';  // Alapertelmezett menutipus
$menuTypes = array('index', 'admin');
if (isset($_REQUEST['menutype']) && in_array($_REQUEST['menutype'], $menuTypes)) {
	$menuType = $_REQUEST['menutype'];
}
$tpl->assign('menuType', $menuType);

//megnezzuk, hogy az azonosito alapjan milyen menupontot akar lekerdezni
$admin_menu = 0;
if (isset($_REQUEST['m_id']) && is_numeric($_REQUEST['m_id'])) {
	$m_id = intval($_REQUEST['m_id']);

	$query = "
		SELECT type 
		FROM iShark_Menus 
		WHERE menu_id = $m_id
	";
	$result =& $mdb2->query($query);
	if ($result->numRows() > 0) {
		$row = $result->fetchRow();

		if ($row['type'] == "admin") {
			$admin_menu = 1;
		}
	}
}

//jogosultsag ellenorzes
if (!check_perm($act, NULL, 1, $module_name) || ($admin_menu == 1 && $is_admin == 0)) {
    $site_errors[] = array('text' => $locale->get('error_no_permission'), 'link' => 'javascript:history.back(-1)');
	return;
}

/**
 * a hozzadas vagy modositas reszhez tartozo quickform kozos beallitasa
 */
if ($act == "add" || $act == "mod") {
	$javascripts[] = "javascripts";

	if (isset($_REQUEST['mid']) && is_numeric($_REQUEST['mid'])) {
		$mid = intval($_REQUEST['mid']);
	} else {
		$mid = 0;
	}

	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/jscalendar.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
	require_once $include_dir.'/function.check.php';

	$titles = array('add' => $locale->get('title_add'), 'mod' => $locale->get('title_mod'));

	$form =& new HTML_QuickForm('frm_menus', 'post', 'admin.php?p='.$module_name);

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'menus',    $locale->get('form_header'));
	$form->addElement('hidden', 'menutype', $menuType);
	$form->addElement('hidden', 'mid',      $mid);

	//szulo menu kiirasa
	if (isset($_REQUEST['par'])) {
		$par = intval($_REQUEST['par']);

		$query = "
			SELECT menu_name 
			FROM iShark_Menus 
			WHERE menu_id = ".$_REQUEST['par']."
		";
		$result = $mdb2->query($query);
		if ($result->numRows() > 0) {
			$row = $result->fetchRow();
			$parent = $row['menu_name'];
		} else {
			$parent = $locale->get('form_no_parent');
		}
	} else {
		$parent = $locale->get('form_no_parent');
	}
	$form->addElement('static', 'parent', $locale->get('field_parent'), $parent);

	//vedett menupont
	if ($menuType == 'index') {
		$protected = array();
		$protected[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
		$protected[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'),  '0');
		$form->addGroup($protected, 'protected', $locale->get('field_protected'));
	} else {
		$form->addElement('hidden', 'protected', '1');
	}

	$blank_link = array();
	$blank_link[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_yes'), '1');
	$blank_link[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('form_no'), '0');
	$form->addGroup($blank_link, 'open_in_blank', $locale->get('open_in_blank'));

	//ha tobb nyelvu az oldal, akkor kilistazzuk a nyelveket
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		include_once $include_dir.'/functions.php';
		$form->addElement('select', 'languages', $locale->get('field_lang'), $locale->getLocales());
	}

	//menu neve
	$form->addElement('text', 'name', $locale->get('field_name'));
	$form->addElement('select', 'menu_color', 'Menu színe', array(
		'' => '--',
		'kek' => 'Kék',
		'narancs' => 'Narancs',
		'rozsaszin' => 'Rózsaszín',
		'zold' => 'Zöld',
		'narancs2' => 'Narancs 2'
	));
    
    if ($menuType == 'index') {
        //hatterkep
        $file =& $form->addElement('file', 'bg_file', $locale->get('field_bgfile'));
    }

	//kirakunk egy ures option-t az elejere
	$empty_array = array('' => '');

	if ($menuType == 'index') {
		//poziciok lekerdezese
		$query = "
			SELECT mp.position_id AS pid, mp.position_name AS pname 
			FROM iShark_Menus_Positions mp 
			ORDER BY mp.position_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'position', $locale->get('field_position'), $result->fetchAll('', $rekey = true));

		//lekerdezzuk, hogy milyen modulokat lehet hozzaadni - fooldal
		$query = "
			SELECT m.module_id AS mid, m.module_name AS mname 
			FROM iShark_Modules m 
			WHERE m.is_active = 1 AND m.type = 'index'
			ORDER BY m.module_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'modules', $locale->get('field_modules'), $empty_array + $result->fetchAll('', $rekey = true));

		//lekerdezzuk, hogy milyen tartalmakat lehet hozzaadni (a hirek kivetelevel)
		$query = "
			SELECT c.content_id AS cid, SUBSTRING(c.title, 1, 50) AS ctitle 
			FROM iShark_Contents c 
			WHERE c.is_active = 1 AND type = 1 AND (c.timer_start = '0000-00-00 00:00:00' OR c.timer_start < NOW())
			ORDER BY c.title
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'contents', $locale->get('field_contents'), $empty_array + $result->fetchAll('', $rekey = true));
		
		//lekerdezzuk, hogy milyen galériákat lehet hozzaadni (képek)
		$query = "
			SELECT gallery_id, name 
			FROM iShark_Galleries 
			WHERE is_active = 1 AND type = 'p'
			ORDER BY name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'pic_gallery', $locale->get('field_pic_gallery'), $empty_array + $result->fetchAll('', $rekey = true));
		
		//lekerdezzuk, hogy milyen galériákat lehet hozzaadni (slideshow)
		$query = "
			SELECT gallery_id, name 
			FROM iShark_Galleries 
			WHERE is_active = 1 AND type = 's'
			ORDER BY name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'slideshow_gallery', $locale->get('field_slideshow_gallery'), $empty_array + $result->fetchAll('', $rekey = true));
		
		//lekerdezzuk, hogy milyen galériákat lehet hozzaadni (video)
		$query = "
			SELECT gallery_id, name 
			FROM iShark_Galleries 
			WHERE is_active = 1 AND type = 'v'
			ORDER BY name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'video_gallery', $locale->get('field_video_gallery'), $empty_array + $result->fetchAll('', $rekey = true));

		//kulso link
		$form->addElement('text', 'link', $locale->get('field_outerlink'), array('value' => 'http://'));
	}
	if ($menuType == 'admin' && $is_admin == 1) {
		$form->addElement('hidden', 'position', '1');
		//lekerdezzuk, hogy milyen modulokat lehet hozzaadni - adminoldal
		$query = "
			SELECT m.module_id AS mid, m.module_name AS mname 
			FROM iShark_Modules m 
			WHERE m.is_active = 1 AND m.type = 'admin' 
			ORDER BY m.module_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'modulesadm', $locale->get('field_adminmodules'), $empty_array + $result->fetchAll('', $rekey = true));
	}

	//ha engedelyezve van az idozites
	if ($menuType=='index' && isset($_SESSION['site_conttimer']) && $_SESSION['site_conttimer'] == 1) {
		$form->addGroup(
            array(
                HTML_QuickForm::createElement('text', 'timer_start', null, array('readonly' => '1', 'id' => 'timer_start')),
                HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
				HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_start\')"')
            ),
            'date_start', $locale->get('field_timerstart'), null, false
        );
		$form->addGroup(
            array(
                HTML_QuickForm::createElement('text', 'timer_end', null, array('readonly' => '1', 'id' => 'timer_end')),
                HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
				HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_end\')"')
            ),
            'date_end', $locale->get('field_timerend'), null, false
        );
	}
	
	//ha engedelyezve van a kategoriak hasznalata
	if ($menuType=='index' && isset($_SESSION['site_category']) && $_SESSION['site_category'] == 1) {
		//lekerdezzuk, hogy milyen kategóriákat lehet hozzaadni
		$query = "
			SELECT c.category_id AS cid, c.category_name AS cname 
			FROM iShark_Category c 
			WHERE c.is_active = 1 AND c.is_deleted = '0' 
			ORDER BY c.category_name
		";
		$result = $mdb2->query($query);
		$select =& $form->addElement('select', 'categs', $locale->get('field_category'), $empty_array + $result->fetchAll('', $rekey = true));
	}

	//csoport kivalasztasa
	$def_array = array('0' => $locale->get('form_groupdefault'));

	$query = "
		SELECT g.group_id AS gid, g.group_name AS gname 
		FROM iShark_Groups g 
		WHERE g.is_deleted = 0 AND g.group_id > ".$_SESSION['site_sys_prefgroup']."
		ORDER BY g.group_name
	";
	$result = $mdb2->query($query);
	$select_group =& $form->addElement('select', 'group', $locale->get('form_rightgroups'), $def_array + $result->fetchAll('', $rekey = true));
	$select_group->setSize(5);
	$select_group->setMultiple(true);

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//szurok beallitasa
	$form->applyFilter('__ALL__', 'trim');

	//szabalyok beallitasa
	//vedett menupont
	$form->addRule('protected', $locale->get('error_protected'), 'required');
	//nyelv kivalasztasa
	if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
		$form->addRule('languages', $locale->get('error_language'), 'required');
	}
	//menu neve
	$form->addRule('name', $locale->get('error_name'), 'required');
	//menu pozicioja
	$form->addRule('position', $locale->get('error_position'), 'required');
	//ha elkuldtuk a form-ot es az idozitoben valamit beallitottunk
	if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
		$form->addFormRule('check_timer');
	}
	//ha nem valasztott se modult, se tartalmat, se kulso linket, se kategóriát
	if ($menuType == 'index' && $form->getSubmitValue('modules') == ""  && $form->getSubmitValue('contents') == "" && $form->getSubmitValues('link') == "" && $form->getSubmitValues('categs') == "") {
		$form->addRule('modules',  $locale->get('error_modules'), 'required');
		$form->addRule('contents', $locale->get('error_modules'), 'required');
		$form->addRule('link',     $locale->get('error_modules'), 'required');
		$form->addRule('categs',   $locale->get('error_modules'), 'required');
	}
	if ($menuType == 'admin' && $form->getSubmitValue('modulesadm') == "" && $is_admin == 1) {
		$form->addRule('modulesadm', $locale->get('error_modules'), 'required');
	}
	//ha valasztott fooldali modult es adminisztracios modult es tartalmat is, akkor hiba (csak az egyiket lehet valasztani)
	$mod    = $form->getSubmitValue('modules');
	$modadm = $form->getSubmitValue('modulesadm');
	$con    = $form->getSubmitValue('contents');
	$gal    = $form->getSubmitValue('pic_gallery');
	$slide  = $form->getSubmitValue('slideshow_gallery');
	$video  = $form->getSubmitValue('video_gallery');
	$link   = $form->getSubmitValue('link');
	$categs = $form->getSubmitValue('categs');
	$tomb = array();
	if ($mod    != 0)  { $tomb[] = $mod; }
	if ($modadm != 0)  { $tomb[] = $modadm; }
	if ($con    != 0)  { $tomb[] = $con; }
	if (!empty($link) && $link != "http://") { $tomb[] = $link; }
	if ($categs    != 0)  { $tomb[] = $categs; }
	if (count($tomb) > 1) {
		$form->setElementError('modules',    $locale->get('error_modules2'));
		$form->setElementError('modulesadm', $locale->get('error_modules2'));
		$form->setElementError('contents',   $locale->get('error_modules2'));
		$form->setElementError('link',       $locale->get('error_modules2'));
		$form->setElementError('categs',     $locale->get('error_modules2'));
	}
	//csoport
	$form->addGroupRule('group', $locale->get('error_group'), 'required');

	/**
	 * ha uj menupontot adunk hozza
	 */
	if ($act == "add") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		//form-hoz elemek hozzaadasa - csak hozzaadasnal
		$form->addElement('hidden', 'act', 'add');

		//ha van parent, akkor almenu
		if (isset($_REQUEST['par']) && is_numeric($_REQUEST['par'])) {
			$form->addElement('hidden', 'par', $_REQUEST['par']);
			//ha letezik a pos parameter, akkor beallitjuk alapertelmezettnek
			//ez mondja meg, ha almenut csinalunk, hogy mi a menu pozicioja
			if (isset($_GET['pos']) && is_numeric($_GET['pos']))
			$form->setDefaults(array(
				'position' => intval($_GET['pos'])
				)
			);
		} else {
			$form->addElement('hidden', 'par', 0);
		}

		//beallitjuk az alapertelmezett ertekeket - csak hozzaadasnal
		$form->setDefaults(array(
			'protected'     => '0',
			'languages'     => $_SESSION['site_deflang'],
			'open_in_blank' => '0'
			)
		);
		$select_group->setSelected(0);

		//ellenorzes, vegso muveletek
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$name        = $form->getSubmitValue('name');
			$position    = intval($form->getSubmitValue('position'));
			$modulendx   = intval($form->getSubmitValue('modules'));
			$moduleadm   = intval($form->getSubmitValue('modulesadm'));
			$content     = intval($form->getSubmitValue('contents'));
			$gal         = intval($form->getSubmitValue('pic_gallery'));
			$slide       = intval($form->getSubmitValue('slideshow_gallery'));
			$video       = intval($form->getSubmitValue('video_gallery'));
			$link        = intval($form->getSubmitValue('link'));
			$category_id = intval($form->getSubmitValue('categs'));
			$parent      = intval($form->getSubmitValue('par'));
			$protected   = intval($form->getSubmitValue('protected'));
			$timer_start = $form->getSubmitValue('timer_start');
			$timer_end   = $form->getSubmitValue('timer_end');
			$open_in_new = $form->getSubmitValue('open_in_blank');
			$mid		 = $form->getSubmitValue('mid');
			$group       = $form->getSubmitValue('group');
			$menu_color      = $form->getSubmitValue('menu_color');

			//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
			if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
				$languages = $form->getSubmitValue('languages');
			} else {
				$languages = $_SESSION['site_deflang'];
			}

			//lekerdezzuk a legmagasabb sorszamokat
			$maxorder = 0;
			$query = "
				SELECT MAX(sortorder) AS sortorder 
				FROM iShark_Menus 
			";
			$result = $mdb2->query($query);
			while ($row = $result->fetchRow())
			{
				$maxorder = $row['sortorder'];
			}

			//kulso link ellenorzese
			if ($form->getSubmitValue('link') != "") {
				$link = check_link($form->getSubmitValue('link'));
			} else {
				$link = "";
			}

			//megnezzuk, hogy fooldali vagy adminoldali modult akarunk-e felvinni
			if ($modulendx != 0 || $moduleadm != 0) {
				if ($modulendx > 0) {
					$module = $modulendx;
					$type   = "index";
				}
				if ($moduleadm > 0) {
					$module = $moduleadm;
					$type   = "admin";
				}
			} else {
				$module = 0;
				$type   = "index";
			}
            if ($menuType == 'index') {
                if ($file->isUploadedFile()) {
                    $filevalues = $file->getValue();
                    $sdir = preg_replace('|/$|','', $_SESSION['site_cnt_picdir']).'/';
                    $filename = time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));
    
                    //kep atmeretezese
                    include_once 'includes/function.images.php';
                    //ha vezeto hirhez toltunk fel
                    if (is_array($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, 980, 320))) {
                        @chmod($sdir.$filename,0664);
                        @unlink($filevalues['tmp_name']);
                    }
                    if (!$pic) {
                        $form->setElementError('bg_file', $locale->get('error_picupload'));
                    }
                } else {
                    $pic = true;
                    $filename = '';
                }
            }
            if ($menuType == 'admin') {
                $pic = true;
                $filename = '';
            }
            if ($pic) {
                $menu_id = $mdb2->extended->getBeforeID('iShark_Menus', 'menu_id', TRUE, TRUE);
                $query = "
                    INSERT INTO iShark_Menus 
                    (menu_name, position_id, parent, sortorder, module_id, content_id, link, add_user_id, add_date, 
                    mod_user_id, mod_date, is_active, timer_start, timer_end, lang, type, is_protected, category_id, open_in_new_window, picture, menu_color, gallery_id, slideshow, video) 
                    VALUES 
                    ('".$name."', '$position', '$parent', '$maxorder'+1, '$module', '$content', '".$link."', '".$_SESSION['user_id']."', NOW(), 
                    '".$_SESSION['user_id']."', NOW(), '1', '$timer_start', '$timer_end', '$languages', '$type', '$protected', '$categs', '$open_in_new', '".$filename."', '".$menu_color."', '".$gal."', '".$slide."', '".$video."')
                ";
                $mdb2->exec($query);
                $last_menu_id = $mdb2->extended->getAfterID($menu_id, 'iShark_Menus', 'menu_id');
    
                //csoportokat beszurjuk a menucsoportokba
                if (!empty($group)) {
                    $is_everyone = 0;
    
                    foreach ($group as $row) {
                        if ($row > 0) {
                            $query = "
                                INSERT INTO iShark_Menus_Groups 
                                (menu_id, module_id, content_id, group_id)
                                VALUES 
                                ($last_menu_id, $module, $content, $row)
                            ";
                            $mdb2->exec($query);
                        }
    
                        if ($row == 0) {
                            $is_everyone = 1;
                        }
                    }
                
                    if ($is_everyone == 1) {
                        $query = "
                            DELETE FROM iShark_Menus_Groups 
                            WHERE menu_id = $menu_id
                        ";
                        $mdb2->exec($query);
                    }
                }
            }
			//loggolas
			logger($act, '', '');

			//"fagyasztjuk" a form-ot
			$form->freeze();

			//visszadobjuk a lista oldalra
			header('Location: admin.php?p='.$module_name.'&menutype='.$menuType.'&mid='.$mid);
			exit;
		}
	} //hozzaadas vege

	/**
	 * ha modositunk egy menupontot
	 */
	if ($act == "mod") {
		//breadcrumb
		$breadcrumb->add($titles[$act], '#');

		if (isset($_REQUEST['m_id']) && is_numeric($_REQUEST['m_id'])) {
			$m_id = intval($_REQUEST['m_id']);

			//form-hoz elemek hozzaadasa - csak modositasnal
			$form->addElement('hidden', 'act', 'mod');
			$form->addElement('hidden', 'm_id', $m_id);

			//lekerdezzuk a menu tablat, es az eredmenyt beallitjuk alapertelmezettnek
			$query = "
				SELECT m.menu_name AS mname, m.position_id AS mpos, m.module_id AS modid, m.content_id AS cid, m.category_id AS catid, 
					m.lang AS lang, m.is_protected AS protected, m.timer_start AS timer_start, m.timer_end AS timer_end, 
					m.link AS link, m.open_in_new_window AS open_in_blank, m.picture AS picture, m.menu_color, m.gallery_id, m.slideshow, m.video
				FROM iShark_Menus m 
				WHERE m.menu_id = $m_id
			";
			$result = $mdb2->query($query);
			if ($result->numRows() > 0) {
				while ($row = $result->fetchRow())
				{
					if ($row['timer_start'] == "0000-00-00 00:00:00") {
						$timer_start = "";
					} else {
						$timer_start = $row['timer_start'];
					}
					if ($row['timer_end'] == "0000-00-00 00:00:00") {
						$timer_end = "";
					} else {
						$timer_end = $row['timer_end'];
					}

					//beallitjuk az alapertelmezett ertekeket, csak modositasnal
					$form->setDefaults(array(
						'languages'     => $row['lang'],
						'name'          => $row['mname'],
						'position'      => $row['mpos'],
						'modules'       => $row['modid'],
						'modulesadm'    => $row['modid'],
						'contents'      => $row['cid'],
						'protected'     => $row['protected'],
						'timer_start'   => $timer_start,
						'timer_end'     => $timer_end,
						'link'          => 'http://'.$row['link'],
						'categs'        => $row['catid'],
						'menu_color'        => $row['menu_color'],
						'open_in_blank' => $row['open_in_blank'],
						'pic_gallery'   => $row['gallery_id'],
						'slideshow_gallery' => $row['slideshow'],
						'video_gallery'     => $row['video']
						)
					);
                    $content_picture = $filename = $row['picture'];
				}
				//lekerdezzuk a menu_groups tablat es beallitjuk alapertelmezettnek
	            $query_groups = "
					SELECT group_id 
					FROM iShark_Menus_Groups 
					WHERE menu_id = $m_id
				";
	            $result_groups =& $mdb2->query($query_groups);
	            if ($result_groups->numRows() > 0) {
	                $select_group->setSelected($result_groups->fetchCol());
	            } else {
	                $select_group->setSelected(0);
	            }
			} else {
				header('Location: admin.php?p='.$module_name);
				exit;
			}
            
            //modositas eseten jelenlegi kep kirajzolasa
            if (!empty($content_picture)) {
                $form->addElement('static', 'pic', $locale->get('field_currentpic'), '<img width="100" src="'.$_SESSION['site_cnt_picdir'].'/'.$content_picture.'" alt="'.$content_picture.'" />' );
                $delpic =& $form->addElement('checkbox', 'delpic', '', $locale->get('field_delpic'));
            }

			//ellenorzes, vegso muveletek
			if ($form->validate()) {
				$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

				$name        = $form->getSubmitValue('name');
				$position    = intval($form->getSubmitValue('position'));
				$modulendx   = intval($form->getSubmitValue('modules'));
				$moduleadm   = intval($form->getSubmitValue('modulesadm'));
				$contents    = intval($form->getSubmitValue('contents'));
				$protected   = intval($form->getSubmitValue('protected'));
				$category_id = intval($form->getSubmitValue('categs'));
				$timer_start = $form->getSubmitValue('timer_start');
				$timer_end   = $form->getSubmitValue('timer_end');
				$open_in_new = $form->getSubmitValue('open_in_blank');
				$mid		 = $form->getSubmitValue('mid');
				$group       = $form->getSubmitValue('group');
				$menu_color      = $form->getSubmitValue('menu_color');
				$gal         = intval($form->getSubmitValue('pic_gallery'));
				$slide       = intval($form->getSubmitValue('slideshow_gallery'));
				$video       = intval($form->getSubmitValue('video_gallery'));

				//ha tobbnyelvu az oldal, akkor a kivalasztott nyelvet adjuk hozza
				if (isset($_SESSION['site_multilang']) && $_SESSION['site_multilang'] == 1) {
					$languages = $form->getSubmitValue('languages');
				} else {
					$languages = $_SESSION['site_deflang'];
				}

				//megnezzuk, hogy fooldali vagy adminoldali modult akarunk-e felvinni
				if ($modulendx != 0 || $moduleadm != 0) {
					if ($modulendx > 0) {
						$module = $modulendx;
						$type   = "index";
					}
					if ($moduleadm > 0) {
						$module = $moduleadm;
						$type   = "admin";
					}
				} else {
					$module = 0;
					$type   = "index";
				}

				//kulso link ellenorzese
				if ($form->getSubmitValue('link') != "") {
					$link = check_link($form->getSubmitValue('link'));
				} else {
					$link = "";
				}
                
                //ha ki akarjuk torolni a regi kepet - de semmi mast nem csinalunk
                if (isset($delpic) && $delpic->getChecked()) {
                    $filename = "";
                    if (file_exists($_SESSION['site_cnt_picdir']."/".$content_picture)) {
                        @unlink($_SESSION['site_cnt_picdir']."/".$content_picture);
                    }
                }
                if ($menuType == 'index') {
                    if ($file->isUploadedFile()) {
                        $filevalues = $file->getValue();
                        $sdir = preg_replace('|/$|','', $_SESSION['site_cnt_picdir']).'/';
                        $filename = time().preg_replace('|[^\da-zA-Z_\.]|', '_', change_hunchar($filevalues['name']));
        
                        //kep atmeretezese
                        include_once 'includes/function.images.php';
                        //ha vezeto hirhez toltunk fel
                        if ($pic = img_resize($filevalues['tmp_name'], $sdir.$filename, 980, 320)) {
                            @chmod($sdir.$filename,0664);
                            @unlink($filevalues['tmp_name']);
                        }
                        $form->setElementError('bg_file', $locale->get('error_picupload'));
        
                        //regi kep torlese - ha volt
                        if ($content_picture != "") {
                            if (file_exists($_SESSION['site_cnt_picdir']."/".$content_picture)) {
                                @unlink($_SESSION['site_cnt_picdir']."/".$content_picture);
                            }
                        }
                    }
                }

				$query = "
					UPDATE iShark_Menus 
					SET menu_name = '".$name."', position_id = '$position', module_id = '$module', mod_user_id = '".$_SESSION['user_id']."', 
						mod_date = NOW(), lang = '".$languages."', content_id = '$contents', link = '".$link."', type = '".$type."', is_protected = '$protected', category_id = '".$categs."',
						timer_start = '$timer_start', timer_end = '$timer_end', open_in_new_window = '".$open_in_new."', picture = '".$filename."', menu_color = '".$menu_color."',
						gallery_id = '".$gal."', slideshow = '".$slide."', video = '".$video."'
					WHERE menu_id = '$m_id'
				";
				$mdb2->exec($query);

			    //csoportok update-je
				$query = "
					DELETE FROM iShark_Menus_Groups 
					WHERE menu_id = $m_id
				";
				$mdb2->exec($query);
				if (!empty($group)) {
    				foreach ($group as $row)
    				{
    					if ($row > 0)
    					{
    						$query = "
								INSERT INTO iShark_Menus_Groups 
								(menu_id, module_id, content_id, group_id)
    							VALUES 
								($m_id, $module, $contents, $row)
							";
    						$mdb2->exec($query);
    					}
    				}
				}

				//loggolas
				logger($act, '', '');

				//"fagyasztjuk" a form-ot
				$form->freeze();

				//visszadobjuk a lista oldalra
				header('Location: admin.php?p='.$module_name.'&menutype='.$menuType.'&mid='.$mid);
				exit;
			}
		}
	} //modositas vege

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('lang_title', $titles[$act]);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&menutype='.$menuType.'&mid='.$mid);
	$tpl->assign('form',       $renderer->toArray());

	// capture the array stucture
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_array', ob_get_contents());
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}

/**
 * ha torlunk egy menupontot
 */
if ($act == "del") {
	if (isset($_GET['m_id']) && is_numeric($_GET['m_id'])) {
		$m_id = intval($_GET['m_id']);
		$mid  = intval($_REQUEST['mid']);

		$query = "
			DELETE FROM iShark_Menus 
			WHERE menu_id = $m_id OR parent = $m_id
		";
		$mdb2->exec($query);

		//loggolas
		logger($act, '', '');
	}

	header('Location: admin.php?p='.$module_name.'&menutype='.$menuType.'&mid='.$mid);
	exit;
} //torles vege

/**
 * ha sorrendet modositunk
 */
if ($act == "ord") {
	if (isset($_GET['par']) && is_numeric($_GET['par']) && isset($_GET['m_id']) && is_numeric($_GET['m_id'])) {
		$par   = intval($_GET['par']);
		$m_id  = intval($_GET['m_id']);
		$type  = $_GET['type'];
		$mid   = intval($_REQUEST['mid']);

		if (isset($_GET['way']) && ($_GET['way'] == "up" || $_GET['way'] == "down")) {
			// Attól függ, hogy lefelé vagy felfelé akarjuk mozgatni
			$gt_lt  = ($_GET['way'] == 'up' ? '<' : '>');
			$order  = ($_GET['way'] == 'up' ? 'DESC' : '');
			$query  = "
				SELECT sortorder 
				FROM iShark_Menus 
				WHERE menu_id = $m_id
			";
			$result = $mdb2->query($query);
			while ($regihely = $result->fetchRow()) {
				// Kicserélendõ elem kiválasztása, gt_lt tõl függõen az alatta, vagy felette levõ elem
				$query = "
					SELECT menu_id, sortorder 
					FROM iShark_Menus 
					WHERE parent = $par AND type = '$type' AND sortorder $gt_lt $regihely[sortorder]
					ORDER BY sortorder $order
				";
				$mdb2->setLimit(1);
				$csere = $mdb2->query($query);
				// sorrend adatok cseréje:
				while ($ujhely = $csere->fetchRow()) {
					$query = "
						UPDATE iShark_Menus 
						SET sortorder = $ujhely[sortorder] 
						WHERE menu_id = $m_id
					";
					$mdb2->exec($query);

					$query = "
						UPDATE iShark_Menus 
						SET sortorder = $regihely[sortorder] 
						WHERE menu_id = $ujhely[menu_id]
					";
					$mdb2->exec($query);
				}
			}
		}
	}

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&menutype='.$menuType.'&mid='.$mid);
	exit;
} //sorrend modositas vege

/**
 * ha aktivalunk vagy inaktivalunk egy menupontot
 */
if ($act == "act") {
	include_once $include_dir.'/function.check.php';
	$m_id = intval($_REQUEST['m_id']);
	$mid  = intval($_REQUEST['mid']);

	check_active('iShark_Menus', 'menu_id', $m_id);

	//loggolas
	logger($act, '', '');

	header('Location: admin.php?p='.$module_name.'&menutype='.$menuType.'&mid='.$mid);
	exit;
}

/**
 * ha nincs semmilyen muvelet, akkor a listat mutatjuk
 */
if ($act == "lst") {
	include_once $include_dir.'/function.menu.php';

	$add_new = array (
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act=add&amp;menutype='.$menuType,
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

	//atadjuk a smarty-nak a kiirando cuccokat
	$tpl->assign('add_new',     $add_new);
	$tpl->assign('is_admin',    $is_admin);
	$tpl->assign('sitemenu',    menu(0, TRUE, 0, 1, 'all', $menuType, 0));
	$tpl->assign('back_arrows', 'admin.php');

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'menus_list';
}

?>
