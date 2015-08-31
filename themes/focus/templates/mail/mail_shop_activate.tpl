<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>{$sitename}</title>
	<link rel="STYLESHEET" type="text/css" href="{$theme_dir}/style.css">
	{foreach from=$css item=c}
		<link rel="STYLESHEET" type="text/css" href="{$theme_dir}/{$c}.css">
	{/foreach}

	<link rel="shortcut icon" href="{$theme_dir}/images/favicon.ico">

	<meta http-equiv="Content-Type" content="text/html; charset={$shopactivate_charset}">

	{foreach from=$javascripts item=js}
		<script type="text/javascript" src="{$include_dir}/{$js}.js"></script>
	{/foreach}
</head>

<body>
<div>
	<p>{$lang_shopactivate_mail.strShopNotregMailHeader} {$shopactivate_sender}!</p><br />
	<p>{$lang_shopactivate_mail.strShopNotregMailMsg1} <a href="{$smarty.session.site_sitehttp}" title="{$smarty.session.site_sitename}">{$smarty.session.site_sitename}</a> {$lang_shopactivate_mail.strShopNotregMailMsg2}</p>
	<p>{$lang_shopactivate_mail.strShopNotregMailMsg3}</p>
	<p><a href="{$smarty.session.site_sitehttp}/index.php?p=shop&amp;act=act&amp;nid={$shopactivate_lastid}&amp;code={$activate}&amp;sid={$session_id}" title="{$lang_shopactivate_mail.strShopNotregMailMsg4}">{$lang_shopactivate_mail.strShopNotregMailMsg4}</a></p><br />
	<p>{$lang_shopactivate_mail.strShopNotregMailMsg5}</p>
	<p><a href="{$smarty.session.site_sitehttp}" title="{$smarty.session.site_sitename}">{$smarty.session.site_sitename}</a></p>
</div>
</body>
</html>
