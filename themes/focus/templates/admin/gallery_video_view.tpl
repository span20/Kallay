<div id="table">
	{include file="admin/dynamic_tabs.tpl"}
	<div id="t_content">
		<div class="t_filter">
            <h3 style="margin:0;">{$lang_title|upper}</h3>
		</div>
		<div class="pager"></div>
			<div style="display: block; text-align: center;">
				<div>
                    {* FLASH PLAYER *}
                    {if $ext == ".flv"}
                        <p id="player1"><a href="http://www.macromedia.com/go/getflashplayer">{$locale.admin_gallery.video_field_getflash}</a></p>
                        <script type="text/javascript">
                            var s1 = new SWFObject("{$libs_dir}/flvplayer.swf", "single", "320", "240", "7");
                            s1.addParam("allowfullscreen", "true");
                            s1.addVariable("file", "../{$smarty.session.site_galerydir}/{$pic.realname}");
                            s1.addVariable("image", "{$smarty.session.site_galerydir}/tn_{$pic.realname}.jpg");
                            s1.write("player1");
                        </script>
                    {* FLASH PLAYER VEGE *}

                    {* EGYEB VIDEOK *}
                    {else}
                        <object NAME="Player" WIDTH="320" HEIGHT="240" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components...">
                            <param name="url" value="{$smarty.session.site_galerydir}/{$pic.realname}" />
                            <param name="ShowStatusBar" value="1" />
                            <param name="autostart" value="0" />
                            <param name="volume" value="100" />
                            <embed name="WMplay" width="320" height="240" type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" src="{$smarty.session.site_galerydir}/{$pic.realname}" AllowChangeDisplaySize="True" ShowControls="1" AutoStart=0 ShowStatusBar="1" Volume="100"></embed>
                        </object>
                    {* EGYEB VIDEOK VEGE *}
                    {/if}
                </div>
                <p>{$locale.admin_gallery.video_field_view_width} {$pic.width} {$locale.admin_gallery.video_field_pixel}, {$locale.admin_gallery.video_field_view_height} {$pic.height} {$locale.admin_gallery.video_field_pixel}</p>
			</div>
		<div class="pager"></div>
	</div>
	<div id="t_bottom"></div>
</div>