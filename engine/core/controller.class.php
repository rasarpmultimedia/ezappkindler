<?php
namespace engine\core;
abstract class Controller{
    protected $view;
    protected $registry;
    protected $data = [];
    private   $controller;
    private   $model_class;
    
    public function __construct(Registry $registry=null) {
        $request = new Request;
        $this->registry = $registry;
        $this->controller = $request->controller? ucfirst($request->controller) : "Home";
        $this->model_class = $this->controller."_Model";
    }
    
    abstract public function index();
    
    protected function view($filename,$data=array()) {
        return new View($filename,$data);        
    } 
    
    protected  function model($params=[]){
        if(file_exists(MODEL.strtolower($this->model_class).".php")){
            require_once MODEL.strtolower($this->model_class).".php";
            if(class_exists($this->model_class)){
                return new $this->model_class($params);
            } else { new Error("Class Name ".$this->model_class." does not exist please...");}
           
        }else{
            new Error("The File ".$this->model_class." could not be found...");           
        }   
    }
}
