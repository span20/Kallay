<?php /* Smarty version 2.6.16, created on 2015-06-15 16:01:20
         compiled from admin/contents_category_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/contents_category_list.tpl', 37, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
				<input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_orderby']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_name']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_adduser']; ?>
</option>
					<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_adddate']; ?>
</option>
					<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_moduser']; ?>
</option>
					<option value="5" <?php echo $this->_tpl_vars['fieldselect5']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_moddate']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_adminby']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_orderasc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_orderdesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_order']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_submitorder']; ?>
" class="submit_filter">
			</form>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_adduser']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_adddate']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_moduser']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_moddate']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
" alt="<?php echo $this->_tpl_vars['data']['mlang']; ?>
" />
					<?php else: ?>
						<?php echo $this->_tpl_vars['data']['mlang']; ?>

					<?php endif; ?>
				</td>
					<td><?php echo $this->_tpl_vars['data']['cname']; ?>
</td><td><?php echo $this->_tpl_vars['data']['add_name']; ?>
</td><td><?php echo $this->_tpl_vars['data']['add_date']; ?>
</td><td><?php echo $this->_tpl_vars['data']['mod_name']; ?>
</td><td><?php echo $this->_tpl_vars['data']['mod_date']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['cact'] == 1): ?>
							<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_inactivate']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_activate']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_list_delete']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_warning_no_category']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_contents']['category_tpl_warning_no_category']; ?>

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