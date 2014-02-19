<?php
include "../include/main.inc.php";

$what = basename($_POST['what']);
$id = intval(basename($_POST['id']));

if($id && $what) {
	$targetFolder = $GLOBALS['chemin_site']."data/".$what.'/'.$id.'/';
	@mkdir($targetFolder,0777,true);




	if (!empty($_FILES)) {
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetFile = $targetFolder.cleanName($_FILES['Filedata']['name']);

		// Validate the file type
		$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
		$fileParts = pathinfo($_FILES['Filedata']['name']);
		echo $targetFile;
		if (in_array($fileParts['extension'],$fileTypes)) {
			move_uploaded_file($tempFile,$targetFile);
			echo '1';
		} else {
			echo 'Invalid file type.';
		}
	}
}