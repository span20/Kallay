<?php

$i = 0;

/**
 * adminisztracios oldalhoz tartozo modulok
 */
$modules[$i]['name'] = "Men�pontok";
$modules[$i]['desc'] = "Men�pontok szerkeszt�se";
$modules[$i]['file'] = "menus";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'add' => 'felvitel', 
	'mod' => 'm�dos�t�s', 
	'del' => 't�rl�s', 
	'lst' => 'list�z�s', 
	'ord' => 'rendez�s', 
	'act' => 'aktiv�l�s'
);
$i++;

$modules[$i]['name'] = "Modulok";
$modules[$i]['desc'] = "Modulok kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "modules";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'act' => 'aktiv�l�s', 
	'lst' => 'list�z�s', 
	'ins' => 'telep�t�s/elt�vol�t�s'
);
$i++;

$modules[$i]['name'] = "Jogosults�gok";
$modules[$i]['desc'] = "Jogosults�gok kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "rights";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'rights'     => 'list�z�s',
	'rights_add' => 'felvitel', 
	'rights_mod' => 'm�dos�t�s', 
	'rights_del' => 't�rl�s'
);
$i++;


$modules[$i]['name'] = "Jogosults�gok 2";
$modules[$i]['desc'] = "Jogosults�gok kezel�s�re szolg�l� modul2 ";
$modules[$i]['file'] = "rights2";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'rights2'     => 'list�z�s',
	'rights2_mod' => 'm�dos�t�s', 
);
$i++;



$modules[$i]['name'] = "Rendszer";
$modules[$i]['desc'] = "A rendszer alapbe�ll�t�sai v�gezhet�ek el itt";
$modules[$i]['file'] = "system";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 1;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'mod' => 'm�dos�t�s', 
	'lst' => 'list�z�s'
);
$i++;

$modules[$i]['name'] = "Nyelvek";
$modules[$i]['desc'] = "A nyelvi f�jlok szerkeszt�s�re van itt lehet�s�g";
$modules[$i]['file'] = "langs";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'langs'          => 'nyelvek kezel�se',
    'langs_add_lang' => 'nyelv hozz�ad�sa',
    'langs_mod_lang' => 'nyelv m�dos�t�sa',
    'langs_del_lang' => 'nyelv t�rl�se',
    'langs_export'   => 'nyelvi v�ltoz�k export�l�sa',
    'langs_import'   => 'nyelvi v�ltoz�k bet�lt�se f�jlb�l',
    'langs_add'      => 'modul hozz�ad�sa',
	'langs_mod'      => 'modul m�dos�t�sa', 
	'langs_del'      => 'modul t�rl�se',
	'langs_w_lst'    => 'kifejez�sek list�ja',
	'langs_w_add'    => 'kifejez�s hozz�ad�sa',
	'langs_w_mod'    => 'kifejez�s m�dos�t�sa',
	'langs_w_del'    => 'kifejez�s t�rl�se',
	'search'         => 'keres�s'
);
$i++;

$modules[$i]['name'] = "Csoportok";
$modules[$i]['desc'] = "A csoportok kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "groups";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'add' => 'felvitel', 
	'mod' => 'm�dos�t�s', 
	'del' => 't�rl�s', 
	'lst' => 'list�z�s'
);
$i++;

$modules[$i]['name'] = "Felhaszn�l�k";
$modules[$i]['desc'] = "A felhaszn�l�k kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "users";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'add'    => 'felvitel', 
	'mod'    => 'm�dos�t�s', 
	'del'    => 't�rl�s', 
	'act'    => 'aktiv�l�s', 
	'lst'    => 'list�z�s',
	'search' => 'keres�s'
);
$i++;

