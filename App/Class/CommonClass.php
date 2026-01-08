<?php

class CommonClass {

    public function getSpotsAutocomplete($radioStanicaID=0,$return_array=0) {


        $radioStanicaID+=0;

        //$radioStanicaID=1;

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


        $xml_path = __DIR__ . '/../../XML/' . $radio . '/file.xml';

        //define(XML_PATH, __DIR__ . '/../../XML/' . $radio . '/file.xml');


        if (!file_exists($xml_path)) {
            die('XML not exist!!!');
        }

        $xml = file_get_contents($xml_path);

        $xml=str_replace('&','###',$xml);

        $xml = trim($xml);


        $parser = new XmlParserClass($xml);

        $parser->Parse();

        $nizArr=array();


        foreach ($parser->document->playitem as $item) {

            $naziv = $item->naziv[0]->tagData;
            $naziv=str_replace('###','&',$naziv);
            //$trajanje = strtolower($item->trajanje[0]->tagData);
            $naziv=substr($naziv,0,strlen($naziv)-4);

            if($return_array) {
                $nizArr[$naziv] = $naziv;
            }else{
                $nizArr[] = $naziv;
            }

        }



        if($return_array){
            return $nizArr;
        }




        $responseNew = new CoreAjaxResponseInfo();
        $responseNew->SetSuccess('true');
        $responseNew->SetMessage("");

        $responseNew->SetData('rows:' . json_encode($nizArr));

        return $responseNew;

    }



    public function getSpotsAutocomplete_xxx($radioStanicaID=0,$return_array=0) {


        $radioStanicaID+=0;



        $nizArr=array();




        $nizArr[]='aaaaaa'.$radioStanicaID."xxx";
        $nizArr[]='aaaaaa11';
        $nizArr[]='aaaaaa22';
        $nizArr[]='aaaaaa33';
        $nizArr[]='aaaaaa44';
        $nizArr[]='bbbbbb';
        $nizArr[]='cccccc';
        $nizArr[]='dddddd';
        $nizArr[]='eeeeee';
        $nizArr[]='ffffff';


        if($return_array){
            return $nizArr;
        }




        $responseNew = new CoreAjaxResponseInfo();
        $responseNew->SetSuccess('true');
        $responseNew->SetMessage("");

        $responseNew->SetData('rows:' . json_encode($nizArr));

        return $responseNew;

    }



}

?>
