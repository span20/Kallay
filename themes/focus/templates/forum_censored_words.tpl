<div class="centered">
<h2>{$lang_forum.strForumCensorHeader}</h2>
{if $censoradd_right}
<div class="content_menu">
	<a href="index.php?{$self}&amp;act=censoradd" title="{$lang_forum.strForumCensorAdd}">{$lang_forum.strForumCensorAdd}</a>
</div>
{/if}
<p>{$lang_forum.strForumCensorTotal}: {$censor_total}</p>
<p class="page_list">{$pl_forum}</p>
{if $censordel_right}
<script type="text/javascript">//<![CDATA[{literal}
	function torol(cid)
	{ {/literal}
		x = confirm('{$lang_forum.strForumCensorDeleteConfirm}'); {literal}       
		if (x) { {/literal}
			document.location.href='index.php?{$self}&act=censordel&cid='+cid {literal}
		}
	}
//]]>{/literal}
</script>
{/if}
<table class="content_table">
	<thead>
	<tr>
	    <th class="table_subject">{$lang_forum.strForumCensorWord}</th>
        <th class="table_date">{$lang_forum.strForumCensorActions}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$pd_forum item=data}
	<tr class="{cycle values="tr_odd,tr_twin"} row">
		<td>{$data.word|htmlspecialchars}</td>
		<td>{if $censormod_right}[<a href="index.php?{$self}&amp;act=censormod&amp;cid={$data.cens_id}" title="{$lang_forum.strForumCensorMod}">{$lang_forum.strForumCensorMod}</a>]{/if}
			{if $censordel_right}[<a href="javascript: torol({$data.cens_id});" title="{$lang_forum.strForumCensorDel}">{$lang_forum.strForumCensorDel}</a>]{/if}
		</td>
	</tr>
	{foreachelse}
		<tr><td colspan="6" class="error">{$lang_forum.strForumCensorEmptyList}</td></tr>
	{/foreach}
	</tbody>
</table>
<a href="index.php?{$self}" title="{$lang_forum.strForumBack}">{$lang_forum.strForumBack}</a>
</div>
