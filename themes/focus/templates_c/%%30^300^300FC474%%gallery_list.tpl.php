<?php /* Smarty version 2.6.16, created on 2015-06-12 19:21:24
         compiled from gallery_list.tpl */ ?>
<?php $_from = $this->_tpl_vars['picgals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
	<div class="col-md-4 gallery_item" data-galleryid="<?php echo $this->_tpl_vars['data']['gallery_id']; ?>
">
		<a href="files/gallery/<?php echo $this->_tpl_vars['data']['realname']; ?>
" rel="prettyPhoto[gal<?php echo $this->_tpl_vars['data']['gallery_id']; ?>
]"><img src="files/gallery/tn_<?php echo $this->_tpl_vars['data']['realname']; ?>
" class="img-responsive" /></a>
		<div class="gal_title">
			<?php echo $this->_tpl_vars['data']['name']; ?>

		</div>
	</div>
<?php endforeach; endif; unset($_from); ?>