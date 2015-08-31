<?php /* Smarty version 2.6.16, created on 2007-07-24 10:35:13
         compiled from forum_censored_words.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'forum_censored_words.tpl', 31, false),array('modifier', 'htmlspecialchars', 'forum_censored_words.tpl', 32, false),)), $this); ?>
<div class="centered">
<h2><?php echo $this->_tpl_vars['lang_forum']['strForumCensorHeader']; ?>
</h2>
<?php if ($this->_tpl_vars['censoradd_right']): ?>
<div class="content_menu">
	<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=censoradd" title="<?php echo $this->_tpl_vars['lang_forum']['strForumCensorAdd']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumCensorAdd']; ?>
</a>
</div>
<?php endif; ?>
<p><?php echo $this->_tpl_vars['lang_forum']['strForumCensorTotal']; ?>
: <?php echo $this->_tpl_vars['censor_total']; ?>
</p>
<p class="page_list"><?php echo $this->_tpl_vars['pl_forum']; ?>
</p>
<?php if ($this->_tpl_vars['censordel_right']): ?>
<script type="text/javascript">//<![CDATA[<?php echo '
	function torol(cid)
	{ '; ?>

		x = confirm('<?php echo $this->_tpl_vars['lang_forum']['strForumCensorDeleteConfirm']; ?>
'); <?php echo '       
		if (x) { '; ?>

			document.location.href='index.php?<?php echo $this->_tpl_vars['self']; ?>
&act=censordel&cid='+cid <?php echo '
		}
	}
//]]>'; ?>

</script>
<?php endif; ?>
<table class="content_table">
	<thead>
	<tr>
	    <th class="table_subject"><?php echo $this->_tpl_vars['lang_forum']['strForumCensorWord']; ?>
</th>
        <th class="table_date"><?php echo $this->_tpl_vars['lang_forum']['strForumCensorActions']; ?>
</th>
	</tr>
	</thead>
	<tbody>
	<?php $_from = $this->_tpl_vars['pd_forum']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
	<tr class="<?php echo smarty_function_cycle(array('values' => "tr_odd,tr_twin"), $this);?>
 row">
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['word'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</td>
		<td><?php if ($this->_tpl_vars['censormod_right']): ?>[<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
&amp;act=censormod&amp;cid=<?php echo $this->_tpl_vars['data']['cens_id']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumCensorMod']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumCensorMod']; ?>
</a>]<?php endif; ?>
			<?php if ($this->_tpl_vars['censordel_right']): ?>[<a href="javascript: torol(<?php echo $this->_tpl_vars['data']['cens_id']; ?>
);" title="<?php echo $this->_tpl_vars['lang_forum']['strForumCensorDel']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumCensorDel']; ?>
</a>]<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="6" class="error"><?php echo $this->_tpl_vars['lang_forum']['strForumCensorEmptyList']; ?>
</td></tr>
	<?php endif; unset($_from); ?>
	</tbody>
</table>
<a href="index.php?<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['lang_forum']['strForumBack']; ?>
"><?php echo $this->_tpl_vars['lang_forum']['strForumBack']; ?>
</a>
</div>