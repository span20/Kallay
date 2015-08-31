<table cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/images/nyil_szurke.png" border="0" alt=""></td>
		<td colspan="4" width="97%" class="content_title" style="border-top: 1px solid;"><span >{$locale.$self.field_old_title|upper}</span></td>
	</tr>
</table>
<table cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<td colspan="5" align="center" class="pager">{$page_list}</td>
	</tr>
	<tr>
		<td></td>
		<th style="text-align: left;">{$locale.$self.field_old_question}</th>
		<th style="text-align: left;">{$locale.$self.field_old_start}</th>
		<th style="text-align: left;">{$locale.$self.field_old_end}</th>
		<td></td>
	</tr>
	{foreach from=$page_data item=data}
		<tr bgcolor="{cycle values="#F5F5F5,#FFFFFF"}">
			<td></td>
			<td valign="top" class="simpletext">{$data.ptitle}</td>
			<td valign="top" class="simpletext">{$data.pstart}</td>
			<td valign="top" class="simpletext">{$data.pend}</td>
			<td valign="top"><a class="more_link" href="index.php?p=polls_old&amp;pid={$data.pid}" title="{$locale.$self.field_old_more}">{$locale.$self.field_old_more}</a></td>
		</tr>
	{foreachelse}
		<tr><td colspan="5" class="hiba">{$locale.$self.warning_old_empty}</td></tr>
	{/foreach}
	<tr><td>&nbsp;</td></tr>
</table>
