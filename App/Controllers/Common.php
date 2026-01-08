<?php
require_once __DIR__.'/../../init.php';


if (isset($_GET['action'])) {
    $paraGet = $_GET['action'];
}
$parameter = $_POST['action'];
if (isset($paraGet)) {
    $parameter = $paraGet;
}
$commonClass = new CommonClass();

switch ($parameter) {
    case "getSpotsAutocomplete":
        $radioStanicaID = $_POST['radioStanicaID'];
        $response = $commonClass->$parameter($radioStanicaID);

        //$response = $commonClass->getSpotsAutocomplete_xxx($radioStanicaID);

        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>

