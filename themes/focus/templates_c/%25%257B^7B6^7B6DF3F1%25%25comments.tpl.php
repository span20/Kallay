<?php /* Smarty version 2.6.16, created on 2007-06-21 12:37:25
         compiled from comments.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlencode', 'comments.tpl', 2, false),array('modifier', 'nl2br', 'comments.tpl', 18, false),)), $this); ?>
<?php if (( $this->_tpl_vars['is_user_reg'] == 1 && $_SESSION['user_id'] ) || $this->_tpl_vars['is_user_reg'] == 0): ?>
	<input type="button" class="submit" name="submit" onClick="window.location='index.php?p=comments&amp;com_act=comments_add&amp;back_id=<?php echo $this->_tpl_vars['back_id']; ?>
&amp;module=<?php echo $this->_tpl_vars['back_module']; ?>
&amp;link=<?php echo ((is_array($_tmp=$this->_tpl_vars['back_link'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
'" value="<?php echo $this->_tpl_vars['locale']['index_comments']['button_add_comment']; ?>
" /></td>
<?php else: ?>
	<?php echo $this->_tpl_vars['locale']['index_comments']['warning_reg_comment']; ?>

<?php endif; ?>
<div>
	<?php $_from = $this->_tpl_vars['news_comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
	<div>
		<a name="<?php echo $this->_tpl_vars['key']; ?>
"></a>
		<div style="float: left;"><?php echo $this->_tpl_vars['data']['name']; ?>
</div>
			<div style="float: right;">
				<?php if (( $this->_tpl_vars['is_user_reg'] == 1 && $_SESSION['user_id'] ) || $this->_tpl_vars['is_user_reg'] == 0): ?>
					<a href="index.php?p=comments&amp;com_act=comments_add&amp;back_id=<?php echo $this->_tpl_vars['back_id']; ?>
&amp;pre=<?php echo $this->_tpl_vars['key']; ?>
&amp;module=<?php echo $this->_tpl_vars['back_module']; ?>
&amp;link=<?php echo ((is_array($_tmp=$this->_tpl_vars['back_link'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['locale']['index_comments']['field_main_reply']; ?>
"><?php echo $this->_tpl_vars['locale']['index_comments']['field_main_reply']; ?>
</a>&nbsp;
				<?php endif; ?>
				<?php if ($this->_tpl_vars['data']['premise'] && $this->_tpl_vars['data']['premise'] != 0): ?><a href="index.php?p=<?php echo $this->_tpl_vars['back_module'];  echo $this->_tpl_vars['back_link']; ?>
#<?php echo $this->_tpl_vars['data']['premise']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_comments']['field_main_premise']; ?>
"><?php echo $this->_tpl_vars['locale']['index_comments']['field_main_premise']; ?>
</a>&nbsp;<?php endif; ?>
				<?php echo $this->_tpl_vars['data']['add_date']; ?>
 (#<?php echo $this->_tpl_vars['key']; ?>
)
			</div>
			<div style="clear: both;"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['comment'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
			<?php if ($this->_tpl_vars['is_newscomment_modify'] || $this->_tpl_vars['is_newscomment_delete']): ?>
			<div>
				<?php if ($this->_tpl_vars['is_newscomment_modify']): ?>
					<a href="index.php?p=comments&amp;com_act=comments_mod&amp;coid=<?php echo $this->_tpl_vars['key']; ?>
&amp;back_id=<?php echo $this->_tpl_vars['back_id']; ?>
&amp;module=<?php echo $this->_tpl_vars['back_module']; ?>
&amp;link=<?php echo ((is_array($_tmp=$this->_tpl_vars['back_link'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['locale']['index_comments']['title_comment_modify']; ?>
"><?php echo $this->_tpl_vars['locale']['index_comments']['title_comment_modify']; ?>
</a>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['is_newscomment_delete']): ?>
					<a href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['index_comments']['confirm_comment_delete']; ?>
')) document.location.href='index.php?p=comments&amp;com_act=comments_del&amp;coid=<?php echo $this->_tpl_vars['key']; ?>
&amp;module=<?php echo $this->_tpl_vars['back_module']; ?>
&amp;link='+escape('<?php echo $this->_tpl_vars['back_link']; ?>
');" title="<?php echo $this->_tpl_vars['locale']['index_comments']['title_comment_delete']; ?>
"><?php echo $this->_tpl_vars['locale']['index_comments']['title_comment_delete']; ?>
</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	<?php endforeach; else: ?>
		<div style="text-align: center"><?php echo $this->_tpl_vars['locale']['index_comments']['warning_empty_comments']; ?>
</div>
	<?php endif; unset($_from); ?>
</div>