<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
    <title>{$sitename}{if $content_title} - {$content_title}{/if}</title>
    <base href="{$smarty.session.site_sitehttp}" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
    <meta name="description" content="{$meta_tags.description}" />
    <meta name="keywords" content="{$meta_tags.keywords}" />

	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="7 days" />

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=iso-8859-2" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<link rel="shortcut icon" href="{$theme_dir}/images/favicon.ico" />

	<link rel="stylesheet" type="text/css" media="screen, projection" href="{$include_dir}/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="{$include_dir}/bootstrap/css/bootstrap-theme.min.css" />
	
	<link rel="stylesheet" type="text/css" media="screen, projection" href="{$theme_dir}/style.css" />
	{if $smarty.session.sitetype eq 2}
		<link rel="stylesheet" type="text/css" media="screen, projection" href="{$theme_dir}/blind.css" />
	{/if}
	
	{foreach from=$css item=c}
		<link rel="stylesheet" type="text/css" media="screen, projection" href="{$theme_dir}/{$c}.css" />
	{/foreach}	
	<link rel="stylesheet" type="text/css" media="print" href="{$theme_dir}/print.css" />	
</head>

<body {if $bodyonload}onload="{foreach from=$bodyonload item=load}{$load};{/foreach}"{/if}>
<div class="container">
	{if $smarty.session.sitetype neq 2}
	<div class="header" onclick="window.location = '{$smarty.session.site_sitehttp}'">
		{if $bgpic}
			<img src="{$bgpic}" class="img-responsive" />
		{else}
			<img src="{$theme_dir}/images/head_pic.jpg" class="img-responsive" />
		{/if}
	</div>
	{/if}
	<div>
		<nav class="navbar navbar-inverse">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					{foreach from=$topmenu item=data name="topfor1"}			
						{*<a href="{$data.menu_name_no}/{$data.menu_id}aa">{$data.menu_name}</a>*}
						<li class="dropdown">
							{assign value=false var="isactive"}
							{if $data.element}						
								<ul class="dropdown-menu">
								{foreach from=$data.element item=datasub name="subfor1"}
									<li class="subitem">
										{*<a href="{$datasub.menu_name_no}/{$datasub.menu_id}">{$datasub.menu_name}</a>*}
										<a href="index.php?mid={$datasub.menu_id}">{$datasub.menu_name}</a>										
									</li>
									{if $datasub.menu_id eq $smarty.request.mid}
										{assign value=true var="isactive"}
									{/if}
								{/foreach}
								</ul>
							{/if}
							<a class="
								{if $data.menu_id eq $smarty.request.mid || $isactive}{$data.menu_color}active{/if}
								{if $data.menu_color}{$data.menu_color}{/if}
							" href="index.php?mid={$data.menu_id}">{$data.menu_name}</a>
						</li>				
					{/foreach}
				</ul>
				<div class="gyengenlato pull-right">
					{if $smarty.session.sitetype eq 2}
						<a href="index.php?sitetype=1">Normál verzió</a>
					{else}
						<a href="index.php?sitetype=2"><img src="{$theme_dir}/images/gyengenlato-icon.png"></a>
					{/if}
				</div>
			</div>
		</nav>
	</div>
	<div class="col-md-12 white-bg">
		{if $module_name eq "gallery"}
		<div class="col-md-12">
		{else}
		<div class="col-md-8">
		{/if}
			{if ($site_errors || $site_success || $page neq "")}
				{if $site_errors}
					{foreach from=$site_errors item=data}
						<div style="text-align: center;">
							{$data.text}<br />
							<a href="{$data.link}" title="{$locale.config.back_link}">{$locale.config.back_link}</a>
						</div>
					{/foreach}
				{elseif $site_success}
					{foreach from=$site_success item=data}
						<div style="text-align: center;">
							{$data.text}<br />
							<a href="{$data.link}" title="{$locale.config.next_link}">{$locale.config.next_link}</a>
						</div>
					{/foreach}
				{else}			
					{include file="$page.tpl"}						
				{/if}
			{else}
				<div class="cont_text {if $heading_color}{$heading_color}{/if}">
					{if !empty($main_cont.content2)}
						<div class="col-md-6">
							{$main_cont.content}
						</div>
						<div class="col-md-6">
							{$main_cont.content2}
						</div>
					{else}
						<div class="col-md-12">
							{$main_cont.content}
						</div>
					{/if}
				</div>
			{/if}
		</div>
		{if $module_name neq "gallery"}
			<div class="col-md-4">
				<div class="sidebar">
					<h4>Nyitva tartás / Opening hours</h4>
					<div class="sidebar_box">
						Kialakítás alatt{*$locale.config.opening_hours*}
					</div>
					
					<h4>BELÉPŐDÍJAK / ADMISSION FEE</h4>
					<div class="sidebar_box">					
						{*<div class="row">
							<div class="col-md-6">felnőtt / adult</div>
							<div class="col-md-6 pull-right">0.000 HUF</div>
						</div>
						<div class="row">
							<div class="col-md-6">diák, nyugdíjas / student, senior</div>
							<div class="col-md-6 pull-right">0.000 HUF</div>
						</div>
						<div class="row">
							<div class="col-md-6">gyerek /children</div>
							<div class="col-md-6 pull-right">0.000 HUF</div>
						</div>*}
						Kialakítás alatt
					</div>
					
					<h4>Kapcsolat / Contact</h4>
					<div class="sidebar_box">
						4324 Kállósemjén, Kossuth út 94.<br />
						info@kallaykuria.hu<br />
						+36 (42) 255-423<br />
						{if $smarty.session.sitetype neq 2}
							<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
							{literal}
							<script>
							window.onload = function() {
							
								var posLatlng = new google.maps.LatLng(47.860983,21.920417);
							
								var myOptions = {
									center: posLatlng,
									zoom: 16,
									mapTypeId: google.maps.MapTypeId.ROADMAP,
									disableDefaultUI: true
								};
								
								var map = new google.maps.Map(document.getElementById("map"), myOptions);
								
								var marker = new google.maps.Marker({
								  position: posLatlng,
								  map: map
								});					
							}
							</script>
							{/literal}
							<div id="map" style="width: 275px; height:140px; margin: 10px 0;" />
						{/if}
					</div>
					{if $latnivalok}
						<h4>Látnivalók a környéken /<br /> Places of interest nearby</h4>
						<div class="sidebar_box">
							{foreach from=$latnivalok item=data}						
								<a href="index.php?p=news&act=show&cid={$data.content_id}">{$data.title}</a><br />
							{/foreach}
						</div>
					{/if}
					{if $partnerek}
						<h4>Partnereink / Our partners</h4>
						<div class="sidebar_box">
							{foreach from=$partnerek item=data}						
								<a href="index.php?p=news&act=show&cid={$data.content_id}">{$data.title}</a>
							{/foreach}
						</div>
					{/if}
				</div>
			</div>
		{/if}
	</div>
	<div class="col-md-12 footer">
		{$smarty.now|date_format:"%Y"} &copy; Kállay kúria. Minden jog fenntartva.
	</div>
</div>

{if $bannerek}
	<script type="text/javascript">//<![CDATA[
		bid    = new Array();
		pid    = new Array();
		mid    = new Array();
		pic    = new Array();
		width  = new Array();
		height = new Array();
		type   = new Array();
		reload = new Array();
		code   = new Array();

		{$bannerek}
	//]]>
	</script>
{/if}

{if $ajax.link}<script type="text/javascript" src="{$ajax.link}"></script>{/if}
{if $ajax.script}
	<script type="text/javascript">
		//<![CDATA[{$ajax.script}//]]>
	</script>
{/if}

{foreach from=$javascripts item=js}
{if preg_match("/\.js/", $js)}
	<script type="text/javascript" src="{$include_dir}/{$js}"></script>
{else}
	<script type="text/javascript" src="{$include_dir}/{$js}.js"></script>
{/if}
{/foreach}

</body>
</html>
