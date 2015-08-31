<script type="text/javascript">//<![CDATA[
function torol(nid) {literal} { {/literal}
	x = confirm('{$locale.admin_banners.confirm_del_owner}');
	if (x) {literal} { {/literal}
		document.location.href='admin.php?p={$self}&act={$this_page}&sub_act=odel&oid='+nid
	{literal} }
} {/literal}
//]]>
</script>

<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_banners.field_list_owner_name}</th>
				<th>{$locale.admin_banners.field_list_owner_contact}</th>
				<th>{$locale.admin_banners.field_list_owner_email}</th>
				<th>{$locale.admin_banners.field_list_owner_phone}</th>
				<th class="last">{$locale.admin_banners.field_list_owner_action}</th>
			</tr>
			{foreach from=$page_data item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">{$data.owner_name}</td>
				<td>{$data.kapcs_tarto}</td>
				<td>{$data.email}</td>
				<td>{$data.telefon}</td>
				<td class="last">
					<a class="action" style="background: url({$theme_dir}/images/admin/bannersmod.gif) no-repeat top left;" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=blst&amp;oid={$data.owner_id}" title="{$locale.admin_banners.field_list_owner_banners}"></a>
					<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=omod&amp;oid={$data.owner_id}" title="{$locale.admin_banners.field_list_owner_modify}"></a>
					<a class="action del" href="javascript: torol({$data.owner_id});" title="{$locale.admin_banners.field_list_owner_delete}"></a>
				</td>
			</tr>
			{foreachelse}
				<tr>
					<td colspan="5" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_banners.warning_no_owners}" />
						{$locale.admin_banners.warning_no_owners}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>