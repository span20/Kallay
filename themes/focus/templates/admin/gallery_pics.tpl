<style type="text/css"><!--
	{literal}
	.gallery_thumbnail  { {/literal}
		/*width: {$smarty.session.site_thumbwidth+14}px;
		height: {$smarty.session.site_thumbheight+50}px;*/
		position: relative;
	{literal}
		border: 2px solid #688da8;
		background-color: #e6edf2;
		text-align:center;line-height:14px;
		margin: 10px;
		display: block;
		float: left;
		voice-family: "\"}\"";
		voice-family: inherit;
	{/literal}
		/*width: {$smarty.session.site_thumbwidth+10}px;
		height: {$smarty.session.site_thumbheight+50}px;*/
	{literal}
	} html>body .gallery_thumbnail { 
	{/literal}
		/*width: {$smarty.session.site_thumbwidth+10}px;
		height: {$smarty.session.site_thumbheight+50}px;*/
	{literal}
	} 
	.gallery_thumbnail span {
		font-size:10px;
		display: block;
		text-align:center; {/literal}
		/*width: {$smarty.session.site_thumbwidth+8}px; 
		position: absolute; 
		top: {$smarty.session.site_thumbheight+2}px;*/ 
		left: 0px;   
	{literal}
	}
	{/literal}
//-->
</style>
<script type="text/javascript">//<![CDATA[
{literal}
	function torol(pid)
	{
	{/literal}
		x = confirm('{$locale.admin_gallery.confirm_pics_del}');
		gid = {$gid};

		if (x) {literal}{{/literal}
			document.location.href='admin.php?p={$self}&act={$this_page}&sub_act=pdel&gid='+gid+'&pid='+pid;
		{literal}}{/literal}
	{literal}
	}
{/literal}
//]]>
</script>
<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
        </div>
		<div class="pager">{$page_list}</div>
			<div style="text-align: center; display: block; background: none;">
				<div style="margin: auto; text-align: left; background: none; width: auto;">
					{foreach from=$page_data item=data name="pics"}
						<div class="gallery_thumbnail">
							<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=view&amp;gid={$gid}&amp;pid={$data.picture_id}" title="{$data.name|htmlspecialchars}">
                                <img src="{$smarty.session.site_galerydir}/tn_{$data.realname}" width="{$data.tn_width}" height="{$data.tn_height}" alt="{$data.name}" />
                            </a>
							<span>
								<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pmod&amp;gid={$gid}&amp;pid={$data.picture_id}" title="{$locale.admin_gallery.list_pics_modify}">[{$locale.admin_gallery.list_pics_modify}]</a><br>
								<a href="javascript:torol({$data.picture_id})" title="{$locale.admin_gallery.list_pics_delete}">[{$locale.admin_gallery.list_pics_delete}]</a><br />
								{if !$smarty.foreach.pics.first}
								<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pord&amp;actorder={$data.orders}&amp;way=up&amp;gid={$gid}&amp;pid={$data.picture_id}"><<</a>
								{/if}
								{if !$smarty.foreach.pics.last}
								<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pord&amp;actorder={$data.orders}&amp;way=down&amp;gid={$gid}&amp;pid={$data.picture_id}">>></a>
								{/if}
							</span>
						</div>
					{foreachelse}
						<p class="hiba" style="clear:left;">{$locale.admin_gallery.warning_pics_empty}</p>
					{/foreach}
				</div>
			</div>
		<div class="pager">{$page_list}</div>
	</div>
	<div id="t_bottom"></div>
</div>