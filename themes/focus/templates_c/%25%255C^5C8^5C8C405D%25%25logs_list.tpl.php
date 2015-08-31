<?php /* Smarty version 2.6.16, created on 2007-06-14 21:28:28
         compiled from admin/logs_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/logs_list.tpl', 36, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
" />
				<?php echo $this->_tpl_vars['locale']['admin_logs']['field_orderby']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_time']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_user']; ?>
</option>
					<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_username']; ?>
</option>
					<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_module']; ?>
</option>
					<option value="5" <?php echo $this->_tpl_vars['fieldselect5']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_function']; ?>
</option>
					<option value="6" <?php echo $this->_tpl_vars['fieldselect6']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_description']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_logs']['field_adminby']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_orderasc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_logs']['field_orderdesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_logs']['field_order']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_logs']['field_submitorder']; ?>
" class="submit_filter" />
			</form>
		</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_time']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_user']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_username']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_module']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_function']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_logs']['field_list_description']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['time']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['user_name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['module_name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['function_desc']; ?>
</td>
					<td class="last"><?php echo $this->_tpl_vars['data']['description']; ?>
</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_logs']['warning_no_logs']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_logs']['warning_no_logs']; ?>

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