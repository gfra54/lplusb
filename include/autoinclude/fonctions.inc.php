<?php
$GLO_COULEURS = array(
	'#333'=>array('lib'=>'Noir','color'=>'#fff','image'=>'logo_noir','fond'=>'fond-menu-blanc'),
//	'#ccc'=>array('lib'=>'Gris clair','color'=>'#000','image'=>'logo_gc'),
//	'#999'=>array('lib'=>'Gris fonc?','color'=>'#fff','image'=>'logo_gf'),
	'#ddd'=>array('lib'=>'Blanc','color'=>'#000','image'=>'logo_blanc','fond'=>'fond-menu-noir'),			
//	'red'=>array('lib'=>'Rouge','color'=>'#fff'),
);

function closed() {
	showHeader();
	?><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<center><big>Site en maintenance</big></center>
	<?php showFooter();
	exit;
}


function htaccessRebuild(){
	$htaccess = file_get_contents('.htaccess.edit');
	$pages = getData('pages');
	foreach($pages as $k=>$v) {
		$htaccess.="\n".'RewriteRule   ^'.$v['uri'].'$								page.php?id='.$v['id'].'  [L,QSA]';
	}
	return file_put_contents($GLOBALS['chemin_site'].'.htaccess',$htaccess);
}
function teleport($url){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$url);
        exit;
}

function url_page($page,$html=true) {
	return doUrl('page',$page['id'], $page['titre'],$html);
}

function url_news($news,$html=true) {
	return doUrl('news',$news['id'], $news['titre'],$html);
}

function url_video($video,$html=true) {
	return doUrl('videos',$video['id'], $video['titre'],$html);
}

function doUrl($w,$id, $lib,$html=true) {
	if($html) {
		$url = cleanName($lib).'-'.$w.'-'.$id.'.html';
	} else {
		$url = $w.'.php?id='.$id;
	}
	return $url;
}

function doAttribute($w,$data) {
	echo $data ? $w.'="'.htmlspecialchars($data).'"' : '';
}
function nl2br_special($s){
	$s =str_replace("\r","",$s);
	$s =str_replace("\n\n\n","<p></p>",$s);
	$s =str_replace("\n\n","<br>",$s);
	return $s;
}
function builBlocks($w) {
$blocks_home = getBlocks($w);
foreach($blocks_home as $k=>$v){
	$main_image = getMainImage('blocks',$v,false);
?>
					<a href="<?php echo $v['url'];?>" <?php echo doAttribute('title',$v['balise_title']);?> class="block">
						<img src="<?php doImage($main_image,429);?>" class="image" <?php echo doAttribute('alt',$v['balise_alt']);?>>
						<hr>
						<span class="texte <?php echo $v['couleur'];?>">
							<span class="titre">
								<?php echo $v['titre'];?>
							</span>
							<span class="desc">
								<?php echo $v['texte'];?>
							</span>
						</span>
					</a>
					<?php adminEdit('blocks',$v['id']);?>
<?php }
}

function supprimer_tags($texte, $rempl = "") {
	$texte = preg_replace(",<[^>]*>,US", $rempl, $texte);
	// ne pas oublier un < final non ferme
	// mais qui peut aussi etre un simple signe plus petit que
	$texte = str_replace('<', ' ', $texte);
	return $texte;
}

