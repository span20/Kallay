<div id="table">
	<div id="ear">
		<ul>
			<li><a href="admin.php?p={$self}" title="{$locale.admin_users.title_users_tab}">{$locale.admin_users.title_users_tab}</a></li>
			<li><a href="admin.php?p={$self}&amp;act=search" title="{$locale.admin_users.title_search_tab}">{$locale.admin_users.title_search_tab}</a></li>
			<li id="current"><a href="admin.php?p={$self}&amp;act=jatekoslista" title="{$locale.admin_users.title_search_tab}">Játékoslista</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter">
		</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_users.field_list_email}</th>
				<th>Név</th>
				<th class="last">Dátum</th>
			</tr>
			{foreach from=$userlist item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">{$data.email}</td>
				<td>{$data.uname}</td>
				<td class="last">{$data.datum}</td>
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
