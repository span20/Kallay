<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
                <input type="hidden" name="act" value="{$this_page}">
                <input type="hidden" name="sub_act" value="slst">
                <input type="hidden" name="nid" value="{$nid}">
                <input type="hidden" name="did" value="{$smarty.get.did}">
				{$locale.admin_newsletter.letter_filter_order_by}
				<select name="field">
					<option value="4" {$fieldselect4}>{$locale.admin_newsletter.letter_field_recipient}</option>
					<option value="5" {$fieldselect5}>{$locale.admin_newsletter.letter_field_email}</option>
                    <option value="6" {$fieldselect6}>{$locale.admin_newsletter.letter_field_senddate}</option>
				</select>
				{$locale.admin_newsletter.letter_filter_by}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$locale.admin_newsletter.letter_filter_order_asc}</option>
					<option value="desc" {$ordselect2}>{$locale.admin_newsletter.letter_filter_order_desc}</option>
				</select>
                {$locale.admin_newsletter.letter_tpl_order}
				<input type="submit" name="submit" value="{$locale.admin_newsletter.letter_filter_order_submit}" class="submit_filter">
			</form>
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_newsletter.letter_field_recipient}</th>
				<th>{$locale.admin_newsletter.letter_field_email}</th>
                <th class="last">{$locale.admin_newsletter.letter_field_senddate}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.name}</td>
					<td>{$data.email}</td>
                    <td class="last">
                        {if empty($data.sent_time)}
                            <span style="color: green;">{$locale.admin_newsletter.letter_field_ongoing}</span>
                        {else}
                            {$data.send_date}
                        {/if}
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
