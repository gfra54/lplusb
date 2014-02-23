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
require_once "settings.".$_SERVER['HTTP_HOST'].".php";

/**
 * Settings start
 */
require_once "settings.inc.php";
initSettings();

/**
 * autres includes
 */
require_once './include/includes.inc.php';

/**
 * Debug start
 */
$debug = isAdmin() || isset($_GET['debug']);

