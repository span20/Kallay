<div id="basket">
	<div id="basket_top">{$locale.index_shop.block_basket_header|upper}</div>
	<div id="basket_content">
		<div>
			{foreach name=test from=$basket item=data}
                <b>{$smarty.foreach.test.iteration}.</b> {$data.pname}<br />
                {$locale.index_shop.block_basket_amount} {$data.amount}db<br />
                {$locale.index_shop.block_basket_price} {$data.price}<br />
                {$locale.index_shop.block_basket_netto} {$data.sum}<br />
            {foreachelse}
                {$locale.index_shop.block_basket_warning_empty}
            {/foreach}
		</div>
		<div id="bsktarget"></div>
		<div id="osszar"><br />{$allsum}</div>
        <div style="margin-bottom: 5px;"><a href="index.php?p=shop&amp;act=bsk" title="{$locale.index_shop.block_basket_modify}">{$locale.index_shop.block_basket_modify}</a></div>
	</div>
	<div id="basket_bottom"></div>
</div>