<?php /* Smarty version 2.6.16, created on 2015-06-12 09:54:55
         compiled from admin/login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/login.tpl', 39, false),)), $this); ?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
	<title><?php echo $this->_tpl_vars['sitename']; ?>
: <?php echo $this->_tpl_vars['lang_admin']['strAdminTitle']; ?>
</title>
<!--	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/ishark.css" /> !-->
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/login.css" />

	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['lang_admin']['strLangCharset']; ?>
" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=<?php echo $this->_tpl_vars['lang_Admin']['strLangCharset']; ?>
" />

	<?php $_from = $this->_tpl_vars['javascripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js']):
?>
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/<?php echo $this->_tpl_vars['js']; ?>
.js"></script>
	<?php endforeach; endif; unset($_from); ?>
</head>

<body>
	<div id="outer">
		<!-- HEADER START -->
		<div id="header">
			<div id="header_left"></div>
			<div id="header_right"></div>
		</div>
		<!-- HEADER END -->

		<!-- MENU START -->
		<div id="menu">&nbsp;</div>
		<!-- MENU END -->
		<div id="middle"><div id="inner">
			<div id="login">
				<div id="l_top"></div>
				<div id="l_content">
					<div class="l_empty"></div>
					<div class="pager"></div>
					<div id="l_form">
						<div id="loginpic"></div>
						<div>
							<p style="font-size: 13px; font-weight: bold; text-align: left; padding-top: 15px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_admin']['strAdminLoginHeader'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</p>
							<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
							<?php echo $this->_tpl_vars['form']['hidden']; ?>

								<p style="text-align: left; font-weight: bold;">
									<span>
										<?php echo $this->_tpl_vars['form']['name']['label']; ?>

									</span><br />
									<span>
										<?php if ($this->_tpl_vars['form']['name']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['name']['error']; ?>
</font><br /><?php endif;  echo $this->_tpl_vars['form']['name']['html']; ?>

									</span>
								</p>
								<p style="text-align: left; font-weight: bold;">
									<span>
										<?php echo $this->_tpl_vars['form']['pass']['label']; ?>

									</span><br />
									<span>
										<?php if ($this->_tpl_vars['form']['pass']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['pass']['error']; ?>
</font><br /><?php endif;  echo $this->_tpl_vars['form']['pass']['html']; ?>

									</span>
								</p>
								<p style="text-align: left;"><?php if ($this->_tpl_vars['form']['requirednote'] && ! $this->_tpl_vars['form']['frozen']):  echo $this->_tpl_vars['form']['requirednote'];  endif;  echo $this->_tpl_vars['form']['submit']['html'];  echo $this->_tpl_vars['form']['reset']['html']; ?>
</p>
							</form>
						</div>
					</div>
					<div class="pager"></div>
					<div class="l_empty"></div>
				</div>
				<div id="l_bottom"></div>
			</div>
		</div></div>
		<!-- FOOTER START -->
		<div id="footer">
			<div id="footer_left"><?php echo $this->_tpl_vars['lang_admin']['strFooterShark']; ?>
</div>
			<div id="footer_right"><?php echo $this->_tpl_vars['lang_admin']['strFooterCopyright']; ?>
</div>
		</div>
		<!-- FOOTER END-->
	</div>
</body>
</html>