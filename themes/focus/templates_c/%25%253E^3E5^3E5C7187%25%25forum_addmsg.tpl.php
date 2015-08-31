<?php /* Smarty version 2.6.16, created on 2007-07-25 16:34:42
         compiled from forum_addmsg.tpl */ ?>
<form <?php echo $this->_tpl_vars['form_forum']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['form_forum']['hidden']; ?>

	<fieldset>
		<legend><?php echo $this->_tpl_vars['form_forum']['header']['headername']; ?>
</legend>
		<p>	<?php if ($this->_tpl_vars['form_forum']['subject']['required']): ?><span class="required">*</span><?php endif; ?>
					<?php echo $this->_tpl_vars['form_forum']['subject']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['subject']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['subject']['error']; ?>
</span><?php endif; ?>
			<br />
			<?php echo $this->_tpl_vars['form_forum']['subject']['html']; ?>

		</p>
		<p> <?php if ($this->_tpl_vars['form_forum']['message']['required']): ?><span class="required">*</span><?php endif; ?>
			<?php echo $this->_tpl_vars['form_forum']['message']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['message']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['message']['error']; ?>
</span><?php endif; ?>
			<br />
			<?php echo $this->_tpl_vars['form_forum']['message']['html']; ?>

		</p>
		<div id="forum_help"><noscript><p><?php echo $this->_tpl_vars['lang_forum']['strForumNoJavascript']; ?>
</p></noscript></div>
		<p>
		<?php echo $this->_tpl_vars['form_forum']['embed']['label']; ?>
:<br />
		<?php if ($this->_tpl_vars['form_forum']['embed']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_forum']['embed']['error']; ?>
</span><br /><?php endif; ?>
		<?php echo $this->_tpl_vars['form_forum']['embed']['html']; ?>

		</p>
		<?php if ($this->_tpl_vars['forum_pics']): ?>
		    <p>
		    <?php echo $this->_tpl_vars['form_forum']['msgpic0']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['msgpic0']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['msgpic0']['error']; ?>
</span><?php endif; ?>
		    <br />
		    <?php echo $this->_tpl_vars['form_forum']['msgpic0']['html']; ?>

		    <br />
		    <?php echo $this->_tpl_vars['form_forum']['msgpic1']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['msgpic0']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['msgpic1']['error']; ?>
</span><?php endif; ?>
		    <br />
		    <?php echo $this->_tpl_vars['form_forum']['msgpic1']['html']; ?>

		    <br />
		    <?php echo $this->_tpl_vars['form_forum']['msgpic2']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['msgpic2']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['msgpic2']['error']; ?>
</span><?php endif; ?>
		    <br />
		    <?php echo $this->_tpl_vars['form_forum']['msgpic2']['html']; ?>

		    </p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['forum_captcha']): ?>
			<p>
			<img src="<?php echo $this->_tpl_vars['forum_captcha']; ?>
" id="captcha" alt="captcha"><br />
			<?php if ($this->_tpl_vars['form_forum']['forum_recaptcha']['required']): ?><span class="required">*</span><?php endif; ?>
					<?php echo $this->_tpl_vars['form_forum']['forum_recaptcha']['label']; ?>
: <?php if ($this->_tpl_vars['form_forum']['forum_recaptcha']['error']): ?><br /><span class="error"><?php echo $this->_tpl_vars['form_forum']['forum_recaptcha']['error']; ?>
</span><?php endif; ?>
			<br />
			<?php echo $this->_tpl_vars['form_forum']['forum_recaptcha']['html']; ?>

			</p>
		<?php endif; ?>
		<p><?php echo $this->_tpl_vars['form_forum']['requirednote']; ?>
</p>
		<p><?php echo $this->_tpl_vars['form_forum']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_forum']['reset']['html']; ?>
</p>
	</fieldset>
</form>
<p class="centered">
<a class="back" href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;tid=<?php echo $this->_tpl_vars['tid']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumBack']; ?>
</a>
</p>