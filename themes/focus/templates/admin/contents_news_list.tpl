<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
			<div style="float: left;">
				<form action="admin.php" method="get">
					<input type="hidden" name="p" value="{$self}">
					<input type="hidden" name="act" value="{$this_page}">
					<input type="hidden" name="cat_fil" value="{$smarty.get.cat_fil}">
					{$locale.admin_contents.news_tpl_list_orderby}
					<select name="field">
						<option value="1" {$fieldselect1}>{$locale.admin_contents.news_tpl_list_title}</option>
						<option value="2" {$fieldselect2}>{$locale.admin_contents.news_tpl_list_lang}</option>
						<option value="3" {$fieldselect3}>{$locale.admin_contents.news_tpl_list_adddate}</option>
						<option value="4" {$fieldselect4}>{$locale.admin_contents.news_tpl_list_adduser}</option>
					</select>
					{$locale.admin_contents.news_tpl_list_adminby}
					<select name="ord">
						<option value="asc" {$ordselect1}>{$locale.admin_contents.news_tpl_list_orderasc}</option>
						<option value="desc" {$ordselect2}>{$locale.admin_contents.news_tpl_list_orderdesc}</option>
					</select>
					{$locale.admin_contents.news_tpl_list_order}
					<input type="submit" name="submit" value="{$locale.admin_contents.news_tpl_list_submitorder}" class="submit_filter">
				</form>
			</div>
			{if $category_list}
			<div style="float: right; padding-right: 5px;">
				{$locale.admin_contents.news_tpl_list_filter}
					<select name="cat_filter" onchange="window.location='admin.php?p={$self}&amp;act={$this_page}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}&amp;cat_fil='+this.value;">
					{foreach from=$category_list key=key item=cats}
						<option value="{$key}" {$catselect.$key}>{$cats}</option>
					{/foreach}
					</select>
			</div>
			{/if}
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first" style="width:40px;">{$locale.admin_contents.news_tpl_list_lang}</th>
				<th>{$locale.admin_contents.news_tpl_list_type}</th>
				<th>{$locale.admin_contents.news_tpl_list_title}</th>
				<th>{$locale.admin_contents.news_tpl_list_adddate}</th>
				<th>{$locale.admin_contents.news_tpl_list_adduser}</th>
				<th class="last" style="width: 100px;">{$locale.admin_contents.news_tpl_list_action}</th>
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
					<td>
						{if $data.mnews == 0}
							{$locale.admin_contents.news_tpl_list_typenews}
						{else}
							{$locale.admin_contents.news_tpl_list_typemain}
						{/if}
					</td>
					<td>
                        <a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('{$data.lead|nl2br|escape:javascript}')">{$data.ctitle}</a>
                    </td>
                    <td>{$data.add_date}</td><td>{$data.username}</td>
					<td class="last">
						{if $data.cact == 1}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.news_tpl_list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.news_tpl_list_activate}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_contents.news_tpl_list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_contents.news_tpl_confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;cid={$data.cid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_contents.news_tpl_list_delete}"></a>
						{if !empty( $data.versions ) AND !empty( $smarty.session.site_cnt_version )}
						 <a href="javascript: trSwitcher( {$data.cid} );" title="{$locale.admin_contents.news_tpl_list_versions}"><img src="{$theme_dir}/images/admin/versions.gif" alt="{$locale.admin_contents.news_tpl_list_versions}" /></a>
						{/if}
					</td>
				</tr>
				{if !empty( $data.versions ) AND !empty( $smarty.session.site_cnt_version )}
				{assign var=td_colspan value=4}
				{if $smarty.session.site_conttimer == 1}{assign var=td_colspan value=$td_colspan+2}{/if}
				<tr id="{$data.cid}" style="display: none;">
					<td colspan="{$td_colspan}">
						<table style="margin-left: 15px; width: 730px;">
							<tr style="border-bottom: 1px solid #688da8;">
								<td class="first">{$locale.admin_contents.news_tpl_list_versiontitle}</td>
								<td>{$locale.admin_contents.news_tpl_list_versiondate}</td>
								<td>{$locale.admin_contents.news_tpl_list_versionauthor}</td>
								<td>{$locale.admin_contents.news_tpl_list_versionaction}</td>
							</tr>
							{foreach from=$data.versions key=key item=version}
							<tr>
								<td class="first">{$version.title}</td>
								<td>{$version.mod_date}</td>
								<td>{$version.author}</td>
								<td>
                                    <a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=show&amp;cvid={$key}">{$locale.admin_contents.news_tpl_list_versionshow}</a>
                                    <a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=restore&amp;cid={$data.cid}&amp;restore_version={$key}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" onClick="return confirm('{$locale.admin_contents.news_tpl_confirm_restore}');">{$locale.admin_contents.news_tpl_list_versionrestore}</a></td>
							</tr>
							{/foreach}
						</table>
					</td>
				</tr>
				{/if}
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_contents.news_tpl_warning_no_news}" />
						{$locale.admin_contents.news_tpl_warning_no_news}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