function couper($texte, $taille=50, $suite = '&nbsp;<small>...</small>') {
	if (!($length=strlen($texte)) OR $taille <= 0) return '';
	$offset = 400 + 2*$taille;
	while ($offset<$length
		AND strlen(preg_replace(",<[^>]+>,Uims","",substr($texte,0,$offset)))<$taille)
		$offset = 2*$offset;
	if (	$offset<$length
			&& ($p_tag_ouvrant = strpos($texte,'<',$offset))!==NULL){
		$p_tag_fermant = strpos($texte,'>',$offset);
		if ($p_tag_fermant<$p_tag_ouvrant)
			$offset = $p_tag_fermant+1; // prolonger la coupe jusqu'au tag fermant suivant eventuel
	}
	$texte = substr($texte, 0, $offset); /* eviter de travailler sur 10ko pour extraire 150 caracteres */

	// on utilise les \r pour passer entre les gouttes
	$texte = str_replace("\r\n", "\n", $texte);
	$texte = str_replace("\r", "\n", $texte);

	// sauts de ligne et paragraphes
	$texte = preg_replace("/\n\n+/", "\r", $texte);
	$texte = preg_replace("/<(p|br)( [^>]*)?".">/", "\r", $texte);

	// supprimer les traits, lignes etc
	$texte = preg_replace("/(^|\r|\n)(-[-#\*]*|_ )/", "\r", $texte);

	// supprimer les tags
	$texte = supprimer_tags($texte);
	$texte = trim(str_replace("\n"," ", $texte));
	$texte .= "\n";	// marquer la fin


	// couper au mot precedent
	$long = substr($texte, 0, max($taille-4,1));
	$u = 'u';
	$court = preg_replace("/([^\s][\s]+)[^\s]*\n?$/".$u, "\\1", $long);
	$points = $suite;

	// trop court ? ne pas faire de (...)
	if (strlen($court) < max(0.75 * $taille,2)) {
//		$points = '';
		$long = substr($texte, 0, $taille);
		$texte = preg_replace("/([^\s][\s]+)[^\s]*\n?$/".$u, "\\1", $long);
		// encore trop court ? couper au caractere
		if (strlen($texte) < 0.75 * $taille)
			$texte = $long;
	} else
		$texte = $court;

	if (strpos($texte, "\n"))	// la fin est encore la : c'est qu'on n'a pas de texte de suite
		$points = '';

	// remettre les paragraphes
	$texte = preg_replace("/\r+/", "\n\n", $texte);

	// supprimer l'eventuelle entite finale mal coupee
	$texte = preg_replace('/&#?[a-z0-9]*$/S', '', $texte);

	return quote_amp(trim($texte)).$points;
}

function quote_amp($u) {
	return preg_replace(
		"/&(?![a-z]{0,4}\w{2,3};|#x?[0-9a-f]{2,5};)/i",
		"&amp;",$u);
}

function showOGImage($image = false) {
	global $GLO_SITE;
	if($image) {
		$image = doImage($image,300,false,false,true);
	} else {
		$image = $GLO_SITE['url_site'].'images/logo_noir.png';
	}

	echo '<meta property="og:image" content="'.$image.'"/>'."\n";
}
function makeLien($url) {
	if(substr($url,0,3) == 'www')
		return 'http://'.$url;
	
	return $url;
}
function fullbasename($url) {
	if (strstr($url,'news.'))
		$url = 'news.htm';
	if (strstr($url,'about'))
		$url = 'about.htm';
	list($url) = explode(',',$url);
	return basename($url);
}
function my_htmlspecialchars($s) {
	if(strstr($s,'<')===false && strstr($s,'>')===false) {
		return $s;
	} else {
		return str_replace('<','&lt;',str_replace('>','&gt;',$s));
	}
}
function getIp() {
	return $_SERVER['REMOTE_ADDR'];
}
function searchLink($s) {
	global $GLO_SITE;
	return $GLO_SITE['url_site'].'results/'.urlencode($s);
}



