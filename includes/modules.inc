<?php

$i = 0;

/**
 * adminisztracios oldalhoz tartozo modulok
 */
$modules[$i]['name'] = "Menüpontok";
$modules[$i]['desc'] = "Menüpontok szerkesztése";
$modules[$i]['file'] = "menus";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'add' => 'felvitel', 
	'mod' => 'módosítás', 
	'del' => 'törlés', 
	'lst' => 'listázás', 
	'ord' => 'rendezés', 
	'act' => 'aktiválás'
);
$i++;

$modules[$i]['name'] = "Modulok";
$modules[$i]['desc'] = "Modulok kezelésére szolgáló modul";
$modules[$i]['file'] = "modules";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'act' => 'aktiválás', 
	'lst' => 'listázás', 
	'ins' => 'telepítés/eltávolítás'
);
$i++;

$modules[$i]['name'] = "Jogosultságok";
$modules[$i]['desc'] = "Jogosultságok kezelésére szolgáló modul";
$modules[$i]['file'] = "rights";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'rights'     => 'listázás',
	'rights_add' => 'felvitel', 
	'rights_mod' => 'módosítás', 
	'rights_del' => 'törlés'
);
$i++;


$modules[$i]['name'] = "Jogosultságok 2";
$modules[$i]['desc'] = "Jogosultságok kezelésére szolgáló modul2 ";
$modules[$i]['file'] = "rights2";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'rights2'     => 'listázás',
	'rights2_mod' => 'módosítás', 
);
$i++;



$modules[$i]['name'] = "Rendszer";
$modules[$i]['desc'] = "A rendszer alapbeállításai végezhetõek el itt";
$modules[$i]['file'] = "system";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'mod' => 'módosítás', 
	'lst' => 'listázás'
);
$i++;

$modules[$i]['name'] = "Nyelvek";
$modules[$i]['desc'] = "A nyelvi fájlok szerkesztésére van itt lehetõség";
$modules[$i]['file'] = "langs";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'langs'          => 'nyelvek kezelése',
    'langs_add_lang' => 'nyelv hozzáadása',
    'langs_mod_lang' => 'nyelv módosítása',
    'langs_del_lang' => 'nyelv törlése',
    'langs_export'   => 'nyelvi változók exportálása',
    'langs_import'   => 'nyelvi változók betöltése fájlból',
    'langs_add'      => 'modul hozzáadása',
	'langs_mod'      => 'modul módosítása', 
	'langs_del'      => 'modul törlése',
	'langs_w_lst'    => 'kifejezések listája',
	'langs_w_add'    => 'kifejezés hozzáadása',
	'langs_w_mod'    => 'kifejezés módosítása',
	'langs_w_del'    => 'kifejezés törlése',
	'search'         => 'keresés'
);
$i++;

$modules[$i]['name'] = "Csoportok";
$modules[$i]['desc'] = "A csoportok kezelésére szolgáló modul";
$modules[$i]['file'] = "groups";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'add' => 'felvitel', 
	'mod' => 'módosítás', 
	'del' => 'törlés', 
	'lst' => 'listázás'
);
$i++;

$modules[$i]['name'] = "Felhasználók";
$modules[$i]['desc'] = "A felhasználók kezelésére szolgáló modul";
$modules[$i]['file'] = "users";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'add'    => 'felvitel', 
	'mod'    => 'módosítás', 
	'del'    => 'törlés', 
	'act'    => 'aktiválás', 
	'lst'    => 'listázás',
	'search' => 'keresés'
);
$i++;

