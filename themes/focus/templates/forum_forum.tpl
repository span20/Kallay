<form {$form_forum.attributes}>
{$form_forum.hidden}
<fieldset><legend>{$form_forum.header.headername}</legend>
<p>{if $form_forum.topic_name.required}<span class="required">*</span>{/if}
			{$form_forum.topic_name.label}: {if $form_forum.topic_name.error}<br /><span class="error">{$form_forum.topic_name.error}</span>{/if}
	<br />
	{$form_forum.topic_name.html}
	</p>
<p>{if $form_forum.topic_subject.required}<span class="required">*</span>{/if}
	{$form_forum.topic_subject.label}: 	{if $form_forum.topic_subject.error}<br /><span class="error">{$form_forum.topic_subject.error}</span>{/if}
	<br />
	{$form_forum.topic_subject.html}
</p>
{if $form_forum.write_everybody}
<p>
	{$form_forum.write_everybody.html}
</p>
{/if}
{if $form_forum.read_everybody}
<p>
	{$form_forum.read_everybody.html}
</p>
{/if}
{if $form_forum.is_sticky}
<p>
	{$form_forum.is_sticky.html}
</p>
{/if}
{if $form_forum.default_blocked}
<p>
	{$form_forum.default_blocked.html}
</p>
{/if}

<p>{$form_forum.requirednote}</p>
<p>{$form_forum.submit.html} {$form_forum.reset.html}</p>
</fieldset>
</form>
