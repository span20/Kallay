<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="f_content">
		<div class="t_filter">
			<h3 style="margin:0;">{$locale.admin_shop.finished_title|upper}</h3>
		</div>
		<table>
			{foreach from=$order_data item=data}
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_id}</td><td>{$data.oid}</td></tr>
				<tr class="{cycle values="row1,row2"}">
					<td class="form">{$locale.admin_shop.finished_field_name}</td>
					<td>
						{if $data.uname}{$data.uname}{/if}
						{if $data.nuname}{$data.nuname}{/if}
					</td>
				</tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_date}</td><td>{$data.odate}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_phone}</td><td>{$data.mphone}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_address_postal}</td><td>{$data.paddr}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_address_shipping}</td><td>{$data.saddr}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_paymethod}</td><td>{$data.ship}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">{$locale.admin_shop.finished_field_comment}</td><td>{$data.ocom}</td></tr>
				<tr class="{cycle values="row1,row2"}"><td class="form">&nbsp;</td><td></td></tr>
			{/foreach}
		</table>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>

	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="{$locale.admin_shop.finished_field_products_list}">{$locale.admin_shop.finished_field_products_list}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager"></div>
		<table>
			<tr class="{cycle values="row1,row2"}">
				<th class="first">{$locale.admin_shop.finished_field_item}</th>
				<th>{$locale.admin_shop.finished_field_prodname}</th>
				<th>{$locale.admin_shop.finished_field_amount}</th>
				<th>{$locale.admin_shop.finished_field_price}</th>
				<th>{$locale.admin_shop.finished_field_list_date}</th>
				<th>{$locale.admin_shop.finished_field_status}</th>
				<th class="last">{$locale.admin_shop.finished_field_orderid}</th>
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
					<td>{$data.amount}</td>
					<td>{$data.price}</td>
					<td>{$data.adate}</td>
					<td>{$data.status}</td>
					<td class="last">{$data.oid}</td>
				</tr>
			{/foreach}
			<tr class="{cycle values="row1,row2"}"><td colspan="7">&nbsp;</td></tr>
			<tr class="{cycle values="row1,row2"}"><td colspan="7" class="first"><b>{$locale.admin_shop.finished_field_otherfinished}</b></td></tr>
			{foreach from=$oldproducts item=data key=key}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.item}</td>
					<td>
						{$data.pname}
						{if $data.attr}
							<br /><span style="font-weight: normal; font-size: 9px;">{$data.attr}</span>
						{/if}
					</td>
					<td>{$data.amount}</td>
					<td>{$data.price}</td>
					<td>{$data.adate}</td>
					<td>{$data.status}</td>
					<td class="last">{$data.oid}</td>
				</tr>
			{/foreach}
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="f_bottom"></div>
</div>