<?php /* Smarty version 2.6.16, created on 2007-12-12 14:52:39
         compiled from admin/fields_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/fields_list.tpl', 14, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_fieldname']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_fieldtype']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_fieldaction']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['fname']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['ftype']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['factive'] == '1'): ?>
							<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=fact&amp;field_id=<?php echo $this->_tpl_vars['data']['field_id']; ?>
&amp;form_id=<?php echo $_GET['id']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_activate']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=fact&amp;field_id=<?php echo $this->_tpl_vars['data']['field_id']; ?>
&amp;form_id=<?php echo $_GET['id']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_deactivate']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=fmod&amp;field_id=<?php echo $this->_tpl_vars['data']['field_id']; ?>
&amp;form_id=<?php echo $_GET['id']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_forms']['confirm_fielddel']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=fdel&amp;field_id=<?php echo $this->_tpl_vars['data']['field_id']; ?>
&amp;form_id=<?php echo $_GET['id']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_forms']['field_list_delete']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="3" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_forms']['warning_no_group']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_forms']['warning_no_fields']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>