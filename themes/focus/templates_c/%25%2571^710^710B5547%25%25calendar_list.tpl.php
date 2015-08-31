<?php /* Smarty version 2.6.16, created on 2007-06-14 21:28:23
         compiled from admin/calendar_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/calendar_list.tpl', 122, false),)), $this); ?>
<style type="text/css"><!--
	#calendar_cnt <?php echo ' { '; ?>

		width: 757px;
		text-align: center;
	<?php echo ' } '; ?>


	table.calendar <?php echo ' { '; ?>

		border-collapse: collapse;
		width: 200px;
	<?php echo ' } '; ?>


	th.calendar <?php echo ' { '; ?>

		border: 1px solid #688DA8;
		width: 24px;
		height: 24px;
		background-color: #688DA8;
		color: #FFFFFF;
	<?php echo ' } '; ?>


	td.nodays <?php echo ' { '; ?>

		border: 1px solid;
	<?php echo ' } '; ?>


	td.event <?php echo ' { '; ?>

		border: 1px solid;
		background-color: #E6EDF2;
		color: #688DA8;
		font-weight: bold;
	<?php echo ' } '; ?>

//-->
</style>

<div id="table">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<div class="t_content">
		<div class="t_filter">
			<form action="admin.php" method="get">
    			<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['self']; ?>
">
                <input type="hidden" name="act" value="<?php echo $this->_tpl_vars['this_page']; ?>
">
    			<input type="hidden" name="d" value="1">
    			<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_jump']; ?>

    			<input type="text" name="y" value="<?php echo $this->_tpl_vars['fyear']; ?>
" size="4" maxlength="4">
    			<select name="m">
    				<?php $_from = $this->_tpl_vars['month_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    					<option value="<?php echo $this->_tpl_vars['data']['option']; ?>
" <?php echo $this->_tpl_vars['data']['selected']; ?>
><?php echo $this->_tpl_vars['data']['option']; ?>
</option>
    				<?php endforeach; endif; unset($_from); ?>
    			</select>
    			<input type="submit" value="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_ok']; ?>
" class="submit_filter">
			</form>
		</div>
		<div class="pager"><?php echo $this->_tpl_vars['monthName']; ?>
</div>
		<div id="calendar_cnt">
			<div style="float: left; padding: 70px 0 0 150px; vertical-align: middle;">
				<a href="<?php echo $this->_tpl_vars['prevMonth']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_prevmonth']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/left.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_prevmonth']; ?>
" /></a>
			</div>
			<div style="float: right; padding: 70px 150px 0 0; vertical-align: middle;">
				<a href="<?php echo $this->_tpl_vars['nextMonth']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_nextmonth']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/right.gif" alt="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_nextmonth']; ?>
" /></a>
			</div>

			<table class="calendar" align="center">
				<tr>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_monday']; ?>
</th>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_tuesday']; ?>
</th>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_wednesday']; ?>
</th>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_thursday']; ?>
</th>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_friday']; ?>
</th>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_saturday']; ?>
</th>
					<th class="calendar"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_short_sunday']; ?>
</th>
				</tr>
				<?php unset($this->_sections['week']);
$this->_sections['week']['name'] = 'week';
$this->_sections['week']['loop'] = is_array($_loop=$this->_tpl_vars['month']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['week']['show'] = true;
$this->_sections['week']['max'] = $this->_sections['week']['loop'];
$this->_sections['week']['step'] = 1;
$this->_sections['week']['start'] = $this->_sections['week']['step'] > 0 ? 0 : $this->_sections['week']['loop']-1;
if ($this->_sections['week']['show']) {
    $this->_sections['week']['total'] = $this->_sections['week']['loop'];
    if ($this->_sections['week']['total'] == 0)
        $this->_sections['week']['show'] = false;
} else
    $this->_sections['week']['total'] = 0;
if ($this->_sections['week']['show']):

            for ($this->_sections['week']['index'] = $this->_sections['week']['start'], $this->_sections['week']['iteration'] = 1;
                 $this->_sections['week']['iteration'] <= $this->_sections['week']['total'];
                 $this->_sections['week']['index'] += $this->_sections['week']['step'], $this->_sections['week']['iteration']++):
$this->_sections['week']['rownum'] = $this->_sections['week']['iteration'];
$this->_sections['week']['index_prev'] = $this->_sections['week']['index'] - $this->_sections['week']['step'];
$this->_sections['week']['index_next'] = $this->_sections['week']['index'] + $this->_sections['week']['step'];
$this->_sections['week']['first']      = ($this->_sections['week']['iteration'] == 1);
$this->_sections['week']['last']       = ($this->_sections['week']['iteration'] == $this->_sections['week']['total']);
?>
				<tr>
					<?php unset($this->_sections['day']);
$this->_sections['day']['name'] = 'day';
$this->_sections['day']['loop'] = is_array($_loop=$this->_tpl_vars['month'][$this->_sections['week']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['day']['show'] = true;
$this->_sections['day']['max'] = $this->_sections['day']['loop'];
$this->_sections['day']['step'] = 1;
$this->_sections['day']['start'] = $this->_sections['day']['step'] > 0 ? 0 : $this->_sections['day']['loop']-1;
if ($this->_sections['day']['show']) {
    $this->_sections['day']['total'] = $this->_sections['day']['loop'];
    if ($this->_sections['day']['total'] == 0)
        $this->_sections['day']['show'] = false;
} else
    $this->_sections['day']['total'] = 0;
if ($this->_sections['day']['show']):

            for ($this->_sections['day']['index'] = $this->_sections['day']['start'], $this->_sections['day']['iteration'] = 1;
                 $this->_sections['day']['iteration'] <= $this->_sections['day']['total'];
                 $this->_sections['day']['index'] += $this->_sections['day']['step'], $this->_sections['day']['iteration']++):
$this->_sections['day']['rownum'] = $this->_sections['day']['iteration'];
$this->_sections['day']['index_prev'] = $this->_sections['day']['index'] - $this->_sections['day']['step'];
$this->_sections['day']['index_next'] = $this->_sections['day']['index'] + $this->_sections['day']['step'];
$this->_sections['day']['first']      = ($this->_sections['day']['iteration'] == 1);
$this->_sections['day']['last']       = ($this->_sections['day']['iteration'] == $this->_sections['day']['total']);
?>
						<?php if ($this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->isEmpty()): ?>
							<td class="nodays"></td>
						<?php elseif ($this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->isSelected()): ?>
							<td class="event">
								<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=lst&amp;y=<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisYear(); ?>
&amp;m=<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisMonth(); ?>
&amp;d=<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisDay(); ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_events']; ?>
">
									<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisDay(); ?>

								</a>
							</td>
						<?php else: ?>
							<td style="border: 1px solid;" align="center">
								<a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=lst&amp;y=<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisYear(); ?>
&amp;m=<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisMonth(); ?>
&amp;d=<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisDay(); ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_events']; ?>
">
									<?php echo $this->_tpl_vars['month'][$this->_sections['week']['index']][$this->_sections['day']['index']]->thisDay(); ?>

								</a>
							</td>
						<?php endif; ?>
					<?php endfor; endif; ?>
				</tr>
				<?php endfor; endif; ?>
			</table>
		</div>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>

	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_header']; ?>
"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_header']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div class="t_content">
		<div class="t_filter"></div>
		<div class="pager"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_events_title']; ?>
 <?php echo $this->_tpl_vars['today']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_major']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_event']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_timerstart']; ?>
</th>
				<th><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_timerend']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_action']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['today_event']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
				<td class="first"><?php echo $this->_tpl_vars['data']['is_major']; ?>
</td><td><?php echo $this->_tpl_vars['data']['title']; ?>
</td><td><?php echo $this->_tpl_vars['data']['start_date']; ?>
</td><td><?php echo $this->_tpl_vars['data']['end_date']; ?>
</td>
				<td class="last">
					<a class="action mod" href="admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;act=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=mod&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;y=<?php echo $_GET['y']; ?>
&amp;m=<?php echo $_GET['m']; ?>
&amp;d=<?php echo $_GET['d']; ?>
" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_modify']; ?>
"></a>
					<a class="action del" href="javascript: if (confirm('<?php echo $this->_tpl_vars['locale']['admin_calendar']['confirm_del']; ?>
')) document.location.href='admin.php?p=<?php echo $this->_tpl_vars['self']; ?>
&amp;=<?php echo $this->_tpl_vars['this_page']; ?>
&amp;sub_act=del&amp;cid=<?php echo $this->_tpl_vars['data']['cid']; ?>
&amp;y=<?php echo $_GET['y']; ?>
&amp;m=<?php echo $_GET['m']; ?>
&amp;d=<?php echo $_GET['d']; ?>
';" title="<?php echo $this->_tpl_vars['locale']['admin_calendar']['field_list_delete']; ?>
"></a>
				</td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="5" class="empty">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_calendar']['warning_no_event']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_calendar']['warning_no_event']; ?>

				</td>
			</tr>
			<?php endif; unset($_from); ?>
		</table>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>