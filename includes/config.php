<?php

    require_once 'config_paths.php';
	require_once 'PEAR.php';
	require_once 'config_mdb2.php';

	//SESSION MDB2-vel.
	require_once 'session.php';
	session_name("iShark");
	session_start();

	//leellenorizzuk a session-ok erteket, ha azt jelzik, hogy modosultak bizonyos ertekek, akkor futtatjuk a szukseges fuggvenyeket
	require_once 'functions.php';
	site_properties();

	//nyelv betoltese
	if ($_SESSION['site_multilang'] == 1) {
        if (isset($_GET['l'])) {
            $_SESSION['site_lang'] = $_GET['l'];
        } elseif(!isset($_SESSION['site_lang'])) {
            $_SESSION['site_lang'] = $_SESSION['site_deflang'];
        }
	} else {
		$_SESSION['site_lang'] = $_SESSION['site_deflang'];
	}

    //smarty beallitasok
	require_once $libs_dir.'/'.$smarty_dir.'/Smarty.class.php';
	$tpl = new Smarty();

	$tpl->template_dir = $theme_dir.'/'.$theme.'/templates';
	$tpl->compile_dir  = $theme_dir.'/'.$theme.'/templates_c';
	$tpl->cache_dir    = $theme_dir.'/'.$theme.'/cache';
	$tpl->config_dir   = $theme_dir.'/'.$theme.'/configs';
	$tpl->caching      = $_SESSION['site_cache']; //kikapcsoljuk a cache-t

	$tpl->assign('theme_dir',   $theme_dir.'/'.$theme);
	$tpl->assign('include_dir', $include_dir);
	$tpl->assign('libs_dir',    $libs_dir);
	$tpl->assign('sitename',    $_SESSION['site_sitename']);

	// Translator
	require_once 'config_translator.php';

    $locale->initSmarty($tpl);
    $tpl->assign_by_ref('locale_obj', $locale);

    $tpl->register_modifier('local_date', 'get_date');
    $locale->useArea('config');

	//Pager beallitasok
	$pagerOptions = array(
		'mode'    	=> 'Sliding', //Jumping vagy Sliding lehet meg
		'delta'   	=> 2,
		'perPage' 	=> $_SESSION['site_pager'],
		'altFirst'	=> $locale->get('config', 'pager_first'),
		'altPrev'	=> $locale->get('config', 'pager_prev'),
		'altNext'	=> $locale->get('config', 'pager_next'),
		'altLast'	=> $locale->get('config', 'pager_last'),
		'altPage'	=> '',
	);

	//jscalendar beallitasok
	$calendar_start = array(
		'baseURL'  => $libs_dir.'/jscalendar/',
		'styleCss' => 'calendar-blue2.css',
		'language' => 'hu',
		'image'    => array(
			'src'    => $libs_dir.'/jscalendar/img.gif',
			'border' => 0
		),
		'setup'    => array(
			'inputField'  => 'timer_start',
			'ifFormat'    => $locale->get('calendar_date_format'),
			'showsTime'   => true,
			'time24'      => true,
			'weekNumbers' => true,
			'showOthers'  => true
		)
	);
	$calendar_end = array(
        'baseURL'  => $libs_dir.'/jscalendar/',
        'styleCss' => 'calendar-blue2.css',
        'language' => 'hu',
        'image'    => array(
		    'src'      => $libs_dir.'/jscalendar/img.gif',
		    'border'   => 0
		),
		'setup'        => array(
		    'inputField'  => 'timer_end',
		    'ifFormat'    => $locale->get('calendar_date_format'),
		    'showsTime'   => true,
		    'time24'      => true,
		    'weekNumbers' => true,
		    'showOthers'  => true
		)
	);

	//hibauzenet beallitasa, ha engedelyezve van az uzenetek megjelenitese
	if (!isset($_SESSION['site_debug']) || (isset($_SESSION['site_debug']) && $_SESSION['site_debug'] == 1)) {
		//error reporting beallitasa
		ini_set('display_errors', 0);
		error_reporting(E_ALL);

		//pear hibauzenet beallitasa
		function handle_pear_error ($error_obj)
		{
			print '<pre><b>PEAR-Error</b><br />';
			echo $error_obj->getMessage().': '.$error_obj->getUserinfo();
			print '</pre>';
		}
		PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'handle_pear_error');
	} else {
		$tpl->error_reporting = 0;
		ini_set('display_errors', 0);
		error_reporting(0);

		//pear hibauzenet beallitasa - azert valami hibauzenetet csak irunk a luzernek
		function handle_pear_error ($error_obj)
		{
		    global $locale;
			echo $locale->get('error_system');
		}
		PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'handle_pear_error');
	}

	// Mail Queue Ba�ll�t�sok
    $mail_queue_db_options['type']       = 'mdb2';

    // Haszn�lt adatb�zis be�ll�t�sok
    $mail_queue_db_options['dsn']        =& $dsn;
    $mail_queue_db_options['mail_table'] = 'iShark_Mail_Queue';

    // here are the options for sending the messages themselves
    // these are the options needed for the Mail-Class, especially used for Mail::factory()
    $mail_queue_mail_options['driver']    = 'smtp';
    $mail_queue_mail_options['host']      = 'localhost';
    $mail_queue_mail_options['port']      = 25;
    $mail_queue_mail_options['localhost'] = 'localhost'; //optional Mail_smtp parameter
    $mail_queue_mail_options['auth']      = false;
    $mail_queue_mail_options['username']  = '';
    $mail_queue_mail_options['password']  = '';

?>
