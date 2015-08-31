{if $marquee}
	<script language="javascript">var marqueecontent='<nobr>{foreach from=$marquee key=key item=data name=csik}<a href="index.php?p=contents&cid={$key}">{$data|escape}</a> {if !$smarty.foreach.csik.last}-{/if} {/foreach}</nobr>'</script>
	<script language="javascript" src="{$include_dir}/javascript.marquee.js"></script>
{/if}
