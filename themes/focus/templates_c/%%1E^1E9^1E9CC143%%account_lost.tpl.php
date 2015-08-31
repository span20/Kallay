<?php /* Smarty version 2.6.16, created on 2013-04-09 21:41:15
         compiled from account_lost.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'account_lost.tpl', 6, false),)), $this); ?>
<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form']['hidden']; ?>

<table cellpadding="2" cellspacing="0" width="60%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/<?php echo $this->_tpl_vars['theme']; ?>
/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['form']['header']['account_lost'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span></td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr class="form">
		<td></td>
		<td><?php if ($this->_tpl_vars['form']['name']['required']): ?><font color="red">*</font><?php endif;  echo $this->_tpl_vars['form']['name']['label']; ?>
</td>
		<td>
			<?php if ($this->_tpl_vars['form']['name']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['name']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form']['name']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td><?php if ($this->_tpl_vars['form']['email']['required']): ?><font color="red">*</font><?php endif;  echo $this->_tpl_vars['form']['email']['label']; ?>
</td>
		<td>
			<?php if ($this->_tpl_vars['form']['email']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['email']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form']['email']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td colspan="2"><?php echo $this->_tpl_vars['form']['requirednote']; ?>
</td>
	</tr>
	<tr class="form">
		<td></td>
		<td colspan="2"><?php echo $this->_tpl_vars['form']['submit']['html']; ?>
&nbsp;<?php echo $this->_tpl_vars['form']['reset']['html']; ?>
</td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
</form>