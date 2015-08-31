<?php /* Smarty version 2.6.16, created on 2011-06-22 17:07:38
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'hunchars', 'header.tpl', 8, false),)), $this); ?>
<div id="header">
    <div style="float: left;">
    	<a href="index.php"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/logo.gif" /></a>
    </div>
    <div style="float: right; padding-top: 70px;" id="topmenu">
    	<div>
            <?php $_from = $this->_tpl_vars['topmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['topmenu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['topmenu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['topmenu']['iteration']++;
?>
                <a href="/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['menu_name'])) ? $this->_run_mod_handler('hunchars', true, $_tmp) : smarty_modifier_hunchars($_tmp)); ?>
/<?php echo $this->_tpl_vars['data']['menu_id']; ?>
"><?php echo $this->_tpl_vars['data']['menu_name']; ?>
</a> <?php if (! ($this->_foreach['topmenu']['iteration'] == $this->_foreach['topmenu']['total'])): ?>&middot;<?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
        </div>
    </div>
</div>