$modules[$i]['name'] = "Tartalomszerkesztõ";
$modules[$i]['desc'] = "Szöveges tartalmak szerkesztésére szolgáló modul";
$modules[$i]['file'] = "contents";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'news'            => 'hírek kezelése',
	'news_act'        => 'hír aktiválása',
	'news_add'        => 'hír hozzáadása',
	'news_mod'        => 'hír módosítása',
	'news_del'        => 'hír törlése',
	'news_restore'    => 'hír verzió visszaállítása',
	'news_show'       => 'hír verzió megtekintése',
	'content'         => 'tartalom kezelése',
	'content_act'     => 'tartalom aktiválása',
	'content_add'     => 'tartalom hozzáadása',
	'content_mod'     => 'tartalom módosítása',
	'content_del'     => 'tartalom törlése',
	'content_restore' => 'tartalom verzió visszaállítása',
	'content_show'    => 'tartalom verzió megtekintése',
	'category'        => 'kategória kezelése',
	'category_act'    => 'kategória aktiválása',
	'category_add'    => 'kategória hozzáadása',
	'category_mod'    => 'kategória módosítása',
	'category_del'    => 'kategória törlése',
	'sendnews'        => 'hírküldés',
	'sendnews_act'    => 'hír aktiválása',
	'sendnews_del'    => 'hír törlése',
	'sendnews_show'   => 'hír megtekintése',
	'mtinews'         => 'MTI hírek',
	'mtinews_act'     => 'MTI hír aktiválása',
	'mtinews_del'     => 'MTI hír törlése',
	'mtinews_show'    => 'MTI hír megtekintése',
	'mti'             => 'MTI hírek',
	'mti_act'         => 'MTI hír aktiválása',
	'mti_mod'         => 'MTI hír módosítása',
	'mti_del'         => 'MTI hír törlése'
);
$i++;

$modules[$i]['name'] = "Modul beállítások";
$modules[$i]['desc'] = "Fõoldali modulok beállítására szolgáló modul";
$modules[$i]['file'] = "settings";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'mod'   => 'módosítás', 
	'lst'   => 'listázás'
);
$i++;

//hirlevel
$modules[$i]['name'] = "Hírlevél";
$modules[$i]['desc'] = "Hírlevél szerkesztõ modul";
$modules[$i]['file'] = "newsletter";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'letter'      => 'hírlevelek',
    'letter_add'  => 'hírlevél hozzáadása',
    'letter_mod'  => 'hírlevél módosítása',
    'letter_del'  => 'hírlevél törlése',
    'letter_slst' => 'küldési adatok listája',
    'letter_send' => 'hírlevél küldése',
    'groups'      => 'hírlevél csoportok kezelése',
    'groups_add'  => 'csoport hozzáadása',
    'groups_mod'  => 'csoport módosítása',
    'groups_del'  => 'csoport törlése',
    'users'       => 'hírlevél felhasználók kezelése',
    'users_add'   => 'felhasználó hozzáadása',
    'users_mod'   => 'felhasználó módosítása',
    'users_del'   => 'felhasználó törlése',
    'users_act'   => 'felhasználó aktiválása'
);
$i++;
//hirlevel vege

//rendszernaplo
$modules[$i]['name'] = "Rendszernapló";
$modules[$i]['desc'] = "Rendszeresemények nyomonkövetésére szolgáló modul";
$modules[$i]['file'] = "logs";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'logs'     => 'rendszernapló',
	'logs_lst' => 'listázás', 
	'logs_tru' => 'napló ürítés'
);
$i++;
//rendszernaplo vege

//szavazas
$modules[$i]['name'] = "Szavazás";
$modules[$i]['desc'] = "Szavazások karbantartására szolgáló modul";
$modules[$i]['file'] = "polls";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'lst'   => 'listázás', 
	'add'   => 'kérdés hozzáadás', 
	'mod'   => 'kérdés módosítás', 
	'del'   => 'törlés', 
	'act'   => 'aktiválás', 
	'res'   => 'eredmény lista'
);
$i++;
//szavazas vege

//letoltesvezerlo
$modules[$i]['name'] = "Letöltések";
$modules[$i]['desc'] = "Letöltések kezelésére szolgáló modul";
$modules[$i]['file'] = "downloads";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'downloads'     => 'letöltések kezelése',
	'downloads_lst' => 'listázás', 
	'downloads_add' => 'hozzáadás', 
	'downloads_mod' => 'átnevezés', 
	'downloads_upl' => 'feltöltés', 
	'downloads_act' => 'aktiválás', 
	'downloads_del' => 'törlés', 
	'downloads_ftp' => 'ftp feltöltés'
);
$i++;
//letoltesvezerlo vege

