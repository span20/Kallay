<?php /* Smarty version 2.6.16, created on 2007-06-20 15:27:12
         compiled from admin/gallery_video.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/gallery_video.tpl', 59, false),array('modifier', 'htmlspecialchars', 'admin/gallery_video.tpl', 66, false),)), $this); ?>
<style type="text/css"><!--
	<?php echo '
	.gallery_thumbnail  { '; ?>

		width: <?php echo $_SESSION['site_thumbwidth']+14; ?>
px;
		height: <?php echo $_SESSION['site_thumbheight']+44; ?>
px;
		position: relative;
	<?php echo '
		border: 2px solid #688da8;
		background-color: #e6edf2;
		text-align:center;line-height:14px;
		margin: 10px;
		display: block;
		float: left;
		voice-family: "\\"}\\"";
		voice-family: inherit;
	'; ?>

		width: <?php echo $_SESSION['site_thumbwidth']+10; ?>
px;
		height: <?php echo $_SESSION['site_thumbheight']+40; ?>
px;
	<?php echo '
	} html>body .gallery_thumbnail { 
	'; ?>

		width: <?php echo $_SESSION['site_thumbwidth']+10; ?>
px;
		height: <?php echo $_SESSION['site_thumbheight']+40; ?>
px;
	<?php echo '
	} 
	.gallery_thumbnail span {
		font-size:10px;
		display: block;
		text-align:center; '; ?>

		width: <?php echo $_SESSION['site_thumbwidth']+8; ?>
px; 
		position: absolute; 
		top: <?php echo $_SESSION['site_thumbheight']+2; ?>
px; 
		left: 0px;   
	<?php echo '
	}
	'; ?>

//-->
</style>
<script type="text/javascript">//<![CDATA[
<?php echo '
	function torol(pid)
	{
	'; ?>

		x = confirm('<?php echo $this->_tpl_vars['locale']['admin_gallery']['video_confirm_vdel']; ?>
');
		gid = <?php echo $this->_tpl_vars['gid']; ?>
;

		if (x) <?php echo '{'; ?>

			document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&act=<?php echo $this->_tpl_vars['this_page']; ?>
&sub_act=pdel&gid='+gid+'&pid='+pid;
		<?php echo '}'; ?>

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
		<div class="t_filter">
            <h3 style="margin:0;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lang_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
        </div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
			<div style="text-align: center; display: block; background: none;">
				<div style="margin: auto; text-align: left; background: none; width: auto;">
					<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
						<div class="gallery_thumbnail">
							<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=view&amp;gid=<?php echo $this->_tpl_vars['gid']; ?>
&amp;pid=<?php echo $this->_tpl_vars['data']['picture_id']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
">
                                <img src="<?php echo $_SESSION['site_galerydir']; ?>
/tn_<?php echo $this->_tpl_vars['data']['realname']; ?>
.jpg" width="<?php echo $this->_tpl_vars['data']['tn_width']; ?>
" height="<?php echo $this->_tpl_vars['data']['tn_height']; ?>
" alt="<?php echo $this->_tpl_vars['data']['name']; ?>
" />
                            </a>
							<span>
								<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=pmod&amp;gid=<?php echo $this->_tpl_vars['gid']; ?>
&amp;pid=<?php echo $this->_tpl_vars['data']['picture_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_gallery']['video_list_vmodify']; ?>
">[<?php echo $this->_tpl_vars['locale']['admin_gallery']['video_list_vmodify']; ?>
]</a><br>
								<a href="javascript:torol(<?php echo $this->_tpl_vars['data']['picture_id']; ?>
)" title="<?php echo $this->_tpl_vars['locale']['admin_gallery']['video_list_vdelete']; ?>
">[<?php echo $this->_tpl_vars['locale']['admin_gallery']['video_list_vdelete']; ?>
]</a>
							</span>
						</div>
					<?php endforeach; else: ?>
						<p class="hiba" style="clear:left;"><?php echo $this->_tpl_vars['locale']['admin_gallery']['warning_vids_empty']; ?>
</p>
					<?php endif; unset($_from); ?>
				</div>
			</div>
		<div class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
	</div>
	<div id="t_bottom"></div>
</div>