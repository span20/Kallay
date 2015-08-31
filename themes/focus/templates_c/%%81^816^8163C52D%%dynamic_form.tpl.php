<?php /* Smarty version 2.6.16, created on 2015-06-16 12:34:40
         compiled from admin/dynamic_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/dynamic_form.tpl', 89, false),array('function', 'cycle', 'admin/dynamic_form.tpl', 96, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['tiny_fields'] )): ?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['libs_dir']; ?>
/tiny_mce_new/tiny_mce.js"></script>
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
		language                            : "hu",
		external_link_list_url              : "includes/linklist.php",
		plugins                             : "table,advlink,advimage,emotions,paste,preview",
		file_browser_callback               : "ajaxfilemanager",
		theme_advanced_buttons2_add	        : "separator,forecolor,backcolor,emotions,preview",
		theme_advanced_buttons3_add         : "separator,tablecontrols,separator,pasteword,divwrap",
		content_css                         : "<?php echo $this->_tpl_vars['theme_dir']; ?>
/tiny.css",
		width                               : "680",
		theme_advanced_toolbar_location     : "top",
		theme_advanced_statusbar_location   : "bottom",
		convert_urls                        : true,
        extended_valid_elements             : "iframe[src|width|height|name|align|frameborder]",
		entity_encoding			            : "raw",
		plugin_preview_width                : "<?php echo $_SESSION['site_mce_pagewidth']; ?>
",		
	<?php echo '
	}
	'; ?>

	);
	
	function ajaxfilemanager(field_name, url, type, win) <?php echo '{'; ?>

            var ajaxfilemanagerurl = "/devel/kallay/libs/tiny_mce_new/plugins/ajaxfilemanager/ajaxfilemanager.php";
            switch (type) <?php echo '{'; ?>

                case "image":
                    break;
                case "media":
                    break;
                case "flash": //for older versions of tinymce
                    break;
                case "file":
                    break;
                default:
                    return false;
            <?php echo '}'; ?>

            tinyMCE.activeEditor.windowManager.open(<?php echo '{'; ?>

                url: "/devel/kallay/libs/tiny_mce_new/plugins/ajaxfilemanager/ajaxfilemanager.php",
                width: 782,
                height: 440,
                inline : "yes",
                close_previous : "no"
            <?php echo '}'; ?>
,<?php echo '{'; ?>

                window : win,
                input : field_name
            <?php echo '}'; ?>
);
        <?php echo '}'; ?>

	</script>
<?php endif; ?>

<div id="form_cnt">
	<?php if ($this->_tpl_vars['dynamic_tabs']): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<div class="tabs">
			<ul>
				<li class="current"><a href="#" title="<?php echo $this->_tpl_vars['lang_title']; ?>
"><?php echo $this->_tpl_vars['lang_title']; ?>
</a></li>
			</ul>
			<div class="blueleft"></div><div class="blueright"></div>
		</div>
	<?php endif; ?>
	<div id="f_content">
		<?php if (! isset ( $this->_tpl_vars['dynamic_tabs'] )): ?><div class="f_empty"></div><?php else: ?>
		<div class="t_filter"><h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3></div><?php endif; ?>
		<form<?php echo $this->_tpl_vars['form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table>
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
							<td class="form" colspan="2">
								<?php if ($this->_tpl_vars['element']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['element']['label']; ?>
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

								<?php if ($this->_tpl_vars['gitem']['type'] == 'radio'): ?>
									<span class="radio"><?php echo $this->_tpl_vars['gitem']['html']; ?>
</span>
								<?php else: ?>
									<?php echo $this->_tpl_vars['gitem']['html']; ?>

								<?php endif;  if ($this->_tpl_vars['gitem']['required']): ?><span class="error">*</span><?php endif; ?>
								<?php if ($this->_tpl_vars['element']['separator']):  echo smarty_function_cycle(array('values' => $this->_tpl_vars['element']['separator']), $this); endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						<?php else: ?>
							<?php echo $this->_tpl_vars['element']['html']; ?>

						<?php endif; ?>
						<?php if ($this->_tpl_vars['element']['error']): ?><span class="error"><?php echo $this->_tpl_vars['element']['error']; ?>
</span><?php endif; ?>
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
</div>