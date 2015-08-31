<?php /* Smarty version 2.6.16, created on 2007-08-01 09:05:17
         compiled from admin/classifieds_categories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/classifieds_categories.tpl', 37, false),array('function', 'cycle', 'admin/classifieds_categories.tpl', 43, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['libs_dir']; ?>
/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init(
	<?php echo '
	{
	'; ?>

		mode                                : "exact",
		elements                            : "desc",
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
/shop.css",
		width                               : "680",
		theme_advanced_toolbar_location     : "top",
		theme_advanced_statusbar_location   : "bottom",
		convert_urls                        : true

	<?php echo '
	}
	'; ?>

	);
	</script>

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
		<form <?php echo $this->_tpl_vars['form_class']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form_class']['hidden']; ?>

		<table>
			<?php if ($_SESSION['site_multilang'] == 1): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_class']['languages']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_class']['languages']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_class']['languages']['html'];  if ($this->_tpl_vars['form_class']['languages']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_class']['languages']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_class']['name']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_class']['name']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_class']['name']['html'];  if ($this->_tpl_vars['form_class']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_class']['name']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_class']['date_start']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_class']['date_start']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_class']['date_start']['html'];  if ($this->_tpl_vars['form_class']['date_start']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_class']['date_start']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form_class']['date_end']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_class']['date_end']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form_class']['date_end']['html'];  if ($this->_tpl_vars['form_class']['date_end']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_class']['date_end']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php if ($_SESSION['site_shop_mainpic'] == 1): ?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="form"><?php if ($this->_tpl_vars['form_class']['picture']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_class']['picture']['label']; ?>
</td>
					<td><?php echo $this->_tpl_vars['form_class']['picture']['html'];  if ($this->_tpl_vars['form_class']['picture']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_class']['picture']['error']; ?>
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
						<input type="checkbox" name="delpic"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_category_delpic']; ?>

					</td>
				</tr>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($_SESSION['site_class_is_catdesc']): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" colspan="2">
					<?php if ($this->_tpl_vars['form_class']['desc']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form_class']['desc']['label']; ?>
<br />
					<?php echo $this->_tpl_vars['form_class']['desc']['html'];  if ($this->_tpl_vars['form_class']['desc']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_class']['desc']['error']; ?>
</span><?php endif; ?>
				</td>
			</tr>
			<?php endif; ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form" colspan="2">
					<?php if (! $this->_tpl_vars['form_class']['frozen']): ?>
						<?php if ($this->_tpl_vars['form_class']['requirednote']):  echo $this->_tpl_vars['form_class']['requirednote'];  endif; ?>
						<?php echo $this->_tpl_vars['form_class']['submit']['html'];  echo $this->_tpl_vars['form_class']['reset']['html']; ?>

					<?php endif; ?>
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>