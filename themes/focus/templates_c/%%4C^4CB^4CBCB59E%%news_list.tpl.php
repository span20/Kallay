<?php /* Smarty version 2.6.16, created on 2015-07-20 15:44:40
         compiled from news_list.tpl */ ?>
<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
	<div>
		<div class="cont_title">
			<a href="index.php?mid=<?php echo $_REQUEST['mid']; ?>
&act=show&cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
"><?php echo $this->_tpl_vars['data']['ctitle']; ?>
</a>
		</div>
		<?php if ($this->_tpl_vars['data']['cpic']): ?>
			<div><img src="files/news/<?php echo $this->_tpl_vars['data']['cpic']; ?>
" /></div>
		<?php endif; ?>
		<div><?php echo $this->_tpl_vars['data']['clead']; ?>
</div>
	</div>
	<div style="padding: 10px 0;">
		
	</div>
<?php endforeach; endif; unset($_from); ?>