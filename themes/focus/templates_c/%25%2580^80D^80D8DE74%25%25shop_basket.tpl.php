<?php /* Smarty version 2.6.16, created on 2007-07-12 16:35:31
         compiled from shop_basket.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop_basket.tpl', 3, false),)), $this); ?>
<div id="form">
	<div id="form_top">
		<span style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_shop']['basket_field_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>
	</div>
	<div id="form_cnt">
		<form <?php echo $this->_tpl_vars['form_basket']['attributes']; ?>
>
		<?php echo $this->_tpl_vars['form_basket']['hidden']; ?>

		<table cellspacing="0" class="table_main">
			<tr style="background-color: #4A4A4A;">
				<th class="table_th"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_name']; ?>
</th>
				<th class="table_th"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_amount']; ?>
</th>
				<th class="table_th"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_delete']; ?>
</th>
				<?php if ($_SESSION['site_shop_stateuse'] == 1): ?>
					<th class="table_th"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_state']; ?>
</th>
				<?php endif; ?>
				<th class="table_th"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_price']; ?>
</th>
				<th class="table_th" style="width: 350px;"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_sum']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['basket_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr>
					<td class="table_td">
						<?php echo $this->_tpl_vars['data']['pname']; ?>

						<?php if ($this->_tpl_vars['data']['attr']): ?>
							<br /><span style="font-weight: normal; font-size: 9px;"><?php echo $this->_tpl_vars['data']['attr']; ?>
</span>
						<?php endif; ?>
					</td>
					<td class="table_td"><input value="<?php echo $this->_tpl_vars['data']['amount']; ?>
" name="amount[<?php echo $this->_tpl_vars['data']['pid']; ?>
]" type="text" size="5" /></td>
					<td class="table_td"><input name="delete[<?php echo $this->_tpl_vars['data']['pid']; ?>
]" type="checkbox" value="1" /></td>
					<?php if ($_SESSION['site_shop_stateuse'] == 1): ?><td class="table_td"><?php echo $this->_tpl_vars['data']['sname']; ?>
</td><?php endif; ?>
					<td class="table_td"><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
					<td class="table_td"><?php echo $this->_tpl_vars['data']['amount']*$this->_tpl_vars['data']['price']; ?>
</td>
				</tr>
			<?php endforeach; else: ?>
				<tr><td colspan="6" align="center" class="table_td"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_warning_empty']; ?>
</td></tr>
			<?php endif; unset($_from); ?>
			<?php if ($this->_tpl_vars['basket_list']): ?>
				<tr>
					<td colspan="<?php if ($_SESSION['site_shop_stateuse'] == 1): ?>5<?php else: ?>4<?php endif; ?>" class="table_tdsum"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_priceall']; ?>
</td>
					<td class="table_tdsum"><?php echo $this->_tpl_vars['price']; ?>
</td>
				</tr>
				<tr>
					<td colspan="<?php if ($_SESSION['site_shop_stateuse'] == 1): ?>5<?php else: ?>4<?php endif; ?>"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_vat']; ?>
</td>
					<td><?php echo $this->_tpl_vars['afa']; ?>
</td>
				</tr>
				<tr>
					<td colspan="<?php if ($_SESSION['site_shop_stateuse'] == 1): ?>5<?php else: ?>4<?php endif; ?>"><?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_list_total']; ?>
</td>
					<td><?php echo $this->_tpl_vars['sum_price']; ?>
</td>
				</tr>
			<?php endif; ?>
		</table><br />
		<?php if ($this->_tpl_vars['basket_list']): ?>
		<table>
			<tr>
				<td colspan="6" class="table_td">
					<input id="back" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['basket_button_continue']; ?>
" onclick="document.location='index.php?p=shop&amp;act=lst';" />
					<input id="refresh" type="submit" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['basket_button_refresh']; ?>
" />
					<input id="empty" type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['basket_button_empty']; ?>
" onclick="document.location='index.php?p=shop&amp;act=ebsk';" />
					<?php if ($_SESSION['user_id']): ?>
						<input id="next" type="button" class="submit2" value="<?php echo $this->_tpl_vars['locale']['index_shop']['basket_button_order']; ?>
" onclick="document.location='index.php?p=shop&amp;act=addr';" />
					<?php else: ?>
						<input id="next" type="button" class="submit2" value="<?php echo $this->_tpl_vars['locale']['index_shop']['basket_button_order']; ?>
" onclick="document.location='index.php?p=shop&amp;act=reg';" />
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php endif; ?>
		<table cellspacing="0" class="table_main">
			<tr>
				<td colspan="6" class="table_comment">
					<?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_comment1']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_comment2']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_comment3']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_comment4']; ?>
<br />
					<?php echo $this->_tpl_vars['locale']['index_shop']['basket_field_comment5']; ?>

				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		</form>
	</div><br />
	<div id="form_bottom"></div>
</div>