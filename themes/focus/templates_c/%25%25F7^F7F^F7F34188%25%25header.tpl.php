<?php /* Smarty version 2.6.16, created on 2007-06-08 11:36:50
         compiled from header.tpl */ ?>
<table width="920" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="bottom"><a href="index.php"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/logo.png" border="0" alt="logo"></a></td>
		<td valign="bottom"></td>
	</tr>
	<tr>
		<td style="height: 19px; background-color: #CECFD1;">
			<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/menu_left.jpg" border="0" alt="">
		</td>
		<td valign="middle" style="height: 19px; background-color: #CECFD1;">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td><a class="topmenu" href="index.php" title="<?php echo $this->_tpl_vars['main']['strIndexIndexpage']; ?>
"><?php echo $this->_tpl_vars['main']['strIndexIndexpage']; ?>
</a></td>
					<td><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/point.jpg" align="absmiddle" style="height: 19px; width: 7px;" border="0" alt=""></td>
				<?php $_from = $this->_tpl_vars['mainmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['menu']['iteration']++;
?>
					<?php if ($this->_tpl_vars['data']['posname'] == top && $this->_tpl_vars['data']['level'] == 0): ?>
						<?php if (! ($this->_foreach['menu']['iteration'] <= 1)): ?>
							<td><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/point.jpg" align="absmiddle" style="height: 19px; width: 7px;" border="0" alt=""></td>
						<?php endif; ?>
						<td><a class="topmenu" href="index.php?mid=<?php echo $this->_tpl_vars['data']['mid']; ?>
" <?php if ($this->_tpl_vars['data']['mblank'] == 1): ?>target="_blank"<?php endif; ?> title="<?php echo $this->_tpl_vars['data']['title']; ?>
"><?php echo $this->_tpl_vars['data']['title']; ?>
</a></td>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/line.jpg" border="0" alt=""></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div style="padding-left: 190px; padding-top: 15px;">
	<?php if ($this->_tpl_vars['shop_breadcrumb']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "shop_breadcrumb.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	<?php if ($this->_tpl_vars['class_breadcrumb']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "class_breadcrumb.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</div>