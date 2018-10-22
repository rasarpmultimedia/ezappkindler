<?php
use engine\core\Model;
class Home_Model extends Model{
    protected $id;
    public $title;
    public $data = [];
    
    public function indexModel(){
        $database = static::database();
        $params   = static::$params;
        $this->id = $params["id"];
        $this->title = $params["title"];
        $query = $database->initQuery($this->dbtable["Member"]);
        //var_dump($sql);
        $this->data =[
            "title"=>"Welcome to EzAppKindler Version 1.2.0",
            "message"=>"ezAppKindler is a Rapid Application Development (RAD) framework developed in "
            ."PHP implementing MVC architecture and has many build-in libraries to make web development easier and faster."
            ,"developer"=>"Developed by Sarpong Abdul-Rahman, Mobile: +233271957502, Email: fadanash@gmail.com",
            "db_list"=>[
                "Kofi","Ama","Selina","Saadatu","Awo","Khadijah","Mariam","Abdul-Salam"
                ]
        ];
        return $this->data;
    }
}