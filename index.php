<?php
header('Content-Type: text/html; charset=ISO-8859-2');

ini_set('display_errors', 0);

include_once 'includes/config.php';
include_once 'includes/functions.php';

$mdb2->query("SET NAMES 'latin2'");

/**
 * $javascripts   - a javascript file-okat a rendszeren belul barhol be tudjuk rakni a tombbe, ezutan automatikusan bekerul a kodba
 * $bodyonload    - ha valami js fuggvenyt automatikusan szeretnenk lefuttatni az oldal toltodesekro, akkor ebbe a tombbe rakjuk bele
 * $css           - egyedi css-t lehet hasznalni, a kivant css file nevet adjuk hozza a tombhoz
 * $ajax          - ajax-hoz szukseges dolgokat lehet berakni a tombbe 
 *                  ket eleme lehet: link, script (pl. admin/rights.php-ban)
 * $site_warnings - figyelmezteto szoveget tudunk kiirni a tomb segitsegevel 
 *                  warning eseten az oldal futasa nem all meg, tehat a content reszbe betolti a tartalmat
 *                  ket eleme lehet: text (maga a szoveg), link (vissza linkhez tartozo link)
 * $site_errors   - hibauzenetet tudunk kiirni a tomb segitsegevel
 *                  error eseten az oldal futasa megall, tehat a content reszben csak a hibauzenet latszodik
 *                  ket eleme lehet: text (maga a szoveg), link (vissza linkhez tartozo link)
 * $site_success  - sikeres uzeneteket tudunk kiiratni vele
 *                  ket eleme lehet: text (maga a szoveg), link (tovabb link)
 */
$javascripts   = array();
$bodyonload    = array();
$css           = array();
$ajax          = array();
$site_warnings = array();
$site_errors   = array();
$site_success  = array();
$acttpl        = "";

//elavult captcha file-ok torlese
clearCaptcha();

//nyelvi modulnak atadjuk, hogy mit hasznaljon
$locale->lang = $_SESSION['site_lang'];
$locale->initArea('config');

if (isset($_REQUEST["sitetype"])) {
	$_SESSION["sitetype"] = $_REQUEST["sitetype"];
}

/**
 * website statisztika
 * ebben a valtozoban megadott szoveg fog megjelenni a statisztikaban, mint altogatott oldal
 */
$statdoc = 'index';

$honapok = array(
    '01' => 'január',
    '02' => 'február',
    '03' => 'március',
    '04' => 'április',
    '05' => 'május',
    '06' => 'június',
    '07' => 'július',
    '08' => 'augusztus',
    '09' => 'szeptember',
    '10' => 'október',
    '11' => 'november',
    '12' => 'december',
);

$tpl->assign('honap', $honapok[date("m")]);

//ha van shop-unk csak, akkor hasznaljuk ezt a reszt
if (isModule('shop', 'index') && !empty($_SESSION['site_shop_is_breadcrumb'])) {
	include_once 'includes/classes.php';
	$shop_breadcrumb =& new Breadcrumb();
}

//ha van aprohirdetesunk-unk csak, akkor hasznaljuk ezt a reszt
if (isModule('classifieds', 'index') && !empty($_SESSION['site_class_is_breadcrumb'])) {
	include_once 'includes/classes.php';
	$class_breadcrumb =& new Breadcrumb();
}

//sikeresen uzenetek kezelese
if (isset($_GET['success']) && isset($_GET['link'])) {
	if ($locale->variableExists('success', $_GET['success'])) {
		$site_success[] = array('text' => $locale->get('success', $_GET['success']), 'link' => $_GET['link']);
	}
}

$javascripts[] = 'jquery';
$javascripts[] = 'javascripts';
$javascripts[] = 'bootstrap/js/bootstrap.min';

//fomenu lekerdezese
include_once $include_dir.'/function.menu.php';

$startdate = strtotime("2013-04-22");
$today = strtotime(date("Y-m-d"));
if ($today >= $startdate) {
	$tpl->assign('showsub', 1);
} else {
	$tpl->assign('showsub', 0);
}

$tpl->assign('topmenu', menu(2, FALSE, '', 1, NULL, 'index', 1));

