<form {$form_guestbook.attributes}>
{$form_guestbook.hidden}
<table cellpadding="2" cellspacing="0" width="60%">
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr class="form">
		<td>&nbsp;</td>
		<td valign="top">
			{if $form_guestbook.guestbook_answer.required}<font color="red">*</font>{/if}
			{$form_guestbook.guestbook_answer.label}
		</td>
		<td>
			{if $form_guestbook.guestbook_answer.error}<font color="red">{$form_guestbook.guestbook_answer.error}</font><br />{/if}
			{$form_guestbook.guestbook_answer.html}
		</td>
	</tr>
	{if $form_guestbook.requirednote and not $form_guestbook.frozen}
		<tr class="form">
			<td>&nbsp;</td>
			<td colspan="2">{$form_guestbook.requirednote}</td>
		</tr>
	{/if}
	<tr class="form">
		<td>&nbsp;</td>
		<td colspan="2">{$form_guestbook.submit.html} {$form_guestbook.reset.html}</td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
</form>
