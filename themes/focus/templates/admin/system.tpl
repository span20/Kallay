<div id="table">
	<div class="tabs">
		<ul>
			{foreach from=$dynamic_tabs key=tabcode item=tabname}
			<li{if $this_page==$tabcode} class="current"{/if}><a href="admin.php?p={$self}&amp;act={$tabcode}" title="{$tabname|htmlspecialchars}">{$tabname|htmlspecialchars}</a></li>
			{foreachelse}
			<li class="current"><a href="#">...</a></li>
			{/foreach}
		</ul>
		<div class="blueleft"></div><div class="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter"></div>
		<div class="pager">{$page_list}</div>

		<table>
			<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=sys" title="{$lang_system.strAdminSystemSystem}">{$lang_system.strAdminSystemSystem}</a></td></tr>
			{if $lang_system.strAdminSystemContent}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=cont" title="{$lang_system.strAdminSystemContent}">{$lang_system.strAdminSystemContent}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemTinyMCE}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=mce" title="{$lang_system.strAdminSystemTinyMCE}">{$lang_system.strAdminSystemTinyMCE}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemDownload}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=dwn" title="{$lang_system.strAdminSystemDownload}">{$lang_system.strAdminSystemDownload}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemGallery}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=gal" title="{$lang_system.strAdminSystemGallery}">{$lang_system.strAdminSystemGallery}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemBanner}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=ban" title="{$lang_system.strAdminSystemBanner}">{$lang_system.strAdminSystemBanner}</a></td></tr>
				{if file_exists("admin/banners_system.php")}
					<tr><td><a href="admin.php?p=banners_system" title="{$lang_system.strAdminSystemPlaces}">{$lang_system.strAdminSystemPlaces}</a></td></tr>
				{/if}
			{/if}
			{if $lang_system.strAdminSystemPartners}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=partners" title="{$lang_system.strAdminSystemPartners}">{$lang_system.strAdminSystemPartners}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemShop}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=sho" title="{$lang_system.strAdminSystemShop}">{$lang_system.strAdminSystemShop}</a></td></tr>
				{if file_exists("admin/shop_system.php")}
					<tr><td><a href="admin.php?p=shop_system" title="{$lang_system.strAdminSystemProp}">{$lang_system.strAdminSystemProp}</a></td></tr>
				{/if}
			{/if}
			{if $lang_system.strAdminSystemBuilderTitle}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=builder" title="{$lang_system.strAdminSystemBuilderTitle}">{$lang_system.strAdminSystemBuilderTitle}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemStatTitle}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=stat" title="{$lang_system.strAdminSystemStatTitle}">{$lang_system.strAdminSystemStatTitle}</a></td></tr>
			{/if}
			{if $lang_system.strAdminSystemClassTitle}
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=class" title="{$lang_system.strAdminSystemClassTitle}">{$lang_system.strAdminSystemClassTitle}</a></td></tr>
			{/if}
            <tr><td><a href="admin.php?p=users_system" title="{$lang_system.strAdminSystemUsersTitle}">{$lang_system.strAdminSystemUsersTitle}</a></td></tr>
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>
