<?php 

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


function showHeaderAdmin($titre=false,$extra=false) {
	$headerSent=true;
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titre ? $titre : 'Admin';?></title>
<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/admin/admin.css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/admin/admin.js"></script>
<script type="text/javascript" src="js/admin/toolbar.js"></script>
<script type="text/javascript" src="uploadify/jquery.uploadify.min.js"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css" />

<script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/jquery.fancybox.js?v=2.1.5"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="fancybox/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<?php include_js('js/tool-man/*');?>

<script language="JavaScript" type="text/javascript" src="js/admin/dodrag.js"></script>


</head>
<body <?php echo $extra ? $extra : false;?>>        
<div id="float_logo"></div>
<?php }


function showFooterAdmin() {
?>
</body>
</html>
<?php
}

function showMenuAdmin($selected=false){
	if($selected=='index') {?>
	<blockquote>
        <div style="float:right">
			<a href="<?php echo $GLOBALS['url_site'];?>admin.php?vidercache"><small>Vider cache</small></a>
			<br>
			<a href="<?php echo $GLOBALS['url_site'];?>admin.php?create"><small>Créer les tables</small></a>
		</div>
        <?php foreach($GLOBALS['DESC'] as $k => $v) {?>
                <p>[ <a href="admin.php?w=<?php echo $k;?><?php echo isset($v['specs']['unique']) ? '&id='.$v['specs']['unique'] : '';?><?php echo isset($v['specs']['default_sort']) ? $v['specs']['default_sort'] : '';?>" title="<?php echo $v['specs']['help'];?>" <?php echo $_GET['w'] == $k ? 'class="selected"' : '';?>><?php echo $v['specs']['lib'];?></a> ] &nbsp; <small><?php echo $v['specs']['help'];?></small>
                </p>
        <?php }?>
        <p>[ <a href="<?php echo $GLOBALS['url_site'];?>">Retour au site</a> ] &nbsp; <small>Voir le site</small></p>
        <p>[ <a href="?logout">Quitter</a> ] &nbsp; <small>Se d&eacute;connecter</small></p>

	</blockquote>
	<?php } else {
?>
	<div id="menu">
		Login : <?php echo $_SESSION['login']['name'].' ('.$_SESSION['login']['login'].')';?> &nbsp; 
		[
        <a href="admin.php" <?php echo basename($_SERVER['REQUEST_URI']) == 'admin.php' ? 'class="selected"' : "";?>>Accueil</a> &nbsp; 
        <?php foreach($GLOBALS['DESC'] as $k => $v) {?>
                <a href="admin.php?w=<?php echo $k;?><?php echo !empty($v['specs']['unique']) ? '&id='.$v['specs']['unique'] : '';?><?php echo isset($v['specs']['default_sort']) ? $v['specs']['default_sort'] : '';?>" title="<?php echo $v['specs']['help'];?>" <?php echo $_GET['w'] == $k ? 'class="selected"' : '';?>><?php echo $v['specs']['lib'];?></a> &nbsp;
        <?php }?>
        
<!--        <a href="<?php echo $GLOBALS['url_site'];?>admin.php?vidercache">Vider cache</a> &nbsp;-->
        <a href="<?php echo $GLOBALS['url_site'];?>">Site</a> &nbsp;
        <a href="?logout">Quitter</a>
        ]        
	</div>
<?php
	}
}

