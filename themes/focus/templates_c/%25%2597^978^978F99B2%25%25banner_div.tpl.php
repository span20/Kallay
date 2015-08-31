<?php /* Smarty version 2.6.16, created on 2007-07-04 14:46:40
         compiled from banner_div.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlencode', 'banner_div.tpl', 22, false),array('modifier', 'htmlspecialchars', 'banner_div.tpl', 33, false),array('function', 'redim_banner', 'banner_div.tpl', 22, false),)), $this); ?>
<div class="banners" id="bannerplace_<?php echo $this->_tpl_vars['divBannerCnt']; ?>
">
<?php $_from = $this->_tpl_vars['banners'][$this->_tpl_vars['divBannerCnt']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['bfore'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['bfore']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['banner']):
        $this->_foreach['bfore']['iteration']++;
?>
    <?php $this->assign('bbid', $this->_tpl_vars['banner']['banner_id']); ?>
    <?php $this->assign('bmid', $this->_tpl_vars['banner']['menu_id']); ?>
    <?php $this->assign('bpid', $this->_tpl_vars['banner']['place_id']); ?>
    <?php $this->assign('banner_link', "index.php?p=banners&bid=".($this->_tpl_vars['bbid'])."&mid=".($this->_tpl_vars['bmid'])."&pid=".($this->_tpl_vars['bpid'])); ?>
    <?php if (($this->_foreach['bfore']['iteration'] <= 1)): ?>
    <?php $this->assign('bannerStyle', ' style="z-index:10;"'); ?>
    <?php else: ?>
    <?php $this->assign('bannerStyle', ' style="display:none;"'); ?>
    <?php endif; ?>
    <?php if (! empty ( $this->_tpl_vars['banner']['banner_code'] )): ?>
        <div class="banner"<?php echo $this->_tpl_vars['bannerStyle']; ?>
>
            <div>
            <?php echo $this->_tpl_vars['banner']['banner_code']; ?>

            </div>
        </div>
    <?php elseif ($this->_tpl_vars['banner']['type'] == 13 || $this->_tpl_vars['banner']['type'] == 4): ?>
        <div class="banner"<?php echo $this->_tpl_vars['bannerStyle']; ?>
>
        <div>
		<object type="application/x-shockwave-flash" id="medikemia"
			data="<?php echo $_SESSION['site_bannerdir']; ?>
/<?php echo $this->_tpl_vars['banner']['pic']; ?>
?clickTAG=<?php echo ((is_array($_tmp=$this->_tpl_vars['banner_link'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&amp;clickTARGET=_blank" <?php echo get_banner_dimension(array('width' => $this->_tpl_vars['banner']['width'],'height' => $this->_tpl_vars['banner']['height'],'max_width' => $this->_tpl_vars['banner']['max_width'],'max_height' => $this->_tpl_vars['banner']['max_height']), $this);?>
>
				<param name="movie" value="<?php echo $_SESSION['site_bannerdir']; ?>
/<?php echo $this->_tpl_vars['banner']['pic']; ?>
?clickTAG=<?php echo ((is_array($_tmp=$this->_tpl_vars['banner_link'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&amp;clickTARGET=_blank" />
				<param name="allowScriptAccess" value="sameDomain" />
    			<param name="quality" value="high" />
				<param name="scale" value="Scale" />
				<param name="salign" value="TL" />
				<param name="FlashVars" value="playerMode=embedded" />
		</object>
		</div>
		</div>
	<?php else: ?>
	   <a class="banner" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['banner_link'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['banner']['name']; ?>
" target="_blank"<?php echo $this->_tpl_vars['bannerStyle']; ?>
>
	       <img src="<?php echo $_SESSION['site_bannerdir']; ?>
/<?php echo $this->_tpl_vars['banner']['pic']; ?>
" alt="<?php echo $this->_tpl_vars['banner']['name']; ?>
" <?php echo get_banner_dimension(array('width' => $this->_tpl_vars['banner']['width'],'height' => $this->_tpl_vars['banner']['height'],'max_width' => $this->_tpl_vars['banner']['max_width'],'max_height' => $this->_tpl_vars['banner']['max_height']), $this);?>
 />
	   </a>
	<?php endif;  endforeach; endif; unset($_from); ?>
</div>