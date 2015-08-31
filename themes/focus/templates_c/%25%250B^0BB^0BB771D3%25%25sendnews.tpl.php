<?php /* Smarty version 2.6.16, created on 2007-06-19 08:11:10
         compiled from sendnews.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'sendnews.tpl', 25, false),)), $this); ?>
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
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['lead_len']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['lead_len']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['lead_len']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['lead_len']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['lead_len']['html']; ?>

		</td>
	</tr>
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['body']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['body']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['body']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['body']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['body']['html']; ?>

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
	<tr class="form">
		<td></td>
		<td valign="top">
			<?php if ($this->_tpl_vars['form_sendnews']['fileupl']['required']): ?><font color="red">*</font><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['fileupl']['label']; ?>

		</td>
		<td>
			<?php if ($this->_tpl_vars['form_sendnews']['fileupl']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form_sendnews']['fileupl']['error']; ?>
</font><br /><?php endif; ?>
			<?php echo $this->_tpl_vars['form_sendnews']['fileupl']['html']; ?>

		</td>
	</tr>
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