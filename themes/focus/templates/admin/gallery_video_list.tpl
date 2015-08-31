<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">
            <form action="admin.php" method="get">
                <input type="hidden" name="p" value="{$self}">
				<input type="hidden" name="act" value="{$this_page}">
                {$locale.admin_gallery.video_list_orderby}
                <select name="field">
                    <option value="1" {$fieldselect1}>{$locale.admin_gallery.video_list_name}</option>
                    <option value="2" {$fieldselect2}>{$locale.admin_gallery.video_list_adddate}</option>
                </select>
                {$locale.admin_gallery.video_list_adminby}
                <select name="ord">
                    <option value="asc" {$ordselect1}>{$locale.admin_gallery.video_list_orderasc}</option>
                    <option value="desc" {$ordselect2}>{$locale.admin_gallery.video_list_orderdesc}</option>
                </select>
                {$locale.admin_gallery.video_list_order}
                <input type="submit" name="submit" value="{$locale.admin_gallery.video_list_submitorder}" class="submit_filter" />
            </form>
        </div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_gallery.video_list_name}</th>
				<th>{$locale.admin_gallery.video_list_adddate}</th>
				<th class="last">{$locale.admin_gallery.video_list_action}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i>{$locale.admin_gallery.video_list_description}</i></u><br>{$data.description|escape:quotes}')">{$data.name}</a></td>
					<td>{$data.add_date}</td>
					<td class="last">
						{if $data.is_active}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;gid={$data.gid}" title="{$locale.admin_gallery.video_list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;gid={$data.gid}" title="{$locale.admin_gallery.video_list_active}"></a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=gmod&amp;gid={$data.gid}" title="{$locale.admin_gallery.video_list_modify}"></a>
						<a class="action" style="background: url({$theme_dir}/images/admin/gallery.gif) no-repeat top left;" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=plst&amp;gid={$data.gid}" title="{$locale.admin_gallery.video_list_video}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_gallery.confirm_video_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=gdel&amp;gid={$data.gid}';" title="{$locale.admin_gallery.video_list_delete}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="2" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_gallery.warning_video_empty}" />
						{$locale.admin_gallery.warning_video_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>