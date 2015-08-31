<?php /* Smarty version 2.6.16, created on 2015-06-16 13:30:53
         compiled from contents.tpl */ ?>
<div class="cont_text <?php if ($this->_tpl_vars['heading_color']):  echo $this->_tpl_vars['heading_color'];  endif; ?>">
	<?php if (! empty ( $this->_tpl_vars['content_content2'] )): ?>
		<div class="col-md-6">
			<?php echo $this->_tpl_vars['content_content']; ?>

		</div>
		<div class="col-md-6">
			<?php echo $this->_tpl_vars['content_content2']; ?>

		</div>
	<?php else: ?>
		<div class="col-md-12">
			<?php echo $this->_tpl_vars['content_content']; ?>

		</div>
	<?php endif; ?>
</div>