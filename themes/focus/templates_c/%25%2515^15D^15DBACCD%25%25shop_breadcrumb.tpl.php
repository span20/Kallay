<?php /* Smarty version 2.6.16, created on 2007-07-13 10:25:54
         compiled from shop_breadcrumb.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'shop_breadcrumb.tpl', 6, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['shop_breadcrumb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['bcrumb'] = array('total' => count($_from), 'iteration' => 0);
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
	<?php endif;  endforeach; endif; unset($_from); ?>