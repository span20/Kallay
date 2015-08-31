<?php /* Smarty version 2.6.16, created on 2007-06-28 14:43:14
         compiled from admin/rss_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/rss_list.tpl', 27, false),)), $this); ?>
<script type="text/javascript">//<![CDATA[
function torol(rid) <?php echo ' { '; ?>

	x = confirm('<?php echo $this->_tpl_vars['locale']['admin_rss']['confirm_variable']; ?>
');
	if (x) <?php echo ' { '; ?>

		document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&act=del&rid='+rid
	<?php echo ' }
} '; ?>

//]]>
</script>

<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_rss']['title_rss']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_rss']['title_rss']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">&nbsp;</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_rss']['form_name']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_rss']['actions']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['rss_name']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['is_active'] == 1): ?>
								<a class="action act" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;rid=<?php echo $this->_tpl_vars['data']['rss_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_rss']['act_inact']; ?>
"></a>
							<?php else: ?>
								<a class="action inact" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=act&amp;rid=<?php echo $this->_tpl_vars['data']['rss_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_rss']['act_act']; ?>
"></a>
						<?php endif; ?>
						<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=mod&amp;rid=<?php echo $this->_tpl_vars['data']['rss_id']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_rss']['act_mod']; ?>
"></a>
						<a class="action del" href="javascript: torol(<?php echo $this->_tpl_vars['data']['rss_id']; ?>
);" title="<?php echo $this->_tpl_vars['locale']['admin_rss']['act_del']; ?>
"></a>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="3" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_rss']['warning_no_rssreader']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_rss']['warning_no_rssreader']; ?>

					</td>
				</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>