<?php
namespace ezappkindler;
/** Loads Library classes */
error_reporting(E_ALL);
date_default_timezone_set("Africa/Accra");

require_once "dir_paths.inc.php";
require_once "global_const.inc.php";
//debuger
function _debug($data){
    echo"<pre>";var_dump($data);echo"</pre>";	
}

spl_autoload_extensions(".php,.class.php,.comp.php,.inc");
spl_autoload_register("spl_autoload",false);

use engine\{
    core\Request,
    core\Router,
    core\Registry
};
//var_dump($_SERVER);
/** Using Composer autoload  */
//require_once VENDOR.'autoload.php';
/*Loading Extensions*/
 //require_once EXTENSIONS."shopping/shoppingcart.php";
/*/Registry classes initialisation*/
$registry = new Registry;
$registry->config  = new \engine\core\Settings;
//$registry->auth = new \engine\components\Auth;

/** Setting URL Partterns
*	url = http://localhost/ezappkindler/?/order/sells/id
*	SET_QSTR_FORMAT : Request::URL_PATTERN;
**/
(defined("SET_URL_FORMAT")) ?null:define("SET_URL_FORMAT",Request::URL_PATTERN);
