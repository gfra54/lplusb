<?php

Class File{

	public static function ext($f){
		if(strstr($f,'.', needle)!==false && substr($f, 0,1)!='.'){
			$tab = explode('.',$f);
			$ext = strtolower($tab[count($tab)-1]);
			return $ext;
		} else {
			return false;
		}
	}

	function rmdir($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	 } 	
}