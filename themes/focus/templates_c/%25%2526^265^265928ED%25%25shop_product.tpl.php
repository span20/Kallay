<?php /* Smarty version 2.6.16, created on 2007-07-13 10:25:54
         compiled from shop_product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop_product.tpl', 5, false),array('function', 'math', 'shop_product.tpl', 35, false),)), $this); ?>
<!-- igy nem rakja ki a jobb felso sarokba a piros Loading... feliratot, igy lehetne varialni, ha akarnank -->
<div id="HTML_AJAX_LOADING"></div>

<div id="shop">
	<div id="shop_top" style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_shop']['main_details_field_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div class="shop_cnt">
	<?php $_from = $this->_tpl_vars['prod_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
		<div class="shop_pic">
			<?php if ($this->_tpl_vars['pictures'] != ""): ?>
				<?php $_from = $this->_tpl_vars['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data2']):
?>
					<img src="<?php echo $_SESSION['site_shop_prodpicdir']; ?>
/<?php echo $this->_tpl_vars['data2']; ?>
" alt="<?php echo $this->_tpl_vars['data2']; ?>
" />
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</div>
		<div class="shop_datadetails">
			<p class="shop_detailstitle"><?php echo $this->_tpl_vars['data']['pname']; ?>
</p>
			<p><span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_item']; ?>
:</span> <?php echo $this->_tpl_vars['data']['item']; ?>
</p>
			<?php if ($_SESSION['site_shop_userbuy'] == 1): ?>
				<p>
					<?php if ($_SESSION['site_shop_actionuse']): ?>
						<?php if ($this->_tpl_vars['data']['actionprice'] != 0.00 && $this->_tpl_vars['data']['actionprice'] != NULL && ( $this->_tpl_vars['data']['actiontstart'] == '0000-00-00 00:00:00' || $this->_tpl_vars['data']['actiontstart'] == NULL )): ?>
							<?php $this->assign('price', ($this->_tpl_vars['data']['actionprice'])); ?>
							<?php $this->assign('is_action', 1); ?>
						<?php elseif ($this->_tpl_vars['data']['actionpercent'] != 0 && $this->_tpl_vars['data']['actionpercent'] != NULL && ( $this->_tpl_vars['data']['actiontstart'] == '0000-00-00 00:00:00' || $this->_tpl_vars['data']['actiontstart'] == NULL )): ?>
							<?php $this->assign('price', ($this->_tpl_vars['data']['netto']-$this->_tpl_vars['data']['netto']/100*$this->_tpl_vars['data']['actionpercent'])); ?>
							<?php $this->assign('is_action', 1); ?>
						<?php else: ?>
							<?php $this->assign('price', ($this->_tpl_vars['data']['netto'])); ?>
							<?php $this->assign('is_action', 0); ?>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['is_action'] == 1): ?>
						<strike>
						<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_price']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data']['netto']; ?>
</span>
						+ <?php echo $this->_tpl_vars['data']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['data']['netto'],'y' => $this->_tpl_vars['data']['afa']), $this);?>
</b></span>
						</strike><br />
						<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_actionprice']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['price']; ?>
</span>
						+ <?php echo $this->_tpl_vars['data']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['price'],'y' => $this->_tpl_vars['data']['afa']), $this);?>
</b></span>
					<?php else: ?>
						<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_price']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data']['netto']; ?>
</span>
						+ <?php echo $this->_tpl_vars['data']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['data']['netto'],'y' => $this->_tpl_vars['data']['afa']), $this);?>
</b></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ($_SESSION['site_shop_stateuse'] == 1): ?>
				<p>
					<?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_state']; ?>
 <?php echo $this->_tpl_vars['data']['state']; ?>

				</p>
			<?php endif; ?>
			<p class="shop_detailsdesc"><?php echo $this->_tpl_vars['data']['pdesc']; ?>
</p>

			<?php $_from = $this->_tpl_vars['tplfields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['plus']):
?>
				<?php $this->assign('field', $this->_tpl_vars['plus']['value']); ?>
				<p><span class="shop_desc"><?php echo $this->_tpl_vars['plus']['display']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['field']]; ?>
</span></p>
			<?php endforeach; endif; unset($_from); ?>

			<?php if ($_SESSION['site_shop_is_extra_attr'] == 1 && $this->_tpl_vars['attributes']): ?>
				<?php $_from = $this->_tpl_vars['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr']):
?>
				<div style="padding-top: 3px;">
					<span class="shop_desc"><?php echo $this->_tpl_vars['attr']['title']; ?>
:</span>
					<select name="<?php echo $this->_tpl_vars['attr']['title']; ?>
" id="attr_select_<?php echo $this->_foreach['attr']['iteration']; ?>
">
					<?php $_from = $this->_tpl_vars['attr']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>
						<option value="<?php echo $this->_tpl_vars['value']; ?>
"><?php echo $this->_tpl_vars['value']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
				</div>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>

			<?php if ($_SESSION['site_shop_userbuy'] == 1): ?>
				<?php if ($this->_tpl_vars['amount']): ?>
					<p class="amount"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_amount1']; ?>
 <span class='shop_extra'><?php echo $this->_tpl_vars['amount']; ?>
</span> <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_amount2']; ?>
</p>
				<?php else: ?>
					<div id="target_<?php echo $this->_tpl_vars['key']; ?>
">
						<input type="text" id="amount_<?php echo $this->_tpl_vars['key']; ?>
" name="amount[<?php echo $this->_tpl_vars['key']; ?>
]" size="2" />
						<input type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['main_details_button']; ?>
" onclick="bsksend('<?php echo $this->_tpl_vars['key']; ?>
', '<?php echo $this->_tpl_vars['data']['pname']; ?>
', '<?php echo $this->_tpl_vars['data']['netto']; ?>
', '<?php echo $this->_foreach['attr']['total']; ?>
')" />
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<br />
			<a href="index.php?p=shop&amp;act=lst&amp;cid=<?php echo $this->_tpl_vars['cid']; ?>
#prd_<?php echo $this->_tpl_vars['key']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_shop']['main_details_link_backcat']; ?>
"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_link_backcat']; ?>
</a>
                    <?php if ($_SESSION['site_shop_userbuy'] == 1): ?>
			    <a href="index.php?p=shop&amp;act=bsk" title="<?php echo $this->_tpl_vars['locale']['index_shop']['main_details_link_editbasket']; ?>
"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_link_editbasket']; ?>
</a>
                    <?php endif; ?>

            			<?php if ($this->_tpl_vars['documents']): ?>
            <div>
				<?php $_from = $this->_tpl_vars['documents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
					<a href="index.php?p=shop&amp;act=dwn&amp;did=<?php echo $this->_tpl_vars['data']['did']; ?>
" title="<?php echo $this->_tpl_vars['data']['document']; ?>
"><?php echo $this->_tpl_vars['data']['document']; ?>
</a>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
            </div>
            
		</div>
	<?php endforeach; endif; unset($_from); ?>
	</div>

		<?php if ($_SESSION['site_shop_joinprod'] && $this->_tpl_vars['joinprods']): ?>
	<div id="shop_top" style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_shop']['main_details_field_joinprod'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div class="shop_cnt">
		<?php $_from = $this->_tpl_vars['joinprods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key3'] => $this->_tpl_vars['data3']):
?>
		<div style="padding-left: 10px; float: left; width: 30%; <?php if (!(($this->_foreach['cat']['iteration']-1) % 3)): ?>clear: both;<?php endif; ?>">
			<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;pid=<?php echo $this->_tpl_vars['key3']; ?>
" title="<?php echo $this->_tpl_vars['data3']['jpname']; ?>
"><?php echo $this->_tpl_vars['data3']['jpname']; ?>
</a><br />
			<?php if ($this->_tpl_vars['data3']['jpic'] != ""): ?>
				<a href="index.php?p=shop&amp;act=prd&amp;pid=<?php echo $this->_tpl_vars['key3']; ?>
" title="<?php echo $this->_tpl_vars['data3']['jpname']; ?>
" style="text-decoration: none;">
				<img src="<?php echo $_SESSION['site_shop_prodpicdir']; ?>
/tn_<?php echo $this->_tpl_vars['data3']['jpic']; ?>
" alt="<?php echo $this->_tpl_vars['data3']['jpname']; ?>
" border="0" />
				</a><br />
			<?php endif; ?>
			<?php if ($_SESSION['site_shop_userbuy'] == 1): ?>
				<p>
					<?php if ($_SESSION['site_shop_actionuse']): ?>
						<?php if ($this->_tpl_vars['data3']['actionprice'] != 0.00 && $this->_tpl_vars['data3']['actionprice'] != NULL && ( $this->_tpl_vars['data3']['actiontstart'] == '0000-00-00 00:00:00' || $this->_tpl_vars['data3']['actiontstart'] == NULL )): ?>
							<?php $this->assign('price', ($this->_tpl_vars['data3']['actionprice'])); ?>
							<?php $this->assign('is_action', 1); ?>
						<?php elseif ($this->_tpl_vars['data3']['actionpercent'] != 0 && $this->_tpl_vars['data3']['actionpercent'] != NULL && ( $this->_tpl_vars['data3']['actiontstart'] == '0000-00-00 00:00:00' && $this->_tpl_vars['data3']['actiontstart'] == NULL )): ?>
							<?php $this->assign('price', ($this->_tpl_vars['data3']['netto']-$this->_tpl_vars['data3']['netto']/100*$this->_tpl_vars['data3']['actionpercent'])); ?>
							<?php $this->assign('is_action', 1); ?>
						<?php else: ?>
							<?php $this->assign('price', ($this->_tpl_vars['data3']['netto'])); ?>
							<?php $this->assign('is_action', 0); ?>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['is_action'] == 1): ?>
						<strike>
						<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_price']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data3']['netto']; ?>
</span>
						+ <?php echo $this->_tpl_vars['data3']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['data3']['netto'],'y' => $this->_tpl_vars['data3']['afa']), $this);?>
</b></span>
						</strike><br />
						<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_actionprice']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['price']; ?>
</span>
						+ <?php echo $this->_tpl_vars['data3']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['price'],'y' => $this->_tpl_vars['data3']['afa']), $this);?>
</b></span>
					<?php else: ?>
						<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_price']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data3']['netto']; ?>
</span>
						+ <?php echo $this->_tpl_vars['data3']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['data3']['netto'],'y' => $this->_tpl_vars['data3']['afa']), $this);?>
</b></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div
		<?php endforeach; endif; unset($_from); ?>
	</div>
	<?php endif; ?>
	
		<?php if ($_SESSION['site_shop_is_rating']): ?>
	<div id="shop_top" style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_shop']['main_details_field_rating'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div class="shop_cnt">
		<div style="padding-left: 10px; padding-right: 10px;">
			<?php $_from = $this->_tpl_vars['shop_ratings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rating']):
?>
				<span><b><?php echo $this->_tpl_vars['rating']['user_name']; ?>
 - <?php echo $this->_tpl_vars['rating']['add_date']; ?>
</b></span><br />
				<span class="shop_desc"><b><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_rate']; ?>
</b></span>
				<span>
					<?php unset($this->_sections['star']);
$this->_sections['star']['name'] = 'star';
$this->_sections['star']['start'] = (int)0;
$this->_sections['star']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['star']['loop'] = is_array($_loop=$this->_tpl_vars['rating']['rating']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['star']['show'] = true;
$this->_sections['star']['max'] = $this->_sections['star']['loop'];
if ($this->_sections['star']['start'] < 0)
    $this->_sections['star']['start'] = max($this->_sections['star']['step'] > 0 ? 0 : -1, $this->_sections['star']['loop'] + $this->_sections['star']['start']);
else
    $this->_sections['star']['start'] = min($this->_sections['star']['start'], $this->_sections['star']['step'] > 0 ? $this->_sections['star']['loop'] : $this->_sections['star']['loop']-1);
if ($this->_sections['star']['show']) {
    $this->_sections['star']['total'] = min(ceil(($this->_sections['star']['step'] > 0 ? $this->_sections['star']['loop'] - $this->_sections['star']['start'] : $this->_sections['star']['start']+1)/abs($this->_sections['star']['step'])), $this->_sections['star']['max']);
    if ($this->_sections['star']['total'] == 0)
        $this->_sections['star']['show'] = false;
} else
    $this->_sections['star']['total'] = 0;
if ($this->_sections['star']['show']):

            for ($this->_sections['star']['index'] = $this->_sections['star']['start'], $this->_sections['star']['iteration'] = 1;
                 $this->_sections['star']['iteration'] <= $this->_sections['star']['total'];
                 $this->_sections['star']['index'] += $this->_sections['star']['step'], $this->_sections['star']['iteration']++):
$this->_sections['star']['rownum'] = $this->_sections['star']['iteration'];
$this->_sections['star']['index_prev'] = $this->_sections['star']['index'] - $this->_sections['star']['step'];
$this->_sections['star']['index_next'] = $this->_sections['star']['index'] + $this->_sections['star']['step'];
$this->_sections['star']['first']      = ($this->_sections['star']['iteration'] == 1);
$this->_sections['star']['last']       = ($this->_sections['star']['iteration'] == $this->_sections['star']['total']);
?>
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/shop_star.gif" alt="<?php echo $this->_tpl_vars['rating']['rating']; ?>
" border="0" />
					<?php endfor; else: ?>
						<?php echo $this->_tpl_vars['rating']['rating']; ?>

					<?php endif; ?>
				</span><br />
				<span class="shop_desc"><?php echo $this->_tpl_vars['rating']['comment']; ?>
</span>
				<?php if ($this->_tpl_vars['delcom_link']): ?>
					<br /><a href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['index_shop']['main_details_confirm_del']; ?>
')) document.location.href='<?php echo $this->_tpl_vars['delcom_link']; ?>
&amp;rid=<?php echo $this->_tpl_vars['rating']['rid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_delete']; ?>
"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_delete']; ?>
</a>
				<?php endif; ?>
				<br /><br />
			<?php endforeach; else: ?>
				<?php if ($this->_tpl_vars['shop_is_reguser_rating'] == 1 && ! $_SESSION['user_id']): ?>
					<span><?php echo $this->_tpl_vars['locale']['index_shopmain_details_rating_onlyreg']; ?>
</span>
				<?php else: ?>
					<span><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_field_notratingyet']; ?>
</span>
				<?php endif; ?>
			<?php endif; unset($_from); ?>
		</div><br />
		<?php if (( $this->_tpl_vars['shop_is_reguser_rating'] == 1 && $_SESSION['user_id'] ) || $this->_tpl_vars['shop_is_reguser_rating'] == 0): ?>
		<div style="padding-left: 10px; padding-right: 10px;">
			<form <?php echo $this->_tpl_vars['form_rating']['attributes']; ?>
>
			<?php echo $this->_tpl_vars['form_rating']['hidden']; ?>

				<?php echo $this->_tpl_vars['form_rating']['ratingnum']['label']; ?>
<br />
				<?php echo $this->_tpl_vars['form_rating']['ratingnum']['html']; ?>
 <?php if ($this->_tpl_vars['form_rating']['ratingnum']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_rating']['ratingnum']['error']; ?>
</span><?php endif; ?><br />
				<?php echo $this->_tpl_vars['form_rating']['ratingcom']['label']; ?>
<br />
				<?php echo $this->_tpl_vars['form_rating']['ratingcom']['html']; ?>
<br />
				<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_details_rating_comm1'];  echo $this->_tpl_vars['shop_ratemin'];  echo $this->_tpl_vars['locale']['index_shop']['main_details_rating_comm2'];  echo $this->_tpl_vars['shop_ratemax'];  echo $this->_tpl_vars['locale']['index_shop']['main_details_rating_comm3']; ?>
</span><br />
				<?php if ($this->_tpl_vars['form_rating']['ratingcom']['error']): ?><span class="error"><?php echo $this->_tpl_vars['form_rating']['ratingcom']['error']; ?>
</span><?php endif; ?><br />
				<?php echo $this->_tpl_vars['form_rating']['submit']['html']; ?>
 <?php echo $this->_tpl_vars['form_rating']['reset']['html']; ?>

			</form>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	
	<div id="shop_bottom"></div>
</div>