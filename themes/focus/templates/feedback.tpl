<form {$form_feedback.attributes}>
{$form_feedback.hidden}
<table cellpadding="2" cellspacing="0" width="60%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title">{$form_feedback.header.feedback|upper}</span></td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_feedback.name.required}<font color="red">*</font>{/if}
			{$form_feedback.name.label}
		</td>
		<td>
			{if $form_feedback.name.error}<font color="red">{$form_feedback.name.error}</font><br />{/if}
			{$form_feedback.name.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_feedback.email.required}<font color="red">*</font>{/if}
			{$form_feedback.email.label}
		</td>
		<td>
			{if $form_feedback.email.error}<font color="red">{$form_feedback.email.error}</font><br />{/if}
			{$form_feedback.email.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_feedback.subject.required}<font color="red">*</font>{/if}
			{$form_feedback.subject.label}
		</td>
		<td>
			{if $form_feedback.subject.error}<font color="red">{$form_feedback.subject.error}</font><br />{/if}
			{$form_feedback.subject.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_feedback.message.required}<font color="red">*</font>{/if}
			{$form_feedback.message.label}
		</td>
		<td>
			{if $form_feedback.message.error}<font color="red">{$form_feedback.message.error}</font><br />{/if}
			{$form_feedback.message.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_feedback.copymail.required}<font color="red">*</font>{/if}
			{$form_feedback.copymail.label}
		</td>
		<td>
			{if $form_feedback.copymail.error}<font color="red">{$form_feedback.copymail.error}</font><br />{/if}
			{$form_feedback.copymail.html}
		</td>
	</tr>
	{if $form_feedback.requirednote and not $form_feedback.frozen}
		<tr class="form">
			<td></td>
			<td colspan="2">{$form_feedback.requirednote}</td>
		</tr>
	{/if}
	<tr class="form">
		<td></td>
		<td colspan="2">{$form_feedback.submit.html} {$form_feedback.reset.html}</td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
</form>
