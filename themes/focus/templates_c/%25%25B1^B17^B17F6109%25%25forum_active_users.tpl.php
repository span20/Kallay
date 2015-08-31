<?php /* Smarty version 2.6.16, created on 2007-07-12 11:47:11
         compiled from forum_active_users.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'forum_active_users.tpl', 12, false),)), $this); ?>
<div class="centered">
    <h2><?php echo $this->_tpl_vars['lang_forum']['strForumActiveUsers']; ?>
</h2>
    <p class="page_list">
    <?php echo $this->_tpl_vars['forum_userlinks']; ?>

    </p>
    <table class="content_table">
        <tr>
            <th><?php echo $this->_tpl_vars['lang_forum']['strForumActiveUsersName']; ?>
</th>
            <th><?php echo $this->_tpl_vars['lang_forum']['strForumActiveUsersMessageCount']; ?>
</th>
        </tr>
        <?php $_from = $this->_tpl_vars['forum_userpages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr class="<?php echo smarty_function_cycle(array('values' => "tr_odd,tr_twin"), $this);?>
 row">
            <td><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['data']['message_count']; ?>
</td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="2">Nincsenek hozzászólások</td>
        </tr>
        <?php endif; unset($_from); ?>
    </table>
</div>