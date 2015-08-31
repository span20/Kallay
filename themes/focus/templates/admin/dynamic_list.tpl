<div id="table">
{if !isset($id)}{assign var=id value="id"}{/if}
	{if $dynamic_tabs}
		{include file="admin/dynamic_tabs.tpl"}
	{else}
		<div class="tabs">
			<ul>
				<li class="current"><a href="#">...</a></li>
			</ul>
			<div class="blueleft"></div><div class="blueright"></div>
		</div>
	{/if}
	<div class="t_content">
		<div class="t_filter">{if isset($lang_title)}<h2 style="margin:0;">{$lang_title}</h2>{/if}</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
			  {foreach from=$table_headers item=th key=thkey name=ths}
				<th{if $smarty.foreach.ths.first} class="first"{elseif  $smarty.foreach.ths.last} class="last"{assign var=columnCount value=$smarty.foreach.ths.total}{/if}>{$th}</th>
			  {/foreach}
			</tr>
			{foreach from=$page_data item=listItem}
			<tr class="{cycle values="row1,row2"}">
				{foreach from=$table_headers key=itemKey item=item name=tds}
				{if $itemKey != '__act__' && $itemKey != '__lang__'}
					<td{if $smarty.foreach.tds.first} class="first"{elseif $smarty.foreach.tds.last} class="last"{/if}>{$listItem.$itemKey}</td>
				{else}
					{if $itemKey == '__act__'}
						<td{if $smarty.foreach.tds.first} class="first"{elseif $smarty.foreach.tds.last} class="last"{/if}>
						{foreach from=$actions_dynamic item=actionItem key=actionCode}
							{if $actionCode=="del"}
								<a class="action {$actionCode}" href="javascript: if (confirm('{$lang_dynamic.strAdminConfirm}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;{$id}={$listItem.$id}&amp;{$link_additional}';" title="{$actionItem}"><span>{$actionItem}</span></a>
							{elseif $actionCode eq "act"}
								{if $listItem.is_active eq 1} 
									<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act={$actionCode}&amp;{$id}={$listItem.$id}&amp;{$link_additional}" title="{$actionItem.1}"><span>{$actionItem.1}</span></a>
								{else}
									<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act={$actionCode}&amp;{$id}={$listItem.$id}&amp;{$link_additional}" title="{$actionItem.0}"><span>{$actionItem.0}</span></a>
								{/if}
							{elseif $actionCode eq w_lst}
								<a class="action langlist" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act={$actionCode}&amp;{$id}={$listItem.$id}&amp;{$link_additional}" title="{$actionItem}"><span>{$actionItem}</span></a>
							{else}
								<a class="action {$actionCode}" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act={$actionCode}&amp;{$id}={$listItem.$id}&amp;{$link_additional}" title="{$actionItem}"><span>{$actionItem}</span></a>
							{/if}
						{/foreach}
						</td>
					{else}
						<td{if $smarty.foreach.tds.first} class="first"{elseif $smarty.foreach.tds.last} class="last"{/if}>
						{assign var="flag" value=$listItem.lang}
						{assign var="flagpic" value="flag_$flag.gif"}
						{if file_exists("$theme_dir/images/admin/$flagpic")}
							<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$listItem.lang}" />
						{else}
							{$data.clang}
						{/if}
						</td>
					{/if}
				{/if}
				{/foreach}
			</tr>
			{foreachelse}
				<tr>
					<td colspan="{$columnCount}" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$lang_dynamic.strAdminEmpty}" />
						{$lang_dynamic.strAdminEmpty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>
