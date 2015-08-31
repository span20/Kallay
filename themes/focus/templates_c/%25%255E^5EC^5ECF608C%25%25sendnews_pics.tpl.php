<?php /* Smarty version 2.6.16, created on 2007-07-30 10:22:50
         compiled from sendnews_pics.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'sendnews_pics.tpl', 25, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['libs_dir']; ?>
/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init(
	<?php echo '
	{
	'; ?>

		mode             : "exact",
		elements         : "body",
		theme            : "simple",
		language         : "<?php echo $_SESSION['site_mce_lang']; ?>
",
		width            : "100%",
		height			 : "400px",
		convert_urls     : true,
		entity_encoding	 : "raw"
	<?php echo '
	}
	'; ?>

	);
</script>
<form <?php echo $this->_tpl_vars['form_sendnews']['attributes']; ?>
>
<?php echo $this->_tpl_vars['form_sendnews']['hidden']; ?>

<table cellpadding="2" cellspacing="0">
	<tr>
		<td style="padding-left: 2px; padding-right: 2px;"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/nyil_kek.png" border="0" alt=""></td>
		<td colspan="2" width="97%" style="border-top: 1px solid;"><span class="mainnews_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_sendnews']['header']['sendnews'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span></td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['sendtype']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['sendtype']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['sendtype']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['sendtype']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['sendtype']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['title']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['title']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['title']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['title']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['title']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['lead']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['lead']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['lead']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['lead']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['lead']['html']; ?>

		</td>
	</tr>	
	<?php if ($this->_tpl_vars['form_sendnews']['category']['html']): ?>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['category']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['category']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['category']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['category']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['category']['html']; ?>

		</td>
	</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['form_sendnews']['tags']['html']): ?>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['tags']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['tags']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['tags']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['tags']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['tags']['html']; ?>

		</td>
	</tr>
	<?php endif; ?>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['file_1']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['file_1']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['file_1']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['file_1']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['file_1']['html']; ?>

		</td>
	</tr>
	
	<tr class="form">
		<td></td>
		<td valign="top" colspan="2">
			<div id="files_list"></div>
			<input type="hidden" value="1" name="pic_count" id="pic_count">
			<script>
				var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 5 );
				multi_selector.addElement( document.getElementById( 'pic_select' ) );
			</script>
		</td>
	</tr>
	<?php if ($this->_tpl_vars['form_sendnews']['requirednote'] && ! $this->_tpl_vars['form_sendnews']['frozen']): ?>
		<tr class="form">
			<td></td>
			<td colspan="2"><?php echo $this->_tpl_vars['form_sendnews']['requirednote']; ?>
</td>
		</tr>
	<?php endif; ?>
	<tr class="form">
		<td></td>
		<td colspan="2"><?php echo $this->_tpl_vars['form_sendnews']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_sendnews']['reset']['html']; ?>
</td>
	</tr>
</table>
</form>