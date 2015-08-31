<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
   die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//modul neve
$module_name = "system";
$locale->useArea("admin_".$module_name);

//ezek az elfogadhato muveleti hivasok ($_REQUEST['act'])
$is_act = array('mod');

//ezek az elfogadhato almuveleti hivasok ($_REQUEST['type'])
$is_type = array('sys', 'cont', 'mce', 'dwn', 'gal', 'ban', 'sho', 'partners', 'builder', 'stat', 'class');

//jogosultsag ellenorzes
if (isset($_REQUEST['act']) && in_array($_REQUEST['act'], $is_act)) {
	$act = $_REQUEST['act'];
} else {
	$act = "lst";
}
if (!check_perm($act, NULL, 1, 'system')) {
	$acttpl = 'error';
	$tpl->assign('errormsg', $locale->get('error_no_permission'));
	return;
}

if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], $is_type)) {
	require_once 'HTML/QuickForm.php';
	require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

	$form =& new HTML_QuickForm('frm_system', 'post', 'admin.php?p=system');

	$form->setRequiredNote($locale->get('form_required_note'));

	$form->addElement('header', 'system', $locale->get('form_header'));
	$form->addElement('hidden', 'act',    'mod');
	$form->addElement('hidden', 'type',   $_REQUEST['type']);

	/**
	 * a rendszer alapbeallitasai
	 */
	if ($_REQUEST['type'] == "sys") {
		$lang_title = $locale->get('system_title');

		//oldal neve
		$form->addElement('text', 'sitename', $locale->get('system_field_sitename'));

		//oldal cime
		$form->addElement('text', 'sitehttp', $locale->get('system_field_sitehttp'));

		//meta description
		$form->addElement('text', 'description', $locale->get('system_field_sitedesc'));

		//meta keywords
		$form->addElement('text', 'keywords', $locale->get('system_field_sitekeywords'));

		//module kimenet kivalasztasa
		$output = array();
		$output[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_smarty'), '1');
		$output[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_xsl'),  '0');
		$form->addGroup($output, 'output', $locale->get('system_field_output'));

		//kimeno levelek feladoja
		$form->addElement('text', 'sitemail', $locale->get('system_field_mail'));

		//talalat/oldal
		$form->addElement('text', 'pager', $locale->get('system_field_pager'));

		//jelszo minimalis hossza
		$form->addElement('text', 'minpass', $locale->get('system_field_minpass'));

		//cache-eles hasznalata
		$cache = array();
		$cache[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_yes'), '1');
		$cache[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_no'),  '0');
		$form->addGroup($cache, 'cache', $locale->get('system_field_cache'));

		//hibauzenetek kiirasa az oldalon
		$debug = array();
		$debug[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_yes'), '1');
		$debug[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_no'),  '0');
		$form->addGroup($debug, 'debug', $locale->get('system_field_debug'));

		//felhasznalok regisztralhatnak-e az oldalon
		$userlogin = array();
		$userlogin[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_yes'), '1');
		$userlogin[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_no'),  '0');
		$form->addGroup($userlogin, 'userlogin',  $locale->get('system_field_usrlogin'));

		//csoportok listaja a kiemelt csoporthoz
		$query = "
			SELECT group_id, group_name
			FROM iShark_Groups
			ORDER BY group_name
		";
		$result =& $mdb2->query($query);
		if ($result->numRows() > 0) {
			$form->addElement('select', 'prefgroup', $locale->get('system_field_prefgroup'), $result->fetchAll('', $rekey = true));
		}

		//egy vagy tobbnyelvu
		$form->addElement('select', 'multilang', $locale->get('system_field_multilang'), array($locale->get('system_field_onelang'), $locale->get('system_field_morelang')));

		//alapertelmezett nyelv
		$form->addElement('select', 'deflang', $locale->get('system_field_deflang'), $locale->getLocales());

		//alapertelmezett datumformatum
		$form->addElement('text', 'dateformat', $locale->get('system_field_dateformat'));

		//letrehozhato csoportok szama
		$form->addElement('text', 'groupnum', $locale->get('system_field_groupnum'));

		//loggolas engedelyezese
		$logging = array();
		$logging[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_yes'), '1');
		$logging[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('system_field_no'),  '0');
		$form->addGroup($logging, 'logging', $locale->get('system_field_logging'));

		//lekerdezzuk a config tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$form->setDefaults(array(
				'sitename'    => $row['sitename'],
				'pager'		  => $row['pager'],
				'minpass'     => $row['minpass'],
				'cache'       => $row['cache'],
				'debug'       => $row['debug'],
				'multilang'   => $row['multilang'],
				'deflang'     => $row['deflang'],
				'userlogin'   => $row['userlogin'],
				'dateformat'  => $row['dateformat'],
				'groupnum'    => $row['groupnum'],
				'sitemail'    => $row['sitemail'],
				'sitehttp'    => $row['sitehttp'],
				'logging'     => $row['is_logging'],
				'prefgroup'   => $row['sys_prefgroup'],
				'description' => $row['sys_meta_description'],
				'output'      => $row['sys_output'],
				'keywords'    => $row['sys_meta_keywords']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('sitename',   $locale->get('system_error_sitename'),   'required');
		$form->addRule('sitehttp',   $locale->get('system_error_sitehttp'),   'required');
		$form->addRule('sitemail',   $locale->get('system_error_sitemail1'),  'required');
		$form->addRule('sitemail',   $locale->get('system_error_sitemail2'),  'email');
		$form->addRule('pager',      $locale->get('system_error_pager1'),     'required');
		$form->addRule('pager',      $locale->get('system_error_pager2'),     'rangelength', array(1, 3));
		$form->addRule('pager',      $locale->get('system_error_pager3'),     'numeric');
		$form->addRule('minpass',    $locale->get('system_error_minpass1'),   'required');
		$form->addRule('minpass',    $locale->get('system_error_minpass2'),   'rangelength', array(1, 3));
		$form->addRule('minpass',    $locale->get('system_error_minpass3'),   'numeric');
		$form->addRule('cache',      $locale->get('system_error_cache'),      'required');
		$form->addRule('debug',      $locale->get('system_error_debug'),      'required');
		$form->addRule('userlogin',  $locale->get('system_error_usrlogin'),   'required');
		$form->addRule('multilang',  $locale->get('system_error_multilang'),  'required');
		$form->addRule('deflang',    $locale->get('system_error_deflang'),    'required');
		$form->addRule('dateformat', $locale->get('system_error_dateformat'), 'required');
		$form->addRule('groupnum',   $locale->get('system_error_groupnum'),  'required');
		$form->addRule('groupnum',   $locale->get('system_error_groupnum2'),  'numeric');
		$form->addRule('logging',    $locale->get('system_error_logging'),    'required');
		$form->addRule('prefgroup',  $locale->get('system_error_prefgroup'),  'required');
		$form->addRule('output',     $locale->get('system_error_output'),     'required');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$sitename    = $form->getSubmitValue('sitename');
			$sitehttp    = $form->getSubmitValue('sitehttp');
			$sitemail    = $form->getSubmitValue('sitemail');
			$pager       = intval($form->getSubmitValue('pager'));
			$minpass     = intval($form->getSubmitValue('minpass'));
			$cache       = intval($form->getSubmitValue('cache'));
			$debug       = intval($form->getSubmitValue('debug'));
			$multilang   = intval($form->getSubmitValue('multilang'));
			$deflang     = $form->getSubmitValue('deflang');
			$userlogin   = intval($form->getSubmitValue('userlogin'));
			$dateformat  = $form->getSubmitValue('dateformat');
			$groupnum    = intval($form->getSubmitValue('groupnum'));
			$logging     = intval($form->getSubmitValue('logging'));
			$prefgroup   = intval($form->getSubmitValue('prefgroup'));
			$description = $form->getSubmitValue('description');
			$output      = intval($form->getSubmitValue('output'));
			$keywords    = $form->getSubmitValue('keywords');

			$query = "
				UPDATE iShark_Configs
				SET sitename             = '".$sitename."',
					sitehttp             = '".$sitehttp."',
					sitemail             = '".$sitemail."',
					pager                = '$pager',
					minpass              = '$minpass',
					cache                = '$cache',
					debug                = '$debug',
					multilang            = '$multilang',
					deflang              = '$deflang',
					userlogin            = '$userlogin',
					dateformat           = '".$dateformat."',
					groupnum             = '$groupnum',
					is_logging           = '$logging',
					sys_prefgroup        = '$prefgroup',
					sys_meta_description = '$description',
					sys_output           = '$output',
					sys_meta_keywords    = '$keywords'
			";
			$mdb2->exec($query);

			$form->freeze();

			header('Location: admin.php?p=system');
			exit;
		}
	}

	/**
	 * a tartalomszerkeszto beallitasai
	 */
	if ($_REQUEST['type'] == "cont") {
		$lang_title = $locale->get('contents_title');

		$javascripts[] = 'javascript.system';
		$bodyonload[]  = 'contentdis()';

		//hireket akarunk-e hasznalni
		$isnews  =& $form->addElement('checkbox', 'news', $locale->get('contents_field_type'), $locale->get('contents_field_news'), 'onclick="contentdis()"');

		//sima tartalmat akarunk-e hasznalni
		$isother =& $form->addElement('checkbox', 'other', null, $locale->get('contents_field_content'));

		//vezeto hir hasznalata
		$lead = array();
		$lead[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$lead[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($lead, 'lead', $locale->get('contents_field_leadnews'));

		//vezeto hirek szama a fooldalon
		$form->addElement('text', 'leadnum', $locale->get('contents_field_leadnum'));

		//kepek a vezeto hirnel
		$leadpic = array();
		$leadpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$leadpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($leadpic, 'leadpic', $locale->get('contents_field_leadpic'));

		//kep szelessege
		$form->addElement('text', 'leadpicw', $locale->get('contents_field_leadpic_width'));

		//kep magassaga
		$form->addElement('text', 'leadpich', $locale->get('contents_field_leadpic_height'));

		//hirek szama a fooldalon
		$form->addElement('text', 'newsnum', $locale->get('contents_field_newsnum'));

		//kepek a hireknel
		$newspic = array();
		$newspic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$newspic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($newspic, 'newspic', $locale->get('contents_field_newspic'));

		//kep szelessege
		$form->addElement('text', 'newspicw', $locale->get('contents_filed_newspic_width'));

		//kep magassaga
		$form->addElement('text', 'newspich', $locale->get('contents_filed_newspic_height'));

		//kepek mappaja
		$form->addElement('text', 'cnt_picdir', $locale->get('contents_field_picdir'));

		//bevezeto szoveg hasznalata - hirek
		$is_lead = array();
		$is_lead[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$is_lead[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($is_lead, 'islead', $locale->get('contents_field_news_lead'));

		//bevezeto szoveg hasznalata - egyeb
		$is_leadoth = array();
		$is_leadoth[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$is_leadoth[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($is_leadoth, 'isleadoth', $locale->get('contents_field_content_lead'));

		//bevezeto szoveg maximalis hossza
		$form->addElement('text', 'leadmax', $locale->get('contents_field_leadmax'));

		//tartalom idozitheto
		$timer = array();
		$timer[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$timer[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($timer, 'timer', $locale->get('contents_field_timer'));

		//rovatok hasznalata
		$category = array();
		$category[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$category[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($category, 'category', $locale->get('contents_field_category'));

		//tartalmat csak sajat csoport modosithat
		$contedit = array();
		$contedit[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$contedit[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($contedit, 'contedit', $locale->get('contents_field_owngroup'));

		//tartalmak verziokovetese
		$version = array();
		$version[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$version[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($version, 'cnt_version', $locale->get('contents_field_version'));

		//olvasottsag szamlalo
		$counter = array();
		$counter[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$counter[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($counter, 'cnt_counter', $locale->get('contents_field_counter'));

		//hirek ertekelese
		$rating = array();
		$rating[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$rating[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($rating, 'cnt_rating', $locale->get('contents_field_rating'));

		//hirertekeles tipusa
		$rattype = array();
		$rattype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_rating_notreg'), '0');
		$rattype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_rating_reg'),    '1');
		//$rattype[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminSystemCntRateTypeWeight, '2');
		$form->addGroup($rattype, 'cnt_rattype', $locale->get('contents_field_ratingtype'));

		//hirekhez megjegyzes
		$comment = array();
		$comment[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$comment[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($comment, 'cnt_comment', $locale->get('contents_field_comments'));

		//tartalmak ertekelese
		$ratingcnt = array();
		$ratingcnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$ratingcnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($ratingcnt, 'cnt_ratingcnt', $locale->get('contents_field_rating_content'));

		//tartalomertekeles tipusa
		$rattypecnt = array();
		$rattypecnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_rating_notreg'), '0');
		$rattypecnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_rating_reg'),    '1');
		//$rattypecnt[] = &HTML_QuickForm::createElement('radio', null, null, $strAdminSystemCntRateTypeWeight, '2');
		$form->addGroup($rattypecnt, 'cnt_rattypecnt', $locale->get('contents_field_ratingtype'));

		//tartalmakhoz megjegyzes
		$comcnt = array();
		$comcnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$comcnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($comcnt, 'cnt_comcnt', $locale->get('contents_filed_comment_content'));

		//Kapcsolodo tartalmak
		$joincnt = array();
		$joincnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$joincnt[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($joincnt, 'cnt_is_attached_content', $locale->get('contents_field_attach_content'));

		//kapcsolodo kulso tartalmak
		$joinlink = array();
		$joinlink[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$joinlink[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($joinlink, 'cnt_is_attached_link', $locale->get('contents_field_attach_link'));

		//Kapcsolodo galeriak
		$joingal = array();
		$joingal[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$joingal[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($joingal, 'cnt_is_attached_gallery', $locale->get('contents_field_attach_gallery'));

		//Kapcsolodo letoltesek
		$joindwn = array();
		$joindwn[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$joindwn[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($joindwn, 'cnt_is_attached_download', $locale->get('contents_field_attach_download'));

		//Kapcsolodo urlapok
		$joinforms = array();
		$joinforms[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$joinforms[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($joinforms, 'cnt_is_attached_forms', $locale->get('contents_field_attach_forms'));

		//ajanlo
		if (isModule('recommend', 'index')) {
			$send = array();
			$send[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
			$send[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
			$form->addGroup($send, 'cnt_is_send', $locale->get('contents_field_recommend'));

			$form->addRule('cnt_is_send', $locale->get('contents_error_recommend'), 'required');
		}

		//cimkek hasznalata
		if (isModule('tags')) {
			$tags = array();
			$tags[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
			$tags[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
			$form->addGroup($tags, 'cnt_is_tags', $locale->get('contents_field_tags'));

			$form->addRule('cnt_is_tags', $locale->get('contents_error_tags'), 'required');
		}

		//futo csik hasznalata
		$marquee = array();
		$marquee[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$marquee[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($marquee, 'cnt_is_marquee', $locale->get('contents_field_marquee'));

		//futo csikban hany hir jelenik meg
		$form->addElement('text', 'cnt_marquee_num', $locale->get('contents_field_marqueenum'));

		//mti hirek hasznalata
		$mti = array();
		$mti[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_yes'), '1');
		$mti[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('contents_field_no'),  '0');
		$form->addGroup($mti, 'cnt_is_mti', $locale->get('contents_field_mti'));

		//mti hirek forrasa
		$form->addElement('text', 'cnt_mti_link', $locale->get('contents_field_mtilink'));

		// mti hireknel hany hir lehet a fooldalon
	    $form->addElement('text', 'cnt_mti_indexmax', $locale->get('contents_field_mtiindexmax'));

	    // mti hireknel egy nap hany mir lehet max. az oldalon
	    $form->addElement('text', 'cnt_mti_daymax', $locale->get('contents_field_mtidaymax'));

	    //lekerdezzuk a config tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$lead = $row['lead'];
			$form->setDefaults(array(
				'lead'                     => $row['lead'],
				'leadnum'                  => $row['leadnum'],
				'leadpic'                  => $row['leadpic'],
				'leadpicw'                 => $row['leadpicw'],
				'leadpich'                 => $row['leadpich'],
				'newsnum'                  => $row['newsnum'],
				'newspic'                  => $row['newspic'],
				'newspicw'                 => $row['newspicw'],
				'newspich'                 => $row['newspich'],
				'leadmax'                  => $row['leadmax'],
				'timer'                    => $row['conttimer'],
				'category'                 => $row['category'],
				'contedit'                 => $row['contedit'],
				'islead'                   => $row['is_lead'],
				'news'                     => $row['is_news'],
				'other'                    => $row['is_other'],
				'isleadoth'                => $row['cnt_is_lead_other'],
				'cnt_picdir'               => $row['cnt_picdir'],
				'cnt_version'              => $row['cnt_version'],
				'cnt_counter'              => $row['cnt_is_viewcounter'],
				'cnt_rating'               => $row['cnt_is_rating_news'],
				'cnt_rattype'              => $row['cnt_rating_type_news'],
				'cnt_comment'              => $row['cnt_is_comment_news'],
				'cnt_ratingcnt'            => $row['cnt_is_rating_cnt'],
				'cnt_rattypecnt'           => $row['cnt_rating_type_cnt'],
				'cnt_comcnt'               => $row['cnt_is_comment_cnt'],
				'cnt_is_attached_download' => $row['cnt_is_attached_download'],
				'cnt_is_attached_gallery'  => $row['cnt_is_attached_gallery'],
				'cnt_is_attached_content'  => $row['cnt_is_attached_content'],
				'cnt_is_attached_link'     => $row['cnt_is_attached_link'],
				'cnt_is_attached_forms'    => $row['cnt_is_attached_forms'],
				'cnt_is_send'              => $row['cnt_is_send'],
				'cnt_is_tags'              => $row['cnt_is_tags'],
				'cnt_is_marquee'           => $row['cnt_is_marquee'],
				'cnt_marquee_num'          => $row['cnt_marquee_num'],
				'cnt_is_mti'               => $row['cnt_is_mti'],
				'cnt_mti_link'             => $row['cnt_mti_link'],
				'cnt_mti_indexmax'         => $row['cnt_mti_indexmax'],
				'cnt_mti_daymax'           => $row['cnt_mti_daymax']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		//ha nincs bekapcsolva sem a hirek sem az egyeb tartalom lehetosege, akkor hiba
		if ($form->isSubmitted() && !$isnews->getChecked() && !$isother->getChecked()) {
			$form->setElementError('news', $locale->get('contents_error_type'));
		}
		//ha hasznaljuk a hireket
		if ($isnews->getChecked()) {
			$form->addRule('lead', $locale->get('contents_error_lead'), 'required');
			//ha lead == 1, csak akkor tesszuk kotelezove
			if (intval($form->getSubmitValue('lead')) == 1 || $lead == 1) {
				$form->addRule('leadnum',  $locale->get('contents_error_leadnum1'),        'required');
				$form->addRule('leadnum',  $locale->get('contents_error_leadnum2'),        'numeric');
				$form->addRule('leadnum',  $locale->get('contents_error_leadnum3'),        'nonzero');
				$form->addRule('leadpic',  $locale->get('contents_error_leadpic_width1'),  'required');
				$form->addRule('leadpicw', $locale->get('contents_error_leadpic_width2'),  'required');
				$form->addRule('leadpicw', $locale->get('contents_error_leadpic_width3'),  'numeric');
				$form->addRule('leadpicw', $locale->get('contents_error_leadpic_width4'),  'nonzero');
				$form->addRule('leadpich', $locale->get('contents_error_leadpic_height1'), 'required');
				$form->addRule('leadpich', $locale->get('contents_error_leadpic_height2'), 'numeric');
				$form->addRule('leadpich', $locale->get('contents_error_leadpic_height3'), 'nonzero');
			}
			$form->addRule('newsnum', $locale->get('contents_error_newsnum1'), 'required');
			$form->addRule('newsnum', $locale->get('contents_error_newsnum2'), 'numeric');
			$form->addRule('newsnum', $locale->get('contents_error_newsnum3'), 'nonzero');
			$form->addRule('newspic', $locale->get('contents_error_newspic'),  'required');
			//ha newspic == 1, csak akkor tesszuk kotelezove
			if (intval($form->getSubmitValue('newspic')) == 1) {
				$form->addRule('newspicw', $locale->get('contents_error_newspic_width1'),  'required');
				$form->addRule('newspicw', $locale->get('contents_error_newspic_width2'),  'numeric');
				$form->addRule('newspicw', $locale->get('contents_error_newspic_width3'),  'nonzero');
				$form->addRule('newspich', $locale->get('contents_error_newspic_height1'), 'required');
				$form->addRule('newspich', $locale->get('contents_error_newspic_height2'), 'numeric');
				$form->addRule('newspich', $locale->get('contents_error_newspic_height3'), 'nonzero');
			}
			//vagy vezetohir kep vagy simahir kep eseten ellenorizzuk a kepek mappat
			if (intval($form->getSubmitValue('lead')) == 1 || intval($form->getSubmitValue('newspic')) == 1) {
				$form->addRule('cnt_picdir', $locale->get('contents_error_picdir'), 'required');
			}
			//ha hasznaljuk a futo csikot
			$form->addRule('cnt_is_marquee', $locale->get('contents_error_marquee'), 'required');
			if (intval($form->getSubmitValue('cnt_is_marquee')) == 1) {
				$form->addRule('cnt_marquee_num', $locale->get('contents_error_marqueenum1'), 'required');
				$form->addRule('cnt_marquee_num', $locale->get('contents_error_marqueenum2'), 'numeric');
				$form->addRule('cnt_marquee_num', $locale->get('contents_error_marqueenum3'), 'nonzero');
			}
			//mti hirek
			$form->addRule('cnt_is_mti', $locale->get('contents_error_mti'), 'required');
			if (intval($form->getSubmitValue('cnt_is_mti')) == 1) {
				$form->addRule('cnt_mti_link',     $locale->get('contents_error_mtilink'),   'required');
				$form->addRule('cnt_mti_indexmax', $locale->get('contents_error_indexmax1'), 'required');
				$form->addRule('cnt_mti_indexmax', $locale->get('contents_error_indexmax2'), 'numeric');
				$form->addRule('cnt_mti_daymax',   $locale->get('contents_error_daymax1'),   'required');
				$form->addRule('cnt_mti_daymax',   $locale->get('contents_error_daymax2'),   'numeric');
			}
		}
		$form->addRule('islead',    $locale->get('contents_error_leadnews'),     'required');
		$form->addRule('isleadoth', $locale->get('contents_error_lead_content'), 'required');
		//ha islead == 1, csak akkor tesszuk kotelezove
		if (intval($form->getSubmitValue('islead')) == 1 || intval($form->getSubmitValue('isleadoth')) == 1) {
			$form->addRule('leadmax', $locale->get('contents_error_leadmax1'), 'required');
			$form->addRule('leadmax', $locale->get('contents_error_leadmax2'), 'numeric');
			$form->addRule('leadmax', $locale->get('contents_error_leadmax3'), 'nonzero');
			$form->addRule('leadmax', $locale->get('contents_error_leadmax4'), 'regex',  '/^[0-5]\d{2,4}$|^6\d{1,3}$|^6[0-4]\d{3}$|^65[0-4]\d\d$|^655[0-2]\d$|^6553[0-5]$/');
		}
		$form->addRule('timer',                    $locale->get('contents_error_timer'),              'required');
		$form->addRule('category',                 $locale->get('contents_error_category'),           'required');
		$form->addRule('contedit',                 $locale->get('contents_error_owngroup'),           'required');
		$form->addRule('cnt_version',              $locale->get('contents_error_version'),            'required');
		$form->addRule('cnt_counter',              $locale->get('contents_error_counter'),            'required');
		$form->addRule('cnt_rating',               $locale->get('contents_error_rating'),             'required');
		$form->addRule('cnt_rattype',              $locale->get('contents_error_ratingtype'),         'required');
		$form->addRule('cnt_comment',              $locale->get('contents_error_comment'),            'required');
		$form->addRule('cnt_ratingcnt',            $locale->get('contents_error_rating_content'),     'required');
		$form->addRule('cnt_rattypecnt',           $locale->get('contents_error_ratingtype_content'), 'required');
		$form->addRule('cnt_comcnt',               $locale->get('contentt_error_comment_content'),    'required');
		$form->addRule('cnt_is_attached_download', $locale->get('contents_error_attach_download'),    'required');
		$form->addRule('cnt_is_attached_gallery',  $locale->get('contents_error_attach_gallery'),     'required');
		$form->addRule('cnt_is_attached_content',  $locale->get('contents_error_attach_content'),     'required');
		$form->addRule('cnt_is_attached_forms',    $locale->get('contents_error_attach_forms'),       'required');
		$form->addRule('cnt_is_attached_link',     $locale->get('contents_error_attach_link'),        'required');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			if ($isnews->getChecked()) {
				$lead             = intval($form->getSubmitValue('lead'));
				$leadnum          = intval($form->getSubmitValue('leadnum'));
				$leadpic          = intval($form->getSubmitValue('leadpic'));
				$leadpicw         = intval($form->getSubmitValue('leadpicw'));
				$leadpich         = intval($form->getSubmitValue('leadpich'));
				$newsnum          = intval($form->getSubmitValue('newsnum'));
				$newspic          = intval($form->getSubmitValue('newspic'));
				$newspicw         = intval($form->getSubmitValue('newspicw'));
				$newspich         = intval($form->getSubmitValue('newspich'));
				$islead           = intval($form->getSubmitValue('islead'));
				$cnt_picdir       = $form->getSubmitValue('cnt_picdir');
				$cnt_is_marquee   = intval($form->getSubmitValue('cnt_is_marquee'));
				$cnt_marquee_num  = intval($form->getSubmitValue('cnt_marquee_num'));
				$cnt_is_mti       = intval($form->getSubmitValue('cnt_is_mti'));
				$cnt_mti_link     = $form->getSubmitValue('cnt_mti_link');
				$cnt_mti_indexmax = intval($form->getSubmitValue('cnt_mti_indexmax'));
				$cnt_mti_daymax   = intval($form->getSubmitValue('cnt_mti_daymax'));
			}
			$is_leadoth               = intval($form->getSubmitValue('isleadoth'));
			$leadmax                  = intval($form->getSubmitValue('leadmax'));
			$timer                    = intval($form->getSubmitValue('timer'));
			$category                 = intval($form->getSubmitValue('category'));
			$contedit                 = intval($form->getSubmitValue('contedit'));
			$news                     = $isnews->getChecked() ? 1 : 0;
			$other                    = $isother->getChecked() ? 1 : 0;
			$cnt_version              = intval($form->getSubmitValue('cnt_version'));
			$cnt_counter              = intval($form->getSubmitValue('cnt_counter'));
			$cnt_rating               = intval($form->getSubmitValue('cnt_rating'));
			$cnt_rattype              = intval($form->getSubmitValue('cnt_rattype'));
			$cnt_comment              = intval($form->getSubmitValue('cnt_comment'));
			$cnt_comcnt               = intval($form->getSubmitValue('cnt_comcnt'));
			$cnt_ratingcnt            = intval($form->getSubmitValue('cnt_ratingcnt'));
			$cnt_rattypecnt           = intval($form->getSubmitValue('cnt_rattypecnt'));
			$cnt_is_attached_gallery  = intval($form->getSubmitValue('cnt_is_attached_gallery'));
			$cnt_is_attached_download = intval($form->getSubmitValue('cnt_is_attached_download'));
			$cnt_is_attached_content  = intval($form->getSubmitValue('cnt_is_attached_content'));
			$cnt_is_attached_forms    = intval($form->getSubmitValue('cnt_is_attached_forms'));
			$cnt_is_attached_link     = intval($form->getSubmitValue('cnt_is_attached_link'));
			$cnt_is_send              = intval($form->getSubmitValue('cnt_is_send'));
			$cnt_is_tags              = intval($form->getSubmitValue('cnt_is_tags'));

			//ha hireket viszunk fel
			if ($news == 1) {
				$query = "
					UPDATE iShark_Configs
					SET lead                     = '$lead',
						leadnum                  = $leadnum,
						leadpic                  = '$leadpic',
						leadpicw                 = '$leadpicw',
						leadpich                 = '$leadpich',
						newsnum                  = $newsnum,
						newspic                  = '$newspic',
						newspicw                 = '$newspicw',
						newspich                 = '$newspich',
						leadmax                  = $leadmax,
						conttimer                = '$timer',
						category                 = '$category',
						contedit                 = '$contedit',
						is_lead                  = '$islead',
						leadmax                  = $leadmax,
						is_news                  = '$news',
						is_other                 = '$other',
						cnt_is_lead_other        = '$is_leadoth',
						cnt_picdir               = '".$cnt_picdir."',
						cnt_version              = '$cnt_version',
						cnt_is_viewcounter       = '$cnt_counter',
						cnt_is_rating_news       = '$cnt_rating',
						cnt_rating_type_news     = '$cnt_rattype',
						cnt_is_comment_news      = '$cnt_comment',
						cnt_is_rating_cnt        = '$cnt_ratingcnt',
						cnt_rating_type_cnt      = '$cnt_rattypecnt',
						cnt_is_comment_cnt       = '$cnt_comcnt',
						cnt_is_attached_content  = '$cnt_is_attached_content',
						cnt_is_attached_download = '$cnt_is_attached_download',
						cnt_is_attached_gallery  = '$cnt_is_attached_gallery',
						cnt_is_attached_forms    = '$cnt_is_attached_forms',
						cnt_is_attached_link     = '$cnt_is_attached_link',
						cnt_is_send              = '$cnt_is_send',
						cnt_is_tags              = '$cnt_is_tags',
						cnt_is_marquee           = $cnt_is_marquee,
						cnt_marquee_num          = $cnt_marquee_num,
						cnt_is_mti               = $cnt_is_mti,
						cnt_mti_link             = '$cnt_mti_link',
						cnt_mti_indexmax         = $cnt_mti_indexmax,
						cnt_mti_daymax           = $cnt_mti_daymax
				";
			} else {
				$query = "
					UPDATE iShark_Configs
					SET cnt_is_lead_other        = '$is_leadoth',
						leadmax                  = '$leadmax',
						conttimer                = '$timer',
						category                 = '$category',
						contedit                 = '$contedit',
						is_news                  = '$news',
						is_other                 = '$other',
						cnt_version              = '$cnt_version',
						cnt_is_viewcounter       = '$cnt_counter',
						cnt_is_rating_cnt        = '$cnt_ratingcnt',
						cnt_rating_type_cnt      = '$cnt_rattypecnt',
						cnt_is_comment_cnt       = '$cnt_comcnt',
						cnt_is_attached_content  = '$cnt_is_attached_content',
						cnt_is_attached_download = '$cnt_is_attached_download',
						cnt_is_attached_gallery  = '$cnt_is_attached_gallery',
						cnt_is_attached_forms    = '$cnt_is_attached_forms',
						cnt_is_attached_link     = '$cnt_is_attached_link',
						cnt_is_send              = '$cnt_is_send',
						cnt_is_tags              = '$cnt_is_tags'
				";
			}
			$mdb2->exec($query);

			$form->freeze();

			header('Location: admin.php?p=system');
			exit;
		}
	}

	/**
	 * TinyMCE beallitasai
	 */
	if ($_REQUEST['type'] == "mce") {
		$lang_title = $locale->get('mce_title');

		//szukseges tombok feltoltese
		$mce_theme = array('advanced' => 'advanced', 'simple' => 'simple');

		//tema kivalasztasa
		$form->addElement('select', 'theme', $locale->get('mce_field_theme'), $mce_theme);

		//nyelv kivalasztasa
		$form->addElement('select', 'lang', $locale->get('mce_field_lang'), directory_list($libs_dir.'/tiny_mce/langs/', 'js', array(), '1'));

		//mappa, ahova a tiny-bol feltolti a fileokat
		$form->addElement('text', 'mcedir', $locale->get('mce_field_updir'));

		//a css, amit hasznalunk az oldalon
		$form->addElement('select', 'css', $locale->get('mce_field_css'), directory_list($theme_dir.'/'.$theme.'/', 'css', array(), '1'));

		//oldal tartalmi reszenek a szelessege - kell az elonezet plugin-hoz
		$form->addElement('text', 'pwidth', $locale->get('mce_field_pagewidth'));

		//lekerdezzuk a config tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			if ($row['mce_uploaddir'] == "") {
				$uploaddir = substr($_SERVER['PHP_SELF'], 0, strlen($_SERVER['PHP_SELF'])-strlen('admin.php')).'uploads/tiny_mce/';
			} else {
				$uploaddir = $row['mce_uploaddir'];
			}

			$form->setDefaults(array(
				'theme'  => $row['mce_theme'],
				'lang'   => $row['mce_lang'],
				'mcedir' => $uploaddir,
				'css'    => $row['mce_css'],
				'pwidth' => $row['mce_pagewidth']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('theme',  $locale->get('mce_error_theme'),      'required');
		$form->addRule('lang',   $locale->get('mce_error_lang'),       'required');
		$form->addRule('mcedir', $locale->get('mce_error_updir'),      'required');
		$form->addRule('css',    $locale->get('mce_error_css'),        'required');
		$form->addRule('pwidth', $locale->get('mce_error_pagewidth1'), 'required');
		$form->addRule('pwidth', $locale->get('mce_error_pagewidth2'), 'numeric');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$theme     = $form->getSubmitValue('theme');
			$lang      = $form->getSubmitValue('lang');
			$uploaddir = $form->getSubmitValue('mcedir');
			$css       = $form->getSubmitValue('css');
			$pwidth    = intval($form->getSubmitValue('pwidth'));

			$query = "
				UPDATE iShark_Configs
				SET mce_theme     = '".$theme."',
					mce_lang      = '".$lang."',
					mce_uploaddir = '".$uploaddir."',
					mce_css       = '".$css."',
					mce_pagewidth = $pwidth
			";
			$mdb2->exec($query);

			$form->freeze();

			header('Location: admin.php?p=system');
			exit;
		}
	} //TinyMCE beallitas vege

	/**
	 * letoltesvezerlo beallitasai
	 */
	if ($_REQUEST['type'] == "dwn") {
		$lang_title = $locale->get('downloads_title');

		//ftp feltoltes engedelyezese
		$ftpup = array();
		$ftpup[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('downloads_field_yes'), '1');
		$ftpup[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('downloads_field_no'),  '0');
		$form->addGroup($ftpup, 'ftpup', $locale->get('downloads_field_ftpup'));

		//ftp mappa
		$form->addElement('text', 'ftpdir', $locale->get('downloads_field_ftpdir'));

		//letoltes mappa
		$form->addElement('text', 'downdir', $locale->get('downloads_field_downdir'));

		//mappak maximalis szama
		$form->addElement('text', 'maxdir', $locale->get('downloads_field_maxdir'));

		//engedelyezett filetipusok
		$form->addElement('text', 'types', $locale->get('downloads_field_types'));

		//fileok max. merete
		$form->addElement('text', 'maxsize', $locale->get('downloads_field_maxsize'));

		//lekerdezzuk a config tablat, es az eredmenyeket beallitjuk alapertelmezettnek
		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result = $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			//ftp mappa
			if ($row['ftpdir'] == "") {
				$ftpdir = 'files/ftp/';
			} else {
				$ftpdir = $row['ftpdir'];
			}

			//letoltes mappa
			if ($row['downdir'] == "") {
				$downdir = 'files/downloads/';
			} else {
				$downdir = $row['downdir'];
			}

			$form->setDefaults(array(
				'ftpup'   => $row['is_ftpdir'],
				'ftpdir'  => $ftpdir,
				'downdir' => $downdir,
				'maxdir'  => $row['maxdir'],
				'types'   => $row['allow_filetypes'],
				'maxsize' => $row['maxsize']/1024/1024
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('ftpup', $locale->get('downloads_error_ftpup'), 'required');
		if ($form->getSubmitValue('ftpup') == 1) {
			$form->addRule('ftpdir', $locale->get('downloads_error_ftpdir'), 'required');
		}
		$form->addRule('downdir', $locale->get('downloads_error_downdir'),  'required');
		$form->addRule('maxdir',  $locale->get('downloads_error_maxdir1'),  'required');
		$form->addRule('maxdir',  $locale->get('downloads_error_maxdir2'),  'numeric');
		$form->addRule('maxsize', $locale->get('downloads_error_maxsize1'), 'required');
		$form->addRule('maxsize', $locale->get('downloads_error_maxsize2'), 'numeric');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$ftpup   = intval($form->getSubmitValue('ftpup'));
			$ftpdir  = $form->getSubmitValue('ftpdir');
			$downdir = $form->getSubmitValue('downdir');
			$maxdir  = intval($form->getSubmitValue('maxdir'));
			$types   = $form->getSubmitValue('types');
			$maxsize = $form->getSubmitValue('maxsize')*1024*1024;

			$query = "
				UPDATE iShark_Configs
				SET is_ftpdir       = '$ftpup',
					ftpdir          = '".$ftpdir."',
					downdir         = '".$downdir."',
					maxdir          = '$maxdir',
					allow_filetypes = '".$types."',
					maxsize         = '$maxsize'
			";
			$mdb2->exec($query);

			$form->freeze();

			header('Location: admin.php?p=system');
			exit;
		}
	} //letoltesvezerlo beallitas vege

	/**
	 * Galéria beállításai
	 */
	if ($_REQUEST['type'] == 'gal') {
		$lang_title = $locale->get('gallery_title');

		//galeriakepek mappaja
		$form->addElement('text', 'galerydir', $locale->get('gallery_field_dir'), array('maxlength' => '255'));

		//indexkepek szelessegs
		$form->addElement('text', 'thumbwidth', $locale->get('gallery_field_thumbwidth'), array('maxlength' => '4', 'size' => '4'));

		//indexkepek magassaga
		$form->addElement('text', 'thumbheight', $locale->get('gallery_field_thumbheight'), array('maxlength' => '4', 'size' => '4'));

		//kepek szellesege
		$form->addElement('text', 'picwidth', $locale->get('gallery_field_width'), array('maxlength' => '4', 'size' => '4'));

		//kepek magassaga
		$form->addElement('text', 'picheight', $locale->get('gallery_field_height'), array('maxlength' => '4', 'size' => '4'));

		//ftp mappak hasznalata
		$isftp =& $form->addElement('checkbox', 'gallery_is_ftpdir', $locale->get('gallery_field_isftp'));

		//ftp mappa
		$form->addElement('text', 'galleryftpdir', $locale->get('gallery_field_ftpdir'), array('maxlength' => 255));

		//ertekeles hasznalata
		$israting =& $form->addElement('checkbox', 'gallery_is_rating', $locale->get('gallery_field_rating'));

		//toplista
		$istoplist =& $form->addElement('checkbox', 'gallerytoplist', $locale->get('gallery_field_toplist'));

		//toplistaban az elemek szama
		$form->addElement('text', 'gallerytopnum', $locale->get('gallery_field_toplistnum'));

		//tartalom csatolhato
		$iscntattach =& $form->addElement('checkbox', 'gallery_cnt_attach', $locale->get('gallery_field_attach_contents'));

		//cimkek hasznalata
		$istags =& $form->addElement('checkbox', 'gallery_is_tags', $locale->get('gallery_field_tags'));

		//rovatok hasznalata
		$iscateg =& $form->addElement('checkbox', 'gallery_is_category', $locale->get('gallery_field_category'));

		//videogaleriak hasznalata
		$isvideo =& $form->addElement('checkbox', 'gallery_is_video', $locale->get('gallery_field_video'));

		//videok letolthetoek-e
		$isdownload =& $form->addElement('checkbox', 'gallery_is_download', $locale->get('gallery_field_download_video'));

		//videok konvertalasa
		$isconvert =& $form->addElement('checkbox', 'gallery_is_convert', $locale->get('gallery_field_download_convert'));

		//leiras max. hossza
		$form->addElement('text', 'maxdesc', $locale->get('gallery_field_max_description'));

		//galeria tipusa
		$galtype = array();
		$galtype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('gallery_field_type_inline'), '1');
		$galtype[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('gallery_field_type_popup'),  '0');
		$form->addGroup($galtype, 'galtype', $locale->get('gallery_field_type'));

		$query = "
			SELECT * 
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			if ($row['galerydir'] == '') {
				$row['galerydir'] = 'files/gallery';
			}
			if ($row['galleryftpdir'] == '') {
				$row['galleryftpdir'] = 'files/gallery_ftp';
			}
			if ($row['thumbwidth'] == '0') {
				$row['thumbwidth'] = '90';
			}
			if ($row['thumbheight'] == '0') {
				$row['thumbheight'] = '90';
			}
			if ($row['picwidth'] == '0') {
				$row['picwidth'] = '450';
			}
			if ($row['picheight'] == '0') {
				$row['picheight'] = '450';
			}
			$form->setDefaults(array(
				'galerydir'		      => $row['galerydir'],
				'thumbwidth'	      => $row['thumbwidth'],
				'thumbheight'	      => $row['thumbheight'],
				'picwidth'		      => $row['picwidth'],
				'picheight'		      => $row['picheight'],
				'galleryftpdir'       => $row['galleryftpdir'],
				'gallery_is_ftpdir'   => $row['gallery_is_ftpdir'],
				'gallery_is_rating'   => $row['gallery_is_rating'],
				'gallerytoplist'      => $row['gallerytoplist'],
				'gallerytopnum'       => $row['gallerytopnum'],
				'gallery_cnt_attach'  => $row['gallery_cnt_attach'],
				'gallery_is_tags'     => $row['gallery_is_tags'],
				'gallery_is_category' => $row['gallery_is_category'],
				'gallery_is_download' => $row['gallery_is_download'],
				'maxdesc'             => $row['gallery_max_desc'],
				'galtype'             => $row['gallery_type'],
				'gallery_is_video'    => $row['gallery_is_video'],
				'gallery_is_convert'  => $row['gallery_is_convert']
				)
			);
		}
		$form->applyFilter('__ALL__', 'trim');
		$form->addRule('galerydir',     $locale->get('gallery_error_dir'),          'required');
		$form->addRule('thumbwidth',    $locale->get('gallery_error_thumbwidth1'),  'required');
		$form->addRule('thumbheight',   $locale->get('gallery_error_thumbheight1'), 'required');
		$form->addRule('picwidth',      $locale->get('gallery_error_width1'),       'required');
		$form->addRule('picheight',	    $locale->get('gallery_error_height1'),      'required');
		$form->addRule('galleryftpdir', $locale->get('gallery_error_ftpdir'),       'required');
		$form->addRule('thumbwidth',    $locale->get('gallery_error_thumbwidth2'),  'numeric');
		$form->addRule('thumbheight',   $locale->get('gallery_error_thumbheight2'), 'numeric');
		$form->addRule('picwidth',	    $locale->get('gallery_error_width2'),       'numeric');
		$form->addRule('picheight',     $locale->get('gallery_error_height2'),      'numeric');
		$form->addRule('maxdesc',       $locale->get('gallery_error_maxdesc1'),     'required');
		$form->addRule('maxdesc',       $locale->get('gallery_error_maxdesc2'),     'numeric');
		$form->addRule('maxdesc',       $locale->get('gallery_error_maxdesc3'),     'regex', '/^[0-5]\d{2,4}$|^6\d{1,3}$|^6[0-4]\d{3}$|^65[0-4]\d\d$|^655[0-2]\d$|^6553[0-5]$/');
		$form->addRule('galtype',       $locale->get('gallery_error_galtype'),      'required');

		if ($form->isSubmitted() && $istoplist->getChecked() === true && $israting->getChecked() === false){
			$form->setElementError('gallerytoplist', $locale->get('gallery_error_toplist'));
		}
		if ($form->isSubmitted() && $istoplist->getChecked() === true && $israting->getChecked() === true){
			$form->addRule('gallerytopnum', $locale->get('gallery_error_toplistnum1'), 'required');
			$form->addRule('gallerytopnum', $locale->get('gallery_error_toplistnum2'), 'numeric');
			$form->addRule('gallerytopnum', $locale->get('gallery_error_toplistnum3'), 'nonzero');
		}
		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$galerydir			 = $form->getSubmitValue('galerydir');
			$thumbwidth			 = intval($form->getSubmitValue('thumbwidth'));
			$thumbheight		 = intval($form->getSubmitValue('thumbheight'));
			$picwidth			 = intval($form->getSubmitValue('picwidth'));
			$picheight			 = intval($form->getSubmitValue('picheight'));
			$galleryftpdir		 = $form->getSubmitValue('galleryftpdir');
			$gallery_is_ftpdir   = $isftp->getChecked() ? '1' : '0';
			$gallery_is_rating   = $israting->getChecked() ? '1' : '0';
			$gallerytoplist      = $istoplist->getChecked() ? '1' : '0';
			$gallerytopnum		 = intval($form->getSubmitValue('gallerytopnum'));
			$gallery_cnt_attach  = $iscntattach->getChecked() ? '1' : '0';
			$gallery_is_tags     = $istags->getChecked() ? '1' : '0';
			$gallery_is_category = $iscateg->getChecked() ? '1' : '0';
			$gallery_is_download = $isdownload->getChecked() ? '1' : '0';
			$maxdesc             = intval($form->getSubmitValue('maxdesc'));
			$gallery_type        = intval($form->getSubmitValue('galtype'));
			$video               = $isvideo->getChecked() ? '1' : '0';
			$convert             = $isconvert->getChecked() ? '1' : '0';

			$query = "
				UPDATE iShark_Configs
				SET galerydir           = '$galerydir',
					thumbwidth          = '$thumbwidth',
					thumbheight         = '$thumbheight',
					picwidth            = '$picwidth',
					picheight           = '$picheight',
					gallery_is_ftpdir   = '$gallery_is_ftpdir',
					galleryftpdir       = '$galleryftpdir',
					gallery_is_rating   = '$gallery_is_rating',
					gallerytoplist      = '$gallerytoplist',
					gallerytopnum       = '$gallerytopnum',
					gallery_cnt_attach  = '$gallery_cnt_attach',
					gallery_is_tags     = '$gallery_is_tags',
					gallery_is_category = '$gallery_is_category',
					gallery_is_download = '$gallery_is_download',
					gallery_max_desc    = $maxdesc,
					gallery_type        = '$gallery_type',
					gallery_is_video    = '$video',
					gallery_is_convert  = '$convert'
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	} //galériabeállítás vége

	/**
	 * Bannerkezelo beállításai
	 */
	if ($_REQUEST['type'] == 'ban') {
		$lang_title = $locale->get('banners_title');

		//bannerek mappaja
		$form->addElement('text', 'bannerdir', $locale->get('banners_field_dir'), array('maxlength' => '255'));

		//thumbnail szelesseg (admin oldalon)
		$form->addElement('text', 'banner_widths', $locale->get('banners_field_width'), array('maxlength' => '4', 'size' => '4'));

		//thumbnail magassag (admin oldalon)
		$form->addElement('text', 'banner_heights', $locale->get('banners_field_height'), array('maxlength' => '4', 'size' => '4'));

		//ujratoltes ideje
		$form->addElement('text', 'banner_reload', $locale->get('banners_field_reload'), array('maxlength' => '5', 'size' => '4'));

        //bannerkezelo tipusa
        $type = array();
		$type[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('banners_field_swap'),   '1');
		$type[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('banners_field_random'), '0');
		$form->addGroup($type, 'banner_type', $locale->get('banners_field_type'));

		$query = "
			SELECT * 
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			if ($row['bannerdir'] == '') {
				$row['bannerdir'] = 'files/banners';
			}
			if ($row['banner_widths'] == '0') {
				$row['banner_widths'] = '150';
			}
			if ($row['banner_heights'] == '0') {
				$row['banner_heights'] = '150';
			}
			if ($row['banner_reload'] == '0') {
				$row['banner_reload'] = '5000';
			}

			$form->setDefaults(array(
				'bannerdir'      => $row['bannerdir'],
				'banner_widths'  => $row['banner_widths'],
				'banner_heights' => $row['banner_heights'],
				'banner_reload'  => $row['banner_reload']/1000,
                'banner_type'    => $row['banner_type']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('bannerdir',      $locale->get('banners_error_dir'),          'required');
		$form->addRule('banner_widths',  $locale->get('banners_error_thumbwidth1'),  'required');
		$form->addRule('banner_heights', $locale->get('banners_error_thumbheight1'), 'required');
		$form->addRule('banner_widths',  $locale->get('banners_error_thumbwidth2'),  'numeric');
		$form->addRule('banner_heights', $locale->get('banners_error_thumbheight2'), 'numeric');
		$form->addRule('banner_reload',  $locale->get('banners_error_reload1'),      'required');
		$form->addRule('banner_reload',  $locale->get('banners_error_reload2'),      'numeric');
        $form->addRule('banner_type',    $locale->get('banners_error_type'),         'required');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$bannerdir      = $form->getSubmitValue('bannerdir');
			$banner_widths  = intval($form->getSubmitValue('banner_widths'));
			$banner_heights	= intval($form->getSubmitValue('banner_heights'));
			$banner_reload	= intval($form->getSubmitValue('banner_reload'))*1000;
            $banner_type    = intval($form->getSubmitValue('banner_type'));

			$query = "
				UPDATE iShark_Configs
				SET bannerdir      = '$bannerdir',
					banner_widths  = '$banner_widths',
					banner_heights = '$banner_heights',
					banner_reload  = '$banner_reload',
                    banner_type    = '$banner_type'
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	} //bannerkezelo beallitas vége

	/**
	 * Shop beállításai
	 */
	if ($_REQUEST['type'] == 'sho') {
		$lang_title = $locale->get('shop_title');

		//felhasznalok vasarolhatnak-e
		$userbuy = array();
		$userbuy[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$userbuy[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($userbuy, 'userbuy', $locale->get('shop_field_userbuy'));

		//vasarlashoz kotelezo a regisztracio
		$reguserbuy = array();
		$reguserbuy[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$reguserbuy[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($reguserbuy, 'reguserbuy', $locale->get('shop_field_regbuy'));

		//csoportok hasznalata
		$groupuse = array();
		$groupuse[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$groupuse[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($groupuse, 'groupuse', $locale->get('shop_field_groupuse'));

		//allapot hasznalata
		$stateuse = array();
		$stateuse[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$stateuse[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($stateuse, 'stateuse', $locale->get('shop_field_stateuse'));

		//kapcsolodo termekek hasznalata
		$joinprod = array();
		$joinprod[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$joinprod[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($joinprod, 'joinprod', $locale->get('shop_field_joinprod'));

		//kapcsolodo termekeknel csak a sajat kategoria
		$joinourcat = array();
		$joinourcat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$joinourcat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($joinourcat, 'joinourcat', $locale->get('shop_field_joinourcat'));

		//ertekelesek a termekekhez
		$rate = array();
		$rate[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$rate[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($rate, 'rate', $locale->get('shop_field_rate'));

		//akciok hasznalata
		$actionuse = array();
		$actionuse[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$actionuse[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($actionuse, 'actionuse', $locale->get('shop_field_actionuse'));

		//extra attributumok hasznalata
		$attr = array();
		$attr[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$attr[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($attr, 'attr', $locale->get('shop_field_attributes'));

		//breadcrumb hasznalata
		$bread = array();
		$bread[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$bread[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($bread, 'bread', $locale->get('shop_field_breadcrumb'));

		//csv betoltes engedelyezese
		$shopcsv = array();
		$shopcsv[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$shopcsv[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($shopcsv, 'shopcsv', $locale->get('shop_field_csv'));

		//letoltheto dokumentum csatolasa
		$attach = array();
		$attach[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$attach[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($attach, 'attach', $locale->get('shop_field_attach'));

		//dokumetumok maximalis szama
		$form->addElement('text', 'attachnum', $locale->get('shop_field_attachnum'));

		//dokumentumok mappaja
		$form->addElement('text', 'attachdir', $locale->get('shop_field_attachdir'));

		//rendezes tipusa
		$ordertype = array(1 => $locale->get('shop_field_order_abc'), 2 => $locale->get('shop_field_order_num'));
		$form->addElement('select', 'ordertype', $locale->get('shop_field_order_type'), $ordertype);

		//kategoriak maximalis szama
		$form->addElement('text', 'maincat', $locale->get('shop_field_category_num'));

		//kategoriakhoz kepek
		$mainpic = array();
		$mainpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$mainpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($mainpic, 'mainpic', $locale->get('shop_field_category_pic'));

		//kategoriak mappaja
		$form->addElement('text', 'mainpicdir', $locale->get('shop_field_category_picdir'));

		//kategoria kepek thumbnail szelessege
		$form->addElement('text', 'mainpicswidth', $locale->get('shop_field_category_picthumbwidth'));

		//kategoria kepek thumbnail magassaga
		$form->addElement('text', 'mainpicsheight', $locale->get('shop_field_category_picthumbheight'));

		//kategoria kepek teljes szelessege
		$form->addElement('text', 'mainpicwidth', $locale->get('shop_field_category_picwidth'));

		//kategoria kepek teljes magassaga
		$form->addElement('text', 'mainpicheight', $locale->get('shop_field_category_picheight'));

		//termekekhez kepek
		$prodpic = array();
		$prodpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_yes'), '1');
		$prodpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('shop_field_no'),  '0');
		$form->addGroup($prodpic, 'prodpic', $locale->get('shop_field_product_pic'));

		//termekkepek szama
		$form->addElement('text', 'prodpicnum', $locale->get('shop_field_product_picnum'));

		//termekkepek szama listaban
		$form->addElement('text', 'prodpiclistnum', $locale->get('shop_field_product_piclistnum'));

		//termekkepek mappaja
		$form->addElement('text', 'prodpicdir', $locale->get('shop_field_product_picdir'));

		//termekkepek thumbnail szelessege
		$form->addElement('text', 'prodpicswidth', $locale->get('shop_field_product_picthumbwidth'));

		//termekkepek thumbnail magassag
		$form->addElement('text', 'prodpicsheight', $locale->get('shop_field_product_picthumbheight'));

		//termekkepek teljes szelessege
		$form->addElement('text', 'prodpicwidth', $locale->get('shop_field_product_picwidth'));

		//termekkepek teljes magassaga
		$form->addElement('text', 'prodpicheight', $locale->get('shop_field_product_picheight'));

		//keresesnel minimalis karakterhossz
		$form->addElement('text', 'searchminchar', $locale->get('shop_field_search_minchar'));

		//szallitasi modok max. szama
		$form->addElement('text', 'shipmax', $locale->get('shop_field_shippingmax'));

		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			if ($row['shop_mainpicdir'] == '') {
				$row['shop_mainpicdir'] = 'files/shop/category';
			}
			if ($row['shop_prodpicdir'] == '') {
				$row['shop_prodpicdir'] = 'files/shop/products';
			}
			$form->setDefaults(array(
				'userbuy'        => $row['shop_userbuy'],
				'groupuse'       => $row['shop_groupuse'],
				'ordertype'      => $row['shop_ordertype'],
				'maincat'        => $row['shop_maincat'],
				'mainpic'        => $row['shop_mainpic'],
				'mainpicdir'     => $row['shop_mainpicdir'],
				'mainpicswidth'  => $row['shop_mainpicswidth'],
				'mainpicsheight' => $row['shop_mainpicsheight'],
				'mainpicwidth'   => $row['shop_mainpicwidth'],
				'mainpicheight'  => $row['shop_mainpicheight'],
				'prodpic'        => $row['shop_prodpic'],
				'prodpicdir'     => $row['shop_prodpicdir'],
				'prodpicswidth'  => $row['shop_prodpicswidth'],
				'prodpicsheight' => $row['shop_prodpicsheight'],
				'prodpicwidth'   => $row['shop_prodpicwidth'],
				'prodpicheight'  => $row['shop_prodpicheight'],
				'stateuse'       => $row['shop_stateuse'],
				'prodpicnum'     => $row['shop_prodpicnum'],
				'reguserbuy'     => $row['shop_reguserbuy'],
				'attach'         => $row['shop_attach'],
				'prodpiclistnum' => $row['shop_prodpiclistnum'],
				'attachnum'      => $row['shop_attachnum'],
				'attachdir'      => $row['shop_attachdir'],
				'actionuse'      => $row['shop_actionuse'],
				'searchminchar'  => $row['shop_searchminchar'],
				'joinprod'       => $row['shop_joinprod'],
				'rate'           => $row['shop_is_rating'],
				'attr'           => $row['shop_is_extra_attr'],
				'bread'          => $row['shop_is_breadcrumb'],
				'joinourcat'     => $row['shop_joinourcat'],
				'shopcsv'        => $row['shop_is_csv'],
				'shipmax'        => $row['shop_shipping_max']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('userbuy',        $locale->get('shop_error_userbuy'),                 'required');
		$form->addRule('groupuse',       $locale->get('shop_error_groupuse'),                 'required');
		$form->addRule('ordertype',      $locale->get('shop_error_ordertype'),                'required');
		$form->addRule('maincat',        $locale->get('shop_error_categorynum1'),             'required');
		$form->addRule('maincat',        $locale->get('shop_error_categorynum2'),             'numeric');
		$form->addRule('mainpic',        $locale->get('shop_error_category_pic'),             'required');
		$form->addRule('mainpicdir',     $locale->get('shop_error_category_picdir'),          'required');
		$form->addRule('mainpicswidth',  $locale->get('shop_error_category_picthumbwidth1'),  'required');
		$form->addRule('mainpicswidth',  $locale->get('shop_error_category_picthumbwidth2'),  'numeric');
		$form->addRule('mainpicsheight', $locale->get('shop_error_category_picthumbheight1'), 'required');
		$form->addRule('mainpicsheight', $locale->get('shop_error_category_picthumbheight2'), 'numeric');
		$form->addRule('mainpicwidth',   $locale->get('shop_error_category_picwidth1'),       'required');
		$form->addRule('mainpicwidth',   $locale->get('shop_error_category_picwidth2'),       'numeric');
		$form->addRule('mainpicheight',  $locale->get('shop_error_category_picheight1'),      'required');
		$form->addRule('mainpicheight',  $locale->get('shop_error_category_picheight2'),      'numeric');
		$form->addRule('prodpic',        $locale->get('shop_error_product_pic'),              'required');
		$form->addRule('prodpicdir',     $locale->get('shop_error_product_picdir'),           'required');
		$form->addRule('prodpicswidth',  $locale->get('shop_error_product_picthumbwidth1'),   'required');
		$form->addRule('prodpicswidth',  $locale->get('shop_error_product_picthumbwidth2'),   'numeric');
		$form->addRule('prodpicsheight', $locale->get('shop_error_product_picthumbheight1'),  'required');
		$form->addRule('prodpicsheight', $locale->get('shop_error_product_picthumbheight2'),  'numeric');
		$form->addRule('prodpicwidth',   $locale->get('shop_error_product_picwidth1'),        'required');
		$form->addRule('prodpicwidth',   $locale->get('shop_error_product_picwidth2'),        'numeric');
		$form->addRule('prodpicheight',  $locale->get('shop_error_product_picheight1'),       'required');
		$form->addRule('prodpicheight',  $locale->get('shop_error_product_picheight2'),       'numeric');
		$form->addRule('stateuse',       $locale->get('shop_error_stateuse'),                 'required');
		$form->addRule('prodpicnum',     $locale->get('shop_error_product_picnum1'),          'required');
		$form->addRule('prodpicnum',     $locale->get('shop_error_product_picnum1'),          'numeric');
		$form->addRule('reguserbuy',     $locale->get('shop_error_reguserbuy'),               'required');
		$form->addRule('attach',         $locale->get('shop_error_attach'),                   'required');
		$form->addRule('prodpiclistnum', $locale->get('shop_error_product_piclistnum1'),      'required');
		$form->addRule('prodpiclistnum', $locale->get('shop_error_product_piclistnum2'),      'numeric');
		$form->addRule('attachnum',      $locale->get('shop_error_attachnum1'),               'required');
		$form->addRule('attachnum',      $locale->get('shop_error_attachnum2'),               'numeric');
		$form->addRule('attachdir',      $locale->get('shop_error_attachdir'),                'required');
		$form->addRule('actionuse',      $locale->get('shop_error_actionuse'),                'required');
		$form->addRule('searchminchar',  $locale->get('shop_error_search_minchar1'),          'required');
		$form->addRule('searchminchar',  $locale->get('shop_error_search_minchar2'),          'numeric');
		$form->addRule('joinprod',       $locale->get('shop_error_joinprod'),                 'required');
		$form->addRule('rate',           $locale->get('shop_error_rate'),                     'required');
		$form->addRule('attr',           $locale->get('shop_error_attributes'),               'required');
		$form->addRule('bread',          $locale->get('shop_error_breadcrumb'),               'required');
		$form->addRule('joinourcat',     $locale->get('shop_error_joinourcat'),               'required');
		$form->addRule('shopcsv',        $locale->get('shop_error_csv'),                      'required');
		$form->addRule('shipmax',        $locale->get('shop_error_shipmax1'),                 'required');
		$form->addRule('shipmax',        $locale->get('shop_error_shipmax2'),                 'numeric');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));
			$userbuy        = intval($form->getSubmitValue('userbuy'));
			$groupuse       = intval($form->getSubmitValue('groupuse'));
			$ordertype      = intval($form->getSubmitValue('ordertype'));
			$maincat        = intval($form->getSubmitValue('maincat'));
			$mainpic        = intval($form->getSubmitValue('mainpic'));
			$mainpicdir     = $form->getSubmitValue('mainpicdir');
			$mainpicswidth  = intval($form->getSubmitValue('mainpicswidth'));
			$mainpicsheight = intval($form->getSubmitValue('mainpicsheight'));
			$mainpicwidth   = intval($form->getSubmitValue('mainpicwidth'));
			$mainpicheight  = intval($form->getSubmitValue('mainpicheight'));
			$prodpic        = intval($form->getSubmitValue('prodpic'));
			$prodpicdir     = $form->getSubmitValue('prodpicdir');
			$prodpicswidth  = intval($form->getSubmitValue('prodpicswidth'));
			$prodpicsheight = intval($form->getSubmitValue('prodpicsheight'));
			$prodpicwidth   = intval($form->getSubmitValue('prodpicwidth'));
			$prodpicheight  = intval($form->getSubmitValue('prodpicheight'));
			$stateuse       = intval($form->getSubmitValue('stateuse'));
			$prodpicnum     = intval($form->getSubmitValue('prodpicnum'));
			$reguserbuy     = intval($form->getSubmitValue('reguserbuy'));
			$attach         = intval($form->getSubmitValue('attach'));
			$prodpiclistnum = intval($form->getSubmitValue('prodpiclistnum'));
			$attachnum      = intval($form->getSubmitValue('attachnum'));
			$attachdir      = $form->getSubmitValue('attachdir');
			$actionuse      = intval($form->getSubmitValue('actionuse'));
			$searchminchar  = intval($form->getSubmitValue('searchminchar'));
			$joinprod       = intval($form->getSubmitValue('joinprod'));
			$rate           = intval($form->getSubmitValue('rate'));
			$attr           = intval($form->getSubmitValue('attr'));
			$bread          = intval($form->getSubmitValue('bread'));
			$joinourcat     = intval($form->getSubmitValue('joinourcat'));
			$shopcsv        = intval($form->getSubmitValue('shopcsv'));
			$shipmax        = intval($form->getSubmitValue('shipmax'));

			$query = "
				UPDATE iShark_Configs
				SET shop_userbuy        = $userbuy,
					shop_groupuse       = $groupuse,
					shop_ordertype      = $ordertype,
					shop_maincat        = $maincat,
					shop_mainpic        = $mainpic,
					shop_mainpicdir     = '".$mainpicdir."',
					shop_mainpicswidth  = $mainpicswidth,
					shop_mainpicsheight = $mainpicsheight,
					shop_mainpicwidth   = $mainpicwidth,
					shop_mainpicheight  = $mainpicheight,
					shop_prodpic        = $prodpic,
					shop_prodpicdir     = '".$prodpicdir."',
					shop_prodpicswidth  = $prodpicswidth,
					shop_prodpicsheight = $prodpicsheight,
					shop_prodpicwidth   = $prodpicwidth,
					shop_prodpicheight  = $prodpicheight,
					shop_stateuse       = $stateuse,
					shop_prodpicnum     = $prodpicnum,
					shop_reguserbuy     = $reguserbuy,
					shop_attach         = $attach,
					shop_prodpiclistnum = $prodpiclistnum,
					shop_attachnum      = $attachnum,
					shop_attachdir      = '".$attachdir."',
					shop_actionuse      = $actionuse,
					shop_searchminchar  = $searchminchar,
					shop_joinprod       = $joinprod,
					shop_is_rating      = $rate,
					shop_is_extra_attr  = $attr,
					shop_is_breadcrumb  = $bread,
					shop_joinourcat     = $joinourcat,
					shop_is_csv         = $shopcsv,
					shop_shipping_max   = $shipmax
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	}

	/**
	 * Partnerkezelo beallitasok
	 */
	if ($_REQUEST['type'] == 'partners') {
		$lang_title = $locale->get('partners_title');

		//arlistak mappaja
		$form->addElement('text', 'partners_pricesdir', $locale->get('partners_field_pricesdir'));

		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$form->setDefaults(array(
				"partners_pricesdir" => $row['partners_pricesdir']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('partners_pricesdir', $locale->get('partners_error_pricesdir'), 'required');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$partners_pricesdir = $form->getSubmitValue('partners_pricesdir');

			$query = "
				UPDATE iShark_Configs
				SET partners_pricesdir = '$partners_pricesdir'
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	}

	/**
	 * Fõoldali összerakó beállítások
	 */
	if ($_REQUEST['type'] == 'builder') {
		$lang_title = $locale->get('builder_title');

		//felhasznalo modosithatja-e
		$isuser = array();
		$isuser[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('builder_field_yes'), '1');
		$isuser[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('builder_field_no'),  '0');
		$form->addGroup($isuser, 'builder_is_user', $locale->get('builder_field_usermod'));

		//hasabok szama
		$form->addElement('text', 'builder_columns', $locale->get('builder_field_columns'));

		//hasabok szelessege
		$form->addElement('text', 'builder_columns_width', $locale->get('builder_field_columnswidth'));

		//hasabok mertekegysege
		$form->addElement('select', 'builder_columns_measure', $locale->get('builder_field_measure'), array('px' => 'Pixel', '%' => 'Százalék'));

		//tartalmi hasab szama
		$form->addElement('text', 'builder_content_column', $locale->get('builder_field_content_column'));

		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$form->setDefaults(array(
				"builder_is_user"         => $row['builder_is_user'],
				"builder_columns"         => $row['builder_columns'],
				"builder_columns_width"   => $row['builder_columns_width'],
				"builder_columns_measure" => $row['builder_columns_measure'],
				"builder_content_column"  => $row['builder_content_column']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('builder_is_user',         $locale->get('builder_error_usermod'),         'required');
		$form->addRule('builder_columns',         $locale->get('builder_error_columns1'),        'required');
		$form->addRule('builder_columns',         $locale->get('builder_error_columns2'),        'numeric');
		$form->addRule('builder_columns_width',   $locale->get('builder_error_columns_width1'),  'required');
		$form->addRule('builder_content_column',  $locale->get('builder_error_content_column1'), 'required');
		$form->addRule('builder_columns_measure', $locale->get('builder_error_measure'),         'required');

		if ($form->isSubmitted()){
			$colwidths = explode(";", $form->getSubmitValue('builder_columns_width'));
			if (count($colwidths) != $form->getSubmitValue('builder_columns')) {
				$form->setElementError('builder_columns_width', $locale->get('builder_error_columns_width2'));
			}
			if($form->getSubmitValue('builder_content_column') > $form->getSubmitValue('builder_columns')) {
				$form->setElementError('builder_content_column', $locale->get('builder_error_content_column2'));
			}
		}

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$builder_is_user         = $form->getSubmitValue('builder_is_user');
			$builder_columns         = $form->getSubmitValue('builder_columns');
			$builder_columns_width   = $form->getSubmitValue('builder_columns_width');
			$builder_columns_measure = $form->getSubmitValue('builder_columns_measure');
			$builder_content_column  = $form->getSubmitValue('builder_content_column');

			$query = "
				UPDATE iShark_Configs
				SET builder_is_user         = $builder_is_user,
					builder_columns         = $builder_columns,
					builder_columns_width   = '$builder_columns_width',
					builder_columns_measure = '$builder_columns_measure',
					builder_content_column  = '$builder_content_column'
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	}

	/**
	 * Statisztika
	 */
	if ($_REQUEST['type'] == "stat") {
		$lang_title = $locale->get('stat_title');

		//limit beallitasa top oldalaknal
		$form->addElement('text', 'stat_limit', $locale->get('stat_field_toplimit'));

		//kereso statisztika
		$search = array();
		$search[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_yes'), '1');
		$search[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_no'),  '0');
		$form->addGroup($search, 'stat_search', $locale->get('stat_field_search'));

		//orszagok
		$country = array();
		$country[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_yes'), '1');
		$country[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_no'),  '0');
		$form->addGroup($country, 'stat_country', $locale->get('stat_field_country'));

		//ujratoltesek szamolasa
		$reload = array();
		$reload[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_yes'), '1');
		$reload[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_no'),  '0');
		$form->addGroup($reload, 'stat_reload', $locale->get('stat_field_reload'));

		//grafikonok
		$graph = array();
		$graph[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_yes'), '1');
		$graph[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_no'),  '0');
		$form->addGroup($graph, 'stat_graph', $locale->get('stat_field_graph'));

		//visszatero latogatok
		$return = array();
		$return[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_yes'), '1');
		$return[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('stat_field_no'),  '0');
		$form->addGroup($return, 'stat_return', $locale->get('stat_field_return'));

		//visszatero latogato cookie lejarati ideje
		$cookie = $form->addElement('text', 'stat_cookie', $locale->get('stat_field_cookie'));

		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$form->setDefaults(array(
				'stat_limit'   => $row['stat_limit'],
				'stat_search'  => $row['stat_search'],
				'stat_country' => $row['stat_country'],
				'stat_reload'  => $row['stat_reload'],
				'stat_graph'   => $row['stat_is_graph'],
				'stat_return'  => $row['stat_return_visitor'],
				'stat_cookie'  => $row['stat_cookie_lifetime']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('stat_limit',   $locale->get('stat_error_limit'),   'required');
		$form->addRule('stat_limit',   $locale->get('stat_error_limit2'),  'numeric');
		$form->addRule('stat_search',  $locale->get('stat_error_search'),  'required');
		$form->addRule('stat_country', $locale->get('stat_error_country'), 'required');
		$form->addRule('stat_reload',  $locale->get('stat_error_reload'),  'required');
		$form->addRule('stat_graph',   $locale->get('stat_error_graph'),   'required');
		$form->addRule('stat_return',  $locale->get('stat_error_return'),  'required');
		$form->addRule('stat_cookie',  $locale->get('stat_error_cookie1'), 'required');
		$form->addRule('stat_cookie',  $locale->get('stat_error_cookie2'), 'numeric');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$stat_limit   = intval($form->getSubmitValue('stat_limit'));
			$stat_search  = intval($form->getSubmitValue('stat_search'));
			$stat_country = intval($form->getSubmitValue('stat_country'));
			$stat_reload  = intval($form->getSubmitValue('stat_reload'));
			$stat_graph   = intval($form->getSubmitValue('stat_graph'));
			$stat_return  = intval($form->getSubmitValue('stat_return'));
			$stat_cookie  = intval($form->getSubmitValue('stat_cookie'));

			$query = "
				UPDATE iShark_Configs
				SET stat_limit           = $stat_limit,
					stat_search          = '$stat_search',
					stat_country         = '$stat_country',
					stat_reload          = '$stat_reload',
					stat_is_graph        = '$stat_graph',
					stat_return_visitor  = '$stat_return',
					stat_cookie_lifetime = $stat_cookie
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	}

	/**
	 * Aprohirdetes
	 */
	if ($_REQUEST['type'] == "class") {
		$lang_title = $locale->get('classifieds_title');

		//breadcrumb hasznalata
		$bread = array();
		$bread[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_yes'), '1');
		$bread[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_no'),  '0');
		$form->addGroup($bread, 'class_bread', $locale->get('classifieds_field_breadcrumb'));

		//kategoriak maximalis szama
		$form->addElement('text', 'class_maxcat', $locale->get('classifieds_field_categorynum'));

		//kategoriakhoz leiras
		$catdesc = array();
		$catdesc[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_yes'), '1');
		$catdesc[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_no'),  '0');
		$form->addGroup($catdesc, 'class_catdesc', $locale->get('classifieds_field_categorydesc'));

		//kategoriakhoz kepek
		$mainpic = array();
		$mainpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_yes'), '1');
		$mainpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_no'),  '0');
		$form->addGroup($mainpic, 'class_catpic', $locale->get('classifieds_field_categorypic'));

		//kategoria kepek mappaja
		$form->addElement('text', 'class_catpicdir', $locale->get('classifieds_field_categorypicdir'));

		//kategoria kepek merete
		$form->addElement('text', 'class_catpictwidth',  $locale->get('classifieds_field_category_picthumbwidth'));
		$form->addElement('text', 'class_catpictheight', $locale->get('classifieds_field_category_picthumbheight'));
		$form->addElement('text', 'class_catpicwidth',   $locale->get('classifieds_field_category_picwidth'));
		$form->addElement('text', 'class_catpicheight',  $locale->get('classifieds_field_category_picheight'));

		//hirdetesekhez kepek
		$prodpic = array();
		$prodpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_yes'), '1');
		$prodpic[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_no'),  '0');
		$form->addGroup($prodpic, 'class_advpic', $locale->get('classifieds_field_productpic'));

		//hirdeteshez tartozo kepek szama
		$form->addElement('text', 'class_advpicnum', $locale->get('classifieds_field_productnum'));

		//hirdetesekhez tartozo kepek szama a listaban
		$form->addElement('text', 'class_advpiclistnum', $locale->get('classifieds_field_productpiclistnum'));

		//hirdetes kepeinek mappaja
		$form->addElement('text', 'class_advpicdir', $locale->get('classifieds_field_productpicdir'));

		//hirdetes kepek merete
		$form->addElement('text', 'class_advpictwidth',  $locale->get('classifieds_field_product_picthumbwidth'));
		$form->addElement('text', 'class_advpictheight', $locale->get('classifieds_field_product_picthumbheight'));
		$form->addElement('text', 'class_advpicwidth',   $locale->get('classifieds_field_product_picwidth'));
		$form->addElement('text', 'class_advpicheight',  $locale->get('classifieds_field_product_picheight'));

		//keresesnel minimalis karakterhossz
		$form->addElement('text', 'class_searchminchar', $locale->get('classifieds_field_search_minchar'));

		//lejarat elott hany nappal kuldjon levelet
		$form->addElement('text', 'class_expmail', $locale->get('classifieds_field_expire_maildate'));

		//automatikusan kapcsolodik a vetel-eladas-csere a kategoriahoz
		$autocat = array();
		$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_yes'), '1');
		$autocat[] = &HTML_QuickForm::createElement('radio', null, null, $locale->get('classifieds_field_no'),  '0');
		$form->addGroup($autocat, 'class_autocat', $locale->get('classifieds_field_autocategory'));

		//nem aktivalt aprohirdetes hany nap utan torlodjon
		$form->addElement('text', 'class_autodel', $locale->get('classifieds_field_activatedate'));

		$query = "
			SELECT *
			FROM iShark_Configs
		";
		$result =& $mdb2->query($query);
		while ($row = $result->fetchRow()) {
			$form->setDefaults(array(
				'class_bread'         => $row['class_is_breadcrumb'],
				'class_maxcat'        => $row['class_maxcat'],
				'class_catpic'        => $row['class_is_catpic'],
				'class_catpicdir'     => $row['class_catpicdir'],
				'class_catpicwidth'   => $row['class_catpicwidth'],
				'class_catpicheight'  => $row['class_catpicheight'],
				'class_catpictwidth'  => $row['class_catpictwidth'],
				'class_catpictheight' => $row['class_catpictheight'],
				'class_advpic'        => $row['class_is_advpic'],
				'class_advpicnum'     => $row['class_advpicnum'],
				'class_advpiclistnum' => $row['class_advpiclistnum'],
				'class_advpicdir'     => $row['class_advpicdir'],
				'class_advpicwidth'   => $row['class_advpicwidth'],
				'class_advpicheight'  => $row['class_advpicheight'],
				'class_advpictwidth'  => $row['class_advpictwidth'],
				'class_advpictheight' => $row['class_advpictheight'],
				'class_searchminchar' => $row['class_searchminchar'],
				'class_expmail'       => $row['class_expiration_mail'],
				'class_autocat'       => $row['class_autocategory'],
				'class_autodel'       => $row['class_autodel'],
				'class_catdesc'       => $row['class_is_catdesc']
				)
			);
		}

		$form->applyFilter('__ALL__', 'trim');

		$form->addRule('class_bread',         $locale->get('classifieds_error_breadcrumb'),               'required');
		$form->addRule('class_maxcat',        $locale->get('classifieds_error_category_max1'),            'required');
		$form->addRule('class_maxcat',        $locale->get('classifieds_error_category_max2'),            'numeric');
		$form->addRule('class_catpic',        $locale->get('classifieds_error_categorypic'),              'required');
		$form->addRule('class_catpicdir',     $locale->get('classifieds_error_categorypicdir'),           'required');
		$form->addRule('class_catpicwidth',   $locale->get('classifieds_error_category_picwidth1'),       'required');
		$form->addRule('class_catpicwidth',   $locale->get('classifieds_error_category_picwidth2'),       'numeric');
		$form->addRule('class_catpicheight',  $locale->get('classifieds_error_category_picheight1'),      'required');
		$form->addRule('class_catpicheight',  $locale->get('classifieds_error_category_picheight2'),      'numeric');
		$form->addRule('class_catpictwidth',  $locale->get('classifieds_error_category_picthumbwidth1'),  'required');
		$form->addRule('class_catpictwidth',  $locale->get('classifieds_error_category_picthumbwidth2'),  'numeric');
		$form->addRule('class_catpictheight', $locale->get('classifieds_error_category_picthumbheight1'), 'required');
		$form->addRule('class_catpictheight', $locale->get('classifieds_error_category_picthumbheight2'), 'numeric');
		$form->addRule('class_advpic',        $locale->get('classifieds_error_productpic'),               'required');
		$form->addRule('class_advpicnum',     $locale->get('classifieds_error_productpicnum1'),           'required');
		$form->addRule('class_advpicnum',     $locale->get('classifieds_error_productpicnum2'),           'numeric');
		$form->addRule('class_advpiclistnum', $locale->get('classifieds_error_productpiclistnum1'),       'required');
		$form->addRule('class_advpiclistnum', $locale->get('classifieds_error_productpiclistnum2'),       'numeric');
		$form->addRule('class_advpicdir',     $locale->get('classifieds_error_productpicdir'),            'required');
		$form->addRule('class_advpicwidth',   $locale->get('classifieds_error_product_picwidth1'),        'required');
		$form->addRule('class_advpicwidth',   $locale->get('classifieds_error_product_picwidth2'),        'numeric');
		$form->addRule('class_advpicheight',  $locale->get('classifieds_error_product_picheight1'),       'required');
		$form->addRule('class_advpicheight',  $locale->get('classifieds_error_product_picheight2'),       'numeric');
		$form->addRule('class_advpictwidth',  $locale->get('classifieds_error_product_picthumbwidth1'),   'required');
		$form->addRule('class_advpictwidth',  $locale->get('classifieds_error_product_picthumbwidth2'),   'numeric');
		$form->addRule('class_advpictheight', $locale->get('classifieds_error_product_picthumbheight1'),  'required');
		$form->addRule('class_advpictheight', $locale->get('classifieds_error_product_picthumbheight2'),  'numeric');
		$form->addRule('class_searchminchar', $locale->get('classifieds_error_search_minchar1'),          'required');
		$form->addRule('class_searchminchar', $locale->get('classifieds_error_search_minchar2'),          'numeric');
		$form->addRule('class_expmail',       $locale->get('classifieds_error_expire_maildate1'),         'required');
		$form->addRule('class_expmail',       $locale->get('classifieds_error_expire_maildate2'),         'numeric');
		$form->addRule('class_autocat',       $locale->get('classifieds_error_autocategory'),             'required');
		$form->addRule('class_autodel',       $locale->get('classifieds_error_activatedate1'),            'required');
		$form->addRule('class_autodel',       $locale->get('classifieds_error_activatedate2'),            'numeric');
		$form->addRule('class_autodel',       $locale->get('classifieds_error_activatedate3'),            'nonzero');
		$form->addRule('class_catdesc',       $locale->get('classifieds_error_categorydesc'),             'required');

		if ($form->validate()) {
			$form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

			$bread         = intval($form->getSubmitValue('class_bread'));
			$maxcat        = intval($form->getSubmitValue('class_maxcat'));
			$catpic        = intval($form->getSubmitValue('class_catpic'));
			$catpicdir     = $form->getSubmitValue('class_catpicdir');
			$catpicwidth   = intval($form->getSubmitValue('class_catpicwidth'));
			$catpicheight  = intval($form->getSubmitValue('class_catpicheight'));
			$catpictwidth  = intval($form->getSubmitValue('class_catpictwidth'));
			$catpictheight = intval($form->getSubmitValue('class_catpictheight'));
			$advpic        = intval($form->getSubmitValue('class_advpic'));
			$advpicnum     = intval($form->getSubmitValue('class_advpicnum'));
			$advpiclistnum = intval($form->getSubmitValue('class_advpiclistnum'));
			$advpicdir     = $form->getSubmitValue('class_advpicdir');
			$advpicwidth   = intval($form->getSubmitValue('class_advpicwidth'));
			$advpicheight  = intval($form->getSubmitValue('class_advpicheight'));
			$advpictwidth  = intval($form->getSubmitValue('class_advpictwidth'));
			$advpictheight = intval($form->getSubmitValue('class_advpictheight'));
			$searchminchar = intval($form->getSubmitValue('class_searchminchar'));
			$expmail       = intval($form->getSubmitValue('class_expmail'));
			$autocat       = intval($form->getSubmitValue('class_autocat'));
			$autodel       = intval($form->getSubmitValue('class_autodel'));
			$catdesc       = intval($form->getSubmitValue('class_catdesc'));

			$query = "
				UPDATE iShark_Configs
				SET class_is_breadcrumb   = '$bread',
					class_maxcat          = $maxcat,
					class_is_catpic       = '$catpic',
					class_catpicdir       = '$catpicdir',
					class_catpicwidth     = $catpicwidth,
					class_catpicheight    = $catpicheight,
					class_catpictwidth    = $catpictwidth,
					class_catpictheight   = $catpictheight,
					class_is_advpic       = '$advpic',
					class_advpicnum       = $advpicnum,
					class_advpiclistnum   = $advpiclistnum,
					class_advpicdir       = '$advpicdir',
					class_advpicwidth     = $advpicwidth,
					class_advpicheight    = $advpicheight,
					class_advpictwidth    = $advpictwidth,
					class_advpictheight   = $advpictheight,
					class_searchminchar   = $searchminchar,
					class_expiration_mail = '$expmail',
					class_autocategory    = '$autocat',
					class_autodel         = $autodel,
					class_is_catdesc      = $catdesc
			";
			$mdb2->exec($query);

			$form->freeze();

			header("Location: admin.php?p=system");
			exit;
		}
	}

	$form->addElement('submit', 'submit', $locale->get('form_submit'), 'class="submit"');
	$form->addElement('reset',  'reset',  $locale->get('form_reset'),  'class="reset"');

	$renderer =& new HTML_QuickForm_Renderer_Array(true, true);
	$form->accept($renderer);

	$tpl->assign('form', $renderer->toArray());

	//capture the array structure
	ob_start();
	print_r($renderer->toArray());
	$tpl->assign('dynamic_form', ob_get_contents());
	$tpl->assign('lang_title',   $lang_title);
	ob_end_clean();

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'dynamic_form';
}
/**
 * ha a listat mutatjuk
 */
if ($act == "lst") {
	$lang_system = array();
	$lang_system['strAdminHeader']        = $locale->get('title_header');
	$lang_system['strAdminSystemSystem']  = $locale->get('title_system');

	//ha letezik a tiny_mce mappa, akkor lehet csak allitgatni
	if (is_dir($libs_dir."/tiny_mce")) {
		$lang_system['strAdminSystemTinyMCE'] = $locale->get('title_tiny');
	}

	//lekerdezzuk azokat a modulokat, amik szerepelhetnek a rendszerbeallitasoknal
	$query = "
		SELECT file_name, is_active
		FROM iShark_Modules
	";
	$result = $mdb2->query($query);
	if ($result->numRows() > 0) {
		while ($row = $result->fetchRow())
		{
			//tartalomszerkeszto
			if ($row['file_name'] == 'contents' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemContent'] = $locale->get('title_contents');
			}
			//letolteskezelo
			if ($row['file_name'] == 'downloads' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemDownload'] = $locale->get('title_downloads');
			}
			//galeria
			if ($row['file_name'] == 'gallery' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemGallery'] = $locale->get('title_gallery');
			}
			//bannerkezelo
			if ($row['file_name'] == 'banners' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemBanner'] = $locale->get('title_banners');
				$lang_system['strAdminSystemPlaces'] = $locale->get('title_bannerplaces');
			}
			//shop
			if ($row['file_name'] == 'shop' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemShop'] = $locale->get('title_shop');
				$lang_system['strAdminSystemProp'] = $locale->get('title_shop_properties');
			}
			//partners
			if ($row['file_name'] == 'partners' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemPartners'] = $locale->get('title_partners');
			}
			//builder
			if ($row['file_name'] == 'builder' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemBuilderTitle'] = $locale->get('title_builder');
			}
			//stat
			if ($row['file_name'] == 'stat' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemStatTitle'] = $locale->get('title_stat');
			}
			//aprohirdetes
			if ($row['file_name'] == 'classifieds' && $row['is_active'] == 1) {
				$lang_system['strAdminSystemClassTitle'] = $locale->get('title_classifieds');
			}
			//felhasznalok
			$lang_system['strAdminSystemUsersTitle'] = $locale->get('title_users');
		}
	}

	//a file-hoz tartozo nyelvi valtozok atadasa a template-nek
	$tpl->assign('lang_system', $lang_system);

	//megadjuk a tpl file nevet, amit atadunk az admin.php-nek
	$acttpl = 'system';
}

?>