<?php /* Smarty version 2.6.16, created on 2007-07-25 16:39:30
         compiled from forum_message_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'forum_message_list.tpl', 27, false),array('function', 'censor', 'forum_message_list.tpl', 73, false),array('function', 'mailto', 'forum_message_list.tpl', 75, false),array('function', 'bbcode', 'forum_message_list.tpl', 79, false),array('modifier', 'htmlspecialchars', 'forum_message_list.tpl', 41, false),)), $this); ?>
<div class="centered">
<h2><?php echo $this->_tpl_vars['lang_forum']['strForumHeader']; ?>
</h2>
<div class="topic_message">
	<h3 class="topic_title">
		<span><?php echo $this->_tpl_vars['lang_forum']['strForumTopicName']; ?>
:</span> <?php echo $this->_tpl_vars['topic_name']; ?>

	</h3>
	<div class="message">
	<?php echo $this->_tpl_vars['topic_subject']; ?>

	</div>
	<?php if ($this->_tpl_vars['rate_right']): ?>
    <p>
    <form<?php echo $this->_tpl_vars['form_forum']['attributes']; ?>
>
    <?php echo $this->_tpl_vars['form_forum']['hidden']; ?>

    <?php $_from = $this->_tpl_vars['form_forum']['sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['sec']):
?>
	   <?php $_from = $this->_tpl_vars['sec']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
		<?php if ($this->_tpl_vars['element']['type'] == 'submit' || $this->_tpl_vars['element']['type'] == 'reset'): ?>
			<?php if (! $this->_tpl_vars['form_forum']['frozen']): ?>
			 <?php echo $this->_tpl_vars['element']['html']; ?>

			<?php endif; ?>
		<?php else: ?>
			<?php if ($this->_tpl_vars['element']['required']): ?><span class="required">*</span><?php endif;  echo $this->_tpl_vars['element']['label']; ?>
<br />
			<?php if ($this->_tpl_vars['element']['error']): ?><span class="error"><?php echo $this->_tpl_vars['element']['error']; ?>
</span><br /><?php endif; ?>
			<?php if ($this->_tpl_vars['element']['type'] == 'group'): ?>
				<?php $_from = $this->_tpl_vars['element']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gkey'] => $this->_tpl_vars['gitem']):
?>
					<?php echo $this->_tpl_vars['gitem']['label']; ?>

					<?php echo $this->_tpl_vars['gitem']['html'];  if ($this->_tpl_vars['gitem']['required']): ?><span class="required">*</span><?php endif; ?>
					<?php if ($this->_tpl_vars['element']['separator']):  echo smarty_function_cycle(array('values' => $this->_tpl_vars['element']['separator']), $this); endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			<?php else: ?>
				<?php echo $this->_tpl_vars['element']['html']; ?>

			<?php endif; ?>
			<br />
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
  <?php endforeach; endif; unset($_from); ?>
  </form>    
  </p>
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
	<p class="centered"><?php echo $this->_tpl_vars['lang_forum']['strForumTopicMessageCount']; ?>
: <?php echo $this->_tpl_vars['total']; ?>
</p>
</div>
<?php if ($this->_tpl_vars['is_addright']): ?>
<div class="content_menu">
	<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;tid=<?php echo $this->_tpl_vars['tid']; ?>
&amp;act=addmsg" title="<?php echo $this->_tpl_vars['lang_forum']['strForumNewMessage']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumNewMessage']; ?>
</a>
</div>
<?php endif; ?>
<p><a class="back" href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumBack']; ?>
</a></p>
<p class="page_list"><?php echo $this->_tpl_vars['pl_forum']; ?>
</p>

<script type="text/javascript">//<![CDATA[<?php if ($this->_tpl_vars['del_right']):  echo '
	function torol(msgid)
	{ '; ?>

		x = confirm('<?php echo $this->_tpl_vars['lang_forum']['strForumMessageDeleteConfirm']; ?>
'); <?php echo '       
		if (x) { '; ?>

			document.location.href='index.php?<?php echo $this->_tpl_vars['self']; ?>
&parent=<?php echo $this->_tpl_vars['parent']; ?>
&tid=<?php echo $this->_tpl_vars['tid']; ?>
&act=delmsg&msgid='+msgid <?php echo '
		}
	} ';  endif;  echo '
	function picClick(obj, width, height) {
	    w = window.open(obj.href, \'picwindow\', "status=0,toolbar=0,resizable=1,menubar=0,location=0,height="+(height+20)+",width="+(width+20));
	    w.moveTo(window.screenX+((window.outerWidth-width)/2), window.screenY+((window.outerHeight-height)/2));
	}
//]]>'; ?>

</script>

<?php $_from = $this->_tpl_vars['pd_forum']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
	<div class="topic_message">
		<div class="message_header">
			<h3>
			<?php echo censor(array('text' => $this->_tpl_vars['data']['subject']), $this);?>

			</h3>
			<span class="user_data"><?php echo $this->_tpl_vars['data']['user_name'];  if ($this->_tpl_vars['data']['user_email'] != ""): ?> &lt;<?php echo smarty_function_mailto(array('address' => $this->_tpl_vars['data']['user_email']), $this);?>
&gt;<?php endif; ?></span>
			<span class="time"><?php echo $this->_tpl_vars['data']['add_date']; ?>
</span>
		</div>
		<div class="message">
		<?php echo get_bbcode(array('text' => $this->_tpl_vars['data']['message']), $this);?>

		<?php if (! empty ( $this->_tpl_vars['data']['pics'] )): ?>
		  <div class="message_pics">
		  <?php $_from = $this->_tpl_vars['data']['pics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pic']):
?>
		      <a href="<?php echo $this->_tpl_vars['forum_files_dir']; ?>
/<?php echo $this->_tpl_vars['pic']['realname']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['pic']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" onclick="picClick(this, <?php echo $this->_tpl_vars['pic']['width']; ?>
,<?php echo $this->_tpl_vars['pic']['height']; ?>
); return false;"><?php echo ((is_array($_tmp=$this->_tpl_vars['pic']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a>
		  <?php endforeach; endif; unset($_from); ?>
		  </div>
		<?php endif; ?>

		<?php if ($this->_tpl_vars['data']['embed']): ?>
		<div class="message_embed">
		  <?php echo $this->_tpl_vars['data']['embed']; ?>

		</div>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['data']['user_sign']): ?>
		  <div class="message_sign"><hr />
		      <?php echo $this->_tpl_vars['data']['user_sign']; ?>

		  </div>
		<?php endif; ?>
		</div>
		<?php if ($this->_tpl_vars['is_addright'] || $this->_tpl_vars['block_right'] || $this->_tpl_vars['mod_right'] || $this->_tpl_vars['del_right']): ?>
		<ul class="message_menu noprint">
			<li>[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=addmsg&amp;tid=<?php echo $this->_tpl_vars['tid']; ?>
&amp;re_id=<?php echo $this->_tpl_vars['data']['mid']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumReply']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumMessageReply']; ?>
</a>]</li>
			<?php if ($this->_tpl_vars['block_right']): ?>
				<?php if ($this->_tpl_vars['data']['is_blocked'] == '1'): ?>
					<li><span class="error"><?php echo $this->_tpl_vars['lang_forum']['strForumMessageBlocked']; ?>
</span></li>
					<li>[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=block&amp;tid=<?php echo $this->_tpl_vars['tid']; ?>
&amp;msgid=<?php echo $this->_tpl_vars['data']['mid']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumMessageUnBlock']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumMessageUnBlock']; ?>
</a>]
				<?php else: ?>	
					<li>[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=block&amp;tid=<?php echo $this->_tpl_vars['tid']; ?>
&amp;msgid=<?php echo $this->_tpl_vars['data']['mid']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumMessageBlock']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumMessageBlock']; ?>
</a>]</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['mod_right']): ?>
				<li>[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
&amp;act=modmsg&amp;tid=<?php echo $this->_tpl_vars['tid']; ?>
&amp;msgid=<?php echo $this->_tpl_vars['data']['mid']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumMessageModify']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumMessageModify']; ?>
</a>]</li>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['del_right']): ?>
				<li>[<a href="javascript: torol(<?php echo $this->_tpl_vars['data']['mid']; ?>
);" title="<?php echo $this->_tpl_vars['lang_forum']['strForumMessageDelete']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumMessageDelete']; ?>
</a>]</li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
	</div>
<?php endforeach; endif; unset($_from); ?>
<p><a class="back" href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;parent=<?php echo $this->_tpl_vars['parent']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumBack']; ?>
</a></p>
</div>