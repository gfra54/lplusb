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
	?>
		<?php if($cpt == 9){?>
			<hr><div class="wrap">
		<?php } else if($cpt==13) {?>
			</div>
		<?php }?>
		<a href="<?php echo $proj->url();?>" class="slidin <?php echo $cpt==13 ? 'big' : '';?> cell"><span class="text"><b><?php echo $proj->data['titre'];?></b><p><?php echo $chapo;?></p></span><img src="<?php echo Url::image($main_image,450);?>"></a>
		<?php if($cpt == 13){?>
			<hr>
		<?php }?>

	<?php }}?>



<?php showFooter(true);?>