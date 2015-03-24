<?php 
include "include/main.inc.php";
if($Project = new Data('projects',$_GET['id_project'])) {

showHeader($Project->data['titre'],false,false,true);?>

	<div class="slider-frame">
	<ul id="slider">
	<li class="nav" id="prev"></li>
	<li class="nav" id="next"></li>
	<li id="zone">
		<ul id="sliding">
		<?php $legendes = '';$cpt=0;foreach($Project->getDocs() as $id_doc => $doc) {$cpt++;
			if($doc->text) {
				$legendes.='<span class="legende legende-hidden legende-'.$cpt.'">'.$doc->text.'</span>';
				$legende = '<span class="image-legende">'.$doc->text.'</span>';
			} else {
				$legende='';
			}
			?><li class="slide" data-id="<?php echo $cpt;?>"><span class="slide-in"><span class="slide-content"><?php echo $legende;?><img src="<?php echo Url::image($doc,false,800);?>"></span></span></li><?php
		}?>
		</ul>
	</li>
	</ul>
	</div>
	<div id="description">
		<span class="count"></span>
		<b><?php echo $Project->data['titre'];?></b><br>
		<span class="legende legende-0"><?php echo nl2br($Project->data['texte']);?></span>
		<?php echo $legendes;?>

	</div>

	<?php $cpt=0;foreach($Project->getDocs() as $id_doc => $doc) {$cpt++;

		$chapo = $doc->text;
		if(!$chapo) {
			$chapo = $Project->data['chapo'];
		}
		if(!$chapo) {
			$chapo = couper($Project->data['texte'],100);
		}
		$mode = mode_image($doc);
	?>
		<a style="height:<?php echo $GLOBALS['MIN_CELL_HEIGHT'];?>px;width:<?php echo round($GLOBALS['MIN_CELL_HEIGHT']*$mode['ratio']);?>px;" data-mode="<?php echo $mode['mode'];?>" data-ratio="<?php echo $mode['ratio'];?>" href="#i<?php echo $cpt;?>" class="toup cell project">
		<span class="text"><span class="in"><b><?php echo $Project->data['titre'];?></b><p><?php echo $chapo;?></p></span></span>
		<img src="<?php echo Url::image($doc,450);?>">
		</a>

	<?php }?>


<?php showFooter(true);
}?>