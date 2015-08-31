<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

$module_name = "banners";

//nyelvi file betoltese
$locale->useArea("admin_".$module_name);

//design-hoz a cim betoltese
$title_module = array(
	'title' => $locale->get('title')
);

// fulek definialasa
$tabs = array(
    'banners' => $locale->get('field_list_header')
);

$acts = array(
    'banners' => array('oadd', 'omod', 'odel', 'badd', 'bmod', 'bdel', 'blst', 'bacta', 'bactm', 'bactd'),
);

//aktualis ful beallitasa
$page = 'banners';
if (isset($_REQUEST['act']) && array_key_exists($_REQUEST['act'], $tabs)) {
    $page = $_REQUEST['act'];
}

$sub_act = 'lst';
if (isset($_REQUEST['sub_act']) && in_array($_REQUEST['sub_act'], $acts[$page])) {
    $sub_act = $_REQUEST['sub_act'];
}

//breadcrumb
$breadcrumb->add($title_module['title'], 'admin.php?p='.$module_name);

if (isset($_GET['pageID']) && is_numeric($_GET['pageID'])) {
	$page_id = intval($_GET['pageID']);
} else {
	$page_id = 1;
}

// jogosultsagellenorzes
if (!check_perm($page, 0, 0, $module_name) || ($sub_act != 'lst' && !check_perm($page.'_'.$sub_act, 0, 1, $module_name))) {
    $acttpl = 'error';
    $tpl->assign('errormsg', $locale->get('error_no_permission'));
    return;
}

$tpl->assign('page_id',      $page_id);
$tpl->assign('self',         $module_name);
$tpl->assign('title_module', $title_module);
$tpl->assign('this_page',    $page);
$tpl->assign('dynamic_tabs', $tabs);

//javascript
$javascripts[] = "javascripts";

include_once $include_dir.'/function.banners.php';

$image_types = array (
	1  => 'GIF',
	2  => 'JPG',
	3  => 'PNG',
	4  => 'SWF',
	13 => 'SWF+'
);

/**
 * Paraméter beállítások
 */

// Feltöltési könyvtár tároló
$bannerdir = preg_replace('|/$|', '', $_SESSION['site_bannerdir']);

// Owner id paraméter, és owner adatai
$oid = 0;
$owner = array();
$bid = 0;

if (isset($_REQUEST['oid']) && $_REQUEST['oid'] != 0) {
	$oid = (int) $_REQUEST['oid'];
	$query = "
		SELECT * 
		FROM iShark_Banners_Owners 
		WHERE owner_id = $oid 
	";
	$result =& $mdb2->query($query);
	if (!$owner = $result->fetchRow()) {
		header('Location: admin.php?p='.$module_name);
		exit;
	}
	$tpl->assign('oid',   $oid);
	$tpl->assign('owner', $owner);
}

// Banner adatai
if (isset($_REQUEST['bid']) && $_REQUEST['bid'] != 0) {
	$bid = (int) $_REQUEST['bid'];
	$query = "
		SELECT * 
		FROM iShark_Banners 
		WHERE banner_id = $bid
	";
	$result =& $mdb2->query($query);
	if (!$banner = $result->fetchRow()) {
		header('Location: admin.php?p='.$module_name);
		exit;
	}
	$tpl->assign('bid',    $bid);
	$tpl->assign('banner', $banner);
}

/**
 * ha telepitjuk a modult
 */
