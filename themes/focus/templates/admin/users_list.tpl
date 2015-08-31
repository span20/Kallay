<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.admin_users.title_users_tab}">{$locale.admin_users.title_users_tab}</a></li>
			<li><a href="admin.php?p={$self}&amp;act=search" title="{$locale.admin_users.title_search_tab}">{$locale.admin_users.title_search_tab}</a></li>
			<li><a href="admin.php?p={$self}&amp;act=jatekoslista" title="{$locale.admin_users.title_search_tab}">Játékoslista</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
				{$locale.admin_users.field_orderby}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_users.field_list_name}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_users.field_list_username}</option>
					<option value="3" {$fieldselect3}>{$locale.admin_users.field_list_email}</option>
					<option value="4" {$fieldselect4}>{$locale.admin_users.field_list_deleted}</option>
					<option value="5" {$fieldselect5}>{$locale.admin_users.field_list_public}</option>
					<option value="6" {$fieldselect6}>{$locale.admin_users.field_list_publicmail}</option>
				</select>
				{$locale.admin_users.field_adminby}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_users.field_orderasc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_users.field_orderdesc}</option>
				</select>
				{$locale.admin_users.field_order}
				<input type="submit" name="submit" value="{$locale.admin_users.field_submitorder}" class="submit_filter">
			</form>
		</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_users.field_list_name}</th>
				<th>{$locale.admin_users.field_list_username}</th>
				<th>{$locale.admin_users.field_list_email}</th>
				<th>{$locale.admin_users.field_list_deleted}</th>
				<th>{$locale.admin_users.field_list_public}</th>
				<th>{$locale.admin_users.field_list_publicmail}</th>
				<th class="last">{$locale.admin_users.field_list_action}</th>
			</tr>
			{foreach from=$userlist item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i>{$locale.admin_users.field_list_tooltip}</i></u><br>{$data.grouplist}')">{$data.uname}</a></td>
				<td>{$data.username}</td><td>{$data.umail}</td><td>{$data.udel}</td><td>{$data.upub}</td><td>{$data.upubmail}</td>
				<td class="last">
					{if $data.uact == 1}
						<a class="action act" href="admin.php?p={$self}&amp;act=act&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_users.field_list_inactivate}"></a>
					{else}
						<a class="action inact" href="admin.php?p={$self}&amp;act=act&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_users.field_list_activate}"></a>
					{/if}
					<a class="action mod" href="admin.php?p={$self}&amp;act=mod&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_users.field_list_modify}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_users.confirm_del}')) document.location.href='admin.php?p={$self}&amp;act=del&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_users.field_list_delete}"></a>
				</td>
			</tr>
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_users.warning_no_users}" />
						{$locale.admin_users.warning_no_users}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>
