<?php /* Smarty version 2.6.16, created on 2007-06-28 14:43:19
         compiled from admin/stat_total_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/stat_total_list.tpl', 67, false),)), $this); ?>
<div id="table">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/dynamic_tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="t_content">
		<div class="t_filter"></div>
		<div class="pager"></div>
		<table>
			<tr>
				<th class="first" colspan="6"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_access']; ?>
</th>
			</tr>
			<tr class="row1">
				<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_id']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_client']; ?>
</td>
				<td style="border: 1px solid;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_total']; ?>
</td>
				<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_actmonth']; ?>
</td>
			</tr>
			<tr class="row2">
				<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['client_id']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['client']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['visits_total']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_visits']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['pi_total']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_impressions']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['visits_month']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_visits']; ?>
</td>
				<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['pi_month']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_impressions']; ?>
</td>
			</tr>
		</table><br /><br />
		<?php if ($_SESSION['site_stat_return_visitor']): ?>
			<table>
				<tr>
					<th class="first" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_visitors']; ?>
</th>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid; width: 40%;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_returning_unique']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['num_unique_visitors']; ?>
</td>
				</tr>
				<tr class="row2">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_returning_onetime']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['num_one_time_visitors']; ?>
</td>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_returning_visitors']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['num_returning_visitors']; ?>
</td>
				</tr>
				<tr class="row2">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_return_visits']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['num_return_visits']; ?>
</td>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_returning_avg_visits']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['average_visits']; ?>
</td>
				</tr>
				<tr class="row2">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_returning_time']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['average_time_between_visits']; ?>
</td>
				</tr>
			</table><br /><br />
		<?php endif; ?>
		<table>
			<?php if ($this->_tpl_vars['monthly_stat']): ?>
				<tr>
					<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthly']; ?>
</th>
				</tr>
				<tr class="row1">
					<td style="border: 1px solid; width: 33%;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlydate']; ?>
</td>
					<td style="border: 1px solid; width: 33%;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlyimp']; ?>
</td>
					<td style="border: 1px solid; width: 33%;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlyvisitor']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['monthly_stat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['data']['link']; ?>
</td>
					<td style="border: 1px solid; width: 16%;"><?php echo $this->_tpl_vars['data']['pi_number']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['pi_percent']; ?>
%</td>
					<td style="border: 1px solid; width: 16%;"><?php echo $this->_tpl_vars['data']['visits_number']; ?>
</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['visits_percent']; ?>
%</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
				<?php if ($this->_tpl_vars['graph_link_monthly']): ?>
					<tr><td style="padding-top: 10px; text-align: center;" colspan="5"><img src="<?php echo $this->_tpl_vars['graph_link_monthly']; ?>
" /></td></tr>
				<?php endif; ?>
			<?php elseif ($this->_tpl_vars['dayly_stat']): ?>
				<tr>
					<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_dayly']; ?>
 - <?php echo $this->_tpl_vars['year']; ?>
. <?php echo $this->_tpl_vars['month']; ?>
</th>
				</tr>
				<?php if ($this->_tpl_vars['graph_link_dayly']): ?>
					<tr><td style="padding-top: 10px; text-align: center;" colspan="5"><img src="<?php echo $this->_tpl_vars['graph_link_dayly']; ?>
" /></td></tr>
				<?php endif; ?>
				<tr class="row1">
					<td style="border: 1px solid; width: 33%;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlydate']; ?>
</td>
					<td style="border: 1px solid; width: 33%;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlyimp']; ?>
</td>
					<td style="border: 1px solid; width: 33%;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlyvisitor']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['dayly_stat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['data']['link']; ?>
</td>
					<td style="border: 1px solid; width: 16%;"><?php echo $this->_tpl_vars['data']['pi_number']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['pi_percent']; ?>
%</td>
					<td style="border: 1px solid; width: 16%;"><?php echo $this->_tpl_vars['data']['visits_number']; ?>
</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['visits_percent']; ?>
%</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			<?php elseif ($this->_tpl_vars['hourly_stat']): ?>
				<tr>
					<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hourly']; ?>
 - <?php echo $this->_tpl_vars['year']; ?>
. <?php echo $this->_tpl_vars['month']; ?>
 <?php echo $this->_tpl_vars['day']; ?>
.</th>
				</tr>
				<?php if ($this->_tpl_vars['graph_link_hourly']): ?>
					<tr><td style="padding-top: 10px; text-align: center;" colspan="5"><img src="<?php echo $this->_tpl_vars['graph_link_hourly']; ?>
" /></td></tr>
				<?php endif; ?>
				<tr class="row1">
					<td style="border: 1px solid; width: 33%;" class="first"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlydate']; ?>
</td>
					<td style="border: 1px solid; width: 33%;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlyimp']; ?>
</td>
					<td style="border: 1px solid; width: 33%;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_monthlyvisitor']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['hourly_stat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['data']['hour']; ?>
</td>
					<td style="border: 1px solid; width: 16%;"><?php echo $this->_tpl_vars['data']['pi_number']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['pi_percent']; ?>
%</td>
					<td style="border: 1px solid; width: 16%;"><?php echo $this->_tpl_vars['data']['visits_number']; ?>
</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['visits_percent']; ?>
%</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_totalpages']; ?>
</th>
			</tr>
			<?php if ($_SESSION['site_stat_is_graph']): ?>
				<?php if ($this->_tpl_vars['top_total_pages']['0']['count'] > 0): ?>
					<tr><td style="padding-top: 10px; text-align: center;">
					<?php if (! $_GET['statpage'] || ( $_GET['statpage'] != month && $_GET['statpage'] != day )): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=document&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_year" />
					<?php elseif ($_GET['statpage'] == month): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=document&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_month" />
					<?php elseif ($_GET['statpage'] == day): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=document&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_day" />
					<?php endif; ?>
					</td></tr>
				<?php else: ?>
					<tr>
						<td colspan="4" class="empty">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
							<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

						</td>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
					<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_page']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['top_total_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
					<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
				</tr>
				<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

					</td>
				</tr>
				<?php endif; unset($_from); ?>
			<?php endif; ?>
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_host']; ?>
</th>
			</tr>
			<?php if ($_SESSION['site_stat_is_graph']): ?>
				<?php if ($this->_tpl_vars['top_hosts']['0']['count'] > 0): ?>
					<tr><td style="padding-top: 10px; text-align: center;">
					<?php if (! $_GET['statpage'] || ( $_GET['statpage'] != month && $_GET['statpage'] != day )): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=host&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_year" />
					<?php elseif ($_GET['statpage'] == month): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=host&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_month" />
					<?php elseif ($_GET['statpage'] == day): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=host&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_day" />
					<?php endif; ?>
					</td></tr>
				<?php else: ?>
					<tr>
						<td colspan="4" class="empty">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
							<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

						</td>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
					<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_page']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['top_hosts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
					<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
				</tr>
				<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

					</td>
				</tr>
				<?php endif; unset($_from); ?>
			<?php endif; ?>
		</table>
		<br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_referer']; ?>
</th>
			</tr>
			<?php if ($_SESSION['site_stat_is_graph']): ?>
				<?php if ($this->_tpl_vars['top_referers']['0']['count'] > 0): ?>
					<tr><td style="padding-top: 10px; text-align: center;">
					<?php if (! $_GET['statpage'] || ( $_GET['statpage'] != month && $_GET['statpage'] != day )): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=referer&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_year" />
					<?php elseif ($_GET['statpage'] == month): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=referer&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_month" />
					<?php elseif ($_GET['statpage'] == day): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=referer&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_day" />
					<?php endif; ?>
					</td></tr>
				<?php else: ?>
					<tr>
						<td colspan="4" class="empty">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
							<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

						</td>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
					<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_page']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['top_referers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
					<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
				</tr>
				<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

					</td>
				</tr>
				<?php endif; unset($_from); ?>
			<?php endif; ?>
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_opsyss']; ?>
</th>
			</tr>
			<?php if ($_SESSION['site_stat_is_graph']): ?>
				<?php if ($this->_tpl_vars['top_op_systems']['0']['count'] > 0): ?>
					<tr><td style="padding-top: 10px; text-align: center;">
					<?php if (! $_GET['statpage'] || ( $_GET['statpage'] != month && $_GET['statpage'] != day )): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=operating_system&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_year" />
					<?php elseif ($_GET['statpage'] == month): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=operating_system&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_month" />
					<?php elseif ($_GET['statpage'] == day): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=operating_system&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_day" />
					<?php endif; ?>
					</td></tr>
				<?php else: ?>
					<tr>
						<td colspan="4" class="empty">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
							<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

						</td>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
					<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_opsys']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['top_op_systems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
					<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
				</tr>
				<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

					</td>
				</tr>
				<?php endif; unset($_from); ?>
			<?php endif; ?>
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_browsers']; ?>
</th>
			</tr>
			<?php if ($_SESSION['site_stat_is_graph']): ?>
				<?php if ($this->_tpl_vars['top_user_agents']['0']['count'] > 0): ?>
					<tr><td style="padding-top: 10px; text-align: center;">
					<?php if (! $_GET['statpage'] || ( $_GET['statpage'] != month && $_GET['statpage'] != day )): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=user_agent&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_year" />
					<?php elseif ($_GET['statpage'] == month): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=user_agent&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_month" />
					<?php elseif ($_GET['statpage'] == day): ?>
						<img src="admin/stat_graph.php?what=top&amp;type=user_agent&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_day" />
					<?php endif; ?>
					</td></tr>
				<?php else: ?>
					<tr>
						<td colspan="4" class="empty">
							<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
							<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

						</td>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td style="border: 1px solid; width: 30px;" class="first"></td>
					<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
					<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_browser']; ?>
</td>
				</tr>
				<?php $_from = $this->_tpl_vars['top_user_agents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
					<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
					<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
					<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
					<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
				</tr>
				<?php endforeach; else: ?>
				<tr>
					<td colspan="4" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
						<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

					</td>
				</tr>
				<?php endif; unset($_from); ?>
			<?php endif; ?>
		</table>
		<?php if ($_SESSION['site_stat_country']): ?>
		<br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_countries']; ?>
</th>
			</tr>
			<?php if ($this->_tpl_vars['countries']['0']['count'] > 0): ?>
			<tr>
				<td style="border: 1px solid; width: 30px;" class="first"></td>
				<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_country']; ?>
</td>
				<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_region']; ?>
</td>
			</tr>
			<?php endif; ?>
			<?php $_from = $this->_tpl_vars['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
				<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
				<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
				<td style="border: 1px solid;">
					<img src="<?php echo $this->_tpl_vars['libs_dir']; ?>
/pear/phpOpenTracker/flags/s/<?php echo $this->_tpl_vars['data']['flag']; ?>
" />
					<?php echo $this->_tpl_vars['data']['string']; ?>

				</td>
				<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['region']; ?>
</td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="5" class="empty">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

				</td>
			</tr>
			<?php endif; unset($_from); ?>
			<?php if ($_SESSION['site_stat_is_graph']): ?>
				<tr><td style="padding-top: 10px; text-align: center;" colspan="6">
				<?php if (! $_GET['statpage'] || ( $_GET['statpage'] != month && $_GET['statpage'] != day )): ?>
					<img src="admin/stat_graph.php?what=localizer&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_year" />
				<?php elseif ($_GET['statpage'] == month): ?>
					<img src="admin/stat_graph.php?what=localizer&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_month" />
				<?php elseif ($_GET['statpage'] == day): ?>
					<img src="admin/stat_graph.php?what=localizer&amp;limit=<?php echo $this->_tpl_vars['stat_limit']; ?>
&amp;range=current_day" />
				<?php endif; ?>
				</td></tr>
			<?php endif; ?>
		</table>
		<?php endif; ?>
		<?php if ($_SESSION['site_stat_search']): ?>
		<br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_searchengines']; ?>
</th>
			</tr>
			<?php if ($this->_tpl_vars['search_engines']['0']['count'] > 0): ?>
			<tr>
				<td style="border: 1px solid; width: 30px;" class="first"></td>
				<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
				<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_searchengine']; ?>
</td>
			</tr>
			<?php endif; ?>
			<?php $_from = $this->_tpl_vars['search_engines']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
				<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
				<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
				<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="4" class="empty">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

				</td>
			</tr>
			<?php endif; unset($_from); ?>
		</table><br /><br />
		<table>
			<tr>
				<th class="first" colspan="5"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_top']; ?>
 <?php echo $this->_tpl_vars['stat_limit']; ?>
 <?php echo $this->_tpl_vars['locale']['admin_stat']['field_keywords']; ?>
</th>
			</tr>
			<?php if ($this->_tpl_vars['search_keywords']['0']['count'] > 0): ?>
			<tr>
				<td style="border: 1px solid; width: 30px;" class="first"></td>
				<td style="border: 1px solid; width: 200px;" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_hit']; ?>
</td>
				<td style="border: 1px solid;" class="last" colspan="2"><?php echo $this->_tpl_vars['locale']['admin_stat']['field_keyword']; ?>
</td>
			</tr>
			<?php endif; ?>
			<?php $_from = $this->_tpl_vars['search_keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "row2,row1"), $this);?>
">
				<td style="border: 1px solid;" class="first"><?php echo $this->_tpl_vars['key']+1; ?>
.</td>
				<td style="border: 1px solid; width: 100px;"><?php echo $this->_tpl_vars['data']['count']; ?>
</td>
				<td style="border: 1px solid;"><?php echo $this->_tpl_vars['data']['percent']; ?>
%</td>
				<td style="border: 1px solid;" class="last"><?php echo $this->_tpl_vars['data']['string']; ?>
</td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="4" class="empty">
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>
" />
					<?php echo $this->_tpl_vars['locale']['admin_stat']['warning_no_data']; ?>

				</td>
			</tr>
			<?php endif; unset($_from); ?>
		</table>
		<?php endif; ?>
		<div class="pager"></div>
		<div class="t_empty"></div>
	</div>
	<div id="t_bottom"></div>
</div>