<?php /* Smarty version 2.6.16, created on 2007-07-30 14:43:52
         compiled from admin/polls_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/polls_add.tpl', 15, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/javascript.polls.js"></script>
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
					<?php if ($this->_tpl_vars['form']['regpoll']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['regpoll']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['regpoll']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['regpoll']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['regpoll']['html']; ?>

				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['question']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['question']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['question']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['question']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['question']['html']; ?>

				</td>
			</tr>
			<?php if ($this->_tpl_vars['ismenu'] == 1): ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['menulist']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['menulist']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['menulist']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['menulist']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['menulist']['html']; ?>

				</td>
			</tr>
			<?php endif; ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['date_start']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['date_start']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['date_start']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['date_start']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['date_start']['html']; ?>

				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['date_end']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['date_end']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['date_end']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['date_end']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['date_end']['html']; ?>

				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td width="50%" class="form">
					<?php if ($this->_tpl_vars['form']['link']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['link']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['link']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['link']['error']; ?>
</font><br /><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['link']['html']; ?>

				</td>
			</tr>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="form">
					<?php if ($this->_tpl_vars['form']['answer']['required']): ?><font color="red">*</font><?php endif; ?>
					<?php echo $this->_tpl_vars['form']['answer']['label']; ?>

				</td>
				<td>
					<?php if ($this->_tpl_vars['form']['answer']['error']): ?><font color="red"><?php echo $this->_tpl_vars['form']['answer']['error']; ?>
</font><br /><?php endif; ?>
					<input value="0" id="theValue" type="hidden">
					<input type="text" name="answer[]">
					<span id="myDiv">
					</span>
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