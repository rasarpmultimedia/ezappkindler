<?php
namespace engine\lib;
include_once"form.class.php";
/* Form Processing Class is used to create forms validate form data and process form all at once */
class  ProcessForm{
	 public $successflag = false;
	 public $errorinfo = []; //add each error to the end of this array
	 public $successmsg = "";
	 public $message;
	 public $method = ["post"=>"POST","get"=>"GET"];
	 public $post_sess;
    
	 public function requestMethod($req="post"){
	 	   return (($_SERVER["REQUEST_METHOD"] == $this->method[$req])?true:false);
		  //return ($_SERVER["REQUEST_METHOD"]=="POST"?true:false);
	 } 
    
	 public function post($poststr,$input_val=''){
	 	return (isset($_POST[$poststr]))?trim(filter_var($_POST[$poststr],FILTER_SANITIZE_STRING)):strip_tags($input_val);
	 }
	
	 public function get($poststr,$input_val=''){
	 	return (isset($_GET[$poststr]))?trim(filter_var($_GET[$poststr],FILTER_SANITIZE_STRING)):strip_tags($input_val);
	 }

	 public function request($poststr,$input_val=''){
	 	return (isset($_REQUEST[$poststr]))?trim(filter_var($_REQUEST[$poststr],FILTER_SANITIZE_STRING)):strip_tags($input_val);
	 }
	
	/** Use postItems to post checkbox items in Array **/
     public function postItems($poststr){
		 $list ="";
		 if(!empty($_POST[$poststr])){
			 foreach($_POST[$poststr] as $value){
				 $list .= $value.",";
			 }
		   // use substr(trim($list),0, -1) or rtrim($list,",") to remove last comma
		  $list = substr(trim($list),0, -1); 	 
		 }
		 return $list;
	 }
	
	 public function postFiles($poststr,$input_val=''){
	 	return (isset($_FILES[$poststr]["name"]))?strip_tags($_FILES[$poststr]["name"]):strip_tags($input_val);
	 }

	/*@method postSessObject, setPostSession , getPostSession, delPostSession */
	 public function postSessObject(){
		 $this->post_sess = new \engine\core\Session;
		 return $this->post_sess;
	 }
	
	 public function setPostSession($session_name,$post_sessdata){
	  return $this->post_sess->setSession($session_name,$post_sessdata);
	 }
	
	 public function getPostSession($session_name){
		return $this->post_sess->getSession($session_name);
	 }
	 
	 public function delPostSession($session_name){
		return $this->post_sess->delSession($session_name);
	 }
	
	 public function isOk(){
		$isok = count($this->errorinfo);
		if($isok > 0){
		   $this->successflag = false;
		}else{
		   $isok = 0;
		   $this->successflag = true;
		}
		return $this->successflag;
	 }
	//displays both errors and success messages
	 public function message($msg=''){
	    //if no errors are found
            if(count($this->errorinfo)==0 && $this->successflag == true){
               $this->successmsg = $msg;
            }else{
               // Display Error Msg Here
               if(count($this->errorinfo)==1){
                     $this->successflag = false;
                     $msg = "There is ".count($this->errorinfo)." error in the form field";
               }elseif(count($this->errorinfo) > 1){
                     $this->successflag = false;
                     $msg = "There are ".count($this->errorinfo)." errors in the form fields";
               }	
            }
         
            if($this->successflag==true){
               $this->message = "<div class=\"alert alert-success\" role=\"alert\">".$this->successmsg."</div>";            
            }elseif(count($this->errorinfo)>0) {
               $this->message = "<p class=\"alert alert-danger\" role=\"alert\">".$msg."</p>";          
            }
            return $this->message;        
        
         
         } 
        
      /* This function validate input forms */
	 public static function validate(){
	 	//return new FormValidator;
	 	return new ValidateForms;
	 }
     public function validateForm(){
		return new ValidateForms;
	 }
}

?>