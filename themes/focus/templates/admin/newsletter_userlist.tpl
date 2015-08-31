<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
				<input type="hidden" name="act" value="{$this_page}">
                <input type="hidden" name="sub_act" value="lst">
				{$locale.admin_newsletter.users_filter_order_by}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_newsletter.users_field_name}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_newsletter.users_field_email}</option>
				</select>
				{$locale.admin_newsletter.users_filter_by}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_newsletter.users_filter_order_asc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_newsletter.users_filter_order_desc}</option>
				</select>
                {$locale.admin_newsletter.users_tpl_order}
				<input type="submit" name="submit" value="{$locale.admin_newsletter.users_filter_order_submit}" class="submit_filter">
			</form>
		</div>
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_newsletter.users_field_name}</th>
				<th>{$locale.admin_newsletter.users_field_email}</th>
				<th class="last">{$locale.admin_newsletter.users_field_act}</th>
			</tr>
			{foreach from=$page_data item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i>{$locale.admin_newsletter.users_field_tooltip}</i></u><br>{$data.grouplist}')">{$data.name}</a></td>
                <td>{$data.email}</td>
				<td class="last">
					{if $data.is_active eq "1"}
					   <a class="action act" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_newsletter.act_inact}"></a>
					{else}
						<a class="action inact" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=act&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_newsletter.act_act}"></a>
					{/if}
					<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.admin_newsletter.act_mod}"></a>
					<a class="action del" href="javascript: if (confirm('{$locale.admin_newsletter.confirm_user_del}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;uid={$data.uid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}';" title="{$locale.admin_newsletter.act_del}"></a>
				</td>
			</tr>
			{foreachelse}
				<tr>
					<td colspan="3" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_newsletter.warning_list_empty}" />
						{$locale.admin_newsletter.warning_list_empty}
					</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
