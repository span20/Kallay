<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
				<input type="hidden" name="act" value="{$this_page}">
				{$locale.admin_contents.contents_tpl_orderby}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_contents.contents_tpl_list_title}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_contents.contents_tpl_list_lang}</option>
					{if $smarty.session.site_conttimer == 1}
						<option value="3" {$fieldselect4}>{$locale.admin_contents.contents_tpl_list_timerstart}</option>
						<option value="4" {$fieldselect5}>{$locale.admin_contents.contents_tpl_list_timerend}</option>
					{/if}
				</select>
				{$locale.admin_contents.contents_tpl_adminby}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_contents.contents_tpl_orderasc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_contents.contents_tpl_orderdesc}</option>
				</select>
				{$local.admin_contents.contents_tpl_order}
				<input type="submit" name="submit" value="{$locale.admin_contents.contents_tpl_submitorder}" class="submit_filter">
			</form>
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first" style="width:40px;">{$locale.admin_contents.contents_tpl_list_lang}</th>
				<th>{$locale.admin_contents.contents_tpl_list_title}</th>
				{if $smarty.session.site_conttimer == 1}
					<th>{$locale.admin_contents.contents_tpl_list_timerstart}</th>
					<th>{$locale.admin_contents.contents_tpl_list_timerend}</th>
				{/if}
				<th class="last" style="width: 100px;">{$locale.admin_contents.contents_tpl_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
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
					<td>{$data.ctitle}</td>
					{if $smarty.session.site_conttimer == 1}
						<td>{$data.cstart}</td><td>{$data.cend}</td>
					{/if}
					<td class="last">
						{if $data.cact == 1}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.contents_tpl_list_inactivate}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.contents_tpl_list_activate}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.contents_tpl_list_modify}"></a>
                        {if $data.cid neq 95 && $data.cid neq 96 && $data.cid neq 100 && $data.cid neq 101 && $data.cid neq 102}
						<a class="action del" href="javascript: if (confirm('{$locale.admin_contents.contents_tpl_confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_contents.contents_tpl_list_delete}"></a>
                        {/if}
						{if !empty( $data.versions ) AND !empty( $smarty.session.site_cnt_version )}
                            <a href="javascript: trSwitcher( {$data.cid} );" title="{$locale.admin_contents.contents_tpl_list_versions}"><img src="{$theme_dir}/images/admin/versions.gif" alt="{$locale.admin_contents.contents_tpl_list_versions}" /></a>
						{/if}
					</td>
				</tr>
				{if !empty( $data.versions ) AND !empty( $smarty.session.site_cnt_version )}
				{assign var=td_colspan value=3}
				{if $smarty.session.site_conttimer == 1}{assign var=td_colspan value=$td_colspan+2}{/if}
				<tr id="{$data.cid}" style="display: none;">
					<td colspan="{$td_colspan}">
						<table style="margin-left: 15px; width: 730px;">
							<tr style="border-bottom: 1px solid #688da8;">
								<td class="first">{$locale.admin_contents.contents_tpl_list_versiontitle}</td>
								<td>{$locale.admin_contents.contents_tpl_list_versiondate}</td>
								<td>{$locale.admin_contents.contents_tpl_list_versionauthor}</td>
								<td>{$locale.admin_contents.contents_tpl_list_versionaction}</td>
							</tr>
							{foreach from=$data.versions key=key item=version}
							<tr>
								<td class="first">{$version.title}</td>
								<td>{$version.mod_date}</td>
								<td>{$version.author}</td>
								<td>
                                    <a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=show&amp;cvid={$key}">{$locale.admin_contents.contents_tpl_list_versionshow}</a>
                                    <a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=restore&amp;cid={$data.cid}&amp;restore_version={$key}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" onClick="return confirm('{$locale.admin_contents.contents_tpl_confirm_restore}');">{$locale.admin_contents.contents_tpl_list_versionrestore}</a>
                                </td>
							</tr>
							{/foreach}
						</table>
					</td>
				</tr>
				{/if}
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_contents.contents_tpl_warning_no_content}" />
						{$locale.admin_contents.contents_tpl_warning_no_content}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
