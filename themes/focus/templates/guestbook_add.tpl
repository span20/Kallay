<form {$form_guestbook.attributes}>
{$form_guestbook.hidden}
<p>
	{if $form_guestbook.guestbook_name.required}<span class="required">*</span>{/if}
	{$form_guestbook.guestbook_name.label}:<br />
	{if $form_guestbook.guestbook_name.error}<span class="error">{$form_guestbook.guestbook_name.error}</span><br />{/if}
	{$form_guestbook.guestbook_name.html}
</p>
<p>
	{if $form_guestbook.guestbook_email.required}<span class="required">*</span>{/if}
	{$form_guestbook.guestbook_email.label}:<br />
	{if $form_guestbook.guestbook_email.error}<span class="error">{$form_guestbook.guestbook_email.error}</span><br />{/if}
	{$form_guestbook.guestbook_email.html}
</p>
<p>
	{if $form_guestbook.guestbook_message.required}<span class="required">*</span>{/if}
	{$form_guestbook.guestbook_message.label}:<br />
	{if $form_guestbook.guestbook_message.error}<span class="error">{$form_guestbook.guestbook_message.error}</span><br />{/if}
	{$form_guestbook.guestbook_message.html}
</p>
	{if $gb_captcha}
	<p>
		<img src="{$gb_captcha}" border="0" alt="gb_captcha" /><br />
		{if $form_guestbook.gb_recaptcha.required}<span class="required">*</span>{/if}
		{$form_guestbook.gb_recaptcha.label}:<br />
		{if $form_guestbook.gb_recaptcha.error}<span class="error">{$form_guestbook.gb_recaptcha.error}</span><br />{/if}
		{$form_guestbook.gb_recaptcha.html}
	</p>
	{/if}
	{if $form_guestbook.requirednote and not $form_guestbook.frozen}
	<p>
		{$form_guestbook.requirednote}
	</p>
	{/if}
<p>
{$form_guestbook.gb_submit.html}
{$form_guestbook.gb_reset.html}
</p>
</form>

