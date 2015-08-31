<div>{$lang.strAdminHeader}</div>

<form{$form.attributes}>
{$form.hidden}
<table cellpadding="2" cellspacing="0" class="szurke">
{foreach item=sec key=i from=$form.sections}
	{foreach item=element from=$sec.elements}
		{if $element.type eq "submit" or $element.type eq "reset"}
			{if not $form.frozen}
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

{if $form.requirednote and not $form.frozen}
	<tr><td colspan="2">{$form.requirednote}</td></tr>
{/if}
</table>
</form>
