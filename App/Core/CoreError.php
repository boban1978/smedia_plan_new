<?php
class CoreError {
    public static function getError() {
        if(isset($_SESSION['mysqlGreska'])) {
            $greska = $_SESSION['mysqlGreska'];
            unset($_SESSION['mysqlGreska']);
        } elseif (isset($_SESSION['nekaGreska'])) {
            $greska = $_SESSION['nekaGreska'];
            unset($_SESSION['nekaGreska']);
        }else {
            $greska = 'Nepoznata greska.';
        }
        return $greska;
    }
}
?>
