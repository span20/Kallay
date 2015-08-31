<?php /* Smarty version 2.6.16, created on 2007-06-29 16:13:58
         compiled from admin/banners_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/banners_list.tpl', 59, false),array('modifier', 'regex_replace', 'admin/banners_list.tpl', 79, false),array('function', 'getdim', 'admin/banners_list.tpl', 78, false),)), $this); ?>
<style type="text/css"><!--<?php echo '
.thumbnail_border{
border: 2px solid #688da8;
margin: 5px;
float: left;
display: inline;
}
.thumbnail .spacer {'; ?>

height: <?php echo $_SESSION['site_banner_widths']; ?>
px;<?php echo '
background-color: #C9D5DF;
margin-top: 5px;
display: block;
background-color: #C9D5DF;
}
.thumbnail{'; ?>

width: <?php echo $_SESSION['site_banner_widths']; ?>
px;<?php echo '
overflow:hidden;
text-align:center;
font-size:10px;
color: #FFFFFF;
}
.thumbnail span{
background-color: #98ABB8;
display:block;
margin: 0px auto;
height: 14px;
line-height: 14px;
overflow:hidden;
}
.thumbnail img, .thumbnail object {
border:none;
margin:auto;
}
'; ?>

-->
</style>
<script type="text/javascript">//<![CDATA[
<?php echo '
	function torol(bid)
	{
	'; ?>

		x = confirm('<?php echo $this->_tpl_vars['locale']['admin_banners']['confirm_del_banner']; ?>
');
		oid=<?php echo $this->_tpl_vars['oid']; ?>
;
		<?php echo '
		if (x) {
			document.location.href=\'admin.php?p=';  echo $this->_tpl_vars['self'];  echo '&act=';  echo $this->_tpl_vars['this_page'];  echo '&sub_act=bdel&oid=\'+oid+\'&bid=\'+bid;
		}
		'; ?>

	<?php echo '
	}
'; ?>

//]]>
</script>

<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div id="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
        </div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div style="margin:auto;text-align:left;background:none;">
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			  <div class="thumbnail_border">
				<div class="thumbnail">
					<span>
                                                <?php if ($this->_tpl_vars['data']['banner_link']):  echo $this->_tpl_vars['data']['banner_link'];  endif; ?>
                                                                        <?php if ($this->_tpl_vars['data']['banner_code']):  echo $this->_tpl_vars['locale']['admin_banners']['field_outside'];  endif; ?>
                                            </span>
					<div class="spacer">
                    <?php if ($this->_tpl_vars['data']['banner_link']): ?>
    					<?php if ($this->_tpl_vars['data']['type'] == '4'): ?>
    						<object type="application/x-shockwave-flash"
    							<?php echo get_dimensions(array('width' => $this->_tpl_vars['data']['width'],'height' => $this->_tpl_vars['data']['height']), $this);?>

    							data="<?php echo ((is_array($_tmp=$_SESSION['site_bannerdir'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "|/$|", "") : smarty_modifier_regex_replace($_tmp, "|/$|", "")); ?>
/<?php echo $this->_tpl_vars['data']['realname']; ?>
">
    							<param name="movie" value="<?php echo ((is_array($_tmp=$_SESSION['site_bannerdir'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "|/$|", "") : smarty_modifier_regex_replace($_tmp, "|/$|", "")); ?>
/<?php echo $this->_tpl_vars['data']['realname']; ?>
" />
    							<param name="quality" value="high" />
    							<param name="loop" value="false" />
    							<param name="FlashVars" value="playerMode=embedded" />
    							<param name="bgcolor" value="#000000" />
    						</object>
    					<?php else: ?>
    						<img 
    							<?php echo get_dimensions(array('width' => $this->_tpl_vars['data']['width'],'height' => $this->_tpl_vars['data']['height']), $this);?>
  
    							src="<?php echo ((is_array($_tmp=$_SESSION['site_bannerdir'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "|/$|", "") : smarty_modifier_regex_replace($_tmp, "|/$|", "")); ?>
/<?php echo $this->_tpl_vars['data']['realname']; ?>
" />
    					<?php endif; ?>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['data']['banner_code']): ?>
                        <div style="width: <?php echo $_SESSION['site_banner_widths']; ?>
px; overflow: auto;"><?php echo $this->_tpl_vars['data']['banner_code']; ?>
</div>
                    <?php endif; ?>
					</div>
					<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=bacta&amp;oid=<?php echo $this->_tpl_vars['oid']; ?>
&amp;bid=<?php echo $this->_tpl_vars['data']['banner_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_activate']; ?>
"></a>
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=bmod&amp;oid=<?php echo $this->_tpl_vars['oid']; ?>
&amp;bid=<?php echo $this->_tpl_vars['data']['banner_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_modify']; ?>
"></a>
					<a class="action del" href="javascript:torol(<?php echo $this->_tpl_vars['data']['banner_id']; ?>
);" title="<?php echo $this->_tpl_vars['locale']['admin_banners']['field_list_delete']; ?>
"></a>
				</div>
			 </div>
			<?php endforeach; else: ?>
				<p class="empty" style="clear:left;">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_banners']['warning_no_banners']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_banners']['warning_no_banners']; ?>

				</p>
			<?php endif; unset($_from); ?>
		</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
	</div>
	<div id="t_bottom"></div>
</div>