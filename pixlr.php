<?php 
include "include/main.inc.php";
//		print_r($_GET);
switch($_GET['action']){
	case "close":
		closeWindow();
	break;

	default:
		if(isset($_GET['image']) && $t = base64_decode($_GET['title'])){
			$c = file_get_contents($_GET['image']);
			file_put_contents($GLOBALS['CHEMIN_SITE'].$t,$c);
		}
		closeWindow('document.getElementById("form_upload").submit()');
	break;

}

?>
