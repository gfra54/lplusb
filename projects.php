<?php 
include "include/main.inc.php";
if($Project = new Data('projects',$_GET['id_project'])) {

showHeader($Project->data['titre'],false,false,true);?>

	<ul id="slider">
	<li class="nav" id="prev"></li>
	<li id="zone">
		<ul id="sliding">
		<?php $cpt=0;foreach($Project->getDocs() as $id_doc => $doc) {$cpt++;
			?><li class="slide"><img src="<?php echo Url::image($doc,false,800);?>"></li><?php
		}?>
		</ul>
	</li>
	<li class="nav" id="next"></li>
	</ul>
	<div id="description"></div>

	<?php $cpt=0;foreach($Project->getDocs() as $id_doc => $doc) {$cpt++;

	?>
		<?php if($cpt == 9){?>
			<hr><div class="wrap">
		<?php } else if($cpt==13) {?>
			</div>
		<?php }?>
		<a href="#i<?php echo $cpt;?>" class="<?php echo $cpt==13 ? 'big' : '';?> cell"><img src="<?php echo Url::image($doc,300);?>"></a>
		<?php if($cpt == 13){?>
			<hr>
		<?php }?>

	<?php }?>


<?php showFooter(true);
}?>