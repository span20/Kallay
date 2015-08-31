<div id="cnt">
	<div id="c_top"></div>
		<div id="c_content">
			{foreach name=menu from=$settinglist item=data}
    			<a href="admin.php?p={$self}&amp;file={$data.mfile}{$data.mext}&amp;act=mod" class="linkopacity s_icon" title="{$data.mname}">
    				<img src="{$theme_dir}/images/admin/settings/{$data.mfile}.jpg" class="c_image" alt="{$data.mname}" border="0" />
    				<span class="c_link">{$data.mname}</span>
    			</a>
            {foreachelse}
                {$locale.admin_settings.warning_empty_list}
			{/foreach}
		</div>
	<div id="c_bottom"></div>
</div>

