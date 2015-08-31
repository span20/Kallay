<?php /* Smarty version 2.6.16, created on 2007-06-19 16:06:58
         compiled from guestbook_add.tpl */ ?>
<form <?php echo $this->_tpl_vars['form_guestbook']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form_guestbook']['hidden']; ?>

<p>
	<?php if ($this->_tpl_vars['form_guestbook']['guestbook_name']['required']): ?><span class="required">*</span><?php endif; ?>
	<?php echo $this->_tpl_vars['form_guestbook']['guestbook_name']['label']; ?>
:<br />
	<?php if ($this->_tpl_vars['form_guestbook']['guestbook_name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_guestbook']['guestbook_name']['error']; ?>
</span><br /><?php endif; ?>
	<?php echo $this->_tpl_vars['form_guestbook']['guestbook_name']['html']; ?>

</p>
<p>
	<?php if ($this->_tpl_vars['form_guestbook']['guestbook_email']['required']): ?><span class="required">*</span><?php endif; ?>
	<?php echo $this->_tpl_vars['form_guestbook']['guestbook_email']['label']; ?>
:<br />
	<?php if ($this->_tpl_vars['form_guestbook']['guestbook_email']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_guestbook']['guestbook_email']['error']; ?>
</span><br /><?php endif; ?>
	<?php echo $this->_tpl_vars['form_guestbook']['guestbook_email']['html']; ?>

</p>
<p>
	<?php if ($this->_tpl_vars['form_guestbook']['guestbook_message']['required']): ?><span class="required">*</span><?php endif; ?>
	<?php echo $this->_tpl_vars['form_guestbook']['guestbook_message']['label']; ?>
:<br />
	<?php if ($this->_tpl_vars['form_guestbook']['guestbook_message']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_guestbook']['guestbook_message']['error']; ?>
</span><br /><?php endif; ?>
	<?php echo $this->_tpl_vars['form_guestbook']['guestbook_message']['html']; ?>

</p>
	<?php if ($this->_tpl_vars['gb_captcha']): ?>
	<p>
		<img src="<?php echo $this->_tpl_vars['gb_captcha']; ?>
" border="0" alt="gb_captcha" /><br />
		<?php if ($this->_tpl_vars['form_guestbook']['gb_recaptcha']['required']): ?><span class="required">*</span><?php endif; ?>
		<?php echo $this->_tpl_vars['form_guestbook']['gb_recaptcha']['label']; ?>
:<br />
		<?php if ($this->_tpl_vars['form_guestbook']['gb_recaptcha']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_guestbook']['gb_recaptcha']['error']; ?>
</span><br /><?php endif; ?>
		<?php echo $this->_tpl_vars['form_guestbook']['gb_recaptcha']['html']; ?>

	</p>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['form_guestbook']['requirednote'] && ! $this->_tpl_vars['form_guestbook']['frozen']): ?>
	<p>
		<?php echo $this->_tpl_vars['form_guestbook']['requirednote']; ?>

	</p>
	<?php endif; ?>
<p>
<?php echo $this->_tpl_vars['form_guestbook']['gb_submit']['html']; ?>

<?php echo $this->_tpl_vars['form_guestbook']['gb_reset']['html']; ?>

</p>
</form>
