<?php
class Image{
	
	protected $data_record;
	protected $results;
	public function __construct(){
		$conf = new Settings;
        $this->cfg  = $conf->Config();
	    $this->data_record = new RecordSet;
		$this->results = $this->data_record->initQuery($this->cfg["Tables"]["Image"]);
	}
	public function showThumbImage($thumbdir='',$imgfield="News_id",$getimgid=null){
		  $results = $this->results;
		  $results::$placeholder[":id"] = $getimgid;
	      $page_image = $results->selectRecord($imgfield."=:id");	 
		 if($page_image) { 
	         $thumbimg  = $thumbdir.$page_image->Image;
	         return (strlen($page_image->Image)!=0)?"<img src=\"$thumbimg\" alt=\"".$page_image->Image."\" title=\"".$page_image->Caption."\"  class=\"thumbimg\" />":"";
			 }else{ return "";}
	   }
	 
	public function showFullImage($imgdir='',$imgfield="News_id",$getimgid=null){
	      $results = $this->results;
		  $results::$placeholder[":id"] = $getimgid;
	      $page_image = $results->selectRecord($imgfield."=:id");	 	
		if($page_image) {
		 $imgdir  = $imgdir.$page_image->Image;
	         $display_img = (strlen($page_image->Image)!=0)?"<img src=\"$imgdir\" alt=\"".$page_image->Caption."\" title=\"".$page_image->Caption."\" width=\"".$page_image->Width."\" height=\"".$page_image->Height."\" class=\"pageimg\" />":"";	
			 $display_img .= (strlen($page_image->Image)!=0 && $page_image->Caption !=null)?"<p class=\"imgcaption\">".$page_image->Caption."</p>":"";
			 return $display_img;
			 }else{return ""; }       
	}
}
//$image = new Image();