<?php
namespace engine\core;
class Session{
    public function __construct(){
        $session_status = \session_status();
        if($session_status == PHP_SESSION_ACTIVE || \session_id()){			
            \session_regenerate_id();                        
        }else{
            \session_start();            
        }        
    }

   public static function database(){
       return new SQLRecords;
   }

   public function setSession($name,$sess_value){
       if(!\in_array($name, $_SESSION)){
           return $_SESSION[$name] = $sess_value;           
       }
   }

   public function getSession($name){
       return isset($_SESSION[$name])?$_SESSION[$name]:null;
   }

   public function delSession($name){
   	   unset($_SESSION[$name]);
	   return \session_destroy();
   }

//Set Cookies
   public function setCookie($name,$value,$expire=0,$domain="/",$secure=false,$httponly=false){
     return \setcookie($name,$value,$expire=0,$domain="/",$secure=false,$httponly=false);
   }
   public function getCookie($name){
      return isset($_COOKIE[$name])?$_COOKIE[$name]:null;
   }
}

?>
