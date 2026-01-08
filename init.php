<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('max_execution_time', 3000);

define('ROOT', dirname(__FILE__));

require_once ROOT.'/Config/define.php';
require_once ROOT.'/Config/Enum.php';
require_once ROOT.'/Config/Functions.php';

function __autoload($class_name) {
     //echo($class_name);
    if(strpos($class_name, 'Core') !== false) {
        include ROOT.'/App/Core/'.$class_name.'.php';
    } elseif(strpos($class_name, 'Class') !== false) {
        include ROOT.'/App/Class/'.$class_name.'.php';
    } elseif(strpos($class_name, 'Filter') !== false) {
        include ROOT.'/App/FilterClass/'.$class_name.'.php';    
    } else {
        include ROOT.'/App/Models/'.$class_name.'.php';
    }
    //include_once ROOT.'/Config/Enum.php';
}

/*
if(defined('RUNNING_BY_SERVER')){
    var_dump(__DIR__);
    exit;
}*/

require_once __DIR__.'/Config/init.php';


