<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_shop.groups_field_list_lang}</th>
				<th>{$locale.admin_shop.groups_field_list_name}</th>
				<th>{$locale.admin_shop.groups_field_list_add}</th>
				<th>{$locale.admin_shop.groups_field_list_add_date}</th>
				<th>{$locale.admin_shop.groups_field_list_mod}</th>
				<th>{$locale.admin_shop.groups_field_list_mod_date}</th>
				<th class="last">{$locale.admin_shop.groups_field_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">
						{assign var="flag" value=$data.glang}
						{assign var="flagpic" value="flag_$flag.gif"}
						{if file_exists("$theme_dir/images/admin/$flagpic")}
							<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$data.glang}" />
						{else}
							{$data.glang}
						{/if}
					</td>
					<td>{$data.gname}</td>
					<td>{$data.ausr}</td>
					<td>{$data.adate}</td>
					<td>{$data.musr}</td>
					<td>{$data.mdate}</td>
					<td class="last">
						{if $data.isact == 1}
						  <a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;gid={$data.gid}&amp;pageID={$page_id}" title="{$locale.admin_shop.groups_field_list_inactive}"></a>
						{else}
						  <a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;gid={$data.gid}&amp;pageID={$page_id}" title="{$locale.admin_shop.groups_field_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;gid={$data.gid}&amp;pageID={$page_id}" title="{$locale.admin_shop.groups_field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.groups_confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;gid={$data.gid}&amp;pageID={$page_id}';" title="{$locale.admin_shop.groups_field_list_del}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr class="{cycle values="row1,row2"}">
					<td colspan="7" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.groups_warning_empty}" />
						{$locale.admin_shop.groups_warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
