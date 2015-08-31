<?php /* Smarty version 2.6.16, created on 2007-07-13 10:23:27
         compiled from admin/shop_actions_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/shop_actions_list.tpl', 16, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_shop']['action_field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_timerstart']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_timerend']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_mod']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_date']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_actions']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['aname']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['astart']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['aend']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['musr']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['mdate']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['isact'] == 1): ?>
						  <a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;aid=<?php echo $this->_tpl_vars['data']['aid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_inactive']; ?>
"></a>
						<?php else: ?>
						  <a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;aid=<?php echo $this->_tpl_vars['data']['aid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_active']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;aid=<?php echo $this->_tpl_vars['data']['aid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;aid=<?php echo $this->_tpl_vars['data']['aid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_field_list_del']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_shop']['actions_warning_empty']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>