<?php /* Smarty version 2.6.16, created on 2007-10-16 17:21:15
         compiled from admin/langs_search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/langs_search.tpl', 9, false),)), $this); ?>
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
			<li id="current"><a href="admin.php?p=langs" title="<?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_result']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_result']; ?>
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
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_module']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_variable']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_expression']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><?php echo $this->_tpl_vars['data']['aname']; ?>
</td>
                <td><?php echo $this->_tpl_vars['data']['vname']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['exp']; ?>
</td>
				<td class="last">
					<a class="action mod" href="admin.php?p=langs&amp;act=langs&amp;sub_act=w_mod&amp;variable_id=<?php echo $this->_tpl_vars['data']['vid']; ?>
&amp;s=1&amp;searchtext=<?php echo $_REQUEST['searchtext']; ?>
&amp;searchtype=<?php echo $_REQUEST['searchtype']; ?>
&amp;pageID=<?php echo $_GET['pageID']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_lang']['search_list_modify']; ?>
"></a>
					<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_langs']['search_confirm_del']; ?>
')) document.location.href='admin.php?p=langs&amp;act=langs&amp;sub_act=w_del&amp;variable_id=<?php echo $this->_tpl_vars['data']['vid']; ?>
&amp;s=1&amp;searchtext=<?php echo $_REQUEST['searchtext']; ?>
&amp;searchtype=<?php echo $_REQUEST['searchtype']; ?>
&amp;pageID=<?php echo $_GET['pageID']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_langs']['search_list_delete']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="4" class="empty">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_langs']['search_warning_empty']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_langs']['search_warning_empty']; ?>

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