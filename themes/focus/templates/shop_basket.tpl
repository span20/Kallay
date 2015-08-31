<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;">{$locale.index_shop.basket_field_title|upper}</span>
	</div>
	<div id="form_cnt">
		<form {$form_basket.attributes}>
		{$form_basket.hidden}
		<table cellspacing="0" class="table_main">
			<tr style="background-color: #4A4A4A;">
				<th class="table_th">{$locale.index_shop.basket_field_list_name}</th>
				<th class="table_th">{$locale.index_shop.basket_field_list_amount}</th>
				<th class="table_th">{$locale.index_shop.basket_field_list_delete}</th>
				{if $smarty.session.site_shop_stateuse == 1}
					<th class="table_th">{$locale.index_shop.basket_field_list_state}</th>
				{/if}
				<th class="table_th">{$locale.index_shop.basket_field_list_price}</th>
				<th class="table_th" style="width: 350px;">{$locale.index_shop.basket_field_list_sum}</th>
			</tr>
			{foreach from=$basket_list item=data}
				<tr>
					<td class="table_td">
						{$data.pname}
						{if $data.attr}
							<br /><span style="font-weight: normal; font-size: 9px;">{$data.attr}</span>
						{/if}
					</td>
					<td class="table_td"><input value="{$data.amount}" name="amount[{$data.pid}]" type="text" size="5" /></td>
					<td class="table_td"><input name="delete[{$data.pid}]" type="checkbox" value="1" /></td>
					{if $smarty.session.site_shop_stateuse == 1}<td class="table_td">{$data.sname}</td>{/if}
					<td class="table_td">{$data.price}</td>
					<td class="table_td">{$data.amount*$data.price}</td>
				</tr>
			{foreachelse}
				<tr><td colspan="6" align="center" class="table_td">{$locale.index_shop.basket_warning_empty}</td></tr>
			{/foreach}
			{if $basket_list}
				<tr>
					<td colspan="{if $smarty.session.site_shop_stateuse == 1}5{else}4{/if}" class="table_tdsum">{$locale.index_shop.basket_field_list_priceall}</td>
					<td class="table_tdsum">{$price}</td>
				</tr>
				<tr>
					<td colspan="{if $smarty.session.site_shop_stateuse == 1}5{else}4{/if}">{$locale.index_shop.basket_field_list_vat}</td>
					<td>{$afa}</td>
				</tr>
				<tr>
					<td colspan="{if $smarty.session.site_shop_stateuse == 1}5{else}4{/if}">{$locale.index_shop.basket_field_list_total}</td>
					<td>{$sum_price}</td>
				</tr>
			{/if}
		</table><br />
		{if $basket_list}
		<table>
			<tr>
				<td colspan="6" class="table_td">
					<input id="back" type="button" class="submit" value="{$locale.index_shop.basket_button_continue}" onclick="document.location='index.php?p=shop&amp;act=lst';" />
					<input id="refresh" type="submit" class="submit" value="{$locale.index_shop.basket_button_refresh}" />
					<input id="empty" type="button" class="submit" value="{$locale.index_shop.basket_button_empty}" onclick="document.location='index.php?p=shop&amp;act=ebsk';" />
					{if $smarty.session.user_id}
						<input id="next" type="button" class="submit2" value="{$locale.index_shop.basket_button_order}" onclick="document.location='index.php?p=shop&amp;act=addr';" />
					{else}
						<input id="next" type="button" class="submit2" value="{$locale.index_shop.basket_button_order}" onclick="document.location='index.php?p=shop&amp;act=reg';" />
					{/if}
				</td>
			</tr>
		</table>
		{/if}
		<table cellspacing="0" class="table_main">
			<tr>
				<td colspan="6" class="table_comment">
					{$locale.index_shop.basket_field_comment1}<br />
					{$locale.index_shop.basket_field_comment2}<br />
					{$locale.index_shop.basket_field_comment3}<br />
					{$locale.index_shop.basket_field_comment4}<br />
					{$locale.index_shop.basket_field_comment5}
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		</form>
	</div><br />
	<div id="form_bottom"></div>
</div>