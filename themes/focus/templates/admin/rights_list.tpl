<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="rights">
				{$ocale.admin_rights.field_orderby}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_rights.list_name}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_rights.list_module}</option>
					<option value="3" {$fieldselect3}>{$locale.admin_rights.list_contents}</option>
				</select>
				{$locale.admin_rights.field_adminby}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_rights.field_orderasc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_rights.field_orderdesc}</option>
				</select>
				{$locale.admin_rights.field_order}
				<input type="submit" name="submit" value="{$locale.admin_rights.field_submitorder}" class="submit_filter">
			</form>
		</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_rights.list_name}</th>
				<th>{$locale.admin_rights.list_module}</th>
				<th>{$locale.admin_rights.list_contents}</th>
				<th class="last">{$locale.admin_rights.list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.rname}</td><td>{$data.mtype} | {$data.mname}</td><td>{$data.ctitle}</td>
					<td class="last">
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;rid={$data.rid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$ocale.admin_rights.list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_rights.confirm_del}')) document.location.href='admin.php?p={$self}&amp;sub_act=del&amp;act={$this_page}&amp;rid={$data.rid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_rights.list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_rights.warning_empty_list}" />
						{$locale.admin_rights.warning_empty_list}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
