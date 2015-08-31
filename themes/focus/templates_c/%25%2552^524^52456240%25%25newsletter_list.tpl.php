<?php /* Smarty version 2.6.16, created on 2007-06-08 11:15:25
         compiled from admin/newsletter_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/newsletter_list.tpl', 47, false),array('function', 'is_sent', 'admin/newsletter_list.tpl', 51, false),)), $this); ?>
<?php echo '
<script type="text/javascript">//<![CDATA[
function torol(nid)
{'; ?>

	x = confirm('<?php echo $this->_tpl_vars['locale']['admin_newsletter']['confirm_newsletter_del']; ?>
');<?php echo '
	if (x) {'; ?>

		document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&act=<?php echo $this->_tpl_vars['this_page']; ?>
&sub_act=del&nid='+nid
    <?php echo '}
}
//]]>
</script>
'; ?>


<div id="table">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
				<input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_filter_order_by']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_subject']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_add_date']; ?>
</option>
					<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_author']; ?>
</option>
                    <option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_id']; ?>
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
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_subject']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_add_date']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_author']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_send_counter']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['letter_field_act']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><?php echo $this->_tpl_vars['data']['subject']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['add_date']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['username']; ?>
</td>
				<td><?php echo is_sent(array('nid' => $this->_tpl_vars['data']['nid']), $this);?>
</td>
				<td class="last">
					<a class="action sendinfo" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=slst&amp;nid=<?php echo $this->_tpl_vars['data']['nid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['newsletter']['act_sendinfo']; ?>
"></a>
					<a class="action send" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=send&amp;nid=<?php echo $this->_tpl_vars['data']['nid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['newsletter']['act_send']; ?>
"></a>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;nid=<?php echo $this->_tpl_vars['data']['nid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['newsletter']['act_mod']; ?>
"></a>
					<a class="action del" href="javascript: torol(<?php echo $this->_tpl_vars['data']['nid']; ?>
);;" title="<?php echo $this->_tpl_vars['locale']['newsletter']['act_del']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
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