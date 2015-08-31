<?php /* Smarty version 2.6.16, created on 2007-07-03 14:01:45
         compiled from admin/system.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'admin/system.tpl', 5, false),)), $this); ?>
<div id="table">
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
	<div class="t_content">
		<div class="t_filter"></div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>

		<table>
			<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=sys" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemSystem']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemSystem']; ?>
</a></td></tr>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemContent']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=cont" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemContent']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemContent']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemTinyMCE']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=mce" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemTinyMCE']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemTinyMCE']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemDownload']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=dwn" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemDownload']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemDownload']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemGallery']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=gal" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemGallery']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemGallery']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemBanner']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=ban" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemBanner']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemBanner']; ?>
</a></td></tr>
				<?php if (file_exists ( "admin/banners_system.php" )): ?>
					<tr><td><a href="admin.php?p=banners_system" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemPlaces']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemPlaces']; ?>
</a></td></tr>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemPartners']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=partners" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemPartners']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemPartners']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemShop']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=sho" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemShop']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemShop']; ?>
</a></td></tr>
				<?php if (file_exists ( "admin/shop_system.php" )): ?>
					<tr><td><a href="admin.php?p=shop_system" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemProp']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemProp']; ?>
</a></td></tr>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemBuilderTitle']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=builder" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemBuilderTitle']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemBuilderTitle']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemStatTitle']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=stat" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemStatTitle']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemStatTitle']; ?>
</a></td></tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['lang_system']['strAdminSystemClassTitle']): ?>
				<tr><td><a href="admin.php?p=system&amp;act=mod&amp;type=class" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemClassTitle']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemClassTitle']; ?>
</a></td></tr>
			<?php endif; ?>
            <tr><td><a href="admin.php?p=users_system" title="<?php echo $this->_tpl_vars['lang_system']['strAdminSystemUsersTitle']; ?>
"><?php echo $this->_tpl_vars['lang_system']['strAdminSystemUsersTitle']; ?>
</a></td></tr>
		</table>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>