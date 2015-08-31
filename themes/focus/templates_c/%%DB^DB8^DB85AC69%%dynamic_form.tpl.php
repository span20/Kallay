<?php /* Smarty version 2.6.16, created on 2011-05-21 13:49:09
         compiled from dynamic_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'dynamic_form.tpl', 29, false),)), $this); ?>
<div><?php echo $this->_tpl_vars['lang']['strAdminHeader']; ?>
</div>

<form<?php echo $this->_tpl_vars['form']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form']['hidden']; ?>

<table cellpadding="2" cellspacing="0" class="szurke">
<?php $_from = $this->_tpl_vars['form']['sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['sec']):
?>
	<?php $_from = $this->_tpl_vars['sec']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
		<?php if ($this->_tpl_vars['element']['type'] == 'submit' || $this->_tpl_vars['element']['type'] == 'reset'): ?>
			<?php if (! $this->_tpl_vars['form']['frozen']): ?>
				<tr>
					<td colspan="2"><?php echo $this->_tpl_vars['element']['html']; ?>
</td>
				</tr>
			<?php endif; ?>
		<?php else: ?>
			<tr>
				<?php if ($this->_tpl_vars['element']['type'] == 'textarea'): ?>
					<td colspan="2">
						<?php if ($this->_tpl_vars['element']['required']): ?><font color="red">*</font><?php endif;  echo $this->_tpl_vars['element']['label']; ?>
<br />
				<?php else: ?>
					<td align="right" valign="top" width="50%">
						<?php if ($this->_tpl_vars['element']['required']): ?><font color="red">*</font><?php endif;  echo $this->_tpl_vars['element']['label']; ?>
</td>
					<td>
				<?php endif; ?>
					<?php if ($this->_tpl_vars['element']['error']): ?><font color="red"><?php echo $this->_tpl_vars['element']['error']; ?>
</font><br /><?php endif; ?>
					<?php if ($this->_tpl_vars['element']['type'] == 'group'): ?>
						<?php $_from = $this->_tpl_vars['element']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gkey'] => $this->_tpl_vars['gitem']):
?>
							<?php echo $this->_tpl_vars['gitem']['label']; ?>

							<?php echo $this->_tpl_vars['gitem']['html'];  if ($this->_tpl_vars['gitem']['required']): ?><font color="red">*</font><?php endif; ?>
							<?php if ($this->_tpl_vars['element']['separator']):  echo smarty_function_cycle(array('values' => $this->_tpl_vars['element']['separator']), $this); endif; ?>
						<?php endforeach; endif; unset($_from); ?>
					
					<?php else: ?>
						<?php echo $this->_tpl_vars['element']['html']; ?>

					<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from);  endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['form']['requirednote'] && ! $this->_tpl_vars['form']['frozen']): ?>
	<tr><td colspan="2"><?php echo $this->_tpl_vars['form']['requirednote']; ?>
</td></tr>
<?php endif; ?>
</table>
</form>