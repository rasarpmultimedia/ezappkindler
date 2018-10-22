<?php
namespace engine\core;

class Registry{
	private $register = array();
	
	public function __set($key,$value){
		$this->register[$key] = $value;
                
        }
    
	public function __get($key){
		if(array_key_exists($key, $this->register)){
			return $this->register[$key];
		}
                
        }
    
	public function __isset($key){
		return isset($this->register[$key]);
	}
	public function __unset($key){
		unset($this->register[$key]);
	}
}