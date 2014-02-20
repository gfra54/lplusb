<?php
include('include/main.inc.php');
$expires = 60*60*24*3;
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');


$nocache = isset($_GET['nocache']);
$alpha = isset($_GET['alpha']);
$png = strstr($_SERVER['REQUEST_URI'],'png')!=false;
$gif = isset($_GET['gif']);
$noborder = isset($_GET['noborder']) ? 2 : false;

$type = $gif ? 'GIF' : ( $png ? 'PNG' : 'JPEG' );

$compression = $type == 'JPEG' && isset($_GET['c']) ? intval($_GET['c']) : 95;
$youtube = isset($_GET['youtube']);
$i = isset ($_GET['i']) ? $_GET['i']: false;
$tmp_youtube=false;
// Gestion d'images
if ($i) {
		$source = Url::fromUrl($i);

		$largeur_max = $_GET['x'];
		$hauteur_max = $_GET['y'];
		$cut = $_GET['cut'];

		if(!file_exists($source))
			exit;

		if(strstr($_SERVER['REQUEST_URI'],'/img-')!==false) {
			if (!is_dir($GLOBALS['chemin_site']."IMG/")){
				@mkdir($GLOBALS['chemin_site']."IMG/",0777);
			}

			$cle = 'IMG/'.basename($_SERVER['REQUEST_URI']);
		} else {
			if (!is_dir("CACHE/IMG/")){
				@mkdir("CACHE",0777);
				@mkdir("CACHE/IMG/",0777);
			}

			$cle = "CACHE/IMG/" . @filemtime($source) . md5(@filesize($source) . $source . $largeur_max . $hauteur_max . $cut . $gif. $alpha. $noborder);
		}

		$IF = new Image;

		$cache = false;
		if (!$nocache && file_exists($cle)) {
			$cache = true;
			//		$_t = filemtime($cle);
			//		$delta = time() - $_t;
		}

		if ($cache) {
			$IF->loadImage($cle);
		} else {
			$size = getimagesize($source);
			$largeur_src = $size[0];
			$hauteur_src = $size[1];
			ini_set("memory_limit","16M");
			//2ieme verification -> on verifie que le type du fichier est un jpg, jpeg ou gif
			// $size[2] -> type de l'image : 1 = GIF , 2 = JPG, JPEG
			if ($size[2] != 1 AND $size[2] != 2 AND $size[2] != 3) {
				echo "Bad format"; exit;
			}

			$IF->loadImage($source);

			if($noborder) {
				$IF->crop($noborder,$noborder,$largeur_src-(2*$noborder),$hauteur_src-(2*$noborder));
			}
			$largeur_max = $largeur_max == 'max' ? intval($largeur_src * $hauteur_max / $hauteur_src) : $largeur_max;
			$hauteur_max = $hauteur_max == 'max' ? intval($hauteur_src * $largeur_max / $largeur_src) : $hauteur_max;

			// on verifie que l'image source ne soit pas plus petite que l'image de destination
			if (($largeur_src > $largeur_max OR $hauteur_src > $hauteur_max)) {
				if ($cut == 'true' || $cut == 'center' || $cut == 'top') {
//					$largeur_max = $largeur_src <= $largeur_max ? $largeur_src : $largeur_max;
//					$hauteur_max = $hauteur_src <= $hauteur_max ? $hauteur_src : $hauteur_max;

					if ($largeur_src < $hauteur_src) {
						$IF->resize($largeur_max, 0, "ratio");
					} else {
						$IF->resize(0, $hauteur_max, "ratio");
					}
//					$IF->resize($largeur_max, 0, "ratio");
					$_s = $IF->getImageSize();

					$largeur_max = $largeur_max > $_s['w'] ? $_s['w'] : $largeur_max;
					$hauteur_max = $hauteur_max > $_s['h'] ? $_s['h'] : $hauteur_max;

					if($cut == 'center') {
						if($largeur_max < $hauteur_max) {
							$dx = intval(($_s['w'] - $largeur_max) / 2);
							$dy = 0;
						} else {
							$dx=0;
							$dy = intval(($_s['h'] - $hauteur_max) / 2);
						} 
					} else if($cut == 'top') {
						$dx=0;
						$dy=0;
					} else {
						$dx=0;
						$dy=0;
					}
					$IF->crop($dx, $dy, $largeur_max, $hauteur_max);
				} else {
					// si la largeur est plus grande que la hauteur
					if ($hauteur_src <= $largeur_src) {
						$ratio = $largeur_max / $largeur_src;
					} else {
						$ratio = $hauteur_max / $hauteur_src;
					}
					$IF->resize(($ratio*100).'%','','force',true);
					if(($ratio*100)>100)
						$IF->blur();
//					$IF->resize(round($largeur_src * $ratio), round($hauteur_src * $ratio));
				}

				$IF->rotation(180);
				$IF->rotation(180);
			}

			if($alpha) {
//				$IF->grayscale();
				$IF->colorize('190', 0,0,0);
			}
			$_s = $IF->getImageSize();
			$compression = $_s['w'] < 200 ? 75 : $compression;
			$IF->output($type, $cle,NULL,$compression);
		}
		//header('Cache-Control: max-age=36000');
		$IF->output($type,NULL,NULL,$compression);

}
if($tmp_youtube) {
	unlink($tmp_youtube);
}

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

