<?php
$GLO_LANG=array();
$GLO_DEFAULT_LANG='fr';
$GLO_LANGUES = array('fr'=>'Fran&ccedil;ais', 'en'=>'English');
$cpt_selects=0;

if(!isset($_SESSION['site_lang'])) {
	$_SESSION['site_lang']=$GLO_DEFAULT_LANG;
}
if(isset($_GET['chl'])){
	$_SESSION['site_lang']=$_GET['chl'];
	redir('back');
}


function langSwitch() {
	if($_SESSION['site_lang']=='fr'){
	?>
	<span>Fran&ccedil;ais</span>  <a href="?chl=en">English</a>
	<?php
	
	} else {
	?>
	<a href="?chl=fr">Fran&ccedil;ais</a>  <span>English</span>
	<?php
	}
}
function loadLang(){
	global $GLO_LANG, $GLO_DEFAULT_LANG,$PATH_SITE;
	$GLO_LANG = myparseini($PATH_SITE.'include/lang/'.$_SESSION['site_lang'].'.ini');
}

function mess(){
	$mess = isset($_GET['mess'])? htmlspecialchars($_GET['mess']) : false;
	if($mess)
		echo'<span class="mess">'.lang($mess).'</span>';
}
function lang($k){
	global $GLO_LANG;
	
	if(!$GLO_LANG) {
		loadLang();
	}
	if(is_array($k)){
		return nl2br(!empty($k[$_SESSION['site_lang']]) ? $k[$_SESSION['site_lang']] : $k['fr']);
	} else
	if(isset($GLO_LANG[$k])){
		return $GLO_LANG[$k];
	} else {
		return $k;
	}
}
function getLangImage($lang) {
	return 'images/'.($lang).'.png';
}

function listeLangues($id=false) {
	global $GLO_LANGUES, $cpt_selects;
	$html='<select id="select_'.$cpt_selects.'" class="form" element="'.$id.'" onchange="switchAllSelect(this)">';
	foreach($GLO_LANGUES as $code=>$lib) {
		$html.='<option style="background:url('.getLangImage($code).') no-repeat right center" value="'.$code.'">'.$lib.'</option>';
	}
	$html.='</select>';
	
	$cpt_selects++;
	return $html;
}


function getCodesLang() {
	global $GLO_LANGUES;
	return array_keys($GLO_LANGUES);
}

function getTabLang() {
	global $GLO_LANGUES;
	$ret=array();
	foreach($GLO_LANGUES as $k=>$v) {
		$ret[$k]='';
	}
	return $ret;
}
?>