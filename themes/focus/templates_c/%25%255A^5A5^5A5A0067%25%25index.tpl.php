<?php /* Smarty version 2.6.16, created on 2007-06-08 11:15:17
         compiled from admin/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'admin/index.tpl', 51, false),array('modifier', 'upper', 'admin/index.tpl', 102, false),)), $this); ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
	<title><?php echo $this->_tpl_vars['sitename']; ?>
: <?php echo $this->_tpl_vars['locale']['admin']['title_admin']; ?>
</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/ishark.css" />

	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['locale_charset']; ?>
" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=<?php echo $this->_tpl_vars['locale_charset']; ?>
" />

	<?php $_from = $this->_tpl_vars['javascripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js']):
?>
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/<?php echo $this->_tpl_vars['js']; ?>
.js"></script>
	<?php endforeach; endif; unset($_from); ?>

	<?php if ($this->_tpl_vars['ajax']['link']): ?>
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['ajax']['link']; ?>
"></script>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['ajax']['script']): ?>
		<script type="text/javascript">
			//<![CDATA[<?php echo $this->_tpl_vars['ajax']['script']; ?>
//]]>
		</script>
	<?php endif; ?>
</head>

<body <?php if ($this->_tpl_vars['bodyonload']): ?>onLoad="<?php $_from = $this->_tpl_vars['bodyonload']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['load']):
 echo $this->_tpl_vars['load']; ?>
;<?php endforeach; endif; unset($_from); ?>"<?php endif; ?>>
	<div id="container_main">
		<!-- HEADER START -->
		<div id="header">
			<div id="header_left"></div>
			<div id="header_center"></div>
			<div id="header_right"></div>
		</div>
		<!-- HEADER END -->

		<!-- MENU START -->
		<div id="menu">
		<?php $_from = $this->_tpl_vars['admin_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['menu']['iteration']++;
?>
			<span><?php if (! ($this->_foreach['menu']['iteration'] <= 1)): ?>|<?php endif; ?><a class="menu" href="admin.php?p=<?php echo $this->_tpl_vars['data']['mfile']; ?>
" title="<?php echo $this->_tpl_vars['data']['mname']; ?>
"><?php echo $this->_tpl_vars['data']['mname']; ?>
</a></span>
		<?php endforeach; endif; unset($_from); ?>
		</div>
		<!-- MENU END -->

		<!-- BREADCRUMB START -->
		<div id="breadcrumb">
		<?php $_from = $this->_tpl_vars['breadcrumb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['bcrumb'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['bcrumb']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['bcdata']):
        $this->_foreach['bcrumb']['iteration']++;
?>
			<?php if (! ($this->_foreach['bcrumb']['iteration'] <= 1)): ?>
			&#x95;
			<?php endif; ?>
			<?php if ($this->_tpl_vars['bcdata']['link'] == ''): ?>
			<span><?php echo ((is_array($_tmp=$this->_tpl_vars['bcdata']['title'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</span>
			<?php else: ?>
			<a href="<?php echo $this->_tpl_vars['bcdata']['link']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['bcdata']['title'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['bcdata']['title'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		</div>
		<!-- BREADCRUMB END -->

		<!-- CONTENT START -->
		<?php if ($this->_tpl_vars['page'] != ""): ?>
			<div id="control">
				<div id="title">
					<?php if (isset ( $this->_tpl_vars['title_module'] )): ?>
						<?php $this->assign('modulepic', $_GET['p']); ?>
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['modulepic']; ?>
_small.jpg" border="0" alt="<?php echo $this->_tpl_vars['title_module']['title']; ?>
" align="middle" />
						<span><?php echo $this->_tpl_vars['title_module']['title']; ?>
</span>
					<?php endif; ?>
				</div>
				<div id="icons">
					<?php if (isset ( $this->_tpl_vars['add_new'] )): ?>
						<?php $_from = $this->_tpl_vars['add_new']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
						<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
" title="<?php echo $this->_tpl_vars['data']['title']; ?>
" accesskey="A">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['data']['pic']; ?>
" border="0" alt="<?php echo $this->_tpl_vars['data']['title']; ?>
" align="middle" />
						</a>
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
					<?php if (isset ( $this->_tpl_vars['back_arrow'] )): ?>
						<a href="<?php echo $this->_tpl_vars['back_arrow']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin']['back']; ?>
" accesskey="B">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/back.jpg" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin']['back']; ?>
" align="middle" />
						</a>
					<?php else: ?>
						<a href="javascript:history.back(-1)" title="<?php echo $this->_tpl_vars['locale']['admin']['back']; ?>
" accesskey="B">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/back.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['back']; ?>
" border="0" align="middle" />
						</a>
					<?php endif; ?>
					<a href="admin.php" title="<?php echo $this->_tpl_vars['locale']['admin']['center']; ?>
" accesskey="C"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/center.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['center']; ?>
" border="0" align="middle" /></a>
					<span class="logout">
      					<a href="index.php" title="<?php echo $this->_tpl_vars['locale']['admin']['backtomain_link']; ?>
" accesskey="M">
       					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/backtomain.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['backtomain_link']; ?>
" border="0" />
      					</a>
      					<a href="index.php?p=account&amp;act=account_out" title="<?php echo $this->_tpl_vars['locale']['admin']['logout']; ?>
" accesskey="L">
       					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/logout.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['logout']; ?>
" border="0" />
      					</a>
     				</span>
				</div>
			</div>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/".($this->_tpl_vars['page']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php else: ?>
			<div id="control">
				<div id="title">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/center_small.jpg" border="0" alt="<?php echo $this->_tpl_vars['title_admin']['title']; ?>
" align="middle" />
					<span><?php echo ((is_array($_tmp=$this->_tpl_vars['title_admin']['title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
				</div>
				<div id="icons">
					<a href="javascript:history.back(-1)" title="<?php echo $this->_tpl_vars['locale']['admin']['back']; ?>
" accesskey="B"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/back.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['back']; ?>
" border="0" align="middle" /></a>
					<a href="admin.php" title="<?php echo $this->_tpl_vars['locale']['admin']['center']; ?>
" accesskey="C"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/center.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['center']; ?>
" border="0" /></a>
					<span class="logout">
      					<a href="index.php" title="<?php echo $this->_tpl_vars['locale']['admin']['backtomain_link']; ?>
" accesskey="M">
       					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/backtomain.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['backtomain_link']; ?>
" border="0" />
      					</a>
      					<a href="index.php?p=account&amp;act=account_out" title="<?php echo $this->_tpl_vars['locale']['admin']['logout']; ?>
" accesskey="L">
       					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/logout.jpg" alt="<?php echo $this->_tpl_vars['locale']['admin']['logout']; ?>
" border="0" />
      					</a>
     				</span>
				</div>
			</div>
			<div id="cnt">
				<div id="c_top"></div>
				<div id="c_content">
					<?php $_from = $this->_tpl_vars['admin_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['menu']['iteration']++;
?>
						<a href="admin.php?p=<?php echo $this->_tpl_vars['data']['mfile']; ?>
" class="linkopacity c_icon" title="<?php echo $this->_tpl_vars['data']['mname']; ?>
">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['data']['mfile']; ?>
.jpg" class="c_image" alt="<?php echo $this->_tpl_vars['data']['mname']; ?>
" border="0" />
							<span class="c_link"><?php echo $this->_tpl_vars['data']['mname']; ?>
</span>
						</a>
					<?php endforeach; endif; unset($_from); ?>
				</div>
				<div id="c_bottom"></div>
			</div>
		<?php endif; ?>
		<div id="help">
			<div id="help_top"></div>
			<div id="help_border">
				<div id="help_content">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/help_content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</div>
			</div>
			<div id="help_bottom"></div>
		</div>
		<!-- CONTENT END -->

		<!-- FOOTER START -->
		<div id="footer">
			<div id="footer_left"><?php echo $this->_tpl_vars['locale']['admin']['footer']; ?>
</div>
			<div id="footer_right"><?php echo $this->_tpl_vars['locale']['admin']['copyright']; ?>
</div>
		</div>
		<!-- FOOTER END-->
	</div>

<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/wz_tooltip.js"></script>
</body>
</html>