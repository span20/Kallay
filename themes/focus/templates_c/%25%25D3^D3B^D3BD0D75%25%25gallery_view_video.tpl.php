<?php /* Smarty version 2.6.16, created on 2007-06-21 12:36:56
         compiled from gallery_view_video.tpl */ ?>
<?php if ($_SESSION['site_gallery_is_video']): ?>
<div style="padding-left: 5px;">

	<div style="background-color: #EFD0B5; height:27px; width:754px;">
		<div class="cim" style="padding-top:6px; padding-left:13px;"><?php echo $this->_tpl_vars['video_name']; ?>
</div>
	</div>

	<div style="float: left; width: 200px; height: 272px; background-image: url(<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/video_hatter.gif);">
	<br />
	<span style="padding-left: 10px;" class="cim"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_videoact']; ?>
</span>
	<?php $_from = $this->_tpl_vars['videos_actgal']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vidact']):
?>
		<p style="padding-right: 10px; border-top: 1px solid #603916; background-color: #FFFFFF; border-bottom: 1px solid #603916; border-right: 1px solid #603916; width: 180px; text-align: right;" class="szoveg">
			<?php echo $this->_tpl_vars['vidact']['vidname']; ?>
<br />
			<?php echo $this->_tpl_vars['vidact']['filesize']; ?>
 Mbyte<br />
			<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&act=gallery_view&vid=<?php echo $this->_tpl_vars['vidact']['vidid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_watch']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_watch']; ?>
 &raquo;</a>
			<?php if ($this->_tpl_vars['vidact']['download']): ?>
				<br /><a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=gallery_dwn&amp;pid=<?php echo $this->_tpl_vars['vidact']['vidid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_download']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_download']; ?>
</a>
			<?php endif; ?>
		</p>	
	<?php endforeach; endif; unset($_from); ?>	
	</div>
	
	<div align="center" style="float:left; width: 354px; text-align: center; height: 272px; background-image: url(<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/video_hatter.gif);">
	<br />
		<object NAME="Player" WIDTH="320" HEIGHT="240" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components...">
		   <param name="url" value="<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['video']['video_file']; ?>
" />
		   <param name="ShowStatusBar" value="1" />
		   <param name="autostart" value="1" />
		   <param name="volume" value="100" />
		   <embed name="WMplay"	width="320" height="240" type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" src="<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['video']['video_file']; ?>
" AllowChangeDisplaySize="True" ShowControls="1" AutoStart=1 ShowStatusBar="1" Volume="100"></embed>
		</object>
			<?php if ($this->_tpl_vars['video']['video_down']): ?>
				<br><a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=gallery_dwn&amp;pid=<?php echo $this->_tpl_vars['video']['video_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_download']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_download']; ?>
</a>
			<?php endif; ?>
	</div>	
	
	<div style="float: left; width: 200px; height: 272px; background-image: url(<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/video_hatter.gif);" align="right">
	<br />
	<span style="padding-right: 10px;" class="cim"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_videooth']; ?>
</span>
	<?php $_from = $this->_tpl_vars['videos_othgal']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vidoth']):
?>
		<p style="text-align: left; padding-left: 10px; border-top: 1px solid #603916; background-color: #FFFFFF; border-bottom: 1px solid #603916; border-left: 1px solid #603916; width: 180px;" class="szoveg">
			<?php echo $this->_tpl_vars['vidoth']['vidname']; ?>
<br />
			<?php echo $this->_tpl_vars['vidoth']['filesize']; ?>
 Mbyte<br />
			<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&act=gallery_view&vid=<?php echo $this->_tpl_vars['vidoth']['vidid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_watch']; ?>
">&laquo; <?php echo $this->_tpl_vars['locale']['index_gallery']['field_watch']; ?>
</a>
			<?php if ($this->_tpl_vars['vidoth']['download']): ?>
				<br /><a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&act=gallery_view&vid=<?php echo $this->_tpl_vars['vidoth']['vidid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_download']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_download']; ?>
</a>
			<?php endif; ?>
		</p>
	<?php endforeach; endif; unset($_from); ?>	
	</div>
</div>
<?php endif; ?>