<div id="form" style="margin-top: 10px;">
	<form {$form_search_details.attributes}>
	{$form_search_details.hidden}
	<div id="form_top" style="padding-left: 10px;">{$form_search_details.header.search|upper}</div>
	<div id="form_cnt">
		<dl>
			<dt style="padding-left: 10px;">{if $form_search_details.searchtext.required}<span class="required">*</span>{/if}<span class="form_text">{$form_search_details.searchtext.label}</dt>
			<dd>{$form_search_details.searchtext.html}{if $form_search_details.searchtext.error}<span class="error">{$form_search_details.searchtext.error}</span>{/if}</dd>
			<dt style="padding-left: 10px;">{if $form_search_details.searchtype.required}<span class="required">*</span>{/if}<span class="form_text">{$form_search_details.searchtype.label}</dt>
			<dd>{$form_search_details.searchtype.html}{if $form_search_details.searchtype.error}<span class="error">{$form_search_details.searchtype.error}</span>{/if}</dd>
		</dl>
		<div style="padding: 3px 0 3px 10px;">{$form_search_details.submit.html}</div>
	</div>
	<div id="form_bottom"></div>
	</form>
</div>
