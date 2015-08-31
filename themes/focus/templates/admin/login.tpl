<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
	<title>{$sitename}: {$lang_admin.strAdminTitle}</title>
<!--	<link rel="stylesheet" type="text/css" href="{$theme_dir}/ishark.css" /> !-->
	<link rel="stylesheet" type="text/css" href="{$theme_dir}/login.css" />

	<meta http-equiv="Content-Type" content="text/html; charset={$lang_admin.strLangCharset}" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$lang_Admin.strLangCharset}" />

	{foreach from=$javascripts item=js}
		<script type="text/javascript" src="{$include_dir}/{$js}.js"></script>
	{/foreach}
</head>

<body>
	<div id="outer">
		<!-- HEADER START -->
		<div id="header">
			<div id="header_left"></div>
			<div id="header_right"></div>
		</div>
		<!-- HEADER END -->

		<!-- MENU START -->
		<div id="menu">&nbsp;</div>
		<!-- MENU END -->
		<div id="middle"><div id="inner">
			<div id="login">
				<div id="l_top"></div>
				<div id="l_content">
					<div class="l_empty"></div>
					<div class="pager"></div>
					<div id="l_form">
						<div id="loginpic"></div>
						<div>
							<p style="font-size: 13px; font-weight: bold; text-align: left; padding-top: 15px;">{$lang_admin.strAdminLoginHeader|upper}</p>
							<form {$form.attributes}>
							{$form.hidden}
								<p style="text-align: left; font-weight: bold;">
									<span>
										{$form.name.label}
									</span><br />
									<span>
										{if $form.name.error}<font color="red">{$form.name.error}</font><br />{/if}{$form.name.html}
									</span>
								</p>
								<p style="text-align: left; font-weight: bold;">
									<span>
										{$form.pass.label}
									</span><br />
									<span>
										{if $form.pass.error}<font color="red">{$form.pass.error}</font><br />{/if}{$form.pass.html}
									</span>
								</p>
								<p style="text-align: left;">{if $form.requirednote and not $form.frozen}{$form.requirednote}{/if}{$form.submit.html}{$form.reset.html}</p>
							</form>
						</div>
					</div>
					<div class="pager"></div>
					<div class="l_empty"></div>
				</div>
				<div id="l_bottom"></div>
			</div>
		</div></div>
		<!-- FOOTER START -->
		<div id="footer">
			<div id="footer_left">{$lang_admin.strFooterShark}</div>
			<div id="footer_right">{$lang_admin.strFooterCopyright}</div>
		</div>
		<!-- FOOTER END-->
	</div>
</body>
</html>
