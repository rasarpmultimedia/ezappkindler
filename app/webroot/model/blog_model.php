<?php
use engine\core\Model;
class Blog_Model extends Model{
    protected $id;
    public $title;
    public $data = [];
    
    public function indexModel(){
        $database = static::database();		
        $params   = static::$params;
	   
        //$this->id = $params["id"];
        //$this->title = $params["title"];
        //$query = $database->initQuery($this->dbtable["Member"]);
        
        $this->data =[
            "title"=>"Welcome to New Blog",
            "message"=>"Rapid Application Development (RAD) framework developed in "
            ."PHP implementing MVC architecture and has many build-in libraries to make web development easier and faster."
            ,"developer"=>"Developed by Sarpong Abdul-Rahman .D, Mobile: +233271957502, Email: fadanash@gmail.com",
            "db_list"=>[
                "Kofi","Ama","Selina Arthur","Saadatu","Awo","Khadijah","Mariam","Abdul-Salam"
                ]
        ];
	  $this->collections = $this->data;
	  
      return $this->collections;
    }
}