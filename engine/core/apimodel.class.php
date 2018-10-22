<?php
namespace engine\core;
use engine\components\JSONData;

class APIModel extends Model{    
    public  $request;
    protected $json;
    protected $xml;
    protected $apikey;
    
    /*public function __construct($params = []) {
        parent::__construct($params);
    }*/    
    
    public function newJSONObj($data=[]){
        $this->json = new JSONData($data); 
        return $this->json;
    }
    public function newXMLObj(){
        $this->xml  = new \SimpleXMLElement; 
        return $this->xml;
    }
    
    public function requestMethod($name,$reqmethod = 'get'){
        switch ($reqmethod) {
            case "get": return $_GET[$name]; break;
            case "post": return $_POST[$name]; break;
            default:
                return $_GET[$name];
            break;
     
        }
    }
    
}

