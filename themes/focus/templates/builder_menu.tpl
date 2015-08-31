<div style="height: 200px; border: 2px dotted brown;">
	{defun name="menu" list=$contents.menu_pos}
		<ul>
			{foreach from=$list item=menupont}
				<li>
					{if $list.0.level=='1'}
						<div><a href="index.php?mid={$menupont.menu_id}" title="{$menupont.menu_name|htmlspecialchars}">{$menupont.menu_name|htmlspecialchars}</a></div>
					{else}
						<a href="index.php?mid={$menupont.menu_id}" title="{$menupont.menu_name|htmlspecialchars}">{$menupont.menu_name|htmlspecialchars}</a>
					{/if}							
					{if $menupont.element}
						{fun name="menu" list=$menupont.element}
					{/if}
				</li>
			{/foreach}
		</ul>
	{/defun}
</div>
