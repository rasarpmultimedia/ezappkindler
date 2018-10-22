<?php
/*
 * This are defined paths to all main directories in the ezAppKindler v_1.1
 * Coder: Abdul-Rahman Sarpong
 * (c) Rasarp Multimedia Systems
 * */
//Set include Path
$path = realpath(dirname(__DIR__));
defined("DS")?null:define("DS",DIRECTORY_SEPARATOR);
defined("_PATH")?null:define("_PATH", $path.DS);

################################################
#FRAMEWORK DIRECTORY CONSTANTS (FRAMEWORK DIRS)#
################################################
defined("INC") 		?null:define("INC",    _PATH."include".DS);
defined("ENGINE") 	?null:define("ENGINE", _PATH."engine".DS);
defined("APP")		?null:define("APP",    _PATH."app".DS);
defined("EXTENSIONS")   ?null:define("EXTENSIONS",_PATH."extensions".DS);
defined("CORE") 	?null:define("CORE",   ENGINE."core".DS);
defined("LIB") 		?null:define("LIB",    ENGINE."lib".DS);
defined("CONFIG") 	?null:define("CONFIG", INC."config".DS);
defined("COMPONENTS")   ?null:define("COMPONENTS", ENGINE."components".DS);


###############################################
#APPLICATION DIRECTORY CONSTANTS (SYSTEM DIRS)#
###############################################
defined("VENDOR")       ?null:define("VENDOR", _PATH."vendor".DS);
defined("PUBLIC_DIR")   ?null:define("PUBLIC_DIR", _PATH."public".DS);
defined("MULTIMEDIA")   ?null:define("MULTIMEDIA",PUBLIC_DIR."multimedia".DS);
defined("WEBROOT")      ?null:define("WEBROOT",  APP."webroot".DS);
defined("ERRORLOG")     ?null:define("ERRORLOG",PUBLIC_DIR."log".DS);
defined("RESOURCES")    ?null:define("RESOURCES",APP."resources".DS);
defined("CONTROLLER")   ?null:define("CONTROLLER",WEBROOT."controller".DS);
defined("MODEL")        ?null:define("MODEL",WEBROOT."model".DS);
defined("VIEW")         ?null:define("VIEW",WEBROOT."view".DS);
defined("API")          ?null:define("API",VIEW."api".DS);
defined("PARTIALS")     ?null:define("PARTIALS",VIEW."partials".DS);
#########################################
#SET FRAMEWORK PATHS                    #
#########################################
$set_paths = [CONTROLLER,MODEL,CORE,LIB,COMPONENTS];
set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR,$set_paths));