<?php /* Smarty version 2.6.16, created on 2013-04-10 19:39:18
         compiled from admin/users_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/users_list.tpl', 44, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_users']['title_users_tab']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_users']['title_users_tab']; ?>
</a></li>
			<li><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=search" title="<?php echo $this->_tpl_vars['locale']['admin_users']['title_search_tab']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_users']['title_search_tab']; ?>
</a></li>
			<li><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=jatekoslista" title="<?php echo $this->_tpl_vars['locale']['admin_users']['title_search_tab']; ?>
">Játékoslista</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_users']['field_orderby']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_name']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_username']; ?>
</option>
					<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_email']; ?>
</option>
					<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_deleted']; ?>
</option>
					<option value="5" <?php echo $this->_tpl_vars['fieldselect5']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_public']; ?>
</option>
					<option value="6" <?php echo $this->_tpl_vars['fieldselect6']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_publicmail']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_users']['field_adminby']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_orderasc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_users']['field_orderdesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_users']['field_order']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_users']['field_submitorder']; ?>
" class="submit_filter">
			</form>
		</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_username']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_email']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_deleted']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_public']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_publicmail']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['userlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i><?php echo $this->_tpl_vars['locale']['admin_users']['field_list_tooltip']; ?>
</i></u><br><?php echo $this->_tpl_vars['data']['grouplist']; ?>
')"><?php echo $this->_tpl_vars['data']['uname']; ?>
</a></td>
				<td><?php echo $this->_tpl_vars['data']['username']; ?>
</td><td><?php echo $this->_tpl_vars['data']['umail']; ?>
</td><td><?php echo $this->_tpl_vars['data']['udel']; ?>
</td><td><?php echo $this->_tpl_vars['data']['upub']; ?>
</td><td><?php echo $this->_tpl_vars['data']['upubmail']; ?>
</td>
				<td class="last">
					<?php if ($this->_tpl_vars['data']['uact'] == 1): ?>
						<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_users']['field_list_inactivate']; ?>
"></a>
					<?php else: ?>
						<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_users']['field_list_activate']; ?>
"></a>
					<?php endif; ?>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=mod&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_users']['field_list_modify']; ?>
"></a>
					<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_users']['confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=del&amp;uid=<?php echo $this->_tpl_vars['data']['uid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_users']['field_list_delete']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_users']['warning_no_users']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_users']['warning_no_users']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>