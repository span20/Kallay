<?php /* Smarty version 2.6.16, created on 2007-06-08 11:41:44
         compiled from admin/modules_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/modules_list.tpl', 38, false),)), $this); ?>
<div id="table">
	<div id="ear">
		<ul>
			<li id="current"><a href="admin.php?p=modules" title="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesHeader']; ?>
"><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesHeader']; ?>
</a></li>
		</ul>
		<div id="blueleft"></div><div id="blueright"></div>
	</div>
	<div id="t_content">
		<div id="t_filter">
			<form action="admin.php" method="get">
				<input type="hidden" name="p" value="modules">
				<?php echo $this->_tpl_vars['lang_admin']['strAdminOrderBy']; ?>

				<select name="field">
					<option value="1" <?php echo $this->_tpl_vars['fieldselect1']; ?>
><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesName']; ?>
</option>
					<option value="2" <?php echo $this->_tpl_vars['fieldselect2']; ?>
><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesType']; ?>
</option>
					<option value="3" <?php echo $this->_tpl_vars['fieldselect3']; ?>
><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesFile']; ?>
</option>
					<option value="4" <?php echo $this->_tpl_vars['fieldselect4']; ?>
><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesDesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['lang_admin']['strAdminBy']; ?>

				<select name="ord">
					<option value="asc" <?php echo $this->_tpl_vars['ordselect1']; ?>
><?php echo $this->_tpl_vars['lang_admin']['strAdminAsc']; ?>
</option>
					<option value="desc" <?php echo $this->_tpl_vars['ordselect2']; ?>
><?php echo $this->_tpl_vars['lang_admin']['strAdminDesc']; ?>
</option>
				</select>
				<?php echo $this->_tpl_vars['lang_admin']['strAdminOrder']; ?>

				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['lang_admin']['strAdminSubmitFilter']; ?>
" class="submit_filter">
			</form>
		</div>
		<div id="pager"><?php echo $this->_tpl_vars['page_list']; ?>
</div>
		<table>
			<tr>
				<th class="first"><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesName']; ?>
</th>
				<th><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesType']; ?>
</th>
				<th><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesFile']; ?>
</th>
				<th><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesDesc']; ?>
</th>
				<th class="last"><?php echo $this->_tpl_vars['lang_modules']['strAdminModulesAction']; ?>
</th>
			</tr>
			<?php $_from = $this->_tpl_vars['page_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<tr class="<?php echo smarty_function_cycle(array('values' => "row1,row2"), $this);?>
">
					<td class="first"><?php echo $this->_tpl_vars['data']['mname']; ?>
</td><td><?php echo $this->_tpl_vars['data']['mtype']; ?>
</td><td><?php echo $this->_tpl_vars['data']['mfname'];  echo $this->_tpl_vars['data']['mfext']; ?>
</td><td><?php echo $this->_tpl_vars['data']['mdesc']; ?>
</td>
					<td class="last">
						<?php if ($this->_tpl_vars['data']['mactive'] == 1): ?>
							<a href="admin.php?p=modules&amp;act=act&amp;m_id=<?php echo $this->_tpl_vars['data']['mid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesInActivate']; ?>
">
								<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/active.gif" border="0" alt="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesInActivate']; ?>
" />
							</a>
						<?php else: ?>
							<a href="admin.php?p=modules&amp;act=act&amp;m_id=<?php echo $this->_tpl_vars['data']['mid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesActivate']; ?>
">
								<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/inactive.gif" border="0" alt="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesActivate']; ?>
" />
							</a>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['data']['mins'] == 1): ?>
							<a href="admin.php?p=modules&amp;act=ins&amp;m_id=<?php echo $this->_tpl_vars['data']['mid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesUnInstall']; ?>
">
								<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/uninstall.gif" border="0" alt="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesUnInstall']; ?>
" />
							</a>
						<?php else: ?>
							<a href="admin.php?p=modules&amp;act=ins&amp;m_id=<?php echo $this->_tpl_vars['data']['mid']; ?>
&amp;field=<?php echo $_GET['field']; ?>
&amp;ord=<?php echo $_GET['ord']; ?>
&amp;pageID=<?php echo $this->_tpl_vars['page_id']; ?>
" title="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesInstall']; ?>
">
								<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/install.gif" border="0" alt="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesInstall']; ?>
" />
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; else: ?>
				<tr>
					<td colspan="5" class="empty">
						<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/admin/error.gif" border="0" alt="<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesEmptylist']; ?>
" />
						<?php echo $this->_tpl_vars['lang_modules']['strAdminModulesEmptylist']; ?>

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