<?php /* Smarty version 2.6.16, created on 2007-12-13 10:17:02
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'index.tpl', 62, false),array('function', 'math', 'index.tpl', 93, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
    <title><?php echo $this->_tpl_vars['sitename'];  if ($this->_tpl_vars['content_title']): ?> - <?php echo $this->_tpl_vars['content_title'];  endif; ?></title>
    <base href="<?php echo $_SESSION['site_sitehttp']; ?>
/" />

    <meta name="description" content="<?php echo $this->_tpl_vars['meta_tags']['description']; ?>
" />
    <meta name="keywords" content="<?php echo $this->_tpl_vars['meta_tags']['keywords']; ?>
" />

	<meta name="robots" content="index,follow" />
	<meta name="author" content="Dolphinet Kft., Hungary" />
	<meta name="revisit-after" content="7 days" />

	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['locale_charset']; ?>
" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=<?php echo $this->_tpl_vars['locale_charset']; ?>
" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/favicon.ico" />

	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/style.css" />
	<?php $_from = $this->_tpl_vars['css']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/<?php echo $this->_tpl_vars['c']; ?>
.css" />
	<?php endforeach; endif; unset($_from); ?>
	<link rel="stylesheet" type="text/css" media="print" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/print.css" />

	<?php if ($this->_tpl_vars['bannerek']): ?>
		<script type="text/javascript">//<![CDATA[
			bid    = new Array();
			pid    = new Array();
			mid    = new Array();
			pic    = new Array();
			width  = new Array();
			height = new Array();
			type   = new Array();
			reload = new Array();
            code   = new Array();

			<?php echo $this->_tpl_vars['bannerek']; ?>

		//]]>
		</script>
	<?php endif; ?>

    <?php if ($this->_tpl_vars['ajax']['link']): ?><script type="text/javascript" src="<?php echo $this->_tpl_vars['ajax']['link']; ?>
"></script><?php endif; ?>
    <?php if ($this->_tpl_vars['ajax']['script']): ?>
        <script type="text/javascript">
            //<![CDATA[<?php echo $this->_tpl_vars['ajax']['script']; ?>
//]]>
        </script>
    <?php endif; ?>

	<?php $_from = $this->_tpl_vars['javascripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js']):
?>
	<?php if (preg_match ( "/\.js/" , $this->_tpl_vars['js'] )): ?>
	    <script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/<?php echo $this->_tpl_vars['js']; ?>
"></script>
	<?php else: ?>
		<script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/<?php echo $this->_tpl_vars['js']; ?>
.js"></script>
    <?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
</head>

<body <?php if ($this->_tpl_vars['bodyonload']): ?>onload="<?php $_from = $this->_tpl_vars['bodyonload']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['load']):
 echo $this->_tpl_vars['load']; ?>
;<?php endforeach; endif; unset($_from); ?>"<?php endif; ?>>
<div style="width: 900px;">
    <?php echo smarty_function_counter(array('name' => 'banners','print' => 0,'assign' => 'divBannerCnt','start' => 0), $this);?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "banner_div.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<div style="float: left; width: inherit;">
		<?php $_from = $this->_tpl_vars['builder']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cols'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cols']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['columns']):
        $this->_foreach['cols']['iteration']++;
?>
			 <div style="width: <?php echo $this->_tpl_vars['colwidth'][($this->_foreach['cols']['iteration']-1)];  echo $_SESSION['site_builder_columns_measure']; ?>
; float: left;">
			 <?php if (( $this->_tpl_vars['site_errors'] || $this->_tpl_vars['site_success'] || $this->_tpl_vars['page'] != "" ) && $_SESSION['site_builder_content_column'] == $this->_foreach['cols']['iteration']): ?>
			 	<?php if ($this->_tpl_vars['site_errors']): ?>
					<?php $_from = $this->_tpl_vars['site_errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
					<div style="text-align: center;">
						<?php echo $this->_tpl_vars['data']['text']; ?>
<br />
						<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
" title="<?php echo $this->_tpl_vars['locale']['config']['back_link']; ?>
"><?php echo $this->_tpl_vars['locale']['config']['back_link']; ?>
</a>
					</div>
					<?php endforeach; endif; unset($_from); ?>
				<?php elseif ($this->_tpl_vars['site_success']): ?>
					<?php $_from = $this->_tpl_vars['site_success']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
					<div style="text-align: center;">
						<?php echo $this->_tpl_vars['data']['text']; ?>
<br />
						<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
" title="<?php echo $this->_tpl_vars['locale']['config']['next_link']; ?>
"><?php echo $this->_tpl_vars['locale']['config']['next_link']; ?>
</a>
					</div>
					<?php endforeach; endif; unset($_from); ?>
				<?php else: ?>
					<div style="float: left; margin-left: 5px; width: 500px; owerflow: hidden;">
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['page']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>
				<?php endif; ?>
			 <?php else: ?>
			 	<?php $_from = $this->_tpl_vars['columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['boxs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['boxs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['boxes']):
        $this->_foreach['boxs']['iteration']++;
?>
					<div style="width: 100%; float: left; clear: both;">
						<?php $_from = $this->_tpl_vars['boxes']['contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['conts'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['conts']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['contents']):
        $this->_foreach['conts']['iteration']++;
?>
							<div style="width: <?php echo smarty_function_math(array('equation' => "x/y",'x' => 100,'y' => $this->_foreach['conts']['total']), $this);?>
%; float: left;">
								<?php if ($this->_tpl_vars['contents']['menu_pos']): ?>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "builder_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['contents']['content_id']): ?>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "builder_content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['contents']['category_id']): ?>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "builder_category.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['contents']['block']): ?>
									<?php $this->assign('block', $this->_tpl_vars['contents']['block']); ?>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['block']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['contents']['module_id']): ?>
									<?php $this->assign('module', $this->_tpl_vars['contents']['module_id']); ?>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['module']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['contents']['banner_pos']): ?>
								    <?php echo smarty_function_counter(array('name' => 'banners'), $this);?>

								    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "banner_div.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								    								<?php endif; ?>
								<?php if ($this->_tpl_vars['contents']['gallery_id']): ?>
									
								<?php endif; ?>
							</div>
						<?php endforeach; endif; unset($_from); ?>
					</div>
				<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
			 </div>
		<?php endforeach; endif; unset($_from); ?>
	</div>
								 
	
	<div id="footer">
		<div id="footer_left"><?php echo $this->_tpl_vars['locale']['iblocks']['info_copyright']; ?>
</div>
		<div id="footer_right"><?php echo $this->_tpl_vars['locale']['iblocks']['info_made_by']; ?>
</div>
	</div>
</div>
</body>

</html>