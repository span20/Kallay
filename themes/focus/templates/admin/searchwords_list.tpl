<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div class="t_content">
		<div class="t_filter">&nbsp;</div>
        <div class="pager">&nbsp;</div>
        <table>
            <tr>
                <th class="first">{$locale.admin_searchwords.list_lang}</th>
                <th></th>
                <th>{$locale.admin_searchwords.list_name}</th>
                <th>{$locale.admin_searchwords.list_addname}</th>
                <th>{$locale.admin_searchwords.list_adddate}</th>
                <th>{$locale.admin_searchwords.list_modname}</th>
                <th>{$locale.admin_searchwords.list_moddate}</th>
                <th class="last">{$locale.admin_searchwords.list_action}</th>
            </tr>
            <tr class="row1">
                <td class="first"></td>
                <td></td>
                <td>{$locale.admin_searchwords.list_indexpage}</td>
                <td>{$index_data.add_name}</td>
                <td>{$index_data.add_date}</td>
                <td>{$index_data.mod_name}</td>
                <td>{$index_data.mod_date}</td>
                <td class="last">
                    <a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;m_mid=0" title="{$locale.admin_searchwords.list_indexpage}"></a>
                </td>
            </tr>
        {defun name=tree list=$menu_array}
            {foreach from=$list item=menu}
            <tr class="{cycle values="row2,row1"}">
                <td class="first">
                    {assign var="flag" value=$menu.mlang}
                    {assign var="flagpic" value="flag_$flag.gif"}
                    {if file_exists("$theme_dir/images/admin/$flagpic")}
                        <img src="{$theme_dir}/images/admin/{$flagpic}" alt="{$menu.mlang}" />
                    {else}
                        {$menu.mlang}
                    {/if}
                </td>
                <td>
                {if $menu.is_sub == "1"}
                    <a href="admin.php?p={$self}&mid={$menu.menu_id}" style="font-size: 14px; font-weight: bold"><img src="{$theme_dir}/images/admin/arrow_down.gif" border="0" alt=""></a>
                {/if}
                </td>
                <td {if $menu.level > 1}style="padding-left: {$menu.level*10}px;"{/if}>{$menu.menu_name}</td>
                <td>{$menu.add_name}</td>
                <td>{$menu.add_date}</td>
                <td>{$menu.mod_name}</td>
                <td>{$menu.mod_date}</td>
                <td class="last">
	               <a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;m_mid={$menu.menu_id}" title="{$menu.menu_name|htmlspecialchars}"></a>
                </td>
            </tr>
    	    {if $menu.element}
                {fun name=tree list=$menu.element}
            {/if}
            {/foreach}
        {/defun}
	   </table>
		<div class="pager">&nbsp;</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>