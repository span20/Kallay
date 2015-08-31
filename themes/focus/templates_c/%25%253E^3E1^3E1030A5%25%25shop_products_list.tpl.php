<?php /* Smarty version 2.6.16, created on 2007-07-13 10:15:54
         compiled from admin/shop_products_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/shop_products_list.tpl', 49, false),)), $this); ?>
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
					<input type="hidden" name="p" value="shop" />
					<input type="hidden" name="act" value="products" />
					<input type="hidden" name="cat_fil" value="<?php echo $_GET['cat_fil']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_orderby']; ?>

					<select name="field">
						<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_name']; ?>
</option>
						<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_add']; ?>
</option>
						<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_add_date']; ?>
</option>
						<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_mod']; ?>
</option>
						<option value="5" <?php echo $this->_tpl_vars['fieldselect5']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_mod_date']; ?>
</option>
					</select>
					<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_adminby']; ?>

					<select name="ord">
						<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_asc']; ?>
</option>
						<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_desc']; ?>
</option>
					</select>
					<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_order']; ?>

					<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_submit_filter']; ?>
" class="submit_filter">
				</form>
			</div>
			<div style="float: right; padding-right: 5px;">
				<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_filter']; ?>

					<select name="cat_filter" onchange="window.location='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;cat_fil='+this.value;">
					<?php $_from = $this->_tpl_vars['katok']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['cats']):
?>
						<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php echo $this->_tpl_vars['catselect'][$this->_tpl_vars['key']]; ?>
><?php echo $this->_tpl_vars['cats']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
			</div>
		</div>
		<div class="pager" style="clear: both;"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_item']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_add']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_add_date']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_mod']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_mod_date']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first">
						<?php $this->assign('flag', $this->_tpl_vars['data']['plang']); ?>
						<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
						<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['data']['plang']; ?>
" />
						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['plang']; ?>

						<?php endif; ?>
						<?php if ($this->_tpl_vars['data']['ispref'] == 1): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/preferred.gif" alt="" />
						<?php endif; ?>
					</td>
					<td><?php echo $this->_tpl_vars['data']['item']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['pname']; ?>
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
&amp;sub_act=act&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
&amp;cat_fil=<?php echo $_GET['cat_fil']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_inactive']; ?>
"></a>
						<?php else: ?>
                            <a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
&amp;cat_fil=<?php echo $_GET['cat_fil']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_active']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
&amp;cat_fil=<?php echo $_GET['cat_fil']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_shop']['products_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;cat_fil=<?php echo $_GET['cat_fil']; ?>
&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_del']; ?>
"></a>
						<?php if ($_SESSION['site_shop_ordertype'] == 2): ?>
                            <a class="action up" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=ord&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
&amp;cat_fil=<?php echo $_GET['cat_fil']; ?>
&amp;way=up&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_way_up']; ?>
"></a>
                            <a class="action down" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=ord&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
&amp;cat_fil=<?php echo $_GET['cat_fil']; ?>
&amp;way=down&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_field_list_way_down']; ?>
"></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="7" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_shop']['products_warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_shop']['products_warning_empty']; ?>

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