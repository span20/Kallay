<?php /* Smarty version 2.6.16, created on 2007-07-26 11:59:09
         compiled from admin/polls_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/polls_list.tpl', 24, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['title']; ?>
"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['title']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_question']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_start']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_end']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_adduser']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_adddate']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_startdate']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_enddate']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<?php echo $this->_tpl_vars['data']['answerlist']; ?>
')"><?php echo $this->_tpl_vars['data']['ptitle']; ?>
</a></td>
					<td><?php echo $this->_tpl_vars['data']['pstart']; ?>
</td><td><?php echo $this->_tpl_vars['data']['pend']; ?>
</td><td><?php echo $this->_tpl_vars['data']['add_name']; ?>
</td><td><?php echo $this->_tpl_vars['data']['add_date']; ?>
</td><td><?php echo $this->_tpl_vars['data']['start_date']; ?>
</td><td><?php echo $this->_tpl_vars['data']['end_date']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['pact']): ?>
							<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_inactivate']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_activate']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=mod&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_modify']; ?>
">
						</a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=del&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
';" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_delete']; ?>
">
						</a>
						<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=res&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_result']; ?>
">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/result.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_result']; ?>
" />
						</a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="8" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['warning_list_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['warning_list_empty']; ?>

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