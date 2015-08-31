<script language="JavaScript" type="text/javascript" src="{$include_dir}/javascript.polls.js"></script>
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
					{if $form.regpoll.required}<font color="red">*</font>{/if}
					{$form.regpoll.label}
				</td>
				<td>
					{if $form.regpoll.error}<font color="red">{$form.regpoll.error}</font><br />{/if}
					{$form.regpoll.html}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.question.required}<font color="red">*</font>{/if}
					{$form.question.label}
				</td>
				<td>
					{if $form.question.error}<font color="red">{$form.question.error}</font><br />{/if}
					{$form.question.html}
				</td>
			</tr>
			{if $ismenu == 1}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.menulist.required}<font color="red">*</font>{/if}
					{$form.menulist.label}
				</td>
				<td>
					{if $form.menulist.error}<font color="red">{$form.menulist.error}</font><br />{/if}
					{$form.menulist.html}
				</td>
			</tr>
			{/if}
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.date_start.required}<font color="red">*</font>{/if}
					{$form.date_start.label}
				</td>
				<td>
					{if $form.date_start.error}<font color="red">{$form.date_start.error}</font><br />{/if}
					{$form.date_start.html}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.date_end.required}<font color="red">*</font>{/if}
					{$form.date_end.label}
				</td>
				<td>
					{if $form.date_end.error}<font color="red">{$form.date_end.error}</font><br />{/if}
					{$form.date_end.html}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.link.required}<font color="red">*</font>{/if}
					{$form.link.label}
				</td>
				<td>
					{if $form.link.error}<font color="red">{$form.link.error}</font><br />{/if}
					{$form.link.html}
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}">
				<td class="form">
					{if $form.answer.required}<font color="red">*</font>{/if}
					{$form.answer.label}
				</td>
				<td>
					{if $form.answer.error}<font color="red">{$form.answer.error}</font><br />{/if}
					<input value="0" id="theValue" type="hidden">
					<input type="text" name="answer[]" value="{$answer.0.answer}">
					<span id="myDiv">
						{foreach from=$answer item=data key=key}
							{if $key != 0}
								<span id="my{$key}Div"><br><input name="answer[]" type="text" value="{$data.answer}"> <a href="#" onclick="removeEvent('my{$key}Div')">válasz törlése</a></span>
							{/if}
						{/foreach}
					</span>
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
