<?php /* Smarty version 2.6.16, created on 2007-12-21 12:49:55
         compiled from feedback.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'feedback.tpl', 6, false),)), $this); ?>
<form <?php echo $this->_tpl_vars['form_feedback']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form_feedback']['hidden']; ?>

<table cellpadding="2" cellspacing="0" width="60%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_feedback']['header']['feedback'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span></td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_feedback']['name']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['name']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_feedback']['name']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_feedback']['name']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['name']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_feedback']['email']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['email']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_feedback']['email']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_feedback']['email']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['email']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_feedback']['subject']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['subject']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_feedback']['subject']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_feedback']['subject']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['subject']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_feedback']['message']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['message']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_feedback']['message']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_feedback']['message']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['message']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_feedback']['copymail']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['copymail']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_feedback']['copymail']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_feedback']['copymail']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_feedback']['copymail']['html']; ?>

		</td>
	</tr>
	<?php if ($this->_tpl_vars['form_feedback']['requirednote'] && ! $this->_tpl_vars['form_feedback']['frozen']): ?>
		<tr class="form">
			<td></td>
			<td colspan="2"><?php echo $this->_tpl_vars['form_feedback']['requirednote']; ?>
</td>
		</tr>
	<?php endif; ?>
	<tr class="form">
		<td></td>
		<td colspan="2"><?php echo $this->_tpl_vars['form_feedback']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_feedback']['reset']['html']; ?>
</td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
</form>