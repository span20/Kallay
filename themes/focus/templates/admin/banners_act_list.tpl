<div id="form_cnt">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
            <h3 style="margin:0;">{$locale.admin_banners.field_list_activate_header|upper}</h3>
        </div>
		<form{$form.attributes}>
		{$form.hidden}
		<table>
			<tr>
				<td colspan="2" align="center">
                    {if $actbanner.0.bcode == ""}
    					{if $actbanner.0.type eq "4"}
    						<object type="application/x-shockwave-flash" width="{$actbanner.0.width}" height="{$actbanner.0.height}" data="{$smarty.session.site_bannerdir|regex_replace:"|/$|":""}/{$actbanner.0.pic}">
    							<param name="movie" value="{$smarty.session.site_bannerdir|regex_replace:"|/$|":""}/{$actbanner.0.pic}" />
    							<param name="quality" value="high" />
    							<param name="loop" value="true" />
    							<param name="FlashVars" value="playerMode=embedded" />
    							<param name="bgcolor" value="#000000" />
    						</object>
    					{else}
    						<img width="{$actbanner.0.width}" height="{$actbanner.0.height}" src="{$smarty.session.site_bannerdir|regex_replace:"|/$|":""}/{$actbanner.0.pic}" />
    					{/if}
                    {else}
                        {$actbanner.0.bcode}
                    {/if}
				</td>
			</tr>
			{foreach item=sec key=i from=$form.sections}
				{foreach item=element from=$sec.elements}
					{if $element.type neq "submit" and $element.type neq "reset"}
					<tr class="{cycle values="row1,row2"}">
					{if $element.type eq "textarea"}
						<td class="form" {if $is_tiny == 1}colspan="2"{/if}>
							{if $element.required}
								<span class="error">*</span>{/if}{$element.label}<br />
							{else}
								<td class="form">
									{if $element.required}<span class="error">*</span>{/if}{$element.label}</td>
								<td>
							{/if}
							{if $element.type eq "group"}
								{foreach key=gkey item=gitem from=$element.elements}
									{$gitem.label}
									{$gitem.html}{if $gitem.required}<span class="error">*</span>{/if}
									{if $element.separator}{cycle values=$element.separator}{/if}
								{/foreach}
							{else}
								{$element.html}
							{/if}
							{if $element.error}
								<span class="error">{$element.error}</span>
							{/if}
						</td>
					</tr>
					{else}
						{if $element.type neq "reset"}
						<tr>
							<td class="form" colspan="2">
							{if not $form.frozen}
								{if $form.requirednote}{$form.requirednote}{/if}
							{/if}
						{/if}
						{$element.html}
						{if $element.type neq "submit"}
							</td>
						</tr>
						{/if}
					{/if}
				{/foreach}
			{/foreach}
		</table>
        </form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>

<br>

<center>{$page_list}</center>
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="{$locale.admin_banners.field_list_activate_header}">{$locale.admin_banners.field_list_activate_header}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="f_content">
		<div class="f_empty"></div>
        <table>
            <tr class="row1">
                <th class="first">{$locale.admin_banners.field_list_activate_place}</th>
                <th>{$locale.admin_banners.field_list_activate_menu}</th>
                <th>{$locale.admin_banners.field_list_activate_timer}</th>
                <th>{$locale.admin_banners.field_list_activate_impmax}</th>
                <th>{$locale.admin_banners.field_list_activate_imprest}</th>
                <th>{$locale.admin_banners.field_list_activate_click}</th>
                <th>{$locale.admin_banners.field_list_activate_clickpercent}</th>
                <th class="last" width="50">{$locale.admin_banners.field_list_activate_action}</th>
            </tr>
            {foreach from=$page_data item=data}
            <tr class="{cycle values="row2,row1"}">
                <td class="first" valign="top">{$data.place_name}</td>
                <td valign="top">{$data.menu_name}</td>
                <td valign="top">{$data.timer_start}<br />{$data.timer_end}</td>
                <td valign="top">{$data.impmax}</td>
                <td valign="top">{$data.imprest}</td>
                <td valign="top">{$data.click_count}</td>
                <td valign="top">{$data.percent}%</td>
                <td class="last" valign="top">
                    <a class="action mod "href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=bactm&amp;oid={$oid}&amp;bid={$bid}&amp;mpid={$data.mpid}" title="{$lang.strAdminBannersActModify}"></a>
                    <a class="action del" href="javascript: if (confirm('{$locale.admin_banners.confirm_del_activate}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=bactd&amp;oid={$oid}&amp;bid={$bid}&amp;mpid={$data.mpid}';" title="{$locale.admin_banners.field_list_active_delete}"></a>
	           </td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="8" class="empty">
                    <img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_banners.field_list_active_notactive}" />
                    {$locale.admin_banners.field_list_active_notactive}
                </td>
            </tr>
            {/foreach}
        </table>
        <div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>