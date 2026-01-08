<?php
include_once __DIR__.'/../../init.php';
//if(!isset($_SESSION['sess_idkor'])){header("Location: ".HOME_ADDRESS."?message=Morate se ulogovati!");exit;}

$cronClass = new CronClass();
//$cron = new Cron();//model
$parameter="cron_xml";
$response = $cronClass->$parameter(NULL);

?>
