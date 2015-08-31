{if $smarty.session.user_id}
	<table cellpadding="2" cellspacing="0">
		<tr>
			<td class="block_header" style="background-color: #006BB6;">
				{$locale.index_account.block_modify_header|upper}
			</td>
		</tr>
		<tr><td style="height: 5px;"></td></tr>
		<tr><td class="block">{$locale.index_account.block_title_name}</td></tr>
		<tr><td class="block">{$smarty.session.username}<br />({$smarty.session.realname})</td></tr>
		<tr><td class="block">{$locale.index_account.block_title_last}</td></tr>
		<tr><td class="block">{$smarty.session.lastvisit}</td></tr>
		<tr><td class="block">&nbsp;</td></tr>
		<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_mod" title="{$locale.index_account.block_title_modify}">{$locale.index_account.block_title_modify}</a>
		</td></tr>
		<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_out" title="{$locale.index_account.block_title_logout}">{$locale.index_account.block_title_logout}</a>
		</td></tr>
		{if $adminlink}
		<tr><td class="block">
			<a href="admin.php" title="{$adminlink}">{$adminlink}</a>
		</td></tr>
		{/if}
		<tr><td class="block">&nbsp;</td></tr>
	</table>
{else}
	<form {$form_login.attributes}>
	{$form_login.hidden}
	<input type="hidden" name="prevpage" value="{$prevpage}">
	<table cellpadding="2" cellspacing="0" style="text-align: center; font-size: 14px;" width="100%">
		<tr>
			<td class="block_header">
				<h1>{$form_login.header.login}</h1>
			</td>
		</tr>
		<tr><td style="height: 5px;"></td></tr>
		<tr><td class="block">{$form_login.login_email.label}</td></tr>
		<tr><td class="block">{$form_login.login_email.html}</td></tr>
		<tr><td class="block">{$form_login.login_pass.label}</td></tr>
		<tr><td class="block">{$form_login.login_pass.html}</td></tr>
		<tr><td class="block">&nbsp;</td></tr>
		<tr><td class="block">{$form_login.acc_submit.html}</td></tr>
		<tr><td class="block">&nbsp;</td></tr>
		<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_add" title="{$locale.index_account.block_title_reg}">{$locale.index_account.block_title_reg}</a>
		</td></tr>
		<!--<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_lst" title="{$locale.index_account.block_title_lostpass}">{$locale.index_account.block_title_lostpass}</a>
		</td></tr>-->
		<tr><td class="block">&nbsp;</td></tr>
	</table>
	</form>
{/if}