$modules[$i]['name'] = "Tartalomszerkeszt�";
$modules[$i]['desc'] = "Sz�veges tartalmak szerkeszt�s�re szolg�l� modul";
$modules[$i]['file'] = "contents";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'news'            => 'h�rek kezel�se',
	'news_act'        => 'h�r aktiv�l�sa',
	'news_add'        => 'h�r hozz�ad�sa',
	'news_mod'        => 'h�r m�dos�t�sa',
	'news_del'        => 'h�r t�rl�se',
	'news_restore'    => 'h�r verzi� vissza�ll�t�sa',
	'news_show'       => 'h�r verzi� megtekint�se',
	'content'         => 'tartalom kezel�se',
	'content_act'     => 'tartalom aktiv�l�sa',
	'content_add'     => 'tartalom hozz�ad�sa',
	'content_mod'     => 'tartalom m�dos�t�sa',
	'content_del'     => 'tartalom t�rl�se',
	'content_restore' => 'tartalom verzi� vissza�ll�t�sa',
	'content_show'    => 'tartalom verzi� megtekint�se',
	'category'        => 'kateg�ria kezel�se',
	'category_act'    => 'kateg�ria aktiv�l�sa',
	'category_add'    => 'kateg�ria hozz�ad�sa',
	'category_mod'    => 'kateg�ria m�dos�t�sa',
	'category_del'    => 'kateg�ria t�rl�se',
	'sendnews'        => 'h�rk�ld�s',
	'sendnews_act'    => 'h�r aktiv�l�sa',
	'sendnews_del'    => 'h�r t�rl�se',
	'sendnews_show'   => 'h�r megtekint�se',
	'mtinews'         => 'MTI h�rek',
	'mtinews_act'     => 'MTI h�r aktiv�l�sa',
	'mtinews_del'     => 'MTI h�r t�rl�se',
	'mtinews_show'    => 'MTI h�r megtekint�se',
	'mti'             => 'MTI h�rek',
	'mti_act'         => 'MTI h�r aktiv�l�sa',
	'mti_mod'         => 'MTI h�r m�dos�t�sa',
	'mti_del'         => 'MTI h�r t�rl�se'
);
$i++;

$modules[$i]['name'] = "Modul be�ll�t�sok";
$modules[$i]['desc'] = "F�oldali modulok be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "settings";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'mod'   => 'm�dos�t�s', 
	'lst'   => 'list�z�s'
);
$i++;

//hirlevel
$modules[$i]['name'] = "H�rlev�l";
$modules[$i]['desc'] = "H�rlev�l szerkeszt� modul";
$modules[$i]['file'] = "newsletter";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'letter'      => 'h�rlevelek',
    'letter_add'  => 'h�rlev�l hozz�ad�sa',
    'letter_mod'  => 'h�rlev�l m�dos�t�sa',
    'letter_del'  => 'h�rlev�l t�rl�se',
    'letter_slst' => 'k�ld�si adatok list�ja',
    'letter_send' => 'h�rlev�l k�ld�se',
    'groups'      => 'h�rlev�l csoportok kezel�se',
    'groups_add'  => 'csoport hozz�ad�sa',
    'groups_mod'  => 'csoport m�dos�t�sa',
    'groups_del'  => 'csoport t�rl�se',
    'users'       => 'h�rlev�l felhaszn�l�k kezel�se',
    'users_add'   => 'felhaszn�l� hozz�ad�sa',
    'users_mod'   => 'felhaszn�l� m�dos�t�sa',
    'users_del'   => 'felhaszn�l� t�rl�se',
    'users_act'   => 'felhaszn�l� aktiv�l�sa'
);
$i++;
//hirlevel vege

//rendszernaplo
$modules[$i]['name'] = "Rendszernapl�";
$modules[$i]['desc'] = "Rendszeresem�nyek nyomonk�vet�s�re szolg�l� modul";
$modules[$i]['file'] = "logs";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'logs'     => 'rendszernapl�',
	'logs_lst' => 'list�z�s', 
	'logs_tru' => 'napl� �r�t�s'
);
$i++;
//rendszernaplo vege

//szavazas
$modules[$i]['name'] = "Szavaz�s";
$modules[$i]['desc'] = "Szavaz�sok karbantart�s�ra szolg�l� modul";
$modules[$i]['file'] = "polls";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'lst'   => 'list�z�s', 
	'add'   => 'k�rd�s hozz�ad�s', 
	'mod'   => 'k�rd�s m�dos�t�s', 
	'del'   => 't�rl�s', 
	'act'   => 'aktiv�l�s', 
	'res'   => 'eredm�ny lista'
);
$i++;
//szavazas vege

