<div>{if $message_string}<p style="text-align:center;color:green;">{$message_string}</p>{/if}</div>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p={$self}" title="{$locale.$self.title_builder_tab}">{$locale.$self.title_builder_tab}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div id="pager"></div>
			<div style="float: left; width: 98%;">
				{foreach from=$c item=columns name=cols}
					 <div style="width: {$colwidth[$smarty.foreach.cols.index]}{$smarty.session.site_builder_columns_measure}; float: left;">
					 	{foreach from=$columns key=key item=boxes name=boxs}
						<div style="width: 100%; float: left; clear: both; height: 100px; border: 1px solid;">
							<div style="border-bottom: 1px solid;">
						    	{$smarty.foreach.boxs.iteration}.
								<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=mod&amp;bid={$key}" title="{$locale.$self.field_builder_list_modify}">
            				    	<img src="{$theme_dir}/images/admin/modify.gif" border="0" alt="{$locale.$self.field_builder_list_modify}" />
								</a>
								<a href="javascript: if (confirm('{$locale.$self.confirm_del_box}')) document.location.href='admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=del&amp;bid={$key}';" title="{$locale.$self.field_builder_list_delete}">
            				    	<img src="{$theme_dir}/images/admin/delete.gif" border="0" alt="{$locale.$self.field_builder_list_delete}" />
								</a>
								{if $smarty.foreach.boxs.total gt "1"}
									{if $smarty.foreach.boxs.first}
										<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pos&amp;bid={$key}&amp;way=down" title="{$locale.$self.field_list_waydown}">
										<img src="{$theme_dir}/images/admin/down.gif" border="0" alt="{$locale.$self.field_list_waydown}" />
									</a>
									{elseif $smarty.foreach.boxs.last}
										<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pos&amp;bid={$key}&amp;way=up" title="{$locale.$self.field_list_wayup}">
										<img src="{$theme_dir}/images/admin/up.gif" border="0" alt="{$locale.$self.field_list_wayup}" />
									</a>
									{else}
										<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pos&amp;bid={$key}&amp;way=up" title="{$locale.$self.field_list_wayup}">
										<img src="{$theme_dir}/images/admin/up.gif" border="0" alt="{$locale.$self.field_list_wayup}" />
									</a>
									<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=pos&amp;bid={$key}&amp;way=down" title="{$locale.$self.field_list_waydown}">
										<img src="{$theme_dir}/images/admin/down.gif" border="0" alt="{$locale.$self.field_list_waydown}" />
									</a>
									{/if}
								{/if}
								{if $smarty.foreach.cols.first}
									<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=colpos&amp;bid={$key}&amp;way=right" title="{$locale.$self.field_list_wayup}">
									<img src="{$theme_dir}/images/admin/right_blue.gif" border="0">
									</a>
								{elseif $smarty.foreach.cols.last}
									<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=colpos&amp;bid={$key}&amp;way=left" title="{$locale.$self.field_list_wayup}">
									<img src="{$theme_dir}/images/admin/left_blue.gif" border="0">
									</a>
								{else}
									<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=colpos&amp;bid={$key}&amp;way=left" title="{$locale.$self.field_list_wayup}">
									<img src="{$theme_dir}/images/admin/left_blue.gif" border="0">
									</a>
									<a href="admin.php?p={$self}&amp;act={$this_page}&amp;sub_act=colpos&amp;bid={$key}&amp;way=right" title="{$locale.$self.field_list_wayup}">
									<img src="{$theme_dir}/images/admin/right_blue.gif" border="0">
									</a>
								{/if}
							</div>
							{foreach from=$boxes.contents item=contents name=conts}
							<div style="width: {math equation="x/y" x=100 y=$smarty.foreach.conts.total}%; float: left;">
								{if $contents.menu_pos}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_menu}</b>
    							{/if}
    							{if $contents.content_id}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_cont}</b>
    							{/if}
    							{if $contents.category_id}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_category}</b>
    							{/if}
    							{if $contents.block}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_block} ({$contents.block})</b>
    							{/if}
    							{if $contents.module_id}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_module}</b>
    							{/if}
    							{if $contents.banner_pos}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_banner}</b>
    							{/if}
    							{if $contents.gallery_id}
    								{$locale.$self.field_builder_list_content}:
    								<b>{$locale.$self.field_builder_list_gallery}</b>
    							{/if}
							</div>
							{/foreach}
						</div>
						{/foreach}
					 </div>
				{foreachelse}
				<p class="empty" style="clear:left;">
					<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$locale.$self.warning_no_banners}" />
					{$locale.$self.warning_no_data}
				</p>
				{/foreach}
			</div>
		<div id="pager"></div>
	</div>
	<div id="t_bottom"></div>
</div>