if (isset($_GET['p']) && $_GET['p'] != '') {
    
    /*if (isset($_GET['mid']) && is_numeric($_GET['mid'])) {
        $query_menu = "
            SELECT m.module_id AS modid, m.content_id AS conid, m.category_id AS catid, m.link AS link, m.is_protected AS mprot, m.picture AS mpic 
            FROM iShark_Menus m 
            WHERE m.menu_id = $mid
        ";
        $result_menu =& $mdb2->query($query_menu);
        while ($row_menu = $result_menu->fetchRow())
		{
            $tpl->assign('menu_bg_pic', $row_menu['mpic']);
        }
    }*/
    
	//ha konkret oldalra akarunk ugrani
	$_GET['p'] = str_replace('../', '',$_GET['p']);
$module_title = "";
	if (is_file("modules/".$_GET['p'].".php")) {
		include_once "modules/".$_GET['p'].".php";

		/*if ($_GET["p"] == "test") {
			$tpl->assign('bgpic', $theme_dir.'/'.$theme.'/images/teszt.jpg');
		}*/
		
        $tpl->assign('parent', $module_title);
        $tpl->assign('parent_id', "index.php?p=account&act=account_add");

		$tpl->assign('page', $acttpl);

		//website statisztika
		$statdoc = 'modules/'.$_GET['p'];		
		
	} else {
		//ha nem letezik a kert oldal, akkor hibauzenet
		$site_errors[] = array('text' => $locale->get('iblocks', 'error_page_not_found'), 'link' => 'javascript:history.back(-1)');
	}
}
//ha menupontot akarunk lekerni
elseif (isset($_GET['mid']) && is_numeric($_GET['mid'])) {
	$mid = intval($_GET['mid']);
    
	/*$query_menu = "
		SELECT m.module_id AS modid, m.content_id AS conid, m.category_id AS catid, m.link AS link, m.is_protected AS mprot, m.picture AS mpic 
		FROM iShark_Menus m 
		WHERE m.menu_id = $mid
	";
	$result_menu =& $mdb2->query($query_menu);
	while ($row_menu = $result_menu->fetchRow())
	{
		$tpl->assign('menu_bg_pic', $row_menu['mpic']);
	}*/

	
	if (!empty($_SESSION['user_id'])) {
	    $usid = $_SESSION['user_id'];
	} else {
	    $usid = 0;
	}

	$query_menu = "
		SELECT m.module_id AS modid, m.content_id AS conid, m.category_id AS catid, m.link AS link, m.is_protected AS mprot, m.picture AS mpic, gallery_id, slideshow, video 
		FROM iShark_Menus m 
		WHERE m.menu_id = $mid
	";
	$result_menu =& $mdb2->query($query_menu);
	if ($result_menu->numRows() == 0) {
		//ha nem letezik a kert oldal, akkor hibauzenet
		$site_errors[] = array('text' => $locale->get('iblocks', 'error_page_not_found'), 'link' => 'javascript:history.back(-1)');
	} else {
		while ($row_menu = $result_menu->fetchRow())
		{
			if (!empty($row_menu['mpic']) && file_exists('files/news/'.$row_menu['mpic'])) {
				$tpl->assign('bgpic', 'files/news/'.$row_menu['mpic']);
			}
		    //ellenorizzuk, hogy van-e csoport kivalasztva a menuhoz
        	$azonos = 0;
        	$query = "
        		SELECT * 
        		FROM iShark_Menus_Groups 
        		WHERE menu_id = $mid
        	";
        	$result =& $mdb2->query($query);
        	if ($result->numRows() > 0) {
        		//lekerdezzuk az user csoportjait
        		$query2 = "
        			SELECT group_id 
        			FROM iShark_Groups_Users 
        			WHERE user_id = $usid
        		";
        		$result2 =& $mdb2->query($query2);
        		$azonos = 1;
        		while ($row2 = $result2->fetchRow())
        		{
        			$g_id = $row2['group_id'];
        			//lekerdezzuk, hogy az adott csoporthoz tartozik-e engedely a menuhoz
	                if ($row_menu['modid'] != 0) {
            			$query3 = "
            				SELECT * 
            				FROM iShark_Menus_Groups 
            				WHERE menu_id = $mid AND module_id = ".intval($row_menu['modid'])." AND group_id = $g_id
            			";
	                }
	                elseif ($row_menu['conid'] != 0) {
	                    $query3 = "
            				SELECT * 
            				FROM iShark_Menus_Groups 
            				WHERE menu_id = $mid AND content_id = ".intval($row_menu['conid'])." AND group_id = $g_id
            			";
	                }
	                else {
	                    $query3 = "
            				SELECT * 
            				FROM iShark_Menus_Groups 
            				WHERE menu_id = $mid AND group_id = $g_id
            			";
	                }
        			$result3 =& $mdb2->query($query3);
        			if ($result3->numRows() > 0 || ($g_id == $_SESSION['site_sys_prefgroup'])) {
        			    $azonos = 0;
        			}
        		}
        	}

			//ha vedett a menupont, de nincs bejelentkezve, akkor hiba
			if ($row_menu['mprot'] == 1 && !isset($_SESSION['user_id'])) {
				$site_errors[] = array('text' => $locale->get('iblocks', 'error_protected'), 'link' => 'javascript:history.back(-1)');
			} else {
				//ha modult akarunk betolteni
				if ($row_menu['modid'] != 0) {
					$module_id = $row_menu['modid'];

					$query_module = "
						SELECT m.file_name AS file, m.file_ext AS ext 
						FROM iShark_Modules m 
						WHERE m.module_id = $module_id AND is_active = '1'
					";
					$result_module =& $mdb2->query($query_module);
					while ($row_module = $result_module->fetchRow())
					{
						if (file_exists('modules/'.$row_module['file'].$row_module['ext'])) {
							if ($azonos != 1) {
    							include_once 'modules/'.$row_module['file'].$row_module['ext'];
    							$tpl->assign('page', $acttpl);
                                $tpl->assign('module_name', $row_module['file']);
    							//website statisztika
    							$statdoc = 'modules/'.$row_module['file'];
							} else {
							    $site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
							}
						} else {
						    $site_errors[] = array('text' => $locale->get('iblocks', 'error_page_not_found'), 'link' => 'javascript:history.back(-1)');
						}
					}
				}
				//ha tartalmat akarunk betolteni
				if ($row_menu['conid'] != 0) {
					$content_id = $row_menu['conid'];

    				if ($azonos != 1) {
    					include_once 'modules/contents.php';
    					$tpl->assign('page', $acttpl);
    
    					//website statisztika
    					$statdoc = 'modules/contents';
    				} else {
    					$site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
    				}
				}

				//ha kulso linket akarunk betolteni
				if ($row_menu['link'] != "") {
				    if ($azonos != 1) {
                        if ($row_menu['link'] == "almenu") {
                            $tpl->assign('almenu', 1);
                            $tpl->assign('page', 1);
                        } elseif ($row_menu['link'] == "terkep") {
                            $tpl->assign('terkep', 1);
                            $tpl->assign('page', 1);
                        } else {
                            header('Location: http://'.$row_menu['link']);
                            exit;
                        }
				    } else {
    					$site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
    				}
				}
				//ha kategoriat akarunk betolteni
				if ($row_menu['catid'] != 0) {
					$category_id = $row_menu['catid'];

					if ($azonos != 1) {
    					include_once 'modules/news.php';
    					$tpl->assign('page', $acttpl);

    					//website statisztika
    					$statdoc = 'modules/news';
					} else {
    					$site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
    				}
				}
				if ($row_menu['gallery_id'] != 0) {
					$gallery_id = $row_menu['gallery_id'];

					$query_pics = "
						SELECT p.picture_id, p.realname, p.name, p.description, p.width, p.height
						FROM iShark_Galleries_Pictures AS gp
						LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
						WHERE gp.gallery_id = '".$gallery_id."'
						ORDER BY gp.orders
						LIMIT 24
					";
					$result_pics = $mdb2->query($query_pics);
					$tpl->assign('gal', $result_pics->fetchAll());
					$tpl->assign('gal_id', $gallery_id);
					
					$all_pics = "
						SELECT COUNT(p.picture_id) AS allpics
						FROM iShark_Galleries_Pictures AS gp
						LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
						WHERE gp.gallery_id = '".$gallery_id."'
						ORDER BY gp.orders
					";
					$result_all_pics = $mdb2->query($all_pics);
					$row_all_pics = $result_all_pics->fetchRow();
					
					$tpl->assign('gal_pages', ceil($row_all_pics['allpics']/24));
					/*if ($azonos != 1) {
    					include_once 'modules/gallery.php';
    					$tpl->assign('page', $acttpl);

					} else {
    					$site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
    				}*/
				}
				if ($row_menu['slideshow'] != 0) {
					$slideshow = $row_menu['slideshow'];

					$query_pics = "
						SELECT p.picture_id, p.realname, p.name, p.description, p.width, p.height
						FROM iShark_Galleries_Pictures AS gp
						LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
						WHERE gp.gallery_id = '".$slideshow."'
						ORDER BY gp.orders
						LIMIT 4
					";
					$result_pics = $mdb2->query($query_pics);
					$tpl->assign('slide', $result_pics->fetchAll());
					$tpl->assign('slide_id', $slideshow);
					
					$all_pics = "
						SELECT COUNT(p.picture_id) AS allpics
						FROM iShark_Galleries_Pictures AS gp
						LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
						WHERE gp.gallery_id = '".$slideshow."'
						ORDER BY gp.orders
					";
					$result_all_pics = $mdb2->query($all_pics);
					$row_all_pics = $result_all_pics->fetchRow();
					
					$tpl->assign('slide_pages', ceil($row_all_pics['allpics']/4)); 
					/*if ($azonos != 1) {
    					include_once 'modules/gallery.php';
    					$tpl->assign('page', $acttpl);

					} else {
    					$site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
    				}*/
				}
				if ($row_menu['video'] != 0) {
					$video = $row_menu['video'];

					$query_pics = "
						SELECT p.picture_id, p.realname, p.name, p.description, p.width, p.height
						FROM iShark_Galleries_Pictures AS gp
						LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
						WHERE gp.gallery_id = '".$video."'
						ORDER BY gp.orders
						LIMIT 4
					";
					$result_pics = $mdb2->query($query_pics);
					$tpl->assign('video', $result_pics->fetchAll());
					$tpl->assign('video_id', $video);
					
					$all_pics = "
						SELECT COUNT(p.picture_id) AS allpics
						FROM iShark_Galleries_Pictures AS gp
						LEFT JOIN iShark_Pictures AS p ON p.picture_id = gp.picture_id
						WHERE gp.gallery_id = '".$video."'
						ORDER BY gp.orders
					";
					$result_all_pics = $mdb2->query($all_pics);
					$row_all_pics = $result_all_pics->fetchRow();
					
					$tpl->assign('video_pages', ceil($row_all_pics['allpics']/4));
					/*if ($azonos != 1) {
    					include_once 'modules/gallery.php';
    					$tpl->assign('page', $acttpl);

					} else {
    					$site_errors[] = array('text' => $locale->get('iblocks', 'error_no_permission'), 'link' => 'javascript:history.back(-1)');
    				}*/
				}
			}
		}
	}
}
else {
	//fooldal
	$q = "
		SELECT * FROM iShark_Contents
		WHERE content_id = 53
	";
	$res = $mdb2->query($q);
	$tpl->assign('main_cont', $res->fetchRow());
}

