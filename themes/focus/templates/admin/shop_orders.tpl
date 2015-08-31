<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<form {$form_orders.attributes}>
		{$form_orders.hidden}
		<div class="t_filter">
			<h3 style="margin:0;">{$locale.admin_shop.orders_title|upper}</h3>
		</div>
		<table>
			{foreach from=$order_data item=data}
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.orders_field_list_id}</td><td>{$data.oid}</td></tr>
				<tr class="{cycle values="row1,row2"}">
					<td class="form">{$locale.admin_shop.orders_field_list_name}</td>
					<td>
						{$data.uname}
						[<a href="mailto:{$data.email}{if $data.mailsubj}?subject={$data.mailsubj|replace:'#oid#':$data.oid}{/if}">{$data.email}</a>]
					</td>
				</tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.orders_field_list_date}</td><td>{$data.odate}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.orders_field_list_phone}</td><td>{$data.mphone}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$form_orders.postselect.label}</td><td>{$form_orders.postselect.html}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$form_orders.shipselect.label}</td><td>{$form_orders.shipselect.html}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$form_orders.shipping.label}</td><td>{$form_orders.shipping.html}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.orders_field_list_comment}</td><td>{$data.ocom}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">&nbsp;</td><td></td></tr>
			{/foreach}
		</table>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>

	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="{$locale.admin_shop.orders_title_products}">{$locale.admin_shop.orders_title_products}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager"></div>
		<table>
			<tr class="{cycle values="row1,row2"}">
				<th class="first">{$locale.admin_shop.orders_field_list_item}</th>
				<th>{$locale.admin_shop.orders_field_list_name2}</th>
				<th>{$locale.admin_shop.orders_field_list_amount}</th>
				<th>{$locale.admin_shop.orders_field_list_price}</th>
				{if $smarty.session.site_shop_stateuse == 1}
					<th>{$locale.admin_shop.orders_field_list_state}</th>
				{/if}
				<th>{$locale.admin_shop.orders_field_list_date2}</th>
				<th>{$locale.admin_shop.orders_field_list_delete}</th>
				<th class="last">{$locale.admin_shop.orders_field_list_finish}</th>
			</tr>
			{foreach from=$products item=data key=key}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.item}</td>
					<td>
						{$data.pname}
						{if $data.attr}
							<br /><span style="font-weight: normal; font-size: 9px;">{$data.attr}</span>
						{/if}
					</td>
					<td><input name="amount[{$data.opid}]" value="{$data.amount}" size="5" maxlength="5" /></td>
					<td>{$data.price}</td>
					{if $smarty.session.site_shop_stateuse == 1}
						<td>
							<select name="state[{$data.opid}]">
							{foreach from=$state_array item=state key=key2}
								<option value="{$key2}" {if $data.sid == $key2}selected="selected"{/if}>{$state}</option>
							{/foreach}
							</select>
						</td>
					{/if}
					<td>{$data.adate}</td>
					<td><input type="checkbox" name="delete[{$data.opid}]" /></td>
					<td class="last"><input type="checkbox" name="finish[{$data.opid}]" /></td>
				</tr>
			{/foreach}
			<tr class="{cycle values="row1,row2"}"><td colspan="7">&nbsp;</td></tr>
			<tr class="{cycle values="row1,row2"}"><td colspan="7" class="first"><b>{$locale.admin_shop.orders_field_list_product_add}</b></td></tr>
			<tr class="{cycle values="row1,row2"}">
				<td colspan="7" class="first">
					<select name="product_add" id="product_add">
					{foreach from=$products_add key=key item=data}
						<option value="{$key}">{$data}</option>
					{/foreach}
					</select>
					<input id="prod_add" type="button" class="submit" value="{$locale.admin_shop.orders_button_add}" onclick="document.location='{$order_add_link}&amp;pid='+document.getElementById('product_add').value;">
				</td>
			</tr>
			<tr class="{cycle values="row1,row2"}"><td colspan="7">&nbsp;</td></tr>
			<tr class="{cycle values="row1,row2"}"><td colspan="7" class="first"><b>{$locale.admin_shop.orders_field_list_unfinished}</b></td></tr>
			{foreach from=$oldproducts item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.item}</td>
					<td>
						{$data.pname}
						{if $data.attr}
							<br /><span style="font-weight: normal; font-size: 9px;">{$data.attr}</span>
						{/if}
					</td>
					<td><input name="amount[{$data.opid}]" value="{$data.amount}" size="5" maxlength="5" /></td>
					<td>{$data.price}</td>
					{if $smarty.session.site_shop_stateuse == 1}
						<td>
							<select name="state[{$data.opid}]">
							{foreach from=$state_array item=state key=key2}
								<option value="{$key2}" {if $data.sid == $key2}selected="selected"{/if}>{$state}</option>
							{/foreach}
							</select>
						</td>
					{/if}
					<td>{$data.adate}</td>
					<td><input type="checkbox" name="delete[{$data.opid}]" /></td>
					<td class="last"><input type="checkbox" name="finish[{$data.opid}]" /></td>
				</tr>
			{foreachelse}
				<tr class="{cycle values="row1,row2"}">
					<td colspan="7" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.admin_shop.orders_warning_not_unfinished}" />
						{$locale.admin_shop.orders_warning_not_unfinished}
					</td>
				</tr>
			{/foreach}
			<tr class="{cycle values="row1,row2"}">
				<td class="form" colspan="7">
					{if not $form_orders.frozen}
						{if $form_orders.requirednote}{$form_orders.requirednote}{/if}
						{$form_orders.submit.html}{$form_orders.reset.html}
					{/if}
				</td>
			</tr>
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="f_bottom"></div>
	</form>
</div>