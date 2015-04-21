<?php 
include "include/main.inc.php";
if($Page = new Data('pages',$_GET['id'])) {

showHeader($Page->data['titre'],false,false,true);?>
<div class="page">

<?php echo nl2br($Page->data['texte']);?>

</div>
<?php showFooter(true);
}?>