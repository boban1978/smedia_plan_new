<?php
include_once __DIR__.'/../../init.php';



var_dump($argv[1]);
exit;


echo $username."hghhhh";
exit;


//if(!isset($_SESSION['sess_idkor'])){header("Location: ".HOME_ADDRESS."?message=Morate se ulogovati!");exit;}

$cronClass = new CronClass();
//$cron = new Cron();//model
$parameter="cron_reklame";
$response = $cronClass->$parameter(NULL,NULL);


?>