//letoltesvezerlo
$modules[$i]['name'] = "Let�lt�sek";
$modules[$i]['desc'] = "Let�lt�sek kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "downloads";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'downloads'     => 'let�lt�sek kezel�se',
	'downloads_lst' => 'list�z�s', 
	'downloads_add' => 'hozz�ad�s', 
	'downloads_mod' => '�tnevez�s', 
	'downloads_upl' => 'felt�lt�s', 
	'downloads_act' => 'aktiv�l�s', 
	'downloads_del' => 't�rl�s', 
	'downloads_ftp' => 'ftp felt�lt�s'
);
$i++;
//letoltesvezerlo vege

//galeria
$modules[$i]['name'] = "Gal�ria";
$modules[$i]['desc'] = "Gal�ria k�pek kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "gallery";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'gallery'      => 'k�pgal�ria kezel�se',
    'gallery_gadd' => 'k�pgal�ria hozz�ad�sa',
    'gallery_gmod' => 'k�pgal�ria m�dos�t�sa', 
    'gallery_gdel' => 'k�pgal�ria t�rl�se', 
    'gallery_act'  => 'k�pgal�ria aktiv�l�sa', 
    'gallery_ftp'  => 'ftp k�pfelt�lt�s', 
    'gallery_upl'  => 'k�p felt�lt�se', 
    'gallery_plst' => 'k�pek list�z�sa', 
    'gallery_view' => 'k�p megtekint�se', 
    'gallery_pmod' => 'k�p m�dos�t�sa', 
    'gallery_pdel' => 'k�p t�rl�se',
    'video'        => 'vide�gal�ria kezel�se',
    'video_gadd'   => 'vide�gal�ria hozz�ad�sa',
    'video_gmod'   => 'vide�gal�ria m�dos�t�sa', 
    'video_gdel'   => 'vide�gal�ria t�rl�se', 
    'video_act'    => 'vide�gal�ria aktiv�l�sa', 
    'video_ftp'    => 'ftp vide�felt�lt�s', 
    'video_upl'    => 'vide� felt�lt�se', 
    'video_plst'   => 'vide�k list�z�sa', 
    'video_view'   => 'vide� megtekint�se', 
    'video_pmod'   => 'vide� m�dos�t�sa', 
    'video_pdel'   => 'vide� t�rl�se',
    'send'         => 'bek�ld�tt gal�ri�k kezel�se',
    'send_gmod'    => 'bek�ld�tt gal�ria m�dos�t�sa',
    'send_gdel'    => 'bek�ld�tt gal�ria t�rl�se',
    'send_act'     => 'bek�ld�tt gal�ria aktiv�l�sa',
    'send_plst'    => 'bek�ld�tt k�pek list�z�sa',
    'send_view'    => 'bek�ld�tt k�pek megtekint�se',
    'send_pmod'    => 'bek�ld�tt k�p m�dos�t�sa',
    'send_pdel'    => 'bek�ld�tt k�p t�rl�se'
);
$i++;
//galeria vege

//banner
$modules[$i]['name'] = 'Bannerkezel�';
$modules[$i]['desc'] = 'Bannerek, rekl�mcs�kok kezel�s�re szolg�l� modul';
$modules[$i]['file'] = 'banners';
$modules[$i]['ext']  = '.php';
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = 'admin';
$modules[$i]['acts'] = array(
    'banners'       => 'bannerek kezel�se',
    'banners_oadd'  => 'bannertulaj felvitel',
    'banners_omod'  => 'bannertulaj m�dos�t�s',
    'banners_odel'  => 'bannertulaj t�rl�s',
    'banners_badd'  => 'banner felvitel',
    'banners_bmod'  => 'banner m�dos�t�s',
    'banners_bdel'  => 'banner t�rl�s',
    'banners_blst'  => 'bannerek list�ja',
    'banners_bacta' => 'banner aktiv�l�s',
    'banners_bactm' => 'banner aktiv�l�s m�dos�t�sa',
    'banners_bactd' => 'aktiv�ci� t�rl�se'
);
$i++;
//banner vege

