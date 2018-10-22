<?php

class View extends Template {
    public $HTML = array();
    public $data = array();
    public $query_result;
    public $setmodel;
    public $setauth;

    public function __construct($htmlholder=[]){
     $this->HTML = [
         "Header"  =>"", "Meta"=>"",
         "Keywords"=>"","Description"=>"",
         "Author" =>"","Stylesheet"=>"","Scripts"=>""   
     ];
	$this->HTML = array_merge($this->HTML,$htmlholder);
    }
    
    //Adds an HTML element to data array
    public function addElement($element,$location="Content"){
    	//$location = strtolower($location);
		$this->HTML[$location] .= $element;           
    }
    
    protected function setView($key,$value){
    	//$key = strtolower($key);
        if(array_key_exists($key, $this->HTML)){$this->HTML[$key] = $value;}
	}
	
    public function getView($key){
   	    //$key = strtolower($key);
       return array_key_exists($key, $this->HTML)?$this->HTML[$key]:null;
    }
    
   /* Loads Data into Template File */
    protected function loadData(array $data){
         if(is_array($data)){
             foreach($data as $key=>$output){
                 $this->setView($key, $output);
            }
        }	
    }
    
    /*Loads Web Page to screen or renders web page */
    public function render(View $view,$name,$layoutfile=""){
        $this->setAuth();//set authentication to view 
        $file = "{$name}.phtml";
        $layoutfile = (!empty($layoutfile))?$layoutfile:"layout";
        $this->setModel();//set database records to view 
		if(file_exists($file)){
            include_once $file;	
            $this->data = isset($data)?$data:array();
            $this->loadData($this->data);
            $this->loadTemplate($view, $layoutfile);
        }else{
			include_once TEMPLATE.LAYOUT_DIR."/_html/404.phtml";
			trigger_error("File not found: {$file} <br> ",E_USER_ERROR);
    	} 
    }
   
	/**
    Loads Template File
    **/ 
    public static function loadTemplate($view,$file){
    	 $file = TEMPLATE.LAYOUT_DIR."/_html/{$file}.phtml";
		 $output ='';
        /*Buffering Page */
    	 if(file_exists($file)){
    	 	ob_start();
             include_once $file;
			 $output = ob_get_contents();
             return $output;
			ob_end_clean();
    	 }else{
    	 	trigger_error("Template not found: {$file} <br> ",E_USER_ERROR);
    	 } 
    }
    
    /** Gets Results from SQL Query **/
    public function queryResult(){
     $this->query_result = isset($this->query_result)?$this->query_result:null;
     return $this->query_result;
    }
    
    /** Sets Database Object form View (model) **/ 
    public function setModel(){
     $this->setmodel = isset($this->setmodel)?$this->setmodel:null;
     return $this->setmodel;
    }
    /** Sets Session Object form View (authenticate) **/
    public function setAuth(){
     $this->setauth = isset($this->setauth)?$this->setauth:null;
     return $this->setauth;
    }
	
}
//$template = new Template();


