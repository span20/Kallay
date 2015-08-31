<div id="table">
	<div id="ear">
		<ul>
			<li{if $menuType == "index"} id="current"{/if}><a href="admin.php?p={$self}" title="{$locale.admin_menus.title_index_tab}">{$locale.admin_menus.title_index_tab}</a></li>
			{if $is_admin == 1}
				<li{if $menuType == "admin"} id="current"{/if}><a href="admin.php?p={$self}&amp;menutype=admin" title="{$locale.admin_menus.title_admin_tab}">{$locale.admin_menus.title_admin_tab}</a></li>
			{/if}
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager">&nbsp;</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_menus.field_list_lang}</th>
				<th>{$locale.admin_menus.field_list_protected}</th>
				<th></th>
				<th>{$locale.admin_menus.field_list_name}</th>
				<th>{$locale.admin_menus.field_list_module}</th>
				{if $menuType == "index"}<th>{$locale.admin_menus.field_list_position}</th>{/if}
				<th class="last">{$locale.admin_menus.field_list_action}</th>
			</tr>
		{defun name="menu" list=$sitemenu}
			{foreach from=$list item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">
					{assign var="flag" value=$data.mlang}
					{assign var="flagpic" value="flag_$flag.gif"}
					{if file_exists("$theme_dir/images/admin/$flagpic")}
						<img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$data.mlang}" />
					{else}
						{$data.mlang}
					{/if}
				</td>
				<td>{$data.mprot}</td>
				<td>
				{if $data.mtype == "index"}
					{if $data.is_sub == "1"}
						<a href="admin.php?p={$self}&mid={$data.menu_id}" style="font-size: 14px; font-weight: bold"><img src="{$theme_dir}/images/admin/arrow_down.gif" border="0" alt=""></a>
					{/if}
				{/if}
				</td>
				<td {if $data.level > 1}style="padding-left: {$data.level*10}px;"{/if}>
					{$data.menu_name}
				</td>
				<td>
					{if $data.moname != ""}
						{if $data.mtype == "admin"}
							{$locale.admin_menus.field_list_adminmodule}
						{else}
							{$locale.admin_menus.field_list_indexmodule}
						{/if}
						{$data.moname}
					{elseif $data.ctitle != ""}
						 {$locale.admin_menus.field_list_content} {$data.ctitle}
					{elseif $data.catname != ""}
						 {$locale.admin_menus.field_list_category} {$data.catname}
					{else}
						{$locale.admin_menus.field_list_outerlink} {$data.mlink}
					{/if}
				</td>
				{if $menuType == "index"}<td>{$data.posname}</td>{/if}
				<td class="last">
					{if $data.isact == 1}
					<a class="action act" href="admin.php?p={$self}&amp;act=act&amp;m_id={$data.menu_id}&amp;type={$smarty.get.type}&amp;lang={$smarty.get.lang}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}" title="{$locale.admin_menus.field_list_inactivate}"></a>
					{else}
					<a class="action inact" href="admin.php?p={$self}&amp;act=act&amp;m_id={$data.menu_id}&amp;type={$smarty.get.type}&amp;lang={$smarty.get.lang}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}" title="{$locale.admin_menus.field_list_activate}"></a>
					{/if}
					<a class="action mod" href="admin.php?p={$self}&amp;act=mod&amp;m_id={$data.menu_id}&amp;type={$smarty.get.type}&amp;lang={$smarty.get.lang}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}" title="{$locale.admin_menus.field_list_modify}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_menus.confirm_del}')) document.location.href='admin.php?p={$self}&amp;act=del&amp;m_id={$data.menu_id}&amp;type={$smarty.get.type}&amp;lang={$smarty.get.lang}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}';" title="{$locale.admin_menus.field_list_delete}"></a>
					{if $menuType=="index"}
					<a href="admin.php?p={$self}&amp;act=add&amp;par={$data.menu_id}&amp;pos={$data.posid}&amp;type={$smarty.get.type}&amp;lang={$smarty.get.lang}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}" title="{$locale.admin_menus.field_list_submenu}">
						<img src="{$theme_dir}/images/admin/submenu.gif" border="0" alt="{$locale.admin_menus.field_list_submenu}" />
					</a>
					{/if}
					<a href="admin.php?p={$self}&amp;act=ord&amp;m_id={$data.menu_id}&amp;way=up&amp;par={$data.parent}&amp;type={$data.mtype}&amp;ordt={$smarty.get.ordt}&amp;ordl={$smarty.get.ordl}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}" title="{$locale.admin_menus.field_list_wayup}">
						<img src="{$theme_dir}/images/admin/up.gif" border="0" alt="{$locale.admin_menus.field_list_wayup}" />
					</a>
					<a href="admin.php?p={$self}&amp;act=ord&amp;m_id={$data.menu_id}&amp;way=down&amp;par={$data.parent}&amp;type={$data.mtype}&amp;ordt={$smarty.get.ordt}&amp;ordl={$smarty.get.ordl}&amp;menutype={$menuType}&amp;mid={$smarty.get.mid}" title="{$locale.admin_menus.field_list_waydown}">
						<img src="{$theme_dir}/images/admin/down.gif" border="0" alt="{$locale.admin_menus.field_list_waydown}" />
					</a>
				</td>
			</tr>
			{if $data.element}
				{fun name="menu" list=$data.element}
			{/if}
			{foreachelse}
				<tr>
					<td colspan="{if $menuType=="index"}7{else}6{/if}" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_menus.warning_no_menu}" />
						{$locale.admin_menus.warning_no_menu}
					</td>
				</tr>
			{/foreach}
		{/defun}
		</table>
		<div class="pager">&nbsp;</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>
