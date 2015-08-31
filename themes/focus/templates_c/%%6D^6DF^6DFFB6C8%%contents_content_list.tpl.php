<?php /* Smarty version 2.6.16, created on 2015-06-12 15:28:32
         compiled from admin/contents_content_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/contents_content_list.tpl', 38, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
				<input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
				<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_orderby']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_title']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_lang']; ?>
</option>
					<?php if ($_SESSION['site_conttimer'] == 1): ?>
						<option value="3" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_timerstart']; ?>
</option>
						<option value="4" <?php echo $this->_tpl_vars['fieldselect5']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_timerend']; ?>
</option>
					<?php endif; ?>
				</select>
				<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_adminby']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_orderasc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_orderdesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['local']['admin_contents']['contents_tpl_order']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_submitorder']; ?>
" class="submit_filter">
			</form>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first" style="width:40px;"><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_title']; ?>
</th>
				<?php if ($_SESSION['site_conttimer'] == 1): ?>
					<th><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_timerstart']; ?>
</th>
					<th><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_timerend']; ?>
</th>
				<?php endif; ?>
				<th class="last" style="width: 100px;"><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first">
						<?php $this->assign('flag', $this->_tpl_vars['data']['clang']); ?>
						<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
						<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['data']['clang']; ?>
" />
						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['clang']; ?>

						<?php endif; ?>
					</td>
					<td><?php echo $this->_tpl_vars['data']['ctitle']; ?>
</td>
					<?php if ($_SESSION['site_conttimer'] == 1): ?>
						<td><?php echo $this->_tpl_vars['data']['cstart']; ?>
</td><td><?php echo $this->_tpl_vars['data']['cend']; ?>
</td>
					<?php endif; ?>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['cact'] == 1): ?>
							<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_inactivate']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_activate']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_modify']; ?>
"></a>
                        <?php if ($this->_tpl_vars['data']['cid'] != 95 && $this->_tpl_vars['data']['cid'] != 96 && $this->_tpl_vars['data']['cid'] != 100 && $this->_tpl_vars['data']['cid'] != 101 && $this->_tpl_vars['data']['cid'] != 102): ?>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_delete']; ?>
"></a>
                        <?php endif; ?>
						<?php if (! empty ( $this->_tpl_vars['data']['versions'] ) && ! empty ( $_SESSION['site_cnt_version'] )): ?>
                            <a href="javascript: trSwitcher( <?php echo $this->_tpl_vars['data']['cid']; ?>
 );" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versions']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/versions.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versions']; ?>
" /></a>
						<?php endif; ?>
					</td>
				</tr>
				<?php if (! empty ( $this->_tpl_vars['data']['versions'] ) && ! empty ( $_SESSION['site_cnt_version'] )): ?>
				<?php $this->assign('td_colspan', 3); ?>
				<?php if ($_SESSION['site_conttimer'] == 1):  $this->assign('td_colspan', $this->_tpl_vars['td_colspan']+2);  endif; ?>
				<tr id="<?php echo $this->_tpl_vars['data']['cid']; ?>
" style="display: none;">
					<td colspan="<?php echo $this->_tpl_vars['td_colspan']; ?>
">
						<table style="margin-left: 15px; width: 730px;">
							<tr style="border-bottom: 1px solid #688da8;">
								<td class="first"><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versiontitle']; ?>
</td>
								<td><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versiondate']; ?>
</td>
								<td><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versionauthor']; ?>
</td>
								<td><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versionaction']; ?>
</td>
							</tr>
							<?php $_from = $this->_tpl_vars['data']['versions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['version']):
?>
							<tr>
								<td class="first"><?php echo $this->_tpl_vars['version']['title']; ?>
</td>
								<td><?php echo $this->_tpl_vars['version']['mod_date']; ?>
</td>
								<td><?php echo $this->_tpl_vars['version']['author']; ?>
</td>
								<td>
                                    <a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=show&amp;cvid=<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versionshow']; ?>
</a>
                                    <a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=restore&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;restore_version=<?php echo $this->_tpl_vars['key']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" onClick="return confirm('<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_confirm_restore']; ?>
');"><?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_list_versionrestore']; ?>
</a>
                                </td>
							</tr>
							<?php endforeach; endif; unset($_from); ?>
						</table>
					</td>
				</tr>
				<?php endif; ?>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="6" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_warning_no_content']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_contents']['contents_tpl_warning_no_content']; ?>

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