    <?php
    require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$cenovnikUslugaClass = new CenovnikUslugaClass();
$cenovnikUsluga = new CenovnikUsluga();


switch ($parameter) {
    case "CenovnikUslugaLoad":
        $entryID = $_POST['entryID'];
        $cenovnikUsluga->setCenovnikUslugaID($entryID);
        $response = $cenovnikUslugaClass->$parameter($cenovnikUsluga);
        break;
    case "CenovnikUslugaInsertUpdate":
        $fieldValues = json_decode($_POST['fieldValues']);
        $cenovnikUsluga->setCenovnikUslugaID($fieldValues->cenovnikUslugaID);
        $cenovnikUsluga->setNaziv($fieldValues->naziv);
        $cenovnikUsluga->setCena($fieldValues->cena);
        if ($fieldValues->cenovnikUslugaID == -1) {
            $action = "CenovnikUslugaInsert";
        } else {
            $action = "CenovnikUslugaUpdate";
        }
        $response = $cenovnikUslugaClass->$action($cenovnikUsluga);
        break;
    case "CenovnikUslugaDelete":
        $entryID = $_POST['entryID'];
        $cenovnikUsluga->setCenovnikUslugaID($entryID);
        $response = $cenovnikUslugaClass->$parameter($cenovnikUsluga);
        break;
    case "CenovnikUslugaGetForComboBox":
        $response = $cenovnikUslugaClass->$parameter($cenovnikUsluga);
        break;
    case "CenovnikUslugaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues = json_decode($_POST['filterValues']);
        $filter = new FilterCenovnikUsluga($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $cenovnikUslugaClass->$parameter($filter);       
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>