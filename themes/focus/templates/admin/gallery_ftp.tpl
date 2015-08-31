<div id="table">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div id="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
		</div>
		<div id="pager">{$page_list}</div>
			<form {$form.attributes}>
			{$form.hidden}
			<table>
				<tr>
					<th colspan="2">{$locale.admin_gallery.field_list_gallery_ftp}: {$act_dir}</th>
				</tr>
				<tr><td style="height: 1px; color:#FFFFFF;"></td></tr>
				<tr>
					<th>{$locale.admin_gallery.field_list_gallery_ftplocation}</th>
					<th style="text-align: right">
						{if $form.all.required}<font color="red">*</font>{/if}
						{if $form.all.error}<font color="red">{$form.all.error}</font><br />{/if}
						{$form.all.label} {$form.all.html}
					</th>
				</tr>
				<tr><td style="height: 1px; color:#FFFFFF;"></td></tr>
				{foreach from=$dirlist item=data key=key}
					<tr bgcolor="{cycle values="row1,row2"}">
						<td>{$data}</td>
						<td align="right"><input type="checkbox" name="fileChecked[]" value="{$data}"></td>
					</tr>
				{foreachelse}
					<tr><td colspan="2" class="hiba">{$locale.admin_gallery.warning_emptyftp}</td></tr>
				{/foreach}

                <tr class="row2">
                    <td class="form" colspan="2">
                        {if not $form.frozen}
                            {if $form.requirednote}{$form.requirednote}{/if}
                            {$form.submit.html}
                        {/if}
                    </td>
                </tr>
			</table>
			</form>
		<div id="pager">{$page_list}</div>
	</div>
	<div id="t_bottom"></div>
</div>
