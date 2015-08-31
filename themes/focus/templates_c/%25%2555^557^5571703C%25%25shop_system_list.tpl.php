<?php /* Smarty version 2.6.16, created on 2007-07-03 13:36:07
         compiled from admin/shop_system_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/shop_system_list.tpl', 19, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=shop_system&amp;act=mod" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_title']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_title']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_value']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_type']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_display']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['pvalue']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['ptype']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['pdisplay']; ?>
</td>
					<td class="last">
						<a class="action mod" href="admin.php?p=shop_system&amp;act=mod&amp;type=mod&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_delconf']; ?>
')) document.location.href='admin.php?p=shop_system&amp;act=del&amp;type=del&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['system_list_deletel']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_shop']['system_warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_shop']['system_warning_empty']; ?>

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