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
	<tr class="form">
		<td></td>
		<td valign="top">
			{if $form_sendnews.file_1.required}<font color="red">*</font>{/if}
			{$form_sendnews.file_1.label}
		</td>
		<td>
			{if $form_sendnews.file_1.error}<font color="red">{$form_sendnews.file_1.error}</font><br />{/if}
			{$form_sendnews.file_1.html}
		</td>
	</tr>
	
	<tr class="form">
		<td></td>
		<td valign="top" colspan="2">
			<div id="files_list"></div>
			<input type="hidden" value="1" name="pic_count" id="pic_count">
			<script>
				var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 5 );
				multi_selector.addElement( document.getElementById( 'pic_select' ) );
			</script>
		</td>
	</tr>
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
