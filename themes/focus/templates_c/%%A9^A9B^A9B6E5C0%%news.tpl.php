<?php /* Smarty version 2.6.16, created on 2015-07-30 09:43:05
         compiled from news.tpl */ ?>
<div style="font-size: 15px; line-height: 22px;">
	<h2 class="news_title"><?php echo $this->_tpl_vars['news_title']; ?>
</h2>
	<?php if ($this->_tpl_vars['news_cpic']): ?>
		<div><img src="files/news/<?php echo $this->_tpl_vars['news_cpic']; ?>
" /></div>
	<?php endif; ?>
	<div style="padding-top: 10px;" class="news_content"><?php echo $this->_tpl_vars['news_content']; ?>
</div>
	<div style="padding-top: 10px;">
		<a href="index.php">vissza</a>
	</div>
</div>