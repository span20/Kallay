<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;">{$form_basket.header.address|upper}</span>
	</div>
	<div id="form_cnt">
		<script type="text/javascript">{$address_list}</script>
		<form {$form_basket.attributes}>
		{$form_basket.hidden}
		<dl>
			{foreach from=$userdata item=data key=key}
				<dt><span class="form_text">{$locale.index_shop.address_field_list_name}</span></dt><dd><span class="form_text">{$key}</span></dd>
				<dt><span class="form_text">{$locale.index_shop.address_field_list_username}</span></dt><dd><span class="form_text">{$data.user_name}</span></dd>
				<dt><span class="form_text">{$locale.index_shop.address_field_list_email}</span></dt><dd><span class="form_text">{$data.email}</span></dd>
			{/foreach}
			<dt>{if $form_basket.mobilephone.required}<span class="required">*</span>{/if}<span class="form_text">{$form_basket.mobilephone.label}</span></dt>
			<dd>{$form_basket.mobilephone.html}{if $form_basket.mobilephone.error}<span class="error">{$form_basket.mobilephone.error}</span>{/if}</dd>
			<dt>{if $form_basket.shipselect.required}<span class="required">*</span>{/if}<span class="form_text">{$form_basket.shipselect.label}</span></dt>
			<dd>
				{$form_basket.shipselect.html}
				<input id="modify" type="button" class="submit" value="{$locale.index_shop.address_button_modify}" onclick="modAddress(address_list[document.getElementById('shipselect').value]['zip'], address_list[document.getElementById('shipselect').value]['city'], address_list[document.getElementById('shipselect').value]['cid'], address_list[document.getElementById('shipselect').value]['address'], address_list[document.getElementById('shipselect').value]['aid']);" />
				<input id="delete" type="button" class="submit" value="{$locale.index_shop.address_button_delete}" onclick="javascript: if (confirm('{$locale.index_shop.address_confirm_del}')) document.location='index.php?p=shop&amp;act=del&amp;aid='+address_list[document.getElementById('shipselect').value]['aid'];" />
				{if $form_basket.shipselect.error}<span class="error">{$form_basket.shipselect.error}</span>{/if}
			</dd>
			<dt>{if $form_basket.postselect.required}<span class="required">*</span>{/if}<span class="form_text">{$form_basket.postselect.label}</span></dt>
			<dd>
				{$form_basket.postselect.html}
				<input id="modify" type="button" class="submit" value="{$locale.index_shop.address_button_modify}" onclick="modAddress(address_list[document.getElementById('postselect').value]['zip'], address_list[document.getElementById('postselect').value]['city'], address_list[document.getElementById('postselect').value]['cid'], address_list[document.getElementById('postselect').value]['address'], address_list[document.getElementById('postselect').value]['aid']);" />
				<input id="delete" type="button" class="submit" value="{$locale.index_shop.address_button_delete}" onclick="javascript: if (confirm('{$locale.index_shop.address_confirm_del}')) document.location='index.php?p=shop&amp;act=del&amp;aid='+address_list[document.getElementById('postselect').value]['aid'];" />
				{if $form_basket.postselect.error}<span class="error">{$form_basket.postselect.error}</span>{/if}
			</dd>
		</dl>
		</form>
		<form {$form_address.attributes}>
		{$form_address.hidden}
		<div style="clear: both;">
			<dt>{if $form_address.new_address.required}<span class="required">*</span>{/if}<span class="form_text">{$form_address.new_address.label}</span></dt>
				<dd>{$form_address.new_address.html}{if $form_address.new_address.error}<span class="error">{$form_address.new_address.error}</span>{/if}</dd>
		</div>
		<div>
			<dl id="addaddress" style="display:{$none_block};">
				<input type="hidden" id="aid" name="aid" value="" />
				<dt>{if $form_address.shipzip.required}<span class="required">*</span>{/if}<span class="form_text">{$form_address.shipzip.label}</span></dt>
				<dd>{$form_address.shipzip.html}{if $form_address.shipzip.error}<span class="error">{$form_address.shipzip.error}</span>{/if}</dd>
				<dt>{if $form_address.shipcity.required}<span class="required">*</span>{/if}<span class="form_text">{$form_address.shipcity.label}</span></dt>
				<dd>{$form_address.shipcity.html}{if $form_address.shipcity.error}<span class="error">{$form_address.shipcity.error}</span>{/if}</dd>
				<dt>{if $form_address.country.required}<span class="required">*</span>{/if}<span class="form_text">{$form_address.country.label}</span></dt>
				<dd>{$form_address.country.html}{if $form_address.country.error}<span class="error">{$form_address.country.error}</span>{/if}</dd>
				<dt>{if $form_address.shipaddr.required}<span class="required">*</span>{/if}<span class="form_text">{$form_address.shipaddr.label}</span></dt>
				<dd>{$form_address.shipaddr.html}{if $form_address.shipaddr.error}<span class="error">{$form_address.shipaddr.error}</span>{/if}</dd>
				{if $form_address.requirednote and not $form_address.frozen}
					<div style="padding: 3px 0 3px 10px;"><span class="form_text">{$form_address.requirednote}</span></div>
				{/if}
				<div style="padding: 3px 0 3px 10px;">{$form_address.submit.html} {$form_address.reset.html}</div>
			</dl>
		</div>
		</form>
		<table>
			<tr>
				<td class="table_td">
					<input id="backbsk" type="button" class="submit" value="{$locale.index_shop.address_button_back}" onclick="document.location='index.php?p=shop&amp;act=bsk';" />
					<input id="cont" type="button" class="submit" value="{$locale.index_shop.address_button_continue}" onclick="document.location='index.php?p=shop&amp;act=lst';" />
					<input id="cancel" type="button" class="submit" value="{$locale.index_shop.address_button_cancel}" onclick="document.location='index.php?p=shop&amp;act=ebsk';" />
					<input id="next" type="submit" class="submit2" value="{$locale.index_shop.address_button_next}" onclick="document.frm_addr.submit();"/>
				</td>
			</tr>
		</table>
		<table cellspacing="0" class="table_main">
			<tr>
				<td class="table_comment">
					{$locale.index_shop.address_field_comment1}<br />
					{$locale.index_shop.address_field_comment2}<br />
					{$locale.index_shop.address_field_comment3}<br />
					{$locale.index_shop.address_field_comment4}<br />
					{$locale.index_shop.address_field_comment5}<br />
					{$locale.index_shop.address_field_comment6}<br />
					{$locale.index_shop.address_field_comment7}
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
	</div>
	<div id="form_bottom"></div>
</div>