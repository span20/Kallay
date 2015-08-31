<div id="form_cnt">
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="{$lang_title}">{$lang_title}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="f_content">
		<div class="f_empty"></div>
		<form {$form.attributes} onSubmit="return SelectAll(this);">
		{$form.hidden}
		<table>
			<tr class="row1">
				<td class="form">{if $form.name.required}<span class="error">*</span>{/if}{$form.name.label}</td>
				<td colspan="2">{$form.name.html}{if $form.name.error}<span class="error">{$form.name.error}</span>{/if}</td>
			</tr>
			<tr class="row2">
				<td class="form" style="width: 33%;">
					{if $form.srcList.required}<span class="error">*</span>{/if}{$form.srcList.label}<br />
					<input type="text" name="SearchInput" onKeyUp="JavaScript: searchSelectBox('frm_groups', 'SearchInput', 'srcList')"><br /><br />
					<span style="font-weight: normal;">{$locale.admin_groups.field_list_allusers}</span><br />
                    {$form.srcList.html}{if $form.srcList.error}<span class="error">{$form.srcList.error}</span>{/if}
				</td>
				<td valign="top" style="padding-top: 70px; width: 33%;">
					<input type="button" value=" >> " onClick="javascript:addSrcToDestList(0)"><br /><br />
					<input type="button" value=" << " onclick="javascript:deleteFromDestList(0);">
				</td>
				<td valign="top" style="padding-top: 65px; width: 33%;">
                    <span style="font-weight: none;">{$locale.admin_groups.field_list_groupusers}</span><br />
					<select size="10" name="destList0[]" id="destList0" multiple="multiple">
					{foreach from=$destList item=data key=key}
						<option value="{$key}">{$data}</option>
					{/foreach}
					</select>
				</td>
			</tr>
            {if $form.deleted.html}
			<tr class="row1">
				<td class="form">{if $form.deleted.required}<span class="error">*</span>{/if}{$form.deleted.label}</td>
				<td colspan="2">{$form.deleted.html}{if $form.deleted.error}<span class="error">{$form.deleted.error}</span>{/if}</td>
			</tr>
            {else}
            <tr class="row1">
                <td>&nbsp;</td>
            </tr>
            {/if}
			<tr class="row2">
				<td class="form" colspan="3">
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
