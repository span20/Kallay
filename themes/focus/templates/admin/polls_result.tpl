<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="{$locale.$self.field_result_title}">{$locale.$self.field_result_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
<!--		<div id="t_filter">&nbsp;</div> -->
		<div class="t_empty"></div>
		<table>
			<tr class="row2">
				<td class="first">
					<dl>
					{foreach from=$poll_data item=data}
						<dt><b>{$locale.$self.field_result_timerstart}</b></dt><dd>{$data.timer_start}</dd>
						<dt><b>{$locale.$self.field_result_timerend}</b></dt><dd>{$data.timer_end}</dd>
						<dt><b>{$locale.$self.field_result_start}</b></dt><dd>{$data.start_date}</dd>
						<dt><b>{$locale.$self.field_result_end}</b></dt><dd>{$data.end_date}</dd>
					{/foreach}
					</dl>
				</td>
				<td class="last" style="text-align:right;"><img src="admin/polls_graph.php?pid={$pid}" border="0" alt="graf" /></td>
			</tr>
		</table>
<!--		<h2 class="row1" style="text-align:center;">{$data.ptitle}</h2> -->
		<table>
			<tr>
				<th class="first">{$locale.$self.field_result_answer}</th>
				<th align="right">{$locale.$self.field_result_polls}</th>
				<th class="last" align="right">{$locale.$self.field_result_percent}</th>
			</tr>
			{foreach from=$poll_text item=text key=key}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$key+1}. {$text.text}</td>
					<td align="right">{$text.polldata}</td>
					<td class="last" align="right">{$text.percent}%</td>
				</tr>
			{/foreach}
			<tr class="{cycle values="row1,row2"}">
				<td class="first" align="right">{$locale.$self.field_result_all}</td>
				<td align="right">{$poll_num.polldata}</td>
				<td class="last" align="right">100%</td>
			</tr>
		</table>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
