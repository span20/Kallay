<?php /* Smarty version 2.6.16, created on 2013-04-10 20:02:44
         compiled from admin/users_g_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/users_g_list.tpl', 21, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_users']['title_users_tab']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_users']['title_users_tab']; ?>
</a></li>
			<li><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=search" title="<?php echo $this->_tpl_vars['locale']['admin_users']['title_search_tab']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_users']['title_search_tab']; ?>
</a></li>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=jatekoslista" title="<?php echo $this->_tpl_vars['locale']['admin_users']['title_search_tab']; ?>
">Játékoslista</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter">
		</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_email']; ?>
</th>
				<th>Név</th>
				<th class="last">Dátum</th>
			</tr>
			<?php $_from = $this->_tpl_vars['userlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><?php echo $this->_tpl_vars['data']['email']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['uname']; ?>
</td>
				<td class="last"><?php echo $this->_tpl_vars['data']['datum']; ?>
</td>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_users']['warning_no_users']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_users']['warning_no_users']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>