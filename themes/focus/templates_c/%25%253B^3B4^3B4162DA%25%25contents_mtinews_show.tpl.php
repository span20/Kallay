<?php /* Smarty version 2.6.16, created on 2007-06-25 18:14:14
         compiled from admin/contents_mtinews_show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/contents_mtinews_show.tpl', 5, false),array('modifier', 'nl2br', 'admin/contents_mtinews_show.tpl', 31, false),array('modifier', 'urlencode', 'admin/contents_mtinews_show.tpl', 37, false),array('modifier', 'htmlspecialchars', 'admin/contents_mtinews_show.tpl', 37, false),)), $this); ?>
<div id="form_cnt">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="f_content">
		<div class="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
        </div>
		<div class="pager"></div>
		<table style="clear: both;">
            <tr class="row2">
                <td class="form" style="width: 150px;"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_id']; ?>
</td>
                <td><?php echo $_GET['cid']; ?>
</td>
            </tr>
			<tr class="row1">
				<td class="form" style="width: 150px;"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_title']; ?>
</td>
				<td><?php echo $this->_tpl_vars['elements']['title']; ?>
</td>
			</tr>
            <tr class="row2">
                <td class="form"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_section']; ?>
</td>
                <td><?php echo $this->_tpl_vars['elements']['mainsection']; ?>
</td>
            </tr>
            <tr class="row1">
                <td class="form"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_cdate']; ?>
</td>
                <td><?php echo $this->_tpl_vars['elements']['createdate']; ?>
</td>
            </tr>
            <tr class="row2">
                <td class="form"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_mdate']; ?>
</td>
                <td><?php echo $this->_tpl_vars['elements']['modifieddate']; ?>
</td>
            </tr>
			<tr class="row1">
				<td class="form"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_lead']; ?>
</td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['elements']['lead'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
			</tr>
            <tr class="row2">
                <td class="form"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_picture']; ?>
</td>
                <td>
                    <?php if ($this->_tpl_vars['elements']['image'] != ""): ?>
                        <img src="admin.php?p=contents&amp;act=mtinews&amp;sub_act=show&amp;pic=<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['elements']['image'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)))) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" alt="<?php echo $this->_tpl_vars['elements']['title']; ?>
" />
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="row1">
                <td class="form"><?php echo $this->_tpl_vars['locale']['admin_contents']['mtinews_field_body']; ?>
</td>
                <td><?php echo ((is_array($_tmp=$this->_tpl_vars['elements']['body'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr>
		</table>
        <div class="pager"></div>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>