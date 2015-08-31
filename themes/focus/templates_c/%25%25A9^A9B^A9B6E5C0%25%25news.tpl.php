<?php /* Smarty version 2.6.16, created on 2008-11-24 12:40:31
         compiled from news.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'news.tpl', 3, false),)), $this); ?>
<div id="cnt">
	<div id="cnt_top">
		<span style="padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['news_title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</span>

				<?php if ($this->_tpl_vars['news_taglist']): ?>
			<br />
			<?php $_from = $this->_tpl_vars['news_taglist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['tags']):
?>
				<a href="<?php echo $this->_tpl_vars['key']; ?>
" title="<?php echo $this->_tpl_vars['tags']; ?>
"><?php echo $this->_tpl_vars['tags']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		
        		<?php if ($_SESSION['site_cnt_is_viewcounter']): ?>
			<br /><span class="mainnews_date" style="font-size: 9px;">(<?php echo $this->_tpl_vars['news_counter']; ?>
 <?php echo $this->_tpl_vars['locale']['index_news']['field_main_viewcounter']; ?>
)</span>
		<?php endif; ?>
        	</div>

    	<?php if ($_SESSION['site_cnt_is_rating_news']): ?>
	<div>
		<b><?php echo $this->_tpl_vars['cntrate']; ?>
</b> <?php echo $this->_tpl_vars['locale']['index_news']['field_main_numrating']; ?>
 <?php echo $this->_tpl_vars['locale']['index_news']['field_main_avgrating']; ?>
<b><?php if ($this->_tpl_vars['avgrate']):  echo $this->_tpl_vars['avgrate'];  else: ?> 0<?php endif; ?></b>
			<?php if ($this->_tpl_vars['usrrate']): ?>
				<br /><?php echo $this->_tpl_vars['locale']['index_news']['field_main_yourrating']; ?>
: <b><?php echo $this->_tpl_vars['usrrate']; ?>
</b>
			<?php else: ?>
				<?php $this->assign('cid', $_GET['cid']); ?>
				<?php if (! $this->_tpl_vars['rated_news'][$this->_tpl_vars['cid']]): ?>
					<br />
					<form method="post" action="index.php?p=<?php echo $this->_tpl_vars['self_news']; ?>
&amp;act=show&amp;cid=<?php echo $this->_tpl_vars['cid']; ?>
" name="newsrate" style="margin: 0;">
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
							<input type="radio" id="newsrate_<?php echo $this->_sections['rateval']['index']; ?>
" name="newsrate" value="<?php echo $this->_sections['rateval']['index']; ?>
" onclick="document.forms['newsrate'].submit()" />
							<label for="newsrate_<?php echo $this->_sections['rateval']['index']; ?>
"><?php echo $this->_sections['rateval']['index']; ?>
</label>&nbsp;
						<?php endfor; endif; ?>
					</form>
				<?php endif; ?>
			<?php endif; ?>
		</td>
	</div>
	<?php endif; ?>
    
    	<div id="cnt_cnt" style="padding-left: 10px;">
		<?php if ($this->_tpl_vars['news_cpic'] != "" && ( ( $this->_tpl_vars['news_main'] == 1 && $_SESSION['site_leadpic'] == 1 ) || ( $this->_tpl_vars['news_main'] == 0 && $_SESSION['site_newspic'] == 1 ) )): ?>
		<div style="clear: both; float: left; padding-top: 10px;">
				<img src="<?php echo $_SESSION['site_cnt_picdir']; ?>
/<?php echo $this->_tpl_vars['news_cpic']; ?>
" border="0" alt="<?php echo $this->_tpl_vars['news_title']; ?>
">
		</div>
		<?php endif; ?>
		<div style="float: left; padding-left: 10px; padding-top: 10px; width: 650px;"><?php echo $this->_tpl_vars['news_lead']; ?>
</div>
		<div class="cnt_text" style="padding-left: 0; padding-top: 10px; clear: both;"><?php echo $this->_tpl_vars['news_content']; ?>
</div>
		<div class="cnt_text" style="padding-left: 0; padding-top: 10px; font-size: 9px;">
			<b><?php echo $this->_tpl_vars['locale']['index_news']['field_main_adduser']; ?>
: </b><?php echo $this->_tpl_vars['news_addname']; ?>
, <?php echo $this->_tpl_vars['news_adddate']; ?>
<br />
			<b><?php echo $this->_tpl_vars['locale']['index_news']['field_main_lastmod']; ?>
: </b><?php echo $this->_tpl_vars['news_modname']; ?>
, <?php echo $this->_tpl_vars['news_moddate']; ?>

		</div>
	</div>
    </div>

<div>
    	<div style="float: left;">
	<?php if ($this->_tpl_vars['prev_news']): ?>
		<a href="index.php?p=<?php echo $this->_tpl_vars['self_news']; ?>
&amp;act=show&amp;cid=<?php echo $this->_tpl_vars['prev_news']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_news']['field_main_prev']; ?>
"><?php echo $this->_tpl_vars['locale']['index_news']['field_main_prev']; ?>
</a>
	<?php endif; ?>
	</div>
    
    	<div style="float: right;">
	<?php if ($this->_tpl_vars['next_news']): ?>
		<a href="index.php?p=<?php echo $this->_tpl_vars['self_news']; ?>
&amp;act=show&amp;cid=<?php echo $this->_tpl_vars['next_news']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_news']['field_main_next']; ?>
"><?php echo $this->_tpl_vars['locale']['index_news']['field_main_next']; ?>
</a>
	<?php endif; ?>
	</div>
    </div>
<div style="clear: both;">
    	<a href="index.php?p=<?php echo $this->_tpl_vars['self_news']; ?>
&amp;act=lst" class="mainnews_link" style="padding-left: 15px;" title="<?php echo $this->_tpl_vars['locale']['index_news']['field_main_more']; ?>
">
        <?php echo $this->_tpl_vars['locale']['index_news']['field_main_more']; ?>

    </a>
    
    	<?php if ($this->_tpl_vars['send_recommend']): ?>
		<a href="<?php echo $this->_tpl_vars['send_recommend']; ?>
" class="mainnews_link" title="<?php echo $this->_tpl_vars['locale']['index_news']['field_main_recommend']; ?>
"><?php echo $this->_tpl_vars['locale']['index_news']['field_main_recommend']; ?>
</a>
	<?php endif; ?>
    </div>

<?php if ($_SESSION['site_cnt_is_comment_news']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "comments.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>