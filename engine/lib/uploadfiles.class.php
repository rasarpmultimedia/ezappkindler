<?php
namespace engine\lib;
class UploadFiles{
  public  $Upload_dir;
  private $Temp_dir;
  public  $Thumb_dir;
  public  $Upload_err = array();
  public  $Upload_status = false;
  
  public  $chmod_val = [
	/*/ Read and write for owner, nothing for everybody else*/
	"rw-owner"=>0600,
	/*/ Read and write for owner, read for everybody else*/
	"rw-owner-r"=>0644,
	/*/ Everything for owner, read and execute for others*/
	"rw-owner-r"=>0755,
	/*/ Everything for owner, read and execute for owner's group */
	"rw-owner-rx-grp"=>0750
];
 
  //upload file attributes
  public $Filename;
  public $Newfilename;
  public $File_ext;
  public $Filetype;
  protected $Files;
  protected $Filetemp_name;
  public $Filesize;
  public $Mimetype;
  public $File_error;
  public $processimg;
  public  $add_thumbnail = false; //adds a thumnail
  public $imgrand_charlen = 20;
  
  //set upload flags;
   static $MAX_IMG_WIDTH 	= 250;
   static $MAX_IMG_HEIGHT   	= 250;
   static $MAX_THUMB_WIDTH  	= 100;
   static $MAX_THUMB_HEIGHT 	= 90;
   static $SET_MAX_FILE_SIZE 	= 3000000;
   
  /** @access set database attributes  */
  public  $Width;
  public  $Height;
   
  public function __construct($upload_dir="",$temp_dir=""){
	  $this->Upload_dir = $upload_dir;
		if($this->add_thumbnail===true){
                    $this->Thumb_dir = $this->Upload_dir."thumbnails/";
		    $this->Thumb_dir = (!mkdir($this->Upload_dir."thumbnails/")) ? (!mkdir($this->Upload_dir."thumbnails/", 0777, true)) : ('Failed to thumbnails folder');
		}			
	$this->Temp_dir = strlen($temp_dir)!=null?$temp_dir:"";
	$this->Files = $_FILES;
	$this->processimg = $this->processImage();

}
  /** 
   * @method uploadImages,
   * Prepare file for uploads 
   * */
  public function uploadImages($fieldname){
          foreach($this->Files[$fieldname]["error"] as $key => $error){
  		$this->Filename = basename($this->Files[$fieldname]["name"][$key]);
		$this->Filename = str_replace(" ", "_", $this->Filename);
                $this->Filetype = $this->Files[$fieldname]["type"][$key];
                $this->Filetemp_name = $this->Files[$fieldname]["tmp_name"][$key];
                $this->Filesize = $this->Files[$fieldname]["size"][$key];
		//upload success
	  if($error == UPLOAD_ERR_OK && empty($this->File_error)){
	     $this->processImageUpload();
             return $this->Upload_status = true;
	  }else{return $this->Upload_status = false;}//upload failure         
          }
        
        
 }  
  /** 
   * @method uploadImage,
   * Prepare file for uploads 
   * */
    public function uploadImage($fieldname){
            $this->Filename = basename($this->Files[$fieldname]["name"]);
            $this->Filename = str_replace(" ", "_", $this->Filename);
            $this->Filetype = $this->Files[$fieldname]["type"];
            $this->Filetemp_name = $this->Files[$fieldname]["tmp_name"];
            $this->Filesize = $this->Files[$fieldname]["size"];
            $this->File_error = $this->Files[$fieldname]["error"];
		//upload success
	    if($this->File_error == UPLOAD_ERR_OK || $this->File_error==0){
	     $this->processImageUpload();
		 return $this->Upload_status = true;
	    }else{return $this->Upload_status = false;}//upload failure
	
        }
		
	
	
 /** 
   * @method uploadFiles,
   * Prepare file for uploads 
   * */
  public function uploadFiles($fieldname){
          foreach($this->Files[$fieldname]["error"] as $key => $error){
  		$this->Filename = basename($this->Files[$fieldname]["name"][$key]);
		$this->Filename = str_replace(" ", "_", $this->Filename);
        $this->Filetype = $this->Files[$fieldname]["type"][$key];
        $this->Filetemp_name = $this->Files[$fieldname]["tmp_name"][$key];
        $this->Filesize = $this->Files[$fieldname]["size"][$key];
		//upload success
	    if($error == UPLOAD_ERR_OK && empty($this->File_error)){
	     $this->processUpload();
		 return $this->Upload_status = true;
	    }else{return $this->Upload_status = false;}//upload failure
                       
            }
        
  }
	
  /** 
   * @method uploadFile,
   * Prepare file for uploads 
   * */
    public function uploadFile($fieldname){
            $this->Filename = basename($this->Files[$fieldname]["name"]);
            $this->Filename = str_replace(" ", "_", $this->Filename);
            $this->Filetype = $this->Files[$fieldname]["type"];
            $this->Filetemp_name = $this->Files[$fieldname]["tmp_name"];
            $this->Filesize = $this->Files[$fieldname]["size"];
            $this->File_error = $this->Files[$fieldname]["error"];
		//upload success
	    if($this->File_error == UPLOAD_ERR_OK || $this->File_error==0){
	     $this->processUpload();
		 return $this->Upload_status = true;
	    }else{return $this->Upload_status = false;}//upload failure
	}
  
