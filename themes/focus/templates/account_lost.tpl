<form {$form.attributes}>
{$form.hidden}
<table cellpadding="2" cellspacing="0" width="60%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/{$theme}/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title">{$form.header.account_lost|upper}</span></td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr class="form">
		<td></td>
		<td>{if $form.name.required}<font color="red">*</font>{/if}{$form.name.label}</td>
		<td>
			{if $form.name.error}<font color="red">{$form.name.error}</font><br />{/if}
			{$form.name.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td>{if $form.email.required}<font color="red">*</font>{/if}{$form.email.label}</td>
		<td>
			{if $form.email.error}<font color="red">{$form.email.error}</font><br />{/if}
			{$form.email.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td colspan="2">{$form.requirednote}</td>
	</tr>
	<tr class="form">
		<td></td>
		<td colspan="2">{$form.submit.html}&nbsp;{$form.reset.html}</td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
</form>
