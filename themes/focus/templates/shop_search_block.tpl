<div id="block" style="margin-top: 10px;">
	<form {$form_search_block.attributes}>
	{$form_search_block.hidden}
	<div id="b_top">{$form_search_block.header.search|upper}</div>
	<div id="b_content">
		<div>
			<div>{$form_search_block.searchtext.label}</div>
			<div>{$form_search_block.searchtext.html}</div>
		</div>
		<div>{$form_search_block.submit.html}</div>
		<div><a href="index.php?p=shop&amp;act=sea" title="{$locale.index_shop.block_search_detailsearch}">{$locale.index_shop.block_search_detailsearch}</a></div>
	</div>
	<div id="b_bottom"></div>
	</form>
</div>