//galeria
$modules[$i]['name'] = "Galéria";
$modules[$i]['desc'] = "Galéria képek kezelésére szolgáló modul";
$modules[$i]['file'] = "gallery";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'gallery'      => 'képgaléria kezelése',
    'gallery_gadd' => 'képgaléria hozzáadása',
    'gallery_gmod' => 'képgaléria módosítása', 
    'gallery_gdel' => 'képgaléria törlése', 
    'gallery_act'  => 'képgaléria aktiválása', 
    'gallery_ftp'  => 'ftp képfeltöltés', 
    'gallery_upl'  => 'kép feltöltése', 
    'gallery_plst' => 'képek listázása', 
    'gallery_view' => 'kép megtekintése', 
    'gallery_pmod' => 'kép módosítása', 
    'gallery_pdel' => 'kép törlése',
    'video'        => 'videógaléria kezelése',
    'video_gadd'   => 'videógaléria hozzáadása',
    'video_gmod'   => 'videógaléria módosítása', 
    'video_gdel'   => 'videógaléria törlése', 
    'video_act'    => 'videógaléria aktiválása', 
    'video_ftp'    => 'ftp videófeltöltés', 
    'video_upl'    => 'videó feltöltése', 
    'video_plst'   => 'videók listázása', 
    'video_view'   => 'videó megtekintése', 
    'video_pmod'   => 'videó módosítása', 
    'video_pdel'   => 'videó törlése',
    'send'         => 'beküldött galériák kezelése',
    'send_gmod'    => 'beküldött galéria módosítása',
    'send_gdel'    => 'beküldött galéria törlése',
    'send_act'     => 'beküldött galéria aktiválása',
    'send_plst'    => 'beküldött képek listázása',
    'send_view'    => 'beküldött képek megtekintése',
    'send_pmod'    => 'beküldött kép módosítása',
    'send_pdel'    => 'beküldött kép törlése'
);
$i++;
//galeria vege

//banner
$modules[$i]['name'] = 'Bannerkezelõ';
$modules[$i]['desc'] = 'Bannerek, reklámcsíkok kezelésére szolgáló modul';
$modules[$i]['file'] = 'banners';
$modules[$i]['ext']  = '.php';
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = 'admin';
$modules[$i]['acts'] = array(
    'banners'       => 'bannerek kezelése',
    'banners_oadd'  => 'bannertulaj felvitel',
    'banners_omod'  => 'bannertulaj módosítás',
    'banners_odel'  => 'bannertulaj törlés',
    'banners_badd'  => 'banner felvitel',
    'banners_bmod'  => 'banner módosítás',
    'banners_bdel'  => 'banner törlés',
    'banners_blst'  => 'bannerek listája',
    'banners_bacta' => 'banner aktiválás',
    'banners_bactm' => 'banner aktiválás módosítása',
    'banners_bactd' => 'aktiváció törlése'
);
$i++;
//banner vege

//esemenynaptar
$modules[$i]['name'] = "Eseménynaptár";
$modules[$i]['desc'] = "Eseménynaptár kezelésére szolgáló modul";
$modules[$i]['file'] = "calendar";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'calendar'     => 'eseménynaptár kezelése',
	'calendar_add' => 'esemény felvitele', 
	'calendar_mod' => 'esemény módosítása', 
	'calendar_del' => 'esemény törlése'
);
$i++;
//esemenynaptar vege

//keresokifejezsek
$modules[$i]['name'] = "Keresõkifejezések";
$modules[$i]['desc'] = "Keresõkifejezések kezelésére szolgáló modul";
$modules[$i]['file'] = "searchwords";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'searchwords'     => 'keresõkifejezések',
	'searchwords_mod' => 'módosítás', 
	'searchwords_lst' => 'listázás', 
);
$i++;
//keresokifejezesek vege

//shop
$modules[$i]['name'] = "Bolt";
$modules[$i]['desc'] = "Bolt kezelésére szolgáló modul";
$modules[$i]['file'] = "shop";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'categories'          => 'kategória listázás',
	'categories_add'      => 'kategória felvitel', 
	'categories_mod'      => 'kategória módosítás',  
	'categories_act'      => 'kategória aktiválás',
	'categories_del'      => 'kategória törlés',
	'categories_ord'      => 'kategória sorrend módosítás',
	'products'            => 'termékek listázása',
	'products_add'        => 'termék felvitel', 
	'products_mod'        => 'termék módosítás', 
	'products_act'        => 'termék aktiválás',
	'products_del'        => 'termék törlés',
	'products_ord'        => 'termék sorrend módosítás',
	'actions'             => 'akciók listája',
	'actions_add'         => 'akció felvitel',
	'actions_mod'         => 'akció módosítás',
	'actions_act'         => 'akció aktiválás',
	'actions_del'         => 'akció törlés',
	'groups'              => 'csoportok listázása',
	'groups_add'          => 'csoport felvitel',
	'groups_mod'          => 'csoport módosítás',
	'groups_act'          => 'csoport aktiválás',
	'groups_del'          => 'csoport törlés',
	'orders'              => 'élõ rendelések listázása',
	'orders_add'          => 'élõ rendeléshez termék hozzáadás',
	'orders_mod'          => 'élõ rendelések kezelése',
	'orders_finished'     => 'lezárt rendelések listázása',
	'orders_finished_mod' => 'lezárt rendelések kezelése',
	'search'              => 'keresés'
);
$i++;
//shop vege

