<?php /* Smarty version 2.6.16, created on 2015-06-12 12:31:20
         compiled from admin/dynamic_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/dynamic_list.tpl', 23, false),)), $this); ?>
<div id="table">
<?php if (! isset ( $this->_tpl_vars['id'] )):  $this->assign('id', 'id');  endif; ?>
	<?php if ($this->_tpl_vars['dynamic_tabs']): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<div class="tabs">
			<ul>
				<li class="current"><a href="#">...</a></li>
			</ul>
			<div class="blueleft"></div><div class="blueright"></div>
		</div>
	<?php endif; ?>
	<div class="t_content">
		<div class="t_filter"><?php if (isset ( $this->_tpl_vars['lang_title'] )): ?><h2 style="margin:0;"><?php echo $this->_tpl_vars['lang_title']; ?>
</h2><?php endif; ?></div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
			  <?php $_from = $this->_tpl_vars['table_headers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ths'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ths']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['thkey'] => $this->_tpl_vars['th']):
        $this->_foreach['ths']['iteration']++;
?>
				<th<?php if (($this->_foreach['ths']['iteration'] <= 1)): ?> class="first"<?php elseif (($this->_foreach['ths']['iteration'] == $this->_foreach['ths']['total'])): ?> class="last"<?php $this->assign('columnCount', $this->_foreach['ths']['total']);  endif; ?>><?php echo $this->_tpl_vars['th']; ?>
</th>
			  <?php endforeach; endif; unset($_from); ?>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listItem']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<?php $_from = $this->_tpl_vars['table_headers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tds'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tds']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['itemKey'] => $this->_tpl_vars['item']):
        $this->_foreach['tds']['iteration']++;
?>
				<?php if ($this->_tpl_vars['itemKey'] != '__act__' && $this->_tpl_vars['itemKey'] != '__lang__'): ?>
					<td<?php if (($this->_foreach['tds']['iteration'] <= 1)): ?> class="first"<?php elseif (($this->_foreach['tds']['iteration'] == $this->_foreach['tds']['total'])): ?> class="last"<?php endif; ?>><?php echo $this->_tpl_vars['listItem'][$this->_tpl_vars['itemKey']]; ?>
</td>
				<?php else: ?>
					<?php if ($this->_tpl_vars['itemKey'] == '__act__'): ?>
						<td<?php if (($this->_foreach['tds']['iteration'] <= 1)): ?> class="first"<?php elseif (($this->_foreach['tds']['iteration'] == $this->_foreach['tds']['total'])): ?> class="last"<?php endif; ?>>
						<?php $_from = $this->_tpl_vars['actions_dynamic']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['actionCode'] => $this->_tpl_vars['actionItem']):
?>
							<?php if ($this->_tpl_vars['actionCode'] == 'del'): ?>
								<a class="action <?php echo $this->_tpl_vars['actionCode']; ?>
" href="javascript: if (confirm('<?php echo $this->_tpl_vars['lang_dynamic']['strAdminConfirm']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;<?php echo $this->_tpl_vars['id']; ?>
=<?php echo $this->_tpl_vars['listItem'][$this->_tpl_vars['id']]; ?>
&amp;<?php echo $this->_tpl_vars['link_additional']; ?>
';" title="<?php echo $this->_tpl_vars['actionItem']; ?>
"><span><?php echo $this->_tpl_vars['actionItem']; ?>
</span></a>
							<?php elseif ($this->_tpl_vars['actionCode'] == 'act'): ?>
								<?php if ($this->_tpl_vars['listItem']['is_active'] == 1): ?> 
									<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=<?php echo $this->_tpl_vars['actionCode']; ?>
&amp;<?php echo $this->_tpl_vars['id']; ?>
=<?php echo $this->_tpl_vars['listItem'][$this->_tpl_vars['id']]; ?>
&amp;<?php echo $this->_tpl_vars['link_additional']; ?>
" title="<?php echo $this->_tpl_vars['actionItem']['1']; ?>
"><span><?php echo $this->_tpl_vars['actionItem']['1']; ?>
</span></a>
								<?php else: ?>
									<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=<?php echo $this->_tpl_vars['actionCode']; ?>
&amp;<?php echo $this->_tpl_vars['id']; ?>
=<?php echo $this->_tpl_vars['listItem'][$this->_tpl_vars['id']]; ?>
&amp;<?php echo $this->_tpl_vars['link_additional']; ?>
" title="<?php echo $this->_tpl_vars['actionItem']['0']; ?>
"><span><?php echo $this->_tpl_vars['actionItem']['0']; ?>
</span></a>
								<?php endif; ?>
							<?php elseif ($this->_tpl_vars['actionCode'] == w_lst): ?>
								<a class="action langlist" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=<?php echo $this->_tpl_vars['actionCode']; ?>
&amp;<?php echo $this->_tpl_vars['id']; ?>
=<?php echo $this->_tpl_vars['listItem'][$this->_tpl_vars['id']]; ?>
&amp;<?php echo $this->_tpl_vars['link_additional']; ?>
" title="<?php echo $this->_tpl_vars['actionItem']; ?>
"><span><?php echo $this->_tpl_vars['actionItem']; ?>
</span></a>
							<?php else: ?>
								<a class="action <?php echo $this->_tpl_vars['actionCode']; ?>
" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=<?php echo $this->_tpl_vars['actionCode']; ?>
&amp;<?php echo $this->_tpl_vars['id']; ?>
=<?php echo $this->_tpl_vars['listItem'][$this->_tpl_vars['id']]; ?>
&amp;<?php echo $this->_tpl_vars['link_additional']; ?>
" title="<?php echo $this->_tpl_vars['actionItem']; ?>
"><span><?php echo $this->_tpl_vars['actionItem']; ?>
</span></a>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						</td>
					<?php else: ?>
						<td<?php if (($this->_foreach['tds']['iteration'] <= 1)): ?> class="first"<?php elseif (($this->_foreach['tds']['iteration'] == $this->_foreach['tds']['total'])): ?> class="last"<?php endif; ?>>
						<?php $this->assign('flag', $this->_tpl_vars['listItem']['lang']); ?>
						<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
						<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['listItem']['lang']; ?>
" />
						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['clang']; ?>

						<?php endif; ?>
						</td>
					<?php endif; ?>
				<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="<?php echo $this->_tpl_vars['columnCount']; ?>
" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['lang_dynamic']['strAdminEmpty']; ?>
" />
						<?php echo $this->_tpl_vars['lang_dynamic']['strAdminEmpty']; ?>

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