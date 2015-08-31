<?php /* Smarty version 2.6.16, created on 2007-10-01 16:35:07
         compiled from guestbook_reply.tpl */ ?>
<form <?php echo $this->_tpl_vars['form_guestbook']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form_guestbook']['hidden']; ?>

<table cellpadding="2" cellspacing="0" width="60%">
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr class="form">
		<td>&nbsp;</td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_guestbook']['guestbook_answer']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_guestbook']['guestbook_answer']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_guestbook']['guestbook_answer']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_guestbook']['guestbook_answer']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_guestbook']['guestbook_answer']['html']; ?>

		</td>
	</tr>
	<?php if ($this->_tpl_vars['form_guestbook']['requirednote'] && ! $this->_tpl_vars['form_guestbook']['frozen']): ?>
		<tr class="form">
			<td>&nbsp;</td>
			<td colspan="2"><?php echo $this->_tpl_vars['form_guestbook']['requirednote']; ?>
</td>
		</tr>
	<?php endif; ?>
	<tr class="form">
		<td>&nbsp;</td>
		<td colspan="2"><?php echo $this->_tpl_vars['form_guestbook']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_guestbook']['reset']['html']; ?>
</td>
	</tr>
	<tr class="form">
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
</form>