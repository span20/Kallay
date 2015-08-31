<?php /* Smarty version 2.6.16, created on 2007-11-19 14:49:57
         compiled from news_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'news_list.tpl', 5, false),)), $this); ?>
<div id="cnt">
	<div id="cnt_top">
		<span style="padding-left: 10px;">
		<?php if (! empty ( $_GET['p'] )): ?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['locale']['index_news']['field_main_header'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

		<?php elseif (! empty ( $this->_tpl_vars['page_data']['0']['category_name'] )): ?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['page_data']['0']['category_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

		<?php endif; ?>
		</span>
	</div>
	<div id="cnt_cnt">
		<p style="text-align: center;"><?php echo $this->_tpl_vars['page_list']; ?>
</p>
		<?php $_from = $this->_tpl_vars['page_data_news']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<div style="border-bottom: #9B9B9B 1px solid;">
			<div class="cnt_text" style="padding-bottom: 10px; padding-top: 10px;">
				<?php echo $this->_tpl_vars['data']['ctitle']; ?>
 [<?php echo $this->_tpl_vars['data']['add_date']; ?>

				<?php if ($_SESSION['site_cnt_is_viewcounter']): ?> | <?php echo $this->_tpl_vars['locale']['index_news']['field_main_viewcounter2']; ?>
 <?php echo $this->_tpl_vars['data']['counter'];  endif; ?>
				<?php if ($_SESSION['site_cnt_is_rating']): ?> | <?php echo $this->_tpl_vars['locale']['index_news']['field_main_avgrating2']; ?>
 <?php if ($this->_tpl_vars['data']['ratings']):  echo $this->_tpl_vars['data']['ratings'];  else: ?>0<?php endif;  endif; ?>
				<?php if ($_SESSION['site_cnt_is_comment']): ?> | <?php echo $this->_tpl_vars['locale']['index_news']['field_main_comment2']; ?>
 <?php if ($this->_tpl_vars['data']['comments']):  echo $this->_tpl_vars['data']['comments'];  else: ?>0<?php endif;  endif; ?>
				]
			</div>
			<?php if ($this->_tpl_vars['data']['cpic'] != "" && ( ( $this->_tpl_vars['data']['main'] == 1 && $_SESSION['site_leadpic'] == 1 ) || ( $this->_tpl_vars['data']['main'] == 0 && $_SESSION['site_newspic'] == 1 ) )): ?>
				<div style="clear: both; float: left; padding-left: 10px;"><img src="<?php echo $_SESSION['site_cnt_picdir']; ?>
/<?php echo $this->_tpl_vars['data']['cpic']; ?>
" border="0" alt="<?php echo $this->_tpl_vars['data']['ctitle']; ?>
" /></div>
			<?php endif; ?>
			<div class="cnt_lead">
				<?php echo $this->_tpl_vars['data']['clead']; ?>

			</div>
			<div class="cnt_text" style="clear: both; font-size: 10px; padding-bottom: 10px; padding-top: 10px;">
				<a href="index.php?p=<?php echo $this->_tpl_vars['self_news']; ?>
&amp;act=show&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_news']['field_main_details']; ?>
"><?php echo $this->_tpl_vars['locale']['index_news']['field_main_details']; ?>
</a>
			</div>
		</div>
		<?php endforeach; else: ?>
		<div>
			<p class="cnt_text" style="text-align: center;"><?php echo $this->_tpl_vars['locale']['index_news']['warning_empty_news']; ?>
</p>
		</div>
		<?php endif; unset($_from); ?>
		<p style="text-align: center;"><?php echo $this->_tpl_vars['page_list_news']; ?>
</p>
	</div>
</div>