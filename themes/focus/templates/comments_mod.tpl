{if $smarty.session.site_cnt_is_comment_news}
	<div>
		<form {$form_news_comment.attributes}>
		{$form_news_comment.hidden}
		<table>
			<tr>
				<td>{if $form_news_comment.newscomment_name.required}<span class="required">*</span>{/if}{$form_news_comment.newscomment_name.label}</td>
				<td>{$form_news_comment.newscomment_name.html}{if $form_news_comment.newscomment_name.error}<span class="error">{$form_news_comment.newscomment_name.error}</span>{/if}</td>
			</tr>
			<tr>
				<td>{if $form_news_comment.newscomment_message.required}<span class="required">*</span>{/if}{$form_news_comment.newscomment_message.label}</td>
				<td>{$form_news_comment.newscomment_message.html}{if $form_news_comment.newscomment_message.error}<span class="error">{$form_news_comment.newscomment_message.error}</span>{/if}</td>
			</tr>
			{if $form_news_comment.newscomment_recaptcha}
				<tr>
					<td></td>
					<td><img src={$newscomment_captcha} border="0" alt="captcha"></td>
				</tr>
				<tr>
					<td>{if $form_news_comment.newscomment_recaptcha.required}<span class="required">*</span>{/if}{$form_news_comment.newscomment_recaptcha.label}</td>
					<td>{$form_news_comment.newscomment_recaptcha.html}{if $form_news_comment.newscomment_recaptcha.error}<span class="error">{$form_news_comment.newscomment_recaptcha.error}</span>{/if}</td>
				</tr>
			{/if}
			{if $form_news_comment.requirednote and not $form_news_comment.frozen}
				<tr><td colspan="2"><span class="form_text">{$form_news_comment.requirednote}</span></td></tr>
			{/if}
			<tr><td colpsan="2">{$form_news_comment.submit.html} {$form_news_comment.reset.html}</td></tr>
		</table>
	</div>
{/if}
