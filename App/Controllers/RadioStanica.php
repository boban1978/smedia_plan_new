<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];

if (isset($paraGet)) {
    $parameter = $paraGet;
}



$radioStanicaClass = new RadioStanicaClass();
$radioStanica = new RadioStanica();


switch ($parameter) {
    case "RadioStanicaLoad":
        $radioStanicaID = $_POST['entryID'];
        $radioStanica->setRadioStanicaID($radioStanicaID);
        $response = $radioStanicaClass->$parameter($radioStanica);
        break;
    case "RadioStanicaInsertUpdate":



        $logo_file = $_FILES['logo'];
        $file = new UploadedFileClass($logo_file);
        $fileInfo = new FileInfoClass($file, DokumentLokacije::RadioStanicaLogo);
        if ($fileInfo->GetResponse()) {//&& $fileInfo1->GetResponse()
            $radioStanica->setLogo($fileInfo->LinkFront);
        }

            /*
            $fieldValues = $_POST['fieldValues'];
            $fieldValues = json_decode($fieldValues);
            $radioStanica->setRadioStanicaID($fieldValues->radioStanicaID);
            $radioStanica->setNaziv($fieldValues->naziv);
            $radioStanica->setAdresa($fieldValues->adresa);
            $radioStanica->setAktivan($fieldValues->aktivan);
            if ($fieldValues->radioStanicaID == -1) {
                $action = "RadioStanicaInsert";
            } else {
                $action = "RadioStanicaUpdate";
            }
            */


            $radioStanica->setRadioStanicaID($_POST['radioStanicaID']);
            $radioStanica->setNaziv($_POST['naziv']);
            $radioStanica->setAdresa($_POST['adresa']);
            $radioStanica->setAktivan($_POST['aktivan']);
            if ($_POST['radioStanicaID'] == -1) {
                $action = "RadioStanicaInsert";
            } else {
                $action = "RadioStanicaUpdate";
            }

mkdir('/var/www/html/mediastream/XML/'.$_POST['naziv'], 0777, true);
mkdir('/home/SHARING/STREAMING/Reklame Zara/'.$_POST['naziv'], 0777, true);
mkdir('/home/SHARING/STREAMING/Emitovanje/Reklame/'.$_POST['naziv'], 0777, true);

$path = '/var/www/html/mediastream/XML/'.$_POST['naziv'];
$cmd = 'sudo /sbin/setroot777.sh ' . escapeshellarg($path);
$output = shell_exec($cmd);

$path = '/home/SHARING/STREAMING/Reklame Zara/'.$_POST['naziv'];
$cmd = 'sudo /sbin/setroot777.sh ' . escapeshellarg($path);
$output = shell_exec($cmd);

$path = '/home/SHARING/STREAMING/Emitovanje/Reklame/'.$_POST['naziv'];
$cmd = 'sudo /sbin/setroot777.sh ' . escapeshellarg($path);
$output = shell_exec($cmd);
//exit;
            $response = $radioStanicaClass->$action($radioStanica);


/*
        } else {
            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess(FALSE);
            $response->SetMessage("Neuspešno kopiranje fajla!");
        }
*/








        break;
    case "RadioStanicaDelete":
        $radioStanicaID = $_POST['entryID'];
        $radioStanica->setRadioStanicaID($radioStanicaID);
        $response = $radioStanicaClass->$parameter($radioStanica);
        break;
    case "RadioStanicaGetForComboBox":
        $response = $radioStanicaClass->$parameter($radioStanica);
        break;
    case "RadioStanicaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterRadioStanica($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $radioStanicaClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>