<?php
/**
 *Router Class to control page routes 
 *This class would direct how url to pages will be formated
 *Router partterns e.g. alphanum:Page/alpha:action/num:id or -alphanum:topic
 *url = index.php?route=controller/action/id:pagetitlehere route formats
 **/
 class Request{
    public $routes = [];
	public $params = [];
	 
	 function __construct(){
		 
	 }
	 
	public function addRoute($route,$params=array()){
		 /** Match Replace forward slahes with Backslahes **/
		 $route = preg_replace('/\//','\\/',$route);
		 
		 /** Match {controller}/ **/
		 $route = preg_replace('/\{([a-z]+)\}/','(?P<\1>[a-z]+)',$route);
		 
		 /** Match {controller}/{id:\d+}/{action} **/
		 $route = preg_replace('/\{([a-z]+):([^\}]+)\}/','(?P<\1>\2)',$route);
		 
		 $route ='/^'.$route.'$/i';
		 $this->routes[$route] = $params;
	 }
	 
	public function getRoute(){
		 return $this->routes;
	 }
	
	public function match($url){
		foreach($this->routes as $route=>$params){
		 if(preg_match($route,$url,$matches)){
			 foreach($matches as $key=>$match){
				 if(is_string($key)){
					 $params[$key] = $match;
				 }
			 }
			 $this->params = $params;
			 //return true;
		  }
		}
		//print_r($this->params) ;
	 }
	 
	public function getParams(){
		 return $this->params;
	 }
	protected function removeQueryString($url){
		if($url!=''){
			$parts = explode("&",$url,2);
			if(strpos($url,$parts[0])===false){
				$url = $parts[0];
			}else{
				$url='';
			}
		}
		return $url;
	}
	
	public function dispatch($url){
		$url = $this->removeQueryString($url);
		var_dump($url);
		if($this->match($url)){
				try{
					$controller = $this->params['controller'];
					$controller = $this->convertToStudyCaps($controller);
					print_r($url);
					if(class_exists($controller)){
						$controller_obj = new $controller($this->params);
						$action = $this->params['action'];
						if(is_object($controller_obj)){
							if(is_callable([$controller_obj,'$action'])){
							 $controller_obj->$action();
							}
						}else{
							throw new Exception("Method $action (in controller $controller) not found");
						}
					}else{
						throw new Exception("Controller class $controller not found");
					}
				}catch(Exception $e){

				}
		}
	}
	 
	public function convertToStudyCaps($string){
		return str_replace(' ','',ucwords(str_replace('-','',$string)));
	}
	public function convertToCamelCase($string){
		return lcfirst($this->convertToStudyCaps($string));
	}
 
}
/** Router */
 $req = new Request;
 $req->addRoute("",["controller"=>"Home","action"=>"index"]);
 $req->addRoute("{controller}/{action}",["controller"=>"news","action"=>"index"]);
 $req->addRoute("{controller}/{id:\d+}/{action}");
 $req->addRoute("dashboard/{controller}/{id:\d+}/{action}");
 echo "<pre>";
 var_dump($req->getParams());
 //var_dump($req->removeQueryString($_SERVER['QUERY_STRING']));
 //echo $req->match($_SERVER['QUERY_STRING']);
 //echo $_GET['route'];
 echo $req->dispatch($_GET['route']);

 echo "<br>";
 echo "Id: {} --++-- Controller: {}_controller.php --++-- Action: {}<br><br>";
 echo "</pre>"; 

?>