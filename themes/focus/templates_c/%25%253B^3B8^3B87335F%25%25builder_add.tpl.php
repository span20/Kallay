<?php /* Smarty version 2.6.16, created on 2007-06-28 14:40:04
         compiled from admin/builder_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/builder_add.tpl', 13, false),)), $this); ?>
<div id="form_cnt">
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="<?php echo $this->_tpl_vars['lang_title']; ?>
"><?php echo $this->_tpl_vars['lang_title']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="f_content">
		<div class="f_empty"></div>
		<form <?php echo $this->_tpl_vars['form']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['column']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['column']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['column']['html'];  if ($this->_tpl_vars['form']['column']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['column']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['inside_columns']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['inside_columns']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['inside_columns']['html'];  if ($this->_tpl_vars['form']['inside_columns']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['inside_columns']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['menu_pos']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['menu_pos']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['menu_pos']['html']; ?>

					<div id="1" style="display: none;">
						<input type="hidden" name="1_col" id="1_col" value="<?php echo $this->_tpl_vars['menu_pos_sel']; ?>
">
						<select id="1_newsel" name="menu_pos_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['menu_pos']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['menu_pos']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['module_id']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['module_id']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['module_id']['html']; ?>

					<div id="2" style="display: none;">
						<input type="hidden" name="2_col" id="2_col" value="<?php echo $this->_tpl_vars['module_id_sel']; ?>
">
						<select id="2_newsel" name="module_id_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['module_id']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['module_id']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php if ($this->_tpl_vars['form']['block']['html']): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['block']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['block']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['block']['html']; ?>

					<div id="7" style="display: none;">
						<input type="hidden" name="7_col" id="7_col" value="<?php echo $this->_tpl_vars['block_sel']; ?>
">
						<select id="7_newsel" name="block_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['block']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['block']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['form']['content_id']['html']): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['content_id']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['content_id']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['content_id']['html']; ?>

					<div id="3" style="display: none;">
						<input type="hidden" name="3_col" id="3_col" value="<?php echo $this->_tpl_vars['content_id_sel']; ?>
">
						<select id="3_newsel" name="content_id_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['content_id']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['content_id']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['form']['category_id']['html']): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['category_id']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['category_id']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['category_id']['html']; ?>

					<div id="4" style="display: none;">
						<input type="hidden" name="4_col" id="4_col" value="<?php echo $this->_tpl_vars['category_id_sel']; ?>
">
						<select id="4_newsel" name="category_id_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['category_id']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['category_id']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['form']['banner_pos']['html']): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['banner_pos']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['banner_pos']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['banner_pos']['html']; ?>

					<div id="5" style="display: none;">
						<input type="hidden" name="5_col" id="5_col" value="<?php echo $this->_tpl_vars['banner_pos_sel']; ?>
">
						<select id="5_newsel" name="banner_pos_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['banner_pos']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['banner_pos']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['form']['gallery_id']['html']): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form"><?php if ($this->_tpl_vars['form']['gallery_id']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['gallery_id']['label']; ?>
</td>
				<td><?php echo $this->_tpl_vars['form']['gallery_id']['html']; ?>

					<div id="6" style="display: none;">
						<input type="hidden" name="6_col" id="6_col" value="<?php echo $this->_tpl_vars['gallery_id_sel']; ?>
">
						<select id="6_newsel" name="gallery_id_sel">
						</select>
					</div>
				<?php if ($this->_tpl_vars['form']['gallery_id']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['gallery_id']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<?php endif; ?>
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