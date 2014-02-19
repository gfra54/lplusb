<?php
include "../include/main.inc.php";

$what = basename($_POST['what']);
$id = intval(basename($_POST['id']));

if($id && $what) {
	$targetFolder = $GLOBALS['chemin_site']."data/".$what.'/'.$id.'/';
	@mkdir($targetFolder,0777,true);




	if (!empty($_FILES)) {
		$tempFile = $_FILES['Filedata']['tmp_name'];
		if($ext = File::ext($_FILES['Filedata']['name'])) {
			$tmpname = str_replace('.'.$ext,'',$_FILES['Filedata']['name']);
			$targetFile = $targetFolder.Url::clean($tmpname).'.'.$ext;

			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				echo '1';
			} else {
				echo 'Invalid file type.';
			}
		}
	}
}