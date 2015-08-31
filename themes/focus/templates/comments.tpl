{if ($is_user_reg == 1 && $smarty.session.user_id) || $is_user_reg == 0}
	<input type="button" class="submit" name="submit" onClick="window.location='index.php?p=comments&amp;com_act=comments_add&amp;back_id={$back_id}&amp;module={$back_module}&amp;link={$back_link|urlencode}'" value="{$locale.index_comments.button_add_comment}" /></td>
{else}
	{$locale.index_comments.warning_reg_comment}
{/if}
<div>
	{foreach from=$news_comment item=data key=key}
	<div>
		<a name="{$key}"></a>
		<div style="float: left;">{$data.name}</div>
			<div style="float: right;">
				{if ($is_user_reg == 1 && $smarty.session.user_id) || $is_user_reg == 0}
					<a href="index.php?p=comments&amp;com_act=comments_add&amp;back_id={$back_id}&amp;pre={$key}&amp;module={$back_module}&amp;link={$back_link|urlencode}" title="{$locale.index_comments.field_main_reply}">{$locale.index_comments.field_main_reply}</a>&nbsp;
				{/if}
				{if $data.premise && $data.premise != 0}<a href="index.php?p={$back_module}{$back_link}#{$data.premise}" title="{$locale.index_comments.field_main_premise}">{$locale.index_comments.field_main_premise}</a>&nbsp;{/if}
				{$data.add_date} (#{$key})
			</div>
			<div style="clear: both;">{$data.comment|nl2br}</div>
			{if $is_newscomment_modify || $is_newscomment_delete}
			<div>
				{if $is_newscomment_modify}
					<a href="index.php?p=comments&amp;com_act=comments_mod&amp;coid={$key}&amp;back_id={$back_id}&amp;module={$back_module}&amp;link={$back_link|urlencode}" title="{$locale.index_comments.title_comment_modify}">{$locale.index_comments.title_comment_modify}</a>
				{/if}
				{if $is_newscomment_delete}
					<a href="javascript: if (confirm('{$locale.index_comments.confirm_comment_delete}')) document.location.href='index.php?p=comments&amp;com_act=comments_del&amp;coid={$key}&amp;module={$back_module}&amp;link='+escape('{$back_link}');" title="{$locale.index_comments.title_comment_delete}">{$locale.index_comments.title_comment_delete}</a>
				{/if}
			</div>
			{/if}
		</div>
	{foreachelse}
		<div style="text-align: center">{$locale.index_comments.warning_empty_comments}</div>
	{/foreach}
</div>