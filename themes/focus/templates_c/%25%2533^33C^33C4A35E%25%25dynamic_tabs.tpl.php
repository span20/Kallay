<?php /* Smarty version 2.6.16, created on 2007-06-08 11:15:17
         compiled from admin/dynamic_tabs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'admin/dynamic_tabs.tpl', 4, false),)), $this); ?>
<div class="tabs">
	<ul>
		<?php $_from = $this->_tpl_vars['dynamic_tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tabcode'] => $this->_tpl_vars['tabname']):
?>
		<li<?php if ($this->_tpl_vars['this_page'] == $this->_tpl_vars['tabcode']): ?> class="current"<?php endif; ?>><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['tabcode']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['tabname'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['tabname'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a></li>
		<?php endforeach; else: ?>
		<li class="current"><a href="#">...</a></li>
		<?php endif; unset($_from); ?>
	</ul>
	<div class="blueleft"></div><div class="blueright"></div>
</div>