	/** 
   * @method uploadAvatar,
   * Prepare file for uploads 
   * */
    public function uploadAvatar($fieldname){
            $this->Filename = basename($this->Files[$fieldname]["name"]);
            $this->Filename = str_replace(" ", "_", $this->Filename);
            $this->Filetype = $this->Files[$fieldname]["type"];
            $this->Filetemp_name = $this->Files[$fieldname]["tmp_name"];
            $this->Filesize = $this->Files[$fieldname]["size"];
            $this->File_error = $this->Files[$fieldname]["error"];
            //upload success
	    if($this->File_error == UPLOAD_ERR_OK || $this->File_error==0){
	     $this->processAvatar();
             return $this->Upload_status = true;
	    }else{return $this->Upload_status = false;}//upload failure	
    }
      
    private function processUpload(){
	 //if no errors are found
	 if(is_uploaded_file($this->Filetemp_name)){
		list($newname,$imgext)= explode(".",$this->Filename);
		$charlen = $this->imgrand_charlen;
		$newfilename = date("dmy",time())."_".$this->randomImgName($charlen).".$imgext";
	    $upload = move_uploaded_file($this->Filetemp_name,$this->Upload_dir.$this->Filename);
		if($upload){
		 $this->Newfilename = $newfilename;
		 rename($this->Upload_dir.$this->Filename,$this->Upload_dir.$this->Newfilename);
			  $filesize = filesize($this->Upload_dir.$this->Newfilename);
			  $this->File_ext = $imgext;
			  $this->Filesize = $filesize;//filesize in byes
              $this->Filetype = $this->Filetype;
			  $this->deleteFile($this->Upload_dir.$this->Filename);//delete original image
		  }
	  }
  }
	
  private function processImageUpload(){
	 //if no errors are found
	 if(is_uploaded_file($this->Filetemp_name)){
		list($newname,$imgext)= explode(".",$this->Filename);
		$charlen = $this->imgrand_charlen;
		$newfilename = date("dmy",time())."_".$this->randomImgName($charlen).".$imgext";
	    $upload = move_uploaded_file($this->Filetemp_name,$this->Upload_dir.$this->Filename);
		if($upload){
			$this->Newfilename = $newfilename;
		 	rename($this->Upload_dir.$this->Filename,$this->Upload_dir.$this->Newfilename);
			  
		 	$this->processimg->resizeImg($this->Upload_dir.$this->Newfilename,$this->Upload_dir.$this->Newfilename,static::$MAX_IMG_WIDTH,static::$MAX_IMG_HEIGHT);
			//create thumbnail for image 
			if($this->add_thumbnail === true){
				$this->processimg->createThumbnail($this->Upload_dir.$this->Newfilename,$this->Thumb_dir.$this->Newfilename,static::$MAX_THUMB_WIDTH,static::$MAX_THUMB_HEIGHT);
			}
			  
			  $image_prop = getimagesize($this->Upload_dir.$this->Newfilename);
			  $filesize = filesize($this->Upload_dir.$this->Newfilename);
			  $this->File_ext = $imgext;
			  $this->Width    = $image_prop[0]; 
			  $this->Height   = $image_prop[1]; 
			  $this->Filesize = $filesize;//filesize in byes
              $this->Filetype = $image_prop["mime"];
			  $this->deleteFile($this->Upload_dir.$this->Filename);//delete original image
		  }
	  }
  }
	
  protected function randomImgName($len=5,$min=0){
		   $namechar = 'abcdefghijklmnopqrstuvwxyz1234567890';$gen_name="";
		    for($i=0;$i<$len;$i++){
			 $gen_name .= $namechar[mt_rand($min,strlen($namechar)-1)];
			}
		return $gen_name;
	}
	
  private function processAvatar(){
	 //if no errors are found  
	   if(is_uploaded_file($this->Filetemp_name)){
		   list($newname,$imgext)= explode(".",$this->Filename);
		   $charlen = $this->imgrand_charlen;
		   $newfilename = date("ydm",time())."_".$this->randomImgName($charlen).".$imgext";

		   $upload = move_uploaded_file($this->Filetemp_name,$this->Upload_dir.$this->Filename);
			if($upload){ $this->Newfilename = $newfilename;
				$this->processimg->createThumbnail($this->Upload_dir.$this->Filename,$this->Upload_dir.$this->Filename,static::$MAX_THUMB_WIDTH,static::$MAX_THUMB_HEIGHT);
				rename($this->Upload_dir.$this->Filename,$this->Upload_dir.$this->Newfilename);
				$this->deleteFile($this->Upload_dir.$this->Filename);//delete original image
		   }   
	   }
	}
    
   public function deleteFile($filepath){
       if($filepath!='' && file_exists($filepath)){
     	   unlink($filepath);   		  
	}
   }
    
  //  Image Processor Initializer;
  public function processImage(){
   return new ImgProcessor;
  }
}

//$upload = new UploadFiles;
