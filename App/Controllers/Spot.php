<?php
require_once __DIR__.'/../../init.php';


if (isset($_GET['action'])) {
    $paraGet = $_GET['action'];
}

$parameter = $_POST['action'];

if (isset($paraGet)) {
    $parameter = $paraGet;
}


$spotClass = new SpotClass();

$spot = new Spot();


switch ($parameter) {
    case "SpotLoad":       
        $entryID = $_POST['entryID'];
        $spot->setSpotID($entryID);
        $response = $spotClass->$parameter($spot);
        break;
    case "SpotInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $spot->setSpotID($fieldValues->spotID);
        $spot->setKampanjaID($fieldValues->kampanjaID);
        $spot->setSpotLink($fieldValues->spotLink);
        $spot->setGlasID($fieldValues->glasID);
        $spot->setSpotTrajanje($fieldValues->trajanje);
        if ($fieldValues->spotID == -1) {
            $action = "SpotInsert";
        } else {
            $action = "SpotUpdate";
        }
        $response = $spotClass->$action($spot);
        break;
    case "SpotNameUpdate":


        send_respons_boban($_POST);
        exit;


        $spot->setSpotID($_POST['spotID']);
        $spot->setSpotName($_POST['spotName']);

        $response = $spotClass->SpotUpdate($spot);
        break;

    case "SpotDelete":
        $spotID = $_POST['entryID'];
        $spot->setSpotID($spotID);
        $response = $spotClass->$parameter($spot);
        break;
    case "SpotGetForComboBox":
        $response = $spotClass->$parameter($spot);
        break;
    case "SpotGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterSpot($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $spotClass->$parameter($filter);
        break;
    case "SpotEdit":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);

        $spot->setSpotID($fieldValues->spotID);
        $spot->setSpotName($fieldValues->spotName);

        $response = $spotClass->SpotUpdate($spot);
        break;

    case "SpotDeleteFile":
        $radioStanica = $_POST['RadioStanica'];
        $spotName = $_POST['SpotName'];

        $response = $spotClass->$parameter($spotName,$radioStanica);
        break;




    case "SpotUpload":

        $radioStanicaID = $_POST['radioStanicaID'];

		$switchclass = new SwitchClass();
		$radio = $switchclass->GetSwitchRadio($radioStanicaID);
		unset($switchclass);
/*
        switch ($radioStanicaID) {
            case 1:
                $radio="s-juzni";
                break;
            case 2:
                $radio="s-mix";
                break;
            default:
                die('radio not set!!!');
        }

*/

        $prilog = $_FILES['prilog'];

        if($prilog['size']!=0){

            $file_name_full=$prilog['name'];
            $file_ext = end(explode(".", $file_name_full));

            $allowed_ext=array('wav','mp3');
            if(!in_array($file_ext,$allowed_ext)){
                $response = new CoreAjaxResponseInfo();
                $response->SetSuccess('false');
                $response->SetMessage("Ekstenzija fajla nije dozvoljena !");

                ob_clean();
                header('Content-type: text/html');
                echo $response->GetResponse();
                ob_flush();
                exit;
            }


            $file_name=substr($file_name_full,0,(strlen($file_name_full)-strlen($file_ext)-1));


            $file_name_sufix=substr($file_name,-2);
            if($file_name_sufix=='~1'){
                $file_name=substr($file_name,0,(strlen($file_name)-2));
                $file_name=trim($file_name);
            }


            $file_name=str_replace(array('~','&'),'',$file_name);
            $file_name=str_replace(array('  ','   ','    '),' ',$file_name);
            $file_name.=' ~1';
            $file_name_full=$file_name.'.'.$file_ext;

            $prilog['name']=$file_name_full;

        }else{

            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess('false');
            $response->SetMessage("Neuspešno kopiranje fajla!");

            ob_clean();
            header('Content-type: text/html');
            echo $response->GetResponse();
            ob_flush();
            exit;

        }


        $file = new UploadedFileClass($prilog);
        $fileInfo = new FileInfoClass($file, "/home/SHARING/STREAMING/Emitovanje/Reklame/" . $radio ."/",false);

        if ($fileInfo->GetResponse()){
            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess('true');
            $response->SetMessage($file_name);

        } else {
            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess('false');
            $response->SetMessage("Neuspešno kopiranje fajla!");
        }

        ob_clean();
        header('Content-type: text/html');
        echo $response->GetResponse();
        ob_flush();
        exit;

        break;

    default:
        break;
}

ob_clean();
header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>