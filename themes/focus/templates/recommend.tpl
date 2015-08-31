<form {$form_recommend.attributes}>
{$form_recommend.hidden}
<table cellpadding="2" cellspacing="0" width="60%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title">{$form_recommend.header.recommend|upper}</span></td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_recommend.sendername.required}<font color="red">*</font>{/if}
			{$form_recommend.sendername.label}
		</td>
		<td>
			{if $form_recommend.sendername.error}<font color="red">{$form_recommend.sendername.error}</font><br />{/if}
			{$form_recommend.sendername.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_recommend.sendermail.required}<font color="red">*</font>{/if}
			{$form_recommend.sendermail.label}
		</td>
		<td>
			{if $form_recommend.sendermail.error}<font color="red">{$form_recommend.sendermail.error}</font><br />{/if}
			{$form_recommend.sendermail.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_recommend.recipename.required}<font color="red">*</font>{/if}
			{$form_recommend.recipename.label}
		</td>
		<td>
			{if $form_recommend.recipename.error}<font color="red">{$form_recommend.recipename.error}</font><br />{/if}
			{$form_recommend.recipename.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			{if $form_recommend.recipemail.required}<font color="red">*</font>{/if}
			{$form_recommend.recipemail.label}
		</td>
		<td>
			{if $form_recommend.recipemail.error}<font color="red">{$form_recommend.recipemail.error}</font><br />{/if}
			{$form_recommend.recipemail.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_recommend.message.required}<font color="red">*</font>{/if}
			{$form_recommend.message.label}
		</td>
		<td>
			{if $form_recommend.message.error}<font color="red">{$form_recommend.message.error}</font><br />{/if}
			{$form_recommend.message.html}
		</td>
	</tr>
	{if $form_recommend.requirednote and not $form_recommend.frozen}
		<tr class="form">
			<td></td>
			<td colspan="2">{$form_recommend.requirednote}</td>
		</tr>
	{/if}
	<tr class="form">
		<td></td>
		<td colspan="2">{$form_recommend.submit.html} {$form_recommend.reset.html}</td>
	</tr>
</table>
</form>
