<?php
namespace engine\core;
class Request{
     protected $route  = [];
     protected $params = [];
     protected $pattern;
     protected $request;
     const URL_PATTERN = '\/?(?<controller>[a-z0-9-]+)\/?(?:(?<action>[a-z0-9-]+))?(?:\/?(?<params>.*)?)';
     
    public function __construct($pattern=self::URL_PATTERN){
        if(isset($_SERVER['QUERY_STRING'])){
             //var_dump($_SERVER['QUERY_STRING']);
             $this->request = urldecode($_SERVER['QUERY_STRING']);         
        }elseif(isset($_SERVER['PATH_INFO'])){
             $this->request = urldecode($_SERVER['PATH_INFO']);		       
        }        
        
        $this->pattern = $pattern;
        if(!empty($this->pattern)){
            $this->route = $this->matchRequest($this->request,$this->pattern); 
            //var_dump($this->route);
        }        
    } 

    protected function matchRequest($url,$pattern){
		 if(preg_match('#'.$pattern.'#i',$url,$matches)){                        
                    $this->route = [
			 "controller"=>(!empty($matches["controller"])?$matches["controller"]:""),
			 "action"=>(!empty($matches["action"])?preg_replace("/[ -]/","",$matches["action"]):""),
			 "params"=>(!empty($matches["params"])?$matches["params"]:"")
                        ]; 
                 $this->params = $this->route["params"];                 
                 return $this->route;                 
		 }    
    }   
    
    public function getAction(){
        return $this->route["action"]?$this->route["action"]:null;
    }

    public function getParams(){
        return $this->params?$this->params:null;
    }

    public function __set($index,$value){
         $this->route[$index] = $value ;
    }
    
    public function __get($index) {
         if(!empty($this->route)){
           return $this->route[$index];			 
        }
    }
    
    public function __toString() {
        return "";
    }
}
?>