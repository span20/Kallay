<div class="centered">
<h2>{$lang_forum.strForumHeader}</h2>
<div class="content_menu">
    <a href="index.php?{$self}&amp;act=active_users">{$lang_forum.strForumActiveUsers}</a>
{if $is_addright}
	{if $parent==0}
    	{if $censor_right}
	   | <a href="index.php?{$self}&amp;act=censor" title="{$lang_forum.strForumCensor}">{$lang_forum.strForumCensor}</a> 
	   {/if}
	   | <a href="index.php?{$self}&amp;act=fadd" title="{$lang_forum.strForumNewForum}">{$lang_forum.strForumAddForum}</a>
	{else}
	   | <a href="index.php?{$self}&amp;act=add&amp;parent={$parent}" title="{$lang_forum.strForumNewTopic}">{$lang_forum.strForumNewTopic}</a>
	{/if}
{/if}
</div>
<p>{$lang_forum.strForumTotalItems}: {$total}</p>
<p class="page_list">{$pl_forum}</p>
{if $del_right}
<script type="text/javascript">//<![CDATA[{literal}
	function torol()
	{ {/literal}
		return confirm('{$lang_forum.strForumTopicDeleteConfirm}'); {literal}       
	}
//]]>{/literal}
</script>
{/if}
<p>
{foreach from=$forum_breadcrumb item=bc name=bc_foreach}
{if not $smarty.foreach.bc_foreach.first} - {/if}<a href="{$bc.link}" title="{$bc.title|htmlspecialchars}">{$bc.title|htmlspecialchars}</a>
{/foreach}
</p>
<table class="content_table">
	<thead>
	<tr>
	    <th class="table_subject">{$lang_forum.strForumTopicName}</th>
	    {if $parent>0}<th class="table_count">{$lang_forum.strForumTopicMessageCount}</th>{/if}
		<th class="table_user">{$lang_forum.strForumTopicOwner}</th>
		{if $parent>0}<th class="table_date">{$lang_forum.strForumTopicLastMessage}</th>{/if}
	</tr>
	</thead>
	<tbody>
	{foreach from=$pd_forum item=data}
	<tr class="{cycle values="tr_odd,tr_twin"} row">
		<td><a href="{if $parent == 0}index.php?{$self}&amp;parent={$data.tid}{else}index.php?{$self}&amp;parent={$parent}&amp;tid={$data.tid}{/if}">{$data.topic_name|htmlspecialchars}</a><br /><i>{$data.topic_subject|htmlspecialchars}</i>{if $smarty.session.user_id}<br />{/if}
			{if $act_right}
				{if $data.is_active=="1"}
					<span class="active">{$lang_forum.strForumTopicActive}</span>
				{else}
					<span class="inactive">{$lang_forum.strForumTopicInactive}</span>
				{/if}
		    [<a href="index.php?{$self}&amp;parent={$parent}&amp;act=act&amp;tid={$data.tid}" title="{$lang_forum.strForumTopicActivate}">{$lang_forum.strForumTopicActivate}</a>]
			{/if}
			{if $mod_right}
			[<a href="index.php?{$self}&amp;parent={$parent}&amp;act=mod&amp;tid={$data.tid}" title="{$lang_forum.strForumTopicModify}">{$lang_forum.strForumTopicModify}</a>]
			{/if}
			{if $del_right}
			[<a href="index.php?{$self}&amp;parent={$parent}&amp;act={if $parent==0}f{/if}del&amp;tid={$data.tid}" onclick="return torol();" title="{$lang_forum.strForumTopicDelete}">{$lang_forum.strForumTopicDelete}</a>]
			{/if}
		</td>
		{if $parent>0}<td class="centered">{num_messages tid=$data.tid}</td>{/if}
		<td class="centered">{$data.add_user_name}</td>
		{if $parent>0}<td class="centered">{$data.last_user_name}<br />
		{$data.last_message_date}</td>{/if}
	</tr>
	{foreachelse}
		<tr><td colspan="6" class="error">{$lang_forum.strForumTopicEmptyList}</td></tr>
	{/foreach}
	</tbody>
</table>
</div>
