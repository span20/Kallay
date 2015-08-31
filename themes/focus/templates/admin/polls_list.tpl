<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.$self.title}">{$locale.$self.title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.$self.field_list_question}</th>
				<th>{$locale.$self.field_list_start}</th>
				<th>{$locale.$self.field_list_end}</th>
				<th>{$locale.$self.field_list_adduser}</th>
				<th>{$locale.$self.field_list_adddate}</th>
				<th>{$locale.$self.field_list_startdate}</th>
				<th>{$locale.$self.field_list_enddate}</th>
				<th class="last">{$locale.$self.field_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('{$data.answerlist}')">{$data.ptitle}</a></td>
					<td>{$data.pstart}</td><td>{$data.pend}</td><td>{$data.add_name}</td><td>{$data.add_date}</td><td>{$data.start_date}</td><td>{$data.end_date}</td>
					<td class="last">
						{if $data.pact}
							<a class="action act" href="admin.php?p={$self}&amp;act=act&amp;pid={$data.pid}" title="{$locale.$self.field_list_inactivate}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act=act&amp;pid={$data.pid}" title="{$locale.$self.field_list_activate}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act=mod&amp;pid={$data.pid}" title="{$locale.$self.field_list_modify}">
						</a>
						<a class="action del" href="javascript: if (confirm('{$locale.$self.confirm_del}')) document.location.href='admin.php?p={$self}&amp;act=del&amp;pid={$data.pid}';" title="{$locale.$self.field_list_delete}">
						</a>
						<a href="admin.php?p={$self}&amp;act=res&amp;pid={$data.pid}" title="{$locale.$self.field_list_result}">
							<img src="{$theme_dir}/images/admin/result.gif" border="0" alt="{$locale.$self.field_list_result}" />
						</a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="8" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.$self.warning_list_empty}" />
						{$locale.$self.warning_list_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
