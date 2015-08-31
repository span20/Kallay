<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
			<div style="float: left;">
				<form action="admin.php" method="get">
					<input type="hidden" name="p" value="shop" />
					<input type="hidden" name="act" value="products" />
					<input type="hidden" name="cat_fil" value="{$smarty.get.cat_fil}" />
					{$locale.admin_shop.products_field_orderby}
					<select name="field">
						<option value="1" {$fieldselect1}>{$locale.admin_shop.products_field_list_name}</option>
						<option value="2" {$fieldselect2}>{$locale.admin_shop.products_field_list_add}</option>
						<option value="3" {$fieldselect3}>{$locale.admin_shop.products_field_list_add_date}</option>
						<option value="4" {$fieldselect4}>{$locale.admin_shop.products_field_list_mod}</option>
						<option value="5" {$fieldselect5}>{$locale.admin_shop.products_field_list_mod_date}</option>
					</select>
					{$locale.admin_shop.products_field_adminby}
					<select name="ord">
						<option value="asc" {$ordselect1}>{$locale.admin_shop.products_field_asc}</option>
						<option value="desc" {$ordselect2}>{$locale.admin_shop.products_field_desc}</option>
					</select>
					{$locale.admin_shop.products_field_order}
					<input type="submit" name="submit" value="{$locale.admin_shop.products_field_submit_filter}" class="submit_filter">
				</form>
			</div>
			<div style="float: right; padding-right: 5px;">
				{$locale.admin_shop.products_field_filter}
					<select name="cat_filter" onchange="window.location='admin.php?p={$self}&amp;act={$this_page}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;cat_fil='+this.value;">
					{foreach from=$katok key=key item=cats}
						<option value="{$key}" {$catselect.$key}>{$cats}</option>
					{/foreach}
					</select>
			</div>
		</div>
		<div class="pager" style="clear: both;">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_shop.products_field_list_lang}</th>
				<th>{$locale.admin_shop.products_field_list_item}</th>
				<th>{$locale.admin_shop.products_field_list_name}</th>
				<th>{$locale.admin_shop.products_field_list_add}</th>
				<th>{$locale.admin_shop.products_field_list_add_date}</th>
				<th>{$locale.admin_shop.products_field_list_mod}</th>
				<th>{$locale.admin_shop.products_field_list_mod_date}</th>
				<th class="last">{$locale.admin_shop.products_field_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">
						{assign var="flag" value=$data.plang}
						{assign var="flagpic" value="flag_$flag.gif"}
						{if file_exists("$theme_dir/images/admin/$flagpic")}
							<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$data.plang}" />
						{else}
							{$data.plang}
						{/if}
						{if $data.ispref == 1}
							<img src="{$theme_dir}/images/admin/preferred.gif" alt="" />
						{/if}
					</td>
					<td>{$data.item}</td>
					<td>{$data.pname}</td>
					<td>{$data.ausr}</td>
					<td>{$data.adate}</td>
					<td>{$data.musr}</td>
					<td>{$data.mdate}</td>
					<td class="last">
						{if $data.isact == 1}
                            <a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;pid={$data.pid}&amp;cat_fil={$smarty.get.cat_fil}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_shop.products_field_list_inactive}"></a>
						{else}
                            <a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;pid={$data.pid}&amp;cat_fil={$smarty.get.cat_fil}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_shop.products_field_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;pid={$data.pid}&amp;cat_fil={$smarty.get.cat_fil}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_shop.products_field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.products_confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;cat_fil={$smarty.get.cat_fil}&amp;pid={$data.pid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_shop.products_field_list_del}"></a>
						{if $smarty.session.site_shop_ordertype == 2}
                            <a class="action up" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=ord&amp;pid={$data.pid}&amp;cat_fil={$smarty.get.cat_fil}&amp;way=up&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_shop.products_field_list_way_up}"></a>
                            <a class="action down" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=ord&amp;pid={$data.pid}&amp;cat_fil={$smarty.get.cat_fil}&amp;way=down&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_shop.products_field_list_way_down}"></a>
						{/if}
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="7" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.products_warning_empty}" />
						{$locale.admin_shop.products_warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>