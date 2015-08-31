<?php /* Smarty version 2.6.16, created on 2007-07-13 10:23:34
         compiled from admin/shop_actions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/shop_actions.tpl', 5, false),)), $this); ?>
<div id="form_cnt">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="f_content">
		<div class="t_filter">
			<h3 style="margin: 0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
		</div>
		<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['name']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['name']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['name']['html'];  if ($this->_tpl_vars['form']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['name']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2">
				<td class="form"><?php if ($this->_tpl_vars['form']['date_start']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['date_start']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['date_start']['html'];  if ($this->_tpl_vars['form']['date_start']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['date_start']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['date_end']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['date_end']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['date_end']['html'];  if ($this->_tpl_vars['form']['date_end']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['date_end']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2">
				<td class="form"><?php if ($this->_tpl_vars['form']['products']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['products']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['products']['html'];  if ($this->_tpl_vars['form']['products']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['products']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['actionradio']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['actionradio']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['actionradio']['html'];  if ($this->_tpl_vars['form']['actionradio']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['actionradio']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2" id="percent" style="display: none;">
				<td class="form"><?php if ($this->_tpl_vars['form']['percent']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['percent']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['percent']['html'];  if ($this->_tpl_vars['form']['percent']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['percent']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2" id="fix" style="display: none;">
				<td class="form"><?php if ($this->_tpl_vars['form']['fix']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['form']['fix']['label']; ?>
</td>
				<td>
					<?php if ($this->_tpl_vars['form']['fix']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['fix']['error']; ?>
</span><?php endif; ?>
					<div id="mySpan"></div>
				</td>
			</tr>
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