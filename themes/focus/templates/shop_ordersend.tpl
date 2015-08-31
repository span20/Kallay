<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;">{$locale.index_shop.orders_header_send|upper}</span>
	</div>
	<div id="form_cnt">
		<form {$form_ordersend.attributes}>
		{$form_ordersend.hidden}
		<dl>
			<dt>{if $form_ordersend.shipping.required}<span class="required">*</span>{/if}<span class="form_text">{$form_ordersend.shipping.label}</span></dt>
			<dd style="padding-left: 10px;"><span class="form_text">{$form_ordersend.shipping.html}</span>{if $form_ordersend.shipping.error}<span class="error">{$form_ordersend.shipping.error}</span>{/if}</dd>
			<dt>{if $form_ordersend.comment.required}<span class="required">*</span>{/if}<span class="form_text">{$form_ordersend.comment.label}</span></dt>
			<dd><span class="form_text">{$form_ordersend.comment.html}</span>{if $form_ordersend.comment.error}<span class="error">{$form_ordersend.comment.error}</span>{/if}</dd>
			{if $form_ordersend.requirednote and not $form_ordersend.frozen}
				<div style="padding: 3px 0 3px 10px;"><span class="form_text">{$form_ordersend.requirednote}</span></div>
			{/if}
		</dl>
		<table>
			<tr>
				<td class="table_td">
					<input id="backbsk" type="button" class="submit" value="{$locale.index_shop.orders_button_back}" onclick="document.location='index.php?p=shop&act=reg';" />
					<input id="cont" type="button" class="submit" value="{$locale.index_shop.orders_button_continue}" onclick="document.location='index.php?p=shop&amp;act=lst';" />
					<input id="cancel" type="button" class="submit" value="{$locale.index_shop.orders_button_cancel}" onclick="document.location='index.php?p=shop&amp;act=ebsk';" />
					{$form_ordersend.submit.html}
				</td>
			</tr>
		</table>
		<table cellspacing="0" class="table_main">
			<tr>
				<td class="table_comment">
					{$locale.index_shop.orders_field_comment1}<br />
					{$locale.index_shop.orders_field_comment2}<br />
					{$locale.index_shop.orders_field_comment3}<br />
					{$locale.index_shop.orders_field_comment4}<br />
					{$locale.index_shop.orders_field_comment5}<br />
					{$locale.index_shop.orders_field_comment6}
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		</form>
	</div><br />
	<div id="form_bottom"></div>
</div>