//esemenynaptar
$modules[$i]['name'] = "Esem�nynapt�r";
$modules[$i]['desc'] = "Esem�nynapt�r kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "calendar";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'calendar'     => 'esem�nynapt�r kezel�se',
	'calendar_add' => 'esem�ny felvitele', 
	'calendar_mod' => 'esem�ny m�dos�t�sa', 
	'calendar_del' => 'esem�ny t�rl�se'
);
$i++;
//esemenynaptar vege

//keresokifejezsek
$modules[$i]['name'] = "Keres�kifejez�sek";
$modules[$i]['desc'] = "Keres�kifejez�sek kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "searchwords";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
    'searchwords'     => 'keres�kifejez�sek',
	'searchwords_mod' => 'm�dos�t�s', 
	'searchwords_lst' => 'list�z�s', 
);
$i++;
//keresokifejezesek vege

//shop
$modules[$i]['name'] = "Bolt";
$modules[$i]['desc'] = "Bolt kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "shop";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'categories'          => 'kateg�ria list�z�s',
	'categories_add'      => 'kateg�ria felvitel', 
	'categories_mod'      => 'kateg�ria m�dos�t�s',  
	'categories_act'      => 'kateg�ria aktiv�l�s',
	'categories_del'      => 'kateg�ria t�rl�s',
	'categories_ord'      => 'kateg�ria sorrend m�dos�t�s',
	'products'            => 'term�kek list�z�sa',
	'products_add'        => 'term�k felvitel', 
	'products_mod'        => 'term�k m�dos�t�s', 
	'products_act'        => 'term�k aktiv�l�s',
	'products_del'        => 'term�k t�rl�s',
	'products_ord'        => 'term�k sorrend m�dos�t�s',
	'actions'             => 'akci�k list�ja',
	'actions_add'         => 'akci� felvitel',
	'actions_mod'         => 'akci� m�dos�t�s',
	'actions_act'         => 'akci� aktiv�l�s',
	'actions_del'         => 'akci� t�rl�s',
	'groups'              => 'csoportok list�z�sa',
	'groups_add'          => 'csoport felvitel',
	'groups_mod'          => 'csoport m�dos�t�s',
	'groups_act'          => 'csoport aktiv�l�s',
	'groups_del'          => 'csoport t�rl�s',
	'orders'              => '�l� rendel�sek list�z�sa',
	'orders_add'          => '�l� rendel�shez term�k hozz�ad�s',
	'orders_mod'          => '�l� rendel�sek kezel�se',
	'orders_finished'     => 'lez�rt rendel�sek list�z�sa',
	'orders_finished_mod' => 'lez�rt rendel�sek kezel�se',
	'search'              => 'keres�s'
);
$i++;
//shop vege

//partnerkezelo
$modules[$i]['name'] = "Partnerkezel�";
$modules[$i]['desc'] = "Partnerek kezel�s�t seg�t� modul";
$modules[$i]['file'] = "partners";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'partners'	       => 'partnerek list�ja',
	'partners_add'     => 'partner hozz�ad�sa',
	'partners_mod'     => 'partner m�dos�t�sa',
	'partners_del'	   => 'partner t�rl�se',
	'partners_act'     => 'partner aktiv�l�sa',
	'groups'           => 'partnercsoportok list�ja',
	'groups_add'       => 'csoport hozz�ad�sa',
	'groups_mod'       => 'csoport m�dos�t�sa',
	'groups_del'       => 'csoport t�rl�se',
	'prices'           => '�rlist�k kezel�se',
	'prices_add'	   => '�rlista hozz�ad�sa',
	'prices_mod'	   => 'arlista modositasa',
	'prices_del'	   => '�rlista t�rl�se',
	'discounts'		   => 'akci�k list�ja',
	'discounts_add'    => 'akci� hozz�ad�sa',
	'discounts_mod'    => 'akci� m�dos�t�sa',
	'discounts_del'    => 'akci� t�rl�se',
	'news'			   => 'h�rek list�ja',
	'news_add'		   => 'h�r hozz�ad�sa',
	'news_mod'		   => 'h�r m�dos�t�sa',
	'news_del'		   => 'h�r t�rl�se',
	'mailing'		   => 'levelez�s',
	'mailing_add'	   => 'lev�l l�trehoz�sa',
	'mailing_mod'	   => 'lev�l m�dos�t�sa',
	'mailing_del'	   => 'lev�l t�rl�se',
	'mailing_send'	   => 'lev�l k�ld�se',
	'mailing_sendinfo' => 'k�ld�si adatok lek�rdez�se'
);
$i++;
// partnerkezelo vege

