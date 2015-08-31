<?php /* Smarty version 2.6.16, created on 2009-12-02 12:36:40
         compiled from gallery_pic_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'gallery_pic_list.tpl', 9, false),array('function', 'math', 'gallery_pic_list.tpl', 14, false),)), $this); ?>
<div style="height: 265px;">
    <?php $_from = $this->_tpl_vars['pd_gallery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['gal'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gal']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['picture']):
        $this->_foreach['gal']['iteration']++;
?>
        <div style="float: left; <?php if ($this->_foreach['gal']['iteration'] == 2 || $this->_foreach['gal']['iteration'] == 5 || $this->_foreach['gal']['iteration'] == 8): ?>padding-right: 4px; padding-left: 4px;<?php endif; ?> padding-bottom: 5px;">
            <?php if (($this->_foreach['gal']['iteration'] <= 1)): ?>
                <script>
                    loadBigPic('<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['picture']['realname']; ?>
');
                </script>
            <?php endif; ?>
            <a href="javascript:loadBigPic('<?php echo $_SESSION['site_galerydir']; ?>
/<?php echo $this->_tpl_vars['picture']['realname']; ?>
');" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['picture']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><img src="<?php echo $_SESSION['site_galerydir']; ?>
/tn_<?php echo $this->_tpl_vars['picture']['realname']; ?>
" width="<?php echo $this->_tpl_vars['picture']['tn_width']; ?>
" height="<?php echo $this->_tpl_vars['picture']['tn_height']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['picture']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" border="0" /></a><br>
        </div>
    <?php endforeach; endif; unset($_from); ?>
</div>
<div style="float: right;">
    <?php echo smarty_function_math(array('equation' => "x * y",'x' => $this->_tpl_vars['section'],'y' => 10,'assign' => 'sec_loop'), $this);?>

    
    <?php echo smarty_function_math(array('equation' => "x + y",'x' => $this->_tpl_vars['sec_loop'],'y' => 1,'assign' => 'loop'), $this);?>

    <?php echo smarty_function_math(array('equation' => "x - y",'x' => $this->_tpl_vars['sec_loop'],'y' => 9,'assign' => 'from'), $this);?>

    <?php if ($_REQUEST['section'] > 1): ?><div style="float: left; padding: 3px 3px 0 0;"><a href="index.php?mid=<?php echo $_REQUEST['mid']; ?>
&page=<?php echo smarty_function_math(array('equation' => "(x * y) - z",'x' => $this->_tpl_vars['section_minus'],'y' => 10,'z' => 10,'assign' => 'npage'), $this); echo $this->_tpl_vars['npage']; ?>
&section=<?php echo $this->_tpl_vars['section_minus']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/pager_arrow_left.png" /></a></div><?php endif; ?>
    <?php unset($this->_sections['pager_foreach']);
$this->_sections['pager_foreach']['start'] = (int)$this->_tpl_vars['from'];
$this->_sections['pager_foreach']['loop'] = is_array($_loop=$this->_tpl_vars['loop']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pager_foreach']['name'] = 'pager_foreach';
$this->_sections['pager_foreach']['show'] = true;
$this->_sections['pager_foreach']['max'] = $this->_sections['pager_foreach']['loop'];
$this->_sections['pager_foreach']['step'] = 1;
if ($this->_sections['pager_foreach']['start'] < 0)
    $this->_sections['pager_foreach']['start'] = max($this->_sections['pager_foreach']['step'] > 0 ? 0 : -1, $this->_sections['pager_foreach']['loop'] + $this->_sections['pager_foreach']['start']);
else
    $this->_sections['pager_foreach']['start'] = min($this->_sections['pager_foreach']['start'], $this->_sections['pager_foreach']['step'] > 0 ? $this->_sections['pager_foreach']['loop'] : $this->_sections['pager_foreach']['loop']-1);
if ($this->_sections['pager_foreach']['show']) {
    $this->_sections['pager_foreach']['total'] = min(ceil(($this->_sections['pager_foreach']['step'] > 0 ? $this->_sections['pager_foreach']['loop'] - $this->_sections['pager_foreach']['start'] : $this->_sections['pager_foreach']['start']+1)/abs($this->_sections['pager_foreach']['step'])), $this->_sections['pager_foreach']['max']);
    if ($this->_sections['pager_foreach']['total'] == 0)
        $this->_sections['pager_foreach']['show'] = false;
} else
    $this->_sections['pager_foreach']['total'] = 0;
if ($this->_sections['pager_foreach']['show']):

            for ($this->_sections['pager_foreach']['index'] = $this->_sections['pager_foreach']['start'], $this->_sections['pager_foreach']['iteration'] = 1;
                 $this->_sections['pager_foreach']['iteration'] <= $this->_sections['pager_foreach']['total'];
                 $this->_sections['pager_foreach']['index'] += $this->_sections['pager_foreach']['step'], $this->_sections['pager_foreach']['iteration']++):
$this->_sections['pager_foreach']['rownum'] = $this->_sections['pager_foreach']['iteration'];
$this->_sections['pager_foreach']['index_prev'] = $this->_sections['pager_foreach']['index'] - $this->_sections['pager_foreach']['step'];
$this->_sections['pager_foreach']['index_next'] = $this->_sections['pager_foreach']['index'] + $this->_sections['pager_foreach']['step'];
$this->_sections['pager_foreach']['first']      = ($this->_sections['pager_foreach']['iteration'] == 1);
$this->_sections['pager_foreach']['last']       = ($this->_sections['pager_foreach']['iteration'] == $this->_sections['pager_foreach']['total']);
?>
        <?php if ($this->_tpl_vars['all_pages'] > $this->_sections['pager_foreach']['index'] - 1): ?>
            <div rel="pager" id="pager_<?php echo $this->_tpl_vars['data']['cid']; ?>
" style="float: left; padding: 0 2px 6px 2px; <?php if (( $_REQUEST['page'] && $_REQUEST['page'] == $this->_sections['pager_foreach']['index'] -1 ) || ( ! $_REQUEST['page'] && $this->_sections['pager_foreach']['first'] )): ?>background: url('<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/pager_arrow.png') no-repeat bottom center;<?php endif; ?>"><a href="index.php?mid=<?php echo $_REQUEST['mid']; ?>
&page=<?php echo smarty_function_math(array('equation' => "x - y",'x' => $this->_sections['pager_foreach']['index'],'y' => 1,'assign' => 'pagenum'), $this); echo $this->_tpl_vars['pagenum']; ?>
&section=<?php echo $this->_tpl_vars['section']; ?>
"><?php echo $this->_sections['pager_foreach']['index']; ?>
</a></div>
        <?php endif; ?>
    <?php endfor; endif; ?>
    <?php if ($this->_tpl_vars['all_sections'] > 1 && $this->_tpl_vars['all_sections'] != $_REQUEST['section']): ?>
        <div style="float: left; padding: 3px 0 0 3px;"><a href="index.php?mid=<?php echo $_REQUEST['mid']; ?>
&page=<?php echo $this->_tpl_vars['sec_loop']; ?>
&section=<?php echo $this->_tpl_vars['section_plus']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/pager_arrow_right.png" /></a></div>
    <?php endif; ?>
</div>