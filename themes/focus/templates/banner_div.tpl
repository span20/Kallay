<div class="banners" id="bannerplace_{$divBannerCnt}">
{foreach from=$banners.$divBannerCnt item=banner name=bfore}
    {assign var=bbid value=$banner.banner_id}
    {assign var=bmid value=$banner.menu_id}
    {assign var=bpid value=$banner.place_id}
    {assign var=banner_link value="index.php?p=banners&bid=$bbid&mid=$bmid&pid=$bpid"}
    {if $smarty.foreach.bfore.first}
    {assign var=bannerStyle value=' style="z-index:10;"'}
    {else}
    {assign var=bannerStyle value=' style="display:none;"'}
    {/if}
    {if !empty($banner.banner_code)}
        <div class="banner"{$bannerStyle}>
            <div>
            {$banner.banner_code}
            </div>
        </div>
    {elseif $banner.type==13 || $banner.type==4}
        <div class="banner"{$bannerStyle}>
        <div>
		<object type="application/x-shockwave-flash" id="medikemia"
			data="{$smarty.session.site_bannerdir}/{$banner.pic}?clickTAG={$banner_link|urlencode}&amp;clickTARGET=_blank" {redim_banner width=$banner.width height=$banner.height max_width=$banner.max_width max_height=$banner.max_height}>
				<param name="movie" value="{$smarty.session.site_bannerdir}/{$banner.pic}?clickTAG={$banner_link|urlencode}&amp;clickTARGET=_blank" />
				<param name="allowScriptAccess" value="sameDomain" />
    			<param name="quality" value="high" />
				<param name="scale" value="Scale" />
				<param name="salign" value="TL" />
				<param name="FlashVars" value="playerMode=embedded" />
		</object>
		</div>
		</div>
	{else}
	   <a class="banner" href="{$banner_link|htmlspecialchars}" title="{$banner.name}" target="_blank"{$bannerStyle}>
	       <img src="{$smarty.session.site_bannerdir}/{$banner.pic}" alt="{$banner.name}" {redim_banner width=$banner.width height=$banner.height max_width=$banner.max_width max_height=$banner.max_height} />
	   </a>
	{/if}
{/foreach}
</div>
