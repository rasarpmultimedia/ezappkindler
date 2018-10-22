<?php
namespace engine\core;
 class View extends Template{
    public $view_file;
    public $view_data = [];
    public $blocks = array();
    protected static $TEMPLATE_DIR;
    public static $SET_TEMPLATE;
    const DS = DIRECTORY_SEPARATOR;

    public function __construct($filename,array $data){
        $this->view_file = !empty($filename)?$filename:"";
        $this->view_data = (is_array($data)?$data:[]);
        self::$TEMPLATE_DIR = self::$SET_TEMPLATE;
        //array_push($this->blocks, $this->view_data);
        $this->blocks = $this->view_data;
    }
    
    public function setView($key,$value){
        if(array_key_exists($key, $this->view_data)){$this->view_data[$key] = $value;}
        return true;
    }
	
    public function getView($key){
       return array_key_exists($key, $this->view_data)?$this->view_data[$key]:null;
    }
    
    public function setHTMLBlock($key,$value){
        if(!array_key_exists($key, $this->blocks)){return $this->blocks[$key] = $value;}        
     }
    public function getHTMLBlock($key){
        return array_key_exists($key, $this->blocks)?$this->blocks[$key]:null;
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
    public function render(View $view=null,$filename=''){
        $view = (is_object($view)?$view : new self);
        $this->loadData($this->view_data);
        $this->loadTemplate($view,$filename);
    }
    
    /*Loads Web Resource to ajax application */
    public function loadResource(View $view=null,$filename=''){
        $view = (is_object($view)?$view : new self);
        $this->loadData($this->view_data);
        $this->view_file = $filename?$filename.".php":$this->view_file.".php";        
        if(file_exists(VIEW.$this->view_file)){
          require_once VIEW.$this->view_file;
        }else{
            echo '404-File:'.VIEW.$this->view_file.' not found';
        }   
    }
    
    
   
    /**
    *Loads Template File
    **/ 
  protected function loadTemplate($view, $filename){
        $output ='';
        $this->view_file = $filename?$filename.".phtml":$this->view_file.".phtml";
        ob_start();
        if(file_exists(VIEW.$this->view_file)){
          require_once VIEW.$this->view_file;
          $output = ob_get_contents();
        }        
        ob_end_flush();
        return $output;   
   }
   
   public static function getPublicDir($dir='') {
       if(empty($dir)){ $dir = self::$SET_TEMPLATE;}        
       return BASE_URL."public/".$dir."/";
   }
   
   public function __toString(){
        return $this->view_data;
  }
}