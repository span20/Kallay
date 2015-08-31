<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=modules" title="{$lang_modules.strAdminModulesHeader}">{$lang_modules.strAdminModulesHeader}</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="modules">
				{$lang_admin.strAdminOrderBy}
				<select name="field">
					<option value="1" {$fieldselect1}>{$lang_modules.strAdminModulesName}</option>
					<option value="2" {$fieldselect2}>{$lang_modules.strAdminModulesType}</option>
					<option value="3" {$fieldselect3}>{$lang_modules.strAdminModulesFile}</option>
					<option value="4" {$fieldselect4}>{$lang_modules.strAdminModulesDesc}</option>
				</select>
				{$lang_admin.strAdminBy}
				<select name="ord">
					<option value="asc" {$ordselect1}>{$lang_admin.strAdminAsc}</option>
					<option value="desc" {$ordselect2}>{$lang_admin.strAdminDesc}</option>
				</select>
				{$lang_admin.strAdminOrder}
				<input type="submit" name="submit" value="{$lang_admin.strAdminSubmitFilter}" class="submit_filter">
			</form>
		</div>
		<div id="pager">{$page_list}</div>
		<table>
			<tr>
				<th class="first">{$lang_modules.strAdminModulesName}</th>
				<th>{$lang_modules.strAdminModulesType}</th>
				<th>{$lang_modules.strAdminModulesFile}</th>
				<th>{$lang_modules.strAdminModulesDesc}</th>
				<th class="last">{$lang_modules.strAdminModulesAction}</th>
			</tr>
			{foreach from=$page_data item=data}
				<tr class="{cycle values="row1,row2"}">
					<td class="first">{$data.mname}</td><td>{$data.mtype}</td><td>{$data.mfname}{$data.mfext}</td><td>{$data.mdesc}</td>
					<td class="last">
						{if $data.mactive == 1}
							<a href="admin.php?p=modules&amp;act=act&amp;m_id={$data.mid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$lang_modules.strAdminModulesInActivate}">
								<img src="{$theme_dir}/images/admin/active.gif" border="0" alt="{$lang_modules.strAdminModulesInActivate}" />
							</a>
						{else}
							<a href="admin.php?p=modules&amp;act=act&amp;m_id={$data.mid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$lang_modules.strAdminModulesActivate}">
								<img src="{$theme_dir}/images/admin/inactive.gif" border="0" alt="{$lang_modules.strAdminModulesActivate}" />
							</a>
						{/if}
						{if $data.mins == 1}
							<a href="admin.php?p=modules&amp;act=ins&amp;m_id={$data.mid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$lang_modules.strAdminModulesUnInstall}">
								<img src="{$theme_dir}/images/admin/uninstall.gif" border="0" alt="{$lang_modules.strAdminModulesUnInstall}" />
							</a>
						{else}
							<a href="admin.php?p=modules&amp;act=ins&amp;m_id={$data.mid}&amp;field={$smarty.get.field}&amp;ord={$smarty.get.ord}&amp;pageID={$page_id}" title="{$lang_modules.strAdminModulesInstall}">
								<img src="{$theme_dir}/images/admin/install.gif" border="0" alt="{$lang_modules.strAdminModulesInstall}" />
							</a>
						{/if}
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td colspan="5" class="empty">
						<img src="{$theme_dir}/images/admin/error.gif" border="0" alt="{$lang_modules.strAdminModulesEmptylist}" />
						{$lang_modules.strAdminModulesEmptylist}
					</td>
				</tr>
			{/foreach}
		</table>
		<div id="pager">{$page_list}</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>
