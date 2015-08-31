{if $class_add_link}
	<a href="{$class_add_link}" title="{$locale.$self_class.title_main_add}">{$locale.$self_class.title_main_add}</a>
{/if}
<div id="shop">
	<div id="shop_top">{$cat_name|upper}</div>
	<div id="shop_cnt">
		{* KATEGORIAK LISTAJA *}
		{foreach from=$class_category item=data name=cat}
		<div class="shop_cat" {if $smarty.foreach.cat.index is div by 4}style="clear: both;"{/if}>
			<div class="shop_catpic">
				{if $data.cpic != ""}
					<a href="index.php?p={$self_class}&amp;act=classifieds_lst&amp;cid={$data.cid}" title="{$data.cname}">
						<img src="{$smarty.session.site_class_catpicdir}/tn_{$data.cpic}" border="0" alt="{$data.cname}">
					</a>
				{/if}
			</div>
			<div class="shop_title">
				<a href="index.php?p={$self_class}&amp;act=classifieds_lst&amp;cid={$data.cid}" title="{$data.cname}">{$data.cname}</a>
				({$data.count} {$locale.$self_class.class_count})
			</div>
			{if $smarty.session.site_class_is_catdesc}
				<div class="shop_title">{$data.cdesc}</div>
			{/if}
		</div>
		{/foreach}
		{* KATEGORIAK LISTAJA VEGE *}

		{* HA VAN AUTOCATEGORY ES NEM APROHIRDETES FOOLDALON VAGYUNK, AKKOR ANNAK A LISTAJA *}
		{if $smarty.session.site_class_autocategory && $smarty.get.cid}
		<div style="text-align: center;">
			<a href="index.php?p={$self_class}&amp;act=classifieds_lst&amp;cid={$smarty.get.cid}&amp;type=0" title="{$locale.$self_class.field_sell}">{$locale.$self_class.field_sell}</a> ({$autocat_sell} {$locale.$self_class.class_count})
			<a href="index.php?p={$self_class}&amp;act=classifieds_lst&amp;cid={$smarty.get.cid}&amp;type=1" title="{$locale.$self_class.field_buy}">{$locale.$self_class.field_buy}</a> ({$autocat_buy} {$locale.$self_class.class_count})
			<a href="index.php?p={$self_class}&amp;act=classifieds_lst&amp;cid={$smarty.get.cid}&amp;type=2" title="{$locale.$self_class.field_swap}">{$locale.$self_class.field_swap}</a> ({$autocat_swap} {$locale.$self_class.class_count})
		</div>
		{/if}

		{* KATEGORIAHOZ TARTOZO HIRDETESEK *}
		{if $page_data}
		<div style="clear: both;">
			<div class="pager">{$page_list}</div>
			{foreach name=prod from=$page_data item=data}
			<div class="shop_prod" {if $smarty.foreach.prod.iteration % 2}style="clear: both;"{/if}>
				<div class="shop_pic">
					<a id="class_{$data.aid}" name="class_{$data.aid}"></a>
					{if $data.pictures}
						{foreach from=$data.pictures item=pic}
							<img src="{$smarty.session.site_class_advpicdir}/tn_{$pic}" border="0" alt="{$data.pname}" />
						{/foreach}
					{/if}
				</div>
				<div class="shop_data">
					{if $data.cattype}
						<p class="desc">{$locale.$self_class.class_type} {$data.cattype}</p>
					{/if}
					{if $data.counties}
						<p class="desc">{$locale.$self_class.class_counties}
						{foreach from=$data.counties item=county name=county}
							{$county}{if !$smarty.foreach.county.last}, {/if}
						{/foreach}
						</p>
					{/if}
					<p class="desc">{$locale.$self_class.class_add_date} {$data.add_date}</p>
					<p class="desc">{$locale.$self_class.class_mod_date} {$data.mod_date}</p>
					<p class="desc">{$locale.$self_class.class_timer_end} {$data.timer_end}</p>
					<p class="desc">{$locale.$self_class.class_mail} <a href="mailto: {$data.email}">{$data.classname}</a></p>
					<p class="desc">{$locale.$self_class.class_phone} {$data.phone}</p>
					<p class="desc">{$locale.$self_class.class_price} {$data.price}</p>
					<p class="desc">{$locale.$self_class.class_desc} {$data.cdesc}</p>
					<p class="desc">{$locale.$self_class.class_id} {$data.aid}</p>
				</div>
			</div>
			{/foreach}
			<div class="pager">{$page_list}</div>
		</div>
		{else}
			<div style="clear: both; text-align: center;">{$lang_shop.strShopNotProduct}</div>
		{/if}
	</div>
	<div id="shop_bottom"></div>
</div>
