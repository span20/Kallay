<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_forms.field_list_fieldname}</th>
				<th>{$locale.admin_forms.field_list_fieldtype}</th>
				<th class="last">{$locale.admin_forms.field_list_fieldaction}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.fname}</td>
					<td>{$data.ftype}</td>
					<td class="last">
						{if $data.factive eq "1"}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=fact&amp;field_id={$data.field_id}&amp;form_id={$smarty.get.id}&amp;pageID={$page_id}" title="{$locale.admin_forms.field_list_activate}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=fact&amp;field_id={$data.field_id}&amp;form_id={$smarty.get.id}&amp;pageID={$page_id}" title="{$locale.admin_forms.field_list_deactivate}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=fmod&amp;field_id={$data.field_id}&amp;form_id={$smarty.get.id}&amp;pageID={$page_id}" title="{$locale.admin_forms.field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_forms.confirm_fielddel}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=fdel&amp;field_id={$data.field_id}&amp;form_id={$smarty.get.id}&amp;pageID={$page_id}';" title="{$locale.admin_forms.field_list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="3" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_forms.warning_no_group}" />
						{$locale.admin_forms.warning_no_fields}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>