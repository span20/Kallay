<?php /* Smarty version 2.6.16, created on 2007-06-21 10:07:00
         compiled from admin/gallery_video_view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/gallery_video_view.tpl', 5, false),)), $this); ?>
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
		<div class="pager"></div>
			<div style="display: block; text-align: center;">
				<div>
                                        <?php if ($this->_tpl_vars['ext'] == ".flv"): ?>
                        <p id="player1"><a href="http://www.macromedia.com/go/getflashplayer"><?php echo $this->_tpl_vars['locale']['admin_gallery']['video_field_getflash']; ?>
</a></p>
                        <script type="text/javascript">
                            var s1 = new SWFObject("<?php echo $this->_tpl_vars['libs_dir']; ?>
/flvplayer.swf", "single", "320", "240", "7");
                            s1.addParam("allowfullscreen", "true");
                            s1.addVariable("file", "../<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['pic']['realname']; ?>
");
                            s1.addVariable("image", "<?php echo $_SESSION['site_galerydir']; ?>
/tn_<?php echo $this->_tpl_vars['pic']['realname']; ?>
.jpg");
                            s1.write("player1");
                        </script>
                    
                                        <?php else: ?>
                        <object NAME="Player" WIDTH="320" HEIGHT="240" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components...">
                            <param name="url" value="<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['pic']['realname']; ?>
" />
                            <param name="ShowStatusBar" value="1" />
                            <param name="autostart" value="0" />
                            <param name="volume" value="100" />
                            <embed name="WMplay" width="320" height="240" type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" src="<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['pic']['realname']; ?>
" AllowChangeDisplaySize="True" ShowControls="1" AutoStart=0 ShowStatusBar="1" Volume="100"></embed>
                        </object>
                                        <?php endif; ?>
                </div>
                <p><?php echo $this->_tpl_vars['locale']['admin_gallery']['video_field_view_width']; ?>
 <?php echo $this->_tpl_vars['pic']['width']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_gallery']['video_field_pixel']; ?>
, <?php echo $this->_tpl_vars['locale']['admin_gallery']['video_field_view_height']; ?>
 <?php echo $this->_tpl_vars['pic']['height']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_gallery']['video_field_pixel']; ?>
</p>
			</div>
		<div class="pager"></div>
	</div>
	<div id="t_bottom"></div>
</div>