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
			<li id="current"><a href="admin.php?p={$self}&amp;act={$this_page}" title="{$locale.admin_classifieds.search_title_search_list}">{$locale.admin_classifieds.search_title_search_list}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter"></div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_classifieds.search_field_advert_lang}</th>
				<th>{$locale.admin_classifieds.search_field_advert_id}</th>
				<th>{$locale.admin_classifieds.search_field_advert_name}</th>
				<th>{$locale.admin_classifieds.search_field_advert_phone}</th>
				<th>{$locale.admin_classifieds.search_field_advert_email}</th>
				<th>{$locale.admin_classifieds.search_field_advert_timerend}</th>
				<th class="last">{$locale.admin_classifieds.search_field_advert_action}</th>
			</tr>
			{foreach from=$page_data item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">
					{assign var="flag" value=$data.lang}
					{assign var="flagpic" value="flag_$flag.gif"}
					{if file_exists("$theme_dir/images/admin/$flagpic")}
						<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$data.lang}" />
					{else}
						{$data.lang}
					{/if}
				</td>
				<td>{$data.id}</td>
				<td>{$data.name}</td>
				<td>{$data.phone}</td>
				<td>{$data.email}</td>
				<td>{$data.timer_end}</td>
				<td class="last">
					{if $data.is_active == 1}
						<a class="action act" href="admin.php?p={$self}&amp;act=adverts&amp;sub_act=act&amp;id={$data.id}&amp;s=1&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_classifieds.search_title_advert_inactivate}"></a>
					{else}
						<a class="action inact" href="admin.php?p={$self}&amp;act=adverts&amp;sub_act=act&amp;id={$data.id}&amp;s=1&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_classifieds.search_title_advert_activate}"></a>
					{/if}
					<a class="action mod" href="admin.php?p={$self}&amp;act=adverts&amp;sub_act=mod&amp;id={$data.id}&amp;s=1&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}" title="{$locale.admin_classifieds.search_title_advert_modify}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_classifieds.search_confirm_advert_delete}')) document.location.href='admin.php?p={$self}&amp;act=adverts&amp;sub_act=del&amp;id={$data.id}&amp;s=1&amp;pageID={$page_id}&amp;searchtext={$smarty.request.searchtext}&amp;searchtype={$smarty.request.searchtype}';" title="{$locale.admin_classifieds.search_title_advert_delete}"></a>
				</td>
			</tr>
			{foreachelse}
				<tr>
					<td colspan="7" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_classifieds.search_warning_advert_empty}" />
						{$locale.admin_classifieds.search_warning_advert_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>