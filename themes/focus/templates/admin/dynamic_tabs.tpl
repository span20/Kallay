<div class="tabs">
	<ul>
		{foreach from=$dynamic_tabs key=tabcode item=tabname}
		<li{if $this_page==$tabcode} class="current"{/if}><a href="admin.php?p={$self}&amp;act={$tabcode}" title="{$tabname|htmlspecialchars}">{$tabname|htmlspecialchars}</a></li>
		{foreachelse}
		<li class="current"><a href="#">...</a></li>
		{/foreach}
	</ul>
	<div class="blueleft"></div><div class="blueright"></div>
</div>

