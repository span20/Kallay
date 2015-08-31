<div id="header">
    <div style="float: left;">
    	<a href="index.php"><img src="{$theme_dir}/images/logo.gif" /></a>
    </div>
    <div style="float: right; padding-top: 70px;" id="topmenu">
    	<div>
            {foreach from=$topmenu item=data name="topmenu"}
                <a href="/{$data.menu_name|hunchars}/{$data.menu_id}">{$data.menu_name}</a> {if !$smarty.foreach.topmenu.last}&middot;{/if}
            {/foreach}
        </div>
    </div>
</div>
