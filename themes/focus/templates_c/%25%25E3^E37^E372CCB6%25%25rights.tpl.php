<?php /* Smarty version 2.6.16, created on 2007-06-18 16:35:37
         compiled from admin/rights.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/rights.tpl', 8, false),)), $this); ?>
<!-- igy nem rakja ki a jobb felso sarokba a piros Loading... feliratot, igy lehetne varialni, ha akarnank -->
<div id="HTML_AJAX_LOADING"></div>

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
		<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['name']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['name']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['name']['html'];  if ($this->_tpl_vars['form']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['name']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2">
				<td class="form"><?php if ($this->_tpl_vars['form']['modules']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['modules']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['modules']['html'];  if ($this->_tpl_vars['form']['modules']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['modules']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['modulesadm']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['modulesadm']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['modulesadm']['html'];  if ($this->_tpl_vars['form']['modulesadm']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['modulesadm']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2">
				<td class="form"><?php if ($this->_tpl_vars['form']['contents']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['contents']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['contents']['html'];  if ($this->_tpl_vars['form']['contents']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['contents']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['group']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['group']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['group']['html'];  if ($this->_tpl_vars['form']['group']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['group']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2">
				<td class="form"><?php if ($this->_tpl_vars['form']['functiontext']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['functiontext']['label']; ?>
</td>
				<td id="target">
					<?php if ($this->_tpl_vars['form']['functiontext']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['functiontext']['error']; ?>
</span><br /><?php endif; ?>
					<?php if (is_array ( $this->_tpl_vars['functionchk'] )): ?>
						<?php $_from = $this->_tpl_vars['functionchk']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
							<input type="checkbox" name="functions[]" value="<?php echo $this->_tpl_vars['data']['fid']; ?>
" <?php if ($this->_tpl_vars['data']['rfid'] != 0): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['data']['falias']; ?>
<br />
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="row1">
				<td class="form" colspan="2">
					<?php if (! $this->_tpl_vars['form']['frozen']): ?>
						<?php if ($this->_tpl_vars['form']['requirednote']):  echo $this->_tpl_vars['form']['requirednote'];  endif; ?>
						<?php echo $this->_tpl_vars['form']['submit']['html'];  echo $this->_tpl_vars['form']['reset']['html']; ?>

					<?php endif; ?>
				</td>
			</tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>