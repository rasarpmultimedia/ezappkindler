<?php
namespace engine\core;
class Error extends \Exception{
    protected $output;
    protected $message;
    protected $code;
    public $logfile;
    public function __construct($message,$logfile="error.log",$errorobj=null,$code = 0){
        parent::__construct($message,$code);
        $this->code = $code;
        $this->message = $message;
        $this->logfile = $logfile;
        if($errorobj==null){ 
            return $this->errorHandler($this);} 
        else {
            return $this->errorHandler($errorobj);   
        }    		
    }

    public function errorHandler($e){
         $output = $logerror="";
         $output .="Error Code: ".$e->getCode().PHP_EOL;
         $output .="An Error Occured: ".$e->getMessage()." in ".$e->getFile().PHP_EOL;
         $output .=" on Line Number ".$e->getLine().PHP_EOL;
         $output .="Trace: ".$e->getTraceAsString().PHP_EOL;
         $output .="File:  ".$e->getFile().PHP_EOL;
         $output .="on Line Number: ".$e->getLine().PHP_EOL;         
         $logpath = ERRORLOG.$this->logfile;
         $fs = new \engine\lib\FileSystem;
         $fs->writeToFile($logpath, $output, FILE_APPEND|LOCK_EX);
         return true;
      }  
      
      public function __toString() {
          parent::__toString();
      }
    }
?>