//partnerkezelo
$modules[$i]['name'] = "Partnerkezelõ";
$modules[$i]['desc'] = "Partnerek kezelését segítõ modul";
$modules[$i]['file'] = "partners";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'partners'	       => 'partnerek listája',
	'partners_add'     => 'partner hozzáadása',
	'partners_mod'     => 'partner módosítása',
	'partners_del'	   => 'partner törlése',
	'partners_act'     => 'partner aktiválása',
	'groups'           => 'partnercsoportok listája',
	'groups_add'       => 'csoport hozzáadása',
	'groups_mod'       => 'csoport módosítása',
	'groups_del'       => 'csoport törlése',
	'prices'           => 'árlisták kezelése',
	'prices_add'	   => 'árlista hozzáadása',
	'prices_mod'	   => 'arlista modositasa',
	'prices_del'	   => 'árlista törlése',
	'discounts'		   => 'akciók listája',
	'discounts_add'    => 'akció hozzáadása',
	'discounts_mod'    => 'akció módosítása',
	'discounts_del'    => 'akció törlése',
	'news'			   => 'hírek listája',
	'news_add'		   => 'hír hozzáadása',
	'news_mod'		   => 'hír módosítása',
	'news_del'		   => 'hír törlése',
	'mailing'		   => 'levelezés',
	'mailing_add'	   => 'levél létrehozása',
	'mailing_mod'	   => 'levél módosítása',
	'mailing_del'	   => 'levél törlése',
	'mailing_send'	   => 'levél küldése',
	'mailing_sendinfo' => 'küldési adatok lekérdezése'
);
$i++;
// partnerkezelo vege

// Maps
$modules[$i]['name'] = "Térképek";
$modules[$i]['desc'] = "Térképek kezelésére szolgáló modul";
$modules[$i]['file'] = "maps";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'resellers'		=> 'Viszonteladók listája',
	'resellers_add' => 'viszonteladó hozzáadása',
	'resellers_mod' => 'viszonteladó módosítása',
	'resellers_del' => 'viszonteladó törlése',
	'agencies'      => 'területi képviselõk',
	'agencies_add'  => 'területi képviselõ hozzáasása',
	'agencies_mod'  => 'területi képviselõ módosítása',
	'agencies_del'  => 'területi képviselõ törlése'
);
$i++;
// Maps vége

//rss
$modules[$i]['name'] = "RSS olvasó";
$modules[$i]['desc'] = "RSS olvasó beállítására szolgáló modul";
$modules[$i]['file'] = "rss";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
		'add'   => 'felvitel', 
		'mod'   => 'módosítás', 
		'del'   => 'törlés', 
		'lst'   => 'listázás',
		'act'   => 'aktiválás'
	);
$i++;
//rss

//fooldali osszerako
$modules[$i]['name'] = "Fõoldal összerakó beállítások";
$modules[$i]['desc'] = "Fõoldali összerakó beállítására szolgáló modul";
$modules[$i]['file'] = "builder";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'builder'   	=> 'Fõoldal összerakó',
	'builder_add'   => 'felvitel',
	'builder_del'   => 'törlés',
	'builder_act'   => 'aktiválás',
	'builder_ins'   => 'telepítés', 
	'builder_unins' => 'eltávolítás', 
	'builder_mod'   => 'módosítás', 
	'builder_lst'   => 'listázás',
	'builder_pos'   => 'pozíció módisítás',
	'builder_colpos'=> 'oszlop pozíció módisítás'
);
$i++;
//fooldali osszerako vege

//statisztika
$modules[$i]['name'] = "Statisztika";
$modules[$i]['desc'] = "Weboldal statisztika";
$modules[$i]['file'] = "stat";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'total'   	  => 'teljes statisztika',
	'total_lst'   => 'teljes statisztika listázás',
	'current'     => 'jelenleg aktív látogatók',
	'current_lst' => 'jelenleg aktív látogatók listája'
);
$i++;
//statisztika vege

