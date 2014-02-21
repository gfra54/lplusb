<?php

function initSettings() {
	if(list(,$ret) = @each($GLOBALS['bdd']->Select('settings',array('id'=>$GLOBALS['CUR_SETTINGS'])))) {
		foreach($ret as $k=>$v) {
			$GLOBALS[$k]=$v;
		}
	}
	if(!$GLOBALS['chemin_site']) {
		$GLOBALS['chemin_site']=realpath('.').'/';
	}
}



function getSetting($k) {
	return isset($GLOBALS[$k]) ? $GLOBALS[$k] : false; 
}
