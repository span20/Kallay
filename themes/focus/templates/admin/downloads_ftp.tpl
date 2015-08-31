<div id="form_cnt">
    {include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
        </div>
		<div class="pager" style="float: left; padding-left: 10px;">
			<strong>{$locale.admin_downloads.field_location}</strong> <img src="{$theme_dir}/images/admin/dir.gif" alt="{$locale.admin_downloads.field_location}" /> 
			{$act_dir}
		</div>
		<form {$form.attributes}>
		{$form.hidden}
		<table style="clear: both;">
			<tr>
				<th class="first">{$locale.admin_downloads.list_ftplocation}</th>
				<th class="last" style="text-align: right;">
					{if $form.all.required}<span class="error">*</span>{/if}
					{if $form.all.error}<span class="error">{$form.all.error}</span>{/if}
					{$form.all.html} {$form.all.label}
				</th>
			</tr>
			{foreach from=$dirlist item=data key=key}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data}</td>
					<td class="last" style="text-align: right;"><input type="checkbox" name="fileChecked[]" value="{$data}"></td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="2" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_downloads.warning_ftpempty}" />
						{$locale.admin_downloads.warning_ftpempty}
					</td>
				</tr>
			{/foreach}
			<tr class="row1">
				<td class="form" colspan="2">
					{if not $form.frozen}
						{if $form.requirednote}{$form.requirednote}{/if}
						{$form.submit.html}{$form.reset.html}
					{/if}
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>
