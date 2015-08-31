<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_flats.flat}</th>
				<th class="last">{$locale.admin_contents.news_tpl_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">
						{$data.name}
					</td>
					<td class="last">
                        <a class="action del" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=fields_del&amp;id={$data.id}" title="{$locale.admin_contents.news_tpl_list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_contents.news_tpl_warning_no_news}" />
						{$locale.admin_contents.news_tpl_warning_no_news}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>

