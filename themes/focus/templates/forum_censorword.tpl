<form {$form_forum.attributes}>
	{$form_forum.hidden}
	<fieldset>
		<legend>{$form_forum.header.headername}</legend>
		<p>	{if $form_forum.word.required}<span class="required">*</span>{/if}
					{$form_forum.word.label}: {if $form_forum.word.error}<br /><span class="error">{$form_forum.word.error}</span>{/if}
			<br />
			{$form_forum.word.html}
		</p>
		<p>{$form_forum.requirednote}</p>
		<p>{$form_forum.submit.html} {$form_forum.reset.html}</p>
	</fieldset>
</form>
<p class="centered">
<a class="back" href="index.php?{$self}&amp;act=censor">{$lang_forum.strForumBack}</a>
</p>
