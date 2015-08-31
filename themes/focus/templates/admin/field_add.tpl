<div id="form_cnt">
	<div id="ear">
		<ul>
			<li id="current"><a href="#">{$lang_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="f_content">
		<div class="f_empty"></div>

		<form {$form.attributes}>
		{$form.hidden}
		<table>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.name.required}<font color="red">*</font>{/if}
					{$form.name.label}
				</td>
				<td>
					{if $form.name.error}<font color="red">{$form.name.error}</font><br />{/if}
					{$form.name.html}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.type.required}<font color="red">*</font>{/if}
					{$form.type.label}
				</td>
				<td>
					{if $form.type.error}<font color="red">{$form.type.error}</font><br />{/if}
					{$form.type.html}
					<input type="hidden" name="fields_num" id="fields_num" value="0">
					<div id="new_answer" style="display: none;">
						<a href="javascript:void(0);" onclick="create_fields();">{$locale.$self_2.form_new_answer}</a>
					</div>
					<div id="answer_fields">
						
					</div>
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.check.required}<font color="red">*</font>{/if}
					{$form.check.label}
				</td>
				<td>
					{if $form.check.error}<font color="red">{$form.check.error}</font><br />{/if}
					{$form.check.html}
				</td>
			</tr>

			<tr class="row2"><td colspan="2" class="form">
			{if not $form.frozen}
				{$form.requirednote}
				{$form.submit.html}
				{$form.reset.html}
			{/if}
			</td></tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>
