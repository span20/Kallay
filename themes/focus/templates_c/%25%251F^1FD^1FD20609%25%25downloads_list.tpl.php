<?php /* Smarty version 2.6.16, created on 2007-11-05 10:28:03
         compiled from admin/downloads_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/downloads_list.tpl', 29, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager">
			<div style="float: left; padding-left: 10px;">
				<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/dir.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['field_location']; ?>
" /> 
				<?php echo $this->_tpl_vars['dirlist']['0']['dir']; ?>

			</div>
			<div style="float: right; padding-right: 20px;">
				<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=0" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_titleroot']; ?>
">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/home.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_titleroot']; ?>
" />
				</a>&nbsp;&nbsp;
				<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['dirlist']['0']['parent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_titleup']; ?>
">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/dir_up.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_titleup']; ?>
" />
				</a>&nbsp;&nbsp;
				<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/totalsize.gif" border="0" alt="<?php echo $this->_tpl_vars['dirsumsize']; ?>
 KB" />
			</div>
		</div>
		<table style="clear: both;">
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_downloads']['list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_downloads']['list_size']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_downloads']['list_date']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_downloads']['list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['dirlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<?php if ($this->_tpl_vars['data']['up'] != ""): ?>
					<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
" <?php if ($this->_tpl_vars['data']['type'] == 'D'): ?>style="font-weight: bold;"<?php endif; ?>>
						<td class="first">
							<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['data']['parent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_titleup']; ?>
">
								<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/dir_up.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_titleup']; ?>
" /> ..
							</a>
						</td>
						<td>&lt;dir&gt;</td>
						<?php if ($this->_tpl_vars['data']['type'] == 'F'): ?>
							<td><?php echo $this->_tpl_vars['data']['size']; ?>
</td>
						<?php endif; ?>
						<td colspan="2"><?php echo $this->_tpl_vars['data']['add_date']; ?>
</td>
					</tr>
				<?php else: ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
" <?php if ($this->_tpl_vars['data']['type'] == 'D'): ?>style="font-weight: bold;"<?php endif; ?>>
					<td class="first">
						<?php if ($this->_tpl_vars['data']['type'] == 'D'): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/dir.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_dir']; ?>
" />
							<a <?php if ($this->_tpl_vars['data']['desc'] != ""): ?>onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<?php echo $this->_tpl_vars['data']['desc']; ?>
')"<?php endif; ?> href="admin.php?p=downloads&amp;parent=<?php echo $this->_tpl_vars['data']['did']; ?>
" title="<?php echo $this->_tpl_vars['data']['name']; ?>
"><b><?php echo $this->_tpl_vars['data']['name']; ?>
</b></a>
						<?php else: ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/file.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_file']; ?>
" />
							<a <?php if ($this->_tpl_vars['data']['desc'] != ""): ?>onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<?php echo $this->_tpl_vars['data']['desc']; ?>
')"<?php endif; ?>><?php echo $this->_tpl_vars['data']['name']; ?>
</a>
						<?php endif; ?>
					</td>
					<td>
					<?php if ($this->_tpl_vars['data']['type'] == 'D'): ?>
						&lt;dir&gt;
					<?php else: ?>
						<?php echo $this->_tpl_vars['data']['size']; ?>
 KB
					<?php endif; ?>
					</td>
					<td><?php echo $this->_tpl_vars['data']['add_date']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['is_act'] == 1): ?>
							<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;&amp;did=<?php echo $this->_tpl_vars['data']['did']; ?>
parent=<?php echo $this->_tpl_vars['data']['parent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_inactive']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;did=<?php echo $this->_tpl_vars['data']['did']; ?>
&amp;parent=<?php echo $this->_tpl_vars['data']['parent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_active']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;did=<?php echo $this->_tpl_vars['data']['did']; ?>
&amp;parent=<?php echo $this->_tpl_vars['data']['parent']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_downloads']['confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;did=<?php echo $this->_tpl_vars['data']['did']; ?>
&amp;parent=<?php echo $this->_tpl_vars['data']['parent']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_downloads']['list_delete']; ?>
"></a>
					</td>
				</tr>
				<?php endif; ?>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['warning_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_downloads']['warning_empty']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager">&nbsp;</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>