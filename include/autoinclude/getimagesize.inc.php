<?php

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
		$size = getimagesize($file);
		$content = '<'.'?php'.PHP_EOL.'$GLOBALS["my_getimagesize"]["'.$hash.'"] = '.var_export($size,true).';';
		file_put_contents(genDir().$hash.'.inc.php',$content);
		$GLOBALS['my_getimagesize'][$hash] = $size;
	}

	return $GLOBALS['my_getimagesize'][$hash];
}

function mode_image($file){
	$size = my_getimagesize($file);	
	$ret = array(
		'width' => $size[0],
		'height' => $size[1],
		'ratio' => $size[0]/$size[1],
		'mode' => $size[0]>$size[1] ? 'paysage' : 'portrait'
	);
	return $ret;
}