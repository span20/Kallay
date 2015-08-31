<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=banners_system&amp;act=mod" title="{$locale.admin_banners.field_list_system_title}">{$locale.admin_banners.field_list_system_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_banners.field_list_system_name}</th>
				<th>{$locale.admin_banners.field_list_system_width}</th>
				<th>{$locale.admin_banners.field_list_system_height}</th>
				<th>{$locale.admin_banners.field_list_system_adduser}</th>
				<th>{$locale.admin_banners.field_list_system_adddate}</th>
				<th>{$locale.admin_banners.field_list_system_moduser}</th>
				<th>{$locale.admin_banners.field_list_system_moddate}</th>
				<th class="last">{$locale.admin_banners.field_list_system_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.pname}</td>
					<td>{$data.pwidth}</td>
					<td>{$data.pheight}</td>
					<td>{$data.aname}</td>
					<td>{$data.adate}</td>
					<td>{$data.mname}</td>
					<td>{$data.mdate}</td>
					<td class="last">
						<a href="admin.php?p=banners_system&amp;act=mod&amp;type=mod&amp;pid={$data.pid}" title="{$locale.admin_banners.field_list_system_modify}">
							<img src="{$theme_dir}/images/admin/modify.gif" border="0" alt="{$locale.admin_banners.field_list_system_modify}" />
						</a>
						<a href="javascript: if (confirm('{$locale.admin_banners.confirm_del_system_place}')) document.location.href='admin.php?p=banners_system&amp;act=del&amp;type=del&amp;pid={$data.pid}';" title="{$locale.admin_banners.field_list_system_del}">
							<img src="{$theme_dir}/images/admin/delete.gif" border="0" alt="{$locale.admin_banners.field_list_system_del}" />
						</a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="8" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_banners.warning_system_no_place}" />
						{$locale.admin_banners.warning_system_no_place}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>