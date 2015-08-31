<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div class="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager">
			<div style="float: left; padding-left: 10px;">
				<img src="{$theme_dir}/images/admin/dir.gif" alt="{$locale.admin_downloads.field_location}" /> 
				{$dirlist.0.dir}
			</div>
			<div style="float: right; padding-right: 20px;">
				<a href="admin.php?p={$self}&amp;parent=0" title="{$locale.admin_downloads.list_titleroot}">
					<img src="{$theme_dir}/images/admin/home.gif" border="0" alt="{$locale.admin_downloads.list_titleroot}" />
				</a>&nbsp;&nbsp;
				<a href="admin.php?p={$self}&amp;parent={$dirlist.0.parent}" title="{$locale.admin_downloads.list_titleup}">
					<img src="{$theme_dir}/images/admin/dir_up.gif" alt="{$locale.admin_downloads.list_titleup}" />
				</a>&nbsp;&nbsp;
				<img src="{$theme_dir}/images/admin/totalsize.gif" border="0" alt="{$dirsumsize} KB" />
			</div>
		</div>
		<table style="clear: both;">
			<tr>
				<th class="first">{$locale.admin_downloads.list_name}</th>
				<th>{$locale.admin_downloads.list_size}</th>
				<th>{$locale.admin_downloads.list_date}</th>
				<th class="last">{$locale.admin_downloads.list_action}</th>
			</tr>
			{foreach from=$dirlist item=data}
				{if $data.up != ""}
					<tr class="{cycle values="row1,row2"}" {if $data.type == "D"}style="font-weight: bold;"{/if}>
						<td class="first">
							<a href="admin.php?p={$self}&amp;parent={$data.parent}" title="{$locale.admin_downloads.list_titleup}">
								<img src="{$theme_dir}/images/admin/dir_up.gif" alt="{$locale.admin_downloads.list_titleup}" /> ..
							</a>
						</td>
						<td>&lt;dir&gt;</td>
						{if $data.type == "F"}
							<td>{$data.size}</td>
						{/if}
						<td colspan="2">{$data.add_date}</td>
					</tr>
				{else}
				<tr class="{cycle values="row1,row2"}" {if $data.type == "D"}style="font-weight: bold;"{/if}>
					<td class="first">
						{if $data.type == "D"}
							<img src="{$theme_dir}/images/admin/dir.gif" alt="{$locale.admin_downloads.list_dir}" />
							<a {if $data.desc != ""}onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('{$data.desc}')"{/if} href="admin.php?p=downloads&amp;parent={$data.did}" title="{$data.name}"><b>{$data.name}</b></a>
						{else}
							<img src="{$theme_dir}/images/admin/file.gif" alt="{$locale.admin_downloads.list_file}" />
							<a {if $data.desc != ""}onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('{$data.desc}')"{/if}>{$data.name}</a>
						{/if}
					</td>
					<td>
					{if $data.type == "D"}
						&lt;dir&gt;
					{else}
						{$data.size} KB
					{/if}
					</td>
					<td>{$data.add_date}</td>
					<td class="last">
						{if $data.is_act == 1}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;&amp;did={$data.did}parent={$data.parent}" title="{$locale.admin_downloads.list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;did={$data.did}&amp;parent={$data.parent}" title="{$locale.admin_downloads.list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;did={$data.did}&amp;parent={$data.parent}" title="{$locale.admin_downloads.list_modify}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_downloads.confirm_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;did={$data.did}&amp;parent={$data.parent}';" title="{$locale.admin_downloads.list_delete}"></a>
					</td>
				</tr>
				{/if}
			{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_downloads.warning_empty}" />
						{$locale.admin_downloads.warning_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">&nbsp;</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>
