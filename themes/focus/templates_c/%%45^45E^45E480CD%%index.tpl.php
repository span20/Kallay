<?php /* Smarty version 2.6.16, created on 2015-07-29 17:54:51
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'index.tpl', 210, false),)), $this); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
    <title><?php echo $this->_tpl_vars['sitename'];  if ($this->_tpl_vars['content_title']): ?> - <?php echo $this->_tpl_vars['content_title'];  endif; ?></title>
    <base href="<?php echo $_SESSION['site_sitehttp']; ?>
" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
    <meta name="description" content="<?php echo $this->_tpl_vars['meta_tags']['description']; ?>
" />
    <meta name="keywords" content="<?php echo $this->_tpl_vars['meta_tags']['keywords']; ?>
" />

	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="7 days" />

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=iso-8859-2" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/favicon.ico" />

	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['include_dir']; ?>
/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['include_dir']; ?>
/bootstrap/css/bootstrap-theme.min.css" />
	
	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/style.css" />
	<?php if ($_SESSION['sitetype'] == 2): ?>
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/blind.css" />
	<?php endif; ?>
	
	<?php $_from = $this->_tpl_vars['css']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/<?php echo $this->_tpl_vars['c']; ?>
.css" />
	<?php endforeach; endif; unset($_from); ?>	
	<link rel="stylesheet" type="text/css" media="print" href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/print.css" />	
</head>

<body <?php if ($this->_tpl_vars['bodyonload']): ?>onload="<?php $_from = $this->_tpl_vars['bodyonload']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['load']):
 echo $this->_tpl_vars['load']; ?>
;<?php endforeach; endif; unset($_from); ?>"<?php endif; ?>>
<div class="container">
	<?php if ($_SESSION['sitetype'] != 2): ?>
	<div class="header" onclick="window.location = '<?php echo $_SESSION['site_sitehttp']; ?>
'">
		<?php if ($this->_tpl_vars['bgpic']): ?>
			<img src="<?php echo $this->_tpl_vars['bgpic']; ?>
" class="img-responsive" />
		<?php else: ?>
			<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/head_pic.jpg" class="img-responsive" />
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div>
		<nav class="navbar navbar-inverse">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<?php $_from = $this->_tpl_vars['topmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['topfor1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['topfor1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['data']):
        $this->_foreach['topfor1']['iteration']++;
?>			
												<li class="dropdown">
							<?php $this->assign('isactive', false); ?>
							<?php if ($this->_tpl_vars['data']['element']): ?>						
								<ul class="dropdown-menu">
								<?php $_from = $this->_tpl_vars['data']['element']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subfor1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subfor1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['datasub']):
        $this->_foreach['subfor1']['iteration']++;
?>
									<li class="subitem">
																				<a href="index.php?mid=<?php echo $this->_tpl_vars['datasub']['menu_id']; ?>
"><?php echo $this->_tpl_vars['datasub']['menu_name']; ?>
</a>										
									</li>
									<?php if ($this->_tpl_vars['datasub']['menu_id'] == $_REQUEST['mid']): ?>
										<?php $this->assign('isactive', true); ?>
									<?php endif; ?>
								<?php endforeach; endif; unset($_from); ?>
								</ul>
							<?php endif; ?>
							<a class="
								<?php if ($this->_tpl_vars['data']['menu_id'] == $_REQUEST['mid'] || $this->_tpl_vars['isactive']):  echo $this->_tpl_vars['data']['menu_color']; ?>
active<?php endif; ?>
								<?php if ($this->_tpl_vars['data']['menu_color']):  echo $this->_tpl_vars['data']['menu_color'];  endif; ?>
							" href="index.php?mid=<?php echo $this->_tpl_vars['data']['menu_id']; ?>
"><?php echo $this->_tpl_vars['data']['menu_name']; ?>
</a>
						</li>				
					<?php endforeach; endif; unset($_from); ?>
				</ul>
				<div class="gyengenlato pull-right">
					<?php if ($_SESSION['sitetype'] == 2): ?>
						<a href="index.php?sitetype=1">Normál verzió</a>
					<?php else: ?>
						<a href="index.php?sitetype=2"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/gyengenlato-icon.png"></a>
					<?php endif; ?>
				</div>
			</div>
		</nav>
	</div>
	<div class="col-md-12 white-bg">
		<?php if ($this->_tpl_vars['module_name'] == 'gallery'): ?>
		<div class="col-md-12">
		<?php else: ?>
		<div class="col-md-8">
		<?php endif; ?>
			<?php if (( $this->_tpl_vars['site_errors'] || $this->_tpl_vars['site_success'] || $this->_tpl_vars['page'] != "" )): ?>
				<?php if ($this->_tpl_vars['site_errors']): ?>
					<?php $_from = $this->_tpl_vars['site_errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
						<div style="text-align: center;">
							<?php echo $this->_tpl_vars['data']['text']; ?>
<br />
							<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
" title="<?php echo $this->_tpl_vars['locale']['config']['back_link']; ?>
"><?php echo $this->_tpl_vars['locale']['config']['back_link']; ?>
</a>
						</div>
					<?php endforeach; endif; unset($_from); ?>
				<?php elseif ($this->_tpl_vars['site_success']): ?>
					<?php $_from = $this->_tpl_vars['site_success']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
						<div style="text-align: center;">
							<?php echo $this->_tpl_vars['data']['text']; ?>
<br />
							<a href="<?php echo $this->_tpl_vars['data']['link']; ?>
" title="<?php echo $this->_tpl_vars['locale']['config']['next_link']; ?>
"><?php echo $this->_tpl_vars['locale']['config']['next_link']; ?>
</a>
						</div>
					<?php endforeach; endif; unset($_from); ?>
				<?php else: ?>			
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['page']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>						
				<?php endif; ?>
			<?php else: ?>
				<div class="cont_text <?php if ($this->_tpl_vars['heading_color']):  echo $this->_tpl_vars['heading_color'];  endif; ?>">
					<?php if (! empty ( $this->_tpl_vars['main_cont']['content2'] )): ?>
						<div class="col-md-6">
							<?php echo $this->_tpl_vars['main_cont']['content']; ?>

						</div>
						<div class="col-md-6">
							<?php echo $this->_tpl_vars['main_cont']['content2']; ?>

						</div>
					<?php else: ?>
						<div class="col-md-12">
							<?php echo $this->_tpl_vars['main_cont']['content']; ?>

						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ($this->_tpl_vars['module_name'] != 'gallery'): ?>
			<div class="col-md-4">
				<div class="sidebar">
					<h4>Nyitva tartás / Opening hours</h4>
					<div class="sidebar_box">
						Kialakítás alatt					</div>
					
					<h4>BELÉPÕDÍJAK / ADMISSION FEE</h4>
					<div class="sidebar_box">					
												Kialakítás alatt
					</div>
					
					<h4>Kapcsolat / Contact</h4>
					<div class="sidebar_box">
						4324 Kállósemjén, Kossuth út 94.<br />
						info@kallaykuria.hu<br />
						+36 (42) 255-423<br />
						<?php if ($_SESSION['sitetype'] != 2): ?>
							<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
							<?php echo '
							<script>
							window.onload = function() {
							
								var posLatlng = new google.maps.LatLng(47.860983,21.920417);
							
								var myOptions = {
									center: posLatlng,
									zoom: 16,
									mapTypeId: google.maps.MapTypeId.ROADMAP,
									disableDefaultUI: true
								};
								
								var map = new google.maps.Map(document.getElementById("map"), myOptions);
								
								var marker = new google.maps.Marker({
								  position: posLatlng,
								  map: map
								});					
							}
							</script>
							'; ?>

							<div id="map" style="width: 275px; height:140px; margin: 10px 0;" />
						<?php endif; ?>
					</div>
					<?php if ($this->_tpl_vars['latnivalok']): ?>
						<h4>Látnivalók a környéken /<br /> Places of interest nearby</h4>
						<div class="sidebar_box">
							<?php $_from = $this->_tpl_vars['latnivalok']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>						
								<a href="index.php?p=news&act=show&cid=<?php echo $this->_tpl_vars['data']['content_id']; ?>
"><?php echo $this->_tpl_vars['data']['title']; ?>
</a><br />
							<?php endforeach; endif; unset($_from); ?>
						</div>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['partnerek']): ?>
						<h4>Partnereink / Our partners</h4>
						<div class="sidebar_box">
							<?php $_from = $this->_tpl_vars['partnerek']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>						
								<a href="index.php?p=news&act=show&cid=<?php echo $this->_tpl_vars['data']['content_id']; ?>
"><?php echo $this->_tpl_vars['data']['title']; ?>
</a>
							<?php endforeach; endif; unset($_from); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="col-md-12 footer">
		<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
 &copy; Kállay kúria. Minden jog fenntartva.
	</div>
</div>

<?php if ($this->_tpl_vars['bannerek']): ?>
	<script type="text/javascript">//<![CDATA[
		bid    = new Array();
		pid    = new Array();
		mid    = new Array();
		pic    = new Array();
		width  = new Array();
		height = new Array();
		type   = new Array();
		reload = new Array();
		code   = new Array();

		<?php echo $this->_tpl_vars['bannerek']; ?>

	//]]>
	</script>
<?php endif; ?>

<?php if ($this->_tpl_vars['ajax']['link']): ?><script type="text/javascript" src="<?php echo $this->_tpl_vars['ajax']['link']; ?>
"></script><?php endif;  if ($this->_tpl_vars['ajax']['script']): ?>
	<script type="text/javascript">
		//<![CDATA[<?php echo $this->_tpl_vars['ajax']['script']; ?>
//]]>
	</script>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['javascripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js']):
 if (preg_match ( "/\.js/" , $this->_tpl_vars['js'] )): ?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/<?php echo $this->_tpl_vars['js']; ?>
"></script>
<?php else: ?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['include_dir']; ?>
/<?php echo $this->_tpl_vars['js']; ?>
.js"></script>
<?php endif;  endforeach; endif; unset($_from); ?>

</body>
</html>