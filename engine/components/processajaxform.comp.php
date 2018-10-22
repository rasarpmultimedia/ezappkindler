<?php
class ProcessAjaxForm extends AppModel{
	public $request;
	public function __construct(){
		parent::__construct();
		$this->dataset = $this->data_record;
		$this->request = static::$make_request;
	    /* Variables Initiation */
		$this->session = $this->sesscookie;
		$this->process = $this->process;
		$this->auth    = $this->auth;
		$this->util    = $this->util;
        $this->validate = $this->process->validate();
        $this->form_data = !empty($this->form_data)?$this->form_data:null;
		$this->parse_data = !empty($this->parse_data)?$this->parse_data:null;
	}
}