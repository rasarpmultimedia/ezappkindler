<?php
namespace engine\lib;
class ValidateForms{
		
        protected $error_string = [];
        protected $form_fields = [];
        protected $display_errors = [];
        public    $regex = [];
        public    $upload_fieldname="";

        public function __construct(){
            return;
        }
        /* Checks to find if input fields. */
        public function checkInputField(array $form_fields,$error='is required'){
             foreach ($form_fields as $fieldname) {
                 if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){
                     $this->error_string[$fieldname] = "".ucfirst($fieldname)." {$error}";
              }
            }
            return $this->error_string;
        }

     
 /* Checks to find if passwords match in two fields. */
 public function checkPassword($password,$confirmpass){
	    $pass = isset($_POST[$password])?$_POST[$password]:null;
	    $cpass = isset($_POST[$confirmpass])?$_POST[$confirmpass]:null;
 	    if(strcasecmp($pass,$cpass) != 0){
		$this->error_string[$confirmpass] ="* Password entered did not match"; 
		}
   return $this->error_string;
  }

  //Checks for required fields length //key =>value pair
   public function checkPasswordLength(array $pass_len_array){
 	   foreach($pass_len_array as $fieldname => $minlen){
	  	if(!empty($_POST[$fieldname])){
         if(strlen(trim($_POST[$fieldname])) < $minlen){
             $this->error_string[$fieldname] = "* Password is too short must be at least {$minlen} characters";
              }  
	  	  }
	  }
	 return $this->error_string;
  }
    
  //Checks for bad characters if field names
 public function checkUsernameChars(array $required_array){
 	     foreach ($required_array as $fieldname) {
		  if(!empty($_POST[$fieldname]) && preg_replace("#[a-zA-Z0-9_-]#i","",$_POST[$fieldname])){
            $this->error_string[$fieldname] = "".ucfirst($fieldname)." is not allowed";
	  	  }
	   }
	 return $this->error_string;	
 }

    //Check for Select Option is null;
    public function checkSelectField(array $option_array){
        foreach($option_array as $option =>$selval){
            if($_POST[$option] == $selval){
                $this->error_string[$option] = ucfirst($option)." is required";
            }
        }
        return $this->error_string;
    }
    /*  Check email fields */
    public function checkEmail($emailaddr){
        if(!empty($_POST[$emailaddr]) && !preg_match( "#^[_\w-]+(\.[_\w-])*@[_\w-]+(\.[\w]+)*(\.[\w]{2,3})$#i",$_POST[$emailaddr])){
            $this->error_string[$emailaddr] ="* Invalid email address provided";
        }
        return $this->error_string;
    }


   //Checks for required fields length //key =>value pair
   public function checkFieldLength(array $required_len_array){
 	   foreach($required_len_array as $fieldname => $maxlen){
	  	  if(!empty($_POST[$fieldname]) && strlen(trim($_POST[$fieldname],"\r\n")) < 3){
	  	  $this->error_string[$fieldname] = "*".ucfirst($fieldname)." is too short";
	  	  }
	  	  if(!empty($_POST[$fieldname]) && strlen(trim($_POST[$fieldname],"\r\n")) > $maxlen){
	  	  $this->error_string[$fieldname] = "*".ucfirst($fieldname)." is too long";
	  	}
	  }
	 return $this->error_string;
  }


  //Checks for bad characters if field names
 public function checkFieldChars(array $required_array,$regex="#[a-zA-Z0-9\- ]#i"){
 	     foreach ($required_array as $fieldname) {
             if(!empty($_POST[$fieldname]) && preg_replace($regex,"",$_POST[$fieldname])){
            $this->error_string[$fieldname] = "* Invalid character in ".ucfirst($fieldname)." is not allowed";
	  	  }
	   }
	 return $this->error_string;	
 }

  /*** Checks if file uploaded is verified */
  public function checkUploadFiletype($filetype,array $mimetype, $filename){
      //var_dump($filetype);
  	if(!array_key_exists($filetype,$mimetype) && !empty($filename)){
	  	 $this->error_string[$this->upload_fieldname] = "".ucfirst($filename)." is not allowed,choose the correct format"; 
		}
    return $this->error_string;
  }
   /*** Checks if file uploaded is already exists */
  public function checkUploadFileExist($filelocation,$filename){
  	    if(file_exists($filelocation) && !empty($filename)){
	   	$this->error_string[$this->upload_fieldname] = "*File ".ucfirst($filename)." already exists";
	   	}
		return $this->error_string;
  }
   /*** Checks if file uploaded file size */
  public function checkUploadFileSize($filesize,$filename){
  	   if(($filesize > $_REQUEST['MAX_FILE_SIZE'] || $filesize > UploadFiles::SET_MAX_FILE_SIZE)&& !empty($filename)){
	     $this->error_string[$this->upload_fieldname] = "File ".ucfirst($filename)." is too large";
	  	}
	   return $this->error_string;
  }

 /* Checks dates by formates in dd/mm/yyyy */
 public function checkFieldDate($dateformat='09/10/2013'){
 	$regex ="~^[0-9]{2}\\/[0-9]{2}\\/[0-9]{4}|[0-9]{2}\-[0-9]{2}\-[0-9]{4}$~";
     if(!empty($_POST[$dateformat]) && !preg_match($regex, $_POST[$dateformat])){
     	$this->error_string[$dateformat] ="* Invalid date format,must be dd/mm/yyyy";
      }
	 return $this->error_string;;
 }

public function checkFieldDigit($fieldname){
 	    //$fieldname = ($fieldname=="phone")?"/[0-9-?]/":"/[0-9]/";
 		if(!empty($_POST[$fieldname]) && !preg_match('/^[\d\.?]+$/i',$_POST[$fieldname])){
		$this->error_string[$fieldname]= "{$fieldname} must be digits";
		}
	return $this->error_string;
 }
		
 /* Display Errors in a form */
 public function displayErrors(array $error_array){
	$output = "<ol class=\"text-error\">";
	foreach($error_array as $error) {
	  $error = str_ireplace("_", " ", $error);
	  $output .="<li> " . $error . "</li>";
    }
	$output .="</ol>";
	return $output;
 }
  /* Display Errors in a form */
 public function displayError(array $err_array, $fieldname){	
 	if(is_array($err_array)){
 	   foreach($err_array as $key=>$value){
 	  	$err_array[$key] = str_ireplace("_", " ", $value);
 	    }
 	    if(array_key_exists($fieldname, $err_array)){
           return $err_array[$fieldname];
 	    }
	 }
   }

}?>