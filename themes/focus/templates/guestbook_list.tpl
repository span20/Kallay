<p>
{$locale.index_guestbook.total} <b>{$total}</b>
</p>
<p style="text-align:center;"><a href="index.php?p={$self}&amp;act=guestbook_add" title="{$locale.index_guestbook.act_add}">{$locale.index_guestbook.act_add}</a></p>

<table cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<td colspan="3" align="center" class="pager">{$page_list}</td>
	</tr>
	{foreach from=$page_data item=data}
	<tr>
		<td>&nbsp;</td>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" style="padding-right: 10px;">
				<tr style="height: 19px;font-weight:bold;background:#EFC47A;">
					<td style="padding-left: 15px;" class="red">
						{if $data.gname != ""}
							{$data.gname}
						{else}
							{$data.username}
						{/if}
						{if $data.gemail != ""}
							&lt; {mailto address=$data.gemail} &gt;
						{else}
							{if $data.pmail == 1}< {$data.umail} >{/if}
						{/if}
					</td>
					<td align="right" style="padding-right: 15px;" class="red">{$data.add_date|local_date:"longdatetime"}</td>
				</tr>
				<tr>
					<td colspan="2" class="mainnews_text" style="border-left: 1px solid #EFC47A;border-bottom: 1px solid #EFC47A;  border-right: 1px solid #EFC47A; padding: 5px 15px 5px 15px;">
						{$data.gmess|htmlspecialchars|nl2br}
					</td>
				</tr>
				{if $data.gans != ""}
					<tr>
						<td colspan="2"  style="border-left: 1px solid #EFC47A; border-bottom: 1px solid #EFC47A; border-right: 1px solid #EFC47A; color: #952E45; font-weight:bold;padding: 0 15px;">
							{$data.gans|htmlspecialchars|nl2br}
						</td>
					</tr>
				{/if}
				{if $is_enable_link != "" || $is_reply_link != "" || $is_delete_link != ""}
				<tr>
					<td colspan="2" style="background-color:#efc47a; border: 1px solid #EFC47A; height: 19px; padding: 0 15px;">
						{if $is_enable_link != ""}
							<a href="{$is_enable_link}{$data.gid}" title="
							{if $data.gena == 1}
								{$locale.index_guestbook.act_deny}">{$locale.index_guestbook.act_deny}
							{else}
								{$locale.index_guestbook.act_allow}">{$locale.index_guestbook.act_allow}
							{/if}
							</a>
						{/if}
						{if $is_reply_link != ""}
							&nbsp;<a href="{$is_reply_link}{$data.gid}" title="{$locale.index_guestbook.act_reply}">{$locale.index_guestbook.act_reply}</a>
						{/if}
						{if $is_delete_link != ""}
							&nbsp;<a href="javascript: if (confirm('{$locale.index_guestbook.confirm_del}')) document.location.href='{$is_delete_link}{$data.gid}';" title="{$locale.index_guestbook.act_del}">{$locale.index_guestbook.act_del}</a>
						{/if}
					</td>
				</tr>
				{/if}
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
{foreachelse}
	<tr><td colspan="3" class="hiba">{$locale.index_guestbook.warning_no_messages}</td></tr>
{/foreach}
</table>