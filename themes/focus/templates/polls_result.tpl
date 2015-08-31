<table cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/{$theme}/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title">{$locale.$self.field_result_title|upper}</span></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td width="100%">
			<table cellpadding="2" cellspacing="0">
				{foreach from=$poll_data item=data}
					<tr><td><b>{$locale.$self.field_main_result_start}</b></td><td width="50%" align="right">{$data.start_date}</td></tr>
					<tr><td><b>{$locale.$self_field_main_result_end}</b></td><td width="50%" align="right">{$data.end_date}</td></tr>
					<tr><td>&nbsp;</td></tr>
				{/foreach}
			</table>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<table cellpadding="2" cellspacing="0">
				<tr><td colspan="3">{$data.ptitle}</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>{$llocale.$self.field_main_result_answer}</td><td align="right" width="15%">{$locale.$self.field_main_result_polls}</td><td align="right" width="15%">{$locale.$self.field_main_result_percent}</td></tr>
				{foreach from=$poll_text item=text key=key}
				<tr>
					<td width="50%">{$key+1}. {$text.text}</td>
					<td width="25%" align="right">{$text.polldata}</td>
					<td width="25%" align="right">{$text.percent}%</td>
				</tr>
				{/foreach}
				<tr>
					<td align="right">{$locale.$self.field_main_result_all}</td><td align="right">{$poll_num.polldata}</td><td align="right">100%</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
