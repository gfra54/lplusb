<?php 
include "include/main.inc.php";
if($Project = new Data('projects',$_GET['id_project'])) {

showHeader($Project->data['titre'],false,false,true);?>

	<ul id="slider">
	<li class="nav" id="prev"></li>
	<li class="nav" id="next"></li>
	<li id="zone">
		<ul id="sliding">
		<?php $cpt=0;foreach($Project->getDocs() as $id_doc => $doc) {$cpt++;
			?><li class="slide"><span class="slide-in"><img src="<?php echo Url::image($doc,false,800);?>"></span></li><?php
		}?>
		</ul>
	</li>
	</ul>
	<div id="description">
		<b><?php echo $Project->data['titre'];?></b><br>
		<?php echo nl2br($Project->data['texte']);?>

	</div>

	<?php $cpt=0;foreach($Project->getDocs() as $id_doc => $doc) {$cpt++;

		$mode = mode_image($doc);
	?>
		<a style="height:220px;width:<?php echo round(220*$mode['ratio']);?>px;" data-mode="<?php echo $mode['mode'];?>" data-ratio="<?php echo $mode['ratio'];?>" href="#i<?php echo $cpt;?>" class="toup cell project"><img src="<?php echo Url::image($doc,450);?>"></a>

	<?php }?>


<?php showFooter(true);
}?>