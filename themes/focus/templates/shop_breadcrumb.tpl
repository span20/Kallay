{foreach name=bcrumb from=$shop_breadcrumb item=bcdata}
	{if !$smarty.foreach.bcrumb.first}
		&#x95;
	{/if}
	{if $bcdata.link == ''}
		<span>{$bcdata.title|htmlspecialchars}</span>
	{else}
		<a href="{$bcdata.link}" title="{$bcdata.title|htmlspecialchars}">{$bcdata.title|htmlspecialchars}</a>
	{/if}
{/foreach}
