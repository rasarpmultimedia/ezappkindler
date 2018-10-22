<?php
namespace engine\core;
//use engine\core\Router as Router;
class Dispatcher{
    //$pattern = REGEX_DEFUALT|REGEX_NORMAL|REGEX_CENTERID    
    public function __construct($registry=null,$pattern = Request::REGEX_DEFUALT){
        $router = new Router($registry,$pattern);
        return $router;
    }    
}



