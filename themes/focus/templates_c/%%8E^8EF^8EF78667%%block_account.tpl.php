<?php /* Smarty version 2.6.16, created on 2013-04-18 19:07:02
         compiled from block_account.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'block_account.tpl', 5, false),)), $this); ?>
<?php if ($_SESSION['user_id']): ?>
	<table cellpadding="2" cellspacing="0">
		<tr>
			<td class="block_header" style="background-color: #006BB6;">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_account']['block_modify_header'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

			</td>
		</tr>
		<tr><td style="height: 5px;"></td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['locale']['index_account']['block_title_name']; ?>
</td></tr>
		<tr><td class="block"><?php echo $_SESSION['username']; ?>
<br />(<?php echo $_SESSION['realname']; ?>
)</td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['locale']['index_account']['block_title_last']; ?>
</td></tr>
		<tr><td class="block"><?php echo $_SESSION['lastvisit']; ?>
</td></tr>
		<tr><td class="block">&nbsp;</td></tr>
		<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_mod" title="<?php echo $this->_tpl_vars['locale']['index_account']['block_title_modify']; ?>
"><?php echo $this->_tpl_vars['locale']['index_account']['block_title_modify']; ?>
</a>
		</td></tr>
		<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_out" title="<?php echo $this->_tpl_vars['locale']['index_account']['block_title_logout']; ?>
"><?php echo $this->_tpl_vars['locale']['index_account']['block_title_logout']; ?>
</a>
		</td></tr>
		<?php if ($this->_tpl_vars['adminlink']): ?>
		<tr><td class="block">
			<a href="admin.php" title="<?php echo $this->_tpl_vars['adminlink']; ?>
"><?php echo $this->_tpl_vars['adminlink']; ?>
</a>
		</td></tr>
		<?php endif; ?>
		<tr><td class="block">&nbsp;</td></tr>
	</table>
<?php else: ?>
	<form <?php echo $this->_tpl_vars['form_login']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['form_login']['hidden']; ?>

	<input type="hidden" name="prevpage" value="<?php echo $this->_tpl_vars['prevpage']; ?>
">
	<table cellpadding="2" cellspacing="0" style="text-align: center; font-size: 14px;" width="100%">
		<tr>
			<td class="block_header">
				<h1><?php echo $this->_tpl_vars['form_login']['header']['login']; ?>
</h1>
			</td>
		</tr>
		<tr><td style="height: 5px;"></td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['form_login']['login_email']['label']; ?>
</td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['form_login']['login_email']['html']; ?>
</td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['form_login']['login_pass']['label']; ?>
</td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['form_login']['login_pass']['html']; ?>
</td></tr>
		<tr><td class="block">&nbsp;</td></tr>
		<tr><td class="block"><?php echo $this->_tpl_vars['form_login']['acc_submit']['html']; ?>
</td></tr>
		<tr><td class="block">&nbsp;</td></tr>
		<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_add" title="<?php echo $this->_tpl_vars['locale']['index_account']['block_title_reg']; ?>
"><?php echo $this->_tpl_vars['locale']['index_account']['block_title_reg']; ?>
</a>
		</td></tr>
		<!--<tr><td class="block">
			<a href="index.php?p=account&amp;act=account_lst" title="<?php echo $this->_tpl_vars['locale']['index_account']['block_title_lostpass']; ?>
"><?php echo $this->_tpl_vars['locale']['index_account']['block_title_lostpass']; ?>
</a>
		</td></tr>-->
		<tr><td class="block">&nbsp;</td></tr>
	</table>
	</form>
<?php endif; ?>