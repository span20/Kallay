<?php /* Smarty version 2.6.16, created on 2007-12-12 15:40:02
         compiled from recommend.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'recommend.tpl', 6, false),)), $this); ?>
<form <?php echo $this->_tpl_vars['form_recommend']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form_recommend']['hidden']; ?>

<table cellpadding="2" cellspacing="0" width="60%">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_recommend']['header']['recommend'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span></td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_recommend']['sendername']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['sendername']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_recommend']['sendername']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_recommend']['sendername']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['sendername']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_recommend']['sendermail']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['sendermail']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_recommend']['sendermail']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_recommend']['sendermail']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['sendermail']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_recommend']['recipename']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['recipename']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_recommend']['recipename']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_recommend']['recipename']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['recipename']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td width="50%" valign="top">
			<?php if ($this->_tpl_vars['form_recommend']['recipemail']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['recipemail']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_recommend']['recipemail']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_recommend']['recipemail']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['recipemail']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_recommend']['message']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['message']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_recommend']['message']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_recommend']['message']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_recommend']['message']['html']; ?>

		</td>
	</tr>
	<?php if ($this->_tpl_vars['form_recommend']['requirednote'] && ! $this->_tpl_vars['form_recommend']['frozen']): ?>
		<tr class="form">
			<td></td>
			<td colspan="2"><?php echo $this->_tpl_vars['form_recommend']['requirednote']; ?>
</td>
		</tr>
	<?php endif; ?>
	<tr class="form">
		<td></td>
		<td colspan="2"><?php echo $this->_tpl_vars['form_recommend']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_recommend']['reset']['html']; ?>
</td>
	</tr>
</table>
</form>