<?php


function createLinks($w1,$id1,$w2,$ids){
	$ids = is_array($ids) ? $ids : array($ids);
	$out='';
	$GLOBALS['bdd']->Delete('links',array(
			'w1'=>$w1,
			'id1'=>$id1
	));
	foreach($ids as $k=>$v) {
		insertData('links',array(
			'w1'=>$w1,
			'id1'=>$id1,
			'w2'=>$w2,
			'id2'=>$v
		));
		$out.=$out?',' : '';
		$out.=$v;
	}
	return $out;
}
function getData($what,$params=false,$orderby=false) {
	return $GLOBALS['bdd']->Select($what,$params,$orderby);
}

function getDataLimit($what,$params=false,$debut=false,$parpage=false,$orderby=false) {
	return $GLOBALS['bdd']->Select($what,$params,$orderby,($debut ? $debut.',' : '').($parpage?$parpage:''));
}
function getUserByLogin($login) {

	if(list(,$ret) = each($GLOBALS['bdd']->Select('users',array('login'=>$login)))) {
		return $ret;	
	}
	return false;
}

function getMainImage($what,$data,$blank=true){
	if(isset($data['main_image'])){
		return str_replace($GLOBALS['CHEMIN_SITE'],'',$data['main_image']);
	} else {
		$docs = getDocs($what,$data['id']);
		foreach($docs as $doc){
			if(!isPdf($doc)){
				return $doc;	
			}
		}
	}
	return $blank  ? 'images/blank.png' : false;
}

function doLibList($nom_data,$data) {
	global $GLO_DESC, $GLO_DEFAULT_LANG;
	$tpl_lib = $GLO_DESC[$nom_data]['specs']['lib_list'];
	$GLO_DESC[$nom_data]['id']=true;
	foreach($GLO_DESC[$nom_data] as $k=>$v) {
		if(strstr($tpl_lib,'#'.$k)!==false) {
			if(isset($v['lang'])) {
				$tpl_lib = str_replace('#'.$k,$data[$k][$GLO_DEFAULT_LANG],$tpl_lib);
			} else {
				if($k == 'pub') {
					$_data = '<img src="images/pub_'.$data[$k].'.png">';
				} else if($k == 'date') {
					$_data = mefDate($data[$k]);
				} else {
					$_data = $data[$k];
				}
				$tpl_lib = str_replace('#'.$k,$_data,$tpl_lib);
			}
		}
	}
	return $tpl_lib;
}
function viderCache(){
	$d = dir('.');
	while (false !== ($entry = $d->read())) {
		if(substr($entry,0,4) == 'img-') {
			unlink($entry);
		}
	}
	File::rmdir($GLOBALS['chemin_site'].'CACHE');
	$d->close();
}
function toUrl($f){
	return str_replace('/','_',$f);
}
function shortName($f) {
	$f = basename($f);
	if(strlen($f)>15) {
		return '<acronym title="'.$f.'">'.substr($f,0,6).'...'.substr($f,strlen($f)-7).'</acronym>';
	}
	return $f;

}
function getDocs($what,$id,$img=false) {
	global $PATH_SITE;
	$dir = $PATH_SITE.'data/'.$what.'/'.$id.'/';
	$image_orders = explode('|',@file_get_contents($dir.'images_order.txt'));
	$docs = array();
	foreach($image_orders as $entry){
		if(!empty($entry)) {
			$docs[$entry]=$dir.($entry);
		}
	}
	if($d = @dir($dir)) {
		while (false !== ($entry = $d->read())) {
			if($img===false || !isPdf($entry)) {
				if(fileIsOk($entry)) {
					if(!isset($docs[$entry])) {
					   $docs[$entry] = $dir.($entry);
					  }
				}
			}
		}
		$d->close();
	}
	return $docs;
}
function getTypeFichier($f){
	$s = '('.ceil(filesize($f)/1024).'ko)';
	if(isPdf($f))
		return 'PDF '.$s;
	return 'Image '.$s;
}

function isPdf($f){
	if(strstr($f,'PDF')!==false || strstr($f,'pdf')!==false)
		return true;
	else
		return false;
}
function fileIsOk($f) {
	if(strstr($f,'jpg')!==false || strstr($f,'JPG')!==false || strstr($f,'png')!==false || strstr($f,'pdf')!==false)
		return true;
	else
		return false;
}
function initDir($what,$id) {
	@mkdir('data');
	@mkdir('data/'.$what);
	@mkdir('data/'.$what.'/'.$id);
}


function insertData($what,$data) {
	if($GLOBALS['bdd']->Insert($data,$what)) {
		return $GLOBALS['bdd']->LastInsertID();
	} else return false;
}

function deleteData($what,$key) {
	$key = is_array($key) ? $key : array($key);
	foreach($key as $k=>$v) {
		removeDir('data/'.$what.'/'.$v);
		$GLOBALS['bdd']->Delete($what,array('id'=>$v));
	}
	return true;
}
function updateData($what,$key,$data) {
	return $GLOBALS['bdd']->Update($what,$data,array('id'=>$key));
}
function writeData() {
	global $GLO_DATA, $PATH_SITE;
	file_put_contents($PATH_SITE.'include/data.gen',serialize($GLO_DATA));
}



function newEmpty($what) {
	global $GLO_DESC; 
	$ret = array();
	foreach($GLO_DESC[$what] as $k=>$v) {
		if(isset($v['data'])) {
			if(isset($v['lang'])) {
				$tmp = getCodesLang();
				$ret[$k]=array();
				foreach($tmp as $c) {
				$ret[$k][$c]='';
				}
			} else {
				$ret[$k]=isset($v['default']) ? $v['default'] : '';
			}
		}
	}
	return $ret;
}