function getInputForm($type,$name,$value=false,$id=false,$class=false,$do_lang=false,$options=array()) {

	global $GLO_DEFAULT_LANG, $GLO_COULEURS;
	$_id = $id;
	$id = $id ? 'id="'.$id.'"' : '';
	$name = 'form['.$name.']';
	$extra="";
	$datas=false;
	if(strstr($type,'(')!==false){
		list($type,$datas) = explode('(',$type);
		list($datas) = explode(')',$datas);
		if(strstr($datas,',')!==false) {
			$tmp = explode(',',$datas);
			$datas=array();
			foreach($tmp as $k=>$v){
				list($a,$b) = explode('=>',$v);
				$datas[trim($a)]=trim($b);
			}
		} else {
			$w=$datas;
			$TmpData = new Data($w);
			$tmp = $TmpData->get();
			$datas=array();
			foreach($tmp as $k=>$v) {
				$datas[$k]=$v->data[$TmpData->getSpec('lib_field')];
			}
		}
	}
	if($do_lang) {
		$extra = 'onmousemove="saveCaseLang(this)" onkeyup="saveCaseLang(this)" style="background-image:url('.getLangImage($GLO_DEFAULT_LANG).')"';
		$class.=' lang';
	}


	$class = $class ? 'class="'.$class.'"' : '';

	switch($type) {
		default:
			$html='<input type="text" name="'.$name.'" '.$id.' '.$class.' '.$extra.' value="'.htmlspecialchars($value).'">';
		break;
		case "couleur_texte_home":
		case "couleur_texte_home_orig":
			$value = $type == 'couleur_texte_home_orig' ? $value : getCouleurTexteHome();
			$html='<select '.$id.' '.$class.' name="'.($type == 'couleur_texte_home_orig' ? $name :'couleur_texte_home').'">';
			foreach($GLO_COULEURS as $k=>$v) {
				$html.='<option style="background:'.$k.';color:'.$v['color'].'" value="'.$k.'" '.($k == $value ? 'selected' : '').'>'.$v['lib'].'</option>';
			}
			$html.='</select>';
		break;
		case"password":
			$html='<input type="password" name="'.$name.'" '.$id.' '.$class.' '.$extra.' value="'.htmlspecialchars($value).'">';
		break;
		case"icon":
			$html='<input type="file" name="'.$name.'" '.$id.'>';
		break;
		case"hidden":
			$html='<input type="hidden" name="'.$name.'" '.$id.' value="'.htmlspecialchars($value).'">';
		break;
		case"date":
			$d=$m=$Y=$H=$i=false;
			if($value>0) {
				$d=date('d',$value);
				$m=date('m',$value);
				$Y=date('Y',$value);
				$H=date('H',$value);
				$i=date('i',$value);
			}
			$html='<input class="form0" type="text" name="'.$name.'[d]" size="2" value="'.$d.'">';
			$html.=' / <input class="form0" type="text" name="'.$name.'[m]" size="2" value="'.$m.'">';
			$html.=' / <input class="form0" type="text" name="'.$name.'[Y]" size="4" value="'.$Y.'">';
			$html.='&nbsp; <input class="form0" type="text" name="'.$name.'[H]" size="2" value="'.$H.'">';
			$html.=' : <input class="form0" type="text" name="'.$name.'[i]" size="2" value="'.$i.'">';
			$html.='&nbsp; <small><a href="javascript:js_now()">Maintenant</a> <a href="javascript:js_now(1)">Demain</a> <a href="javascript:js_now(prompt(\'Dans combien de jour ?\'))">Dans X jours</a> </small>';
		break;
		case"checkbox":			
			$html='<input type="hidden" name="'.$name.'" value="0">';
			$html.='<input type="checkbox" name="'.$name.'" '.$id.' '.($value ? 'checked' : '').' value="1">';
		break;
		case "textarea":
			if($do_lang){
				$extra=str_replace('onkeyup="','onkeyup="showPreview(this.id,true);',$extra);
			} else {
				$extra='onkeyup="showPreview(this.id,true);"';
			}
			$html='<div class="wrap"><textarea name="'.$name.'" '.$id.' '.$class.' '.$extra.'>'.htmlspecialchars($value).'</textarea>'.showToolBar($_id).'</div>';
		break;
		case "smalltextarea":
			if($do_lang){
				$extra=str_replace('style="','style="height:75px;',$extra);
				$extra=str_replace('onkeyup="','onkeyup="showPreview(this.id,true);',$extra);
			} else {
				$extra='style="height:75px" onkeyup="showPreview(this.id,true);"';
			}

			$html='<div class="wrap"><textarea name="'.$name.'" '.$id.' '.$class.' '.$extra.'>'.htmlspecialchars($value).'</textarea>'.showToolBar($_id).'</div>';
		break;
		case "rawtextarea":
			$extra='style="height:75px"';
			$html='<div class="wrap"><textarea name="'.$name.'" '.$id.' '.$class.' '.$extra.'>'.htmlspecialchars($value).'</textarea></div>';
		break;
		case "select":
			if($options['multiple']) {
				$html='<div>';
				if($options['id_data']=='new'){
					$html.= '<input type="submit" class="bouton" value="Cliquez ici pour faire une association de '.getSpec($w,'lib').'">';
				} else {
//					$value = explode(',',$value);
					if(is_array($datas)){foreach($datas as $key=>$val){
						$html.='<div><label><input id="'.$_id.'_'.$key.'" name="'.$name.($options['links'] ? '[links]['.$w.']' : '').'[]" type="checkbox" value="'.$key.'" '.(isset($value[$key]) ? 'checked' : '').'> '.$val.'</label></div>';	
					}}
				}
				$html.='</div>';
			} else {
				$html='<select  name="'.$name.'" '.$id.' '.$class.'>';
				if(is_array($datas)){foreach($datas as $key=>$val){
					$html.='<option value="'.$key.'" '.(''.$key === ''.$value ? 'selected' : '').'>'.$val.'</option>';	
				}}
				$html.='</select>';
			}
		break;
/*		case "select_rubriques":
			$html='<select name="'.$name.'" '.$id.' '.$class.'><option></option>';
			$rubs = getData('rubriques');
			if(is_array($rubs)) {
			foreach($rubs as $key=>$val){
				$html.='<option value="'.$key.'" '.(''.$key === ''.$value ? 'selected' : '').'>'.$val['titre'].'</option>';	
			}}
			$html.='</select>';
		break;*/
	}
	return $html;
}
