<?php /* Smarty version 2.6.16, created on 2007-06-19 11:21:13
         compiled from admin/menus_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/menus_list.tpl', 26, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li<?php if ($this->_tpl_vars['menuType'] == 'index'): ?> id="current"<?php endif; ?>><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['title_index_tab']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_menus']['title_index_tab']; ?>
</a></li>
			<?php if ($this->_tpl_vars['is_admin'] == 1): ?>
				<li<?php if ($this->_tpl_vars['menuType'] == 'admin'): ?> id="current"<?php endif; ?>><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;menutype=admin" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['title_admin_tab']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_menus']['title_admin_tab']; ?>
</a></li>
			<?php endif; ?>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter">&nbsp;</div>
		<div class="pager">&nbsp;</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_protected']; ?>
</th>
				<th></th>
				<th><?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_module']; ?>
</th>
				<?php if ($this->_tpl_vars['menuType'] == 'index'): ?><th><?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_position']; ?>
</th><?php endif; ?>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_action']; ?>
</th>
			</tr>
		<?php if (!function_exists('smarty_fun_menu')) { function smarty_fun_menu(&$this, $params) { $_fun_tpl_vars = $this->_tpl_vars; $this->assign($params);  ?>
			<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first">
					<?php $this->assign('flag', $this->_tpl_vars['data']['mlang']); ?>
					<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
					<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['data']['mlang']; ?>
" />
					<?php else: ?>
						<?php echo $this->_tpl_vars['data']['mlang']; ?>

					<?php endif; ?>
				</td>
				<td><?php echo $this->_tpl_vars['data']['mprot']; ?>
</td>
				<td>
				<?php if ($this->_tpl_vars['data']['mtype'] == 'index'): ?>
					<?php if ($this->_tpl_vars['data']['is_sub'] == '1'): ?>
						<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&mid=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
" style="font-size: 14px; font-weight: bold"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/arrow_down.gif" border="0" alt=""></a>
					<?php endif; ?>
				<?php endif; ?>
				</td>
				<td <?php if ($this->_tpl_vars['data']['level'] > 1): ?>style="padding-left: <?php echo $this->_tpl_vars['data']['level']*10; ?>
px;"<?php endif; ?>>
					<?php echo $this->_tpl_vars['data']['menu_name']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['data']['moname'] != ""): ?>
						<?php if ($this->_tpl_vars['data']['mtype'] == 'admin'): ?>
							<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_adminmodule']; ?>

						<?php else: ?>
							<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_indexmodule']; ?>

						<?php endif; ?>
						<?php echo $this->_tpl_vars['data']['moname']; ?>

					<?php elseif ($this->_tpl_vars['data']['ctitle'] != ""): ?>
						 <?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_content']; ?>
 <?php echo $this->_tpl_vars['data']['ctitle']; ?>

					<?php elseif ($this->_tpl_vars['data']['catname'] != ""): ?>
						 <?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_category']; ?>
 <?php echo $this->_tpl_vars['data']['catname']; ?>

					<?php else: ?>
						<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_outerlink']; ?>
 <?php echo $this->_tpl_vars['data']['mlink']; ?>

					<?php endif; ?>
				</td>
				<?php if ($this->_tpl_vars['menuType'] == 'index'): ?><td><?php echo $this->_tpl_vars['data']['posname']; ?>
</td><?php endif; ?>
				<td class="last">
					<?php if ($this->_tpl_vars['data']['isact'] == 1): ?>
					<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;m_id=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;type=<?php echo $_GET['type']; ?>
&amp;lang=<?php echo $_GET['lang']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_inactivate']; ?>
"></a>
					<?php else: ?>
					<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;m_id=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;type=<?php echo $_GET['type']; ?>
&amp;lang=<?php echo $_GET['lang']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_activate']; ?>
"></a>
					<?php endif; ?>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=mod&amp;m_id=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;type=<?php echo $_GET['type']; ?>
&amp;lang=<?php echo $_GET['lang']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_modify']; ?>
"></a>
					<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_menus']['confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=del&amp;m_id=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;type=<?php echo $_GET['type']; ?>
&amp;lang=<?php echo $_GET['lang']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_delete']; ?>
"></a>
					<?php if ($this->_tpl_vars['menuType'] == 'index'): ?>
					<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=add&amp;par=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;pos=<?php echo $this->_tpl_vars['data']['posid']; ?>
&amp;type=<?php echo $_GET['type']; ?>
&amp;lang=<?php echo $_GET['lang']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_submenu']; ?>
">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/submenu.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_submenu']; ?>
" />
					</a>
					<?php endif; ?>
					<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=ord&amp;m_id=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;way=up&amp;par=<?php echo $this->_tpl_vars['data']['parent']; ?>
&amp;type=<?php echo $this->_tpl_vars['data']['mtype']; ?>
&amp;ordt=<?php echo $_GET['ordt']; ?>
&amp;ordl=<?php echo $_GET['ordl']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_wayup']; ?>
">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/up.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_wayup']; ?>
" />
					</a>
					<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=ord&amp;m_id=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
&amp;way=down&amp;par=<?php echo $this->_tpl_vars['data']['parent']; ?>
&amp;type=<?php echo $this->_tpl_vars['data']['mtype']; ?>
&amp;ordt=<?php echo $_GET['ordt']; ?>
&amp;ordl=<?php echo $_GET['ordl']; ?>
&amp;menutype=<?php echo $this->_tpl_vars['menuType']; ?>
&amp;mid=<?php echo $_GET['mid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_waydown']; ?>
">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/down.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_menus']['field_list_waydown']; ?>
" />
					</a>
				</td>
			</tr>
			<?php if ($this->_tpl_vars['data']['element']): ?>
				<?php smarty_fun_menu($this, array('list'=>$this->_tpl_vars['data']['element']));  ?>
			<?php endif; ?>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="<?php if ($this->_tpl_vars['menuType'] == 'index'): ?>7<?php else: ?>6<?php endif; ?>" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_menus']['warning_no_menu']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_menus']['warning_no_menu']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		<?php  $this->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_menu($this, array('list'=>$this->_tpl_vars['sitemenu']));  ?>
		</table>
		<div class="pager">&nbsp;</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>