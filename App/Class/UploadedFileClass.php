<?php
/**
* Uploaded file handling class
*
* @author    Sven Wagener <wagener_at_indot_dot_de>
* @include 	 Funktion:_include_
*/

class UploadedFileClass extends FileClass{
	public $upload_name;
	public $upload_tmp_name;
	public $upload_size;
	public $upload_type;
	
	/**
	* Constructor of class
	* @param array $file_form_array $_FILE Array of uploaded file
	* @return boolean $file_exists Returns TRUE if file is ok, FALSE if file is faked
	* @desc Constructor of class
	*/
	public function __construct($form_file_array,$binary=false){
		if($form_file_array['size']!=0){
			//$this->file=$file_form_array;
            $this->file=$form_file_array;
			$this->upload_name=$form_file_array['name'];
			$this->upload_tmp_name=$form_file_array['tmp_name'];
			$this->upload_size=$form_file_array['size'];
			$this->upload_type=$form_file_array['type'];
			$this->FileClass($form_file_array['tmp_name'],$binary);
		}else{
			$this->halt("File size must be more than 0 bytes");
			return false;
		}
	}
	
	/**
	* Returns real filename
	* @return string $file_name The real filename
	* @desc Returns real filename
	*/
	public function get_upload_name(){
		//return utf2win($this->upload_name);
                return $this->upload_name;
	}
	
	/**
	* Returns temporary name of file, given by the form
	* @return string $file_tmp_name The temporary filename, given by the form
	* @desc Returns temporary name of file, given by the form
	*/
	public function get_upload_tmp_name(){
		return $this->upload_tmp_name;
	}
	
	/**
	* Returns file size, given by the form
	* @return int $file_size The file size, given by the form in bytes
	* @desc Returns the file size, given by the form
	*/
	public function get_upload_size(){
		return $this->upload_size;
	}
	
	/**
	* Returns file type, given by the form
	* @return string $file_type The file type, given by the form
	* @desc Returns file type, given by the form
	*/
	public function get_upload_type(){
		return $this->upload_type;
	}
}

?>