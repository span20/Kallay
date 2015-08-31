<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
		</div>
		<div class="pager"></div>
			<div style="display: block; text-align: center;">
				<p><img src="{$smarty.session.site_galerydir}/{$pic.realname}" width="{$pic.width}" height="{$pic.height}" alt="{$pic.name|htmlspecialchars}" /></p>
                <p>{$locale.admin_gallery.field_view_width} {$pic.width} {$locale.admin_gallery.field_pixel}, {$locale.admin_gallery.field_view_height} {$pic.height} {$locale.admin_gallery.field_pixel}</p>
			</div>
		<div class="pager"></div>
	</div>
	<div id="t_bottom"></div>
</div>