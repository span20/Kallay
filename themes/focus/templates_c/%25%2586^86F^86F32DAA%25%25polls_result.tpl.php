<?php /* Smarty version 2.6.16, created on 2007-07-26 11:59:25
         compiled from admin/polls_result.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/polls_result.tpl', 34, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="#" title="<?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_title']; ?>
"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_title']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
<!--		<div id="t_filter">&nbsp;</div> -->
		<div class="t_empty"></div>
		<table>
			<tr class="row2">
				<td class="first">
					<dl>
					<?php $_from = $this->_tpl_vars['poll_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
						<dt><b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_timerstart']; ?>
</b></dt><dd><?php echo $this->_tpl_vars['data']['timer_start']; ?>
</dd>
						<dt><b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_timerend']; ?>
</b></dt><dd><?php echo $this->_tpl_vars['data']['timer_end']; ?>
</dd>
						<dt><b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_start']; ?>
</b></dt><dd><?php echo $this->_tpl_vars['data']['start_date']; ?>
</dd>
						<dt><b><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_end']; ?>
</b></dt><dd><?php echo $this->_tpl_vars['data']['end_date']; ?>
</dd>
					<?php endforeach; endif; unset($_from); ?>
					</dl>
				</td>
				<td class="last" style="text-align:right;"><img src="admin/polls_graph.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
" border="0" alt="graf" /></td>
			</tr>
		</table>
<!--		<h2 class="row1" style="text-align:center;"><?php echo $this->_tpl_vars['data']['ptitle']; ?>
</h2> -->
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_answer']; ?>
</th>
				<th align="right"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_polls']; ?>
</th>
				<th class="last" align="right"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_percent']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['poll_text']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['text']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['key']+1; ?>
. <?php echo $this->_tpl_vars['text']['text']; ?>
</td>
					<td align="right"><?php echo $this->_tpl_vars['text']['polldata']; ?>
</td>
					<td class="last" align="right"><?php echo $this->_tpl_vars['text']['percent']; ?>
%</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first" align="right"><?php echo $this->_tpl_vars['locale'][$this->_tpl_vars['self']]['field_result_all']; ?>
</td>
				<td align="right"><?php echo $this->_tpl_vars['poll_num']['polldata']; ?>
</td>
				<td class="last" align="right">100%</td>
			</tr>
		</table>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>