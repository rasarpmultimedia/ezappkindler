<?php
namespace engine\core;
 abstract class Template{
    public $view_file;
    public $view_data = [];
    
    public function __construct($filename,array $data){
        $this->view_file = $filename;
        $this->view_data = $data;
    }
   abstract public function render(View $view=null,$filename='');
   abstract public function loadResource(View $view=null,$filename='');
   abstract public function setView($key,$value);
   abstract public function setHTMLBlock($key,$value);
   abstract public function getHTMLBlock($key);
   abstract protected function loadData(array $data);
   protected static function getPublicDir(){}
}