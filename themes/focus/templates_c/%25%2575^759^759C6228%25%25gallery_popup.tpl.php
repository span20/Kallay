<?php /* Smarty version 2.6.16, created on 2007-07-23 11:58:15
         compiled from gallery_popup.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
    <title><?php echo $_SESSION['site_sitename']; ?>
</title>

    <style type="text/css">
    <?php echo '
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
        .header {'; ?>

            width: 618px;
            height: 117px;
            background: url(<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_fejlec.gif) no-repeat;
        <?php echo '}
        .footer {'; ?>

            clear: both;
            height: 45px;
            background: url(<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_lablec.gif) no-repeat;
        <?php echo '}
    '; ?>

    </style>

    <script type="text/javascript">
    <?php echo '
        var megy;

        function scroll(i,ujleft,osszkepszel)
        {
            var kiskeptar = document.getElementById(\'kiskeptar\');
            var kepdiv    = document.getElementById(\'kepdiv\');

            if (i == 1) {
                if (Math.abs(parseInt(ujleft)) < parseInt(osszkepszel) - parseInt(kepdiv.style.width)) {
                    kiskeptar.style.left = parseInt(ujleft)-parseInt(10)+\'px\';
                    megy                 = setTimeout("scroll(1,\'" + kiskeptar.style.left + "\'," + osszkepszel + ");", 50);   
                }
            }

            if (i == 2) {
                if (parseInt(ujleft) < 0) {
                    kiskeptar.style.left = parseInt(ujleft)+parseInt(10)+\'px\'; 
                    megy                 = setTimeout("scroll(2,\'" + kiskeptar.style.left + "\'," + osszkepszel + ");", 50);
                }
            }
        }

        function stopScroll()
        {
            clearTimeout(megy);
        }
    '; ?>

    </script>

    <?php if ($this->_tpl_vars['ajax']['link']): ?>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['ajax']['link']; ?>
"></script>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['ajax']['script']): ?>
        <script type="text/javascript">//<![CDATA[<?php echo $this->_tpl_vars['ajax']['script']; ?>
//]]></script>
    <?php endif; ?>
</head>

<body>
<div style="text-align: center; width: 618px;">
        <div class="header"></div>
    
    <div style="float: left; width: 618px; background: url('<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_hatter.gif') repeat-y;">
        <div id="target">
                        <div>
                <div style="float: left; padding: 150px 0 0 50px; width: 59px; vertical-align: middle;">
                    <a href="javascript:void(0);" onclick="pic_change('<?php echo $this->_tpl_vars['prevkep']; ?>
','<?php echo $this->_tpl_vars['gid']; ?>
')" title=""><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_bal_nyil_nagy.gif" alt="" /></a>
                </div>
                <div style="float: left; width: 400px;">
                    <img style="text-align: center;" id="nagykep" src="files/gallery/<?php echo $this->_tpl_vars['aktkep']; ?>
" alt="<?php echo $this->_tpl_vars['aktkep_nev']; ?>
" /><br />
                    <b><?php echo $this->_tpl_vars['aktkep_nev']; ?>
</b>
                </div>
                <div style="float: left; padding: 150px 50px 0 0; width: 59px; vertical-align: middle;">
                    <a href="javascript:void(0);" onclick="pic_change('<?php echo $this->_tpl_vars['kovkep']; ?>
','<?php echo $this->_tpl_vars['gid']; ?>
')" title=""><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_jobb_nyil_nagy.gif" alt="" /></a>
                </div>
            </div>
            
                        <?php if (! empty ( $_SESSION['site_gallery_is_rating'] )): ?>
            <div>
                <tr>
                    <td colspan="3" align="center" style="height: 60px;">
                        <?php echo $this->_tpl_vars['locale']['index_gallery']['field_rating1']; ?>
 <b><?php echo $this->_tpl_vars['cntrate']; ?>
</b> <?php echo $this->_tpl_vars['locale']['index_gallery']['field_rating2']; ?>
 <b><?php echo $this->_tpl_vars['sumrate']; ?>
</b>
                        <?php if ($this->_tpl_vars['usrrate']): ?>
                            <br /><?php echo $this->_tpl_vars['locale']['index_gallery']['field_rating3']; ?>
 <b><?php echo $this->_tpl_vars['usrrate']; ?>
</b>
                        <?php else: ?>
                            <br />
                            <form method="post" action="gallery_popup.php" style="margin: 0;">
                                <input type="hidden" name="gid" value="<?php echo $this->_tpl_vars['gid']; ?>
">
                                <input type="hidden" name="kid" value="<?php echo $this->_tpl_vars['kid']; ?>
">
                                <?php unset($this->_sections['rateval']);
$this->_sections['rateval']['name'] = 'rateval';
$this->_sections['rateval']['start'] = (int)1;
$this->_sections['rateval']['loop'] = is_array($_loop=11) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['rateval']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['rateval']['show'] = true;
$this->_sections['rateval']['max'] = $this->_sections['rateval']['loop'];
if ($this->_sections['rateval']['start'] < 0)
    $this->_sections['rateval']['start'] = max($this->_sections['rateval']['step'] > 0 ? 0 : -1, $this->_sections['rateval']['loop'] + $this->_sections['rateval']['start']);
else
    $this->_sections['rateval']['start'] = min($this->_sections['rateval']['start'], $this->_sections['rateval']['step'] > 0 ? $this->_sections['rateval']['loop'] : $this->_sections['rateval']['loop']-1);
if ($this->_sections['rateval']['show']) {
    $this->_sections['rateval']['total'] = min(ceil(($this->_sections['rateval']['step'] > 0 ? $this->_sections['rateval']['loop'] - $this->_sections['rateval']['start'] : $this->_sections['rateval']['start']+1)/abs($this->_sections['rateval']['step'])), $this->_sections['rateval']['max']);
    if ($this->_sections['rateval']['total'] == 0)
        $this->_sections['rateval']['show'] = false;
} else
    $this->_sections['rateval']['total'] = 0;
if ($this->_sections['rateval']['show']):

            for ($this->_sections['rateval']['index'] = $this->_sections['rateval']['start'], $this->_sections['rateval']['iteration'] = 1;
                 $this->_sections['rateval']['iteration'] <= $this->_sections['rateval']['total'];
                 $this->_sections['rateval']['index'] += $this->_sections['rateval']['step'], $this->_sections['rateval']['iteration']++):
$this->_sections['rateval']['rownum'] = $this->_sections['rateval']['iteration'];
$this->_sections['rateval']['index_prev'] = $this->_sections['rateval']['index'] - $this->_sections['rateval']['step'];
$this->_sections['rateval']['index_next'] = $this->_sections['rateval']['index'] + $this->_sections['rateval']['step'];
$this->_sections['rateval']['first']      = ($this->_sections['rateval']['iteration'] == 1);
$this->_sections['rateval']['last']       = ($this->_sections['rateval']['iteration'] == $this->_sections['rateval']['total']);
?>
                                    <input type="radio" id="picrate_<?php echo $this->_sections['rateval']['index']; ?>
" name="picrate" value="<?php echo $this->_sections['rateval']['index']; ?>
" onclick="document.forms[0].submit()" />
                                    <label for="picrate_<?php echo $this->_sections['rateval']['index']; ?>
"><?php echo $this->_sections['rateval']['index']; ?>
</label>&nbsp;
                                <?php endfor; endif; ?>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            </div>
            <?php endif; ?>
                    </div>

                <div style="float: left;">
            <div style="float: left; padding: 30px 0 0 50px; width: 59px; vertical-align: middle;">
                <img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_bal_nyil_kicsi.gif" alt="" onmouseover="scroll(2,document.getElementById('kiskeptar').style.left,<?php echo $this->_tpl_vars['osszkepszel']; ?>
);" onmouseout="stopScroll();" />
            </div>
            <div class="kiskepek" id="kepdiv" style="text-align: center; width: 400px; height: 70px; padding-top: 4px;">
                <table id="kiskeptar" style="position: absolute; left: 0px; text-align: center;">
                    <tr>
                    <?php $_from = $this->_tpl_vars['kiskepek']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
                        <td>
                            <a onclick="pic_change('<?php echo $this->_tpl_vars['data']['pid']; ?>
','<?php echo $this->_tpl_vars['data']['gid']; ?>
')" title="">
                                <img style="padding-right: 2px;" src="files/gallery/tn_<?php echo $this->_tpl_vars['data']['name']; ?>
" alt="" />
                            </a>
                        </td>
                    <?php endforeach; endif; unset($_from); ?>
                    </tr>
                </table>
            </div>
            <div style="float: right; padding: 30px 50px 0 0; width: 59px; vertical-align: middle;">
                <img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/galeria_jobb_nyil_kicsi.gif" alt="" onmouseover="scroll(1,document.getElementById('kiskeptar').style.left,<?php echo $this->_tpl_vars['osszkepszel']; ?>
);" onmouseout="stopScroll();" />
            </div>
        </div>
            </div>

        <div class="footer"></div>
    </div>
</body>

</html>