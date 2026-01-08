<?php
if(!defined('ROOT')){die('umri muski!');}

function getConnection(){
    return CoreEngine::getConnection();
}


function send_respons_boban($result=''){
    //$result = $_POST;
    $responseNew = new CoreAjaxResponseInfo();
    $responseNew->SetSuccess('true');
    $responseNew->SetData('data:'.json_encode($result));
    ob_clean();
    header('Content-type: application/json');
    echo $responseNew->GetResponse();
    ob_flush();
    exit;
}




function resolve_sort(&$sort,&$dir)
{
    //$sort='[{"property":"Naziv","direction":"ASC"}]';
    $sort = json_decode($sort);
    $sort = $sort[0];
    $dir = $sort->direction;
    $sort = $sort->property;
}


?>