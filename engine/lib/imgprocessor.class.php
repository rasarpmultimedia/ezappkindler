<?php
namespace engine\lib;
class ImgProcessor{
	private $img_xcoord;
	private $img_ycoord;
	private $size;
	private $width;
	private $height;
	private $resize_width;
	private $resize_height;
	private $thumb_width;
	private $thumb_height;
	private $watermark;
	
	function __construct(){
		$this->img_xcoord = 0;
		$this->img_ycoord = 0;
	}
	
  private function calResizeRatio(){
  	   $ratio = ($this->width/$this->height);
	   $dim = ($this->resize_width/$this->resize_height);
	   if($dim > $ratio){
	   	$this->resize_width  = ceil($this->resize_height*$ratio);
	   }else{
	   	$this->resize_height = ceil($this->resize_width/$ratio);
	   } 	
	   return true; 
  }
  private function calThumbRatio(){
  	    //$this->img_xcoord = ($this->width/2)-($this->thumb_width/2);	
		//$this->img_ycoord = ($this->height/2)-($this->thumb_height/2);
       $ratio = ($this->width/$this->height);
	   $dim = ceil($this->thumb_width/$this->thumb_height);
	   if($dim > $ratio){
	   	$this->thumb_width  = ceil($this->thumb_height*$ratio);
	   }else{
	   	$this->resize_height = ceil($this->thumb_width/$ratio);
	   }
	    return true; 
  }
   private function get_img_extension($string){
		 $string = strtolower($string);
	 	 $dotpos = strrpos($string,".");
	  	 if(!$dotpos ){ return "";}
	    	$strlen = strlen($string) - $dotpos;
			$extension = substr($string,$dotpos+1,$strlen);
		 return $extension;
  }
    public function resizeImg($source,$destination,$resize_width=100,$resize_height=100){
         $this->size = getimagesize($source);
		 $this->width  = $this->size[0];
		 $this->height = $this->size[1];
		 $this->resize_width  = $resize_width;
		 $this->resize_height = $resize_height;
  	     $this->calResizeRatio();
  	     $tempimage = imagecreatetruecolor($this->resize_width,$this->resize_height);
		 $ext = $this->get_img_extension($source);
			   if($ext=="jpg"||$ext=="jpeg"){
			     $image = imagecreatefromjpeg($source);
			   }
		   	   if($ext=="png"){
			  	 $image = imagecreatefrompng($source);
		       }
		   	   if($ext=="gif"){
			    $image = imagecreatefromgif($source);
			   }
		  //Outputing thumbnail images
		  imagecopyresampled($tempimage,$image,0,0,$this->img_xcoord,$this->img_ycoord,$this->resize_width,$this->resize_height,$this->width,$this->height);
		  if($ext =="jpg"||$ext=="jpeg"){
			  imagejpeg($tempimage,$destination,90);
		  }
		  if($ext =="png"){
			  imagepng($tempimage,$destination);
		  }
		  if($ext=="gif"){
			  imagegif($tempimage,$destination);
		  }
  }

  public function createThumbnail($source,$destination,$thumb_width=50,$thumb_height=50){
         $this->size = getimagesize($source);
		 $this->width  = $this->size[0];
		 $this->height = $this->size[1];
		 $this->thumb_width  = $thumb_width;
		 $this->thumb_height = $thumb_height;
  	     $this->calThumbRatio();
  	     $tempimage = imagecreatetruecolor($this->thumb_width,$this->thumb_height);
		 $ext = $this->get_img_extension($source);
			   if($ext=="jpg"||$ext=="jpeg"){
			     $image = imagecreatefromjpeg($source);
			   }
		   	   if($ext=="png"){
			  	 $image = imagecreatefrompng($source);
			   }
		   	   if($ext=="gif"){
			    $image = imagecreatefromgif($source);
			   }
		  //Outputing thumbnail images
		  imagecopyresampled($tempimage,$image,0,0,$this->img_xcoord,$this->img_ycoord,$this->thumb_width,$this->thumb_height,$this->width,$this->height);
		  if($ext =="jpg"||$ext=="jpeg"){
			  imagejpeg($tempimage,$destination,90);
		  }
		  if($ext =="png"){
			  imagepng($tempimage,$destination);
		  }
		  if($ext=="gif"){
			  imagegif($tempimage,$destination);
		  }
	}
	//convert_img to JPeg
	public function convert_Imgto_Jpeg($source,$destination){
         $this->size = getimagesize($source);
		 $this->width  = $this->size[0];
		 $this->height = $this->size[1];
  	     $tempimage = imagecreatetruecolor($this->width,$this->height);
		 $extension = $this->get_img_extension($source);
		  if($extension=="jpg"||$extension=="jpeg"){
			  $image = imagecreatefromjpeg($source);
		  }
		  if($extension=="png"){
			  $image = imagecreatefrompng($source);
		  }
		  if($extension=="gif"){
			  $image = imagecreatefromgif($source);
		  }
		  //Outputing thumbnail images
		  imagecopyresampled($tempimage,$image,0,0,$this->img_xcoord,$this->img_ycoord,$this->width,$this->height,$this->width,$this->height);
		  if($ext =="jpg"||$ext=="jpeg"){
			  imagejpeg($tempimage,$destination,90);
		  }
  }
  public function addCenteredWatermark($target_file,$watermarkimg,$save_file_as){
   	  $watermark= imagecreatefrompng($watermarkimg);
	  imagealphablending($watermark, false);
	  imagesavealpha($watermark, true);
	  $targetimg = imagecreatefromjpeg($tartget_file);
	  $target_w = imagesx($targetimg);
	  $target_h = imagesy($targetimg);
	  $water_w = imagesx($watermark);
	  $water_h = imagesy($watermark);
	  $des_x = ($target_w/2)-($water_w/2);//calculates position centering
	  $des_y =($target_h/2)-($water_h/2);
	  imagecopy($targetimg, $watermark, $des_x, $des_y, $target_w, $target_h, $water_w, $water_h);
	  imagejpeg($targetimg,$save_file_as,90);
	  imagedestroy($targetimg);
	  imagedestroy($watermark);
    }
   
  public function addWatermark($target_file,$watermarkimg,$save_file_as){
   	  $watermark= imagecreatefrompng($watermarkimg);
	  imagealphablending($watermark, false);
	  imagesavealpha($watermark, true);
	  $targetimg = imagecreatefromjpeg($tartget_file);
	  $target_w = imagesx($targetimg);
	  $target_h = imagesy($targetimg);
	  $water_w = imagesx($watermark);
	  $water_h = imagesy($watermark);
	  $move_right = 15;
	  $move_down  = 10;
	  $des_x = ($target_w-$water_w-$move_right);
	  $des_y = ($target_h-$water_h-$move_down);
	  imagecopy($targetimg, $watermark, $des_x, $des_y,0,0, $water_w,$water_h);
	  imagejpeg($targetimg,$save_file_as,90);
	  imagedestroy($targetimg);
	  imagedestroy($watermark);
	 }
}
