<?php

/** Application constants **/
(defined("FRAMEWORK")) ?null:define("FRAMEWORK" , "EzAppKindler");
(defined("APP_NAME")) ?null:define("APP_NAME" , "ilapi.org");
(defined("APP_VERSION")) ?null:define("APP_VERSION" , ".v1.2.0");
(defined("QUERYSTRING")) ?null:define("QUERYSTRING","");//append query string ?

if($_SERVER["SERVER_ADDR"]==="::1"){
(defined("SITE")) ?null:define("SITE", $_SERVER["SERVER_NAME"]."/".APP_NAME.'/');
}else{
 (defined("SITE")) ?null:define("SITE",$_SERVER["SERVER_NAME"]."/"); 
}
/** Site Layout constants
 *  This help set whole application themes and templates directories
**/

(defined("FRONTEND_HEADER")) ?null:define("FRONTEND_HEADER" , "");
(defined("FRONTEND_FOOTER")) ?null:define("FRONTEND_FOOTER" , "");

(defined("COPYRIGHTS")) ?null:define("COPYRIGHTS" ,"<p class=\"copy_text\"> &copy; Rasarp Multimedia Inc and <i>".APP_NAME.APP_VERSION."&trade; </i>- ".date("Y",time())."</p>");

(defined("BACKEND_HEADER")) ?null:define("BACKEND_HEADER", "");
(defined("BACKEND_FOOTER")) ?null:define("BACKEND_FOOTER", "");

(defined("BACKEND_COPYRIGHT")) ?null:define("BACKEND_COPYRIGHT" , "<p class=\"copy_text\"> &copy; Rasarp Multimedia Inc & Powered by ".FRAMEWORK.APP_VERSION."&trade; - ".date("Y",time())."</p>");

/*Set Base URL for the Application */

if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]=="on"){
  (defined("URI")) ?null:define("URI" ,"https://".SITE);//use this on production server
}else{
   (defined("URI")) ?null:define("URI" ,"http://".SITE);//use this on production server
}

(defined("BASE_URL")) ?null:define("BASE_URL" ,URI);
//var_dump($_SERVER);
?>
