<?php
$id_data = isset($_GET['id_data']) ? $_GET['id_data'] : false;

function getPhotos($debut=0,$nb=false,$exclude=false) {

	$data = getSortData('photos','date',true,'pub');	
	if($exclude)
		unset($data[$exclude]);

	$now = time();
	$out=array();
	foreach($data as $k=>$v){
		if($v['date'] < $now) {
			$out[]=$v;
		}
	}

	return $nb ? array_slice($out,$debut,$nb) : $data;
	
}

function getBlocks($situation=false) {
	$data = getSortData('blocks','ordre',false,'pub');	
	$out=array();
	foreach($data as $k=>$v){
		if($v['situation'] == $situation || !$situation)
		$out[]=$v;
	}
//	echo '<pre>';die(print_r($out));
	return $out;
}

function getPage($id) {
	$data = getSortData('pages','id',true,'pub');	
	return isset($data[$id]) ? $data[$id] : false;
}

function getNewsById($id) {
	$data = getSortData('news','id',true,'pub');	
	return isset($data[$id]) ? $data[$id] : false;
}

function getLastPdf($what,$key) {
	$docs = getDocs($what,$key);
	$docs = array_reverse($docs);
	foreach($docs as $doc) {
		if(isPdf($doc))
			return $doc;
	}
	return false;
}

function getFirstPdf($what,$key) {
	$docs = getDocs($what,$key);
//	$docs = array_reverse($docs);
	foreach($docs as $doc) {
		if(isPdf($doc))
			return $doc;
	}
	return false;
}

function okPdf($what,$key){
	return getLastPdf($what,$key);
}

function okImg($what,$key){
	return getFirstImage($what,$key);
}

function getFirstImage($what,$key,$exclude=false) {
	global $PATH_SITE;
	$docs = getDocs($what,$key);
	foreach($docs as $doc) {
		if(!isPdf($doc) && $exclude != $doc && $PATH_SITE.$exclude != $doc){
			return $doc;
		}
	}
	return false;
}

function getNewsCount($pub=true){
	$data = getSortData('news','date',true,$pub ? 'pub' : false);	
	return count($data);
}

function getNews($debut,$nb,$exclude=false) {

	$data = getSortData('news','date',true,'pub');	
	if($exclude)
		unset($data[$exclude]);

	$now = time();
	$out=array();
	foreach($data as $k=>$v){
		if($v['date'] < $now) {
			$out[]=$v;
		}
	}

	return array_slice($out,$debut,$nb);
	
	
}
?>