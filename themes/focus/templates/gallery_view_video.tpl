{if $smarty.session.site_gallery_is_video}
<div style="padding-left: 5px;">

	<div style="background-color: #EFD0B5; height:27px; width:754px;">
		<div class="cim" style="padding-top:6px; padding-left:13px;">{$video_name}</div>
	</div>

	<div style="float: left; width: 200px; height: 272px; background-image: url({$theme_dir}/images/video_hatter.gif);">
	<br />
	<span style="padding-left: 10px;" class="cim">{$locale.index_gallery.field_videoact}</span>
	{foreach from=$videos_actgal item=vidact}
		<p style="padding-right: 10px; border-top: 1px solid #603916; background-color: #FFFFFF; border-bottom: 1px solid #603916; border-right: 1px solid #603916; width: 180px; text-align: right;" class="szoveg">
			{$vidact.vidname}<br />
			{$vidact.filesize} Mbyte<br />
			<a href="index.php?{$self}&act=gallery_view&vid={$vidact.vidid}" title="{$locale.index_gallery.field_watch}">{$locale.index_gallery.field_watch} &raquo;</a>
			{if $vidact.download}
				<br /><a href="index.php?{$self}&amp;act=gallery_dwn&amp;pid={$vidact.vidid}" title="{$locale.index_gallery.field_download}">{$locale.index_gallery.field_download}</a>
			{/if}
		</p>	
	{/foreach}	
	</div>
	
	<div align="center" style="float:left; width: 354px; text-align: center; height: 272px; background-image: url({$theme_dir}/images/video_hatter.gif);">
	<br />
		<object NAME="Player" WIDTH="320" HEIGHT="240" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components...">
		   <param name="url" value="{$smarty.session.site_galerydir}/{$video.video_file}" />
		   <param name="ShowStatusBar" value="1" />
		   <param name="autostart" value="1" />
		   <param name="volume" value="100" />
		   <embed name="WMplay"	width="320" height="240" type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" src="{$smarty.session.site_galerydir}/{$video.video_file}" AllowChangeDisplaySize="True" ShowControls="1" AutoStart=1 ShowStatusBar="1" Volume="100"></embed>
		</object>
			{if $video.video_down}
				<br><a href="index.php?{$self}&amp;act=gallery_dwn&amp;pid={$video.video_id}" title="{$locale.index_gallery.field_download}">{$locale.index_gallery.field_download}</a>
			{/if}
	</div>	
	
	<div style="float: left; width: 200px; height: 272px; background-image: url({$theme_dir}/images/video_hatter.gif);" align="right">
	<br />
	<span style="padding-right: 10px;" class="cim">{$locale.index_gallery.field_videooth}</span>
	{foreach from=$videos_othgal item=vidoth}
		<p style="text-align: left; padding-left: 10px; border-top: 1px solid #603916; background-color: #FFFFFF; border-bottom: 1px solid #603916; border-left: 1px solid #603916; width: 180px;" class="szoveg">
			{$vidoth.vidname}<br />
			{$vidoth.filesize} Mbyte<br />
			<a href="index.php?{$self}&act=gallery_view&vid={$vidoth.vidid}" title="{$locale.index_gallery.field_watch}">&laquo; {$locale.index_gallery.field_watch}</a>
			{if $vidoth.download}
				<br /><a href="index.php?{$self}&act=gallery_view&vid={$vidoth.vidid}" title="{$locale.index_gallery.field_download}">{$locale.index_gallery.field_download}</a>
			{/if}
		</p>
	{/foreach}	
	</div>
</div>
{/if}