<!-- igy nem rakja ki a jobb felso sarokba a piros Loading... feliratot, igy lehetne varialni, ha akarnank -->
<div id="HTML_AJAX_LOADING"></div>

<div id="shop">
	<div id="shop_top" style="padding-left: 10px;">{$locale.index_shop.main_details_field_title|upper}</div>
	<div class="shop_cnt">
	{foreach from=$prod_data item=data key=key}
		<div class="shop_pic">
			{if $pictures != ""}
				{foreach from=$pictures item=data2}
					<img src="{$smarty.session.site_shop_prodpicdir}/{$data2}" alt="{$data2}" />
				{/foreach}
			{/if}
		</div>
		<div class="shop_datadetails">
			<p class="shop_detailstitle">{$data.pname}</p>
			<p><span class="shop_desc">{$locale.index_shop.main_details_field_item}:</span> {$data.item}</p>
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
						<span class="shop_desc">{$locale.index_shop.main_details_field_price}:</span> <span class="shop_extra">{$data.netto}</span>
						+ {$data.afa}% {$locale.index_shop.main_details_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$data.netto y=$data.afa}</b></span>
						</strike><br />
						<span class="shop_desc">{$locale.index_shop.main_details_field_actionprice}:</span> <span class="shop_extra">{$price}</span>
						+ {$data.afa}% {$locale.index_shop.main_details_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$price y=$data.afa}</b></span>
					{else}
						<span class="shop_desc">{$locale.index_shop.main_details_field_price}:</span> <span class="shop_extra">{$data.netto}</span>
						+ {$data.afa}% {$locale.index_shop.main_details_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$data.netto y=$data.afa}</b></span>
					{/if}
				</p>
			{/if}
			{if $smarty.session.site_shop_stateuse == 1}
				<p>
					{$locale.index_shop.main_details_field_state} {$data.state}
				</p>
			{/if}
			<p class="shop_detailsdesc">{$data.pdesc}</p>

			{foreach from=$tplfields item=plus}
				{assign var="field" value=$plus.value}
				<p><span class="shop_desc">{$plus.display}:</span> <span class="shop_extra">{$data.$field}</span></p>
			{/foreach}

			{if $smarty.session.site_shop_is_extra_attr == 1 && $attributes}
				{foreach from=$attributes item=attr}
				<div style="padding-top: 3px;">
					<span class="shop_desc">{$attr.title}:</span>
					<select name="{$attr.title}" id="attr_select_{$smarty.foreach.attr.iteration}">
					{foreach from=$attr.values item=value}
						<option value="{$value}">{$value}</option>
					{/foreach}
					</select>
				</div>
				{/foreach}
			{/if}

			{if $smarty.session.site_shop_userbuy == 1}
				{if $amount}
					<p class="amount">{$locale.index_shop.main_details_field_amount1} <span class='shop_extra'>{$amount}</span> {$locale.index_shop.main_details_field_amount2}</p>
				{else}
					<div id="target_{$key}">
						<input type="text" id="amount_{$key}" name="amount[{$key}]" size="2" />
						<input type="button" class="submit" value="{$locale.index_shop.main_details_button}" onclick="bsksend('{$key}', '{$data.pname}', '{$data.netto}', '{$smarty.foreach.attr.total}')" />
					</div>
				{/if}
			{/if}
			<br />
			<a href="index.php?p=shop&amp;act=lst&amp;cid={$cid}#prd_{$key}" title="{$locale.index_shop.main_details_link_backcat}">{$locale.index_shop.main_details_link_backcat}</a>
                    {if $smarty.session.site_shop_userbuy == 1}
			    <a href="index.php?p=shop&amp;act=bsk" title="{$locale.index_shop.main_details_link_editbasket}">{$locale.index_shop.main_details_link_editbasket}</a>
                    {/if}

            {* LETOLTHETO DOKUMENTUMOK *}
			{if $documents}
            <div>
				{foreach from=$documents item=data}
					<a href="index.php?p=shop&amp;act=dwn&amp;did={$data.did}" title="{$data.document}">{$data.document}</a>
				{/foreach}
			{/if}
            </div>
            {* LETOLTHETO DOKUMENTUMOK VEGE *}

		</div>
	{/foreach}
	</div>

	{* KAPCSOLODO TERMEKEK *}
	{if $smarty.session.site_shop_joinprod && $joinprods}
	<div id="shop_top" style="padding-left: 10px;">{$locale.index_shop.main_details_field_joinprod|upper}</div>
	<div class="shop_cnt">
		{foreach from=$joinprods item=data3 key=key3}
		<div style="padding-left: 10px; float: left; width: 30%; {if $smarty.foreach.cat.index is div by 3}clear: both;{/if}">
			<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;pid={$key3}" title="{$data3.jpname}">{$data3.jpname}</a><br />
			{if $data3.jpic != ""}
				<a href="index.php?p=shop&amp;act=prd&amp;pid={$key3}" title="{$data3.jpname}" style="text-decoration: none;">
				<img src="{$smarty.session.site_shop_prodpicdir}/tn_{$data3.jpic}" alt="{$data3.jpname}" border="0" />
				</a><br />
			{/if}
			{if $smarty.session.site_shop_userbuy == 1}
				<p>
					{if $smarty.session.site_shop_actionuse}
						{if $data3.actionprice != 0.00 && $data3.actionprice != NULL && ($data3.actiontstart == '0000-00-00 00:00:00' || $data3.actiontstart == NULL)}
							{assign var=price value=`$data3.actionprice`}
							{assign var=is_action value=1}
						{elseif $data3.actionpercent != 0 && $data3.actionpercent != NULL && ($data3.actiontstart == '0000-00-00 00:00:00' && $data3.actiontstart == NULL)}
							{assign var=price value=`$data3.netto-$data3.netto/100*$data3.actionpercent`}
							{assign var=is_action value=1}
						{else}
							{assign var=price value=`$data3.netto`}
							{assign var=is_action value=0}
						{/if}
					{/if}
					{if $is_action == 1}
						<strike>
						<span class="shop_desc">{$locale.index_shop.main_details_field_price}:</span> <span class="shop_extra">{$data3.netto}</span>
						+ {$data3.afa}% {$locale.index_shop.main_details_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$data3.netto y=$data3.afa}</b></span>
						</strike><br />
						<span class="shop_desc">{$locale.index_shop.main_details_field_actionprice}:</span> <span class="shop_extra">{$price}</span>
						+ {$data3.afa}% {$locale.index_shop.main_details_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$price y=$data3.afa}</b></span>
					{else}
						<span class="shop_desc">{$locale.index_shop.main_details_field_price}:</span> <span class="shop_extra">{$data3.netto}</span>
						+ {$data3.afa}% {$locale.index_shop.main_details_field_vat} = <span class="shop_extra"><b>{math equation="x + (x/100*y)" x=$data3.netto y=$data3.afa}</b></span>
					{/if}
				</p>
			{/if}
		</div
		{/foreach}
	</div>
	{/if}
	{* KAPCSOLODO TERMEKEK VEGE *}

	{* ERTEKELES *}
	{if $smarty.session.site_shop_is_rating}
	<div id="shop_top" style="padding-left: 10px;">{$locale.index_shop.main_details_field_rating|upper}</div>
	<div class="shop_cnt">
		<div style="padding-left: 10px; padding-right: 10px;">
			{foreach from=$shop_ratings item=rating}
				<span><b>{$rating.user_name} - {$rating.add_date}</b></span><br />
				<span class="shop_desc"><b>{$locale.index_shop.main_details_field_rate}</b></span>
				<span>
					{section name=star start=0 step=1 loop=$rating.rating}
						<img src="{$theme_dir}/images/shop_star.gif" alt="{$rating.rating}" border="0" />
					{sectionelse}
						{$rating.rating}
					{/section}
				</span><br />
				<span class="shop_desc">{$rating.comment}</span>
				{if $delcom_link}
					<br /><a href="javascript: if (confirm('{$locale.index_shop.main_details_confirm_del}')) document.location.href='{$delcom_link}&amp;rid={$rating.rid}';" title="{$locale.index_shop.main_details_field_delete}">{$locale.index_shop.main_details_field_delete}</a>
				{/if}
				<br /><br />
			{foreachelse}
				{if $shop_is_reguser_rating == 1 && !$smarty.session.user_id}
					<span>{$locale.index_shopmain_details_rating_onlyreg}</span>
				{else}
					<span>{$locale.index_shop.main_details_field_notratingyet}</span>
				{/if}
			{/foreach}
		</div><br />
		{if ($shop_is_reguser_rating == 1 && $smarty.session.user_id) || $shop_is_reguser_rating == 0}
		<div style="padding-left: 10px; padding-right: 10px;">
			<form {$form_rating.attributes}>
			{$form_rating.hidden}
				{$form_rating.ratingnum.label}<br />
				{$form_rating.ratingnum.html} {if $form_rating.ratingnum.error}<span class="error">{$form_rating.ratingnum.error}</span>{/if}<br />
				{$form_rating.ratingcom.label}<br />
				{$form_rating.ratingcom.html}<br />
				<span class="shop_desc">{$locale.index_shop.main_details_rating_comm1}{$shop_ratemin}{$locale.index_shop.main_details_rating_comm2}{$shop_ratemax}{$locale.index_shop.main_details_rating_comm3}</span><br />
				{if $form_rating.ratingcom.error}<span class="error">{$form_rating.ratingcom.error}</span>{/if}<br />
				{$form_rating.submit.html} {$form_rating.reset.html}
			</form>
		</div>
		{/if}
	</div>
	{/if}
	{* ERTEKELES VEGE *}

	<div id="shop_bottom"></div>
</div>
