<?php /* Smarty version 2.6.16, created on 2007-12-12 16:22:27
         compiled from contents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'contents.tpl', 60, false),array('modifier', 'nl2br', 'contents.tpl', 78, false),)), $this); ?>
<br />
<?php if (! empty ( $this->_tpl_vars['content_lead'] )): ?><p><strong><?php echo $this->_tpl_vars['content_lead']; ?>
</strong></p><?php endif;  echo $this->_tpl_vars['content_content']; ?>

<div class="cb"></div><br />


<?php if (! empty ( $this->_tpl_vars['cnt_attach_link'] )): ?>
    <h3><?php echo $this->_tpl_vars['locale']['index_contents']['field_main_attached_link']; ?>
</h3>
    <?php $_from = $this->_tpl_vars['cnt_attach_link']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
        <div><a href="<?php echo $this->_tpl_vars['key']; ?>
" title="<?php echo $this->_tpl_vars['data']; ?>
"><?php echo $this->_tpl_vars['data']; ?>
</a></div>
    <?php endforeach; endif; unset($_from);  endif; ?>



<?php if (empty ( $this->_tpl_vars['divFormCnt'] )): ?>
    <?php $this->assign('divFormCnt', $this->_tpl_vars['form_id']);  endif; ?>

<?php if (! $this->_tpl_vars['form_success_msg'] && ! $this->_tpl_vars['form_back_link']): ?>
    <div id="forms_<?php echo $this->_tpl_vars['divFormCnt']; ?>
">
    <form <?php echo $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['form_id']['attributes']; ?>
>

    <?php echo $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['form_id']['hidden']; ?>

    <?php $_from = $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['form_id']['sections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['sec']):
?>
        <h1><?php echo $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['title']; ?>
</h1>
        <p><?php echo $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['lead']; ?>
</p><div class="lezerkard"></div>
        <?php $_from = $this->_tpl_vars['sec']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
        	<?php if ($this->_tpl_vars['element']['type'] == 'submit'): ?>
        		<?php if (! $this->_tpl_vars['form']['frozen']): ?>
        			<?php if (! $this->_tpl_vars['form']['frozen']): ?>
        				<br /><center><?php echo $this->_tpl_vars['element']['html']; ?>
</center>
        			<?php endif; ?>
        		<?php endif; ?>
        	<?php elseif ($this->_tpl_vars['element']['type'] != 'reset'): ?>
        		<div>
        			<strong><?php echo $this->_tpl_vars['element']['label']; ?>
</strong><?php if ($this->_tpl_vars['element']['required']): ?><span style="color: #f00; font-size: 0.8em;">*</span><?php endif; ?><br />
        			<?php if ($this->_tpl_vars['element']['error']): ?><span style="color: #f00;"><?php echo $this->_tpl_vars['element']['error']; ?>
</span><br /><?php endif; ?>
        			<?php if ($this->_tpl_vars['element']['type'] == 'group'): ?>
        				<?php $_from = $this->_tpl_vars['element']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gkey'] => $this->_tpl_vars['gitem']):
?>
        					<?php echo $this->_tpl_vars['gitem']['label']; ?>

        					<?php echo $this->_tpl_vars['gitem']['html'];  if ($this->_tpl_vars['gitem']['required']): ?><font color="red">*</font><?php endif; ?>
        					<?php if ($this->_tpl_vars['element']['separator']):  echo smarty_function_cycle(array('values' => $this->_tpl_vars['element']['separator']), $this); endif; ?>
        				<?php endforeach; endif; unset($_from); ?>
        			<?php else: ?>
        				<?php echo $this->_tpl_vars['element']['html']; ?>

        			<?php endif; ?>
        		</div>
        	<?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>

    <?php if ($this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['form_id']['requirednote'] && ! $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['form_id']['frozen']): ?>
    	<br /><br /><?php echo $this->_tpl_vars['forms'][$this->_tpl_vars['divFormCnt']]['form_id']['requirednote']; ?>

    <?php endif; ?>

    </form>
    </div>
<?php else: ?>
    <p>
      <?php echo ((is_array($_tmp=$this->_tpl_vars['form_success_msg'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

      <br /><a style="font-size: 1em;" href="index.php?<?php echo $this->_tpl_vars['form_back_link']; ?>
" title="<?php echo $this->_tpl_vars['locale']['index_forms']['field_back']; ?>
"><?php echo $this->_tpl_vars['locale']['index_forms']['field_back']; ?>
 ...</a>
    </p>
<?php endif; ?>