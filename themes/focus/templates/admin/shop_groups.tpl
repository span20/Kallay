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
        theme_advanced_buttons2_add         : "separator,forecolor,backcolor,emotions,preview",
        theme_advanced_buttons3_add         : "separator,tablecontrols,separator,pasteword",
        content_css                         : "{$theme_dir}/{$smarty.session.site_mce_css}",
        width                               : "680",
        theme_advanced_toolbar_location     : "top",
        theme_advanced_statusbar_location   : "bottom",
        convert_urls                        : true,
        entity_encoding                     : "raw",
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
		<form {$form_shop.attributes} onSubmit="return SelectAll(this);">
		{$form_shop.hidden}
		<table>
			{if $smarty.session.site_multilang == 1}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.languages.required}<span class="error">*</span>{/if}{$form_shop.languages.label}</td>
				<td colspan="2">{$form_shop.languages.html}{if $form_shop.languages.error}<span class="error">{$form_shop.languages.error}</span>{/if}</td>
			</tr>
			{/if}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.name.required}<span class="error">*</span>{/if}{$form_shop.name.label}</td>
				<td colspan="2">{$form_shop.name.html}{if $form_shop.name.error}<span class="error">{$form_shop.name.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">{if $form_shop.category.required}<span class="error">*</span>{/if}{$form_shop.category.label}</td>
				<td colspan="2">{$form_shop.category.html}{if $form_shop.category.error}<span class="error">{$form_shop.category.error}</span>{/if}</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form" style="width: 33%;">
					{if $form_shop.srcList.required}<span class="error">*</span>{/if}{$form_shop.srcList.label}<br />
					<input type="text" name="SearchInput" onKeyUp="JavaScript: searchSelectBox('frm_shop', 'SearchInput', 'srcList')"><br /><br />
					{$form_shop.srcList.html}{if $form_shop.srcList.error}<span class="error">{$form_shop.srcList.error}</span>{/if}
				</td>
				<td valign="top" style="padding-top: 70px; width: 33%;">
					<input type="button" value=" >> " onClick="javascript:addSrcToDestList(0)"><br /><br />
					<input type="button" value=" << " onclick="javascript:deleteFromDestList(0);">
				</td>
				<td valign="top" style="padding-top: 65px; width: 33%;">
					<select size="10" name="destList0[]" id="destList0" multiple="multiple">
					{foreach from=$destList item=data key=key}
						<option value="{$key}">{$data}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form" colspan="3">
					{if $form_shop.desc.required}<span class="error">*</span>{/if}{$form_shop.desc.label}<br />
					{$form_shop.desc.html}{if $form_shop.desc.error}<span class="error">{$form_shop.desc.error}</span>{/if}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form" colspan="3">
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
