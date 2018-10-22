<?php
//namespace extensions\shopping;
use engine\core\APIModel as CartModel;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Cart extends CartModel{
    private $database;
    protected $session;
    public function __construct(){
        parent::__construct();
        $this->session  = parent::session;
        $this->database = parent::database;       
        $this->session->setSession($name,$value);
        $this->session->getSession($name);
    }
    
}
