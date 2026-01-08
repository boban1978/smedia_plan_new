<?php
define('RUNNING_BY_SERVER',1);
require_once __DIR__.'/init.php';

//Funkcija koja vrsi redirekciju na konkretnu stranicu
function redirect_to( $location = NULL ) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}
$username = $_POST['username'];
$password = $_POST['password'];
$parameters = KorisnikClass::KorisnikFetchUserLoginDetails($username);
// Registrovanje sesijskih promenljjivih
$_SESSION['sess_username'] = $parameters['Username'];
$_SESSION['sess_idkor'] = $parameters['KorisnikID'];
$_SESSION['sess_tipkor'] = $parameters['TipKorisnik'];
$_SESSION['sess_agencija']= $parameters['AgencijaID'];
$_SESSION['sess_klijent']= $parameters['KlijentID'];
if ($parameters['KorisnikID'] === null)
{
    $message = "Korisnik [".$username."] ne postoji u sistemu!";
    redirect_to("index.php?message=".$message);
}
else {
    //Provera da li je korisnik aktivan prilikom logovanja
    if ($parameters["Aktivan"] === 'true') {        
        //Ubacivanje podataka o ulogovanom useru u posebnu tabelu u bazi radi evidentiranja ulogovanih korisnika
        KorisnikClass::RegisterLogin($parameters['KorisnikID']);
        $msg = KorisnikClass::KorisnikCheckUserPassword($username, md5($password));
        if ($msg == 'OK'){
            redirect_to("Web/CentralPage.php");  
        } else {
            redirect_to("index.php?message=".$msg);
        }
    }
    else {
        $message = "Korisnik [".$username."] nije više aktivan!";
        redirect_to("index.php?message=".$message);
    }
}
ob_flush();
?>
