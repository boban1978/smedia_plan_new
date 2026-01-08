<?php


Class PlayListClass {

    private $elements = array();
    private $path;
    private $pling_path;
    private $pling_path_real;
    private $pling_file_name = "Pling ~3.wav";

    private $pling_data='';

    
    function __construct($xml_path="", $radio = '') {

        if (!file_exists($xml_path)) {
            die("XML not exist !!!");
        }

        $this->path = '\\\\2-stream-stor\\STREAMING\\Emitovanje\\Reklame\\'.strtolower($radio).'\\';
        $this->pling_path = '\\\\2-stream-stor\\STREAMING\\Emitovanje\\Pling\\';
        $this->pling_path_real = "/home/SHARING/STREAMING/Emitovanje/Pling/";


        $xml = file_get_contents($xml_path);
        $xml=str_replace('&','###',$xml);
        $xml = trim($xml);

        $parser = new XmlParserClass($xml);

        $parser->Parse();


        foreach ($parser->document->playitem as $item) {

            $naziv = strtolower($item->naziv[0]->tagData);
            $naziv=str_replace('###','&',$naziv);
            //$trajanje = strtolower($item->trajanje[0]->tagData);
            $naziv=substr($naziv,0,strlen($naziv)-4);

            $naziv=strtolower($naziv);

            $data=$this->get_data($item);
            $this->elements[$naziv]=$data;
        }



        $this->pling_data=$this->get_pling_data();



    }

    function get_data($item){

$data = '
file=' . $this->path . $item->naziv[0]->tagData . '
length='. $item->trajanje[0]->tagData;

        return $data;

    }


    function get_pling_data(){


        $thisVideoFile = new ffmpeg_movie($this->pling_path_real.$this->pling_file_name);

        $duration = $thisVideoFile->getDuration();

        $duration=round($duration*1000);



        $data = '
file=' . $this->pling_path.$this->pling_file_name . '
length='. $duration;

        return $data;

    }





    function Set($opt, $value){
        $this->{$opt} = $value;
    }
    
    function Get($opt){
        return $this->{$opt};
    }
}

?> 