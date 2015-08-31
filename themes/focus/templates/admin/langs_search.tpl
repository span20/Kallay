<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
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
			<li id="current"><a href="admin.php?p=langs" title="{$locale.admin_langs.search_list_result}">{$locale.admin_langs.search_list_result}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter"></div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_langs.search_list_module}</th>
                <th>{$locale.admin_langs.search_list_variable}</th>
				<th>{$locale.admin_langs.search_list_expression}</th>
				<th class="last">{$locale.admin_langs.search_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">{$data.aname}</td>
                <td>{$data.vname}</td>
				<td>{$data.exp}</td>
				<td class="last">
					<a class="action mod" href="admin.php?p=langs&amp;act=langs&amp;sub_act=w_mod&amp;variable_id={$data.vid}&amp;s=1&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}&amp;pageID={$smarty.get.pageID}" title="{$locale.admin_lang.search_list_modify}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_langs.search_confirm_del}')) document.location.href='admin.php?p=langs&amp;act=langs&amp;sub_act=w_del&amp;variable_id={$data.vid}&amp;s=1&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}&amp;pageID={$smarty.get.pageID}';" title="{$locale.admin_langs.search_list_delete}"></a>
				</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="4" class="empty">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_langs.search_warning_empty}" />
					{$locale.admin_langs.search_warning_empty}
				</td>
			</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
