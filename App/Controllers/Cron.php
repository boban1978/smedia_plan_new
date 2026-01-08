<?php

define('RUNNING_BY_SERVER',1);

require_once __DIR__.'/../../init.php';


/*
if(defined('RUNNING_BY_SERVER')){
    echo "YES";
}else{
    echo "NO";
}*/







//if(!isset($_SESSION['sess_idkor'])){header("Location: ".HOME_ADDRESS."?message=Morate se ulogovati!");exit;}


/*
var_dump($_SERVER);
exit;*/


if (isset($_POST['action'])) {
    $parameter = $_POST['action'];
}

if (isset($_GET['action'])) {
    $parameter = $_GET['action'];
}

if(isset($argv)){
    $action=$argv[1];

    $action=str_replace("action=","",$action);
    $parameter = $action;
    /*
    $debug=$argv[2];
    $debug=str_replace("debug=","",$debug);*/


}





$cronClass = new CronClass();
//$cron = new Cron();//model

switch ($parameter) {
    
        case "cron_reklame_all":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }
        $datum=NULL;
        if(isset($_GET['datum'])){
            $datum = $_GET['datum'];
        }

        $debug=1;
        if(isset($_GET['debug'])){
            $debug = 0+ $_GET['debug'];
        }



        $response = $cronClass->$parameter($radio,$datum,$debug);
        break;     
    
    
    
    
    
    
    case "cron_reklame":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }
        $datum=NULL;
        if(isset($_GET['datum'])){
            $datum = $_GET['datum'];
        }

        $debug=1;
        if(isset($_GET['debug'])){
            $debug = 0+ $_GET['debug'];
        }



        $response = $cronClass->$parameter($radio,$datum,$debug);
        break;

    
    case "cron_reklame_fp":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }
        $datum=NULL;
        if(isset($_GET['datum'])){
            $datum = $_GET['datum'];
        }

        $debug=1;
        if(isset($_GET['debug'])){
            $debug = 0+ $_GET['debug'];
        }



        $response = $cronClass->$parameter($radio,$datum,$debug);
        break;    
    
    
    case "cron_reklame_check":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }
        $datum=NULL;
        if(isset($_POST['datum'])){
            $datum = $_POST['datum'];
        }
        if(isset($_GET['datum'])){
            $datum = $_GET['datum'];
        }

        $response = $cronClass->$parameter($radio,$datum);
        break;

    
    
	case "provera_duzine_reklame":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }

        $debug=1;
        if(isset($_GET['debug'])){
            $debug = 0+ $_GET['debug'];
        }


        $response = $cronClass->$parameter($radio,$debug);
    break;    
    
    
    
    
    case "cron_reklame_check2":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }
        $datum=NULL;
        if(isset($_POST['datum'])){
            $datum = $_POST['datum'];
        }
        if(isset($_GET['datum'])){
            $datum = $_GET['datum'];
        }

        $response = $cronClass->$parameter($radio,$datum);
        break;



    case "cron_xml":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }

        $debug=1;
        if(isset($_GET['debug'])){
            $debug = 0+ $_GET['debug'];
        }


        $response = $cronClass->$parameter($radio,$debug);
        break;
////////////////////XML2///////////////////
    
    case "cron_xml_2":
        $radio=NULL;
        if(isset($_GET['radio'])){
            $radio = $_GET['radio'];
        }

        $debug=1;
        if(isset($_GET['debug'])){
            $debug = 0+ $_GET['debug'];
        }


        $response = $cronClass->$parameter($radio,$debug);
        break;    
    
    
////////////////////////////////////////////    
    case "cron_fakturisanje":

        //$response = $cronClass->$parameter();

        $response = $cronClass->cron_fakturisanje_2();
        break;

    case "cron_set_kurs":

        $response = $cronClass->$parameter();

        break;



    default:
        break;
}



?>
