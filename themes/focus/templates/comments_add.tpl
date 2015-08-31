{* HA VALASZOLUNK, AKKOR KIIRJUK AZ ELOZMENYT *}
{if $predata}
<div>
    <p>{$locale.index_comments.field_comment_premise}</p>
    <p>{$predata.name} - {$predata.add_date}</p>
    <p>{$predata.comment|nl2br}</p>
</div>
{/if}
{* ELOZMENY VEGE *}

{* HOZZASZOLAS FORMJA *}
{if $form_comment}
<div>
    <form {$form_comment.attributes}>
	{$form_comment.hidden}
	<table>
		<tr>
			<td>{if $form_comment.newscomment_name.required}<span class="required">*</span>{/if}{$form_comment.newscomment_name.label}</td>
			<td>{$form_comment.newscomment_name.html}{if $form_comment.newscomment_name.error}<span class="error">{$form_comment.newscomment_name.error}</span>{/if}</td>
		</tr>
		<tr>
			<td>{if $form_comment.newscomment_message.required}<span class="required">*</span>{/if}{$form_comment.newscomment_message.label}</td>
			<td>{$form_comment.newscomment_message.html}{if $form_comment.newscomment_message.error}<span class="error">{$form_comment.newscomment_message.error}</span>{/if}</td>
		</tr>
		{if $form_comment.newscomment_recaptcha}
			<tr>
				<td></td>
				<td><img src={$newscomment_captcha} border="0" alt="captcha"></td>
			</tr>
			<tr>
				<td>{if $form_comment.newscomment_recaptcha.required}<span class="required">*</span>{/if}{$form_comment.newscomment_recaptcha.label}</td>
				<td>{$form_comment.newscomment_recaptcha.html}{if $form_comment.newscomment_recaptcha.error}<span class="error">{$form_comment.newscomment_recaptcha.error}</span>{/if}</td>
			</tr>
		{/if}
		{if $form_comment.requirednote and not $form_comment.frozen}
			<tr><td colspan="2"><span class="form_text">{$form_comment.requirednote}</span></td></tr>
		{/if}
		<tr><td colpsan="2">{$form_comment.submit.html} {$form_comment.reset.html}</td></tr>
	</table>
	</div>
{/if}
{* FORM VEGE *}