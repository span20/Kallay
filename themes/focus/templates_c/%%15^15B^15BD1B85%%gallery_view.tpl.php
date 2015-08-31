<?php /* Smarty version 2.6.16, created on 2011-01-13 21:24:29
         compiled from admin/gallery_view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/gallery_view.tpl', 5, false),array('modifier', 'htmlspecialchars', 'admin/gallery_view.tpl', 9, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div class="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
		</div>
		<div class="pager"></div>
			<div style="display: block; text-align: center;">
				<p><img src="<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['pic']['realname']; ?>
" width="<?php echo $this->_tpl_vars['pic']['width']; ?>
" height="<?php echo $this->_tpl_vars['pic']['height']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['pic']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" /></p>
                <p><?php echo $this->_tpl_vars['locale']['admin_gallery']['field_view_width']; ?>
 <?php echo $this->_tpl_vars['pic']['width']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_gallery']['field_pixel']; ?>
, <?php echo $this->_tpl_vars['locale']['admin_gallery']['field_view_height']; ?>
 <?php echo $this->_tpl_vars['pic']['height']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_gallery']['field_pixel']; ?>
</p>
			</div>
		<div class="pager"></div>
	</div>
	<div id="t_bottom"></div>
</div>