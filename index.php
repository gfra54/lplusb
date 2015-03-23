<?php 
include "include/main.inc.php";
$GLOBALS['HOME']=true;

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
		<a style="height:220px;width:<?php echo round(220*$mode['ratio']);?>px;" data-mode="<?php echo $mode['mode'];?>" data-ratio="<?php echo $mode['ratio'];?>" href="<?php echo $proj->url();?>" class="slidin cell"><span class="text"><span class="in"><b><?php echo $proj->data['titre'];?></b><p><?php echo $chapo;?></p></span></span><img src="<?php echo Url::image($main_image,450);?>"></a>

	<?php 
	
	}}?>



<?php showFooter(true);?>