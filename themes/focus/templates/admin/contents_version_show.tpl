<div id="form_cnt">
		<div class="tabs">
			<ul>
				<li class="current"><a href="#" title="{$lang_title}">{$lang_title}</a></li>
			</ul>
			<div class="blueleft"></div><div class="blueright"></div>
		</div>
	<div id="f_content">
	<div class="f_empty"></div>
	<table>
		<tr>
			<td class="first">
				<h4>{$locale.admin_contents.version_tpl_news_versionname}</h4>
				<p>{$page_data.title}</p>
				{if !empty( $page_data.lead )}
					<h4>{$locale.admin_contents.version_tpl_news_versionlead}</h4>
					<p>{$page_data.lead}</p>
				{/if}
				<h4>{$locale.admin_contents.version_tpl_news_versioncontent}</h4>
				{$page_data.content}
				<h4>{$locale.admin_contents.version_tpl_news_versionauthor} {$page_data.author}</h4>
				<h4>{$locale.admin_contents.version_tpl_news_versiondate} {$page_data.mod_date}</h4>
				<center><a href="admin.php?p={$self}&amp;act={$this_page}&amp;act=restore&cid={$page_data.parent_content_id}&amp;restore_version={$page_data.id}" onCLick="return confirm('{$locale.admin_contents.version_tpl_confirm_restore}');">{$locale.admin_contents.version_tpl_news_versionrestore}</a></center>
			</td>
		</tr>
	</table>
  </div>
  <div id="t_bottom"></div>
</div>