<form {$form_forum.attributes}>
	{$form_forum.hidden}
	<fieldset>
		<legend>{$form_forum.header.headername}</legend>
		<p>	{if $form_forum.subject.required}<span class="required">*</span>{/if}
					{$form_forum.subject.label}: {if $form_forum.subject.error}<br /><span class="error">{$form_forum.subject.error}</span>{/if}
			<br />
			{$form_forum.subject.html}
		</p>
		<p> {if $form_forum.message.required}<span class="required">*</span>{/if}
			{$form_forum.message.label}: {if $form_forum.message.error}<br /><span class="error">{$form_forum.message.error}</span>{/if}
			<br />
			{$form_forum.message.html}
		</p>
		<div id="forum_help"><noscript><p>{$lang_forum.strForumNoJavascript}</p></noscript></div>
		<p>
		{$form_forum.embed.label}:<br />
		{if $form_forum.embed.error}<span class="error">{$form_forum.embed.error}</span><br />{/if}
		{$form_forum.embed.html}
		</p>
		{if $forum_pics}
		    <p>
		    {$form_forum.msgpic0.label}: {if $form_forum.msgpic0.error}<br /><span class="error">{$form_forum.msgpic0.error}</span>{/if}
		    <br />
		    {$form_forum.msgpic0.html}
		    <br />
		    {$form_forum.msgpic1.label}: {if $form_forum.msgpic0.error}<br /><span class="error">{$form_forum.msgpic1.error}</span>{/if}
		    <br />
		    {$form_forum.msgpic1.html}
		    <br />
		    {$form_forum.msgpic2.label}: {if $form_forum.msgpic2.error}<br /><span class="error">{$form_forum.msgpic2.error}</span>{/if}
		    <br />
		    {$form_forum.msgpic2.html}
		    </p>
		{/if}
		{if $forum_captcha}
			<p>
			<img src="{$forum_captcha}" id="captcha" alt="captcha"><br />
			{if $form_forum.forum_recaptcha.required}<span class="required">*</span>{/if}
					{$form_forum.forum_recaptcha.label}: {if $form_forum.forum_recaptcha.error}<br /><span class="error">{$form_forum.forum_recaptcha.error}</span>{/if}
			<br />
			{$form_forum.forum_recaptcha.html}
			</p>
		{/if}
		<p>{$form_forum.requirednote}</p>
		<p>{$form_forum.submit.html} {$form_forum.reset.html}</p>
	</fieldset>
</form>
<p class="centered">
<a class="back" href="index.php?{$self}&amp;tid={$tid}">{$lang_forum.strForumBack}</a>
</p>
