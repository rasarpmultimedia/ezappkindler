<?php
use engine\core\Controller;
class Blog_Controller extends Controller{
    protected  $view;
    protected  $model;
    protected  $data = [];
    
    public function index(){
        $util = new \engine\lib\Utility;
        $this->model = $this->model([]);
        $this->data = $this->model->indexModel();		
        $this->data["title"] = "Blog Page";
        $this->view  = $this->view("frontend/post", $this->data);
        $this->view->setView("title",$this->data["title"]);
        $this->view->render($this->view);        
 }
    public function post($id='',$title=''){
       echo "Post Page  has ID: $id ,Title: $title";
    }
}