// Maps
$modules[$i]['name'] = "T�rk�pek";
$modules[$i]['desc'] = "T�rk�pek kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "maps";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'resellers'		=> 'Viszontelad�k list�ja',
	'resellers_add' => 'viszontelad� hozz�ad�sa',
	'resellers_mod' => 'viszontelad� m�dos�t�sa',
	'resellers_del' => 'viszontelad� t�rl�se',
	'agencies'      => 'ter�leti k�pvisel�k',
	'agencies_add'  => 'ter�leti k�pvisel� hozz�as�sa',
	'agencies_mod'  => 'ter�leti k�pvisel� m�dos�t�sa',
	'agencies_del'  => 'ter�leti k�pvisel� t�rl�se'
);
$i++;
// Maps v�ge

//rss
$modules[$i]['name'] = "RSS olvas�";
$modules[$i]['desc'] = "RSS olvas� be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "rss";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
		'add'   => 'felvitel', 
		'mod'   => 'm�dos�t�s', 
		'del'   => 't�rl�s', 
		'lst'   => 'list�z�s',
		'act'   => 'aktiv�l�s'
	);
$i++;
//rss

//fooldali osszerako
$modules[$i]['name'] = "F�oldal �sszerak� be�ll�t�sok";
$modules[$i]['desc'] = "F�oldali �sszerak� be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "builder";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'builder'   	=> 'F�oldal �sszerak�',
	'builder_add'   => 'felvitel',
	'builder_del'   => 't�rl�s',
	'builder_act'   => 'aktiv�l�s',
	'builder_ins'   => 'telep�t�s', 
	'builder_unins' => 'elt�vol�t�s', 
	'builder_mod'   => 'm�dos�t�s', 
	'builder_lst'   => 'list�z�s',
	'builder_pos'   => 'poz�ci� m�dis�t�s',
	'builder_colpos'=> 'oszlop poz�ci� m�dis�t�s'
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
	'total_lst'   => 'teljes statisztika list�z�s',
	'current'     => 'jelenleg akt�v l�togat�k',
	'current_lst' => 'jelenleg akt�v l�togat�k list�ja'
);
$i++;
//statisztika vege

//aprohirdetes
$modules[$i]['name'] = "Apr�hirdet�s";
$modules[$i]['desc'] = "Apr�hirdet�s be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "classifieds";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'categories'     => 'kateg�ri�k',
	'categories_add' => 'kateg�ria hozz�ad�sa',
	'categories_mod' => 'kateg�ria m�dos�t�sa',
	'categories_del' => 'kateg�ria t�rl�se',
	'categories_act' => 'kateg�ria aktiv�l�sa',
	'categories_ord' => 'kateg�ria sorbarendez�se',
	'adverts'        => 'hirdet�s',
	'adverts_add'    => 'hirdet�s hozz�ad�sa',
	'adverts_mod'    => 'hirdet�s m�dos�t�sa',
	'adverts_del'    => 'hirdet�s t�rl�se',
	'adverts_act'    => 'hirdet�s aktiv�l�sa',
	'search'         => 'keres�s'
);
$i++;
//aprohirdetes

//cimkek
$modules[$i]['name'] = "Cimk�k";
$modules[$i]['desc'] = "Cimk�k be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "tags";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "admin";
$modules[$i]['acts'] = array(
	'tags'     => 'cimk�k',
	'tags_add' => 'cimke hozz�ad�sa',
	'tags_mod' => 'cimke m�dos�t�sa',
	'tags_del' => 'cimke t�rl�se'
);
$i++;
//cimkek vege

