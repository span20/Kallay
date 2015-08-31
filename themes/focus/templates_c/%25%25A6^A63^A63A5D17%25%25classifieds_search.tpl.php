<?php /* Smarty version 2.6.16, created on 2007-08-01 14:03:34
         compiled from admin/classifieds_search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/classifieds_search.tpl', 9, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div class="pager"></div>
			<form <?php echo $this->_tpl_vars['form_search']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['form_search']['hidden']; ?>

				<table>
					<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
						<td class="form"><?php if ($this->_tpl_vars['form_search']['searchtext']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_search']['searchtext']['label']; ?>
</td>
						<td><?php echo $this->_tpl_vars['form_search']['searchtext']['html'];  if ($this->_tpl_vars['form_search']['searchtext']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_search']['searchtext']['error']; ?>
</span><?php endif; ?></td>
					</tr>
					<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
						<td class="form"><?php if ($this->_tpl_vars['form_search']['searchtype']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_search']['searchtype']['label']; ?>
</td>
						<td><?php echo $this->_tpl_vars['form_search']['searchtype']['html'];  if ($this->_tpl_vars['form_search']['searchtype']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_search']['searchtype']['error']; ?>
</span><?php endif; ?></td>
					</tr>
					<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="form" colspan="2">
						<?php if (! $this->_tpl_vars['form_search']['frozen']): ?>
							<?php if ($this->_tpl_vars['form_search']['requirednote']):  echo $this->_tpl_vars['form_search']['requirednote'];  endif; ?>
							<?php echo $this->_tpl_vars['form_search']['submit']['html'];  echo $this->_tpl_vars['form_search']['reset']['html']; ?>

						<?php endif; ?>
					</td>
				</tr>
				</table>
			</form>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>

	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_title_search_list']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_title_search_list']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter"></div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_lang']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_id']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_name']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_phone']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_email']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_timerend']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_field_advert_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first">
					<?php $this->assign('flag', $this->_tpl_vars['data']['lang']); ?>
					<?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
					<?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['data']['lang']; ?>
" />
					<?php else: ?>
						<?php echo $this->_tpl_vars['data']['lang']; ?>

					<?php endif; ?>
				</td>
				<td><?php echo $this->_tpl_vars['data']['id']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['phone']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['email']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['timer_end']; ?>
</td>
				<td class="last">
					<?php if ($this->_tpl_vars['data']['is_active'] == 1): ?>
						<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=adverts&amp;sub_act=act&amp;id=<?php echo $this->_tpl_vars['data']['id']; ?>
&amp;s=1&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;searchtext=<?php echo $_REQUEST['searchtext']; ?>
&amp;searchtype=<?php echo $_REQUEST['searchtype']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_title_advert_inactivate']; ?>
"></a>
					<?php else: ?>
						<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=adverts&amp;sub_act=act&amp;id=<?php echo $this->_tpl_vars['data']['id']; ?>
&amp;s=1&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;searchtext=<?php echo $_REQUEST['searchtext']; ?>
&amp;searchtype=<?php echo $_REQUEST['searchtype']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_title_advert_activate']; ?>
"></a>
					<?php endif; ?>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=adverts&amp;sub_act=mod&amp;id=<?php echo $this->_tpl_vars['data']['id']; ?>
&amp;s=1&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;searchtext=<?php echo $_REQUEST['searchtext']; ?>
&amp;searchtype=<?php echo $_REQUEST['searchtype']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_title_advert_modify']; ?>
"></a>
					<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_confirm_advert_delete']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=adverts&amp;sub_act=del&amp;id=<?php echo $this->_tpl_vars['data']['id']; ?>
&amp;s=1&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
&amp;searchtext=<?php echo $_REQUEST['searchtext']; ?>
&amp;searchtype=<?php echo $_REQUEST['searchtype']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_title_advert_delete']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="7" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_warning_advert_empty']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_classifieds']['search_warning_advert_empty']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>