function myparseini ($f) {
	if($tab = file($f)) {
		$out=array();
		foreach($tab as $line) {
			$line = trim($line);
			if((substr($line,0,1) == ';') || empty($line))
				continue;
			$tmp = explode('=',$line);
			$lib = trim($tmp[0]);
			unset($tmp[0]);
			$val = trim(implode('=',$tmp));
			$out[$lib]=$val;
		}
		return $out;
	}
}
function adminEdit($n,$id,$small=false) {
	global $GLO_DESC, $GLO_SITE;
	if(isAdmin() && getSetting('admin_links')){
		$title=$GLO_DESC[$n]['specs']['lib_edit'];
		if($small) {?>
&nbsp; <a href="admin.php?d=<?php echo $n;?>&id_data=<?php echo $id;?>" <?php echo popOpen(1024,768,'admin');?> title="<?php echo $title;?>"><img src="<?php echo $GLO_SITE['url_site'];?>images/edit.gif" alt="Edit"></a>
		<?php } else {?>
		<span class="admin">
			<a href="admin.php?d=<?php echo $n;?>&id_data=<?php echo $id;?>" <?php echo popOpen(1024,768,'admin');?> title="<?php echo $title;?>"><?php echo $title;?> &nbsp;<img alt="Edit" src="<?php echo $GLO_SITE['url_site'];?>images/edit.gif"></a>
		</span>
	<?php }?>
<?php }
}
function mSet($m) {
	$_SESSION['message']=$m;
}
function mGet() {
	if(isset($_SESSION['message']) && !empty($_SESSION['message'])) {
		$m=$_SESSION['message'];
		unset($_SESSION['message']);
		
		if(is_array($m)) {
			array_walk($m, 'htmlspecialchars');
			$m = '<li>'.implode('<li>',$m);
		} else {
			$m = htmlspecialchars($m);
		}
		echo writeMess($m);
	}

}
function writeMess($m) {
		return '<div onclick="this.style.display=\'none\';" id="message_info">'.$m.'</div>';
}
function jsMess($m) {
	if(!$m) return;
?>
<script>
	if(!parent.document.getElementById('message_info')) {
		parent.document.body.innerHTML+='<?php echo addslashes(writeMess($m));?>';
	} else {
		obj=parent.document.getElementById('message_info');
		obj.innerHTML='<?php echo addslashes($m);?>';
		obj.style.display='block';
	}
</script>
<?php
}
function finLoadMoteur($m) {
?>
<script>
	if(_img = parent.document.getElementById('load_moteur_<?php echo $m;?>')) {
		_img.src = _img.getAttribute('real_src');
	}
</script>
<?php
}
function gestDate($s) {
	$s = str_replace('&nbsp;',' ',$s);
	if(substr($s,-1) == 'h') {
		$v = floatval($s);
		$h1=3600;
		$s = date('d-m-Y',time()-$w1*$v);
		
	} else if(substr($s,-1) == 'd') {
		$v = floatval($s);
		$j1=24*3600;
		$s = date('d-m-Y',time()-$w1*$v);
		
	} else 	if(substr($s,-1) == 'w') {
		$v = floatval($s);
		$w1=7*3600*24;
		$s = date('d-m-Y',time()-$w1*$v);
		
	} else if((list($j,$m,$a)=explode(' ',$s)) && is_numeric($j) && !is_numeric($m) && is_numeric($a)) {
		$a = strlen($a) == 2 ? '20'.$a : $a;
		$s = date('d-m-Y',strtotime($j.' '.$m.' '.$a));
	} else if(($tab=explode(',',$s)) && count($tab) == 3) {
		list($m,$j) = explode(' ',trim($tab[1]));

		$s = date('d-m-Y',strtotime($j.' '.$m.' '.$tab[2]));
	} else if((list($m,$d,$a) = explode('/',$s)) && is_numeric($m) && is_numeric($d) && strstr($s,':')!==false) {
		$s = $m.'-'.$d.'-'.($a ? intval($a) : date('Y'));
	} else 	if((list($m,$d,$a) = explode('/',$s)) && is_numeric($m) && is_numeric($d)) {
		$s = $d.'-'.$m.'-'.($a ? intval($a) : date('Y'));
	} else if(strstr($s,'-')!==false && strstr($s,':')!==false) {
		list($a,$b) = explode(' ',$s);
		list($m,$d) = explode('-',$a);
		$s = $d.'-'.$m.'-'.date('Y');
	} else if(strstr($s,' ')!==false && strstr($s,'-')!==false) {
		list($a,$b) = explode(' ',$s);
		list($m,$d) = explode('-',$a);
		$s = $d.'-'.$m.'-'.$b;
	}
	$s = str_replace('  ',' ',$s);
//	$s = date('m-d-Y',strtotime($s));
	return $s;
}

