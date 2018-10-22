<?php
//include_once "request.class.php";
class RouterError extends Error{};
class Router{
    protected $request;
	public $id;
	protected $controller;
	protected $action;
	public $title;
	
    public function __construct(Request $request,$registry){
      $this->registry = $registry;
	  $this->request = $request;
	  $this->controller = $this->request->controller;
	  $this->action = $this->request->action;
	  $this->id = empty($this->request->id)?1:intval($this->request->id);
	  $this->title =  $this->request->title;
		
	   if(($this->controller==null && $this->action==null)){
		    $this->controller = "index";
			$this->action = "index"; 
		   
		}elseif((isset($this->controller) && is_null($this->action))){
			$this->controller = $this->controller;
			$this->action = "index";
		}else{
		   $this->controller = $this->controller;
	  	   $this->action = $this->action;
	   }
		$this->controller = ucfirst($this->controller);
    }

    //Load Controller
    public function loadController($path){
     $path = $this->_getFilePath($path);
     $ctrlhandler  = $path."controller/".lcfirst($this->controller);
     $modelhandler = $path."model/".lcfirst($this->controller);
       
        $this->_incControllerClass($ctrlhandler);
        $this->_incModelClass($modelhandler);
			
       /*Call Controller Class and Model Class */
        $controller = $this->controller."_Controller";
        $model      = $this->controller."_Model";
		$model_object = class_exists($model)? new $model($this->registry):null;
        $ctrl_object  = class_exists($controller)? new $controller($this->registry,$model_object):null;
	
        if(is_object($ctrl_object)){
               $action = $this->action;
               $callback_action  = is_callable([$ctrl_object,"$action"]);
              if($callback_action){
                  $callaction = $ctrl_object->$action();
              }else {
                  $callaction = false;
				  
              }
             
            return $callaction;
        }
    }
     
    private function _getFilePath($path){
        if(!is_dir($path)){
          throw new RouterError("Error: Invalid file path-- {$path}");
        }
     
        return $path;
    }
    //include Model
    private function _incModelClass($name){
      $file = strtolower($name."_model.php");
      if(file_exists(strtolower($file))){
       include_once $file;
      }else{
	   throw new RouterError("Error: Invalid file -- {$file}");
       return false;
      }
    }
    //include Controller
    private function _incControllerClass($name){
      $file = strtolower($name."_controller.php");
      if(file_exists(strtolower($file))){
	   $file = strtolower($name."_controller.php");
       include_once $file;
      }else{
		  echo "<h1>Controller ".strtolower($name."_controller.php")." not found</h1>";
		  throw new RouterError("Error: Invalid file -- {$file}"); 
		  return false;
      }
    }

}
?>