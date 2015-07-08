<?php
Class File{
	var $f;
	var $ext;
	var $text;
	function __construct($f=false,$text=false){
		$this->f = $f;
		$this->text=$text;
		$this->ext=false;
	}
	function ext(){
		if($this->ext===false){
			if(strstr($this->f,'.')!==false && substr($this->f, 0,1)!='.'){
				$tab = explode('.',$this->f);
				$ext = strtolower($tab[count($tab)-1]);
				$this->ext = $ext;
			} else {
				$this->ext='';
			}
		}
		return $this->ext;
	}
	function clean(){
		if($this->ext()){
			$tmp = substr($this->f,0,strlen($this->f)-strlen($this->ext())-1);
			return Url::clean($tmp).'.'.$this->ext();
		} else return Url::clean($this->f);
	}
	function rmdir($dir=false) { 
		$dir = $dir ? $dir : $this->f;
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") {
	         	$this->rmdir($dir."/".$object); 
	         }
	         else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	 } 	
	function type(){
		if(Image::isImage($this->f)){
			return 'image';
		} else return $this->ext();
	}
	function size(){
		return 	ceil(filesize($this->f)/1024).'ko';
	}
	function is($type){
		return Image::isImage($this->f) && $type == 'image' || $this->ext() == $type;
	}
	function shortName(){
		$f = $this->basename();
		if(strlen($f)>15) {
			return '<acronym title="'.$f.'">'.substr($f,0,6).'...'.substr($f,strlen($f)-7).'</acronym>';
		}
		return $f;

	}
	function basename(){
		return basename($this->f);
	}

	function base(){
		return basename($this->f);
	}

	function path(){
		return str_replace($GLOBALS['url_site'],'',$this->f);		
	}
	function url(){
		return $GLOBALS['url_site'].str_replace($GLOBALS['chemin_site'],'',$this->f);		
	}
	function ok() {
		if(in_array($this->ext(), array('jpg','jpeg','png','gif','pdf'))===false){
			return false;
		} else {
			return true;
		}
	}
	function __toString(){
		return $this->f;
	}
}