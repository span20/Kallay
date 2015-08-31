<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager"></div>
			<form {$form_search.attributes}>
			{$form_search.hidden}
				<table>
					<tr class="{cycle values="row1,row2"}">
						<td class="form">{if $form_search.searchtext.required}<span class="error">*</span>{/if}{$form_search.searchtext.label}</td>
						<td>{$form_search.searchtext.html}{if $form_search.searchtext.error}<span class="error">{$form_search.searchtext.error}</span>{/if}</td>
					</tr>
					<tr class="{cycle values="row1,row2"}">
						<td class="form">{if $form_search.searchtype.required}<span class="error">*</span>{/if}{$form_search.searchtype.label}</td>
						<td>{$form_search.searchtype.html}{if $form_search.searchtype.error}<span class="error">{$form_search.searchtype.error}</span>{/if}</td>
					</tr>
					<tr class="{cycle values="row1,row2"}">
					<td class="form" colspan="2">
						{if not $form_search.frozen}
							{if $form_search.requirednote}{$form_search.requirednote}{/if}
							{$form_search.submit.html}{$form_search.reset.html}
						{/if}
					</td>
				</tr>
				</table>
			</form>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>

	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=shop" title="{$locale.admin_shop.search_tabs_result}">{$locale.admin_shop.search_tabs_result}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter"></div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_shop.search_field_list_lang}</th>
				<th>{$locale.admin_shop.search_field_list_item}</th>
				<th>{$locale.admin_shop.search_field_list_name}</th>
				<th>{$locale.admin_shop.search_field_list_add}</th>
				<th>{$locale.admin_shop.search_field_list_add_date}</th>
				<th>{$locale.admin_shop.search_field_list_mod}</th>
				<th>{$locale.admin_shop.search_field_list_mod_date}</th>
				<th class="last">{$locale.admin_shop.search_field_list_action}</th>
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
				<td>
					{if $data.item}
						{$data.item}
					{else}
						-
					{/if}
				</td>
				<td>{$data.pname}</td>
				<td>{$data.ausr}</td>
				<td>{$data.adate}</td>
				<td>{$data.musr}</td>
				<td>{$data.mdate}</td>
				<td class="last">
					{if $this_page == "prod"}
						{if $data.isact == 1}
							<a class="action act" href="admin.php?p=shop&amp;act=products&amp;sub_act=act&amp;pid={$data.pid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p=shop&amp;act=products&amp;sub_act=act&amp;pid={$data.pid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p=shop&amp;act=products&amp;sub_act=mod&amp;pid={$data.pid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.search_confirm_del}')) document.location.href='admin.php?p=shop&amp;act=products&amp;sub_act=del&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;pid={$data.pid}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}';" title="{$locale.admin_shop.search_field_list_del}"></a>
						{if $smarty.session.site_shop_ordertype == 2}
							<a href="admin.php?p=shop&amp;act=products&amp;sub_act=ord&amp;pid={$data.pid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;way=up&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_way_up}">
								<img src="{$theme_dir}/images/admin/up.gif" border="0" alt="{$locale.admin_shop.search_field_list_way_up}" />
							</a>
							<a href="admin.php?p=shop&amp;act=products&amp;sub_act=ord&amp;pid={$data.pid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;way=down&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_way_down}">
								<img src="{$theme_dir}/images/admin/down.gif" border="0" alt="{$locale.admin_shop.search_field_list_way_down}" />
							</a>
						{/if}
					{elseif $this_page == "grp"}
						{if $data.isact == 1}
							<a class="action act" href="admin.php?p=shop&amp;act=groups&amp;sub_act=act&amp;gid={$data.gid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p=shop&amp;act=groups&amp;sub_act=act&amp;gid={$data.gid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p=shop&amp;act=groups&amp;sub_act=mod&amp;gid={$data.gid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.search_confirm_del}')) document.location.href='admin.php?p=shop&amp;act=groups&amp;grp_act=del&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;gid={$data.gid}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}';" title="{$locale.admin_shop.search_field_list_del}"></a>
						{if $smarty.session.site_shop_ordertype == 2}
							<a href="admin.php?p=shop&amp;act=groups&amp;sub_act=ord&amp;gid={$data.gid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;way=up&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_way_up}">
								<img src="{$theme_dir}/images/admin/up.gif" border="0" alt="{$locale.admin_shop.search_field_list_way_up}" />
							</a>
							<a href="admin.php?p=shop&amp;act=groups&amp;sub_act=ord&amp;gid={$data.gid}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;way=down&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_way_down}">
								<img src="{$theme_dir}/images/admin/down.gif" border="0" alt="{$locale.admin_shop.search_field_list_way_down}" />
							</a>
						{/if}
					{elseif $this_page == "cat"}
						{if $data.isact == 1}
							<a class="action act" href="admin.php?p=shop&amp;act=categories&amp;sub_act=act&amp;cid={$data.cid}&amp;par={$data.par}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p=shop&amp;act=categories&amp;sub_act=act&amp;cid={$data.cid}&amp;par={$data.par}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p=shop&amp;act=categories&amp;sub_act=mod&amp;cid={$data.cid}&amp;par={$data.par}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_shop.search_confirm_del}')) document.location.href='admin.php?p=shop&amp;act=categories&amp;sub_act=del&amp;cid={$data.cid}&amp;par={$data.par}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}';" title="{$locale.admin_shop.search_field_list_del}"></a>
						{if $smarty.session.site_shop_ordertype == 2}
							<a href="admin.php?p=shop&amp;act=categories&amp;sub_act=ord&amp;cid={$data.cid}&amp;par={$data.par}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;way=up&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_way_up}">
								<img src="{$theme_dir}/images/admin/up.gif" border="0" alt="{$locale.admin_shop.search_field_list_way_up}" />
							</a>
							<a href="admin.php?p=shop&amp;act=categories&amp;sub_act=ord&amp;cid={$data.cid}&amp;par={$data.par}&amp;s=1&amp;cat_fil={$smarty.get.cat_fil}&amp;way=down&amp;field_order={$smarty.get.field_order}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_shop.search_field_list_way_down}">
								<img src="{$theme_dir}/images/admin/down.gif" border="0" alt="{$locale.admin_shop.search_field_list_way_down}" />
							</a>
						{/if}
					{/if}
				</td>
			</tr>
			{foreachelse}
				<tr>
					<td colspan="8" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.search_warning_empty}" />
						{$locale.admin_shop.search_warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
