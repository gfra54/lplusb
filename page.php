<?php 
include "include/main.inc.php";
if($Page = new Data('pages',$_GET['id'])) {

showHeader($Page->data['titre'],false,false,true);?>


<?php echo mynl2br($Page->data['texte']);?>


<?php showFooter(true);
}?>