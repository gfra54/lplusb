<?php 
include "include/main.inc.php";

$id_data = isset($_GET['id_data']) ? $_GET['id_data'] : 5;
$section = getIdAbout($id_data);
showHeader();
showEntete();

$about = getAbout();

?>
<div id="left_col" class="fixe alphaHide" alpha="2">
<?php foreach($about as $k=>$v){?>
	<div><a class="upp <?php echo $section == $v['id'] ? 'selected' : '';?>" href="<?php echo rwurl('about',$v['id']);?>" <?php echo $v['id'] == $section ? 'class="selected"' : '';?>><?php echo lang($v['titre']);?></a></div>
<?php }?>
	<div><a class="upp" href="biblio.php"><?php echo lang('biblio');?></a></div>
</div>
<?php
?>
<div id="center_col" ie="no" class="fixe_left_col alphaHide" alpha="3">
<?php if(isset($about[$section])){?>
	<h3><?php echo lang($about[$section]['titre']);?></h3>
	<?php adminEdit('about',$section);?>
	<p><?php echo lang($about[$section]['texte']);?></p>
<?php }?>
</div>

<div id="right_col" alpha="4">
	<?php if($img = getMainImage('about',$about[$section],false)) {?>
		<img src="<?php echo doImage($img,295);?>">
	<?php }?>
	<?php if(($pdf = getLastPdf('about',$section)) && islogged()) {?>
		<a href="<?php echo pathToUrl($pdf);?>"><img class="icon" src="images/pdf.png"></a>
	<?php }?>
</div>
<?php

showFooter();
?>