//aprohirdetes
$modules[$i]['name'] = "Apróhirdetés";
$modules[$i]['desc'] = "Apróhirdetés beállítására szolgáló modul";
$modules[$i]['file'] = "classifieds";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'categories'     => 'kategóriák',
	'categories_add' => 'kategória hozzáadása',
	'categories_mod' => 'kategória módosítása',
	'categories_del' => 'kategória törlése',
	'categories_act' => 'kategória aktiválása',
	'categories_ord' => 'kategória sorbarendezése',
	'adverts'        => 'hirdetés',
	'adverts_add'    => 'hirdetés hozzáadása',
	'adverts_mod'    => 'hirdetés módosítása',
	'adverts_del'    => 'hirdetés törlése',
	'adverts_act'    => 'hirdetés aktiválása',
	'search'         => 'keresés'
);
$i++;
//aprohirdetes

//cimkek
$modules[$i]['name'] = "Cimkék";
$modules[$i]['desc'] = "Cimkék beállítására szolgáló modul";
$modules[$i]['file'] = "tags";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'tags'     => 'cimkék',
	'tags_add' => 'cimke hozzáadása',
	'tags_mod' => 'cimke módosítása',
	'tags_del' => 'cimke törlése'
);
$i++;
//cimkek vege

/**
 * fooldalhoz tartozo alapmodulok
 */
$modules[$i]['name'] = "Felhasználók";
$modules[$i]['desc'] = "Felhasználói adatok kezelése";
$modules[$i]['file'] = "account";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'account_add'    => 'felvitel', 
	'account_mod'    => 'módosítás', 
	'account_lst'    => 'listázás', 
	'account_del'    => 'törlés', 
	'account_in'     => 'belépés', 
	'account_out'    => 'kilépés', 
	'account_act'    => 'elfelejtett jelszó', 
	'account_lstact' => 'elfelejtett jelszó aktiválás'
);
$i++;

//oldalterkep
$modules[$i]['name'] = "Oldaltérkép";
$modules[$i]['desc'] = "Oldaltérkép megjelenítésére szolgáló modul";
$modules[$i]['file'] = "sitemap";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//oldalterkep vege

//uzenetkuldes
$modules[$i]['name'] = "Üzenetküldés";
$modules[$i]['desc'] = "Üzenetküldés beállítására szolgáló modul";
$modules[$i]['file'] = "feedback";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'feedback_lst' => 'üzenetküldés'
);
$i++;
//uzenetkuldes vege

//vendegkonyv
$modules[$i]['name'] = "Vendégkönyv";
$modules[$i]['desc'] = "Vendégkönyv beállítására szolgáló modul";
$modules[$i]['file'] = "guestbook";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'guestbook'     => 'vendégkönyv',
	'guestbook_lst' => 'listázás', 
	'guestbook_add' => 'hozzáadás', 
	'guestbook_ena' => 'engedélyezés/tiltás', 
	'guestbook_del' => 'törlés', 
	'guestbook_rep' => 'válasz'
);
$i++;
//vendegkonyv vege

//letolteskezelo
$modules[$i]['name'] = "Letöltések";
$modules[$i]['desc'] = "Fõoldali letöltések kezelésére szolgáló modul";
$modules[$i]['file'] = "downloads";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//letolteskezelo vege

//szavazas
$modules[$i]['name'] = "Szavazás";
$modules[$i]['desc'] = "Fõoldali szavazások kezelésére szolgáló modul";
$modules[$i]['file'] = "polls";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//szavazas vege

// forum
$modules[$i]['name'] = "Fórum";
$modules[$i]['desc'] = "Fórum modul";
$modules[$i]['file'] = "forum";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'       => 'listázás', 
	'fadd'      => 'fórum létrehozása',
	'fdel'      => 'fórum törlése',
	'add'       => 'téma nyitása', 
	'del'       => 'téma törlése', 
	'act'       => 'téma aktiválás', 
	'mod'       => 'téma módosítása', 
	'pub'       => 'téma extra beállítások', 
	'modmsg'    => 'hozzászólás módosítása', 
	'delmsg'    => 'hozzászólás törlése', 
	'block'     => 'hozzászólás tiltása', 
	'censor'	=> 'Cenzúrázott kifejezések listája', 
	'censoradd' => 'Cenzúrázott kifejezés hozzáadása', 
	'censormod' => 'Cenzúrázott kifejezés módosítása', 
	'censordel' => 'Cenzúrázott kifejezés törlése'
);
$i++;
// forum vege

