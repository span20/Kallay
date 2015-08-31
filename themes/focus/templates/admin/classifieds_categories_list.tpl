<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager"></div>
		<table>
			<tr>
				<th class="first" colspan="2">{$locale.admin_classifieds.category_field_category_list_lang}</th>
				<th>{$locale.admin_classifieds.category_field_category_list_name}</th>
				<th>{$locale.admin_classifieds.category_field_category_list_add}</th>
				<th>{$locale.admin_classifieds.category_field_category_list_adddate}</th>
				<th>{$locale.admin_classifieds.category_field_category_list_mod}</th>
				<th>{$locale.admin_classifieds.category_field_category_list_moddate}</th>
				<th class="last">{$locale.admin_classifieds.category_field_category_list_action}</th>
			</tr>
			{defun name="menu" list=$category_list}
			{foreach from=$list item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">
						{assign var="flag" value=$data.clang}
						{assign var="flagpic" value="flag_$flag.gif"}
						{if file_exists("$theme_dir/images/admin/$flagpic")}
							<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$data.clang}" />
						{else}
							{$data.clang}
						{/if}
					</td>
					<td>
						{if $data.is_sub == "1"}
							<a href="admin.php?p={$self}&amp;act={$this_page}&amp;cid={$data.cid}" style="font-size: 14px; font-weight: bold">
								<img src="{$theme_dir}/images/admin/arrow_down.gif" border="0" alt="">
							</a>
						{/if}
					</td>
					<td {if $data.level != 0}style="padding-left: {$data.level*10}px;"{/if}>
						{$data.title}
					</td>
					<td>{$data.ausr}</td>
					<td>{$data.adate}</td>
					<td>{$data.musr}</td>
					<td>{$data.mdate}</td>
					<td class="last">
						{if $data.isact == 1}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}" title="{$locale.admin_classifieds.category_title_category_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}" title="{$locale.admin_classifieds.category_title_category_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;cid={$data.cid}&amp;par={$data.cparent}" title="{$locale.admin_classifieds.category_title_category_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_classifieds.category_confirm_category_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;cid={$data.cid}';" title="{$locale.admin_classifieds.category_title_category_del}"></a>
						{if $add_new}
							<a class="action submenu" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=add&amp;par={$data.cid}" title="{$locale.admin_classifieds.category_title_category_subcat}"></a>
						{/if}
						<a class="action up" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=ord&amp;cid={$data.cid}&amp;way=up&amp;par={$data.cparent}" title="{$locale.admin_classifieds.category_title_category_wayup}"></a>
						<a class="action down" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=ord&amp;cid={$data.cid}&amp;way=down&amp;par={$data.cparent}" title="{$locale.admin_classifieds.category_title_category_waydown}"></a>
					</td>
				</tr>
			{if $data.element}
				{fun name="menu" list=$data.element}
			{/if}
			{foreachelse}
				<tr>
					<td colspan="8" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_classifieds.category_warning_no_category}" />
						{$locale.admin_classifieds.category_warning_no_category}
					</td>
				</tr>
			{/foreach}
			{/defun}
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>