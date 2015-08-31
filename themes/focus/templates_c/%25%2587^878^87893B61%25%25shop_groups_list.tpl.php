<?php /* Smarty version 2.6.16, created on 2007-07-13 10:21:44
         compiled from admin/shop_groups_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/shop_groups_list.tpl', 17, false),)), $this); ?>
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
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_add']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_add_date']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_mod']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_mod_date']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first">
						<?php $this->assign('flag', $this->_tpl_vars['data']['glang']); ?>
						<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
						<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['data']['glang']; ?>
" />
						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['glang']; ?>

						<?php endif; ?>
					</td>
					<td><?php echo $this->_tpl_vars['data']['gname']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['ausr']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['adate']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['musr']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['mdate']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['isact'] == 1): ?>
						  <a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;gid=<?php echo $this->_tpl_vars['data']['gid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_inactive']; ?>
"></a>
						<?php else: ?>
						  <a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;gid=<?php echo $this->_tpl_vars['data']['gid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_active']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;gid=<?php echo $this->_tpl_vars['data']['gid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;gid=<?php echo $this->_tpl_vars['data']['gid']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_field_list_del']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td colspan="7" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_shop']['groups_warning_empty']; ?>

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