//kepgaleria
$modules[$i]['name'] = "Galéria";
$modules[$i]['desc'] = "Galéria modul";
$modules[$i]['file'] = "gallery";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'     => 'listázás', 
	'lspics'  => 'képek listázása', 
	'viewpic' => 'képek megtekintése',
	'dwn'     => 'videó letöltése'
);
$i++;
//kepgaleria vege

//hirek
$modules[$i]['name'] = "Hírek";
$modules[$i]['desc'] = "Hírek";
$modules[$i]['file'] = "news";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'  => 'listázás',
	'show' => 'hír mutatása'
);
$i++;
//hirek vege

//shop
$modules[$i]['name'] = "Bolt";
$modules[$i]['desc'] = "Bolt kezelésére szolgáló modul";
$modules[$i]['file'] = "shop";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'    => 'listázás',
	'bsk'    => 'kosár használata',
	'ebsk'   => 'kosár ürítése',
	'prd'    => 'termék adatlap',
	'ord'    => 'megrendelés',
	'reg'    => 'regisztráció/belépés',
	'del'    => 'törlés',
	'ajax'   => 'ajax használata',
	'dwn'    => 'dokumentáció letöltése',
	'act'    => 'regisztráció nélküli vásárlás aktiválása',
	'addr'   => 'lakcím hozzáadása',
	'sea'    => 'részletes keresés',
	'ser'    => 'keresés találati lista',
	'delcom' => 'megjegyzés törlése'
);
$i++;
//shop vege

//aprohirdetes
$modules[$i]['name'] = "Apróhirdetés";
$modules[$i]['desc'] = "Apróhirdetés modul";
$modules[$i]['file'] = "classifieds";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'classifieds_lst' => 'listázás',
	'classifieds_add' => 'hirdetés feladása',
	'classifieds_mod' => 'hirdetés módosítása',
	'classifieds_del' => 'hirdetés törlése',
	'classifieds_act' => 'hirdetés aktiválása'
);
$i++;
//aprohirdetes

//ajanlo
$modules[$i]['name'] = "Ajánló";
$modules[$i]['desc'] = "Ajánló modul";
$modules[$i]['file'] = "recommend";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
);
$i++;
//ajanlo vege

//hirkuldes
$modules[$i]['name'] = "Hírküldés";
$modules[$i]['desc'] = "Hírküldés modul";
$modules[$i]['file'] = "sendnews";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'sendnews_upl' => 'hír küldése'
);
$i++;
//hirkuldes vege

//rss
$modules[$i]['name'] = "RSS olvasó";
$modules[$i]['desc'] = "RSS olvasó fõoldali része";
$modules[$i]['file'] = "rss";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'   => 'listázás'
);
$i++;
//rss

//megjegyzes
$modules[$i]['name'] = "Megjegyzések";
$modules[$i]['desc'] = "Megjegyzések kezelése a moduloknál";
$modules[$i]['file'] = "comments";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'comments_lst' => 'listázás',
	'comments_add' => 'hozzászólás hozzáadása',
	'comments_mod' => 'hozzászólás módosítása',
	'comments_del' => 'hozzászólás törlése'
);
$i++;
//megjegyzes vege

//partnerek
$modules[$i]['name'] = "Partnerkezelõ";
$modules[$i]['desc'] = "Partnerek kezelését segítõ modul";
$modules[$i]['file'] = "partners";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
);
$i++;
//partnerek vege

//hirlevel
$modules[$i]['name'] = "Hírlevél";
$modules[$i]['desc'] = "Hírlevél fel- és leiratkozás kezelése";
$modules[$i]['file'] = "newsletter";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'newsletter_subs'   => 'feliratkozás', 
	'newsletter_unsubs' => 'leiratkozás'
);
$i++;
//hirlevel vege

//esemenynaptar
$modules[$i]['name'] = "Eseménynaptár";
$modules[$i]['desc'] = "Eseménynaptár fõoldali megjelenítésére szolgáló modul";
$modules[$i]['file'] = "calendar";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//esemenynaptar vege

/**
 * egyeb, csak az adott projecthez tartozo modulok
 */
include_once 'modules.project.inc';

?>
