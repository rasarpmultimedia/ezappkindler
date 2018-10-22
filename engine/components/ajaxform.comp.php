<?php
namespace engine\components;
use engine\{lib\Form as Form, lib\BuildForm as BuildForm};

class AjaxForm extends Form{
	protected $form;
	public $jserror;
	
	public function __construct($name="",$action="",$method="get",$extra_attr=""){
		parent::__construct($name,$action,$method,$extra_attr);
		return $this->buildAjaxForm($name,$action,$method,$extra_attr);
	}
	//Creates
	public function buildAjaxForm($name="",$action="",$method="get",$extra_attr=""){
	    $buildForm = new BuildForm($name,$action,$method,$extra_attr);
		$this->form = $buildForm->form;
		//$this->jserror = $buildForm->jserror;
		return $buildForm;
	}
	
	public function formCollections(){
		$formcollections = array();
		$formInputs = $this->form->formInputs;
		//var_dump($formInputs);
		foreach($formInputs as $label=>$data){
			 $label = (!is_numeric($label)?$label:$label);
			 $input = array_key_exists(0, $data)?$data[0]:$data[0]; 
			 $error = (array_key_exists(1, $data))?$data[1]:null;
			 $formcollections[$label] = ["input"=>$input,"error"=>$error];
		}
		//var_dump($formcollections);
		return $formcollections;
	}
	protected function createAjaxForm(&$layout){ 
		return $this->form->startForm().$layout.$this->form->endForm();
	}
	public function DisplayAjaxForm($layout){
		return $this->createAjaxForm($layout);
	}

}