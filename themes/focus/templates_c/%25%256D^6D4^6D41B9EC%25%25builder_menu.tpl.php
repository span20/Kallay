<?php /* Smarty version 2.6.16, created on 2007-06-08 11:36:50
         compiled from builder_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'builder_menu.tpl', 7, false),)), $this); ?>
<div style="height: 200px; border: 2px dotted brown;">
	<?php if (!function_exists('smarty_fun_menu')) { function smarty_fun_menu(&$this, $params) { $_fun_tpl_vars = $this->_tpl_vars; $this->assign($params);  ?>
		<ul>
			<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menupont']):
?>
				<li>
					<?php if ($this->_tpl_vars['list']['0']['level'] == '1'): ?>
						<div><a href="index.php?mid=<?php echo $this->_tpl_vars['menupont']['menu_id']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['menupont']['menu_name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['menupont']['menu_name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a></div>
					<?php else: ?>
						<a href="index.php?mid=<?php echo $this->_tpl_vars['menupont']['menu_id']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['menupont']['menu_name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['menupont']['menu_name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a>
					<?php endif; ?>							
					<?php if ($this->_tpl_vars['menupont']['element']): ?>
						<?php smarty_fun_menu($this, array('list'=>$this->_tpl_vars['menupont']['element']));  ?>
					<?php endif; ?>
				</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
	<?php  $this->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_menu($this, array('list'=>$this->_tpl_vars['contents']['menu_pos']));  ?>
</div>