/*if ($act == "ins") {
	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Banners` (
			banner_id   INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			owner_id	INT NOT NULL DEFAULT 0,
			banner_link VARCHAR(255) NOT NULL DEFAULT '',
			realname	VARCHAR(255) NOT NULL DEFAULT '',
			name		VARCHAR(255) NOT NULL DEFAULT '',
			width		INT UNSIGNED NOT NULL DEFAULT 0,
			height		INT UNSIGNED NOT NULL DEFAULT 0,
			type		CHAR(1) NOT NULL,

			add_user_id INT NOT NULL DEFAULT 0,
			add_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			mod_user_id INT NOT NULL DEFAULT 0,
			mod_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			
			KEY `owner_id` (owner_id)
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Banners_Owners` (
			owner_id	INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			owner_name	VARCHAR(255) NOT NULL DEFAULT '',
			kapcs_tarto	VARCHAR(255) NOT NULL DEFAULT '',
			email   	VARCHAR(255) NOT NULL DEFAULT '',
			telefon 	VARCHAR(255) NOT NULL DEFAULT '',

			add_user_id INT NOT NULL DEFAULT 0,
			add_date	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			mod_user_id INT NOT NULL DEFAULT 0,
			mod_date	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Banners_Places` (
			place_id	INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			place_name	VARCHAR(255) NOT NULL DEFAULT '',
			max_width	INT UNSIGNED NOT NULL DEFAULT 0,
			max_height  INT UNSIGNED NOT NULL DEFAULT 0,

			add_user_id	INT NOT NULL DEFAULT 0,
			add_date	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			mod_user_id INT NOT NULL DEFAULT 0,
			mod_date	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
		);
	";
	$mdb2->exec($query);

	$query = "
		CREATE TABLE IF NOT EXISTS `iShark_Banners_Menus_Places` (
			menuplace_id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			banner_id	INT NOT NULL DEFAULT 0,
			place_id	INT NOT NULL DEFAULT 0,
			menu_id		INT NOT NULL DEFAULT 0,
			click_count INT UNSIGNED NOT NULL DEFAULT 0,

			timer_start DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			timer_end	DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',

			INDEX place_menu_banner (place_id, menu_id, banner_id),
			INDEX banner_place_menu (banner_id, menu_id, place_id)
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
		DROP TABLE IF EXISTS `iShark_Banners`;
	";
	$mdb2->exec($query);
	$query = "
		DROP TABLE IF EXISTS `iShark_Banners_Places`;
	";
	$mdb2->exec($query);
	$query = "
		DROP TABLE IF EXISTS `iShark_Banners_Owners`;
	";
	$mdb2->exec($query);
	$query = "
		DROP TABLE IF EXISTS `iShark_Banners_Menus_Places`
	";
	$mdb2->exec($query);

	//loggolas
	logger('unins', '', '');

	header('Location: admin.php?p='.$module_name);
	exit;
}*/

/**
 * Banner aktiválásának törlése 
 */
if ($sub_act == 'bactd') {
	if (!isset($_REQUEST['bid']) || !isset($_REQUEST['oid']) || !isset($_REQUEST['mpid'])) {
		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}
	$mpid = intval($_REQUEST['mpid']);

	$query = "
		DELETE FROM iShark_Banners_Menus_Places 
		WHERE menuplace_id = $mpid
	";
	$mdb2->exec($query);

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=bacta&oid='.$oid.'&bid='.$bid);
	exit;
}
/**
 * Banner aktiválása 
 */
