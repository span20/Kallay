<script type="text/javascript" src="{$libs_dir}/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init(
	{literal}
	{
	{/literal}
		mode             : "exact",
		elements         : "body",
		theme            : "simple",
		language         : "{$smarty.session.site_mce_lang}",
		width            : "100%",
		height			 : "400px",
		convert_urls     : true,
		entity_encoding	 : "raw"
	{literal}
	}
	{/literal}
	);
</script>
<form {$form_sendnews.attributes}>
{$form_sendnews.hidden}
<table cellpadding="2" cellspacing="0">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="{$theme_dir}/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title">{$form_sendnews.header.sendnews|upper}</span></td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.sendtype.required}<font color="red">*</font>{/if}
			{$form_sendnews.sendtype.label}
		</td>
		<td>
			{if $form_sendnews.sendtype.error}<font color="red">{$form_sendnews.sendtype.error}</font><br />{/if}
			{$form_sendnews.sendtype.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.title.required}<font color="red">*</font>{/if}
			{$form_sendnews.title.label}
		</td>
		<td>
			{if $form_sendnews.title.error}<font color="red">{$form_sendnews.title.error}</font><br />{/if}
			{$form_sendnews.title.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.lead.required}<font color="red">*</font>{/if}
			{$form_sendnews.lead.label}
		</td>
		<td>
			{if $form_sendnews.lead.error}<font color="red">{$form_sendnews.lead.error}</font><br />{/if}
			{$form_sendnews.lead.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.lead_len.required}<font color="red">*</font>{/if}
			{$form_sendnews.lead_len.label}
		</td>
		<td>
			{if $form_sendnews.lead_len.error}<font color="red">{$form_sendnews.lead_len.error}</font><br />{/if}
			{$form_sendnews.lead_len.html}
		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.body.required}<font color="red">*</font>{/if}
			{$form_sendnews.body.label}
		</td>
		<td>
			{if $form_sendnews.body.error}<font color="red">{$form_sendnews.body.error}</font><br />{/if}
			{$form_sendnews.body.html}
		</td>
	</tr>
	{if $form_sendnews.category.html}
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.category.required}<font color="red">*</font>{/if}
			{$form_sendnews.category.label}
		</td>
		<td>
			{if $form_sendnews.category.error}<font color="red">{$form_sendnews.category.error}</font><br />{/if}
			{$form_sendnews.category.html}
		</td>
	</tr>
	{/if}
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.fileupl.required}<font color="red">*</font>{/if}
			{$form_sendnews.fileupl.label}
		</td>
		<td>
			{if $form_sendnews.fileupl.error}<font color="red">{$form_sendnews.fileupl.error}</font><br />{/if}
			{$form_sendnews.fileupl.html}
		</td>
	</tr>
	{if $form_sendnews.tags.html}
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.tags.required}<font color="red">*</font>{/if}
			{$form_sendnews.tags.label}
		</td>
		<td>
			{if $form_sendnews.tags.error}<font color="red">{$form_sendnews.tags.error}</font><br />{/if}
			{$form_sendnews.tags.html}
		</td>
	</tr>
	{/if}
	{if $form_sendnews.requirednote and not $form_sendnews.frozen}
		<tr class="form">
			<td></td>
			<td colspan="2">{$form_sendnews.requirednote}</td>
		</tr>
	{/if}
	<tr class="form">
		<td></td>
		<td colspan="2">{$form_sendnews.submit.html} {$form_sendnews.reset.html}</td>
	</tr>
</table>
</form>
