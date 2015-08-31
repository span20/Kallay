<?php /* Smarty version 2.6.16, created on 2007-07-24 10:33:49
         compiled from forum_forum.tpl */ ?>
<form <?php echo $this->_tpl_vars['form_forum']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form_forum']['hidden']; ?>

<fieldset><legend><?php echo $this->_tpl_vars['form_forum']['header']['headername']; ?>
</legend>
<p><?php if ($this->_tpl_vars['form_forum']['topic_name']['required']): ?><span class="required">*</span><?php endif; ?>
			<?php echo $this->_tpl_vars['form_forum']['topic_name']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['topic_name']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['topic_name']['error']; ?>
</span><?php endif; ?>
	<br />
	<?php echo $this->_tpl_vars['form_forum']['topic_name']['html']; ?>

	</p>
<p><?php if ($this->_tpl_vars['form_forum']['topic_subject']['required']): ?><span class="required">*</span><?php endif; ?>
	<?php echo $this->_tpl_vars['form_forum']['topic_subject']['label']; ?>
: 	<?php if ($this->_tpl_vars['form_forum']['topic_subject']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['topic_subject']['error']; ?>
</span><?php endif; ?>
	<br />
	<?php echo $this->_tpl_vars['form_forum']['topic_subject']['html']; ?>

</p>
<?php if ($this->_tpl_vars['form_forum']['write_everybody']): ?>
<p>
	<?php echo $this->_tpl_vars['form_forum']['write_everybody']['html']; ?>

</p>
<?php endif;  if ($this->_tpl_vars['form_forum']['read_everybody']): ?>
<p>
	<?php echo $this->_tpl_vars['form_forum']['read_everybody']['html']; ?>

</p>
<?php endif;  if ($this->_tpl_vars['form_forum']['is_sticky']): ?>
<p>
	<?php echo $this->_tpl_vars['form_forum']['is_sticky']['html']; ?>

</p>
<?php endif;  if ($this->_tpl_vars['form_forum']['default_blocked']): ?>
<p>
	<?php echo $this->_tpl_vars['form_forum']['default_blocked']['html']; ?>

</p>
<?php endif; ?>

<p><?php echo $this->_tpl_vars['form_forum']['requirednote']; ?>
</p>
<p><?php echo $this->_tpl_vars['form_forum']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_forum']['reset']['html']; ?>
</p>
</fieldset>
</form>