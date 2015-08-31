<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
				<input type="hidden" name="act" value="{$this_page}">
				{$locale.admin_contents.category_tpl_orderby}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_contents.category_tpl_list_name}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_contents.category_tpl_list_adduser}</option>
					<option value="3" {$fieldselect3}>{$locale.admin_contents.category_tpl_list_adddate}</option>
					<option value="4" {$fieldselect4}>{$locale.admin_contents.category_tpl_list_moduser}</option>
					<option value="5" {$fieldselect5}>{$locale.admin_contents.category_tpl_list_moddate}</option>
				</select>
				{$locale.admin_contents.category_tpl_adminby}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_contents.category_tpl_orderasc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_contents.category_tpl_orderdesc}</option>
				</select>
				{$locale.admin_contents.category_tpl_order}
				<input type="submit" name="submit" value="{$locale.admin_contents.category_tpl_submitorder}" class="submit_filter">
			</form>
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_contents.category_tpl_list_lang}</th>
				<th>{$locale.admin_contents.category_tpl_list_name}</th>
				<th>{$locale.admin_contents.category_tpl_list_adduser}</th>
				<th>{$locale.admin_contents.category_tpl_list_adddate}</th>
				<th>{$locale.admin_contents.category_tpl_list_moduser}</th>
				<th>{$locale.admin_contents.category_tpl_list_moddate}</th>
				<th class="last">{$locale.admin_contents.category_tpl_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
				<td class="first">
					{assign var="flag" value=$data.clang}
					{assign var="flagpic" value="flag_$flag.gif"}
					{if file_exists("$theme_dir/images/admin/$flagpic")}
						<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$data.mlang}" />
					{else}
						{$data.mlang}
					{/if}
				</td>
					<td>{$data.cname}</td><td>{$data.add_name}</td><td>{$data.add_date}</td><td>{$data.mod_name}</td><td>{$data.mod_date}</td>
					<td class="last">
						{if $data.cact == 1}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.category_tpl_list_inactivate}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.category_tpl_list_activate}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.category_tpl_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_contents.category_tpl_confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_contents.category_tpl_list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_contents.category_tpl_warning_no_category}" />
						{$locale.admin_contents.category_tpl_warning_no_category}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
