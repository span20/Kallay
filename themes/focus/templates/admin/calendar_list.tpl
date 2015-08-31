<style type="text/css"><!--
	#calendar_cnt {literal} { {/literal}
		width: 757px;
		text-align: center;
	{literal} } {/literal}

	table.calendar {literal} { {/literal}
		border-collapse: collapse;
		width: 200px;
	{literal} } {/literal}

	th.calendar {literal} { {/literal}
		border: 1px solid #688DA8;
		width: 24px;
		height: 24px;
		background-color: #688DA8;
		color: #FFFFFF;
	{literal} } {/literal}

	td.nodays {literal} { {/literal}
		border: 1px solid;
	{literal} } {/literal}

	td.event {literal} { {/literal}
		border: 1px solid;
		background-color: #E6EDF2;
		color: #688DA8;
		font-weight: bold;
	{literal} } {/literal}
//-->
</style>

<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	{*<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.$self.field_list_header}">{$locale.$self.field_list_header}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>*}
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
    			<input type="hidden" name="p" value="{$self}">
                <input type="hidden" name="act" value="{$this_page}">
    			<input type="hidden" name="d" value="1">
    			{$locale.admin_calendar.field_list_jump}
    			<input type="text" name="y" value="{$fyear}" size="4" maxlength="4">
    			<select name="m">
    				{foreach from=$month_array item=data}
    					<option value="{$data.option}" {$data.selected}>{$data.option}</option>
    				{/foreach}
    			</select>
    			<input type="submit" value="{$locale.admin_calendar.field_list_ok}" class="submit_filter">
			</form>
		</div>
		<div class="pager">{$monthName}</div>
		<div id="calendar_cnt">
			<div style="float: left; padding: 70px 0 0 150px; vertical-align: middle;">
				<a href="{$prevMonth}" title="{$locale.admin_calendar.field_list_prevmonth}"><img src="{$theme_dir}/images/admin/left.gif" alt="{$locale.admin_calendar.field_list_prevmonth}" /></a>
			</div>
			<div style="float: right; padding: 70px 150px 0 0; vertical-align: middle;">
				<a href="{$nextMonth}" title="{$locale.admin_calendar.field_list_nextmonth}"><img src="{$theme_dir}/images/admin/right.gif" alt="{$locale.admin_calendar.field_list_nextmonth}" /></a>
			</div>

			<table class="calendar" align="center">
				<tr>
					<th class="calendar">{$locale.admin_calendar.field_list_short_monday}</th>
					<th class="calendar">{$locale.admin_calendar.field_list_short_tuesday}</th>
					<th class="calendar">{$locale.admin_calendar.field_list_short_wednesday}</th>
					<th class="calendar">{$locale.admin_calendar.field_list_short_thursday}</th>
					<th class="calendar">{$locale.admin_calendar.field_list_short_friday}</th>
					<th class="calendar">{$locale.admin_calendar.field_list_short_saturday}</th>
					<th class="calendar">{$locale.admin_calendar.field_list_short_sunday}</th>
				</tr>
				{section name=week loop=$month}
				<tr>
					{section name=day loop=$month[week]}
						{if $month[week][day]->isEmpty()}
							<td class="nodays"></td>
						{elseif $month[week][day]->isSelected()}
							<td class="event">
								<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=lst&amp;y={$month[week][day]->thisYear()}&amp;m={$month[week][day]->thisMonth()}&amp;d={$month[week][day]->thisDay()}" title="{$locale.admin_calendar.field_list_events}">
									{$month[week][day]->thisDay()}
								</a>
							</td>
						{else}
							<td style="border: 1px solid;" align="center">
								<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=lst&amp;y={$month[week][day]->thisYear()}&amp;m={$month[week][day]->thisMonth()}&amp;d={$month[week][day]->thisDay()}" title="{$locale.admin_calendar.field_list_events}">
									{$month[week][day]->thisDay()}
								</a>
							</td>
						{/if}
					{/section}
				</tr>
				{/section}
			</table>
		</div>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>

	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.admin_calendar.field_list_header}">{$locale.admin_calendar.field_list_header}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter"></div>
		<div class="pager">{$locale.admin_calendar.field_list_events_title} {$today}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_calendar.field_list_major}</th>
				<th>{$locale.admin_calendar.field_list_event}</th>
				<th>{$locale.admin_calendar.field_list_timerstart}</th>
				<th>{$locale.admin_calendar.field_list_timerend}</th>
				<th class="last">{$locale.admin_calendar.field_list_action}</th>
			</tr>
			{foreach from=$today_event item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">{$data.is_major}</td><td>{$data.title}</td><td>{$data.start_date}</td><td>{$data.end_date}</td>
				<td class="last">
					<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;cid={$data.cid}&amp;y={$smarty.get.y}&amp;m={$smarty.get.m}&amp;d={$smarty.get.d}" title="{$locale.admin_calendar.field_list_modify}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_calendar.confirm_del}')) document.location.href='admin.php?p={$self}&amp;={$this_page}&amp;sub_act=del&amp;cid={$data.cid}&amp;y={$smarty.get.y}&amp;m={$smarty.get.m}&amp;d={$smarty.get.d}';" title="{$locale.admin_calendar.field_list_delete}"></a>
				</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="5" class="empty">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_calendar.warning_no_event}" />
					{$locale.admin_calendar.warning_no_event}
				</td>
			</tr>
			{/foreach}
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
