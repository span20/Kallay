<?php /* Smarty version 2.6.16, created on 2007-07-04 10:44:53
         compiled from admin/newsletter_sent_user_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/newsletter_sent_user_list.tpl', 34, false),)), $this); ?>
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
                <input type="hidden" name="sub_act" value="slst">
                <input type="hidden" name="nid" value="<?php echo $this->_tpl_vars['nid']; ?>
">
                <input type="hidden" name="did" value="<?php echo $_GET['did']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_by']; ?>

				<select name="field">
					<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_recipient']; ?>
</option>
					<option value="5" <?php echo $this->_tpl_vars['fieldselect5']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_email']; ?>
</option>
                    <option value="6" <?php echo $this->_tpl_vars['fieldselect6']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_senddate']; ?>
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

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_submit']; ?>
" class="submit_filter">
			</form>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_recipient']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_email']; ?>
</th>
                <th class="last"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_senddate']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['email']; ?>
</td>
                    <td class="last">
                        <?php if (empty ( $this->_tpl_vars['data']['sent_time'] )): ?>
                            <span style="color: green;"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_ongoing']; ?>
</span>
                        <?php else: ?>
                            <?php echo $this->_tpl_vars['data']['send_date']; ?>

                        <?php endif; ?>
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