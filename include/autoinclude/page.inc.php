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
?>
<!doctype html>
<html lang="fr-FR">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset=utf-8>
	<title><?php echo $titre ? $titre.' - '.$GLOBALS['nom_site'] : $GLOBALS['nom_site'];?></title>
	<meta name="description" content="<?php echo strip_tags($desc ? $desc : getSetting('desc_site'));?>" />
	<meta name="keywords" content="<?php echo strip_tags($keywords ? $keywords : getSetting('keywords'));?>" />
	<?php echo $compat;?>
	<link href='http://fonts.googleapis.com/css?family=Actor' rel='stylesheet' type='text/css'>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script-->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<?php showOGImage($main_image);?>
	<?php canonical($GLOBALS['CANONICAL']);?>

</head>
<body <?php echo $GLOBALS['HOME'] ? 'id="home"' : '';?>>

<div id="wrapper">
	<div id="header">
		<a href="<?php echo $GLOBALS['url_site'];?>" id="logo_baseline"><img src="images/logo_baseline.png"></a>
		<hr>
	</div>
	<div class="ligne"></div>
	<div id="contentWrap">
		<div id="nav">
			<?php 
			$selected = false;
			$Projects = new Data('projects');

			$Rubriques = new Data('rubriques');
			foreach($Rubriques->get() as $id_rubrique=>$Rubrique){?>
				<a class="main"><?php echo $Rubrique->data['titre'];?></a>
				<?php 
					$projects = array();
					foreach($Projects->get() as $k=>$Project){
						if(isset($Project->data['rubriques'][$id_rubrique])){
							$projects[$k]=$Project;
						}
					}?>

				<div class="wrap <?php if(!$selected && isset($projects[$_GET['id_project']])){ echo 'visible'; $selected=true;}?>" id="projects_<?php echo $id_rubrique;?>">
					<?php foreach ($projects as $k => $Project) {?>
						<a class="<?php echo $_GET['id_project'] == $k ? 'selected' : '';?>" href="<?php echo $Project->url();?>"><?php echo $Project->data['titre'];?></a>
					<?php }?>
				</div>
			<?php }?>

			
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
				<a href="<?php echo $url;?>"><?php echo $lib;?></a>
				<?php }?>
			</div>
		</div>

		<div id="content">
<?php
}

function showFooter($home=false) {
?>


		</div>
		<hr>
	</div>
</div>

</body>
</html>
<?php
}




