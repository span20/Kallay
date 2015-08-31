<?php /* Smarty version 2.6.16, created on 2007-06-28 14:39:46
         compiled from admin/settings_list.tpl */ ?>
<div id="cnt">
	<div id="c_top"></div>
		<div id="c_content">
			<?php $_from = $this->_tpl_vars['settinglist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['menu'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['menu']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['menu']['iteration']++;
?>
    			<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;file=<?php echo $this->_tpl_vars['data']['mfile'];  echo $this->_tpl_vars['data']['mext']; ?>
&amp;act=mod" class="linkopacity s_icon" title="<?php echo $this->_tpl_vars['data']['mname']; ?>
">
    				<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/settings/<?php echo $this->_tpl_vars['data']['mfile']; ?>
.jpg" class="c_image" alt="<?php echo $this->_tpl_vars['data']['mname']; ?>
" border="0" />
    				<span class="c_link"><?php echo $this->_tpl_vars['data']['mname']; ?>
</span>
    			</a>
            <?php endforeach; else: ?>
                <?php echo $this->_tpl_vars['locale']['admin_settings']['warning_empty_list']; ?>

			<?php endif; unset($_from); ?>
		</div>
	<div id="c_bottom"></div>
</div>
