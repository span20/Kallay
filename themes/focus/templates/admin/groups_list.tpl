<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.admin_groups.field_list_title}">{$locale.admin_groups.field_list_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
				{$locale.admin_groups.field_orderby}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_groups.field_list_name}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_groups.field_list_deleted}</option>
				</select>
				{$locale.admin_groups.field_adminby}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_groups.field_orderasc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_groups.field_orderdesc}</option>
				</select>
				{$locale.admin_groups.field_order}
				<input type="submit" name="submit" value="{$locale.admin_groups.field_submitorder}" class="submit_filter">
			</form>
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_groups.field_list_name}</th>
				<th>{$locale.admin_groups.field_list_deleted}</th>
				<th class="last">{$locale.admin_groups.field_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i>{$locale.admin_groups.field_list_users}</i></u><br>{$data.userlist}')">{$data.gname}</a></td>
					<td>{$data.gdel}</td>
					<td class="last">
						<a class="action mod" href="admin.php?p={$self}&amp;act=mod&amp;gid={$data.gid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_groups.field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_groups.confirm_del}')) document.location.href='admin.php?p={$self}&amp;act=del&amp;gid={$data.gid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_groups.field_list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="3" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_groups.warning_no_group}" />
						{$locale.admin_groups.warning_no_group}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