if ($sub_act == 'bacta' || $sub_act == 'bactm') {
	if (!isset($_REQUEST['oid']) || !isset($_REQUEST['bid'])) {
		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}

	//breadcrumb
	$breadcrumb->add($locale->get('field_list_header'), 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=blst&amp;oid='.$_REQUEST['oid']);
	$breadcrumb->add($locale->get('field_list_activate_header'), '#');

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';
	require_once 'HTML/QuickForm/jscalendar.php';

	$form =& new HTML_QuickForm('act_frm', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'bnr',     $locale->get('form_header_activate'));
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);
	$form->addElement('hidden', 'bid',     $bid);
	$form->addElement('hidden', 'oid',     $oid);

	//Bannerhelyek:
	$query = "
		SELECT place_id, place_name 
		FROM iShark_Banners_Places 
		ORDER BY place_name
	";
	$result =& $mdb2->query($query);
	$select =& $form->addElement('select', 'place_id', $locale->get('field_banner_place'), $result->fetchAll('', $rekey = true));
	//ha nincs meg egyetlen bannerhely sem, akkor hibauzenet
	if ($result->numRows() == 0) {
		$form->setElementError('place_id', $locale->get('error_no_bannerplace'));
	}

	//megjelenesek max. szama
	$form->addElement('text', 'impmax', $locale->get('field_banner_impmax'));

	// Menüpontok:
	$menus         = array();
	$menus[0]      = $locale->get('field_allmenu');
	$menus_from_db = get_menus();
	foreach($menus_from_db as $key=>$value) {
		$menus[$key] = $value;
	}
	$menusel =& $form->addElement('select', '_menu_id', $locale->get('field_menu'), $menus);
	if (empty($menus)) {
		$form->setElementError('_menu_id', $locale->get('error_no_menu'));
	}

	//idozito
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_start', null, array('readonly' => 'readonly', 'id' => 'timer_start')),
			HTML_QuickForm::createElement('jscalendar', 'start_calendar', null, $calendar_start),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_start\')"')
		),
        'date_start', $locale->get('field_timer_start'), null, false
    );
	$form->addGroup(
		array(
			HTML_QuickForm::createElement('text', 'timer_end', null, array('readonly' => 'readonly', 'id' => 'timer_end')),
			HTML_QuickForm::createElement('jscalendar', 'end_calendar', null, $calendar_end),
			HTML_QuickForm::createElement('link', 'deltimer', null, 'javascript:void(null);', $locale->get('deltimer'), 'onclick="deltimer(\'timer_end\')"')
		),
		'date_end', $locale->get('field_timer_end'), null, false
    );

    $form->addRule('impmax',   $locale->get('error_impmax'),                'numeric');
	$form->addRule('place_id', $locale->get('error_no_bannerplace_active'), 'required');
	$form->addRule('_menu_id', $locale->get('error_no_menu_active'),        'required');
	if ($form->isSubmitted() && ($form->getSubmitValue('timer_start') != "" || $form->getSubmitValue('timer_end') != "")) {
	    include_once 'includes/function.check.php';
		$form->addFormRule('check_timer');
    }

	if ($sub_act == 'bactm') {
		$mpid = intval($_REQUEST['mpid']);

		$form->addElement('hidden', 'mpid', $mpid);

		//lekérdezzük a módosítani kívánt bannert
		$query = "
			SELECT * 
			FROM iShark_Banners_Menus_Places 
			WHERE menuplace_id = $mpid
		";
		$result = $mdb2->query($query);
		$row = $result->fetchRow();

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

		$form->setDefaults(array(
			'place_id'    => $row['place_id'],
			'_menu_id' 	  => $row['menu_id'],
			'timer_start' => $timer_start,
			'timer_end'   => $timer_end,
			'impmax'      => $row['impression_max']
			)
		);

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$mpid        = $form->getSubmitValue('mpid');
			$place_id    = $form->getSubmitValue('place_id');
			$_menu_id    = $form->getSubmitValue('_menu_id');
			$timer_start = $form->getSubmitValue('timer_start');
			$timer_end   = $form->getSubmitValue('timer_end');
			$impmax      = intval($form->getSubmitValue('impmax'));

			$query = "
				UPDATE iShark_Banners_Menus_Places 
				SET banner_id      = $bid, 
					place_id       = $place_id, 
					menu_id        = $_menu_id, 
					timer_start    = '$timer_start', 
					timer_end      = '$timer_end',
					impression_max = $impmax 
				WHERE menuplace_id = $mpid
			";
			$mdb2->exec($query);

			logger($page.'_'.$sub_act);

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act='.$sub_act.'&oid='.$oid.'&bid='.$bid);
			exit;
		}
	}

	if ($sub_act == 'bacta') {
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$place_id    = $form->getSubmitValue('place_id');
			$_menu_id    = $form->getSubmitValue('_menu_id');
			$timer_start = $form->getSubmitValue('timer_start');
			$timer_end   = $form->getSubmitValue('timer_end');
			$impmax      = intval($form->getSubmitValue('impmax'));

			$menuplace_id = $mdb2->extended->getBeforeID('iShark_Banners_Menus_Places', 'menuplace_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Banners_Menus_Places 
				(menuplace_id, banner_id, place_id, menu_id, timer_start, timer_end, impression_max)
				VALUES 
				($menuplace_id, $bid, $place_id, $_menu_id, '$timer_start', '$timer_end', $impmax)
			";
			$mdb2->exec($query);

			logger($page.'_'.$sub_act);

			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act='.$sub_act.'&oid='.$oid.'&bid='.$bid);
			exit;
		}
	}
	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	//lekerdezzuk a bannert, hogy mindig meg tudjuk jeleniteni az oldalon
	$query_banner = "
		SELECT b.banner_code AS bcode, b.realname AS pic, b.width AS width, b.height AS height, b.type AS type 
		FROM iShark_Banners b 
		WHERE b.banner_id = $bid
	";
	$result_banner =& $mdb2->query($query_banner);
	if ($result_banner->numRows() > 0) {
	    $tpl->assign('actbanner', $result_banner->fetchAll());
	}

	// Aktív lista kiíratása
	$query = "
		SELECT 
			P.place_name AS place_name, 
			(CASE MP.menu_id 
				WHEN '0' THEN '".$locale->get('field_allmenu')."' 
				ELSE M.menu_name 
			 END
			) AS menu_name,
			MP.timer_start AS timer_start, MP.timer_end AS timer_end, MP.menuplace_id AS mpid,
			MP.click_count AS click_count, MP.impression_max AS impmax,  
			(MP.impression_max - MP.impression_num) AS imprest, 
			(MP.click_count / MP.impression_num * 100) AS percent
		FROM iShark_Banners_Menus_Places MP, iShark_Banners B 
		LEFT JOIN iShark_Banners_Places P ON P.place_id=MP.place_id
		LEFT JOIN iShark_Menus M ON M.menu_id=MP.menu_id
		WHERE MP.banner_id = $bid AND B.banner_id = MP.banner_id
		ORDER BY MP.menuplace_id
	";

	include_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=blst&amp;oid='.$_REQUEST['oid']);
	$tpl->register_function('getdim', 'get_dimensions');

	$acttpl = 'banners_act_list';
}

