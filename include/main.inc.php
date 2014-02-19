<?php
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$GLOBALS['HOME']=false;

$debug=true;

/**
 * BDD Start
 */
require_once "class.mysql.php";
//$GLOBALS['bdd'] = new MYSQL('lplusb','lplusb','larryDirt','mysql51-114.perso');
$GLOBALS['bdd'] = new MYSQL('lplusb','root','','localhost');


/**
 * Settings start
 */
require_once "settings.inc.php";
initSettings();

/**
 * Auto include
 */
foreach (glob($GLOBALS['chemin_site']."include/autoinclude/*.php") as $filename){
    include $filename;
}


/**
 * Debug start
 */
$debug = isAdmin() || isset($_GET['debug']);

