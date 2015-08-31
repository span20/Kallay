<?php /* Smarty version 2.6.16, created on 2007-06-19 16:06:52
         compiled from guestbook_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mailto', 'guestbook_list.tpl', 23, false),array('modifier', 'local_date', 'guestbook_list.tpl', 28, false),array('modifier', 'htmlspecialchars', 'guestbook_list.tpl', 32, false),array('modifier', 'nl2br', 'guestbook_list.tpl', 32, false),)), $this); ?>
<p>
<?php echo $this->_tpl_vars['locale']['index_guestbook']['total']; ?>
 <b><?php echo $this->_tpl_vars['total']; ?>
</b>
</p>
<p style="text-align:center;"><a href="index.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=guestbook_add" title="<?php echo $this->_tpl_vars['locale']['index_guestbook']['act_add']; ?>
"><?php echo $this->_tpl_vars['locale']['index_guestbook']['act_add']; ?>
</a></p>

<table cellpadding="2" cellspacing="1" width="100%">
	<tr>
		<td colspan="3" align="center" class="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</td>
	</tr>
	<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" style="padding-right: 10px;">
				<tr style="height: 19px;font-weight:bold;background:#EFC47A;">
					<td style="padding-left: 15px;" class="red">
						<?php if ($this->_tpl_vars['data']['gname'] != ""): ?>
							<?php echo $this->_tpl_vars['data']['gname']; ?>

						<?php else: ?>
							<?php echo $this->_tpl_vars['data']['username']; ?>

						<?php endif; ?>
						<?php if ($this->_tpl_vars['data']['gemail'] != ""): ?>
							&lt; <?php echo smarty_function_mailto(array('address' => $this->_tpl_vars['data']['gemail']), $this);?>
 &gt;
						<?php else: ?>
							<?php if ($this->_tpl_vars['data']['pmail'] == 1): ?>< <?php echo $this->_tpl_vars['data']['umail']; ?>
 ><?php endif; ?>
						<?php endif; ?>
					</td>
					<td align="right" style="padding-right: 15px;" class="red"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_date'])) ? $this->_run_mod_handler('local_date', true, $_tmp, 'longdatetime') : get_date($_tmp, 'longdatetime')); ?>
</td>
				</tr>
				<tr>
					<td colspan="2" class="mainnews_text" style="border-left: 1px solid #EFC47A;border-bottom: 1px solid #EFC47A;  border-right: 1px solid #EFC47A; padding: 5px 15px 5px 15px;">
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['data']['gmess'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

					</td>
				</tr>
				<?php if ($this->_tpl_vars['data']['gans'] != ""): ?>
					<tr>
						<td colspan="2"  style="border-left: 1px solid #EFC47A; border-bottom: 1px solid #EFC47A; border-right: 1px solid #EFC47A; color: #952E45; font-weight:bold;padding: 0 15px;">
							<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['data']['gans'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

						</td>
					</tr>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['is_enable_link'] != "" || $this->_tpl_vars['is_reply_link'] != "" || $this->_tpl_vars['is_delete_link'] != ""): ?>
				<tr>
					<td colspan="2" style="background-color:#efc47a; border: 1px solid #EFC47A; height: 19px; padding: 0 15px;">
						<?php if ($this->_tpl_vars['is_enable_link'] != ""): ?>
							<a href="<?php echo $this->_tpl_vars['is_enable_link'];  echo $this->_tpl_vars['data']['gid']; ?>
" title="
							<?php if ($this->_tpl_vars['data']['gena'] == 1): ?>
								<?php echo $this->_tpl_vars['locale']['index_guestbook']['act_deny']; ?>
"><?php echo $this->_tpl_vars['locale']['index_guestbook']['act_deny']; ?>

							<?php else: ?>
								<?php echo $this->_tpl_vars['locale']['index_guestbook']['act_allow']; ?>
"><?php echo $this->_tpl_vars['locale']['index_guestbook']['act_allow']; ?>

							<?php endif; ?>
							</a>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['is_reply_link'] != ""): ?>
							&nbsp;<a href="<?php echo $this->_tpl_vars['is_reply_link'];  echo $this->_tpl_vars['data']['gid']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_guestbook']['act_reply']; ?>
"><?php echo $this->_tpl_vars['locale']['index_guestbook']['act_reply']; ?>
</a>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['is_delete_link'] != ""): ?>
							&nbsp;<a href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['index_guestbook']['confirm_del']; ?>
')) document.location.href='<?php echo $this->_tpl_vars['is_delete_link'];  echo $this->_tpl_vars['data']['gid']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['index_guestbook']['act_del']; ?>
"><?php echo $this->_tpl_vars['locale']['index_guestbook']['act_del']; ?>
</a>
						<?php endif; ?>
					</td>
				</tr>
				<?php endif; ?>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
<?php endforeach; else: ?>
	<tr><td colspan="3" class="hiba"><?php echo $this->_tpl_vars['locale']['index_guestbook']['warning_no_messages']; ?>
</td></tr>
<?php endif; unset($_from); ?>
</table>