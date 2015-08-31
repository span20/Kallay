<div class="centered">
<h2>{$lang_forum.strForumHeader}</h2>
<div class="topic_message">
	<h3 class="topic_title">
		<span>{$lang_forum.strForumTopicName}:</span> {$topic_name}
	</h3>
	<div class="message">
	{$topic_subject}
	</div>
	{if $rate_right}
    <p>
    <form{$form_forum.attributes}>
    {$form_forum.hidden}
    {foreach item=sec key=i from=$form_forum.sections}
	   {foreach item=element from=$sec.elements}
		{if $element.type eq "submit" or $element.type eq "reset"}
			{if not $form_forum.frozen}
			 {$element.html}
			{/if}
		{else}
			{if $element.required}<span class="required">*</span>{/if}{$element.label}<br />
			{if $element.error}<span class="error">{$element.error}</span><br />{/if}
			{if $element.type eq "group"}
				{foreach key=gkey item=gitem from=$element.elements}
					{$gitem.label}
					{$gitem.html}{if $gitem.required}<span class="required">*</span>{/if}
					{if $element.separator}{cycle values=$element.separator}{/if}
				{/foreach}
			{else}
				{$element.html}
			{/if}
			<br />
		{/if}
	{/foreach}
  {/foreach}
  </form>    
  </p>
  {/if}
<p>
{foreach from=$forum_breadcrumb item=bc name=bc_foreach}
{if not $smarty.foreach.bc_foreach.first} - {/if}<a href="{$bc.link}" title="{$bc.title|htmlspecialchars}">{$bc.title|htmlspecialchars}</a>
{/foreach}
</p>
	<p class="centered">{$lang_forum.strForumTopicMessageCount}: {$total}</p>
</div>
{if $is_addright}
<div class="content_menu">
	<a href="index.php?{$self}&amp;parent={$parent}&amp;tid={$tid}&amp;act=addmsg" title="{$lang_forum.strForumNewMessage}">{$lang_forum.strForumNewMessage}</a>
</div>
{/if}
<p><a class="back" href="index.php?{$self}&amp;parent={$parent}">{$lang_forum.strForumBack}</a></p>
<p class="page_list">{$pl_forum}</p>

<script type="text/javascript">//<![CDATA[{if $del_right}{literal}
	function torol(msgid)
	{ {/literal}
		x = confirm('{$lang_forum.strForumMessageDeleteConfirm}'); {literal}       
		if (x) { {/literal}
			document.location.href='index.php?{$self}&parent={$parent}&tid={$tid}&act=delmsg&msgid='+msgid {literal}
		}
	} {/literal}{/if}{literal}
	function picClick(obj, width, height) {
	    w = window.open(obj.href, 'picwindow', "status=0,toolbar=0,resizable=1,menubar=0,location=0,height="+(height+20)+",width="+(width+20));
	    w.moveTo(window.screenX+((window.outerWidth-width)/2), window.screenY+((window.outerHeight-height)/2));
	}
//]]>{/literal}
</script>

{foreach from=$pd_forum item=data}
	<div class="topic_message">
		<div class="message_header">
			<h3>
			{censor text=$data.subject}
			</h3>
			<span class="user_data">{$data.user_name}{if $data.user_email!=""} &lt;{mailto address=$data.user_email}&gt;{/if}</span>
			<span class="time">{$data.add_date}</span>
		</div>
		<div class="message">
		{bbcode text=$data.message}
		{if !empty($data.pics)}
		  <div class="message_pics">
		  {foreach from=$data.pics item=pic}
		      <a href="{$forum_files_dir}/{$pic.realname}" title="{$pic.name|htmlspecialchars}" onclick="picClick(this, {$pic.width},{$pic.height}); return false;">{$pic.name|htmlspecialchars}</a>
		  {/foreach}
		  </div>
		{/if}

		{if $data.embed}
		<div class="message_embed">
		  {$data.embed}
		</div>
		{/if}
		{if $data.user_sign}
		  <div class="message_sign"><hr />
		      {$data.user_sign}
		  </div>
		{/if}
		</div>
		{if $is_addright || $block_right || $mod_right || $del_right}
		<ul class="message_menu noprint">
			<li>[<a href="index.php?{$self}&amp;parent={$parent}&amp;act=addmsg&amp;tid={$tid}&amp;re_id={$data.mid}" title="{$lang_forum.strForumReply}">{$lang_forum.strForumMessageReply}</a>]</li>
			{if $block_right}
				{if $data.is_blocked=="1"}
					<li><span class="error">{$lang_forum.strForumMessageBlocked}</span></li>
					<li>[<a href="index.php?{$self}&amp;parent={$parent}&amp;act=block&amp;tid={$tid}&amp;msgid={$data.mid}" title="{$lang_forum.strForumMessageUnBlock}">{$lang_forum.strForumMessageUnBlock}</a>]
				{else}	
					<li>[<a href="index.php?{$self}&amp;parent={$parent}&amp;act=block&amp;tid={$tid}&amp;msgid={$data.mid}" title="{$lang_forum.strForumMessageBlock}">{$lang_forum.strForumMessageBlock}</a>]</li>
				{/if}
			{/if}
			{if $mod_right}
				<li>[<a href="index.php?{$self}&amp;parent={$parent}&amp;act=modmsg&amp;tid={$tid}&amp;msgid={$data.mid}" title="{$lang_forum.strForumMessageModify}">{$lang_forum.strForumMessageModify}</a>]</li>
			{/if}
			{if $del_right}
				<li>[<a href="javascript: torol({$data.mid});" title="{$lang_forum.strForumMessageDelete}">{$lang_forum.strForumMessageDelete}</a>]</li>
			{/if}
		</ul>
		{/if}
	</div>
{/foreach}
<p><a class="back" href="index.php?{$self}&amp;parent={$parent}">{$lang_forum.strForumBack}</a></p>
</div>
