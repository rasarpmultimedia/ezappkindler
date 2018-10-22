<?php
namespace engine\core;
use engine\{ lib\ProcessForm as ProcessForm, 
components\Auth as Auth,
lib\Utility as Utility
};
abstract class Model{
    protected $registry;
    protected $session; 
    protected static $config;
    public $process;
    public $validate;
    public $collections   = [];//set collection array
    public static $params = [];
    public  $data = [];
    public  $dbtable;
    public  $request;

    public function __construct($params=[]){             
        $this->request  = new Request;
        $settings = new Settings;       
        $config = $settings->config();
        $this->dbtable = $config["Tables"];
	$this->session = new Session;
        $this->util    = new Utility;
	$this->process = new ProcessForm;	
        $this->validate= $this->process->validate();
        $this->data  = !empty($this->data)?$this->data:[];
        static::$params = !empty($params) ? $params : null;
    }
    
    protected static function database(){
         return new SQLRecords;
    }
    
    public function auth($auth=null){
        $auth = is_object($auth)?$auth: new Auth;
        return $auth;
    }
    
    protected function setSession($name,$value){
        return $this->session->setSession($name, $value);
    }
    
    protected function getSession($name){
        return $this->session->getSession($name);
    }
    
    protected  function setCookie($name, $value, $expire="0", $domain="/", $secure=false, $httponly=false){
        return $this->session->setCookie($name, $value, $expire, $domain, $secure, $httponly);
    }
    
    protected function cookie($name){
        return $this->session->getCookie($name);
    }


    public function getGlobal($var){ return $GLOBALS[$var];}
    public function setGlobal($name,$var){ return $GLOBALS[$name] = $var;}
    
    public function setData($key,$value){
        return $this->data[$key] = $value; 
    }
	 
    public function data($key){
        return array_key_exists($key, $this->data)?$this->data[$key]:null;        
    }
	 
    public function hasData($key){
        return isset($this->data[$key]);
    }
    
    public function removeData($key){
     unset($this->data[$key]);
    }
	 
}
