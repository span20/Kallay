<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter"></div>
		<div class="pager"></div>
		<table>
			<tr>
				<th class="first" colspan="6">{$locale.admin_stat.field_access}</th>
			</tr>
			<tr class="row1">
				<td style="border: 1px solid;" class="first">{$locale.admin_stat.field_id}</td>
				<td style="border: 1px solid;">{$locale.admin_stat.field_client}</td>
				<td style="border: 1px solid;" colspan="2">{$locale.admin_stat.field_total}</td>
				<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_actmonth}</td>
			</tr>
			<tr class="row2">
				<td style="border: 1px solid;" class="first">{$client_id}</td>
				<td style="border: 1px solid;">{$client}</td>
				<td style="border: 1px solid;">{$visits_total} {$locale.admin_stat.field_visits}</td>
				<td style="border: 1px solid;">{$pi_total} {$locale.admin_stat.field_impressions}</td>
				<td style="border: 1px solid;">{$visits_month} {$locale.admin_stat.field_visits}</td>
				<td style="border: 1px solid;" class="last">{$pi_month} {$locale.admin_stat.field_impressions}</td>
			</tr>
		</table><br /><br />
		{if $smarty.session.site_stat_return_visitor}
			<table>
				<tr>
					<th class="first" colspan="2">{$locale.admin_stat.field_visitors}</th>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid; width: 40%;" class="first">{$locale.admin_stat.field_returning_unique}</td>
					<td style="border: 1px solid;">{$num_unique_visitors}</td>
				</tr>
				<tr class="row2">
					<td style="border: 1px solid;" class="first">{$locale.admin_stat.field_returning_onetime}</td>
					<td style="border: 1px solid;">{$num_one_time_visitors}</td>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid;" class="first">{$locale.admin_stat.field_returning_visitors}</td>
					<td style="border: 1px solid;">{$num_returning_visitors}</td>
				</tr>
				<tr class="row2">
					<td style="border: 1px solid;" class="first">{$locale.admin_stat.field_return_visits}</td>
					<td style="border: 1px solid;">{$num_return_visits}</td>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid;" class="first">{$locale.admin_stat.field_returning_avg_visits}</td>
					<td style="border: 1px solid;">{$average_visits}</td>
				</tr>
				<tr class="row2">
					<td style="border: 1px solid;" class="first">{$locale.admin_stat.field_returning_time}</td>
					<td style="border: 1px solid;">{$average_time_between_visits}</td>
				</tr>
			</table><br /><br />
		{/if}
		<table>
			{if $monthly_stat}
				<tr>
					<th class="first" colspan="5">{$locale.admin_stat.field_monthly}</th>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid; width: 33%;" class="first">{$locale.admin_stat.field_monthlydate}</td>
					<td style="border: 1px solid; width: 33%;" colspan="2">{$locale.admin_stat.field_monthlyimp}</td>
					<td style="border: 1px solid; width: 33%;" class="last" colspan="2">{$locale.admin_stat.field_monthlyvisitor}</td>
				</tr>
				{foreach from=$monthly_stat item=data}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$data.link}</td>
					<td style="border: 1px solid; width: 16%;">{$data.pi_number}</td>
					<td style="border: 1px solid;">{$data.pi_percent}%</td>
					<td style="border: 1px solid; width: 16%;">{$data.visits_number}</td>
					<td style="border: 1px solid;" class="last">{$data.visits_percent}%</td>
				</tr>
				{/foreach}
				{if $graph_link_monthly}
					<tr><td style="padding-top: 10px; text-align: center;" colspan="5"><img src="{$graph_link_monthly}" /></td></tr>
				{/if}
			{elseif $dayly_stat}
				<tr>
					<th class="first" colspan="5">{$locale.admin_stat.field_dayly} - {$year}. {$month}</th>
				</tr>
				{if $graph_link_dayly}
					<tr><td style="padding-top: 10px; text-align: center;" colspan="5"><img src="{$graph_link_dayly}" /></td></tr>
				{/if}
				<tr class="row1">
					<td style="border: 1px solid; width: 33%;" class="first">{$locale.admin_stat.field_monthlydate}</td>
					<td style="border: 1px solid; width: 33%;" colspan="2">{$locale.admin_stat.field_monthlyimp}</td>
					<td style="border: 1px solid; width: 33%;" class="last" colspan="2">{$locale.admin_stat.field_monthlyvisitor}</td>
				</tr>
				{foreach from=$dayly_stat item=data}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$data.link}</td>
					<td style="border: 1px solid; width: 16%;">{$data.pi_number}</td>
					<td style="border: 1px solid;">{$data.pi_percent}%</td>
					<td style="border: 1px solid; width: 16%;">{$data.visits_number}</td>
					<td style="border: 1px solid;" class="last">{$data.visits_percent}%</td>
				</tr>
				{/foreach}
			{elseif $hourly_stat}
				<tr>
					<th class="first" colspan="5">{$locale.admin_stat.field_hourly} - {$year}. {$month} {$day}.</th>
				</tr>
				{if $graph_link_hourly}
					<tr><td style="padding-top: 10px; text-align: center;" colspan="5"><img src="{$graph_link_hourly}" /></td></tr>
				{/if}
				<tr class="row1">
					<td style="border: 1px solid; width: 33%;" class="first">{$locale.admin_stat.field_monthlydate}</td>
					<td style="border: 1px solid; width: 33%;" colspan="2">{$locale.admin_stat.field_monthlyimp}</td>
					<td style="border: 1px solid; width: 33%;" class="last" colspan="2">{$locale.admin_stat.field_monthlyvisitor}</td>
				</tr>
				{foreach from=$hourly_stat item=data}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$data.hour}</td>
					<td style="border: 1px solid; width: 16%;">{$data.pi_number}</td>
					<td style="border: 1px solid;">{$data.pi_percent}%</td>
					<td style="border: 1px solid; width: 16%;">{$data.visits_number}</td>
					<td style="border: 1px solid;" class="last">{$data.visits_percent}%</td>
				</tr>
				{/foreach}
			{/if}
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_totalpages}</th>
			</tr>
			{if $smarty.session.site_stat_is_graph}
				{if $top_total_pages.0.count > 0}
					<tr><td style="padding-top: 10px; text-align: center;">
					{if !$smarty.get.statpage || ($smarty.get.statpage != month && $smarty.get.statpage != day)}
						<img src="admin/stat_graph.php?what=top&amp;type=document&amp;limit={$stat_limit}&amp;range=current_year" />
					{elseif $smarty.get.statpage == month}
						<img src="admin/stat_graph.php?what=top&amp;type=document&amp;limit={$stat_limit}&amp;range=current_month" />
					{elseif $smarty.get.statpage == day}
						<img src="admin/stat_graph.php?what=top&amp;type=document&amp;limit={$stat_limit}&amp;range=current_day" />
					{/if}
					</td></tr>
				{else}
					<tr>
						<td colspan="4" class="empty">
							<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
							{$locale.admin_stat.warning_no_data}
						</td>
					</tr>
				{/if}
			{else}
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
					<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_page}</td>
				</tr>
				{foreach from=$top_total_pages item=data key=key}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$key+1}.</td>
					<td style="border: 1px solid; width: 100px;">{$data.count}</td>
					<td style="border: 1px solid;">{$data.percent}%</td>
					<td style="border: 1px solid;" class="last">{$data.string}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
						{$locale.admin_stat.warning_no_data}
					</td>
				</tr>
				{/foreach}
			{/if}
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_host}</th>
			</tr>
			{if $smarty.session.site_stat_is_graph}
				{if $top_hosts.0.count> 0}
					<tr><td style="padding-top: 10px; text-align: center;">
					{if !$smarty.get.statpage || ($smarty.get.statpage != month && $smarty.get.statpage != day)}
						<img src="admin/stat_graph.php?what=top&amp;type=host&amp;limit={$stat_limit}&amp;range=current_year" />
					{elseif $smarty.get.statpage == month}
						<img src="admin/stat_graph.php?what=top&amp;type=host&amp;limit={$stat_limit}&amp;range=current_month" />
					{elseif $smarty.get.statpage == day}
						<img src="admin/stat_graph.php?what=top&amp;type=host&amp;limit={$stat_limit}&amp;range=current_day" />
					{/if}
					</td></tr>
				{else}
					<tr>
						<td colspan="4" class="empty">
							<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
							{$locale.admin_stat.warning_no_data}
						</td>
					</tr>
				{/if}
			{else}
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
					<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_page}</td>
				</tr>
				{foreach from=$top_hosts item=data key=key}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$key+1}.</td>
					<td style="border: 1px solid; width: 100px;">{$data.count}</td>
					<td style="border: 1px solid;">{$data.percent}%</td>
					<td style="border: 1px solid;" class="last">{$data.string}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
						{$locale.admin_stat.warning_no_data}
					</td>
				</tr>
				{/foreach}
			{/if}
		</table>
		<br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_referer}</th>
			</tr>
			{if $smarty.session.site_stat_is_graph}
				{if $top_referers.0.count > 0}
					<tr><td style="padding-top: 10px; text-align: center;">
					{if !$smarty.get.statpage || ($smarty.get.statpage != month && $smarty.get.statpage != day)}
						<img src="admin/stat_graph.php?what=top&amp;type=referer&amp;limit={$stat_limit}&amp;range=current_year" />
					{elseif $smarty.get.statpage == month}
						<img src="admin/stat_graph.php?what=top&amp;type=referer&amp;limit={$stat_limit}&amp;range=current_month" />
					{elseif $smarty.get.statpage == day}
						<img src="admin/stat_graph.php?what=top&amp;type=referer&amp;limit={$stat_limit}&amp;range=current_day" />
					{/if}
					</td></tr>
				{else}
					<tr>
						<td colspan="4" class="empty">
							<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
							{$locale.admin_stat.warning_no_data}
						</td>
					</tr>
				{/if}
			{else}
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
					<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_page}</td>
				</tr>
				{foreach from=$top_referers item=data key=key}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$key+1}.</td>
					<td style="border: 1px solid; width: 100px;">{$data.count}</td>
					<td style="border: 1px solid;">{$data.percent}%</td>
					<td style="border: 1px solid;" class="last">{$data.string}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
						{$locale.admin_stat.warning_no_data}
					</td>
				</tr>
				{/foreach}
			{/if}
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_opsyss}</th>
			</tr>
			{if $smarty.session.site_stat_is_graph}
				{if $top_op_systems.0.count > 0}
					<tr><td style="padding-top: 10px; text-align: center;">
					{if !$smarty.get.statpage || ($smarty.get.statpage != month && $smarty.get.statpage != day)}
						<img src="admin/stat_graph.php?what=top&amp;type=operating_system&amp;limit={$stat_limit}&amp;range=current_year" />
					{elseif $smarty.get.statpage == month}
						<img src="admin/stat_graph.php?what=top&amp;type=operating_system&amp;limit={$stat_limit}&amp;range=current_month" />
					{elseif $smarty.get.statpage == day}
						<img src="admin/stat_graph.php?what=top&amp;type=operating_system&amp;limit={$stat_limit}&amp;range=current_day" />
					{/if}
					</td></tr>
				{else}
					<tr>
						<td colspan="4" class="empty">
							<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
							{$locale.admin_stat.warning_no_data}
						</td>
					</tr>
				{/if}
			{else}
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
					<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_opsys}</td>
				</tr>
				{foreach from=$top_op_systems item=data key=key}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$key+1}.</td>
					<td style="border: 1px solid; width: 100px;">{$data.count}</td>
					<td style="border: 1px solid;">{$data.percent}%</td>
					<td style="border: 1px solid;" class="last">{$data.string}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
						{$locale.admin_stat.warning_no_data}
					</td>
				</tr>
				{/foreach}
			{/if}
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_browsers}</th>
			</tr>
			{if $smarty.session.site_stat_is_graph}
				{if $top_user_agents.0.count > 0}
					<tr><td style="padding-top: 10px; text-align: center;">
					{if !$smarty.get.statpage || ($smarty.get.statpage != month && $smarty.get.statpage != day)}
						<img src="admin/stat_graph.php?what=top&amp;type=user_agent&amp;limit={$stat_limit}&amp;range=current_year" />
					{elseif $smarty.get.statpage == month}
						<img src="admin/stat_graph.php?what=top&amp;type=user_agent&amp;limit={$stat_limit}&amp;range=current_month" />
					{elseif $smarty.get.statpage == day}
						<img src="admin/stat_graph.php?what=top&amp;type=user_agent&amp;limit={$stat_limit}&amp;range=current_day" />
					{/if}
					</td></tr>
				{else}
					<tr>
						<td colspan="4" class="empty">
							<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
							{$locale.admin_stat.warning_no_data}
						</td>
					</tr>
				{/if}
			{else}
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
					<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_browser}</td>
				</tr>
				{foreach from=$top_user_agents item=data key=key}
				<tr class="{cycle values="row2,row1"}">
					<td style="border: 1px solid;" class="first">{$key+1}.</td>
					<td style="border: 1px solid; width: 100px;">{$data.count}</td>
					<td style="border: 1px solid;">{$data.percent}%</td>
					<td style="border: 1px solid;" class="last">{$data.string}</td>
				</tr>
				{foreachelse}
				<tr>
					<td colspan="4" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
						{$locale.admin_stat.warning_no_data}
					</td>
				</tr>
				{/foreach}
			{/if}
		</table>
		{if $smarty.session.site_stat_country}
		<br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_countries}</th>
			</tr>
			{if $countries.0.count > 0}
			<tr>
				<td style="border: 1px solid; width: 30px;" class="first"></td>
				<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
				<td style="border: 1px solid;">{$locale.admin_stat.field_country}</td>
				<td style="border: 1px solid;" class="last">{$locale.admin_stat.field_region}</td>
			</tr>
			{/if}
			{foreach from=$countries item=data key=key}
			<tr class="{cycle values="row2,row1"}">
				<td style="border: 1px solid;" class="first">{$key+1}.</td>
				<td style="border: 1px solid; width: 100px;">{$data.count}</td>
				<td style="border: 1px solid;">{$data.percent}%</td>
				<td style="border: 1px solid;">
					<img src="{$libs_dir}/pear/phpOpenTracker/flags/s/{$data.flag}" />
					{$data.string}
				</td>
				<td style="border: 1px solid;" class="last">{$data.region}</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="5" class="empty">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
					{$locale.admin_stat.warning_no_data}
				</td>
			</tr>
			{/foreach}
			{if $smarty.session.site_stat_is_graph}
				<tr><td style="padding-top: 10px; text-align: center;" colspan="6">
				{if !$smarty.get.statpage || ($smarty.get.statpage != month && $smarty.get.statpage != day)}
					<img src="admin/stat_graph.php?what=localizer&amp;limit={$stat_limit}&amp;range=current_year" />
				{elseif $smarty.get.statpage == month}
					<img src="admin/stat_graph.php?what=localizer&amp;limit={$stat_limit}&amp;range=current_month" />
				{elseif $smarty.get.statpage == day}
					<img src="admin/stat_graph.php?what=localizer&amp;limit={$stat_limit}&amp;range=current_day" />
				{/if}
				</td></tr>
			{/if}
		</table>
		{/if}
		{if $smarty.session.site_stat_search}
		<br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_searchengines}</th>
			</tr>
			{if $search_engines.0.count > 0}
			<tr>
				<td style="border: 1px solid; width: 30px;" class="first"></td>
				<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
				<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_searchengine}</td>
			</tr>
			{/if}
			{foreach from=$search_engines item=data key=key}
			<tr class="{cycle values="row2,row1"}">
				<td style="border: 1px solid;" class="first">{$key+1}.</td>
				<td style="border: 1px solid; width: 100px;">{$data.count}</td>
				<td style="border: 1px solid;">{$data.percent}%</td>
				<td style="border: 1px solid;" class="last">{$data.string}</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="4" class="empty">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
					{$locale.admin_stat.warning_no_data}
				</td>
			</tr>
			{/foreach}
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5">{$locale.admin_stat.field_top} {$stat_limit} {$locale.admin_stat.field_keywords}</th>
			</tr>
			{if $search_keywords.0.count > 0}
			<tr>
				<td style="border: 1px solid; width: 30px;" class="first"></td>
				<td style="border: 1px solid; width: 200px;" colspan="2">{$locale.admin_stat.field_hit}</td>
				<td style="border: 1px solid;" class="last" colspan="2">{$locale.admin_stat.field_keyword}</td>
			</tr>
			{/if}
			{foreach from=$search_keywords item=data key=key}
			<tr class="{cycle values="row2,row1"}">
				<td style="border: 1px solid;" class="first">{$key+1}.</td>
				<td style="border: 1px solid; width: 100px;">{$data.count}</td>
				<td style="border: 1px solid;">{$data.percent}%</td>
				<td style="border: 1px solid;" class="last">{$data.string}</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="4" class="empty">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_stat.warning_no_data}" />
					{$locale.admin_stat.warning_no_data}
				</td>
			</tr>
			{/foreach}
		</table>
		{/if}
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>