<?php /* Smarty version 2.6.16, created on 2010-11-15 23:12:09
         compiled from admin/newsletters/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'admin/newsletters/default.tpl', 59, false),array('block', 'textformat', 'admin/newsletters/default.tpl', 64, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
<title><?php echo $this->_tpl_vars['mail_title']; ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['mail_charset']; ?>
" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=<?php echo $this->_tpl_vars['mail_charset']; ?>
" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<style type="text/css"><!--
<?php echo '
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	margin: 0;
	padding: 0;
	text-align: left;
}
p, h1, h2, h3, h4, h5, h6 {
	padding:0;
	margin-top: 6px;
	margin-bottom: 6px;
}
table, td, th {
	font-size: 1em;
}
h1, h2, h3 {
	font-family: "Trebuchet MS", Trebuchet, Arial, Helvetica, sans-serif;
}
h2 {
	font-size: 1.8em;
}
h3 {
	font-size: 1.5em;
}
.centered {
	text-align:center;
}
a {
	color: #000;
	font-weight:bold;
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}

p#date {
	font-weight: bold;
}

#unsubscribe {
	padding-top: 20px;
}
'; ?>

-->
</style>
</head>
<body>
	<h1 id="subject"><?php echo ((is_array($_tmp=$this->_tpl_vars['mail_sender'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</h1>
	<h2 id="subject"><?php echo ((is_array($_tmp=$this->_tpl_vars['mail_title'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</h2>
	<p id="date"><?php echo $this->_tpl_vars['mail_date']; ?>
</p>
	<div><?php echo $this->_tpl_vars['mail_email']; ?>
</div>
	<div id="message">
	<?php $this->_tag_stack[] = array('textformat', array('style' => 'email')); $_block_repeat=true;smarty_block_textformat($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<?php echo $this->_tpl_vars['mail_message']; ?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_textformat($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	</div>
</body>
</html>