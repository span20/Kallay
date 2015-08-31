<?php /* Smarty version 2.6.16, created on 2007-07-13 10:15:02
         compiled from admin/shop_category.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/shop_category.tpl', 40, false),array('function', 'cycle', 'admin/shop_category.tpl', 46, false),)), $this); ?>
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
		theme_advanced_buttons2_add			: "separator,forecolor,backcolor,emotions,preview",
		theme_advanced_buttons3_add         : "separator,tablecontrols,separator,pasteword",
		content_css                         : "<?php echo $this->_tpl_vars['theme_dir']; ?>
/<?php echo $_SESSION['site_mce_css']; ?>
",
		width                               : "680",
		theme_advanced_toolbar_location     : "top",
		theme_advanced_statusbar_location   : "bottom",
		convert_urls                        : true,
		entity_encoding						: "raw",
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
>
		<?php echo $this->_tpl_vars['form_shop']['hidden']; ?>

		<table>
			<?php if ($_SESSION['site_multilang'] == 1): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['languages']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['languages']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_shop']['languages']['html'];  if ($this->_tpl_vars['form_shop']['languages']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['languages']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['name']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['name']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_shop']['name']['html'];  if ($this->_tpl_vars['form_shop']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['name']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['date_start']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['date_start']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_shop']['date_start']['html'];  if ($this->_tpl_vars['form_shop']['date_start']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['date_start']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_shop']['date_end']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['date_end']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_shop']['date_end']['html'];  if ($this->_tpl_vars['form_shop']['date_end']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['date_end']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php if ($_SESSION['site_shop_mainpic'] == 1): ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="form"><?php if ($this->_tpl_vars['form_shop']['picture']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['picture']['label']; ?>
</td>
					<td><?php echo $this->_tpl_vars['form_shop']['picture']['html'];  if ($this->_tpl_vars['form_shop']['picture']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['picture']['error']; ?>
</span><?php endif; ?></td>
				</tr>
				<?php if ($this->_tpl_vars['filename'] != ""): ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td colspan="2" align="center">
						<img src="<?php echo $this->_tpl_vars['picture']; ?>
" border="0" alt="" />&nbsp;
						<input type="hidden" name="oldpic_name" value="<?php echo $this->_tpl_vars['filename']; ?>
"><br />
						<input type="checkbox" name="delpic"><?php echo $this->_tpl_vars['locale']['admin_shop']['category_field_list_picdel']; ?>

					</td>
				</tr>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($_SESSION['site_shop_groupuse'] == 1): ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="form"><?php if ($this->_tpl_vars['form_shop']['groups']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['groups']['label']; ?>
</td>
					<td><?php echo $this->_tpl_vars['form_shop']['groups']['html'];  if ($this->_tpl_vars['form_shop']['groups']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['groups']['error']; ?>
</span><?php endif; ?></td>
				</tr>
			<?php else: ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="form"><?php if ($this->_tpl_vars['form_shop']['prods']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['prods']['label']; ?>
</td>
					<td><?php echo $this->_tpl_vars['form_shop']['prods']['html'];  if ($this->_tpl_vars['form_shop']['prods']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['prods']['error']; ?>
</span><?php endif; ?></td>
				</tr>
			<?php endif; ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" colspan="2">
					<?php if ($this->_tpl_vars['form_shop']['desc']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_shop']['desc']['label']; ?>
<br />
					<?php echo $this->_tpl_vars['form_shop']['desc']['html'];  if ($this->_tpl_vars['form_shop']['desc']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_shop']['desc']['error']; ?>
</span><?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" colspan="2">
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