<?php
class Request
{
    protected $controller;
    protected $action;
    protected $params = [];
    protected $request;
    protected $regex;
    public function __construct(){
        $this->request = $_SERVER['REQUEST_URI'];
        var_export($this->request);
    }    

}

$request = new Request;
//$request;
echo $_SERVER['REQUEST_URI'];
?>