/**
 * Banner törlése 
 */
if ($sub_act == 'bdel') {
	if (!isset($_REQUEST['oid']) || !isset($_REQUEST['bid'])) {
		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}
	$query = "
		DELETE FROM iShark_Banners_Menus_Places 
		WHERE banner_id = $bid
	";
	$mdb2->exec($query);

	$query = "
		DELETE FROM iShark_Banners 
		WHERE banner_id = $bid
	";
	$mdb2->exec($query);

	@unlink($bannerdir.'/'.$banner['realname']);

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=blst&oid='.$oid);
	exit;
}

/**
 * Banner feltöltése 
 */
if ($sub_act == 'badd' || $sub_act == 'bmod') {
	if (!isset($_REQUEST['oid'])) {
		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}

	$titles = array('badd' => $locale->get('title_add'), 'bmod' => $locale->get('title_mod'));

	//breadcrumb
	$breadcrumb->add($locale->get('field_list_bheader'), 'admin.php?p='.$module_name.'&act=blst&oid='.$_REQUEST['oid']);
	$breadcrumb->add($titles[$sub_act], '#');

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';

	$form =& new HTML_QuickForm('addbanner_frm', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'banneradd', $locale->get('form_header'));
	$form->addElement('hidden', 'act',       $page);
	$form->addElement('hidden', 'sub_act',   $sub_act);
	$form->addElement('hidden', 'oid',       $oid);
	$form->addElement('hidden', 'bid',       $bid);

	// tipus valaszto
	$btype = array();
	$btype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('field_btype_local'),   '1');
	$btype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('field_bytpe_outside'), '0');
	$form->addGroup($btype, 'btype', $locale->get('field_btype'));

	// banner file - csak hozzadasnal
	if ($sub_act == 'badd') {
	    $file =& $form->addElement('file', 'banner_file', $locale->get('field_file'), array('id' => 'file'));
	}

	// banner hivatkozas
	$form->addElement('text', 'banner_link', $locale->get('field_link'), array('maxlength' => 255));

	// kulso kod
	$code_area =& $form->addElement('textarea', 'codearea', $locale->get('field_codearea'));
	$code_area->setRows(8);
	$code_area->setCols(100);

	// alapertelmezett ertekek hozzadasnal
	if ($sub_act == 'badd') {
	    $form->setDefaults(array(
	        'btype' => 1
	        )
	    );
	}

	// alapertelmezett ertekek modositasnal
	if ($sub_act == 'bmod') {
	    if (!empty($banner['banner_code'])) {
	        $bd_type = 0;
	    } else {
	        $bd_type = 1;
	    }

	    $form->setDefaults(array(
	        'banner_link' => $banner['banner_link'],
	        'btype'       => $bd_type,
	        'codearea'    => $banner['banner_code']
	        )
	    );
	}

	$form->addRule('btype', $locale->get('error_no_btype'), 'required');
	if ($form->isSubmitted() && intval($form->getSubmitValue('btype')) == 1) {
	    $form->addRule('banner_link', $locale->get('error_no_link'),   'required');
	    if ($sub_act == 'badd') {
	        $form->addRule('banner_file', $locale->get('error_no_upload'), 'uploadedfile');
	    }
	}
	if ($form->isSubmitted() && intval($form->getSubmitValue('btype')) == 0) {
	    $form->addRule('codearea', $locale->get('error_no_codearea'), 'required');
	}

	$form->applyFilter('__ALL__', 'trim');

	if ($form->validate()) {
	    $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

	    $bt   = intval($form->getSubmitValue('btype'));
	    $link = $mdb2->escape($form->getSubmitValue('banner_link'));

	    // csak akkor ha helyi banner
	    if ($bt == 1) {
	        // file feltoltes csak hozzadasnal
	        if ($sub_act == 'add') {
        		if (!$file->isUploadedFile()) {
        			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act='.$sub_act.'&oid='.$oid);
        			exit;
        		}

        		$filevalues = $file->getValue();
        		$filename	= time().preg_replace('|[^\d\w_\.]|', '_', change_hunchar($filevalues['name']));

        		// Amennyiben a feltöltött fájl típusa megfelelõ, és sikeres a feltöltés: (JPG/GIF/PNG/SWF)
        		$upload_error = 0;
        		if ($size = @GetImageSize($filevalues['tmp_name'])) { 
        			if (array_key_exists($size[2],$image_types)) {
        				if (@$file->moveUploadedFile($bannerdir,$filename)) {
        					@chmod($bannerdir.'/'.$filename, 0664);
        
        					$width  = $size[0];
        					$height = $size[1];
        					$type   = $size[2];
        					if ($type=='13') {
        						$type = '4';
        					}
        				} else {
        				    $upload_error = 1;
        					$form->setElementError('banner_file', $locale->get('error_no_upload'));
        				}
        			} else {
        			    $upload_error = 1;
        				$form->setElementError('banner_file', $locale->get('error_filetype'));
        			}
        		} else {
        		    $upload_error = 1;
        			$form->setElementError('banner_file', $locale->get('error_filesize'));
        		}

        		if ($upload_error == 0) {
        		    $banner_id = $mdb2->extended->getBeforeID('iShark_Banners', 'banner_id', TRUE, TRUE);
        			$query = "
        				INSERT INTO iShark_Banners 
        				(banner_id, owner_id, banner_link, realname, width, height, type, add_user_id, add_date, mod_user_id, mod_date)
        				VALUES
        				($banner_id, $oid, '$link', '$filename', '$width', '$height', '$type', ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW())
        			";
        			$mdb2->exec($query);

        			logger($page.'_'.$sub_act);

        			header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=blst&oid='.$oid);
        			exit;
        		}
	        }

	        // modositasnal csak a link
	        if ($sub_act == 'bmod') {
	            $query = "
					UPDATE iShark_Banners 
					SET banner_link = '$banner_link', 
						banner_code = '',
						mod_date    = NOW(), 
						mod_user_id = ".$_SESSION['user_id']." 
					WHERE banner_id = $bid
				";
				$mdb2->exec($query);

				logger($page.'_'.$sub_act);

        		header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=blst&oid='.$oid);
        		exit;
	        }
	    } // helyi banner vege

	    // kulso banner
	    if ($bt == 0) {
	        $newlines = array("\r\n", "\n", "\r");
			$code = str_replace($newlines, "", $form->getSubmitValue('codearea'));

	        // hozzaadas
	        if ($sub_act == 'badd') {
	            $banner_id = $mdb2->extended->getBeforeID('iShark_Banners', 'banner_id', TRUE, TRUE);
        		$query = "
        			INSERT INTO iShark_Banners 
        			(banner_id, owner_id, banner_code, add_user_id, add_date, mod_user_id, mod_date)
        			VALUES
        			($banner_id, $oid, '$code', ".$_SESSION['user_id'].", NOW(), ".$_SESSION['user_id'].", NOW())
        		";
        		$mdb2->exec($query);

        		logger($page.'_'.$sub_act);

        		header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=blst&oid='.$oid);
        		exit;
	        }

	        // modositas
	        if ($sub_act == 'bmod') {
	            // kitoroljuk az eredeti banner-t, ha volt
	            if (!empty($banner['realname'])) {
	                @unlink($_SESSION['site_bannerdir'].'/'.$banner['realname']);
	            }

	            $query = "
					UPDATE iShark_Banners 
					SET banner_link = '',
						banner_code = '$code', 
						realname    = '',
						name        = '',
						width       = '',
						height      = '',
						type        = '',
						mod_date    = NOW(), 
						mod_user_id = ".$_SESSION['user_id']." 
					WHERE banner_id = $bid
				";
				$mdb2->exec($query);

				logger($page.'_'.$sub_act);

        		header('Location: admin.php?p='.$module_name.'&act='.$page.'&sub_act=blst&oid='.$oid);
        		exit;
	        }
	    }
	}

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name.'&act='.$page.'&sub_act=blst&oid='.$oid);

	$acttpl = 'dynamic_form';
}

