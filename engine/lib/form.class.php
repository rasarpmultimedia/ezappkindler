<?php
namespace engine\lib;
/** Class Create Html Form from a class */
class Form{
	private $htmlForm;
	private $formName;
	private $action;
	private $method;
	protected $inputName;
	private   $extraAttr;
	
	protected $formInputs = []; 
	protected $groupFormInputs = [];
	//private   $addhtml =[];
	public $selOptions =[];
	public $dataListOptions = [];

	public $process;
	public $validate;
	
	public function __construct($name="",$action="",$method="get",$extra_attr=""){
		$this->formName = $name;
		$this->action   = $action;
		$this->method   = $method;
		$this->extraAttr= $extra_attr;
		/** Initiate */
		$this->process = new ProcessForm;
		$this->validate = $this->process->validate();
	}
	public function startForm(){
	$this->htmlForm = "<form action=\"{$this->action}\" name=\"{$this->formName}\" id=\"{$this->formName}\" method=\"{$this->method}\" {$this->extraAttr}> \n";
	return $this->htmlForm."\r\n";
	}

	public function inputLabel($for,$name,$extra_attr=""){
	return"<label for=\"$for\" {$extra_attr}>$name</label>"."\r\n";
	}

	public function addHTMLToForm($element){
		return $this->setFormField(null,$element);
	}

	public  function inputField($type,$name,$value='',$optional_attr='',$jserror=false){
            $jserror =($jserror==false)?"":"<div id=\"{$name}_error\"></div>";
	return"<input type=\"{$type}\" name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" $optional_attr />{$jserror}"."\r\n";
	}

	public  function uploadField($id,$name,$optional_attr='',$jserror=false){
            $jserror =($jserror==false)?"":"<div id=\"{$name}_error\"></div>";
	return"<input type=\"file\" name=\"{$name}\" id=\"{$id}\" $optional_attr>{$jserror}"."\r\n";
	}

	public function textAreaField($name,$value='',$rows='',$cols='',$optional_attr='',$jserror=false){
            $jserror =($jserror==false)?"":"<div id=\"{$name}_error\"></div>";
            $extra_arr = (strlen($rows)>0||strlen($cols)>0)?"rows =\"$rows\" cols=\"$cols\"":"";
	return "<textarea name=\"$name\" id=\"$name\" $extra_arr $optional_attr>$value</textarea>{$jserror}";
	}
/*Select Options*/
	public function selectOptions($name,array $options,$postback_field,$optional_attr='',$jserror=false){ 
            $jserror =($jserror==false)?"":"<div id=\"{$name}_error\"></div>";
            $output = "<select name=\"{$name}\" id=\"{$name}\" $optional_attr >"."\r\n";
            foreach($options as $key=>$option){
                $output .= ($key == $postback_field)?"<option value=\"{$key}\" selected =\"selected\">{$option}</option>":"<option value=\"{$key}\">{$option}</option>"."\r\n";                     
            }      
            $output .="</select>{$jserror}"; return $output;
	}

	public function dataList($name,array $options){
	$output = "<datalist id=\"{$name}\">  \n";
	foreach($options as $option){
	$output .= "<option label=\"{$option}\" value=\"{$option}\" />";
	}
	$output .="</datalist>\n"; return $output;
	}

	public function radioButton($label,$name,$value='',$checked=false,$optional_attr='style=\"display:inline\"',
								$inline_style="",$jserror=false){ 
	$jserror =($jserror==false)?"":"<div id=\"{$name}_error\"></div>";
	$checked = ($checked==true)?"checked=\"checked\"":"";
	return "<span {$optional_attr}><label><input type=\"radio\" name=\"{$name}\" value=\"{$value}\" $checked /> ".ucfirst("{$label}")."</label></span>{$jserror}";
	}

	public function checkBox($label,$name,$value='',$checked=false,
							$optional_attr ="display:block", $jserror=false){
	$jserror =($jserror==false)?"":"<div id=\"{$name}_error\"></div>";
	$checked = ($checked==true)?"checked=\"checked\"":"";
	return "<span {$optional_attr}><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" $checked /> ".ucfirst("{$label}")."</label></span>{$jserror}";
	}

	public function checkedBoxValue($checked_name,$value){
	if ($_POST && $value != null) {
		$name = $checked_name;
		preg_match("/^[a-z_-]+/", $name, $name_matches);
		if (isset($_POST[$name_matches[0]]) && $postbackval == null) {
			return in_array($value, $_POST[$name_matches[0]]) ? true : false;
		} else {
			return $this->process->post($check_name, $postbackval) == "$value" ? true : false;
		}
	}
	}

	public function checkedRadioValue($check_name,$value,$postbackval=''){
		if (isset($_POST[$check_name]) && $postbackval == null) {
			return $this->process->post($check_name) == "$value" ? true : false;
		} else {
			return $this->process->post($check_name, $postbackval) == "$value" ? true : false;
		}
    }
	
	public function setFormField($label=null,$field='',$error=''){
	 	if($label<>null){
	 	$this->formInputs[$label] = (strlen($error)>0)?[$field,$error]:[$field];
		}elseif($label==null){
		$this->formInputs[] = (strlen($error)>0)?[$field,$error]:[$field];
		}
		return $this->formInputs;
	 }
	
	 public function groupFormFields($grouplable,$label,$field,$error){
        return array_merge($this->groupFormInputs,array($grouplable=>$this->setFormField($label,$field,$error))); 
	 }

	 protected function closeForm(){
	 	return $this->htmlForm = "</form>"."\r\n";
	 }

	 //End of form elements

	 public function formCollections(){
		$collections = [];
		$formInputs = $this->formInputs;
		//var_dump($formInputs);
		foreach($formInputs as $label=>$data){
			 $label = (!is_numeric($label)?$label:$label);
			 $input = array_key_exists(0, $data)?$data[0]:$data[0]; 
			 $error = (array_key_exists(1, $data))?$data[1]:null;
			 $collections[$label] = ["input"=>$input,"error"=>$error];
		}
		return $collections;
	}
	
	protected function wrapField($formelem,$element="div",$attr=""){
		if($element<>null){
		 $tag = "<{$element} {$attr}>{$formelem}</{$element}>";	
		}else{$tag="";}
		return $tag;
	}
	
	public function displayForm(&$layout){
		return $this->startForm() . $layout . $this->closeForm();
	}
	
}
?>

