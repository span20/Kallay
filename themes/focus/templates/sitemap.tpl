<div style="padding-left:14px;padding-right:14px;">
	<div class="cim">{$locale.index_sitename.list_title}</div>
	<div id="sitemap">
	{defun name=tree list=$sitemap}
	<ul>
	{foreach from=$list item=menu}
	<li class="szoveg" style="padding-top:9px;"><a href="index.php?mid={$menu.menu_id}" title="{$menu.menu_name|htmlspecialchars}" class="szoveg">{$menu.menu_name}</a>
	{if is_array($menu.element)}
		{fun name=tree list=$menu.element}
	{/if}
	</li>
	{/foreach}
	</ul>
	{/defun}
	</div>
</div>
