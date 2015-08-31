{if $newprodsnum != 0}
<div id="block" style="margin-top: 10px;">
	<div id="b_top">{$locale.index_shop.block_newprods_header|upper}</div>
	<div id="b_content">
		{foreach from=$newprods item=newprod key=newprodkey name=products}
			<div style="margin-right: 30px; {if !$smarty.foreach.products.last}border-bottom: 1px solid;{/if}">
				<p>
					<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;pid={$newprodkey}" title="{$newprod.pname}">{$newprod.pname}</a>
				</p>
				{if $newprod.pic != ""}
					<p style="text-align: center">
						<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;pid={$newprodkey}" title="{$newprod.pname}">
							<img src="{$smarty.session.site_shop_prodpicdir}/tn_{$newprod.pic}" alt="{$newprod.pname}" />
						</a>
					</p>
				{/if}
				<p>
					<span class="shop_desc">{$locale.index_shop.block_newprods_price}</span> <span class="shop_extra">{$newprod.netto}</span>
					+ {$newprod.afa}% {$locale.index_shop.block_newprods_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$newprod.netto y=$newprod.afa}</b></span>
				</p>
			</div>
		{/foreach}
	</div>
	<div id="b_bottom"></div>
</div>
{/if}