/**
 * Bannerek listája 
 */
if ($sub_act == 'blst') {
	if (!isset($_REQUEST['oid'])) {
		header('Location: admin.php?p='.$module_name);
		exit;
	}

	//breadcrumb
	$breadcrumb->add($locale->get('field_list_bheader'), '#');

	$query = "
		SELECT *
		FROM iShark_Banners
		WHERE owner_id = $oid
	";

	include_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=badd&amp;oid='.$oid,
			'title' => $locale->get('title_add'),
			'pic'   => 'add.jpg'
		)
	);

	$tpl->register_function('getdim', 'get_dimensions');

	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('add_new',    $add_new);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name);
	$tpl->assign('lang_title', $locale->get('field_list_owner').' - '.$owner['owner_name']);

	$acttpl = 'banners_list';
}

/**
 * Bannertulajdonos törlése 
 */
if ($sub_act == 'odel') {
    // Tulajdonoshoz tartozó bannerek törlése
	$query = "
		SELECT * 
		FROM iShark_Banners 
		WHERE owner_id = $oid
	";
	$result =& $mdb2->query($query);
	while ($banner = $result->fetchRow()) {
		$query = "
			DELETE FROM iShark_Banners_Menus_Places 
			WHERE banner_id = ".$banner['banner_id']."
		";
		$mdb2->exec($query);
		@unlink($bannerdir.'/'.$banner['realname']);
	}

	$query = "
		DELETE FROM iShark_Banners 
		WHERE owner_id = $oid
	";
	$mdb2->exec($query);

	// Tulajdonos törlése
	$query = "
		DELETE FROM iShark_Banners_Owners 
		WHERE owner_id = $oid
	";
	$mdb2->exec($query);

	logger($page.'_'.$sub_act);

	header('Location: admin.php?p='.$module_name.'act='.$page);
	exit;
}

