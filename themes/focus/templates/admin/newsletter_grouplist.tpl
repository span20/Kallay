<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
            <h3 style="margin: 0;">{$lang_title|upper}</h3>
        </div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_newsletter.groups_field_group_name}</th>
				<th class="last">{$locale.admin_newsletter.groups_field_act}</th>
			</tr>
			{foreach from=$page_data item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('{$data.userlist}')">{$data.gname}</a></td>
				<td class="last">
					<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;gid={$data.gid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_newsletter.act_mod}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_newsletter.confirm_group_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;gid={$data.gid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_newsletter.act_del}"></a>
				</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="2" class="empty">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_newsletter.warning_empty_list}" />
					{$locale.admin_newsletter.warning_list_empty}
				</td>
			</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
