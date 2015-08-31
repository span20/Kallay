<div id="form_cnt">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
			<h3 style="margin: 0;">{$lang_title|upper}</h3>
		</div>
		<form {$form.attributes}>
		{$form.hidden}
		<table>
			<tr class="row1">
				<td class="form">{if $form.name.required}<span class="required">*</span>{/if}{$form.name.label}</td>
				<td>{$form.name.html}{if $form.name.error}<span class="error">{$form.name.error}</span>{/if}</td>
			</tr>
			<tr class="row2">
				<td class="form">{if $form.date_start.required}<span class="required">*</span>{/if}{$form.date_start.label}</td>
				<td>{$form.date_start.html}{if $form.date_start.error}<span class="error">{$form.date_start.error}</span>{/if}</td>
			</tr>
			<tr class="row1">
				<td class="form">{if $form.date_end.required}<span class="required">*</span>{/if}{$form.date_end.label}</td>
				<td>{$form.date_end.html}{if $form.date_end.error}<span class="error">{$form.date_end.error}</span>{/if}</td>
			</tr>
			<tr class="row2">
				<td class="form">{if $form.products.required}<span class="required">*</span>{/if}{$form.products.label}</td>
				<td>{$form.products.html}{if $form.products.error}<span class="error">{$form.products.error}</span>{/if}</td>
			</tr>
			<tr class="row1">
				<td class="form">{if $form.actionradio.required}<span class="required">*</span>{/if}{$form.actionradio.label}</td>
				<td>{$form.actionradio.html}{if $form.actionradio.error}<span class="error">{$form.actionradio.error}</span>{/if}</td>
			</tr>
			<tr class="row2" id="percent" style="display: none;">
				<td class="form">{if $form.percent.required}<span class="required">*</span>{/if}{$form.percent.label}</td>
				<td>{$form.percent.html}{if $form.percent.error}<span class="error">{$form.percent.error}</span>{/if}</td>
			</tr>
			<tr class="row2" id="fix" style="display: none;">
				<td class="form">{if $form.fix.required}<span class="required">*</span>{/if}{$form.fix.label}</td>
				<td>
					{if $form.fix.error}<span class="error">{$form.fix.error}</span>{/if}
					<div id="mySpan"></div>
				</td>
			</tr>
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
