<?php


Class PlayListClass2 {

    private $elements = array();
    
    function __construct($xml_path="") {

        if (!file_exists($xml_path)) {
            die("XML not exist !!!");
        }
        $xml = file_get_contents($xml_path);

        $xml = trim($xml);

        $parser = new XmlParserClass($xml);

        $parser->Parse();

        foreach ($parser->document->playitem as $item) {
            $naziv = strtolower($item->naziv[0]->tagData);
            $data=$this->get_data($item);
            $this->elements[$naziv]=$data;
        }

    }

    function get_data($item){

    $data = '
<PlayItem>
<ID>' . $item->id[0]->tagData . '</ID>
<Naziv>' . $item->naziv[0]->tagData . '</Naziv>
<Autor>' . $item->autor[0]->tagData . '</Autor>
<Album/>
<Info/>
<Tip>' . $item->tip[0]->tagData . '</Tip>
<Color>' . $item->color[0]->tagData . '</Color>
<NaKanalu>' . $item->nakanalu[0]->tagData . '</NaKanalu>
<PathName>' . $item->pathname[0]->tagData . '</PathName>
<ItemType>' . $item->itemtype[0]->tagData . '</ItemType>
<StartCue>' . $item->startcue[0]->tagData . '</StartCue>
<EndCue>' . $item->endcue[0]->tagData . '</EndCue>
<Pocetak>' . $item->pocetak[0]->tagData . '</Pocetak>
<Trajanje>' . $item->trajanje[0]->tagData . '</Trajanje>
<Vrijeme>' . $item->vrijeme[0]->tagData . '</Vrijeme>
<StvarnoVrijemePocetka>' . $item->stvarnovrijemepocetka[0]->tagData . '</StvarnoVrijemePocetka>
<VrijemeMinTermin>' . $item->vrijememintermin[0]->tagData . '</VrijemeMinTermin>
<VrijemeMaxTermin>' . $item->vrijememaxtermin[0]->tagData . '</VrijemeMaxTermin>
<PrviU_Bloku>' . $item->prviu_bloku[0]->tagData . '</PrviU_Bloku>
<ZadnjiU_Bloku>' . $item->zadnjiu_bloku[0]->tagData . '</ZadnjiU_Bloku>
<JediniU_Bloku>' . $item->jediniu_bloku[0]->tagData . '</JediniU_Bloku>
<FiksniU_Terminu>' . $item->fiksniu_terminu[0]->tagData . '</FiksniU_Terminu>
<Reklama>' . $item->reklama[0]->tagData . '</Reklama>
<WaveIn>' . $item->wavein[0]->tagData . '</WaveIn>
<SoftIn>' . $item->softin[0]->tagData . '</SoftIn>
<SoftOut>' . $item->softout[0]->tagData . '</SoftOut>
<Volume>' . $item->volume[0]->tagData . '</Volume>
<OriginalStartCue>' . $item->originalstartcue[0]->tagData . '</OriginalStartCue>
<OriginalEndCue>' . $item->originalendcue[0]->tagData . '</OriginalEndCue>
<OriginalPocetak>' . $item->originalpocetak[0]->tagData . '</OriginalPocetak>
<OriginalTrajanje>' . $item->originaltrajanje[0]->tagData . '</OriginalTrajanje>
</PlayItem>';

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