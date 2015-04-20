<?php

function setMeta($w,$data){
	if($data) {
		$GLOBALS['META'][$w] = $data;
	}
}

function setCanonical($_url){
	list($uri) = explode('?',basename($_SERVER['REQUEST_URI']));
	if($_url != $uri) {
		teleport($_url);
	}
	$GLOBALS['CANONICAL'] = $_url;
}

function canonical($url=false){
	if($url){
		$url = strstr($url,'http:')===false ? $GLOBALS['URL_SITE'].$url : $url;
	
		?>
		<meta property="og:url" content="<?php echo $url;?>">
		<link rel="canonical" href="<?php echo $url;?>">
<?php
	}
}

function showHeader($titre=false,$desc=false,$main_image=false,$_sideLeft=false) {
	if(isset($GLOBALS['META']['description'])){
		$desc = $GLOBALS['META']['description'];
	}
	if(isset($GLOBALS['META']['keywords'])){
		$keywords = $GLOBALS['META']['keywords'];
	}
	$ajax=isset($_GET['ajax']);
if(!$ajax) {
?>
<!doctype html>
<html lang="fr-FR">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset=utf-8>
	<title><?php echo $titre ? $titre.' - '.$GLOBALS['nom_site'] : $GLOBALS['nom_site'];?></title>
	<meta name="description" content="<?php echo strip_tags($desc ? $desc : getSetting('desc_site'));?>" />
	<meta name="keywords" content="<?php echo strip_tags($keywords ? $keywords : getSetting('keywords'));?>" />
	<?php echo $compat;?>
	<?php include_css();?>
	<?php showOGImage($main_image);?>
	<?php canonical($GLOBALS['CANONICAL']);?>
	<script>//document.write('<style>#content {opacity:0;}</style>');</script>
</head>
<body <?php echo $GLOBALS['HOME'] ? 'id="home"' : '';?>>
<?php }?>

<div id="wrapper">
	<div id="header">
		<a href="<?php echo $GLOBALS['url_site'];?>" id="logo_baseline"><img src="images/logo.svg"></a>
		<hr>
	</div>
	<div class="ligne"></div>
	<div id="contentWrap">
		<div id="nav">
			<?php 
			$selected = false;
			$Projects = new Data('projects');

			$Rubriques = new Data('rubriques');
			foreach($Rubriques->get() as $id_rubrique=>$Rubrique){
					$projects = array();
					foreach($Projects->get() as $k=>$Project){
						if(isset($Project->data['rubriques'][$id_rubrique])){
							$projects[$k]=$Project;
						}
					}

					if(count($projects)>0){?>
				<a class="main"><?php echo $Rubrique->data['titre'];?></a>
				<div class="wrap <?php if(!$selected && isset($projects[$_GET['id_project']])){ echo 'visible'; $selected=true;}?>" id="projects_<?php echo $id_rubrique;?>">
					<?php foreach ($projects as $k => $Project) {?>
						<a class="slidin <?php echo $_GET['id_project'] == $k ? 'selected' : '';?>" href="<?php echo $Project->url();?>"><?php echo $Project->data['titre'];?></a>
					<?php }?>
				</div>
			<?php }}?>

			
			<div id="apropos">
				<?php $menu = explode("\n",getSetting('menu'));
				foreach($menu as $k=>$v){
				if(strstr($v, ':')!==false){
					list($w,$id) = explode(':',$v);
					$Link = new Data($w,$id);
					$lib = $Link->data['titre'];
					$url = $Link->data['uri'];
				} else {
					list($lib,$url) = explode('=>',$v);
				}
				?>
				<a href="<?php echo $url;?>" class="slidin"><?php echo $lib;?></a>
				<?php }?>
			</div>
		</div>

		<div id="content">
<?php
}

function showFooter($home=false) {
	$ajax=isset($_GET['ajax']);
?>


		</div>
		<hr>
	</div>
</div>
<?php if(!$ajax){?>
</body>
</html>
<?php
	include_js();
	if(ENV == 'DEV') {
		?><style>body:before{display:block;}</style><?php
	} else {
		?><style>body:before{display:none;}</style><?php
	}
}
}




