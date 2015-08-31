<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/images/nyil_szurke.png" border="0" alt=""></td>
		<td colspan="3" width="97%" class="content_title" style="border-top: 1px solid;">
			{$locale.index_calendar.field_main_title|upper}
		</td>
	</tr>
</table><br />

<form action="index.php" method="get">
<input type="hidden" name="p" value="calendar">
<input type="hidden" name="d" value="1">
<table cellpadding="2" cellspacing="0" align="center" style="border-collapse: collapse; background-color: #F5F5F5;">
	<tr><td colspan="7" align="center">{$monthName}</td></tr>
	<tr>
		<td><a href="{$prevMonth}">&laquo;</a></td>
		<td colspan="5" align="center">
			<input type="text" name="y" value="{$fyear}" size="4" maxlength="4">
			<select name="m">
				{foreach from=$month_array item=data}
					<option value="{$data.option}" {$data.selected}>{$data.option}</option>
				{/foreach}
			</select>
			<input type="submit" value="ok" class="submit">
		</td>
		<td align="right"><a href="{$nextMonth}">&raquo;</a></td>
	</tr>
	<tr>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_monday}</th>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_tuesday}</th>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_wednesday}</th>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_thursday}</th>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_friday}</th>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_saturday}</th>
		<th style="border: 1px solid #E0E0E0; width: 30px; text-align: center; background-color: #E0E0E0;">{$locale.index_calendar.field_main_short_sunday}</th>
	</tr>

	{section name=week loop=$month}
	<tr>
		{section name=day loop=$month[week]}
			{if $month[week][day]->isEmpty()}
				<td style="border: 1px solid #E0E0E0;" align="center">&nbsp;</td>
			{elseif $month[week][day]->isSelected()}
				<td style="border: 1px solid #E0E0E0; background-color: #E0E0E0;" align="center">
					<a href="index.php?p=calendar&amp;y={$month[week][day]->thisYear()}&amp;m={$month[week][day]->thisMonth()}&amp;d={$month[week][day]->thisDay()}" title="{$locale.admin_calendar.field_list_main_events}"><strong><font color="#FFFFFF">{$month[week][day]->thisDay()}</font></strong></a>
				</td>
			{else}
				<td style="border: 1px solid #E0E0E0;" align="center">
					<a style="color: #23478A" href="index.php?p=calendar&amp;y={$month[week][day]->thisYear()}&amp;m={$month[week][day]->thisMonth()}&amp;d={$month[week][day]->thisDay()}" title="{$locale.admin_calendar.field_list_main_events}">{$month[week][day]->thisDay()}</a>
				</td>
			{/if}
		{/section}
    </tr>
    {/section}
</table>
</form>

<div id="content_text">
	<div>{$cal_today}</div>
    <div>
		<div style="float: left; font-weight: bold; width: 194px;">{$locale.index_calendar.field_main_event}</div>
		<div style="float: left; font-weight: bold; width: 150px;">{$locale.index_calendar.field_main_timerstart}</div>
		<div style="float: left; font-weight: bold; width: 150px;">{$locale.index_calendar.field_main_timerend}</div>
	</div>
	{foreach from=$today_event item=data}
		{if $data.is_major == 1}
		<div style="clear: both;">
			<div style="float: left; width: 194px;"><a href="javascript:;" onclick="cal_show_hide({$data.cid})" style="color: #9B9B9B;"><b>{$data.title}</b></a></div>
			<div style="float: left; width: 150px;"><b>{$data.start_date}</b></div>
            <div style="float: left; width: 150px;"><b>{$data.end_date}</b></div>
		</div>
		<div id="{$data.cid}" style="display: none;">{$data.event}</div>
		{else}
		<div style="clear: both;">
			<div style="float: left; width: 194px;"><a href="javascript:;" onclick="cal_show_hide({$data.cid})" style="color: #9B9B9B;">{$data.title}</a></div>
			<div style="float: left; width: 150px;">{$data.start_date}</div>
            <div style="float: left; width: 150px;">{$data.end_date}</div>
		</div>
		<div id="{$data.cid}" style="display: none;">{$data.event}</div>
		{/if}
	{foreachelse}
	<div style="text-align: center">{$locale.index_calendar.warning_main_no_event}</div>
	{/foreach}
</div>