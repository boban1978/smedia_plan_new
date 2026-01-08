<?php
class FileInfoClass {
    public $Name;
    public $Link;
    public $LinkFront;


    public $Directory;
    //public $DirectoryPart;
    public $NamePrefix;
    public $Size;
    public $Suffix;
    public $SuccessUpload;

    public function __construct(UploadedFileClass $file, $directory,$prefix=true) {
        $this->Name = $file->get_upload_name();
        $this->Directory = $directory;
        //$this->DirectoryPart = $directoryPart;


        $namePrefix = str_pad(mt_rand(0, 10000000), 10, "0", STR_PAD_LEFT);

        if(!$prefix){
            $namePrefix='';
        }
        $fileLink = $directory.$namePrefix.$file->get_upload_name();
        $this->NamePrefix = $namePrefix;
        $this->Link = $fileLink;
        $this->LinkFront = substr($fileLink,3);
        $this->Size = $file->get_size();
        $this->Suffix = $file->get_suffix();
//        $fullDirectory = $directory.$directoryPart."/";
//        if (!is_dir($fullDirectory)){
//            mkdir($fullDirectory, 0777);
//        }
        $flag = $file->copy($fileLink);
        if ($flag) {
            $this->SuccessUpload = true;
        } else {
            $this->SuccessUpload = false;
        }
    }
    public function GetResponse() {
        return $this->SuccessUpload;
    }
}

?>
