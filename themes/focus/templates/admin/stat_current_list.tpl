<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter"></div>
		<div class="pager"></div>
		<table>
			<tr>
				<th class="first">{$locale.admin_stat.field_lastaccess}</th>
				<th>{$locale.admin_stat.field_site}</th>
				<th>{$locale.admin_stat.field_doctitle}</th>
				<th>{$locale.admin_stat.field_docurl}</th>
				<th>{$locale.admin_stat.field_host}</th>
				<th class="last">{$locale.admin_stat.field_referer}</th>
			</tr>
			{foreach from=$visitors item=data key=key}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.last_access}</td>
					<td>{$data.site}</td>
					<td>{$data.document}</td>
					<td>{$data.document_url}</td>
					<td>{$data.host}</td>
					<td class="last">{$data.referer}</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_active}" />
						{$locale.admin_stat.warning_no_active}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