//partnerek
$q = "
	SELECT c.content_id, c.title
	FROM iShark_Contents_Category AS cc
	LEFT JOIN iShark_Contents AS c ON c.content_id = cc.content_id
	WHERE cc.category_id = 4
	LIMIT 5
";
$res = $mdb2->query($q);
$tpl->assign('partnerek', $res->fetchAll());

//latnivalok
$q = "
	SELECT c.content_id, c.title
	FROM iShark_Contents_Category AS cc
	LEFT JOIN iShark_Contents AS c ON c.content_id = cc.content_id
	WHERE cc.category_id = 5
	LIMIT 5
";
$res = $mdb2->query($q);
$tpl->assign('latnivalok', $res->fetchAll());


//ha engedelyezve van a felhasznaloknak a regisztracio, akkor kirakjuk a block-ot
/*if (!empty($_SESSION['site_userlogin'])) {
	include_once 'modules/block_account.php';
}*/

//ha engedelyezve van a shop, es a felhasznalok is vasarolhatnak
/*if (isModule('shop', 'index') && isset($_SESSION['site_shop_userbuy']) && $_SESSION['site_shop_userbuy'] == 1) {
	include_once 'modules/shop_basket_block.php';
	include_once 'modules/shop_search_block.php';
	include_once 'modules/shop_newprods_block.php';
}*/

