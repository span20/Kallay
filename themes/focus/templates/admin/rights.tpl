<!-- igy nem rakja ki a jobb felso sarokba a piros Loading... feliratot, igy lehetne varialni, ha akarnank -->
<div id="HTML_AJAX_LOADING"></div>

<div id="form_cnt">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
				<h3 style="margin:0;">{$lang_title|upper}</h3>
		</div>
		<form {$form.attributes}>
		{$form.hidden}
		<table>
			<tr class="row1">
				<td class="form">{if $form.name.required}<span class="error">*</span>{/if}{$form.name.label}</td>
				<td>{$form.name.html}{if $form.name.error}<span class="error">{$form.name.error}</span>{/if}</td>
			</tr>
			<tr class="row2">
				<td class="form">{if $form.modules.required}<span class="error">*</span>{/if}{$form.modules.label}</td>
				<td>{$form.modules.html}{if $form.modules.error}<span class="error">{$form.modules.error}</span>{/if}</td>
			</tr>
			<tr class="row1">
				<td class="form">{if $form.modulesadm.required}<span class="error">*</span>{/if}{$form.modulesadm.label}</td>
				<td>{$form.modulesadm.html}{if $form.modulesadm.error}<span class="error">{$form.modulesadm.error}</span>{/if}</td>
			</tr>
			<tr class="row2">
				<td class="form">{if $form.contents.required}<span class="error">*</span>{/if}{$form.contents.label}</td>
				<td>{$form.contents.html}{if $form.contents.error}<span class="error">{$form.contents.error}</span>{/if}</td>
			</tr>
			<tr class="row1">
				<td class="form">{if $form.group.required}<span class="error">*</span>{/if}{$form.group.label}</td>
				<td>{$form.group.html}{if $form.group.error}<span class="error">{$form.group.error}</span>{/if}</td>
			</tr>
			<tr class="row2">
				<td class="form">{if $form.functiontext.required}<span class="error">*</span>{/if}{$form.functiontext.label}</td>
				<td id="target">
					{if $form.functiontext.error}<span class="error">{$form.functiontext.error}</span><br />{/if}
					{if is_array($functionchk)}
						{foreach from=$functionchk item=data}
							<input type="checkbox" name="functions[]" value="{$data.fid}" {if $data.rfid != 0}checked{/if}>{$data.falias}<br />
						{/foreach}
					{/if}
				</td>
			</tr>
			<tr class="row1">
				<td class="form" colspan="2">
					{if not $form.frozen}
						{if $form.requirednote}{$form.requirednote}{/if}
						{$form.submit.html}{$form.reset.html}
					{/if}
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>
