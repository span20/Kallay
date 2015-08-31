<div style="height: 265px;">
    {foreach from=$pd_gallery item=picture name="gal"}
        <div style="float: left; {if $smarty.foreach.gal.iteration eq 2 || $smarty.foreach.gal.iteration eq 5 || $smarty.foreach.gal.iteration eq 8}padding-right: 4px; padding-left: 4px;{/if} padding-bottom: 5px;">
            {if $smarty.foreach.gal.first}
                <script>
                    loadBigPic('{$smarty.session.site_galerydir}/{$picture.realname}');
                </script>
            {/if}
            <a href="javascript:loadBigPic('{$smarty.session.site_galerydir}/{$picture.realname}');" title="{$picture.name|htmlspecialchars}"><img src="{$smarty.session.site_galerydir}/tn_{$picture.realname}" width="{$picture.tn_width}" height="{$picture.tn_height}" alt="{$picture.name|htmlspecialchars}" border="0" /></a><br>
        </div>
    {/foreach}
</div>
<div style="float: right;">
    {math equation="x * y" x=$section y=10 assign="sec_loop"}
    
    {math equation="x + y" x=$sec_loop y=1 assign="loop"}
    {math equation="x - y" x=$sec_loop y=9 assign="from"}
    {if $smarty.request.section gt 1}<div style="float: left; padding: 3px 3px 0 0;"><a href="index.php?mid={$smarty.request.mid}&page={math equation="(x * y) - z" x=$section_minus y=10 z=10 assign="npage"}{$npage}&section={$section_minus}"><img src="{$theme_dir}/images/pager_arrow_left.png" /></a></div>{/if}
    {section start=$from loop=$loop name="pager_foreach"}
        {if $all_pages gt $smarty.section.pager_foreach.index - 1}
            <div rel="pager" id="pager_{$data.cid}" style="float: left; padding: 0 2px 6px 2px; {if ($smarty.request.page && $smarty.request.page eq $smarty.section.pager_foreach.index -1) || (!$smarty.request.page && $smarty.section.pager_foreach.first)}background: url('{$theme_dir}/images/pager_arrow.png') no-repeat bottom center;{/if}"><a href="index.php?mid={$smarty.request.mid}&page={math equation="x - y" x=$smarty.section.pager_foreach.index y=1 assign="pagenum"}{$pagenum}&section={$section}">{$smarty.section.pager_foreach.index}</a></div>
        {/if}
    {/section}
    {if $all_sections gt 1 && $all_sections neq $smarty.request.section}
        <div style="float: left; padding: 3px 0 0 3px;"><a href="index.php?mid={$smarty.request.mid}&page={$sec_loop}&section={$section_plus}"><img src="{$theme_dir}/images/pager_arrow_right.png" /></a></div>
    {/if}
</div>