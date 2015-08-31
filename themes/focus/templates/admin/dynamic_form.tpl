{*
setup: function(ed) {
	ed.addButton('divwrap', {
		title : 'A kiválasztott tartalom 50%-os konténerbe rakása',
		label: '50%-os div',
		onclick : function() { 
			console.log(ed.selection.getContent({format : 'html'}));
			var c = ed.selection.getNode().nodeName;
			if(c != "DIV") {					
				ed.selection.setContent('<div class="col-md-6">' + ed.selection.getContent({format : 'html'}) + '</div>');
			}
			//console.log(ed.selection.getNode());
			//ed.dom.setOuterHTML(ed.selection.getNode(), '<div class="col-md-6">'+ed.dom.getOuterHTML(ed.selection.getNode())+'</div>'); 
		}
   });
}
*}
{if !empty($tiny_fields)}
	<script type="text/javascript" src="{$libs_dir}/tiny_mce_new/tiny_mce.js"></script>
	<script type="text/javascript">
	tinyMCE.init(
	{literal}
	{
	{/literal}
		mode                                : "exact",
		elements                            : "{$tiny_fields}",
		theme_advanced_layout_manager       : "SimpleLayout",
		theme                               : "{$smarty.session.site_mce_theme}",
		language                            : "hu",
		external_link_list_url              : "includes/linklist.php",
		plugins                             : "table,advlink,advimage,emotions,paste,preview",
		file_browser_callback               : "ajaxfilemanager",
		theme_advanced_buttons2_add	        : "separator,forecolor,backcolor,emotions,preview",
		theme_advanced_buttons3_add         : "separator,tablecontrols,separator,pasteword,divwrap",
		content_css                         : "{$theme_dir}/tiny.css",
		width                               : "680",
		theme_advanced_toolbar_location     : "top",
		theme_advanced_statusbar_location   : "bottom",
		convert_urls                        : true,
        extended_valid_elements             : "iframe[src|width|height|name|align|frameborder]",
		entity_encoding			            : "raw",
		plugin_preview_width                : "{$smarty.session.site_mce_pagewidth}",		
	{literal}
	}
	{/literal}
	);
	
	function ajaxfilemanager(field_name, url, type, win) {literal}{{/literal}
            var ajaxfilemanagerurl = "/devel/kallay/libs/tiny_mce_new/plugins/ajaxfilemanager/ajaxfilemanager.php";
            switch (type) {literal}{{/literal}
                case "image":
                    break;
                case "media":
                    break;
                case "flash": //for older versions of tinymce
                    break;
                case "file":
                    break;
                default:
                    return false;
            {literal}}{/literal}
            tinyMCE.activeEditor.windowManager.open({literal}{{/literal}
                url: "/devel/kallay/libs/tiny_mce_new/plugins/ajaxfilemanager/ajaxfilemanager.php",
                width: 782,
                height: 440,
                inline : "yes",
                close_previous : "no"
            {literal}}{/literal},{literal}{{/literal}
                window : win,
                input : field_name
            {literal}}{/literal});
        {literal}}{/literal}
	</script>
{/if}

<div id="form_cnt">
	{if $dynamic_tabs}
		{include file="admin/dynamic_tabs.tpl"}
	{else}
		<div class="tabs">
			<ul>
				<li class="current"><a href="#" title="{$lang_title}">{$lang_title}</a></li>
			</ul>
			<div class="blueleft"></div><div class="blueright"></div>
		</div>
	{/if}
	<div id="f_content">
		{if !isset($dynamic_tabs)}<div class="f_empty"></div>{else}
		<div class="t_filter"><h3 style="margin:0;">{$lang_title|upper}</h3></div>{/if}
		<form{$form.attributes}>
		{$form.hidden}
		<table>
			{foreach item=sec key=i from=$form.sections}
				{foreach item=element from=$sec.elements}
					{if $element.type neq "submit" and $element.type neq "reset"}
					<tr class="{cycle values="row1,row2"}">
						{if $element.type eq "textarea"}
							<td class="form" colspan="2">
								{if $element.required}<span class="error">*</span>{/if}{$element.label}<br />
						{else}
							<td class="form">
								{if $element.required}<span class="error">*</span>{/if}{$element.label}</td>
							<td>
						{/if}
						{if $element.type eq "group"}
							{foreach key=gkey item=gitem from=$element.elements}
								{$gitem.label}
								{if $gitem.type eq "radio"}
									<span class="radio">{$gitem.html}</span>
								{else}
									{$gitem.html}
								{/if}{if $gitem.required}<span class="error">*</span>{/if}
								{if $element.separator}{cycle values=$element.separator}{/if}
							{/foreach}
						{else}
							{$element.html}
						{/if}
						{if $element.error}<span class="error">{$element.error}</span>{/if}
						</td>
					</tr>
					{else}
						{if $element.type neq "reset"}
						<tr>
							<td class="form" colspan="2">
							{if not $form.frozen}
								{if $form.requirednote}{$form.requirednote}{/if}
							{/if}
						{/if}
						{$element.html}
						{if $element.type neq "submit"}
							</td>
						</tr>
						{/if}
					{/if}
				{/foreach}
			{/foreach}
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>
