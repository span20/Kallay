<style type="text/css"><!--{literal}
.thumbnail_border{
border: 2px solid #688da8;
margin: 5px;
float: left;
display: inline;
}
.thumbnail .spacer {{/literal}
height: {$smarty.session.site_banner_widths}px;{literal}
background-color: #C9D5DF;
margin-top: 5px;
display: block;
background-color: #C9D5DF;
}
.thumbnail{{/literal}
width: {$smarty.session.site_banner_widths}px;{literal}
overflow:hidden;
text-align:center;
font-size:10px;
color: #FFFFFF;
}
.thumbnail span{
background-color: #98ABB8;
display:block;
margin: 0px auto;
height: 14px;
line-height: 14px;
overflow:hidden;
}
.thumbnail img, .thumbnail object {
border:none;
margin:auto;
}
{/literal}
-->
</style>
<script type="text/javascript">//<![CDATA[
{literal}
	function torol(bid)
	{
	{/literal}
		x = confirm('{$locale.admin_banners.confirm_del_banner}');
		oid={$oid};
		{literal}
		if (x) {
			document.location.href='admin.php?p={/literal}{$self}{literal}&act={/literal}{$this_page}{literal}&sub_act=bdel&oid='+oid+'&bid='+bid;
		}
		{/literal}
	{literal}
	}
{/literal}
//]]>
</script>

<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
        </div>
		<div class="pager">{$page_list}</div>
		<div style="margin:auto;text-align:left;background:none;">
			{foreach from=$page_data item=data}
			  <div class="thumbnail_border">
				<div class="thumbnail">
					<span>
                        {* SAJAT BANNER *}
                        {if $data.banner_link}{$data.banner_link}{/if}
                        {* SAJAT BANNER VEGE *}
                        {* KULSO BANNER *}
                        {if $data.banner_code}{$locale.admin_banners.field_outside}{/if}
                        {* KULSO BANNER VEGE *}
                    </span>
					<div class="spacer">
                    {if $data.banner_link}
    					{if $data.type eq "4"}
    						<object type="application/x-shockwave-flash"
    							{getdim width=$data.width height=$data.height}
    							data="{$smarty.session.site_bannerdir|regex_replace:"|/$|":""}/{$data.realname}">
    							<param name="movie" value="{$smarty.session.site_bannerdir|regex_replace:"|/$|":""}/{$data.realname}" />
    							<param name="quality" value="high" />
    							<param name="loop" value="false" />
    							<param name="FlashVars" value="playerMode=embedded" />
    							<param name="bgcolor" value="#000000" />
    						</object>
    					{else}
    						<img 
    							{getdim width=$data.width height=$data.height}  
    							src="{$smarty.session.site_bannerdir|regex_replace:"|/$|":""}/{$data.realname}" />
    					{/if}
                    {/if}
                    {if $data.banner_code}
                        <div style="width: {$smarty.session.site_banner_widths}px; overflow: auto;">{$data.banner_code}</div>
                    {/if}
					</div>
					<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=bacta&amp;oid={$oid}&amp;bid={$data.banner_id}" title="{$locale.admin_banners.field_list_activate}"></a>
					<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=bmod&amp;oid={$oid}&amp;bid={$data.banner_id}" title="{$locale.admin_banners.field_list_modify}"></a>
					<a class="action del" href="javascript:torol({$data.banner_id});" title="{$locale.admin_banners.field_list_delete}"></a>
				</div>
			 </div>
			{foreachelse}
				<p class="empty" style="clear:left;">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_banners.warning_no_banners}" />
					{$locale.admin_banners.warning_no_banners}
				</p>
			{/foreach}
		</div>
		<div class="pager">{$page_list}</div>
	</div>
	<div id="t_bottom"></div>
</div>