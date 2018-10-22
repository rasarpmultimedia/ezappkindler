<?php
namespace engine\core;
class Router{
    protected $controller;
    protected $action;
    protected $registry;
    protected $params = [];
    public static $id;
    public $request;
    public function __construct(Registry $registry=null,$pattern=''){
        $this->request = new Request($pattern);
        $this->registry = $registry;
        $this->controller = $this->request->controller? ucfirst($this->request->controller) : "Home";
        $this->action = $this->request->action ? lcfirst($this->request->action) : "index";
        $this->params = !empty($this->request->getParams())? explode("/",$this->request->getParams()) : [];
        $this->loadController();       
    }

    protected function loadController(){
        $appcontroller = $this->controller."_Controller"; 
        if(file_exists(CONTROLLER.strtolower($appcontroller).".php")){
            $this->controller = new $appcontroller($this->registry);
            if(method_exists($this->controller, $this->action)){
                call_user_func_array([$this->controller,$this->action],$this->params);                
            }else{
                require_once WEBROOT."view".DIRECTORY_SEPARATOR."404.phtml";
            }          
        }
            return true; 
    }
}