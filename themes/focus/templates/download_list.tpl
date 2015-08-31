<table cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/{$theme}/images/nyil_szurke.png" border="0" alt=""></td>
		<td width="97%" style="border-top: 1px solid;"><span class="mainnews_title">{$locale.index_downloads.list_title|upper} - {$act_dir|upper}</span></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td></td>
		<td>
			<b>{$locale.index_downloads.list_dirnum}</b> {$cdir} <b>{$locale.index_downloads.list_filenum}</b> {$cfile}<br />
			{$locale.index_downloads.list_dirsumsize} {$dirsumsize} KB&nbsp;&nbsp;
			<a href="index.php?mid={$menu_id}&amp;parent=0" title="{$locale.index_downloads.list_titleroot}"><b>\</b></a>&nbsp;&nbsp;
			<a href="index.php?mid={$menu_id}&amp;parent={$dirlist.0.parent}" title="{$locale.index_downloads.list_titleup}"><b>..</b></a>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	{foreach from=$dirlist item=data}
		{if $data.up != ""}
			<tr>
				<td></td>
				<td><a href="index.php?mid={$menu_id}&amp;parent={$data.parent}" title="{$locale.index_downloads.list_titleup}"><b>..</b></a></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		{else}
			<tr>
				<td></td>
				<td>{if $data.type == "D"}<a href="index.php?mid={$menu_id}&amp;parent={$data.did}" title="{$data.name}"><b>{$data.name}</b></a></td>
			</tr>
					{else}<a href="index.php?mid={$menu_id}&amp;parent={$data.did}&amp;did={$data.did}" title="{$data.name}"><b>{$data.name}</b></a></td>
			</tr>
			<tr>
				<td></td>
				<td><b>{$locale.index_downloads.list_size}</b> {$data.size} KB <b>{$locale.index_downloads.list_amount}</b> {$data.amount}</td>
			</tr>{/if}
			<tr>
				<td></td>
				<td><b>{$locale.index_downloads.list_add}</b> {$data.add_date} <b>{$locale.index_downloads.list_mod}</b> {$data.mod_date}</td>
			</tr>
			<tr>
				<td></td>
				<td><b>{$locale.index_downloads.list_description}</b> {$data.desc}</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		{/if}
	{/foreach}
</table>
