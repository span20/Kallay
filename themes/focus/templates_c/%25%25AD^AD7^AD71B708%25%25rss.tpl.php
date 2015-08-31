<?php /* Smarty version 2.6.16, created on 2007-07-11 12:10:52
         compiled from rss.tpl */ ?>
<div style="padding-left: 5px;">
	<div class="szoveg" style="padding-bottom: 5px; padding-top:10px; padding-left: 5px;">
	<table width="100%" cellspacing="0" cellpadding="3">
		<?php $_from = $this->_tpl_vars['rss']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="row1">
				<td width="40%"><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
				<td><?php echo $_SESSION['site_sitehttp']; ?>
/modules/<?php echo $this->_tpl_vars['data']['url']; ?>
</td>
				<td><a href="<?php echo $_SESSION['site_sitehttp']; ?>
/modules/<?php echo $this->_tpl_vars['data']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/xml_button.gif" border="0"></a></td>
			</tr>
			<tr>
				<td colspan="3"><?php echo $this->_tpl_vars['data']['desc']; ?>
</td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
	</div>
</div>