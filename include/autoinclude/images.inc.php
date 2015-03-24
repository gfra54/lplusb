<?php

/*
    Décode le code hexadecimal HTML en un tableau de valeurs Rouge, Vert et Bleu.
    Accepte ces formats : (insensible à la casse) #ffffff, ffffff, #fff, fff
*/
function hex_to_rgb($hex) {
	// enlève le '#'
	if (substr($hex, 0, 1) == '#')
		$hex = substr($hex, 1);

	// expansion de la forme raccourcie ('fff') de la couleur
	if (strlen($hex) == 3) {
		$hex = substr($hex, 0, 1) . substr($hex, 0, 1) .
		substr($hex, 1, 1) . substr($hex, 1, 1) .
		substr($hex, 2, 1) . substr($hex, 2, 1);
	}

	if (strlen($hex) != 6)
		die('Error: Couleur invalide "' .
		$hex . '"');

	// conversion
	$rgb['red'] = hexdec(substr($hex, 0, 2));
	$rgb['green'] = hexdec(substr($hex, 2, 2));
	$rgb['blue'] = hexdec(substr($hex, 4, 2));

	return $rgb;
}

/*
	Essais pour déterminer le nombre de pixels sous la baseline de cette police
	pour cette taille
*/
function get_dip($font, $size) {
	$test_chars = 'abcdefghijklmnopqrstuvwxyz' .
	'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
	'1234567890' .
	'!@#$%^&*()\'"\\/;.,`~<>[]{}-+_-=';
	$box = @ ImageTTFBBox($size, 0, $font, $test_chars);
	return $box[3];
}
function genDir() {
	@mkdir($GLOBALS['chemin_site']."gen/",0777);
	return $GLOBALS['chemin_site']."gen/";
}
foreach(glob(genDir().'*.inc.php') as $file){
	include $file;
}
function my_getimagesize($file){
	$hash = sha1($file);

	if(!isset($GLOBALS['my_getimagesize'][$hash])) {
		if(file_exists($file)) {
			$size = getimagesize($file);
			$content = '<'.'?php'.PHP_EOL.'$GLOBALS["my_getimagesize"]["'.$hash.'"] = '.var_export($size,true).';';
			file_put_contents(genDir().$hash.'.inc.php',$content);
			$GLOBALS['my_getimagesize'][$hash] = $size;
		} else {
			return false;
		}
	}
	return $GLOBALS['my_getimagesize'][$hash];
}
function mode_image($file){
	if($size = my_getimagesize($file)) {
		$ret = array(
			'width' => $size[0],
			'height' => $size[1],
			'ratio' => $size[0]/$size[1],
			'mode' => $size[0]>$size[1] ? 'paysage' : 'portrait'
		);
	} else {
		$ret = array(
			'width' => 0,
			'height' => 0,
			'ratio' => 0,
			'mode' => false
		);
	}
	return $ret;
}