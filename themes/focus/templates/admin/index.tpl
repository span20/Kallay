<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
	<title>{$sitename}: {$locale.admin.title_admin}</title>
	<link rel="stylesheet" type="text/css" href="{$theme_dir}/ishark.css" />

	<meta http-equiv="Content-Type" content="text/html; charset={$locale_charset}" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$locale_charset}" />

	{foreach from=$javascripts item=js}
		<script type="text/javascript" src="{$include_dir}/{$js}.js"></script>
	{/foreach}

	{if $ajax.link}
		<script type="text/javascript" src="{$ajax.link}"></script>
	{/if}
	{if $ajax.script}
		<script type="text/javascript">
			//<![CDATA[{$ajax.script}//]]>
		</script>
	{/if}
</head>

<body {if $bodyonload}onLoad="{foreach from=$bodyonload item=load}{$load};{/foreach}"{/if}>
	<div id="container_main">
		<!-- HEADER START -->
		<div id="header">
			<div id="header_left"></div>
			<div id="header_center"></div>
			<div id="header_right"></div>
		</div>
		<!-- HEADER END -->

		<!-- MENU START -->
		<div id="menu">
		{foreach name=menu from=$admin_menu item=data}
			<span>{if !$smarty.foreach.menu.first}|{/if}<a class="menu" href="admin.php?p={$data.mfile}" title="{$data.mname}">{$data.mname}</a></span>
		{/foreach}
		</div>
		<!-- MENU END -->

		<!-- BREADCRUMB START -->
		<div id="breadcrumb">
		{foreach name=bcrumb from=$breadcrumb item=bcdata}
			{if !$smarty.foreach.bcrumb.first}
			&#x95;
			{/if}
			{if $bcdata.link==''}
			<span>{$bcdata.title|htmlspecialchars}</span>
			{else}
			<a href="{$bcdata.link}" title="{$bcdata.title|htmlspecialchars}">{$bcdata.title|htmlspecialchars}</a>
			{/if}
		{/foreach}
		</div>
		<!-- BREADCRUMB END -->

		<!-- CONTENT START -->
		{if $page != ""}
			<div id="control">
				<div id="title">
					{if isset($title_module)}
						{assign var="modulepic" value=$smarty.get.p}
						<img src="{$theme_dir}/images/admin/{$modulepic}_small.jpg" border="0" alt="{$title_module.title}" align="middle" />
						<span>{$title_module.title}</span>
					{/if}
				</div>
				<div id="icons">
					{if isset($add_new)}
						{foreach from=$add_new item=data}
						<a href="{$data.link}" title="{$data.title}" accesskey="A">
							<img src="{$theme_dir}/images/admin/{$data.pic}" border="0" alt="{$data.title}" align="middle" />
						</a>
						{/foreach}
					{/if}
					{if isset($back_arrow)}
						<a href="{$back_arrow}" title="{$locale.admin.back}" accesskey="B">
							<img src="{$theme_dir}/images/admin/back.jpg" border="0" alt="{$locale.admin.back}" align="middle" />
						</a>
					{else}
						<a href="javascript:history.back(-1)" title="{$locale.admin.back}" accesskey="B">
							<img src="{$theme_dir}/images/admin/back.jpg" alt="{$locale.admin.back}" border="0" align="middle" />
						</a>
					{/if}
					<a href="admin.php" title="{$locale.admin.center}" accesskey="C"><img src="{$theme_dir}/images/admin/center.jpg" alt="{$locale.admin.center}" border="0" align="middle" /></a>
					<span class="logout">
      					<a href="index.php" title="{$locale.admin.backtomain_link}" accesskey="M">
       					<img src="{$theme_dir}/images/admin/backtomain.jpg" alt="{$locale.admin.backtomain_link}" border="0" />
      					</a>
      					<a href="index.php?p=account&amp;act=account_out" title="{$locale.admin.logout}" accesskey="L">
       					<img src="{$theme_dir}/images/admin/logout.jpg" alt="{$locale.admin.logout}" border="0" />
      					</a>
     				</span>
				</div>
			</div>
			{include file="admin/$page.tpl"}
		{else}
			<div id="control">
				<div id="title">
					<img src="{$theme_dir}/images/admin/center_small.jpg" border="0" alt="{$title_admin.title}" align="middle" />
					<span>{$title_admin.title|upper}</span>
				</div>
				<div id="icons">
					<a href="javascript:history.back(-1)" title="{$locale.admin.back}" accesskey="B"><img src="{$theme_dir}/images/admin/back.jpg" alt="{$locale.admin.back}" border="0" align="middle" /></a>
					<a href="admin.php" title="{$locale.admin.center}" accesskey="C"><img src="{$theme_dir}/images/admin/center.jpg" alt="{$locale.admin.center}" border="0" /></a>
					<span class="logout">
      					<a href="index.php" title="{$locale.admin.backtomain_link}" accesskey="M">
       					<img src="{$theme_dir}/images/admin/backtomain.jpg" alt="{$locale.admin.backtomain_link}" border="0" />
      					</a>
      					<a href="index.php?p=account&amp;act=account_out" title="{$locale.admin.logout}" accesskey="L">
       					<img src="{$theme_dir}/images/admin/logout.jpg" alt="{$locale.admin.logout}" border="0" />
      					</a>
     				</span>
				</div>
			</div>
			<div id="cnt">
				<div id="c_top"></div>
				<div id="c_content">
					{foreach name=menu from=$admin_menu item=data}
						<a href="admin.php?p={$data.mfile}" class="linkopacity c_icon" title="{$data.mname}">
							<img src="{$theme_dir}/images/admin/{$data.mfile}.jpg" class="c_image" alt="{$data.mname}" border="0" />
							<span class="c_link">{$data.mname}</span>
						</a>
					{/foreach}
				</div>
				<div id="c_bottom"></div>
			</div>
		{/if}
		<!-- CONTENT END -->

		<!-- FOOTER START -->
		<div id="footer">
			<div id="footer_left"></div>
			<div id="footer_right">{$locale.admin.copyright}</div>
		</div>
		<!-- FOOTER END-->
	</div>

<script language="JavaScript" type="text/javascript" src="{$include_dir}/wz_tooltip.js"></script>
</body>
</html>
