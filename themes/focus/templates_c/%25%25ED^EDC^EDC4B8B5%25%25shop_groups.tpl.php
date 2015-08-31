<?php /* Smarty version 2.6.16, created on 2007-07-13 10:21:48
         compiled from admin/shop_groups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/shop_groups.tpl', 40, false),array('function', 'cycle', 'admin/shop_groups.tpl', 46, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['tiny_fields'] )): ?>
    <script type="text/javascript" src="<?php echo $this->_tpl_vars['libs_dir']; ?>
/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
    tinyMCE.init(
    <?php echo '
    {
    '; ?>

        mode                                : "exact",
        elements                            : "<?php echo $this->_tpl_vars['tiny_fields']; ?>
",
        theme_advanced_layout_manager       : "SimpleLayout",
        theme                               : "<?php echo $_SESSION['site_mce_theme']; ?>
",
        language                            : "<?php echo $_SESSION['site_mce_lang']; ?>
",
        external_link_list_url              : "includes/linklist.php",
        plugins                             : "table,advlink,advimage,simplebrowser,emotions,paste,preview",
        plugin_simplebrowser_width          : "800",
        plugin_simplebrowser_height         : "600",
        plugin_simplebrowser_browselinkurl  : 'simplebrowser/browser.html?Connector=connectors/php/connector.php',
        plugin_simplebrowser_browseimageurl : 'simplebrowser/browser.html?Type=Image&Connector=connectors/php/connector.php',
        plugin_simplebrowser_browseflashurl : 'simplebrowser/browser.html?Type=Flash&Connector=connectors/php/connector.php',
        theme_advanced_buttons2_add         : "separator,forecolor,backcolor,emotions,preview",
        theme_advanced_buttons3_add         : "separator,tablecontrols,separator,pasteword",
        content_css                         : "<?php echo $this->_tpl_vars['theme_dir']; ?>
/<?php echo $_SESSION['site_mce_css']; ?>
",
        width                               : "680",
        theme_advanced_toolbar_location     : "top",
        theme_advanced_statusbar_location   : "bottom",
        convert_urls                        : true,
        entity_encoding                     : "raw",
        plugin_preview_width                : "<?php echo $_SESSION['site_mce_pagewidth']; ?>
"
    <?php echo '
    }
    '; ?>

    );
    </script>
<?php endif; ?>

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
		<form <?php echo $this->_tpl_vars['form_shop']['attributes']; ?>
 onSubmit="return SelectAll(this);">
		<?php echo $this->_tpl_vars['form_shop']['hidden']; ?>

		<table>
			<?php if ($_SESSION['site_multilang'] == 1): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['languages']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['languages']['label']; ?>
</td>
				<td colspan="2"><?php echo $this->_tpl_vars['form_shop']['languages']['html'];  if ($this->_tpl_vars['form_shop']['languages']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['languages']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['name']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['name']['label']; ?>
</td>
				<td colspan="2"><?php echo $this->_tpl_vars['form_shop']['name']['html'];  if ($this->_tpl_vars['form_shop']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['name']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['category']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['category']['label']; ?>
</td>
				<td colspan="2"><?php echo $this->_tpl_vars['form_shop']['category']['html'];  if ($this->_tpl_vars['form_shop']['category']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['category']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" style="width: 33%;">
					<?php if ($this->_tpl_vars['form_shop']['srcList']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['srcList']['label']; ?>
<br />
					<input type="text" name="SearchInput" onKeyUp="JavaScript: searchSelectBox('frm_shop', 'SearchInput', 'srcList')"><br /><br />
					<?php echo $this->_tpl_vars['form_shop']['srcList']['html'];  if ($this->_tpl_vars['form_shop']['srcList']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['srcList']['error']; ?>
</span><?php endif; ?>
				</td>
				<td valign="top" style="padding-top: 70px; width: 33%;">
					<input type="button" value=" >> " onClick="javascript:addSrcToDestList(0)"><br /><br />
					<input type="button" value=" << " onclick="javascript:deleteFromDestList(0);">
				</td>
				<td valign="top" style="padding-top: 65px; width: 33%;">
					<select size="10" name="destList0[]" id="destList0" multiple="multiple">
					<?php $_from = $this->_tpl_vars['destList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
						<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['data']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" colspan="3">
					<?php if ($this->_tpl_vars['form_shop']['desc']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['desc']['label']; ?>
<br />
					<?php echo $this->_tpl_vars['form_shop']['desc']['html'];  if ($this->_tpl_vars['form_shop']['desc']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['desc']['error']; ?>
</span><?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" colspan="3">
					<?php if (! $this->_tpl_vars['form_shop']['frozen']): ?>
						<?php if ($this->_tpl_vars['form_shop']['requirednote']):  echo $this->_tpl_vars['form_shop']['requirednote'];  endif; ?>
						<?php echo $this->_tpl_vars['form_shop']['submit']['html'];  echo $this->_tpl_vars['form_shop']['reset']['html']; ?>

					<?php endif; ?>
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>