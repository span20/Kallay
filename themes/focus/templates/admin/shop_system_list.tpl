<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=shop_system&amp;act=mod" title="{$locale.admin_shop.system_list_title}">{$locale.admin_shop.system_list_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_shop.system_list_value}</th>
				<th>{$locale.admin_shop.system_list_type}</th>
				<th>{$locale.admin_shop.system_list_display}</th>
				<th class="last">{$locale.admin_shop.system_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.pvalue}</td>
					<td>{$data.ptype}</td>
					<td>{$data.pdisplay}</td>
					<td class="last">
						<a class="action mod" href="admin.php?p=shop_system&amp;act=mod&amp;type=mod&amp;pid={$data.pid}" title="{$locale.admin_shop.system_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.system_list_delconf}')) document.location.href='admin.php?p=shop_system&amp;act=del&amp;type=del&amp;pid={$data.pid}';" title="{$locale.admin_shop.system_list_deletel}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.system_warning_empty}" />
						{$locale.admin_shop.system_warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
