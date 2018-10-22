<?php
/**
 *Router Class to control page routes
 *This class would direct how url to pages will be formated
 *Router partterns e.g. alphanum:Page/alpha:action/num:id or -alphanum:topic
 *url = index.php?route=controller/action/id:pagetargethere route formats
 *url = http://localhost/ezappframework/test/?buy/56/coin/ route formats
 *url = http://localhost/ezappframework/?/order/15/sells/this-is-the-ttile
 **/

 class Request{
     protected $request;
     protected $params = [];
	 protected $pattern;
	 
     const REGEX_DEFUALT = '\/?(?P<controller>[a-z0-9-]+)\/?(?:(?P<action>[a-z0-9-]+))?(?:\/?(?P<target>[a-z0-9-]+)?)';
	 const REGEX_NORMAL   = '\/?(?P<controller>[a-z0-9-]+)\/(?P<action>[a-z0-9-]+)\/(?P<id>(?:\d+))?(?:\/?(?P<target>[a-z0-9-]+)?)';
	 const REGEX_CENTERID = '\/?(?P<controller>[a-z0-9-]+)\/(?P<id>(?:\d+))\/(?P<action>[a-z0-9-]+)?(?:\/?(?P<target>[a-z0-9-]+)?)';
	 
     public function __construct($querystr="",$pattern=null) {
		 
          if(isset($_SERVER['QUERY_STRING'])){
			  //var_dump($_SERVER['QUERY_STRING']);
			  $this->request = strval($_SERVER['QUERY_STRING']);
			  
		  }elseif(isset($_SERVER['PATH_INFO'])){
			  //var_dump($_SERVER['PATH_INFO']);
			  $this->request = strval($_SERVER['PATH_INFO']);
		  }
       $this->pattern = $pattern;
	  //$route = $this->formatUrl($querystr,self::REGEX_CENTERID);
     
		 if($this->formatUrl($this->request,$this->pattern)){
			$params = $this->formatUrl($this->request,$this->pattern);
		 }else{
			$params = $this->formatUrl($this->request,Request::REGEX_DEFUALT);
		 }
		 return $params;
		 //var_dump($this->params);
		
     }
	 
	 public function formatUrl($url,$pattern=Request::REGEX_DEFUALT){
		 if(preg_match('#'.$pattern.'#i',$url,$matches)){
			$this->params = [
			 "controller"=>(!empty($matches["controller"])?$matches["controller"]:null),
			 "action"=>(!empty($matches["action"])?$matches["action"]:null),
			 "id"=>(!empty($matches["id"])?$matches["id"]:null),
			 "target"=>(!empty($matches["target"])?preg_replace("~^\s$~","-",$matches["target"]):null)
			];
		 }
		return $this->params;
	 }
	 
	 public function getId(){
		 return $this->params["id"]?$this->params["id"]:null;
	 }
     public function __set($index,$value){
         $this->params[$index] = $value ;
     }

     public function __get($index)   {
         if(array_key_exists($index,$this->params)){
           return $this->params[$index];
			 
         }

     }

}
 /*/Router */
 
 $req = new Request("",Request::REGEX_CENTERID);
 echo "<pre>";
//var_dump($req);
 echo "<br>";

echo "Id: {$req->id} --++-- Controller: {$req->controller}_controller.php --++-- Action: {$req->action}<br><br>";
echo $req->target;
 echo "</pre>";

?>