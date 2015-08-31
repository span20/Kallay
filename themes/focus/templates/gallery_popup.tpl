<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
    <title>{$smarty.session.site_sitename}</title>

    <style type="text/css">
    {literal}
        body {
            margin: 0px;
            padding: 0px;
        }
        .kiskepek {
            height: 80px;
            overflow: hidden;
            float: left;
            position: relative;
            top: 0px;
        }
        img {
            border: 0;
        }
        .header {{/literal}
            width: 618px;
            height: 117px;
            background: url({$theme_dir}/images/galeria_fejlec.gif) no-repeat;
        {literal}}
        .footer {{/literal}
            clear: both;
            height: 45px;
            background: url({$theme_dir}/images/galeria_lablec.gif) no-repeat;
        {literal}}
    {/literal}
    </style>

    <script type="text/javascript">
    {literal}
        var megy;

        function scroll(i,ujleft,osszkepszel)
        {
            var kiskeptar = document.getElementById('kiskeptar');
            var kepdiv    = document.getElementById('kepdiv');

            if (i == 1) {
                if (Math.abs(parseInt(ujleft)) < parseInt(osszkepszel) - parseInt(kepdiv.style.width)) {
                    kiskeptar.style.left = parseInt(ujleft)-parseInt(10)+'px';
                    megy                 = setTimeout("scroll(1,'" + kiskeptar.style.left + "'," + osszkepszel + ");", 50);   
                }
            }

            if (i == 2) {
                if (parseInt(ujleft) < 0) {
                    kiskeptar.style.left = parseInt(ujleft)+parseInt(10)+'px'; 
                    megy                 = setTimeout("scroll(2,'" + kiskeptar.style.left + "'," + osszkepszel + ");", 50);
                }
            }
        }

        function stopScroll()
        {
            clearTimeout(megy);
        }
    {/literal}
    </script>

    {if $ajax.link}
        <script type="text/javascript" src="{$ajax.link}"></script>
    {/if}
    {if $ajax.script}
        <script type="text/javascript">//<![CDATA[{$ajax.script}//]]></script>
    {/if}
</head>

<body>
<div style="text-align: center; width: 618px;">
    {* FEJLEC *}
    <div class="header"></div>
    {* FEJLEC VEGE *}

    <div style="float: left; width: 618px; background: url('{$theme_dir}/images/galeria_hatter.gif') repeat-y;">
        <div id="target">
            {* NAGY KEP *}
            <div>
                <div style="float: left; padding: 150px 0 0 50px; width: 59px; vertical-align: middle;">
                    <a href="javascript:void(0);" onclick="pic_change('{$prevkep}','{$gid}')" title=""><img src="{$theme_dir}/images/galeria_bal_nyil_nagy.gif" alt="" /></a>
                </div>
                <div style="float: left; width: 400px;">
                    <img style="text-align: center;" id="nagykep" src="files/gallery/{$aktkep}" alt="{$aktkep_nev}" /><br />
                    <b>{$aktkep_nev}</b>
                </div>
                <div style="float: left; padding: 150px 50px 0 0; width: 59px; vertical-align: middle;">
                    <a href="javascript:void(0);" onclick="pic_change('{$kovkep}','{$gid}')" title=""><img src="{$theme_dir}/images/galeria_jobb_nyil_nagy.gif" alt="" /></a>
                </div>
            </div>
            {* NAGY KEP VEGE *}

            {* ERTEKELES *}
            {if !empty($smarty.session.site_gallery_is_rating)}
            <div>
                <tr>
                    <td colspan="3" align="center" style="height: 60px;">
                        {$locale.index_gallery.field_rating1} <b>{$cntrate}</b> {$locale.index_gallery.field_rating2} <b>{$sumrate}</b>
                        {if $usrrate}
                            <br />{$locale.index_gallery.field_rating3} <b>{$usrrate}</b>
                        {else}
                            <br />
                            <form method="post" action="gallery_popup.php" style="margin: 0;">
                                <input type="hidden" name="gid" value="{$gid}">
                                <input type="hidden" name="kid" value="{$kid}">
                                {section name=rateval start=1 loop=11 step=1}
                                    <input type="radio" id="picrate_{$smarty.section.rateval.index}" name="picrate" value="{$smarty.section.rateval.index}" onclick="document.forms[0].submit()" />
                                    <label for="picrate_{$smarty.section.rateval.index}">{$smarty.section.rateval.index}</label>&nbsp;
                                {/section}
                            </form>
                        {/if}
                    </td>
                </tr>
            </div>
            {/if}
            {* ERTEKELES VEGE *}
        </div>

        {* KIS KEP *}
        <div style="float: left;">
            <div style="float: left; padding: 30px 0 0 50px; width: 59px; vertical-align: middle;">
                <img src="{$theme_dir}/images/galeria_bal_nyil_kicsi.gif" alt="" onmouseover="scroll(2,document.getElementById('kiskeptar').style.left,{$osszkepszel});" onmouseout="stopScroll();" />
            </div>
            <div class="kiskepek" id="kepdiv" style="text-align: center; width: 400px; height: 70px; padding-top: 4px;">
                <table id="kiskeptar" style="position: absolute; left: 0px; text-align: center;">
                    <tr>
                    {foreach from=$kiskepek key=key item=data}
                        <td>
                            <a onclick="pic_change('{$data.pid}','{$data.gid}')" title="">
                                <img style="padding-right: 2px;" src="files/gallery/tn_{$data.name}" alt="" />
                            </a>
                        </td>
                    {/foreach}
                    </tr>
                </table>
            </div>
            <div style="float: right; padding: 30px 50px 0 0; width: 59px; vertical-align: middle;">
                <img src="{$theme_dir}/images/galeria_jobb_nyil_kicsi.gif" alt="" onmouseover="scroll(1,document.getElementById('kiskeptar').style.left,{$osszkepszel});" onmouseout="stopScroll();" />
            </div>
        </div>
        {* KIS KEP VEGE *}
    </div>

    {* LABLEC *}
    <div class="footer"></div>
    {* LABLEC VEGE *}
</div>
</body>

</html>