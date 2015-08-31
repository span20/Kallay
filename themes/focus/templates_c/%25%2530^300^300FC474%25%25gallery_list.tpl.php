<?php /* Smarty version 2.6.16, created on 2007-06-21 12:36:34
         compiled from gallery_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pic_count', 'gallery_list.tpl', 15, false),array('modifier', 'htmlspecialchars', 'gallery_list.tpl', 46, false),)), $this); ?>
<?php if (empty ( $_GET['which'] )): ?>
	<?php $this->assign('div_width', '50%');  else: ?>
	<?php $this->assign('div_width', '100%');  endif; ?>

<?php if ($this->_tpl_vars['picgals']): ?>
<!-- Kep galeriak listaja -->
<div style="width: <?php echo $this->_tpl_vars['div_width']; ?>
; display: table;" class="fl">
    <?php $_from = $this->_tpl_vars['picgals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gallery']):
?>
    <div style="padding: 10px;">
        <a class="cim" <?php echo $this->_tpl_vars['gallery_link1'];  echo $this->_tpl_vars['gallery']['gallery_id'];  echo $this->_tpl_vars['gallery_link2']; ?>
 title="<?php echo $this->_tpl_vars['gallery']['name']; ?>
"><?php echo $this->_tpl_vars['gallery']['name']; ?>
</a><br />
        <?php echo $this->_tpl_vars['gallery']['description']; ?>
<br />
        ( <?php echo picCount(array('gallery_id' => $this->_tpl_vars['gallery']['gallery_id']), $this);?>
 <?php echo $this->_tpl_vars['locale']['index_gallery']['field_piccount']; ?>
 )<br />
        <a class="cim" <?php echo $this->_tpl_vars['gallery_link1'];  echo $this->_tpl_vars['gallery']['gallery_id'];  echo $this->_tpl_vars['gallery_link2']; ?>
 title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_view']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_view']; ?>
 &raquo;</a><br />
        <?php if (! empty ( $this->_tpl_vars['gallery']['winners'] )): ?>
            <a class="cim" href="javascript:void(0);" onClick="show_hide('gal_win_<?php echo $this->_tpl_vars['gallery']['gallery_id']; ?>
');"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_top'];  echo $_SESSION['site_gallerytopnum']; ?>
 &raquo;</a><br />
            <div id="gal_win_<?php echo $this->_tpl_vars['gallery']['gallery_id']; ?>
" style="display: none;">
                <table cellspacing="0" cellpadding="3" border="0" align="center">
                    <tr>
                        <td><strong><?php echo $this->_tpl_vars['locale']['index_gallery']['field_title']; ?>
</strong></td><td><strong><?php echo $this->_tpl_vars['locale']['index_gallery']['field_result']; ?>
</strong></td>
                    </tr>
                    <?php $_from = $this->_tpl_vars['gallery']['winners']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nyertes'] => $this->_tpl_vars['pontszam']):
?>
                    <tr>
                        <td><?php echo $this->_tpl_vars['nyertes']; ?>
</td><td align="right"><?php echo $this->_tpl_vars['pontszam']; ?>
</td>
                    </tr>
                    <?php endforeach; endif; unset($_from); ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
	<?php endforeach; else: ?>
        <?php echo $this->_tpl_vars['locale']['index_gallery']['warning_empty_gallery']; ?>

	<?php endif; unset($_from); ?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['vidgals'] && $_SESSION['site_gallery_is_video']): ?>
<div style="width: <?php echo $this->_tpl_vars['div_width']; ?>
; display: table;" class="fl">
    <?php $_from = $this->_tpl_vars['vidgals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['vids'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['vids']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['vids']):
        $this->_foreach['vids']['iteration']++;
?>
    <div style="padding: 10px;">
        <a class="cim" href="javascript:void(0);"><?php echo $this->_tpl_vars['vids']['name']; ?>
</a><br />
        <?php echo ((is_array($_tmp=$this->_tpl_vars['vids']['description'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
<br />
        ( <?php echo picCount(array('gallery_id' => $this->_tpl_vars['vids']['gallery_id']), $this);?>
 <?php echo $this->_tpl_vars['locale']['index_gallery']['field_videocount']; ?>
 )<br />
        <a class="cim" href="javascript:;" onclick="show_vids('<?php echo $this->_foreach['vids']['total']; ?>
','<?php echo $this->_foreach['vids']['iteration']; ?>
');" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_videos']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_videos']; ?>
 &raquo;</a><br />
        <div id="vids_<?php echo $this->_foreach['vids']['iteration']; ?>
" style="display: table; display: none;">
            <?php $_from = $this->_tpl_vars['vids']['vids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vids2']):
?>
            <div class="" style="height: 15px;">
                <div style="float: left; clear: both; padding-left: 10px;"><?php echo $this->_tpl_vars['vids2']['vid_name']; ?>
</div>
                <div style="float: right; padding-left: 10px;">
                    <a href="index.php?p=gallery&act=gallery_view&vid=<?php echo $this->_tpl_vars['vids2']['vid_id']; ?>
&gid=<?php echo $this->_tpl_vars['vids']['gallery_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_gallery']['field_watch']; ?>
"><?php echo $this->_tpl_vars['locale']['index_gallery']['field_watch']; ?>
 &raquo;</a>
                </div>
            </div>
            <?php endforeach; else: ?>
                <?php echo $this->_tpl_vars['locale']['index_gallery']['warning_empty_videos']; ?>

            <?php endif; unset($_from); ?>
        </div>
    </div>
    <?php endforeach; else: ?>
        <?php echo $this->_tpl_vars['locale']['index_gallery']['warning_empty_videos']; ?>

    <?php endif; unset($_from); ?>
</div>
<?php endif; ?>