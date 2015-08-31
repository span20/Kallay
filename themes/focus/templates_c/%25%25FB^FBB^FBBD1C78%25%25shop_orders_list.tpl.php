<?php /* Smarty version 2.6.16, created on 2007-07-13 10:24:04
         compiled from admin/shop_orders_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/shop_orders_list.tpl', 44, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
			<div style="float: left;">
				<form action="admin.php" method="get">
					<input type="hidden" name="p" value="shop">
					<input type="hidden" name="act" value="ord">
					<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_orderby']; ?>

					<select name="field_order">
						<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_id2']; ?>
</option>
						<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_name']; ?>
</option>
						<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_date']; ?>
</option>
						<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_shipping']; ?>
</option>
					</select>
					<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_adminby']; ?>

					<select name="ord">
						<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_asc']; ?>
</option>
						<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_desc']; ?>
</option>
					</select>
					<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_order']; ?>

					<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_filter']; ?>
" class="submit_filter">
				</form>
			</div>
					</div>
		<div class="pager" style="clear: both;"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_id2']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_date']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_shipping']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['oid']; ?>
</td>
					<td>
						<?php if ($this->_tpl_vars['data']['uname']):  echo $this->_tpl_vars['data']['uname'];  endif; ?>
						<?php if ($this->_tpl_vars['data']['nuname']):  echo $this->_tpl_vars['data']['nuname'];  endif; ?>
					</td>
					<td><?php echo $this->_tpl_vars['data']['odate']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['stext']; ?>
</td>
					<td class="last">
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;oid=<?php echo $this->_tpl_vars['data']['oid']; ?>
&amp;field_order=<?php echo $_GET['field_order']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;usr_fil=<?php echo $_GET['usr_fil']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_field_list_mod']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="5" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_shop']['orders_warning_empty']; ?>

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