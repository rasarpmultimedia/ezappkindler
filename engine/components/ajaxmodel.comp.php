<?php
class AjaxModel extends AppModel{
	public function __construct(){parent::__construct();}
	public function getRequest(){return static::$make_request;}
	public function databaseConnect(){ return $this->data_record;}
	//data_record
}
?>