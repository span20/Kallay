<?php /* Smarty version 2.6.16, created on 2007-06-08 11:36:50
         compiled from shop_search_block.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop_search_block.tpl', 4, false),)), $this); ?>
<div id="block" style="margin-top: 10px;">
	<form <?php echo $this->_tpl_vars['form_search_block']['attributes']; ?>
>
	<?php echo $this->_tpl_vars['form_search_block']['hidden']; ?>

	<div id="b_top"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_search_block']['header']['search'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div id="b_content">
		<div>
			<div><?php echo $this->_tpl_vars['form_search_block']['searchtext']['label']; ?>
</div>
			<div><?php echo $this->_tpl_vars['form_search_block']['searchtext']['html']; ?>
</div>
		</div>
		<div><?php echo $this->_tpl_vars['form_search_block']['submit']['html']; ?>
</div>
		<div><a href="index.php?p=shop&amp;act=sea" title="<?php echo $this->_tpl_vars['locale']['index_shop']['block_search_detailsearch']; ?>
"><?php echo $this->_tpl_vars['locale']['index_shop']['block_search_detailsearch']; ?>
</a></div>
	</div>
	<div id="b_bottom"></div>
	</form>
</div>