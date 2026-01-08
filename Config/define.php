<?php

if(!defined('ROOT')){die('umri muski!');}

define('DB_TYPE', 'mysql');
define('HOSTNAME', 'localhost');//mysql host
define('USERNAME', 'root');//mysql username
define('PASSWORD', '!Qwerty123#');//mysql password
define('DBNAME', 'mediaplan');// name of mysql database

//define('CENA', 0.2);// name of mysql database

//Home adresa sajta
//define('HOME_ADDRESS', 'http://192.168.1.250/smedia/');
define('HOME_ADDRESS', 'http://'.$_SERVER['HTTP_HOST'].'/smedia/');

?>
