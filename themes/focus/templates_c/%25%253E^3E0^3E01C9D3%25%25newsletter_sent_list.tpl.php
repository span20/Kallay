<?php /* Smarty version 2.6.16, created on 2007-07-04 10:40:17
         compiled from admin/newsletter_sent_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/newsletter_sent_list.tpl', 34, false),)), $this); ?>
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
                <input type="hidden" name="sub_act" value="slst">
                <input type="hidden" name="nid" value="<?php echo $this->_tpl_vars['nid']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_by']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_send_date']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_sender']; ?>
</option>
                    <option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_sendermail']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_by']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_asc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_desc']; ?>
</option>
				</select>
                <?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_tpl_order']; ?>

				<input type="submit" name="sbmt" value="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_submit']; ?>
" class="submit_filter">
			</form>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_send_date']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_sender']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_sendermail']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_act']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['date']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['user']; ?>
</td>
                    <td><?php echo $this->_tpl_vars['data']['sendermail']; ?>
</td>
					<td class="last">
						<a class="action sendlist" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=slst&amp;nid=<?php echo $this->_tpl_vars['nid']; ?>
&amp;did=<?php echo $this->_tpl_vars['data']['date_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['act_recipients']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="3" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="error" />
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