/**
 * fooldalhoz tartozo alapmodulok
 */
$modules[$i]['name'] = "Felhaszn�l�k";
$modules[$i]['desc'] = "Felhaszn�l�i adatok kezel�se";
$modules[$i]['file'] = "account";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'account_add'    => 'felvitel', 
	'account_mod'    => 'm�dos�t�s', 
	'account_lst'    => 'list�z�s', 
	'account_del'    => 't�rl�s', 
	'account_in'     => 'bel�p�s', 
	'account_out'    => 'kil�p�s', 
	'account_act'    => 'elfelejtett jelsz�', 
	'account_lstact' => 'elfelejtett jelsz� aktiv�l�s'
);
$i++;

//oldalterkep
$modules[$i]['name'] = "Oldalt�rk�p";
$modules[$i]['desc'] = "Oldalt�rk�p megjelen�t�s�re szolg�l� modul";
$modules[$i]['file'] = "sitemap";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//oldalterkep vege

//uzenetkuldes
$modules[$i]['name'] = "�zenetk�ld�s";
$modules[$i]['desc'] = "�zenetk�ld�s be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "feedback";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'feedback_lst' => '�zenetk�ld�s'
);
$i++;
//uzenetkuldes vege

//vendegkonyv
$modules[$i]['name'] = "Vend�gk�nyv";
$modules[$i]['desc'] = "Vend�gk�nyv be�ll�t�s�ra szolg�l� modul";
$modules[$i]['file'] = "guestbook";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'guestbook'     => 'vend�gk�nyv',
	'guestbook_lst' => 'list�z�s', 
	'guestbook_add' => 'hozz�ad�s', 
	'guestbook_ena' => 'enged�lyez�s/tilt�s', 
	'guestbook_del' => 't�rl�s', 
	'guestbook_rep' => 'v�lasz'
);
$i++;
//vendegkonyv vege

//letolteskezelo
$modules[$i]['name'] = "Let�lt�sek";
$modules[$i]['desc'] = "F�oldali let�lt�sek kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "downloads";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//letolteskezelo vege

//szavazas
$modules[$i]['name'] = "Szavaz�s";
$modules[$i]['desc'] = "F�oldali szavaz�sok kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "polls";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array();
$i++;
//szavazas vege

// forum
$modules[$i]['name'] = "F�rum";
$modules[$i]['desc'] = "F�rum modul";
$modules[$i]['file'] = "forum";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'       => 'list�z�s', 
	'fadd'      => 'f�rum l�trehoz�sa',
	'fdel'      => 'f�rum t�rl�se',
	'add'       => 't�ma nyit�sa', 
	'del'       => 't�ma t�rl�se', 
	'act'       => 't�ma aktiv�l�s', 
	'mod'       => 't�ma m�dos�t�sa', 
	'pub'       => 't�ma extra be�ll�t�sok', 
	'modmsg'    => 'hozz�sz�l�s m�dos�t�sa', 
	'delmsg'    => 'hozz�sz�l�s t�rl�se', 
	'block'     => 'hozz�sz�l�s tilt�sa', 
	'censor'	=> 'Cenz�r�zott kifejez�sek list�ja', 
	'censoradd' => 'Cenz�r�zott kifejez�s hozz�ad�sa', 
	'censormod' => 'Cenz�r�zott kifejez�s m�dos�t�sa', 
	'censordel' => 'Cenz�r�zott kifejez�s t�rl�se'
);
$i++;
// forum vege

//kepgaleria
$modules[$i]['name'] = "Gal�ria";
$modules[$i]['desc'] = "Gal�ria modul";
$modules[$i]['file'] = "gallery";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'     => 'list�z�s', 
	'lspics'  => 'k�pek list�z�sa', 
	'viewpic' => 'k�pek megtekint�se',
	'dwn'     => 'vide� let�lt�se'
);
$i++;
//kepgaleria vege

