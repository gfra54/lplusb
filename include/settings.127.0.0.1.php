<?php
$GLOBALS['bdd'] = new MYSQL('lplusb','root','','localhost');
$GLOBALS['CUR_SETTINGS']=2;

/**
 * Auto include
 */
$php = array('<?php');
foreach (glob("./include/autoinclude/*.php") as $filename){
    $php[]='include "'.$filename.'";';
}

file_put_contents('./include/includes.inc.php',implode("\n",$php));

