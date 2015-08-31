<?php /* Smarty version 2.6.16, created on 2007-06-08 11:36:50
         compiled from shop_basket_block.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop_basket_block.tpl', 2, false),)), $this); ?>
<div id="basket">
	<div id="basket_top"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_shop']['block_basket_header'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div id="basket_content">
		<div>
			<?php $_from = $this->_tpl_vars['basket']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['test'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['test']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['test']['iteration']++;
?>
                <b><?php echo $this->_foreach['test']['iteration']; ?>
.</b> <?php echo $this->_tpl_vars['data']['pname']; ?>
<br />
                <?php echo $this->_tpl_vars['locale']['index_shop']['block_basket_amount']; ?>
 <?php echo $this->_tpl_vars['data']['amount']; ?>
db<br />
                <?php echo $this->_tpl_vars['locale']['index_shop']['block_basket_price']; ?>
 <?php echo $this->_tpl_vars['data']['price']; ?>
<br />
                <?php echo $this->_tpl_vars['locale']['index_shop']['block_basket_netto']; ?>
 <?php echo $this->_tpl_vars['data']['sum']; ?>
<br />
            <?php endforeach; else: ?>
                <?php echo $this->_tpl_vars['locale']['index_shop']['block_basket_warning_empty']; ?>

            <?php endif; unset($_from); ?>
		</div>
		<div id="bsktarget"></div>
		<div id="osszar"><br /><?php echo $this->_tpl_vars['allsum']; ?>
</div>
        <div style="margin-bottom: 5px;"><a href="index.php?p=shop&amp;act=bsk" title="<?php echo $this->_tpl_vars['locale']['index_shop']['block_basket_modify']; ?>
"><?php echo $this->_tpl_vars['locale']['index_shop']['block_basket_modify']; ?>
</a></div>
	</div>
	<div id="basket_bottom"></div>
</div>