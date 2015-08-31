<?php /* Smarty version 2.6.16, created on 2007-06-28 14:39:50
         compiled from admin/builder_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'admin/builder_list.tpl', 61, false),)), $this); ?>
<div><?php if ($this->_tpl_vars['message_string']): ?><p style="text-align:center;color:green;"><?php echo $this->_tpl_vars['message_string']; ?>
</p><?php endif; ?></div>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['title_builder_tab']; ?>
"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['title_builder_tab']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div id="pager"></div>
			<div style="float: left; width: 98%;">
				<?php $_from = $this->_tpl_vars['c']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cols'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cols']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['columns']):
        $this->_foreach['cols']['iteration']++;
?>
					 <div style="width: <?php echo $this->_tpl_vars['colwidth'][($this->_foreach['cols']['iteration']-1)];  echo $_SESSION['site_builder_columns_measure']; ?>
; float: left;">
					 	<?php $_from = $this->_tpl_vars['columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['boxs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['boxs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['boxes']):
        $this->_foreach['boxs']['iteration']++;
?>
						<div style="width: 100%; float: left; clear: both; height: 100px; border: 1px solid;">
							<div style="border-bottom: 1px solid;">
						    	<?php echo $this->_foreach['boxs']['iteration']; ?>
.
								<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_modify']; ?>
">
            				    	<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/modify.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_modify']; ?>
" />
								</a>
								<a href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['confirm_del_box']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
';" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_delete']; ?>
">
            				    	<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/delete.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_delete']; ?>
" />
								</a>
								<?php if ($this->_foreach['boxs']['total'] > '1'): ?>
									<?php if (($this->_foreach['boxs']['iteration'] <= 1)): ?>
										<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=pos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=down" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_waydown']; ?>
">
										<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/down.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_waydown']; ?>
" />
									</a>
									<?php elseif (($this->_foreach['boxs']['iteration'] == $this->_foreach['boxs']['total'])): ?>
										<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=pos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=up" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
">
										<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/up.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
" />
									</a>
									<?php else: ?>
										<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=pos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=up" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
">
										<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/up.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
" />
									</a>
									<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=pos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=down" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_waydown']; ?>
">
										<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/down.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_waydown']; ?>
" />
									</a>
									<?php endif; ?>
								<?php endif; ?>
								<?php if (($this->_foreach['cols']['iteration'] <= 1)): ?>
									<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=colpos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=right" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
">
									<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/right_blue.gif" border="0">
									</a>
								<?php elseif (($this->_foreach['cols']['iteration'] == $this->_foreach['cols']['total'])): ?>
									<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=colpos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=left" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
">
									<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/left_blue.gif" border="0">
									</a>
								<?php else: ?>
									<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=colpos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=left" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
">
									<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/left_blue.gif" border="0">
									</a>
									<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=colpos&amp;bid=<?php echo $this->_tpl_vars['key']; ?>
&amp;way=right" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_list_wayup']; ?>
">
									<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/right_blue.gif" border="0">
									</a>
								<?php endif; ?>
							</div>
							<?php $_from = $this->_tpl_vars['boxes']['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['conts'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['conts']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['contents']):
        $this->_foreach['conts']['iteration']++;
?>
							<div style="width: <?php echo smarty_function_math(array('equation' => "x/y",'x' => 100,'y' => $this->_foreach['conts']['total']), $this);?>
%; float: left;">
								<?php if ($this->_tpl_vars['contents']['menu_pos']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_menu']; ?>
</b>
    							<?php endif; ?>
    							<?php if ($this->_tpl_vars['contents']['content_id']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_cont']; ?>
</b>
    							<?php endif; ?>
    							<?php if ($this->_tpl_vars['contents']['category_id']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_category']; ?>
</b>
    							<?php endif; ?>
    							<?php if ($this->_tpl_vars['contents']['block']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_block']; ?>
 (<?php echo $this->_tpl_vars['contents']['block']; ?>
)</b>
    							<?php endif; ?>
    							<?php if ($this->_tpl_vars['contents']['module_id']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_module']; ?>
</b>
    							<?php endif; ?>
    							<?php if ($this->_tpl_vars['contents']['banner_pos']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_banner']; ?>
</b>
    							<?php endif; ?>
    							<?php if ($this->_tpl_vars['contents']['gallery_id']): ?>
    								<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_content']; ?>
:
    								<b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_builder_list_gallery']; ?>
</b>
    							<?php endif; ?>
							</div>
							<?php endforeach; endif; unset($_from); ?>
						</div>
						<?php endforeach; endif; unset($_from); ?>
					 </div>
				<?php endforeach; else: ?>
				<p class="empty" style="clear:left;">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['warning_no_banners']; ?>
" />
					<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['warning_no_data']; ?>

				</p>
				<?php endif; unset($_from); ?>
			</div>
		<div id="pager"></div>
	</div>
	<div id="t_bottom"></div>
</div>