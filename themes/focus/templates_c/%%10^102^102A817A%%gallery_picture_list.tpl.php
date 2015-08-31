<?php /* Smarty version 2.6.16, created on 2015-06-12 17:19:15
         compiled from admin/gallery_picture_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/gallery_picture_list.tpl', 31, false),array('modifier', 'escape', 'admin/gallery_picture_list.tpl', 32, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div class="t_filter">
            <form action="admin.php" method="get">
                <input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
				<input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
                <?php echo $this->_tpl_vars['locale']['admin_gallery']['list_orderby']; ?>

                <select name="field">
                    <option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_name']; ?>
</option>
                    <option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_adddate']; ?>
</option>
                </select>
                <?php echo $this->_tpl_vars['locale']['admin_gallery']['list_adminby']; ?>

                <select name="ord">
                    <option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_orderasc']; ?>
</option>
                    <option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_orderdesc']; ?>
</option>
                </select>
                <?php echo $this->_tpl_vars['locale']['admin_gallery']['list_order']; ?>

                <input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_gallery']['list_submitorder']; ?>
" class="submit_filter" />
            </form>
        </div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_adddate']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_gallery']['list_action']; ?>
</th>
			</tr>
			<?php if (!function_exists('smarty_fun_menu')) { function smarty_fun_menu(&$smarty, $params) { $_fun_tpl_vars = $smarty->_tpl_vars; $smarty->assign($params);  ?>
			<?php $_from = $smarty->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $smarty->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $smarty);?>
">
					<td class="first" <?php if ($smarty->_tpl_vars['data']['level'] > 1): ?>style="padding-left: <?php echo $smarty->_tpl_vars['data']['level']*20; ?>
px;"<?php endif; ?>><a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<u><i><?php echo $smarty->_tpl_vars['locale']['admin_gallery']['list_description']; ?>
</i></u><br><?php echo ((is_array($_tmp=$smarty->_tpl_vars['data']['description'])) ? $smarty->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
')"><?php echo $smarty->_tpl_vars['data']['name']; ?>
</a></td>
					<td><?php echo $smarty->_tpl_vars['data']['add_date']; ?>
</td>
					<td class="last">
						<?php if ($smarty->_tpl_vars['data']['is_active']): ?>
							<a class="action act" href="admin.php?p=<?php echo $smarty->_tpl_vars['self']; ?>
&amp;act=<?php echo $smarty->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;gid=<?php echo $smarty->_tpl_vars['data']['gid']; ?>
" title="<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['list_inactive']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $smarty->_tpl_vars['self']; ?>
&amp;act=<?php echo $smarty->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;gid=<?php echo $smarty->_tpl_vars['data']['gid']; ?>
" title="<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['list_active']; ?>
"></a>
						<?php endif; ?>
						<?php if ($smarty->_tpl_vars['data']['level'] < 3): ?>
						<a href="admin.php?p=<?php echo $smarty->_tpl_vars['self']; ?>
&amp;act=<?php echo $smarty->_tpl_vars['this_page']; ?>
&amp;sub_act=gadd&amp;par=<?php echo $smarty->_tpl_vars['data']['gid']; ?>
" title="<?php echo $smarty->_tpl_vars['locale']['admin_menus']['field_list_submenu']; ?>
">
							<img src="<?php echo $smarty->_tpl_vars['theme_dir']; ?>
/images/admin/submenu.gif" border="0" alt="<?php echo $smarty->_tpl_vars['locale']['admin_menus']['field_list_submenu']; ?>
" />
						</a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $smarty->_tpl_vars['self']; ?>
&amp;act=<?php echo $smarty->_tpl_vars['this_page']; ?>
&amp;sub_act=gmod&amp;gid=<?php echo $smarty->_tpl_vars['data']['gid']; ?>
" title="<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['list_modify']; ?>
"></a>
						<a class="action" style="background: url(<?php echo $smarty->_tpl_vars['theme_dir']; ?>
/images/admin/gallery.gif) no-repeat top left;" href="admin.php?p=<?php echo $smarty->_tpl_vars['self']; ?>
&amp;act=<?php echo $smarty->_tpl_vars['this_page']; ?>
&amp;sub_act=plst&amp;gid=<?php echo $smarty->_tpl_vars['data']['gid']; ?>
" title="<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['list_gallery']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['confirm_gallery_del']; ?>
')) document.location.href='admin.php?p=<?php echo $smarty->_tpl_vars['self']; ?>
&amp;act=<?php echo $smarty->_tpl_vars['this_page']; ?>
&amp;sub_act=gdel&amp;gid=<?php echo $smarty->_tpl_vars['data']['gid']; ?>
';" title="<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['list_delete']; ?>
"></a>
					</td>
				</tr>
			<?php if ($smarty->_tpl_vars['data']['element']): ?>
				<?php smarty_fun_menu($smarty, array('list'=>$smarty->_tpl_vars['data']['element']));  ?>
			<?php endif; ?>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="2" class="empty">
						<img src="<?php echo $smarty->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['warning_gallery_empty']; ?>
" />
						<?php echo $smarty->_tpl_vars['locale']['admin_gallery']['warning_gallery_empty']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
			<?php  $smarty->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_menu($this, array('list'=>$this->_tpl_vars['page_data']));  ?>
		</table>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>