<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
                <input type="hidden" name="act" value="{$this_page}">
                <input type="hidden" name="sub_act" value="slst">
                <input type="hidden" name="nid" value="{$nid}">
				{$locale.admin_newsletter.letter_filter_order_by}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_newsletter.letter_field_send_date}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_newsletter.letter_field_sender}</option>
                    <option value="3" {$fieldselect3}>{$locale.admin_newsletter.letter_field_sendermail}</option>
				</select>
				{$locale.admin_newsletter.letter_filter_by}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_newsletter.letter_filter_order_asc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_newsletter.letter_filter_order_desc}</option>
				</select>
                {$locale.admin_newsletter.letter_tpl_order}
				<input type="submit" name="sbmt" value="{$locale.admin_newsletter.letter_filter_order_submit}" class="submit_filter">
			</form>
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_newsletter.letter_field_send_date}</th>
				<th>{$locale.admin_newsletter.letter_field_sender}</th>
                <th>{$locale.admin_newsletter.letter_field_sendermail}</th>
				<th class="last">{$locale.admin_newsletter.letter_field_act}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.date}</td>
					<td>{$data.user}</td>
                    <td>{$data.sendermail}</td>
					<td class="last">
						<a class="action sendlist" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=slst&amp;nid={$nid}&amp;did={$data.date_id}" title="{$locale.admin_newsletter.act_recipients}"></a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="3" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="error" />
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