/**
 * Banner tulajdonos módosítása vagy új felvitel esetén 
 */
if ($sub_act == 'oadd' || $sub_act == 'omod') {
    $titles = array('oadd' => $locale->get('title_add_owner'), 'omod' => $locale->get('title_mod_owner'));

	include_once 'HTML/QuickForm.php';
	include_once 'HTML/QuickForm/Renderer/Array.php';

	$form =& new HTML_QuickForm('owneradd_frm', 'post', 'admin.php?p='.$module_name);
	$form->removeAttribute('name');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'owners',  $locale->get('form_header'));
	$form->addElement('hidden', 'oid',     $oid);
	$form->addElement('hidden', 'act',     $page);
	$form->addElement('hidden', 'sub_act', $sub_act);

	//tulajdonos neve
	$form->addElement('text', 'owner_name',  $locale->get('field_owner_name'),    array('maxlength' => 255));

	//kapcsolattarto neve
	$form->addElement('text', 'contact', $locale->get('field_owner_contact'), array('maxlength' => 255));

	//email cim
	$form->addElement('text', 'email', $locale->get('field_owner_email'),   array('maxlength' => 255));

	//telefon
	$form->addElement('text', 'phone', $locale->get('field_owner_phone'),   array('maxlength' => 255));

	$form->setDefaults($owner);

	$form->applyFilter('__ALL__', 'trim');

	$form->addRule('owner_name',  $locale->get('error_owner_name'),    'required');
	$form->addRule('contact',     $locale->get('error_owner_contact'), 'required');
	$form->addRule('email',       $locale->get('error_owner_email'),   'required');
	$form->addRule('email',       $locale->get('error_owner_email2'),  'email');
	$form->addRule('phone',       $locale->get('error_owner_phone'),   'required');

	if ($form->validate()) {
		$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

		$owner_name  = $form->getSubmitValue('owner_name');
		$kapcs_tarto = $form->getSubmitValue('contact');
		$email       = $form->getSubmitValue('email');
		$telefon     = $form->getSubmitValue('phone');

		if ($oid == 0) {
			$owner_id = $mdb2->extended->getBeforeID('iShark_Banners_Owners', 'owner_id', TRUE, TRUE);
			$query = "
				INSERT INTO iShark_Banners_Owners 
				(owner_id, owner_name, add_user_id, add_date, contact, email, phone) 
				VALUES 
				($owner_id, '$owner_name', ".$_SESSION['user_id'].", NOW(), '$kapcs_tarto', '$email', '$telefon')
			";
		} else {
			$query = "
				UPDATE iShark_Banners_Owners 
				SET owner_name  = '$owner_name',
					mod_user_id = ".$_SESSION['user_id'].",
					mod_date    = NOW(),
					contact     = '$kapcs_tarto',
					email       = '$email',
					phone       = '$telefon'
				WHERE owner_id = $oid
			";
		}
		$mdb2->exec($query);

		$form->freeze();

		logger($page.'_'.$sub_act);

		header('Location: admin.php?p='.$module_name.'&act='.$page);
		exit;
	}

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	//breadcrumb
	$breadcrumb->add($titles[$sub_act], '#');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form',       $renderer->toArray());
	$tpl->assign('lang_title', $titles[$sub_act]);
	$tpl->assign('back_arrow', 'admin.php?p='.$module_name);

	$acttpl = 'dynamic_form';
}

/**
 * Tulajdonosok listája 
 */
if ($sub_act == 'lst') {
	$query = "
		SELECT bo.owner_id AS owner_id, bo.owner_name AS owner_name, bo.contact AS kapcs_tarto,
			bo.email AS email, bo.phone AS telefon, u.name AS username
		FROM iShark_Banners_Owners bo 
		LEFT JOIN iShark_Users u ON bo.add_user_id = u.user_id
		ORDER BY bo.owner_name
	";

	$add_new = array(
		array(
			'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=oadd',
			'title' => $locale->get('title_add_owner'),
			'pic'   => 'add.jpg'
		)
	);

	include_once 'Pager/Pager.php';
	$paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

	$tpl->assign('page_data',  $paged_data['data']);
	$tpl->assign('page_list',  $paged_data['links']);
	$tpl->assign('add_new',    $add_new);
	$tpl->assign('back_arrow', 'admin.php');

	$acttpl = 'banners_owner_list';	
}

?>