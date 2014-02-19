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
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
	<meta charset=windows-1252>
	<title><?php echo $titre ? $titre.' - '.$GLOBALS['nom_site'] : $GLOBALS['nom_site'];?></title>
	<meta name="description" content="<?php echo strip_tags($desc ? $desc : getSetting('desc_site'));?>" />
	<meta name="keywords" content="<?php echo strip_tags($keywords ? $keywords : getSetting('keywords'));?>" />
	<?php echo $compat;?>
	<link href='http://fonts.googleapis.com/css?family=Actor' rel='stylesheet' type='text/css'>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<?php showOGImage($main_image);?>
	<?php canonical($GLOBALS['CANONICAL']);?>

</head>
<body <?php echo $GLOBALS['HOME'] ? 'id="home"' : '';?>>

<div id="wrapper">
	<div id="header">
		<a href="/" id="logo_baseline"><img src="images/logo_baseline.png"></a>
		<hr>
	</div>
	<div class="ligne"></div>
	<div id="nav">
		<a class="main" href="">Réalisation</a>
		<a class="main" href="">Catégories</a>
		<a class="main" href="">Clients</a>
		<a href="">Communauté d'agglomération de l'aéroport du b ourget</a>
		<a href="">catherine andré</a>
		<a href="">tnb</a>
		<a href="">hermes</a>
		<a href="">catherine andré</a>
		<a href="">tnb</a>
		<a href="">hermes</a>
		<a href="">catherine andré</a>
		<a href="">tnb</a>
		<a href="">hermes</a>
		<a href="">catherine andré</a>
		<a href="">tnb</a>
		<a href="">hermes</a>
		
		<div id="apropos">
			<a href="">à propos</a>
			<a href="">savoir faire</a>
			<a href="">contact</a>
		</div>
	</div>

	<div id="content">
<?php
}

function showFooter($home=false) {
?>


	</div>
</div>

</body>
</html>
<?php
}