function gestSize($s) {
	$s = strtoupper($s);
	$s = str_replace('MO','MB',$s);
	$s = str_replace('GO','GB',$s);
	$s = str_replace('IB','B',$s);
	$s = str_replace("G"," G",$s);
	$s = str_replace("M"," M",$s);
	$s = str_replace('  ',' ',$s);

	list($v,$u) = explode(' ',$s);
	if($v > 999 && $u == 'MB') {
		$s = round($v/1000,1).' GB';
	} else 	if($v < 1000 && $v > 100 && $u == 'MB') {
		$s = round($v,0).' MB';
	}

	
	return $s;
}


function ratio($s,$l) {
	if($l)
		return round($s/$l,1);
	else 
		return 'N/A';
}
function unzip($data) {
	return gzuncompress($data);
}
function zip($data) {
	return gzcompress($data);
}
function makeUrlSearch($search,$url) {
	return str_replace('#search#',urlencode(strip_tags($search)),$url);
}

function addbase($what,$data) {
	$url = $data[$what];
	$base = $data['moteur']['url'];
	return addbase2($url,$base);
}
function addbase2($url,$base) {
	if($url && strstr($url,'http:')===false)
		return str_replace("///","/",$base.'/'.$url);
	else 
		return $url;
}
function rwurl($what,$id=false,$extra=false) {
	$data = getSortData($what);
	if($id) {
		$lib = $data[$id]['titre']['en'] ? $data[$id]['titre']['en'] : $data[$id]['titre']['fr'];
	} else {
		list($tmp) = $data;
		$lib = $tmp['titre']['en'] ? $tmp['titre']['en'] : $tmp['titre']['fr'];
	}
	return $what.'-'.Url::clean($lib).($extra!==false ? '-'.$extra : '').'.html';

}
function isIe(){
	if (eregi('msie', $_SERVER['HTTP_USER_AGENT']) && !eregi('opera', $_SERVER['HTTP_USER_AGENT'])){
		return true;
	}
	return false;
}
function closeWindow($opener=false){
	?>
	<script>
	<?php if($opener){?>
		window.opener.<?php echo $opener;?>;
	<?php }?>
	window.close();
	</script>	
	<?php
}
function urlWeb($url){
	global $PATH_SITE;
	$url = str_replace($PATH_SITE,'',$url);
	return $url;
}

function pixlrUrl($img) {
	global $GLO_SITE, $PATH_SITE;
	$img = str_replace($PATH_SITE,'',$img);
	$params = array(
		'loc'=>'fr',
		'referrer'=>$GLO_SITE['url_site'],
		'exit'=>$GLO_SITE['url_site'].'pixlr.php?action=close',
		'title'=>base64_encode($img),		
		'method'=>'GET',
		'target'=>$GLO_SITE['url_site'].'pixlr.php',
//		'locktitle'=>'true',
//		'locktype'=>'true',
		'crendentials'=>'true',
	);
	$_params='';
	foreach($params as $k=>$v){
		$_params.='&'.$k.'='.urlencode($v);
	}
	$url = 'http://pixlr.com/express/?image='.$GLO_SITE['url_site'].$img.$_params;

	return $url;
}

