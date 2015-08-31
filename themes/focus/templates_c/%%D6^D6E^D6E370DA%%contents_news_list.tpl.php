<?php /* Smarty version 2.6.16, created on 2015-06-12 15:28:28
         compiled from admin/contents_news_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/contents_news_list.tpl', 48, false),array('modifier', 'nl2br', 'admin/contents_news_list.tpl', 66, false),array('modifier', 'escape', 'admin/contents_news_list.tpl', 66, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
			<div style="float: left;">
				<form action="admin.php" method="get">
					<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
					<input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
					<input type="hidden" name="cat_fil" value="<?php echo $_GET['cat_fil']; ?>
">
					<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_orderby']; ?>

					<select name="field">
						<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_title']; ?>
</option>
						<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_lang']; ?>
</option>
						<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_adddate']; ?>
</option>
						<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_adduser']; ?>
</option>
					</select>
					<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_adminby']; ?>

					<select name="ord">
						<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_orderasc']; ?>
</option>
						<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_orderdesc']; ?>
</option>
					</select>
					<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_order']; ?>

					<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_submitorder']; ?>
" class="submit_filter">
				</form>
			</div>
			<?php if ($this->_tpl_vars['category_list']): ?>
			<div style="float: right; padding-right: 5px;">
				<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_filter']; ?>

					<select name="cat_filter" onchange="window.location='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;cat_fil='+this.value;">
					<?php $_from = $this->_tpl_vars['category_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['cats']):
?>
						<option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php echo $this->_tpl_vars['catselect'][$this->_tpl_vars['key']]; ?>
><?php echo $this->_tpl_vars['cats']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
			</div>
			<?php endif; ?>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first" style="width:40px;"><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_type']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_title']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_adddate']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_adduser']; ?>
</th>
				<th class="last" style="width: 100px;"><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_action']; ?>
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
					<td>
						<?php if ($this->_tpl_vars['data']['mnews'] == 0): ?>
							<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_typenews']; ?>

						<?php else: ?>
							<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_typemain']; ?>

						<?php endif; ?>
					</td>
					<td>
                        <a onmouseover="this.T_WIDTH=180;this.T_BGCOLOR='#99ABB9';this.T_FONTCOLOR='#FFFFFF';this.T_BORDERCOLOR='#B9C7D2';return escape('<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['data']['lead'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
')"><?php echo $this->_tpl_vars['data']['ctitle']; ?>
</a>
                    </td>
                    <td><?php echo $this->_tpl_vars['data']['add_date']; ?>
</td><td><?php echo $this->_tpl_vars['data']['username']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['cact'] == 1): ?>
							<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_inactive']; ?>
"></a>
						<?php else: ?>
							<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=act&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_activate']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_modify']; ?>
"></a>
						<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_delete']; ?>
"></a>
						<?php if (! empty ( $this->_tpl_vars['data']['versions'] ) && ! empty ( $_SESSION['site_cnt_version'] )): ?>
						 <a href="javascript: trSwitcher( <?php echo $this->_tpl_vars['data']['cid']; ?>
 );" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versions']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/versions.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versions']; ?>
" /></a>
						<?php endif; ?>
					</td>
				</tr>
				<?php if (! empty ( $this->_tpl_vars['data']['versions'] ) && ! empty ( $_SESSION['site_cnt_version'] )): ?>
				<?php $this->assign('td_colspan', 4); ?>
				<?php if ($_SESSION['site_conttimer'] == 1):  $this->assign('td_colspan', $this->_tpl_vars['td_colspan']+2);  endif; ?>
				<tr id="<?php echo $this->_tpl_vars['data']['cid']; ?>
" style="display: none;">
					<td colspan="<?php echo $this->_tpl_vars['td_colspan']; ?>
">
						<table style="margin-left: 15px; width: 730px;">
							<tr style="border-bottom: 1px solid #688da8;">
								<td class="first"><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versiontitle']; ?>
</td>
								<td><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versiondate']; ?>
</td>
								<td><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versionauthor']; ?>
</td>
								<td><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versionaction']; ?>
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
"><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versionshow']; ?>
</a>
                                    <a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=restore&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;restore_version=<?php echo $this->_tpl_vars['key']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" onClick="return confirm('<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_confirm_restore']; ?>
');"><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_versionrestore']; ?>
</a></td>
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
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_warning_no_news']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_warning_no_news']; ?>

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