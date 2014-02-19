<?php
include "include/main.inc.php";
checkLogin();

include "admin/action.php";



showHeaderAdmin(($w ? 'Admin '.$GLOBALS['DESC'][$w]['specs']['lib'] : 'Administration du site').' - '.$GLOBALS['titre']);
showMenuAdmin();

if(!$w) { 
	showMenuAdmin('index');
} else {

	$Data = new Data($w);?>

    <h1><?php echo $Data->lib;?> <img class="link" alt="aide" title="Cliquez ici pour avoir de l'aide'" onclick="showHelp()" src="images/help.gif"></h1>
    <?php echo $Data->extra;?>
    <?php doHelp($Data->help);?>
    <?php doHelp($Data->help2);?>
    
    <p>&nbsp;</p>
    <?php if($id===false) {
    	include('admin/list.php');
    } else {
       	include('admin/edit.php');
	}
}


showFooterAdmin();?>

