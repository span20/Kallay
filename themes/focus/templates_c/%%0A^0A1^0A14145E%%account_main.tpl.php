<?php /* Smarty version 2.6.16, created on 2013-03-23 17:32:55
         compiled from account_main.tpl */ ?>
<div id="form">
	<div id="form_top">
		<h1>Regisztráció</h1>
	</div>
    <script type="text/javascript" src="includes/date.js"></script>
    <script type="text/javascript" src="includes/jquery.datePicker.js"></script>
    <link rel="stylesheet" type="text/css" media="screen, projection" href="includes/datePicker.css" />
	<div id="form_cnt">
		<form <?php echo $this->_tpl_vars['form_account']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['form_account']['hidden']; ?>

            <div style="float: left; width: 200px;"><?php if ($this->_tpl_vars['form_account']['email']['required']): ?><span class="required"><sup>*</sup></span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['email']['label']; ?>
</span></div>
			<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['email']['html'];  if ($this->_tpl_vars['form_account']['email']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['email']['error']; ?>
</span><?php endif; ?></div>

            <?php if ($this->_tpl_vars['form_account']['modpass']): ?>
            <div style="clear: both;">
				<div style="float: left; width: 200px;"><?php if ($this->_tpl_vars['form_account']['modpass']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['modpass']['label']; ?>
</span></div>
				<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['modpass']['html'];  if ($this->_tpl_vars['form_account']['modpass']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['modpass']['error']; ?>
</span><?php endif; ?></div>
			</div>
			<div id="modifypass" style="display:<?php echo $this->_tpl_vars['none_block']; ?>
; clear: both;">
				<div style="float: left; width: 200px;"><?php if ($this->_tpl_vars['form_account']['oldpass']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['oldpass']['label']; ?>
</span></div>
				<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['oldpass']['html'];  if ($this->_tpl_vars['form_account']['oldpass']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['oldpass']['error']; ?>
</span><?php endif; ?></div>
            </div>
			<?php endif; ?>


			<div style="float: left; width: 200px; clear: both;"><?php if ($this->_tpl_vars['form_account']['pass1']['required']): ?><span class="required"><sup>*</sup></span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['pass1']['label']; ?>
</span></div>
			<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['pass1']['html'];  if ($this->_tpl_vars['form_account']['pass1']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['pass1']['error']; ?>
</span><?php endif; ?></div>

			<div style="float: left; width: 200px; clear: both;"><?php if ($this->_tpl_vars['form_account']['pass2']['required']): ?><span class="required"><sup>*</sup></span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['pass2']['label']; ?>
</span></div>
			<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['pass2']['html'];  if ($this->_tpl_vars['form_account']['pass2']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['pass2']['error']; ?>
</span><?php endif; ?></div>

            <div style="float: left; width: 200px; clear: both;"><?php if ($this->_tpl_vars['form_account']['cegnev']['required']): ?><span class="required"><sup>*</sup></span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['cegnev']['label']; ?>
</span></div>
			<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['cegnev']['html'];  if ($this->_tpl_vars['form_account']['cegnev']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['cegnev']['error']; ?>
</span><?php endif; ?></div>

            <div style="float: left; width: 200px; clear: both;"><?php if ($this->_tpl_vars['form_account']['ertesito']['required']): ?><span class="required"><sup>*</sup></span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_account']['ertesito']['label']; ?>
</span></div>
			<div style="float: left;"><?php echo $this->_tpl_vars['form_account']['ertesito']['html'];  if ($this->_tpl_vars['form_account']['ertesito']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_account']['ertesito']['error']; ?>
</span><?php endif; ?></div>

            
			<div style="clear: both; padding-top: 10px;"><?php echo $this->_tpl_vars['form_account']['submit']['html']; ?>
</div>
		</form>
	</div>
	<div id="form_bottom"></div>
</div>

<?php if ($this->_tpl_vars['form_account']['modpass']):  echo '<script type="text/javascript">//<[CDATA[
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