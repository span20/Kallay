<!-- igy nem rakja ki a jobb felso sarokba a piros Loading... feliratot, igy lehetne varialni, ha akarnank -->
<div id="HTML_AJAX_LOADING"></div>

<div id="shop">
	<div id="shop_top">{$cat_name|upper}</div>
	<div id="shop_cnt">
		{foreach name=cat from=$category item=data}
		<div class="shop_cat" {if $smarty.foreach.cat.index is div by 4}style="clear: both;"{/if}>
			<div class="shop_catpic">
				{if $data.cpic != ""}
					<a href="index.php?p=shop&amp;act=lst&amp;cid={$data.cid}" title="{$data.cname}">
						<img src="{$smarty.session.site_shop_mainpicdir}/tn_{$data.cpic}" border="0" alt="{$data.cname}">
					</a>
				{/if}
			</div>
			<div class="shop_title"><a href="index.php?p=shop&amp;act=lst&amp;cid={$data.cid}" title="{$data.cname}">{$data.cname}</a></div>
		</div>
		{/foreach}
		{if $page_data}
		<div style="clear: both;">
			<div class="pager">{$page_list}</div>
			<form action="index.php?p=shop" method="post">
			<input type="hidden" name="act" value="bsk">
			{foreach name=prod from=$page_data item=data}
			<div class="shop_prod" {if $smarty.foreach.prod.iteration % 2}style="clear: both;"{/if}>
				<div class="shop_pic">
					<a id="prd_{$data.pid}" name="prd_{$data.pid}"></a>
					{if $data.pictures}
						{foreach from=$data.pictures item=pic}
							<a href="index.php?p=shop&amp;act=prd&amp;cid={$cid}&amp;pid={$data.pid}" onmouseover="this.T_BGCOLOR='#f0f0f0'; this.T_TITLE='{$data.pname|htmlspecialchars}'; this.T_BORDERCOLOR='#c72926'; return escape('<img src={$smarty.session.site_shop_prodpicdir}/{$pic}>')" title="{$data.pname}">
								<img src="{$smarty.session.site_shop_prodpicdir}/tn_{$pic}" border="0" alt="{$data.pname}" />
							</a>
						{/foreach}
                        <script type="text/javascript" src="includes/wz_tooltip.js"></script>
					{/if}
				</div>
				<div class="shop_data">
					<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;cid={$cid}&amp;pid={$data.pid}" title="{$data.pname}">{$data.pname}</a><br />
					<p class="desc">
						{if $data.pdesc|count_characters > 100}
							{$data.pdesc|truncate:100:"..."|strip_tags}
						{else}
							{$data.pdesc}
						{/if}
					</p>
					<a class="shop_mtitle" href="index.php?p=shop&amp;act=prd&amp;cid={$cid}&amp;pid={$data.pid}" title="{$locale.index_shop.main_field_more}">{$locale.index_shop.main_field_more}</a><br />
					<p><span class="shop_desc">{$locale.index_shop.main_field_item}:</span> {$data.item}</p>
					{if $smarty.session.site_shop_userbuy == 1}
						<p>
							{if $smarty.session.site_shop_actionuse}
								{if $data.actionprice != 0.00 && $data.actionprice != NULL && ($data.actiontstart == '0000-00-00 00:00:00' || $data.actiontstart == NULL)}
									{assign var=price value=`$data.actionprice`}
									{assign var=is_action value=1}
								{elseif $data.actionpercent != 0 && $data.actionpercent != NULL && ($data.actiontstart == '0000-00-00 00:00:00' || $data.actiontstart == NULL)}
									{assign var=price value=`$data.netto-$data.netto/100*$data.actionpercent`}
									{assign var=is_action value=1}
								{else}
									{assign var=price value=`$data.netto`}
									{assign var=is_action value=0}
								{/if}
							{/if}
							{if $is_action == 1}
								<strike>
								<span class="shop_desc">{$locale.index_shop.main_field_price}:</span> <span class="shop_extra">{$data.netto}</span>
								+ {$data.afa}% {$locale.index_shop.main_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$data.netto y=$data.afa}</b></span>
								</strike><br />
								<span class="shop_desc">{$locale.index_shop.main_field_actionprice}:</span> <span class="shop_extra">{$price}</span>
								+ {$data.afa}% {$locale.index_shop.main_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$price y=$data.afa}</b></span>
							{else}
								<span class="shop_desc">{$locale.index_shop.main_field_price}:</span> <span class="shop_extra">{$data.netto}</span>
								+ {$data.afa}% {$locale.index_shop.main_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$data.netto y=$data.afa}</b></span>
							{/if}
						</p>
					{/if}

					{* PLUSZ MEZOK *}
					{foreach from=$tplfields item=plus}
						{assign var="field" value=$plus.value}
						<p><span class="shop_desc">{$plus.display}:</span> <span class="shop_extra">{$data.$field}</span></p>
					{/foreach}
					{* PLUSZ MEZOK VEGE *}

					{* VASARLAS *}
					{if $smarty.session.site_shop_userbuy == 1}
						{if not $data.amount}
						<div id="target_{$data.pid}">
							<input type="text" id="amount_{$data.pid}" name="amount[{$data.pid}]" size="2" />
							<input type="button" class="submit" value="{$locale.index_shop.main_button_basket}" onclick="bsksend('{$data.pid}', '{$data.pname}', '{$price}')" />
						</div>
						{else}
							<p class="amount">{$locale.index_shop.main_field_amount1} <span class='shop_extra'>{$data.amount}</span> {$locale.index_shop.main_field_amount2}</p>
						{/if}
					{/if}
					{* VASARLAS VEGE *}

					{* ERTEKELES *}
					{if $smarty.session.site_shop_is_rating == 1}
						<br />
						<p>
							<span class="shop_desc">{$locale.index_shop.main_field_avgrating}</span> 
							<b> 
							{if $data.avg_rating}
								{section name=star start=0 step=1 loop=$data.avg_rating}
									<img src="{$theme_dir}/images/shop_star.gif" alt="{$data.avg_rating}" border="0" />
								{sectionelse}
									{$data.avg_rating}
								{/section}
							{else}
								{$locale.index_shop.main_field_notrating}
							{/if}
							</b>
							<span class="shop_desc">, {$locale.index_shop.main_field_ratingcount}</span> <b>{$data.cnt_rating}</b>
						</p>
					{/if}
					{* ERTEKELES VEGE *}

				</div>
			</div>
			{/foreach}
			</form>
			<div class="pager">{$page_list}</div>
		</div>
		{else}
			<div style="clear: both; text-align: center;">{$locale.index_shop.main_warning_empty}</div>
		{/if}
	</div>
	<div id="shop_bottom"></div>
</div>