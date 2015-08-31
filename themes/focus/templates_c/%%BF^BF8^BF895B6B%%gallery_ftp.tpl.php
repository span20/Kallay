<?php /* Smarty version 2.6.16, created on 2011-01-18 00:27:50
         compiled from admin/gallery_ftp.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/gallery_ftp.tpl', 5, false),array('function', 'cycle', 'admin/gallery_ftp.tpl', 25, false),)), $this); ?>
<div id="table">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
			<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['form']['hidden']; ?>

			<table>
				<tr>
					<th colspan="2"><?php echo $this->_tpl_vars['locale']['admin_gallery']['field_list_gallery_ftp']; ?>
: <?php echo $this->_tpl_vars['act_dir']; ?>
</th>
				</tr>
				<tr><td style="height: 1px; color:#FFFFFF;"></td></tr>
				<tr>
					<th><?php echo $this->_tpl_vars['locale']['admin_gallery']['field_list_gallery_ftplocation']; ?>
</th>
					<th style="text-align: right">
						<?php if ($this->_tpl_vars['form']['all']['required']): ?><font color="red">*</font><?php endif; ?>
						<?php if ($this->_tpl_vars['form']['all']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['all']['error']; ?>
</font><br /><?php endif; ?>
						<?php echo $this->_tpl_vars['form']['all']['label']; ?>
 <?php echo $this->_tpl_vars['form']['all']['html']; ?>

					</th>
				</tr>
				<tr><td style="height: 1px; color:#FFFFFF;"></td></tr>
				<?php $_from = $this->_tpl_vars['dirlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
					<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
						<td><?php echo $this->_tpl_vars['data']; ?>
</td>
						<td align="right"><input type="checkbox" name="fileChecked[]" value="<?php echo $this->_tpl_vars['data']; ?>
"></td>
					</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="2" class="hiba"><?php echo $this->_tpl_vars['locale']['admin_gallery']['warning_emptyftp']; ?>
</td></tr>
				<?php endif; unset($_from); ?>

                <tr class="row2">
                    <td class="form" colspan="2">
                        <?php if (! $this->_tpl_vars['form']['frozen']): ?>
                            <?php if ($this->_tpl_vars['form']['requirednote']):  echo $this->_tpl_vars['form']['requirednote'];  endif; ?>
                            <?php echo $this->_tpl_vars['form']['submit']['html']; ?>

                        <?php endif; ?>
                    </td>
                </tr>
			</table>
			</form>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
	</div>
	<div id="t_bottom"></div>
</div>