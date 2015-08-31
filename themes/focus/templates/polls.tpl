{if $is_poll_voted == 1}
	<table cellpadding="2" cellspacing="0">
		<tr>
			<td class="block_header" style="background-color: #DAB934;">
				{$locale.$self.field_block_header|upper}
			</td>
		</tr>
		<tr><td style="height: 5px;"></td></tr>
		<tr><td class="block" align="center">{$locale.$self.field_block_voted}</td></tr>
		<tr><td class="block">{$ptitle}</td></tr>
		<tr><td class="block">{$locale.$self.field_block_lastvote}: {$polls_voted.0.poll_text}</td></tr>
		<tr><td class="block">{$locale.$self.field_block_lastdate}: {$polls_voted.0.add_date}</td></tr>
		{if $old_poll}
			<tr><td class="block"><a href="{$old_poll}" title="{$locale.$self.field_block_oldpoll}">{$locale.$self.field_block_oldpoll}</a></td></tr>
		{/if}
		<tr><td class="block"></td></tr>
	</table>
{else}
	<form {$form_polls.attributes}>
	{$form_polls.hidden}
	<table cellpadding="2" cellspacing="0">
		<tr>
			<td class="block_header" style="background-color: #DAB934;">
				{$locale.$self.field_block_header|upper}
			</td>
		</tr>
		<tr><td style="height: 5px;"></td></tr>
		{if $polls_yes != 1}
			<tr><td class="block">{$locale.$self.warning_no_poll}</td></tr>
		{else}
			<tr><td class="block">{$form_polls.header.polls}</td></tr>
			<tr><td class="block">{$form_polls.answer.html}</td></tr>
			{if $captcha}
				<tr><td class="block"><img src={$captcha} border="0" alt="captcha"></td></tr>
				<tr>
					<td class="block">
						{if $form_polls.recaptcha.required}<font color="red">*</font>{/if}
						{$form_polls.recaptcha.label}
					</td>
				</tr>
				<tr>
					<td class="block">
						{if $form_polls.recaptcha.error}<font color="red">{$form_polls.recaptcha.error}</font><br />{/if}
						{$form_polls.recaptcha.html}
					</td>
				</tr>
			{/if}
			<tr><td class="block">{$form_polls.submit.html}</td></tr>
		{/if}
		{if $old_poll}
			<tr><td class="block"><a href="{$old_poll}" title="{$locale.$self.field_block_oldpoll}">{$locale.$self.field_block_oldpoll}</a></td></tr>
		{/if}
		<tr><td class="block">&nbsp;</td></tr>
	</table>
	</form>
{/if}
