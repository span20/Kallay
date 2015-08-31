<?php /* Smarty version 2.6.16, created on 2007-06-29 13:08:53
         compiled from admin/banners_act_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/banners_act_list.tpl', 5, false),array('modifier', 'regex_replace', 'admin/banners_act_list.tpl', 14, false),array('function', 'cycle', 'admin/banners_act_list.tpl', 32, false),)), $this); ?>
<div id="form_cnt">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="f_content">
		<div class="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['admin_banners']['field_list_activate_header'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
        </div>
		<form<?php echo $this->_tpl_vars['form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table>
			<tr>
				<td colspan="2" align="center">
                    <?php if ($this->_tpl_vars['actbanner']['0']['bcode'] == ""): ?>
    					<?php if ($this->_tpl_vars['actbanner']['0']['type'] == '4'): ?>
    						<object type="application/x-shockwave-flash" width="<?php echo $this->_tpl_vars['actbanner']['0']['width']; ?>
" height="<?php echo $this->_tpl_vars['actbanner']['0']['height']; ?>
" data="<?php echo ((is_array($_tmp=$_SESSION['site_bannerdir'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "|/$|", "") : smarty_modifier_regex_replace($_tmp, "|/$|", "")); ?>
/<?php echo $this->_tpl_vars['actbanner']['0']['pic']; ?>
">
    							<param name="movie" value="<?php echo ((is_array($_tmp=$_SESSION['site_bannerdir'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "|/$|", "") : smarty_modifier_regex_replace($_tmp, "|/$|", "")); ?>
/<?php echo $this->_tpl_vars['actbanner']['0']['pic']; ?>
" />
    							<param name="quality" value="high" />
    							<param name="loop" value="true" />
    							<param name="FlashVars" value="playerMode=embedded" />
    							<param name="bgcolor" value="#000000" />
    						</object>
    					<?php else: ?>
    						<img width="<?php echo $this->_tpl_vars['actbanner']['0']['width']; ?>
" height="<?php echo $this->_tpl_vars['actbanner']['0']['height']; ?>
" src="<?php echo ((is_array($_tmp=$_SESSION['site_bannerdir'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "|/$|", "") : smarty_modifier_regex_replace($_tmp, "|/$|", "")); ?>
/<?php echo $this->_tpl_vars['actbanner']['0']['pic']; ?>
" />
    					<?php endif; ?>
                    <?php else: ?>
                        <?php echo $this->_tpl_vars['actbanner']['0']['bcode']; ?>

                    <?php endif; ?>
				</td>
			</tr>
			<?php $_from = $this->_tpl_vars['form']['sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['sec']):
?>
				<?php $_from = $this->_tpl_vars['sec']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
					<?php if ($this->_tpl_vars['element']['type'] != 'submit' && $this->_tpl_vars['element']['type'] != 'reset'): ?>
					<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<?php if ($this->_tpl_vars['element']['type'] == 'textarea'): ?>
						<td class="form" <?php if ($this->_tpl_vars['is_tiny'] == 1): ?>colspan="2"<?php endif; ?>>
							<?php if ($this->_tpl_vars['element']['required']): ?>
								<span class="error">*</span><?php endif;  echo $this->_tpl_vars['element']['label']; ?>
<br />
							<?php else: ?>
								<td class="form">
									<?php if ($this->_tpl_vars['element']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['element']['label']; ?>
</td>
								<td>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['element']['type'] == 'group'): ?>
								<?php $_from = $this->_tpl_vars['element']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gkey'] => $this->_tpl_vars['gitem']):
?>
									<?php echo $this->_tpl_vars['gitem']['label']; ?>

									<?php echo $this->_tpl_vars['gitem']['html'];  if ($this->_tpl_vars['gitem']['required']): ?><span class="error">*</span><?php endif; ?>
									<?php if ($this->_tpl_vars['element']['separator']):  echo smarty_function_cycle(array('values' => $this->_tpl_vars['element']['separator']), $this); endif; ?>
								<?php endforeach; endif; unset($_from); ?>
							<?php else: ?>
								<?php echo $this->_tpl_vars['element']['html']; ?>

							<?php endif; ?>
							<?php if ($this->_tpl_vars['element']['error']): ?>
								<span class="error"><?php echo $this->_tpl_vars['element']['error']; ?>
</span>
							<?php endif; ?>
						</td>
					</tr>
					<?php else: ?>
						<?php if ($this->_tpl_vars['element']['type'] != 'reset'): ?>
						<tr>
							<td class="form" colspan="2">
							<?php if (! $this->_tpl_vars['form']['frozen']): ?>
								<?php if ($this->_tpl_vars['form']['requirednote']):  echo $this->_tpl_vars['form']['requirednote'];  endif; ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php echo $this->_tpl_vars['element']['html']; ?>

						<?php if ($this->_tpl_vars['element']['type'] != 'submit'): ?>
							</td>
						</tr>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			<?php endforeach; endif; unset($_from); ?>
		</table>
        </form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>

<br>

<center><?php echo $this->_tpl_vars['page_list']; ?>
</center>
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_header']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_header']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="f_content">
		<div class="f_empty"></div>
        <table>
            <tr class="row1">
                <th class="first"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_place']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_menu']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_timer']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_impmax']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_imprest']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_click']; ?>
</th>
                <th><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_clickpercent']; ?>
</th>
                <th class="last" width="50"><?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate_action']; ?>
</th>
            </tr>
            <?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
            <tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
                <td class="first" valign="top"><?php echo $this->_tpl_vars['data']['place_name']; ?>
</td>
                <td valign="top"><?php echo $this->_tpl_vars['data']['menu_name']; ?>
</td>
                <td valign="top"><?php echo $this->_tpl_vars['data']['timer_start']; ?>
<br /><?php echo $this->_tpl_vars['data']['timer_end']; ?>
</td>
                <td valign="top"><?php echo $this->_tpl_vars['data']['impmax']; ?>
</td>
                <td valign="top"><?php echo $this->_tpl_vars['data']['imprest']; ?>
</td>
                <td valign="top"><?php echo $this->_tpl_vars['data']['click_count']; ?>
</td>
                <td valign="top"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
                <td class="last" valign="top">
                    <a class="action mod "href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=bactm&amp;oid=<?php echo $this->_tpl_vars['oid']; ?>
&amp;bid=<?php echo $this->_tpl_vars['bid']; ?>
&amp;mpid=<?php echo $this->_tpl_vars['data']['mpid']; ?>
" title="<?php echo $this->_tpl_vars['lang']['strAdminBannersActModify']; ?>
"></a>
                    <a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_banners']['confirm_del_activate']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=bactd&amp;oid=<?php echo $this->_tpl_vars['oid']; ?>
&amp;bid=<?php echo $this->_tpl_vars['bid']; ?>
&amp;mpid=<?php echo $this->_tpl_vars['data']['mpid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_active_delete']; ?>
"></a>
	           </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="8" class="empty">
                    <img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_active_notactive']; ?>
" />
                    <?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_active_notactive']; ?>

                </td>
            </tr>
            <?php endif; unset($_from); ?>
        </table>
        <div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>