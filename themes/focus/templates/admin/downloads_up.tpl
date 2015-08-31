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
			<tr class="row2">
				<td class="form">{if $form.downfile.required}<span class="error">*</span>{/if}{$form.downfile.label}</td>
				<td>{$form.downfile.html}{if $form.downfile.error}<span class="error">{$form.downfile.error}</span>{/if}</td>
			</tr>
			<tr class="row1">
				<td class="form">{if $form.desc.required}<span class="error">*</span>{/if}{$form.desc.label}</td>
				<td>{$form.desc.html}{if $form.desc.error}<span class="error">{$form.desc.error}</span>{/if}</td>
			</tr>
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
