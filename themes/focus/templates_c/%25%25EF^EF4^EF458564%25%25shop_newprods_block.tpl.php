<?php /* Smarty version 2.6.16, created on 2007-06-08 11:36:50
         compiled from shop_newprods_block.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop_newprods_block.tpl', 3, false),array('function', 'math', 'shop_newprods_block.tpl', 19, false),)), $this); ?>
<?php if ($this->_tpl_vars['newprodsnum'] != 0): ?>
<div id="block" style="margin-top: 10px;">
	<div id="b_top"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_shop']['block_newprods_header'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div id="b_content">
		<?php $_from = $this->_tpl_vars['newprods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['newprodkey'] => $this->_tpl_vars['newprod']):
        $this->_foreach['products']['iteration']++;
?>
			<div style="margin-right: 30px; <?php if (! ($this->_foreach['products']['iteration'] == $this->_foreach['products']['total'])): ?>border-bottom: 1px solid;<?php endif; ?>">
				<p>
					<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;pid=<?php echo $this->_tpl_vars['newprodkey']; ?>
" title="<?php echo $this->_tpl_vars['newprod']['pname']; ?>
"><?php echo $this->_tpl_vars['newprod']['pname']; ?>
</a>
				</p>
				<?php if ($this->_tpl_vars['newprod']['pic'] != ""): ?>
					<p style="text-align: center">
						<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;pid=<?php echo $this->_tpl_vars['newprodkey']; ?>
" title="<?php echo $this->_tpl_vars['newprod']['pname']; ?>
">
							<img src="<?php echo $_SESSION['site_shop_prodpicdir']; ?>
/tn_<?php echo $this->_tpl_vars['newprod']['pic']; ?>
" alt="<?php echo $this->_tpl_vars['newprod']['pname']; ?>
" />
						</a>
					</p>
				<?php endif; ?>
				<p>
					<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['block_newprods_price']; ?>
</span> <span class="shop_extra"><?php echo $this->_tpl_vars['newprod']['netto']; ?>
</span>
					+ <?php echo $this->_tpl_vars['newprod']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['block_newprods_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['newprod']['netto'],'y' => $this->_tpl_vars['newprod']['afa']), $this);?>
</b></span>
				</p>
			</div>
		<?php endforeach; endif; unset($_from); ?>
	</div>
	<div id="b_bottom"></div>
</div>
<?php endif; ?>