<?php /* Smarty version 2.6.16, created on 2007-12-18 08:33:27
         compiled from admin/downloads_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/downloads_add.tpl', 5, false),)), $this); ?>
<div id="form_cnt">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="f_content">
		<div class="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
        </div>
		<div class="pager" style="float: left; padding-left: 10px;">
			<strong><?php echo $this->_tpl_vars['locale']['admin_downloads']['field_location']; ?>
</strong> <img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/dir.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_downloads']['field_location']; ?>
" /> 
			<?php echo $this->_tpl_vars['act_dir']; ?>

		</div>
		<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table style="clear: both;">
			<tr class="row2">
				<td class="form"><?php if ($this->_tpl_vars['form']['dirname']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['dirname']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['dirname']['html'];  if ($this->_tpl_vars['form']['dirname']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['dirname']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['desc']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['desc']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['desc']['html'];  if ($this->_tpl_vars['form']['desc']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['desc']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row1">
				<td class="form" colspan="2">
					<?php if (! $this->_tpl_vars['form']['frozen']): ?>
						<?php if ($this->_tpl_vars['form']['requirednote']):  echo $this->_tpl_vars['form']['requirednote'];  endif; ?>
						<?php echo $this->_tpl_vars['form']['submit']['html'];  echo $this->_tpl_vars['form']['reset']['html']; ?>

					<?php endif; ?>
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>