<div id="table">
	<div id="ear">
		<ul>
			<li><a href="admin.php?p=gallery" title="{$lang_gallery.strAdminGalleryHeader}">{$lang_gallery.strAdminGalleryHeader}</a></li>
			<li id="current"><a href="admin.php?p={$self}" title="{$lang_gallery.strAdminGalleryHeader}">{$lang_gallery.strAdminGallerySendHeader}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$lang_gallery.strAdminGalleryListName}</th>
				<th class="first">{$lang_gallery.strAdminGalleryListType}</th>
				<th class="last">{$lang_gallery.strAdminGalleryListAction}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i>{$lang_gallery.strAdminGalleryDescription}</i></u><br>{$data.description|escape:quotes}')">{$data.name}</a></td>
					<td>
					{if $data.type eq "p"}
						{$lang_gallery.strAdminGalleryTypePic}
					{else}
						{$lang_gallery.strAdminGalleryTypeVideo}
					{/if}
					</td>
					<td class="last">
						<a href="admin.php?p={$self}&amp;act=act&amp;gid={$data.gid}" title="{$lang_gallery.strAdminGalleryInActive}">
							<img src="{$theme_dir}/images/admin/inactive.gif" border="0" alt="{$lang_gallery.strAdminGalleryInActive}" />
						</a>
						<a href="admin.php?p={$self}&amp;act=mod&amp;gid={$data.gid}">
							<img src="{$theme_dir}/images/admin/modify.gif" border="0" alt="{$lang_gallery.strAdminGalleryModify}" />
						</a>
						<a href="admin.php?p={$self}&amp;act=pic&amp;gid={$data.gid}">
							<img src="{$theme_dir}/images/admin/gallery.gif" border="0" alt="{$lang_gallery.strAdminGalleryPics}" />
						</a>
						<a href="javascript: if (confirm('{$lang_gallery.strAdminGalleryDelconf}')) document.location.href='admin.php?p={$self}&amp;act=del&amp;gid={$data.gid}';">
							<img src="{$theme_dir}/images/admin/delete.gif" border="0" alt="{$lang_gallery.strAdminGalleryDelete}" />
						</a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="2" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$lang_gallery.strAdminGalleryEmpty}" />
						{$lang_gallery.strAdminGalleryEmpty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
