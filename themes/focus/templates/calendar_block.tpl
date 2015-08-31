<form action="index.php" method="get">
<input type="hidden" name="d" value="1">
<table cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse;">
	<tr>
		<td class="block_header" style="background-color: #DAB934;">{$locale.index_calendar.field_block_list_title|upper}</td>
	</tr>
	<tr><td style="height: 5px;"></td></tr>
	{foreach from=$dateArray item=data}
		<tr>
			<td class="block">
				{if $data.is_major == 1}
					<a href="index.php?p={$self_calendar}&amp;cid={$data.cid}&amp;y={$data.year}&amp;m={$data.month}&amp;d={$data.day}" title="{$data.title}" style="color: #9B9B9B;"><b>{$data.title}</b></a>
				{else}
					<a href="index.php?p={$self_calendar}&amp;cid={$data.cid}&amp;y={$data.year}&amp;m={$data.month}&amp;d={$data.day}" title="{$data.title}" style="color: #9B9B9B;">{$data.title}</a>
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr><td class="block">{$locale.index_calendar.warning_block_no_event}</td></tr>
	{/foreach}
	<tr><td class="block" style="height: 5px;"></td></tr>
	<tr><td class="block"><a href="index.php?p=calendar" title="{$locale.index_calendar.field_block_list_more}">{$locale.index_calendar.field_block_list_more}</a></td></tr>
</table>
</form>
