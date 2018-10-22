<?php
require_once "include/init.php";
use engine\core\{Dispatcher as Dispatcher,View as View};
View::$SET_TEMPLATE= "default_theme";
new Dispatcher($registry,SET_URL_FORMAT);//SET_URL_FORMAT

