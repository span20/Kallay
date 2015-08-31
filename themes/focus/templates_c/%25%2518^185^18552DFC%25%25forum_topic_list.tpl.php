<?php /* Smarty version 2.6.16, created on 2007-07-25 08:38:37
         compiled from forum_topic_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'forum_topic_list.tpl', 29, false),array('function', 'cycle', 'forum_topic_list.tpl', 43, false),array('function', 'num_messages', 'forum_topic_list.tpl', 60, false),)), $this); ?>
<div class="centered">
<h2><?php echo $this->_tpl_vars['lang_forum']['strForumHeader']; ?>
</h2>
<div class="content_menu">
    <a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=active_users"><?php echo $this->_tpl_vars['lang_forum']['strForumActiveUsers']; ?>
</a>
<?php if ($this->_tpl_vars['is_addright']): ?>
	<?php if ($this->_tpl_vars['parent'] == 0): ?>
    	<?php if ($this->_tpl_vars['censor_right']): ?>
	   | <a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=censor" title="<?php echo $this->_tpl_vars['lang_forum']['strForumCensor']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumCensor']; ?>
</a> 
	   <?php endif; ?>
	   | <a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=fadd" title="<?php echo $this->_tpl_vars['lang_forum']['strForumNewForum']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumAddForum']; ?>
</a>
	<?php else: ?>
	   | <a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=add&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumNewTopic']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumNewTopic']; ?>
</a>
	<?php endif;  endif; ?>
</div>
<p><?php echo $this->_tpl_vars['lang_forum']['strForumTotalItems']; ?>
: <?php echo $this->_tpl_vars['total']; ?>
</p>
<p class="page_list"><?php echo $this->_tpl_vars['pl_forum']; ?>
</p>
<?php if ($this->_tpl_vars['del_right']): ?>
<script type="text/javascript">//<![CDATA[<?php echo '
	function torol()
	{ '; ?>

		return confirm('<?php echo $this->_tpl_vars['lang_forum']['strForumTopicDeleteConfirm']; ?>
'); <?php echo '       
	}
//]]>'; ?>

</script>
<?php endif; ?>
<p>
<?php $_from = $this->_tpl_vars['forum_breadcrumb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['bc_foreach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['bc_foreach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['bc']):
        $this->_foreach['bc_foreach']['iteration']++;
 if (! ($this->_foreach['bc_foreach']['iteration'] <= 1)): ?> - <?php endif; ?><a href="<?php echo $this->_tpl_vars['bc']['link']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['bc']['title'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['bc']['title'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a>
<?php endforeach; endif; unset($_from); ?>
</p>
<table class="content_table">
	<thead>
	<tr>
	    <th class="table_subject"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicName']; ?>
</th>
	    <?php if ($this->_tpl_vars['parent'] > 0): ?><th class="table_count"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicMessageCount']; ?>
</th><?php endif; ?>
		<th class="table_user"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicOwner']; ?>
</th>
		<?php if ($this->_tpl_vars['parent'] > 0): ?><th class="table_date"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicLastMessage']; ?>
</th><?php endif; ?>
	</tr>
	</thead>
	<tbody>
	<?php $_from = $this->_tpl_vars['pd_forum']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
	<tr class="<?php echo smarty_function_cycle(array('values' => "tr_odd,tr_twin"), $this);?>
 row">
		<td><a href="<?php if ($this->_tpl_vars['parent'] == 0): ?>index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['data']['tid'];  else: ?>index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;tid=<?php echo $this->_tpl_vars['data']['tid'];  endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['topic_name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a><br /><i><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['topic_subject'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</i><?php if ($_SESSION['user_id']): ?><br /><?php endif; ?>
			<?php if ($this->_tpl_vars['act_right']): ?>
				<?php if ($this->_tpl_vars['data']['is_active'] == '1'): ?>
					<span class="active"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicActive']; ?>
</span>
				<?php else: ?>
					<span class="inactive"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicInactive']; ?>
</span>
				<?php endif; ?>
		    [<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=act&amp;tid=<?php echo $this->_tpl_vars['data']['tid']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumTopicActivate']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicActivate']; ?>
</a>]
			<?php endif; ?>
			<?php if ($this->_tpl_vars['mod_right']): ?>
			[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=mod&amp;tid=<?php echo $this->_tpl_vars['data']['tid']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumTopicModify']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicModify']; ?>
</a>]
			<?php endif; ?>
			<?php if ($this->_tpl_vars['del_right']): ?>
			[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=<?php if ($this->_tpl_vars['parent'] == 0): ?>f<?php endif; ?>del&amp;tid=<?php echo $this->_tpl_vars['data']['tid']; ?>
" onclick="return torol();" title="<?php echo $this->_tpl_vars['lang_forum']['strForumTopicDelete']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicDelete']; ?>
</a>]
			<?php endif; ?>
		</td>
		<?php if ($this->_tpl_vars['parent'] > 0): ?><td class="centered"><?php echo get_num_messages(array('tid' => $this->_tpl_vars['data']['tid']), $this);?>
</td><?php endif; ?>
		<td class="centered"><?php echo $this->_tpl_vars['data']['add_user_name']; ?>
</td>
		<?php if ($this->_tpl_vars['parent'] > 0): ?><td class="centered"><?php echo $this->_tpl_vars['data']['last_user_name']; ?>
<br />
		<?php echo $this->_tpl_vars['data']['last_message_date']; ?>
</td><?php endif; ?>
	</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="6" class="error"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicEmptyList']; ?>
</td></tr>
	<?php endif; unset($_from); ?>
	</tbody>
</table>
</div>