//hirek
$modules[$i]['name'] = "H�rek";
$modules[$i]['desc'] = "H�rek";
$modules[$i]['file'] = "news";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'  => 'list�z�s',
	'show' => 'h�r mutat�sa'
);
$i++;
//hirek vege

//shop
$modules[$i]['name'] = "Bolt";
$modules[$i]['desc'] = "Bolt kezel�s�re szolg�l� modul";
$modules[$i]['file'] = "shop";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'    => 'list�z�s',
	'bsk'    => 'kos�r haszn�lata',
	'ebsk'   => 'kos�r �r�t�se',
	'prd'    => 'term�k adatlap',
	'ord'    => 'megrendel�s',
	'reg'    => 'regisztr�ci�/bel�p�s',
	'del'    => 't�rl�s',
	'ajax'   => 'ajax haszn�lata',
	'dwn'    => 'dokument�ci� let�lt�se',
	'act'    => 'regisztr�ci� n�lk�li v�s�rl�s aktiv�l�sa',
	'addr'   => 'lakc�m hozz�ad�sa',
	'sea'    => 'r�szletes keres�s',
	'ser'    => 'keres�s tal�lati lista',
	'delcom' => 'megjegyz�s t�rl�se'
);
$i++;
//shop vege

//aprohirdetes
$modules[$i]['name'] = "Apr�hirdet�s";
$modules[$i]['desc'] = "Apr�hirdet�s modul";
$modules[$i]['file'] = "classifieds";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'classifieds_lst' => 'list�z�s',
	'classifieds_add' => 'hirdet�s felad�sa',
	'classifieds_mod' => 'hirdet�s m�dos�t�sa',
	'classifieds_del' => 'hirdet�s t�rl�se',
	'classifieds_act' => 'hirdet�s aktiv�l�sa'
);
$i++;
//aprohirdetes

//ajanlo
$modules[$i]['name'] = "Aj�nl�";
$modules[$i]['desc'] = "Aj�nl� modul";
$modules[$i]['file'] = "recommend";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
);
$i++;
//ajanlo vege

//hirkuldes
$modules[$i]['name'] = "H�rk�ld�s";
$modules[$i]['desc'] = "H�rk�ld�s modul";
$modules[$i]['file'] = "sendnews";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'sendnews_upl' => 'h�r k�ld�se'
);
$i++;
//hirkuldes vege

//rss
$modules[$i]['name'] = "RSS olvas�";
$modules[$i]['desc'] = "RSS olvas� f�oldali r�sze";
$modules[$i]['file'] = "rss";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'lst'   => 'list�z�s'
);
$i++;
//rss

//megjegyzes
$modules[$i]['name'] = "Megjegyz�sek";
$modules[$i]['desc'] = "Megjegyz�sek kezel�se a modulokn�l";
$modules[$i]['file'] = "comments";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'comments_lst' => 'list�z�s',
	'comments_add' => 'hozz�sz�l�s hozz�ad�sa',
	'comments_mod' => 'hozz�sz�l�s m�dos�t�sa',
	'comments_del' => 'hozz�sz�l�s t�rl�se'
);
$i++;
//megjegyzes vege

//partnerek
$modules[$i]['name'] = "Partnerkezel�";
$modules[$i]['desc'] = "Partnerek kezel�s�t seg�t� modul";
$modules[$i]['file'] = "partners";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
);
$i++;
//partnerek vege

//hirlevel
$modules[$i]['name'] = "H�rlev�l";
$modules[$i]['desc'] = "H�rlev�l fel- �s leiratkoz�s kezel�se";
$modules[$i]['file'] = "newsletter";
$modules[$i]['ext']  = ".php";
$modules[$i]['dact'] = 0;
$modules[$i]['type'] = "index";
$modules[$i]['acts'] = array(
	'newsletter_subs'   => 'feliratkoz�s', 
	'newsletter_unsubs' => 'leiratkoz�s'
);
$i++;
//hirlevel vege

//esemenynaptar
$modules[$i]['name'] = "Esem�nynapt�r";
$modules[$i]['desc'] = "Esem�nynapt�r f�oldali megjelen�t�s�re szolg�l� modul";
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
