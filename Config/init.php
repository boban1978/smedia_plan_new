<?php
if(!defined('ROOT')){die('umri muski!');}


$korisnik_id_pom=0;
if(defined('RUNNING_BY_SERVER')){

}else{

    if(!isset($_SESSION['sess_idkor'])){header("Location: ".HOME_ADDRESS."?message=Morate se ulogovati!");exit;}

    $korisnik_id_pom=0+$_SESSION['sess_idkor'];

}

global $korisnik_init;
$korisnikClass_pom = new KorisnikClass();
$korisnik_pom = new Korisnik();
$korisnik_pom->setKorisnikID($korisnik_id_pom);
$korisnik_init = $korisnikClass_pom->KorisnikLoad_init($korisnik_pom);


/*
var_dump($korisnik_init);
exit;*/