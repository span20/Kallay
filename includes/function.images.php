<?php

/**
 * img_resize - k�p �tm�retez�se 
 *
 * Visszat�r�si �rt�ke false, ha sikertelen, egy�bk�nt asszociat�v t�mb, a
 * l�trej�tt k�p m�rete 'width', 'height' kulcsokkal. 
 *
 * @param string $src - az �tm�retezni k�v�nt k�p el�r�si �tja
 * @param string $dest - a mentend� �tm�retezett k�p el�r�si �tja
 * @param int $max_width - maxim�lis sz�less�g pixelben
 * @param int $max_height - maxim�lis magass�g pixelben
 * @param int $jpegquality - jpeg min�s�ge 0-100-ig.
 * @access public
 * @return mixed
 *
 */
function img_resize($src, $dest, $max_width=80, $max_height=60, $jpegquality=100, $crop = 0) 
{
	$GIF = 1;
	$JPG = 2;
	$PNG = 3;

	if (!function_exists('GetImageSize')) {
		return FALSE;
	}
	$size = @GetImageSize($src) ;

	if (!is_array($size)) return FALSE;
	$width  = $size[0] ;
	$height = $size[1] ;

	$x_arany = $max_width / $width ;
	$y_arany = $max_height / $height ;

	if (($height <= $max_height) && ($width <= $max_width)) 
	{
		$uj_height = $height ;
		$uj_width  = $width ;
	} elseif ($x_arany * $height < $max_height) 
	{
		$uj_height = ceil($x_arany*$height) ;
		$uj_width  = $max_width ;
	} else {
		$uj_width = ceil($y_arany * $width) ;
		$uj_height = $max_height ;
	}

	$tipus = $size[2] ;
	switch ($tipus) {
		case $GIF: 
			if (!function_exists('ImageCreateFromGif') || !($kep = @ImageCreateFromGif($src))) {
				return FALSE ;
			}
			break ;
		case $JPG:
			if (!function_exists('ImageCreateFromJpeg') || !($kep = @ImageCreateFromJpeg($src))) {
				return FALSE;
			}
			break;
		case $PNG: 
			if (!function_exists('ImageCreateFromPng') || !($kep = @ImageCreateFromPng($src))) {
				return FALSE ;
			}
			break;
		default: return FALSE;
	}
	$src_from_x = 0;
	$src_from_y = 0;
	
	$ujkep = ImageCreateTrueColor($uj_width, $uj_height);
	if ($crop == 1 || $crop == 2) {
		$src_from_x = 150;
		$src_from_y = 150;
		$width = $src_from_x + $uj_width;
		$height = $src_from_y + $uj_height;
	}
	ImageCopyResampled($ujkep, $kep, 0, 0, $src_from_x, $src_from_y, $uj_width, $uj_height, $width, $height);
	if ($crop == 2) {
		$imgw = imagesx($ujkep);
		$imgh = imagesy($ujkep);
		
		for ($i=0; $i<$imgw; $i++)
		{
			for ($j=0; $j<$imgh; $j++)
			{
				// get the rgb value for current pixel
				$rgb = ImageColorAt($ujkep, $i, $j);
			   
				// extract each value for r, g, b
				$rr = ($rgb >> 16) & 0xFF;
				$gg = ($rgb >> 8) & 0xFF;
				$bb = $rgb & 0xFF;
			   
				// get the Value from the RGB value
				$g = round(($rr + $gg + $bb) / 3);
				// grayscale values have r=g=b=g
				$val = imagecolorallocate($ujkep, $g, $g, $g);
				// set the gray value
				imagesetpixel($ujkep, $i, $j, $val);
			}
		}
	}
	if ($tipus==$GIF) {
		if (!@ImageGif($ujkep, $dest)) {
			return FALSE;
		}
	} elseif ($tipus==$JPG) {
		if (!@ImageJpeg($ujkep, $dest, $jpegquality)) {
			return FALSE;
		}
	} else {
		if (!@ImagePng($ujkep, $dest)) {
			return FALSE;
		}
	}
	ImageDestroy($kep) ;
	ImageDestroy($ujkep) ;
	return array("width"=>$uj_width, "height"=>$uj_height) ;	
}

/**
 * uj filenevet general a datum alapjan
 *
 * @param	string	file kiterjesztese
 * @param	string	a file eredeti neve
 *
 * @return	name	generalt filenev
 */
function create_filename($ext, $oldname)
{
	$date    = mktime();
	$ext     = strtolower($ext);

	$oldname = change_hunchar($oldname);
	$oldname = strtolower($oldname);

	$name    = $date."_".$oldname.".".$ext;

	return $name;
}
?>
