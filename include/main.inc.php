<?php
session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$GLOBALS['HOME']=false;
$GLOBALS['MIN_CELL_HEIGHT']=200;

$debug=true;

/**
 * BDD Start
 */
require_once "class.mysql.php";
require_once "settings.".$_SERVER['HTTP_HOST'].".php";

/**
 * Settings start
 */
require_once "settings.inc.php";
initSettings();

/**
 * Auto include
 */
foreach (glob("./include/autoinclude/*.php") as $filename){
    include $filename;
}


/**
 * Debug start
 */
$debug = isAdmin() || isset($_GET['debug']);

