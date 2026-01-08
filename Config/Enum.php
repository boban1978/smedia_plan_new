<?php
if(!defined('ROOT')){die('umri muski!');}

class TipKorisnika {
    const InterniKorisnik = 1;
    const KlijentskiKorisnik = 2;
    const AgencijskiKorisnik = 3;
}

class TipBitanDatum {
    const Kontakt = 1;
    const Klijent = 2;
    const Agencija = 3;
}

class StatusKampanjaEnum {
    const Rezervisana = 1;
    const Potvrdjena = 2;
    const Otkazana = 3;
}

class FinansijskiStatus {
    const Nefakturisana = 1;
    const Fakturisana = 2;
    const Nenaplacena = 3;
    const Placena = 4;
}

class DokumentLokacije {
    const PonudaDokument = "../../Document/Ponuda/";
    const SpotDokument = "../../Document/Spot/";
    const IstorijaKomunikacijeDokument = "../../Document/IstorijaKomunikacije/";
    const PrilogIzjava = "../../Document/PrilogIzjava/";
    const RadioStanicaLogo = "../../Document/RadioStanicaLogo/";
}




?>
