{literal}
<script type="text/javascript">//<![CDATA[
function torol(nid)
{{/literal}
	x = confirm('{$locale.admin_newsletter.confirm_newsletter_del}');{literal}
	if (x) {{/literal}
		document.location.href='admin.php?p={$self}&act={$this_page}&sub_act=del&nid='+nid
    {literal}}
}
//]]>
</script>
{/literal}

<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div class="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="{$self}">
				<input type="hidden" name="act" value="{$this_page}">
				{$locale.admin_newsletter.letter_filter_order_by}
				<select name="field">
					<option value="1" {$fieldselect1}>{$locale.admin_newsletter.letter_field_subject}</option>
					<option value="2" {$fieldselect2}>{$locale.admin_newsletter.letter_field_add_date}</option>
					<option value="3" {$fieldselect3}>{$locale.admin_newsletter.letter_field_author}</option>
                    <option value="4" {$fieldselect4}>{$locale.admin_newsletter.letter_field_id}</option>
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
		<div class="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$locale.admin_newsletter.letter_field_subject}</th>
				<th>{$locale.admin_newsletter.letter_field_add_date}</th>
				<th>{$locale.admin_newsletter.letter_field_author}</th>
				<th>{$locale.admin_newsletter.letter_field_send_counter}</th>
				<th class="last">{$locale.admin_newsletter.letter_field_act}</th>
			</tr>
			{foreach from=$page_data item=data}
			<tr class="{cycle values="row1,row2"}">
				<td class="first">{$data.subject}</td>
				<td>{$data.add_date}</td>
				<td>{$data.username}</td>
				<td>{is_sent nid=$data.nid}</td>
				<td class="last">
					<a class="action sendinfo" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=slst&amp;nid={$data.nid}" title="{$locale.newsletter.act_sendinfo}"></a>
					<a class="action send" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=send&amp;nid={$data.nid}" title="{$locale.newsletter.act_send}"></a>
					<a class="action mod" href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;nid={$data.nid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$locale.newsletter.act_mod}"></a>
					<a class="action del" href="javascript: torol({$data.nid});;" title="{$locale.newsletter.act_del}"></a>
				</td>
			</tr>
			{foreachelse}
				<tr>
					<td colspan="6" class="empty">
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