$tpl->assign('javascripts',   $javascripts);
$tpl->assign('bodyonload',    $bodyonload);
$tpl->assign('css',           $css);
$tpl->assign('ajax',          $ajax);
$tpl->assign('site_warnings', $site_warnings);
$tpl->assign('site_errors',   $site_errors);
$tpl->assign('site_success',  $site_success);
//$banners = get_banners2();
//$tpl->assign("banners", get_banners2());

//meta tag-ek
//if (!empty($mid)) {
 //   $tpl->assign('meta_tags', getMetaTags($mid));
//} else {
//    $tpl->assign('meta_tags', getMetaTags());
//}

//ha van shopunk csak, akkor hasznaljuk ezt a reszt
if (isModule('shop', 'index') && !empty($_SESSION['site_shop_is_breadcrumb'])) {
	$tpl->assign('shop_breadcrumb', $shop_breadcrumb->getArray());
}

//ha van aprohirdetesunk csak, akkor hasznaljuk ezt a reszt
if (isModule('classifieds', 'index') && !empty($_SESSION['site_class_is_breadcrumb'])) {
	$tpl->assign('class_breadcrumb', $class_breadcrumb->getArray());
}



$locale->assignToSmarty('locale');
$tpl->display("index.tpl");

//website statisztika
/*if (isModule('stat', 'admin') && !empty($statdoc)) {
	include_once 'phpOpenTracker.php';

	// log access
	phpOpenTracker::log(
		array(
			'document' => $statdoc
			)
		);
}*/

?>