<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}" />
				{$locale.admin_logs.field_orderby}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_logs.field_list_time}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_logs.field_list_user}</option>
					<option value="3" {$fieldselect3}>{$locale.admin_logs.field_list_username}</option>
					<option value="4" {$fieldselect4}>{$locale.admin_logs.field_list_module}</option>
					<option value="5" {$fieldselect5}>{$locale.admin_logs.field_list_function}</option>
					<option value="6" {$fieldselect6}>{$locale.admin_logs.field_list_description}</option>
				</select>
				{$locale.admin_logs.field_adminby}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_logs.field_orderasc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_logs.field_orderdesc}</option>
				</select>
				{$locale.admin_logs.field_order}
				<input type="submit" name="submit" value="{$locale.admin_logs.field_submitorder}" class="submit_filter" />
			</form>
		</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_logs.field_list_time}</th>
				<th>{$locale.admin_logs.field_list_user}</th>
				<th>{$locale.admin_logs.field_list_username}</th>
				<th>{$locale.admin_logs.field_list_module}</th>
				<th>{$locale.admin_logs.field_list_function}</th>
				<th class="last">{$locale.admin_logs.field_list_description}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.time}</td>
					<td>{$data.name}</td>
					<td>{$data.user_name}</td>
					<td>{$data.module_name}</td>
					<td>{$data.function_desc}</td>
					<td class="last">{$data.description}</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_logs.warning_no_logs}" />
						{$locale.admin_logs.warning_no_logs}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>
