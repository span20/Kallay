<?php /* Smarty version 2.6.16, created on 2007-07-13 10:25:28
         compiled from shop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'shop.tpl', 5, false),array('modifier', 'htmlspecialchars', 'shop.tpl', 30, false),array('modifier', 'count_characters', 'shop.tpl', 40, false),array('modifier', 'truncate', 'shop.tpl', 41, false),array('modifier', 'strip_tags', 'shop.tpl', 41, false),array('function', 'math', 'shop.tpl', 65, false),)), $this); ?>
<!-- igy nem rakja ki a jobb felso sarokba a piros Loading... feliratot, igy lehetne varialni, ha akarnank -->
<div id="HTML_AJAX_LOADING"></div>

<div id="shop">
	<div id="shop_top"><?php echo ((is_array($_tmp=$this->_tpl_vars['cat_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</div>
	<div id="shop_cnt">
		<?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['cat']['iteration']++;
?>
		<div class="shop_cat" <?php if (!(($this->_foreach['cat']['iteration']-1) % 4)): ?>style="clear: both;"<?php endif; ?>>
			<div class="shop_catpic">
				<?php if ($this->_tpl_vars['data']['cpic'] != ""): ?>
					<a href="index.php?p=shop&amp;act=lst&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
" title="<?php echo $this->_tpl_vars['data']['cname']; ?>
">
						<img src="<?php echo $_SESSION['site_shop_mainpicdir']; ?>
/tn_<?php echo $this->_tpl_vars['data']['cpic']; ?>
" border="0" alt="<?php echo $this->_tpl_vars['data']['cname']; ?>
">
					</a>
				<?php endif; ?>
			</div>
			<div class="shop_title"><a href="index.php?p=shop&amp;act=lst&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
" title="<?php echo $this->_tpl_vars['data']['cname']; ?>
"><?php echo $this->_tpl_vars['data']['cname']; ?>
</a></div>
		</div>
		<?php endforeach; endif; unset($_from); ?>
		<?php if ($this->_tpl_vars['page_data']): ?>
		<div style="clear: both;">
			<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
			<form action="index.php?p=shop" method="post">
			<input type="hidden" name="act" value="bsk">
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['prod'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['prod']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['prod']['iteration']++;
?>
			<div class="shop_prod" <?php if ($this->_foreach['prod']['iteration'] % 2): ?>style="clear: both;"<?php endif; ?>>
				<div class="shop_pic">
					<a id="prd_<?php echo $this->_tpl_vars['data']['pid']; ?>
" name="prd_<?php echo $this->_tpl_vars['data']['pid']; ?>
"></a>
					<?php if ($this->_tpl_vars['data']['pictures']): ?>
						<?php $_from = $this->_tpl_vars['data']['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pic']):
?>
							<a href="index.php?p=shop&amp;act=prd&amp;cid=<?php echo $this->_tpl_vars['cid']; ?>
&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" onmouseover="this.T_BGCOLOR='#f0f0f0'; this.T_TITLE='<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['pname'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
'; this.T_BORDERCOLOR='#c72926'; return escape('<img src=<?php echo $_SESSION['site_shop_prodpicdir']; ?>
/<?php echo $this->_tpl_vars['pic']; ?>
>')" title="<?php echo $this->_tpl_vars['data']['pname']; ?>
">
								<img src="<?php echo $_SESSION['site_shop_prodpicdir']; ?>
/tn_<?php echo $this->_tpl_vars['pic']; ?>
" border="0" alt="<?php echo $this->_tpl_vars['data']['pname']; ?>
" />
							</a>
						<?php endforeach; endif; unset($_from); ?>
                        <script type="text/javascript" src="includes/wz_tooltip.js"></script>
					<?php endif; ?>
				</div>
				<div class="shop_data">
					<a class="shop_ltitle" href="index.php?p=shop&amp;act=prd&amp;cid=<?php echo $this->_tpl_vars['cid']; ?>
&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['data']['pname']; ?>
"><?php echo $this->_tpl_vars['data']['pname']; ?>
</a><br />
					<p class="desc">
						<?php if (((is_array($_tmp=$this->_tpl_vars['data']['pdesc'])) ? $this->_run_mod_handler('count_characters', true, $_tmp) : smarty_modifier_count_characters($_tmp)) > 100): ?>
							<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['data']['pdesc'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 100, "...") : smarty_modifier_truncate($_tmp, 100, "...")))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>

						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['pdesc']; ?>

						<?php endif; ?>
					</p>
					<a class="shop_mtitle" href="index.php?p=shop&amp;act=prd&amp;cid=<?php echo $this->_tpl_vars['cid']; ?>
&amp;pid=<?php echo $this->_tpl_vars['data']['pid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_shop']['main_field_more']; ?>
"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_more']; ?>
</a><br />
					<p><span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_item']; ?>
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
								<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_price']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data']['netto']; ?>
</span>
								+ <?php echo $this->_tpl_vars['data']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['data']['netto'],'y' => $this->_tpl_vars['data']['afa']), $this);?>
</b></span>
								</strike><br />
								<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_actionprice']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['price']; ?>
</span>
								+ <?php echo $this->_tpl_vars['data']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['price'],'y' => $this->_tpl_vars['data']['afa']), $this);?>
</b></span>
							<?php else: ?>
								<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_price']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data']['netto']; ?>
</span>
								+ <?php echo $this->_tpl_vars['data']['afa']; ?>
% <?php echo $this->_tpl_vars['locale']['index_shop']['main_field_vat']; ?>
 = <span class="shop_extra"><b><?php echo smarty_function_math(array('equation' => "x + (x/100*y)",'x' => $this->_tpl_vars['data']['netto'],'y' => $this->_tpl_vars['data']['afa']), $this);?>
</b></span>
							<?php endif; ?>
						</p>
					<?php endif; ?>

										<?php $_from = $this->_tpl_vars['tplfields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['plus']):
?>
						<?php $this->assign('field', $this->_tpl_vars['plus']['value']); ?>
						<p><span class="shop_desc"><?php echo $this->_tpl_vars['plus']['display']; ?>
:</span> <span class="shop_extra"><?php echo $this->_tpl_vars['data'][$this->_tpl_vars['field']]; ?>
</span></p>
					<?php endforeach; endif; unset($_from); ?>
					
										<?php if ($_SESSION['site_shop_userbuy'] == 1): ?>
						<?php if (! $this->_tpl_vars['data']['amount']): ?>
						<div id="target_<?php echo $this->_tpl_vars['data']['pid']; ?>
">
							<input type="text" id="amount_<?php echo $this->_tpl_vars['data']['pid']; ?>
" name="amount[<?php echo $this->_tpl_vars['data']['pid']; ?>
]" size="2" />
							<input type="button" class="submit" value="<?php echo $this->_tpl_vars['locale']['index_shop']['main_button_basket']; ?>
" onclick="bsksend('<?php echo $this->_tpl_vars['data']['pid']; ?>
', '<?php echo $this->_tpl_vars['data']['pname']; ?>
', '<?php echo $this->_tpl_vars['price']; ?>
')" />
						</div>
						<?php else: ?>
							<p class="amount"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_amount1']; ?>
 <span class='shop_extra'><?php echo $this->_tpl_vars['data']['amount']; ?>
</span> <?php echo $this->_tpl_vars['locale']['index_shop']['main_field_amount2']; ?>
</p>
						<?php endif; ?>
					<?php endif; ?>
					
										<?php if ($_SESSION['site_shop_is_rating'] == 1): ?>
						<br />
						<p>
							<span class="shop_desc"><?php echo $this->_tpl_vars['locale']['index_shop']['main_field_avgrating']; ?>
</span> 
							<b> 
							<?php if ($this->_tpl_vars['data']['avg_rating']): ?>
								<?php unset($this->_sections['star']);
$this->_sections['star']['name'] = 'star';
$this->_sections['star']['start'] = (int)0;
$this->_sections['star']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['star']['loop'] = is_array($_loop=$this->_tpl_vars['data']['avg_rating']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
/images/shop_star.gif" alt="<?php echo $this->_tpl_vars['data']['avg_rating']; ?>
" border="0" />
								<?php endfor; else: ?>
									<?php echo $this->_tpl_vars['data']['avg_rating']; ?>

								<?php endif; ?>
							<?php else: ?>
								<?php echo $this->_tpl_vars['locale']['index_shop']['main_field_notrating']; ?>

							<?php endif; ?>
							</b>
							<span class="shop_desc">, <?php echo $this->_tpl_vars['locale']['index_shop']['main_field_ratingcount']; ?>
</span> <b><?php echo $this->_tpl_vars['data']['cnt_rating']; ?>
</b>
						</p>
					<?php endif; ?>
					
				</div>
			</div>
			<?php endforeach; endif; unset($_from); ?>
			</form>
			<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		</div>
		<?php else: ?>
			<div style="clear: both; text-align: center;"><?php echo $this->_tpl_vars['locale']['index_shop']['main_warning_empty']; ?>
</div>
		<?php endif; ?>
	</div>
	<div id="shop_bottom"></div>
</div>