<?php /* Smarty version 2.6.16, created on 2007-06-19 15:33:13
         compiled from admin/banners_owner_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/banners_owner_list.tpl', 25, false),)), $this); ?>
<script type="text/javascript">//<![CDATA[
function torol(nid) <?php echo ' { '; ?>

	x = confirm('<?php echo $this->_tpl_vars['locale']['admin_banners']['confirm_del_owner']; ?>
');
	if (x) <?php echo ' { '; ?>

		document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&act=<?php echo $this->_tpl_vars['this_page']; ?>
&sub_act=odel&oid='+nid
	<?php echo ' }
} '; ?>

//]]>
</script>

<div id="table">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_contact']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_email']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_phone']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><?php echo $this->_tpl_vars['data']['owner_name']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['kapcs_tarto']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['email']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['telefon']; ?>
</td>
				<td class="last">
					<a class="action" style="background: url(<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/bannersmod.gif) no-repeat top left;" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=blst&amp;oid=<?php echo $this->_tpl_vars['data']['owner_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_banners']; ?>
"></a>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=omod&amp;oid=<?php echo $this->_tpl_vars['data']['owner_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_modify']; ?>
"></a>
					<a class="action del" href="javascript: torol(<?php echo $this->_tpl_vars['data']['owner_id']; ?>
);" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_owner_delete']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="5" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_banners']['warning_no_owners']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_banners']['warning_no_owners']; ?>

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