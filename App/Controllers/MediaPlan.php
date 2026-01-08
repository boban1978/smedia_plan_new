<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}

$mediaPlanClass = new MediaPlanClass();
//$kampanja = new Kampanja();


switch ($parameter) {
    case "MediaPlanLoad":
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $radioStanicaID = !empty($_POST['radioStanicaID']) ? $_POST['radioStanicaID'] : 'null';
        $response = $mediaPlanClass->$parameter($startDate, $endDate, $radioStanicaID);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
//echo $response;
ob_flush();
?>