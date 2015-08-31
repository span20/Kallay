<div style="padding-left: 5px;">
	<div class="szoveg" style="padding-bottom: 5px; padding-top:10px; padding-left: 5px;">
	<table width="100%" cellspacing="0" cellpadding="3">
		{foreach from=$rss item=data}
			<tr class="row1">
				<td width="40%">{$data.name}</td>
				<td>{$smarty.session.site_sitehttp}/modules/{$data.url}</td>
				<td><a href="{$smarty.session.site_sitehttp}/modules/{$data.url}"><img src="{$theme_dir}/images/xml_button.gif" border="0"></a></td>
			</tr>
			<tr>
				<td colspan="3">{$data.desc}</td>
			</tr>
		{/foreach}
	</table>
	</div>
</div>