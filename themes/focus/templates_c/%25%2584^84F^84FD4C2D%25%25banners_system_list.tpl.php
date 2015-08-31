<?php /* Smarty version 2.6.16, created on 2007-06-29 16:20:07
         compiled from admin/banners_system_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/banners_system_list.tpl', 23, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=banners_system&amp;act=mod" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_title']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_title']; ?>
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
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_width']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_height']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_adduser']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_adddate']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_moduser']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_moddate']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['pname']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['pwidth']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['pheight']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['aname']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['adate']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['mname']; ?>
</td>
					<td><?php echo $this->_tpl_vars['data']['mdate']; ?>
</td>
					<td class="last">
						<a href="admin.php?p=banners_system&amp;act=mod&amp;type=mod&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_modify']; ?>
">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/modify.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_modify']; ?>
" />
						</a>
						<a href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_banners']['confirm_del_system_place']; ?>
')) document.location.href='admin.php?p=banners_system&amp;act=del&amp;type=del&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_del']; ?>
">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/delete.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_system_del']; ?>
" />
						</a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="8" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_banners']['warning_system_no_place']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_banners']['warning_system_no_place']; ?>

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