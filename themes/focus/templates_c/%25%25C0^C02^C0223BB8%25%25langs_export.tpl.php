<?php /* Smarty version 2.6.16, created on 2007-06-11 15:50:29
         compiled from admin/langs_export.tpl */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="iso-8859-1"<?php echo '?>'; ?>

<area name="<?php echo $this->_tpl_vars['_module_name']; ?>
" lang="<?php echo $this->_tpl_vars['_locale_id']; ?>
">
<?php $_from = $this->_tpl_vars['_expressions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <variable name="<?php echo $this->_tpl_vars['data']['variable_name']; ?>
"><![CDATA[<?php echo $this->_tpl_vars['data']['expression']; ?>
]]></variable>
<?php endforeach; endif; unset($_from); ?>
</area>