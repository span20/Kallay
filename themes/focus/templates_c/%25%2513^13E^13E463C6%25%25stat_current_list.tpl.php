<?php /* Smarty version 2.6.16, created on 2007-07-03 11:29:27
         compiled from admin/stat_current_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/stat_current_list.tpl', 16, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div class="t_filter"></div>
		<div class="pager"></div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_lastaccess']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_stat']['field_site']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_stat']['field_doctitle']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_stat']['field_docurl']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_stat']['field_host']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_referer']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['visitors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['last_access']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['site']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['document']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['document_url']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['host']; ?>
</td>
					<td class="last"><?php echo $this->_tpl_vars['data']['referer']; ?>
</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_active']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_active']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>