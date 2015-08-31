<script type="text/javascript">//<![CDATA[
function torol(rid) {literal} { {/literal}
	x = confirm('{$locale.admin_rss.confirm_variable}');
	if (x) {literal} { {/literal}
		document.location.href='admin.php?p={$self}&act=del&rid='+rid
	{literal} }
} {/literal}
//]]>
</script>

<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.admin_rss.title_rss}">{$locale.admin_rss.title_rss}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_rss.form_name}</th>
				<th class="last">{$locale.admin_rss.actions}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.rss_name}</td>
					<td class="last">
						{if $data.is_active == 1}
								<a class="action act" href="admin.php?p={$self}&amp;act=act&amp;rid={$data.rss_id}" title="{$locale.admin_rss.act_inact}"></a>
							{else}
								<a class="action inact" href="admin.php?p={$self}&amp;act=act&amp;rid={$data.rss_id}" title="{$locale.admin_rss.act_act}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act=mod&amp;rid={$data.rss_id}" title="{$locale.admin_rss.act_mod}"></a>
						<a class="action del" href="javascript: torol({$data.rss_id});" title="{$locale.admin_rss.act_del}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="3" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_rss.warning_no_rssreader}" />
						{$locale.admin_rss.warning_no_rssreader}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>