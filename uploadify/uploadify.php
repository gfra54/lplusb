<?php
include "../include/main.inc.php";

$what = basename($_POST['what']);
$id = intval(basename($_POST['id']));

if($id && $what) {
	$targetFolder = $GLOBALS['chemin_site']."data/".$what.'/'.$id.'/';
	@mkdir($targetFolder,0777,true);




	if (!empty($_FILES)) {
		$File = new File($_FILES['Filedata']['name']);
		if($File->is('image')) {
			$targetFile = $targetFolder.$File->clean();
			move_uploaded_file($_FILES['Filedata']['tmp_name'],$targetFile);
			echo '1';
		} else {
			echo 'Invalid file type.';
		}
	}
}