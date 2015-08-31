<?php /* Smarty version 2.6.16, created on 2007-06-19 15:34:11
         compiled from admin/searchwords_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/searchwords_list.tpl', 31, false),array('modifier', 'htmlspecialchars', 'admin/searchwords_list.tpl', 52, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="t_content">
		<div class="t_filter">&nbsp;</div>
        <div class="pager">&nbsp;</div>
        <table>
            <tr>
                <th class="first"><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_lang']; ?>
</th>
                <th></th>
                <th><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_name']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_addname']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_adddate']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_modname']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_moddate']; ?>
</th>
                <th class="last"><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_action']; ?>
</th>
            </tr>
            <tr class="row1">
                <td class="first"></td>
                <td></td>
                <td><?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_indexpage']; ?>
</td>
                <td><?php echo $this->_tpl_vars['index_data']['add_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['index_data']['add_date']; ?>
</td>
                <td><?php echo $this->_tpl_vars['index_data']['mod_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['index_data']['mod_date']; ?>
</td>
                <td class="last">
                    <a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;m_mid=0" title="<?php echo $this->_tpl_vars['locale']['admin_searchwords']['list_indexpage']; ?>
"></a>
                </td>
            </tr>
        <?php if (!function_exists('smarty_fun_tree')) { function smarty_fun_tree(&$this, $params) { $_fun_tpl_vars = $this->_tpl_vars; $this->assign($params);  ?>
            <?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
            <tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
                <td class="first">
                    <?php $this->assign('flag', $this->_tpl_vars['menu']['mlang']); ?>
                    <?php $this->assign('flagpic', "flag_".($this->_tpl_vars['flag']).".gif"); ?>
                    <?php if (file_exists ( ($this->_tpl_vars['theme_dir'])."/images/admin/".($this->_tpl_vars['flagpic']) )): ?>
                        <img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/<?php echo $this->_tpl_vars['flagpic']; ?>
" alt="<?php echo $this->_tpl_vars['menu']['mlang']; ?>
" />
                    <?php else: ?>
                        <?php echo $this->_tpl_vars['menu']['mlang']; ?>

                    <?php endif; ?>
                </td>
                <td>
                <?php if ($this->_tpl_vars['menu']['is_sub'] == '1'): ?>
                    <a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&mid=<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
" style="font-size: 14px; font-weight: bold"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/arrow_down.gif" border="0" alt=""></a>
                <?php endif; ?>
                </td>
                <td <?php if ($this->_tpl_vars['menu']['level'] > 1): ?>style="padding-left: <?php echo $this->_tpl_vars['menu']['level']*10; ?>
px;"<?php endif; ?>><?php echo $this->_tpl_vars['menu']['menu_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['menu']['add_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['menu']['add_date']; ?>
</td>
                <td><?php echo $this->_tpl_vars['menu']['mod_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['menu']['mod_date']; ?>
</td>
                <td class="last">
	               <a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;m_mid=<?php echo $this->_tpl_vars['menu']['menu_id']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['menu']['menu_name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"></a>
                </td>
            </tr>
    	    <?php if ($this->_tpl_vars['menu']['element']): ?>
                <?php smarty_fun_tree($this, array('list'=>$this->_tpl_vars['menu']['element']));  ?>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
        <?php  $this->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_tree($this, array('list'=>$this->_tpl_vars['menu_array']));  ?>
	   </table>
		<div class="pager">&nbsp;</div>
		<div class="t_empty"></div>
	</div>
	<div class="t_bottom"></div>
</div>