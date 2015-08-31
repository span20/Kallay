<form {$form_builder.attributes}>
	{$form_builder.hidden}
	<fieldset>
		<legend>{$form_title}</legend>
		<p>{$form_lead}</p>
		<table cellpadding="2" cellspacing="0">
		{foreach item=sec key=i from=$form_builder.sections}
			{foreach item=element from=$sec.elements}
				{if $element.type eq "submit" or $element.type eq "reset"}
					{if not $form_builder.frozen}
						<tr>
							<td colspan="2">{$element.html}</td>
						</tr>
					{/if}
				{else}
					<tr>
						{if $element.type eq "textarea"}
							<td colspan="2">
								{if $element.required}<font color="red">*</font>{/if}{$element.label}<br />
						{else}
							<td align="right" valign="top" width="50%">
								{if $element.required}<font color="red">*</font>{/if}{$element.label}</td>
							<td>
						{/if}
							{if $element.error}<font color="red">{$element.error}</font><br />{/if}
							{if $element.type eq "group"}
								{foreach key=gkey item=gitem from=$element.elements}
									{$gitem.label}
									{$gitem.html}{if $gitem.required}<font color="red">*</font>{/if}
									{if $element.separator}{cycle values=$element.separator}{/if}
								{/foreach}
							
							{else}
								{$element.html}
							{/if}
						</td>
					</tr>
				{/if}
			{/foreach}
		{/foreach}
		{if $form_builder.requirednote and not $form_builder.frozen}
			<tr><td colspan="2">{$form_builder.requirednote}</td></tr>
		{/if}
		</table>
	</fieldset>
</form>
<p class="centered">
<a class="back" href="index.php?{$self}&amp;tid={$tid}">{$lang_forum.strForumBack}</a>
</p>