function doImage($img,$w=false,$h=false,$cut='true',$return=false) {
	global $GLO_SITE, $PATH_SITE;
//	$img = str_replace('/cedricragot/','/',$img);
	$img = str_replace($PATH_SITE,'',$img);
	if($w && $h){
		$ret = 'img-'.$w.'-'.$h.'-'.$cut.'-'.toUrl($img);
	} else if ($h){
		$ret = 'img-y'.$h.'-'.toUrl($img);
	} else if ($w){
		$ret = 'img-x'.$w.'-'.toUrl($img);
	} else {
		$ret = $img;
	}
	$ret = $GLO_SITE['url_site'].$ret;
	if($return)
		return $ret;
	else 
		echo $ret;
}
function popOpen($w,$h,$name='window') {
	return 'onclick="window.open(this.href,\''.$name.'\',\'width='.$w.',height='.$h.',scrollbars=yes,menubar=no,resizable=no\');return false;"';
}
$tab_toolbar = array(
	'Titre'=>'h2',	
	'Paragraphe'=>'p',	
	'Gras'=>'b',	
	'Italique'=>'i',	
	'Souligné'=>'u',	
	'Grand'=>'big',	
	'Petit'=>'small',
	'Image'=>array('question'=>'URL du fichier image','balises'=>array('img src="0val0"',false)),	
	'Lien interne'=>array('question'=>'URL du lien','balises'=>array('a href="0val0"','a')),	
	'Lien externe'=>array('question'=>'URL du lien','balises'=>array('a target="_blank" href="0val0"','a')),	
);
function showToolBar($id_cible) {
	global $tab_toolbar;
	$html='<div class="toolbar">';
	foreach($tab_toolbar as $lib=>$c){
		if(is_array($c)) {
			$html.='<a href="javascript:alterTextSpecial(\''.$id_cible.'\',\''.htmlspecialchars($c['question']).'\',\''.htmlspecialchars($c['balises'][0]).'\',\''.htmlspecialchars($c['balises'][1]).'\')">'.$lib.'</a> &nbsp; ';
		} else {
			$html.='<a href="javascript:alterText(\''.$id_cible.'\',\''.$c.'\')">'.$lib.'</a> &nbsp; ';
		}
	}
	$html.='<a href="javascript:showPreview(\''.$id_cible.'\')">Aper&ccedil;u</a> &nbsp; ';
	$html.='<a href="javascript:growTextarea(\''.$id_cible.'\')">Agrandir</a>';
	$html.='</div><div class="preview" id="preview_'.$id_cible.'"></div>';
	return $html;
}
$cpt_help=0;
function dohelp($m){
	global $cpt_help;
	if($m) {
		echo '<div id="help_'.$cpt_help.'" class="help">'.$m.'</div>';
		$cpt_help++;
	}
}

function confirmLink(){
	return 'onclick="return confirm(\'Etes-vous s?r ?\')"';
}

function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function cleanName($txt) {
	$txt = stripAccents($txt);
	$txt = eregi_replace("\&[^\;]+\;", "-", $txt);
	$txt = virerCharsDeMerde($txt);
	$txt = eregi_replace("[^a-z0-9\.]", "-", $txt);
	$txt = eregi_replace("--", "-", $txt);
	$txt = eregi_replace("--", "-", $txt);
	while (substr($txt, 0, 1) == '-') {
		$txt = substr($txt, 1);
	}
	while (substr($txt, -1) == '-') {
		$txt = substr($txt, 0, strlen($txt) - 1);
	}
	return strtolower($txt);
}

function redir($url) {
	global $headerSent, $GLO_SITE;
	if($url == 'back'){
		$url = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $GLO_SITE['url_site'];
	}
	if($header && !$headerSent){
		showHeaderAdmin('Redirection...');
	}

	if(true) {
	?>	<script>window.open('<?php echo $url;?>','_self');</script>	<?php	
	} else {
	?><a href="<?php echo $url;?>"><?php echo $url;?></a><?php
	}
	if($header) {
		showDebug();
	}
	exit;
}
function mefDate($date) {
	return $date ? date('d/m/Y').' &agrave; '.date('H:i',$date) : '';
}

function removeDir($dir){
	if (!file_exists($dir)) return true;
	if (!is_dir($dir) || is_link($dir)) return unlink($dir);
		$d = dir($dir);
	while (false !== ($item = $d->read())) 	{
	if ($item == '.' || $item == '..') continue;
	if (!removeDir($dir . "/" . $item))	{
		if (!removeDir($dir . "/" . $item)) return false;
	};
	}
	return rmdir($dir);
}

function VerifierAdresseMail($adresse) { 
   $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
   if(preg_match($Syntaxe,$adresse)) 
      return true; 
   else 
     return false; 
}

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}

function sitemapdate($d=false) {
	$d = $d ? strtotime($d) : time();
    return date('Y-m-d H:i:s.0T',$d);
}

?>