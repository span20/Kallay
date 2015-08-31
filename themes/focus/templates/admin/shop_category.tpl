{if !empty($tiny_fields)}
	<script type="text/javascript" src="{$libs_dir}/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
	tinyMCE.init(
	{literal}
	{
	{/literal}
		mode                                : "exact",
		elements                            : "{$tiny_fields}",
		theme_advanced_layout_manager       : "SimpleLayout",
		theme                               : "{$smarty.session.site_mce_theme}",
		language                            : "{$smarty.session.site_mce_lang}",
		external_link_list_url              : "includes/linklist.php",
		plugins                             : "table,advlink,advimage,simplebrowser,emotions,paste,preview",
		plugin_simplebrowser_width          : "800",
		plugin_simplebrowser_height         : "600",
		plugin_simplebrowser_browselinkurl  : 'simplebrowser/browser.html?Connector=connectors/php/connector.php',
		plugin_simplebrowser_browseimageurl : 'simplebrowser/browser.html?Type=Image&Connector=connectors/php/connector.php',
		plugin_simplebrowser_browseflashurl : 'simplebrowser/browser.html?Type=Flash&Connector=connectors/php/connector.php',
		theme_advanced_buttons2_add			: "separator,forecolor,backcolor,emotions,preview",
		theme_advanced_buttons3_add         : "separator,tablecontrols,separator,pasteword",
		content_css                         : "{$theme_dir}/{$smarty.session.site_mce_css}",
		width                               : "680",
		theme_advanced_toolbar_location     : "top",
		theme_advanced_statusbar_location   : "bottom",
		convert_urls                        : true,
		entity_encoding						: "raw",
        plugin_preview_width                : "{$smarty.session.site_mce_pagewidth}"
	{literal}
	}
	{/literal}
	);
	</script>
{/if}

<div id="form_cnt">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
			<h3 style="margin:0;">{$lang_title|upper}</h3>
		</div>
		<form {$form_shop.attributes}>
		{$form_shop.hidden}
		<table>
			{if $smarty.session.site_multilang == 1}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.languages.required}<span class="error">*</span>{/if}{$form_shop.languages.label}</td>
				<td>{$form_shop.languages.html}{if $form_shop.languages.error}<span class="error">{$form_shop.languages.error}</span>{/if}</td>
			</tr>
			{/if}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.name.required}<span class="error">*</span>{/if}{$form_shop.name.label}</td>
				<td>{$form_shop.name.html}{if $form_shop.name.error}<span class="error">{$form_shop.name.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.date_start.required}<span class="error">*</span>{/if}{$form_shop.date_start.label}</td>
				<td>{$form_shop.date_start.html}{if $form_shop.date_start.error}<span class="error">{$form_shop.date_start.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.date_end.required}<span class="error">*</span>{/if}{$form_shop.date_end.label}</td>
				<td>{$form_shop.date_end.html}{if $form_shop.date_end.error}<span class="error">{$form_shop.date_end.error}</span>{/if}</td>
			</tr>
			{if $smarty.session.site_shop_mainpic == 1}
				<tr class="{cycle values="row1,row2"}">
					<td class="form">{if $form_shop.picture.required}<span class="error">*</span>{/if}{$form_shop.picture.label}</td>
					<td>{$form_shop.picture.html}{if $form_shop.picture.error}<span class="error">{$form_shop.picture.error}</span>{/if}</td>
				</tr>
				{if $filename != ""}
				<tr class="{cycle values="row1,row2"}">
					<td colspan="2" align="center">
						<img src="{$picture}" border="0" alt="" />&nbsp;
						<input type="hidden" name="oldpic_name" value="{$filename}"><br />
						<input type="checkbox" name="delpic">{$locale.admin_shop.category_field_list_picdel}
					</td>
				</tr>
				{/if}
			{/if}
			{if $smarty.session.site_shop_groupuse == 1}
				<tr class="{cycle values="row1,row2"}">
					<td class="form">{if $form_shop.groups.required}<span class="error">*</span>{/if}{$form_shop.groups.label}</td>
					<td>{$form_shop.groups.html}{if $form_shop.groups.error}<span class="error">{$form_shop.groups.error}</span>{/if}</td>
				</tr>
			{else}
				<tr class="{cycle values="row1,row2"}">
					<td class="form">{if $form_shop.prods.required}<span class="error">*</span>{/if}{$form_shop.prods.label}</td>
					<td>{$form_shop.prods.html}{if $form_shop.prods.error}<span class="error">{$form_shop.prods.error}</span>{/if}</td>
				</tr>
			{/if}
			<tr class="{cycle values="row1,row2"}">
				<td class="form" colspan="2">
					{if $form_shop.desc.required}<span class="error">*</span>{/if}{$form_shop.desc.label}<br />
					{$form_shop.desc.html}{if $form_shop.desc.error}<span class="error">{$form_shop.desc.error}</span>{/if}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form" colspan="2">
					{if not $form_shop.frozen}
						{if $form_shop.requirednote}{$form_shop.requirednote}{/if}
						{$form_shop.submit.html}{$form_shop.reset.html}
					{/if}
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>
