<?php 
include "include/main.inc.php";
$GLOBALS['HOME']=true;

//$projects = getSortData('projects','id',false,'pub');

$projects = new Data('projects');

showHeader(false,false,false,true);?>


	<?php $cpt=0;foreach($projects->get() as $id_proj => $proj) {$cpt++;
		$main_image = $proj->getLogo(300);
	?>
		<?php if($cpt == 9){?>
			<hr><div class="wrap">
		<?php } else if($cpt==13) {?>
			</div>
		<?php }?>
		<a href="" class="<?php echo $cpt==13 ? 'big' : '';?> cell"><img src="<?php echo $main_image;?>"></a>
		<?php if($cpt == 13){?>
			<hr>
		<?php }?>

	<?php }?>



<?php showFooter(true);?>