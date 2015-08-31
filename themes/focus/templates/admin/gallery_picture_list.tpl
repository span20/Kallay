<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">
            <form action="admin.php" method="get">
                <input type="hidden" name="p" value="{$self}">
				<input type="hidden" name="act" value="{$this_page}">
                {$locale.admin_gallery.list_orderby}
                <select name="field">
                    <option value="1" {$fieldselect1}>{$locale.admin_gallery.list_name}</option>
                    <option value="2" {$fieldselect2}>{$locale.admin_gallery.list_adddate}</option>
                </select>
                {$locale.admin_gallery.list_adminby}
                <select name="ord">
                    <option value="asc" {$ordselect1}>{$locale.admin_gallery.list_orderasc}</option>
                    <option value="desc" {$ordselect2}>{$locale.admin_gallery.list_orderdesc}</option>
                </select>
                {$locale.admin_gallery.list_order}
                <input type="submit" name="submit" value="{$locale.admin_gallery.list_submitorder}" class="submit_filter" />
            </form>
        </div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_gallery.list_name}</th>
				<th>{$locale.admin_gallery.list_adddate}</th>
				<th class="last">{$locale.admin_gallery.list_action}</th>
			</tr>
			{defun name="menu" list=$page_data}
			{foreach from=$list item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first" {if $data.level > 1}style="padding-left: {$data.level*20}px;"{/if}><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i>{$locale.admin_gallery.list_description}</i></u><br>{$data.description|escape:quotes}')">{$data.name}</a></td>
					<td>{$data.add_date}</td>
					<td class="last">
						{if $data.is_active}
							<a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;gid={$data.gid}" title="{$locale.admin_gallery.list_inactive}"></a>
						{else}
							<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;gid={$data.gid}" title="{$locale.admin_gallery.list_active}"></a>
						{/if}
						{if $data.level < 3}
						<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=gadd&amp;par={$data.gid}" title="{$locale.admin_menus.field_list_submenu}">
							<img src="{$theme_dir}/images/admin/submenu.gif" border="0" alt="{$locale.admin_menus.field_list_submenu}" />
						</a>
						{/if}
						<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=gmod&amp;gid={$data.gid}" title="{$locale.admin_gallery.list_modify}"></a>
						<a class="action" style="background: url({$theme_dir}/images/admin/gallery.gif) no-repeat top left;" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=plst&amp;gid={$data.gid}" title="{$locale.admin_gallery.list_gallery}"></a>
						<a class="action del" href="javascript: if (confirm('{$locale.admin_gallery.confirm_gallery_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=gdel&amp;gid={$data.gid}';" title="{$locale.admin_gallery.list_delete}"></a>
					</td>
				</tr>
			{if $data.element}
				{fun name="menu" list=$data.element}
			{/if}
			{foreachelse}
				<tr>
					<td colspan="2" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_gallery.warning_gallery_empty}" />
						{$locale.admin_gallery.warning_gallery_empty}
					</td>
				</tr>
			{/foreach}
			{/defun}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
