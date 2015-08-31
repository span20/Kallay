<?php /* Smarty version 2.6.16, created on 2007-06-14 23:53:44
         compiled from admin/shop_categories_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/shop_categories_list.tpl', 18, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager"></div>
		<table>
			<tr>
				<th class="first" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_add']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_add_date']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_mod']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_mod_date']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_action']; ?>
</th>
			</tr>
			<?php if (!function_exists('smarty_fun_menu')) { function smarty_fun_menu(&$this, $params) { $_fun_tpl_vars = $this->_tpl_vars; $this->assign($params);  ?>
			<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first">
						<?php $this->assign('flag', $this->_tpl_vars['data']['clang']); ?>
						<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
						<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['data']['clang']; ?>
" />
						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['clang']; ?>

						<?php endif; ?>
					</td>
					<td>
						<?php if ($this->_tpl_vars['data']['is_sub'] == '1'): ?>
							<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
" style="font-size: 14px; font-weight: bold"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/arrow_down.gif" border="0" alt=""></a>
						<?php endif; ?>
					</td>
					<td <?php if ($this->_tpl_vars['data']['level'] != 0): ?>style="padding-left: <?php echo $this->_tpl_vars['data']['level']*10; ?>
px;"<?php endif; ?>>
						<?php echo $this->_tpl_vars['data']['title']; ?>

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
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_inactive']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_active']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;par=<?php echo $this->_tpl_vars['data']['cparent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_shop']['category_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_delete']; ?>
"></a>
						<?php if ($this->_tpl_vars['add_new']): ?>
							<a class="action submenu" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=add&amp;par=<?php echo $this->_tpl_vars['data']['cid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_subcat']; ?>
"></a>
						<?php endif; ?>
						<?php if ($_SESSION['site_shop_ordertype'] == 2): ?>
						<a class="action up" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=ord&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;way=up&amp;par=<?php echo $this->_tpl_vars['data']['cparent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_way_up']; ?>
"></a>
						<a class="action down" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=ord&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;way=down&amp;par=<?php echo $this->_tpl_vars['data']['cparent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_way_down']; ?>
"></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php if ($this->_tpl_vars['data']['element']): ?>
				<?php smarty_fun_menu($this, array('list'=>$this->_tpl_vars['data']['element']));  ?>
			<?php endif; ?>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="8" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_shop']['category_warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_shop']['category_warning_empty']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
			<?php  $this->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_menu($this, array('list'=>$this->_tpl_vars['category_list']));  ?>
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>