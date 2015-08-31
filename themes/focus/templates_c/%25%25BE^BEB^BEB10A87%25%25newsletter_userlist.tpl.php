<?php /* Smarty version 2.6.16, created on 2007-06-27 16:36:23
         compiled from admin/newsletter_userlist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/newsletter_userlist.tpl', 31, false),)), $this); ?>
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
">
				<input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
                <input type="hidden" name="sub_act" value="lst">
				<?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_filter_order_by']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_field_name']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_field_email']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_filter_by']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_filter_order_asc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_filter_order_desc']; ?>
</option>
				</select>
                <?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_tpl_order']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_filter_order_submit']; ?>
" class="submit_filter">
			</form>
		</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_field_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_field_email']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_field_act']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i><?php echo $this->_tpl_vars['locale']['admin_newsletter']['users_field_tooltip']; ?>
</i></u><br><?php echo $this->_tpl_vars['data']['grouplist']; ?>
')"><?php echo $this->_tpl_vars['data']['name']; ?>
</a></td>
                <td><?php echo $this->_tpl_vars['data']['email']; ?>
</td>
				<td class="last">
					<?php if ($this->_tpl_vars['data']['is_active'] == '1'): ?>
					   <a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['act_inact']; ?>
"></a>
					<?php else: ?>
						<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['act_act']; ?>
"></a>
					<?php endif; ?>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['act_mod']; ?>
"></a>
					<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_newsletter']['confirm_user_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['act_del']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="3" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['warning_list_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_newsletter']['warning_list_empty']; ?>

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