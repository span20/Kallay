<?php
	ini_set('include_path', '../../../../../libs/pear'.PATH_SEPARATOR.
						'../../../../../'.PATH_SEPARATOR.
						ini_get('include_path'));
	include_once 'includes/config.php';
	if (!isset($_SESSION['user_id'])) {
		die('No rights');
	}

	$file    = $_GET['file'];
	if (get_magic_quotes_gpc()) {
		$file = stripslashes($file);
	}
	$path	 = realpath('./');
	$ld		 = preg_quote($libs_dir);
	$dirsep  = DIRECTORY_SEPARATOR;
	$akt_dir = preg_replace("!$dirsep$ld$dirsep.*$!", '', $path);

	$text = 'Nem siker�lt t�r�lni a f�jlt';
	if (is_file($akt_dir.$dirsep.$file)) {
		if (@unlink($akt_dir.$dirsep.$file)) {
			$text = $file.' t�r�lve';
		}
	}
?>
<html>
<head>
<title>T�rl�s</title>
<meta http-equiv="Content-Type" content="text/html; utf-8" />
<script type="text/javascript">
opener.history.go();
</script>
</head>
<body>
<p style="text-align:center;"><?php print $text;?><br><a href="javascript:self.close();">bez�r�s</a></p>
</body>
</html>
