<?php /* Smarty version 2.6.16, created on 2013-04-18 19:07:08
         compiled from video.tpl */ ?>
<?php if ($_SESSION['user_id']): ?>
	
	<div style="text-align: center;">
		Bel�pve: <?php echo $_SESSION['usermail']; ?>
 | <a href="index.php?p=account&act=account_out">Kil�p�s</a>
		<br />
		<br />
		<?php if ($_REQUEST['error']): ?>
			<?php if ($_REQUEST['error'] == 4): ?>
				<div style="color: #5A9700; padding: 10px;">Sikeres felt�lt�s!</div>
			<?php endif; ?>
			<?php if ($_REQUEST['error'] == 1): ?>
				<div style="color: #ff0000; padding: 10px;">V�lassz f�jlt!</div>
			<?php endif; ?>
			<?php if ($_REQUEST['error'] == 2): ?>
				<div style="color: #ff0000; padding: 10px;">Hiba a felt�lt�s sor�n!</div>
			<?php endif; ?>
			<?php if ($_REQUEST['error'] == 3): ?>
				<div style="color: #ff0000; padding: 10px;">Hib�s file form�tum! (avi, mp4, flv, mpg, wmv enged�lyezett)</div>
			<?php endif; ?>
		<?php endif; ?>
		<form method="post" enctype="multipart/form-data" action="">
			Vide� felt�lt�s:<br /><br />
			<input type="file" name="vidfile" /><br /><br />
			<input type="submit" name="submitted" value="Felt�lt�s" />
		</form>
		<?php if ($this->_tpl_vars['uvids']): ?>
			<div style="padding: 10px;">
				Felt�lt�tt vide�k:<br /><br />
				<?php $_from = $this->_tpl_vars['uvids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
					<div><?php echo $this->_tpl_vars['data']['videofile']; ?>
</div>
				<?php endforeach; endif; unset($_from); ?>
			</div>
		<?php endif; ?>
	</div>
<?php else: ?>
	<div style="clear: both; text-align: center;">
		Vide� felt�lt�s�hez jelentkezz be!
	</div>
	<?php $this->assign('prevpage', 'video'); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "block_account.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>