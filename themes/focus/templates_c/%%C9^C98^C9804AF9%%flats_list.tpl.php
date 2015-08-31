<?php /* Smarty version 2.6.16, created on 2009-11-21 10:44:14
         compiled from admin/flats_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/flats_list.tpl', 14, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_flats']['flat']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_flats']['flat_status']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first">
						<?php echo $this->_tpl_vars['data']['flat']; ?>

					</td>
                    <td>
                        <?php if ($this->_tpl_vars['data']['status'] == 0): ?>
                            szabad
                        <?php elseif ($this->_tpl_vars['data']['status'] == 1): ?>
                            lefoglalva
                        <?php else: ?>
                            eladva
                        <?php endif; ?>
                    </td>
					<td class="last">
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;flat_id=<?php echo $this->_tpl_vars['data']['id']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_contents']['news_tpl_list_modify']; ?>
"></a>
					</td>
				</tr>
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