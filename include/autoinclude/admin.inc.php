<?php 
function showHeaderAdmin($titre=false,$extra=false) {
	$headerSent=true;
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titre ? $titre : 'Admin';?></title>
<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/toolbar.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="uploadify/jquery.uploadify.min.js"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css" />
<!--
<script type="text/javascript" src="lightbox/js/prototype.js"></script>
<script type="text/javascript" src="lightbox/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="lightbox/js/lightbox.js"></script>
<link rel="stylesheet" href="lightbox/css/lightbox.css" type="text/css" media="screen" />
-->
<script language="JavaScript" type="text/javascript" src="js/tool-man/core.js"></script>
<script language="JavaScript" type="text/javascript" src="js/tool-man/events.js"></script>
<script language="JavaScript" type="text/javascript" src="js/tool-man/css.js"></script>

<script language="JavaScript" type="text/javascript" src="js/tool-man/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="js/tool-man/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="js/tool-man/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="js/tool-man/cookies.js"></script>
<script language="JavaScript" type="text/javascript" src="js/dodrag.js"></script>
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
                <p>[ <a href="admin.php?w=<?php echo $k;?><?php echo isset($v['specs']['unique']) ? '&id_data='.$v['specs']['unique'] : '';?><?php echo isset($v['specs']['default_sort']) ? $v['specs']['default_sort'] : '';?>" title="<?php echo $v['specs']['help'];?>" <?php echo $_GET['w'] == $k ? 'class="selected"' : '';?>><?php echo $v['specs']['lib'];?></a> ] &nbsp; <small><?php echo $v['specs']['help'];?></small>
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
                <a href="admin.php?w=<?php echo $k;?><?php echo isset($v['specs']['unique']) ? '&id_data='.$v['specs']['unique'] : '';?><?php echo isset($v['specs']['default_sort']) ? $v['specs']['default_sort'] : '';?>" title="<?php echo $v['specs']['help'];?>" <?php echo $_GET['w'] == $k ? 'class="selected"' : '';?>><?php echo $v['specs']['lib'];?></a> &nbsp;
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
			$tmp = getData($w);
			$datas=array();
			foreach($tmp as $k=>$v) {
				$datas[$v['id']]=$v[getSpec($w,'lib_field')];
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
					$value = explode(',',$value);
					if(is_array($datas)){foreach($datas as $key=>$val){
						$html.='<div><label><input id="'.$_id.'_'.$key.'" name="'.$name.($options['links'] ? '[links]['.$w.']' : '').'[]" type="checkbox" value="'.$key.'" '.(in_array($key,$value)!==false ? 'checked' : '').'> '.$val.'</label></div>';	
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
