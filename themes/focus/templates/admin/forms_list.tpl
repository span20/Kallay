<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.$self_2.field_list_title}">{$locale.$self_2.field_list_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.$self_2.field_list_name}</th>
				<th>{$locale.$self_2.field_list_add_date}</th>
				<th class="last">{$locale.$self_2.field_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.ftitle}</td>
					<td>{$data.add_date}</td>
					<td class="last">
						{if $data.factive eq "1"}
							<a class="action act" href="admin.php?p={$self}&amp;act=act&amp;form_id={$data.fid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.$self.field_list_modify}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act=act&amp;form_id={$data.fid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.$self.field_list_modify}"></a>
							<a href="admin/form_csv.php?form_id={$data.fid}">csv</a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act=mod&amp;fid={$data.fid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.$self.field_list_modify}"></a>
						<a href="admin.php?p={$self}&amp;act=field_lst&amp;form_id={$data.fid}">mezõk kezelése</a>
						<a class="action del" href="javascript: if (confirm('{$locale.$self_2.confirm_del}')) document.location.href='admin.php?p={$self}&amp;act=del&amp;fid={$data.fid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.$self.field_list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="3" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.$self.warning_no_group}" />
						{$locale.$self.warning_no_group}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
