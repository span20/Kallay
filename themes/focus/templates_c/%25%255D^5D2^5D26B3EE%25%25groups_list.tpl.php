<?php /* Smarty version 2.6.16, created on 2007-06-28 14:39:44
         compiled from admin/groups_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/groups_list.tpl', 34, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_title']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_title']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_groups']['field_orderby']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_name']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_deleted']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_groups']['field_adminby']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_groups']['field_orderasc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_groups']['field_orderdesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_groups']['field_order']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_groups']['field_submitorder']; ?>
" class="submit_filter">
			</form>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_deleted']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i><?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_users']; ?>
</i></u><br><?php echo $this->_tpl_vars['data']['userlist']; ?>
')"><?php echo $this->_tpl_vars['data']['gname']; ?>
</a></td>
					<td><?php echo $this->_tpl_vars['data']['gdel']; ?>
</td>
					<td class="last">
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=mod&amp;gid=<?php echo $this->_tpl_vars['data']['gid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_groups']['confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=del&amp;gid=<?php echo $this->_tpl_vars['data']['gid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_groups']['field_list_delete']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="3" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_groups']['warning_no_group']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_groups']['warning_no_group']; ?>

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