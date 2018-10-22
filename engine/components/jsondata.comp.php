<?php
namespace engine\components;
class JSONData implements \JsonSerializable {
    protected $jsondata = array();
    public $opt_as =["pretty"=>JSON_PRETTY_PRINT,
                     "object"=>JSON_FORCE_OBJECT,
                     "num_check"=>JSON_NUMERIC_CHECK,
                     "preserve_zero"=>JSON_PRESERVE_ZERO_FRACTION
                    ];
    
    function __construct(array $jsondata_array){
       $this->jsondata = $jsondata_array;
    }

    public function jsonSerialize() {
        return $this->jsondata;
    }
        
    public function encodeJSON($data,$opt=JSON_PRETTY_PRINT){
        return \json_encode(new JSONData($data),$opt);
    }
    
    public function decodeJSON($data,$assoc = false){
        $this->jsondata = $data;
        return \json_decode($this->jsondata,$assoc);
    }
}

?>