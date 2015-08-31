<?php /* Smarty version 2.6.16, created on 2008-08-22 16:19:16
         compiled from shop_basket_address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop_basket_address.tpl', 3, false),)), $this); ?>
<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['form_basket']['header']['address'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
	</div>
	<div id="form_cnt">
		<script type="text/javascript"><?php echo $this->_tpl_vars['address_list']; ?>
</script>
		<form <?php echo $this->_tpl_vars['form_basket']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form_basket']['hidden']; ?>

		<dl>
			<?php $_from = $this->_tpl_vars['userdata']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<dt><span class="form_text"><?php echo $this->_tpl_vars['locale']['index_shop']['address_field_list_name']; ?>
</span></dt><dd><span class="form_text"><?php echo $this->_tpl_vars['key']; ?>
</span></dd>
				<dt><span class="form_text"><?php echo $this->_tpl_vars['locale']['index_shop']['address_field_list_username']; ?>
</span></dt><dd><span class="form_text"><?php echo $this->_tpl_vars['data']['user_name']; ?>
</span></dd>
				<dt><span class="form_text"><?php echo $this->_tpl_vars['locale']['index_shop']['address_field_list_email']; ?>
</span></dt><dd><span class="form_text"><?php echo $this->_tpl_vars['data']['email']; ?>
</span></dd>
			<?php endforeach; endif; unset($_from); ?>
			<dt><?php if ($this->_tpl_vars['form_basket']['mobilephone']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_basket']['mobilephone']['label']; ?>
</span></dt>
			<dd><?php echo $this->_tpl_vars['form_basket']['mobilephone']['html'];  if ($this->_tpl_vars['form_basket']['mobilephone']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_basket']['mobilephone']['error']; ?>
</span><?php endif; ?></dd>
			<dt><?php if ($this->_tpl_vars['form_basket']['shipselect']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_basket']['shipselect']['label']; ?>
</span></dt>
			<dd>
				<?php echo $this->_tpl_vars['form_basket']['shipselect']['html']; ?>

				<input id="modify" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_modify']; ?>
" onclick="modAddress(address_list[document.getElementById('shipselect').value]['zip'], address_list[document.getElementById('shipselect').value]['city'], address_list[document.getElementById('shipselect').value]['cid'], address_list[document.getElementById('shipselect').value]['address'], address_list[document.getElementById('shipselect').value]['aid']);" />
				<input id="delete" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_delete']; ?>
" onclick="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['index_shop']['address_confirm_del']; ?>
')) document.location='index.php?p=shop&amp;act=del&amp;aid='+address_list[document.getElementById('shipselect').value]['aid'];" />
				<?php if ($this->_tpl_vars['form_basket']['shipselect']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_basket']['shipselect']['error']; ?>
</span><?php endif; ?>
			</dd>
			<dt><?php if ($this->_tpl_vars['form_basket']['postselect']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_basket']['postselect']['label']; ?>
</span></dt>
			<dd>
				<?php echo $this->_tpl_vars['form_basket']['postselect']['html']; ?>

				<input id="modify" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_modify']; ?>
" onclick="modAddress(address_list[document.getElementById('postselect').value]['zip'], address_list[document.getElementById('postselect').value]['city'], address_list[document.getElementById('postselect').value]['cid'], address_list[document.getElementById('postselect').value]['address'], address_list[document.getElementById('postselect').value]['aid']);" />
				<input id="delete" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_delete']; ?>
" onclick="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['index_shop']['address_confirm_del']; ?>
')) document.location='index.php?p=shop&amp;act=del&amp;aid='+address_list[document.getElementById('postselect').value]['aid'];" />
				<?php if ($this->_tpl_vars['form_basket']['postselect']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_basket']['postselect']['error']; ?>
</span><?php endif; ?>
			</dd>
		</dl>
		</form>
		<form <?php echo $this->_tpl_vars['form_address']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form_address']['hidden']; ?>

		<div style="clear: both;">
			<dt><?php if ($this->_tpl_vars['form_address']['new_address']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_address']['new_address']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_address']['new_address']['html'];  if ($this->_tpl_vars['form_address']['new_address']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_address']['new_address']['error']; ?>
</span><?php endif; ?></dd>
		</div>
		<div>
			<dl id="addaddress" style="display:<?php echo $this->_tpl_vars['none_block']; ?>
;">
				<input type="hidden" id="aid" name="aid" value="" />
				<dt><?php if ($this->_tpl_vars['form_address']['shipzip']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_address']['shipzip']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_address']['shipzip']['html'];  if ($this->_tpl_vars['form_address']['shipzip']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_address']['shipzip']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_address']['shipcity']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_address']['shipcity']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_address']['shipcity']['html'];  if ($this->_tpl_vars['form_address']['shipcity']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_address']['shipcity']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_address']['country']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_address']['country']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_address']['country']['html'];  if ($this->_tpl_vars['form_address']['country']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_address']['country']['error']; ?>
</span><?php endif; ?></dd>
				<dt><?php if ($this->_tpl_vars['form_address']['shipaddr']['required']): ?><span class="required">*</span><?php endif; ?><span class="form_text"><?php echo $this->_tpl_vars['form_address']['shipaddr']['label']; ?>
</span></dt>
				<dd><?php echo $this->_tpl_vars['form_address']['shipaddr']['html'];  if ($this->_tpl_vars['form_address']['shipaddr']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_address']['shipaddr']['error']; ?>
</span><?php endif; ?></dd>
				<?php if ($this->_tpl_vars['form_address']['requirednote'] && ! $this->_tpl_vars['form_address']['frozen']): ?>
					<div style="padding: 3px 0 3px 10px;"><span class="form_text"><?php echo $this->_tpl_vars['form_address']['requirednote']; ?>
</span></div>
				<?php endif; ?>
				<div style="padding: 3px 0 3px 10px;"><?php echo $this->_tpl_vars['form_address']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_address']['reset']['html']; ?>
</div>
			</dl>
		</div>
		</form>
		<table>
			<tr>
				<td class="table_td">
					<input id="backbsk" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_back']; ?>
" onclick="document.location='index.php?p=shop&amp;act=bsk';" />
					<input id="cont" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_continue']; ?>
" onclick="document.location='index.php?p=shop&amp;act=lst';" />
					<input id="cancel" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_cancel']; ?>
" onclick="document.location='index.php?p=shop&amp;act=ebsk';" />
					<input id="next" type="submit" class="submit2" value="<?php echo $this->_tpl_vars['locale']['index_shop']['address_button_next']; ?>
" onclick="document.frm_addr.submit();"/>
				</td>
			</tr>
		</table>
		<table cellspacing="0" class="table_main">
			<tr>
				<td class="table_comment">
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment1']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment2']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment3']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment4']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment5']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment6']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['address_field_comment7']; ?>

				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
	</div>
	<div id="form_bottom"></div>
</div>