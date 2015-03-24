<?php 
include "include/main.inc.php";
$GLOBALS['HOME']=true;


if(isset($_GET['test'])) {
	$_SESSION['ok']=true;
	header('Location:'.str_replace('test','',$_SERVER['REQUEST_URI']));
}
if(!isAdmin() && !isset($_SESSION['ok'])) {
	echo '<body style="background:#ccc url(images/logo.svg) no-repeat center center;margin:0;padding:0;text-align: center;padding-top: 40%;font-family:arial;font-size:0.6em">Work in progress<br>Come back soon !</body>';
	die();
}

//$projects = getSortData('projects','id',false,'pub');

$projects = new Data('projects');

showHeader(false,false,false,true);?>


	<?php $cpt=0;foreach($projects->get() as $id_proj => $proj) {if($proj->data['sticky']){$cpt++;
		$main_image = $proj->getLogo(300);
		if(!$proj->data['chapo']){
			$Rubriques = new Data('rubriques');
			$rubs = $Rubriques->get();
			$chapo='';
			foreach ($rubs as $key => $value) {
				if(isset($proj->data['rubriques'][$key])){
					$chapo.=$chapo ? ', ' : '';
					$chapo.=$value->data["titre"];
				}
			}
		} else {
			$chapo = $proj->data['chapo'];
		}
		$mode = mode_image($main_image);
	?>
		<a style="height:<?php echo $GLOBALS['MIN_CELL_HEIGHT'];?>px;width:<?php echo round($GLOBALS['MIN_CELL_HEIGHT']*$mode['ratio']);?>px;" data-mode="<?php echo $mode['mode'];?>" data-ratio="<?php echo $mode['ratio'];?>" href="<?php echo $proj->url();?>" class="slidin cell"><span class="text"><span class="in"><b><?php echo $proj->data['titre'];?></b><p><?php echo $chapo;?></p></span></span><img src="<?php echo Url::image($main_image,450);?>"></a>

	<?php 
	
	}}?>



<?php showFooter(true);?>