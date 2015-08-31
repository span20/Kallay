<?php /* Smarty version 2.6.16, created on 2007-12-27 14:25:51
         compiled from admin/newsletter_groups_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/newsletter_groups_add.tpl', 5, false),)), $this); ?>
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
 onSubmit="return SelectAll(this);">
		<?php echo $this->_tpl_vars['form']['hidden']; ?>

		<table>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['name']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['name']['label']; ?>
</td>
				<td colspan="2"><?php echo $this->_tpl_vars['form']['name']['html'];  if ($this->_tpl_vars['form']['name']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['name']['error']; ?>
</span><?php endif; ?></td>
			</tr>
			<tr class="row2">
				<td class="form" style="width: 33%;">
					<?php if ($this->_tpl_vars['form']['srcList']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['srcList']['label']; ?>
<br />
					<input type="text" name="SearchInput" onKeyUp="JavaScript: searchSelectBox('frm_newsletter_groups', 'SearchInput', 'srcList')"><br /><br />
                    <span style="font-weight: none;"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['groups_field_allusers']; ?>
</span><br />
					<?php echo $this->_tpl_vars['form']['srcList']['html'];  if ($this->_tpl_vars['form']['srcList']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['srcList']['error']; ?>
</span><?php endif; ?>
				</td>
				<td valign="top" style="padding-top: 70px; width: 33%;">
					<input type="button" value=" &gt;&gt; " onClick="javascript:addSrcToDestList(0)"><br /><br />
					<input type="button" value=" &lt;&lt; " onclick="javascript:deleteFromDestList(0);">
				</td>
				<td class="form" valign="top" style="padding-top: 65px; width: 33%;">
                    <span style="font-weight: none;"><?php echo $this->_tpl_vars['locale']['admin_newsletter']['groups_field_groupusers']; ?>
</span><br />
					<select size="10" name="destList0[]" id="destList0" multiple="multiple">
					<?php $_from = $this->_tpl_vars['destList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
						<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['data']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
				</td>
			</tr>
			<?php if ($this->_tpl_vars['form']['deleted']['html']): ?>
			<tr class="row1">
				<td class="form"><?php if ($this->_tpl_vars['form']['deleted']['required']): ?><span class="error">*</span><?php endif;  echo $this->_tpl_vars['form']['deleted']['label']; ?>
</td>
				<td colspan="2"><?php echo $this->_tpl_vars['form']['deleted']['html'];  if ($this->_tpl_vars['form']['deleted']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form']['deleted']['error']; ?>
</span><?php endif; ?></td>
			</tr>
            <?php else: ?>
            <tr class="row1">
                <td>&nbsp;</td>
            </tr>
            <?php endif; ?>
			<tr class="row2}">
				<td class="form" colspan="3">
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