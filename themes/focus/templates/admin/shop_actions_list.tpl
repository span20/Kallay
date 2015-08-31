<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_shop.action_field_list_name}</th>
				<th>{$locale.admin_shop.actions_field_list_timerstart}</th>
				<th>{$locale.admin_shop.actions_field_list_timerend}</th>
				<th>{$locale.admin_shop.actions_field_list_mod}</th>
				<th>{$locale.admin_shop.actions_field_list_date}</th>
				<th class="last">{$locale.admin_shop.actions_field_list_actions}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.aname}</td>
					<td>{$data.astart}</td>
					<td>{$data.aend}</td>
					<td>{$data.musr}</td>
					<td>{$data.mdate}</td>
					<td class="last">
						{if $data.isact == 1}
						  <a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;aid={$data.aid}&amp;pageID={$page_id}" title="{$locale.admin_shop.actions_field_list_inactive}"></a>
						{else}
						  <a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;aid={$data.aid}&amp;pageID={$page_id}" title="{$locale.admin_shop.actions_field_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;aid={$data.aid}&amp;pageID={$page_id}" title="{$locale.admin_shop.actions_field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.actions_confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;aid={$data.aid}&amp;pageID={$page_id}';" title="{$locale.admin_shop.actions_field_list_del}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr class="{cycle values="row1,row2"}">
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.actions_warning_empty}" />
						{$locale.admin_shop.actions_warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>