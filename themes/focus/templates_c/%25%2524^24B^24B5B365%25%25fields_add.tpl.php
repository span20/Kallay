<?php /* Smarty version 2.6.16, created on 2007-12-12 14:52:41
         compiled from admin/fields_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/fields_add.tpl', 14, false),)), $this); ?>
<div id="form_cnt">
	<div id="ear">
		<ul>
			<li id="current"><a href="#"><?php echo $this->_tpl_vars['lang_title']; ?>
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
				<td class="form">
					<?php if ($this->_tpl_vars['form']['name']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['name']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['name']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['name']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['name']['html']; ?>

				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['type']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['type']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['type']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['type']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['type']['html']; ?>

					<input type="hidden" name="fields_num" id="fields_num" value="0">
					<div id="new_answer" style="display: none;">
						<a href="javascript:void(0);" onclick="create_fields();"><?php echo $this->_tpl_vars['locale']['admin_forms']['form_new_answer']; ?>
</a>
					</div>
					<div id="answer_fields">
						
					</div>
				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['check']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['check']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['check']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['check']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['check']['html']; ?>

				</td>
			</tr>

			<tr class="row2"><td colspan="2" class="form">
			<?php if (! $this->_tpl_vars['form']['frozen']): ?>
				<?php echo $this->_tpl_vars['form']['requirednote']; ?>

				<?php echo $this->_tpl_vars['form']['submit']['html']; ?>

				<?php echo $this->_tpl_vars['form']['reset']['html']; ?>

			<?php endif; ?>
			</td></tr>
		</table>
		</form>
		<div class="f_empty">&nbsp;</div>
	</div>
	<div id="f_bottom"></div>
</div>