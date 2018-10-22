<?php
use engine\core\Controller;
class Home_Controller extends Controller{
    protected  $view;
    protected  $model;
    protected  $data = [];
    
    public function index($id='',$title=''){
        $util = new \engine\lib\Utility;
        $this->model = $this->model(["id"=>$util->validID($id),"title"=>$title]);
        $this->data = $this->model->indexModel();
        $this->data["title"] ="Home Page";
        $this->view  = $this->view("frontend/index", $this->data);
        $this->view->setView("title",$this->data["title"]);
        $this->view->render($this->view);        
 }
 
    public function article($id='',$title=''){
       echo "Article Page  has ID: $id ,Title: $title";
    }
}