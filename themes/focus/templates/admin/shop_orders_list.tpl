<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
			<div style="float: left;">
				<form action="admin.php" method="get">
					<input type="hidden" name="p" value="shop">
					<input type="hidden" name="act" value="ord">
					{$locale.admin_shop.orders_orderby}
					<select name="field_order">
						<option value="1" {$fieldselect1}>{$locale.admin_shop.orders_field_list_id2}</option>
						<option value="2" {$fieldselect2}>{$locale.admin_shop.orders_field_list_name}</option>
						<option value="3" {$fieldselect3}>{$locale.admin_shop.orders_field_list_date}</option>
						<option value="4" {$fieldselect4}>{$locale.admin_shop.orders_field_list_shipping}</option>
					</select>
					{$locale.admin_shop.orders_adminby}
					<select name="ord">
						<option value="asc" {$ordselect1}>{$locale.admin_shop.orders_asc}</option>
						<option value="desc" {$ordselect2}>{$locale.admin_shop.orders_desc}</option>
					</select>
					{$locale.admin_shop.orders_order}
					<input type="submit" name="submit" value="{$locale.admin_shop.orders_filter}" class="submit_filter">
				</form>
			</div>
			{*<div style="float: right; padding-right: 5px;">
				{$locale.admin_shop.orders_field_filter}
				<select name="cat_filter" onchange="window.location='admin.php?p={$self}&amp;act={$this_page}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;usr_fil='+this.value;">
					{foreach from=$user_select key=key item=users}
						<option value="{$key}" {$usrselect.$key}>{$users}</option>
					{/foreach}
				</select>
			</div>*}
		</div>
		<div class="pager" style="clear: both;">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_shop.orders_field_list_id2}</th>
				<th>{$locale.admin_shop.orders_field_list_name}</th>
				<th>{$locale.admin_shop.orders_field_list_date}</th>
				<th>{$locale.admin_shop.orders_field_list_shipping}</th>
				<th class="last">{$locale.admin_shop.orders_field_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.oid}</td>
					<td>
						{if $data.uname}{$data.uname}{/if}
						{if $data.nuname}{$data.nuname}{/if}
					</td>
					<td>{$data.odate}</td>
					<td>{$data.stext}</td>
					<td class="last">
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;oid={$data.oid}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;usr_fil={$smarty.get.usr_fil}" title="{$locale.admin_shop.orders_field_list_mod}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="5" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.orders_warning_empty}" />
						{$locale.admin_shop.orders_warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>