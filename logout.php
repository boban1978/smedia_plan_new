<?php
ob_start();
session_start();
//Funkcija koja vrsi redirekciju na konkretnu stranicu
function redirect_to( $location = NULL ) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}
session_destroy();
redirect_to("index.php");
?>
