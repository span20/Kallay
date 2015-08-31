<?php /* Smarty version 2.6.16, created on 2007-07-04 12:48:22
         compiled from account_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'account_main.tpl', 3, false),)), $this); ?>
<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_account']['header']['account'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
	</div>
	<div id="form_cnt">
		<form <?php echo $this->_tpl_vars['form_account']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['form_account']['hidden']; ?>

			<dl>
				<dt><?php if ($this->_tpl_vars['form_account']['name']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['name']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['name']['html'];  if ($this->_tpl_vars['form_account']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['name']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_account']['user_name']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['user_name']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['user_name']['html'];  if ($this->_tpl_vars['form_account']['user_name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['user_name']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_account']['email']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['email']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['email']['html'];  if ($this->_tpl_vars['form_account']['email']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['email']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_account']['is_public_mail']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['is_public_mail']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['is_public_mail']['html'];  if ($this->_tpl_vars['form_account']['is_public_mail']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['is_public_mail']['error']; ?>
</span><?php endif; ?></dd>
				<?php if ($this->_tpl_vars['form_account']['subscribe']): ?>
					<dt><?php if ($this->_tpl_vars['form_account']['subscribe']['required']): ?><span class="required">*</font><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['subscribe']['label']; ?>
</span></dt>
					<dd><?php echo $this->_tpl_vars['form_account']['subscribe']['html'];  if ($this->_tpl_vars['form_account']['subscribe']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['subscribe']['error']; ?>
</span><?php endif; ?></dd>
				<?php endif; ?>
			<?php if ($this->_tpl_vars['form_account']['modpass']): ?>
				<dt><?php if ($this->_tpl_vars['form_account']['modpass']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['modpass']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['modpass']['html'];  if ($this->_tpl_vars['form_account']['modpass']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['modpass']['error']; ?>
</span><?php endif; ?></dd>
			</dl>
			<dl id="modifypass" style="display:<?php echo $this->_tpl_vars['none_block']; ?>
;">
				<dt><?php if ($this->_tpl_vars['form_account']['oldpass']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['oldpass']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['oldpass']['html'];  if ($this->_tpl_vars['form_account']['oldpass']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['oldpass']['error']; ?>
</span><?php endif; ?></dd>
			<?php endif; ?>
				<dt><?php if ($this->_tpl_vars['form_account']['pass1']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['pass1']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['pass1']['html'];  if ($this->_tpl_vars['form_account']['pass1']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['pass1']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_account']['pass2']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['pass2']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_account']['pass2']['html'];  if ($this->_tpl_vars['form_account']['pass2']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['pass2']['error']; ?>
</span><?php endif; ?></dd>
			</dl>
			<?php if ($this->_tpl_vars['form_account']['requirednote'] && ! $this->_tpl_vars['form_account']['frozen']): ?>
				<p class="form_text"><?php echo $this->_tpl_vars['form_account']['requirednote']; ?>
</p>
			<?php endif; ?>
			<div><?php echo $this->_tpl_vars['form_account']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_account']['reset']['html']; ?>
</div>
		</form>
	</div>
	<div id="form_bottom"></div>
</div>

<?php if ($this->_tpl_vars['form_account']['modpass']): ?>
<?php echo '<script type="text/javascript">//<[CDATA[
function modPassActivate()
{
	modif = document.getElementById(\'modifypass\');
	p1 = document.getElementById(\'pass1\');
	p2 = document.getElementById(\'pass2\');
	if (document.getElementById(\'modpass\').checked) {
		modif.style.display = \'block\';
	} else {
		modif.style.display = \'none\';
		p1.value=\'\';p2.value=\'\';
	}
}
//]]></script>'; ?>

<?php endif; ?>