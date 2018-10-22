<?php
namespace engine\lib;
//include_once"form.class.php";
class BuildForm extends Form{
	 /**
	   * Use this class to create Input Objects / Fields / Forms
	   * @param $field , data type is array type
	   * @example $field = array("label"=>"Firstname","type"=>"text","name"=>"firstname","value"=>"","required"=>true,"optionalattr"=>"class=\"formclass\" placeholer=\"Enter name here\"")
	   * @param array elements attributes: 
	   * "label"=>"" for field label,
	   * "type"=>"" for input field type attribute  default to "type"="text" if ignored,
	   * "value"=>"" for input field value attribute default to "value"="" if ignored,
	   * "optionalattr"=>"" use it to add more attribute to any field, it is optional
	   * "cols"=>"","rows"=>""  use this attributes for textarea fields or multiline input fields
	   * "options"=>array("optionvalue"=>"value","optionvalue"=>"value") use this attributes for select options in select dropdown fields
	   * @example $field=array("label"=>"Gender","radiogroup"=>array(array("name"=>"male","value"=>"","required"=>true,"checked"=>true),											        array("name"=>"male","value"=>"","required"=>true,"checked"=>true),..)) use this attributes for radio and checkbox fields
	   * 
	   * @method createInputField(array$field,$makevalid=null)  visibility public
	   * @method createTextField(array$field,$makevalid=null)   visibility public
	   * @method createUploadField(array$field,$makevalid=null) visibility public
	   * @method createSelectField(array$field,$makevalid=null) visibility public
	   * @method createUploadField(array$field,$makevalid=null) visibility public
	   * @method createRadioButton(array$field,$makevalid=null) visibility public
	   * @method createCheckBox(array$field,$makevalid=null)	visibility public
           * @method displayForm($layout,$message="")    visibility public display to form to screen
	   * 
	   **/

	public $form;
	public $jserror = false;
		
	public function __construct($name="",$action="",$method="get",$extra_attr=""){
		parent::__construct($name,$action,$method,$extra_attr);
		$this->form = new Form($name,$action,$method,$extra_attr) ;
		$this->process = $this->process;
	}
        
        public function createInputField(array $field,$makevalid=null){
            $inputcaption ='';
            $labelattr = (array_key_exists("labelattr", $field))?$field["labelattr"]:"";
            $label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["name"], ucfirst($field["label"]),$labelattr):null;
            $type  =(array_key_exists("type", $field))?$field["type"]:$field["type"] = "text";//default type to text
            $name  =(array_key_exists("name", $field))?$field["name"]:$field["name"] = "";
            $value =(array_key_exists("value", $field))?$field["value"]:$field["value"] = "";
            $optional =(array_key_exists("optionalattr", $field))?$field["optionalattr"]:null;
	    $jserror  =(array_key_exists("jserror", $field))?$field["jserror"]:false;
	    $caption  = (array_key_exists("caption", $field))?$field["caption"]:null;
	    $inputcaption = ($caption<>null)?"{$caption}":"";
         //Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
                     $labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
                     $labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr),
			$this->form->wrapField($this->form->inputField($type,$name,$this->process->post($name,$value),$optional,$jserror),$inputtag,$inputattr).$inputcaption,$makevalid);
		  }else{
			$this->form->setFormField($label,$this->form->inputField($type,$name,$this->process->post($name,$value),$optional,$jserror).$inputcaption,$makevalid);  
		  }		   
      }	
	   //Form Input Group
	  public function createInputGroup(array $field ,$makevalid=null){
		  $addinputs ='';  $inputs ='';
	  	  if(array_key_exists("inputgroup",$field)){
		   $inputs = count($field["inputgroup"]);
		   for($key=0; $key < $inputs; $key++){
			//prep vals
		  	$labelattr =(array_key_exists("labelattr", $field))?$field["labelattr"]:null;
		  	$label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["label"], ucfirst($field["label"]),$labelattr):null;
		  	$name  = (array_key_exists("name",$field["inputgroup"][$key]))?$field["inputgroup"][$key]["name"]:$field["inputgroup"][$key]["name"] = "";
		  	$value = (array_key_exists("value",$field["inputgroup"][$key]))?$field["inputgroup"][$key]["value"]:$field["inputgroup"][$key]["value"] = "";
			$type  = (array_key_exists("type", $field["inputgroup"][$key]))?$field["inputgroup"][$key]["type"]:$field["inputgroup"][$key]["type"] = "text";//default type to text
                        $optional = (array_key_exists("optionalattr",$field["inputgroup"][$key]))?$field["inputgroup"][$key]["optionalattr"]:$$field["inputgroup"][$key]["optionalattr"] = "style=\"display:inline\"";
			$caption  = (array_key_exists("caption", $field["inputgroup"][$key]))?$field["inputgroup"][$key]["caption"]:null;
			$inputcaption = ($caption<>null)?"{$caption}":"";
			$jserror =(array_key_exists("jserror", $field))?$field["jserror"]:false;
		  	$addinputs .= $this->form->inputField($type,$name,$this->process->post($name,$value),$optional,$jserror).$inputcaption;/**/
		}
			
		 //Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
			$labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
			$labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr), $this->form->wrapField($addinputs,$inputtag,$inputattr),$makevalid);
		  }else{ $this->form->setFormField($label,$addinputs,$makevalid); }
		 
		}	  
	}
	 //Creates a text area field
    public function createTextField(array $field,$makevalid=null){
	  	//prep vals
		 $inputcaption ='';
		 $labelattr =(array_key_exists("labelattr", $field))?$field["labelattr"]:"";
         $label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["name"], ucfirst($field["label"]),$labelattr):null;
         $name  =(array_key_exists("name", $field))?$field["name"]:null;
         $value =(array_key_exists("value", $field))?$field["value"]:$field["value"] = "";
         $optional = (array_key_exists("optionalattr", $field))?$field["optionalattr"]:null;
         $rows = (array_key_exists("rows", $field))?$field["rows"]:null;
         $cols = (array_key_exists("cols", $field))?$field["cols"]:null;
		 $jserror =(array_key_exists("jserror", $field))?$field["jserror"]:false;
		 $caption = (array_key_exists("caption", $field))?$field["caption"]:null;
		 $inputcaption = ($caption<>null)?"{$caption}":"";
		 
		//Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
			$labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
			$labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr),
				  $this->form->wrapField($this->form->textAreaField($name,$this->process->post($name,$value),$rows,$cols,$optional,$jserror).$inputcaption,$inputtag,$inputattr),$makevalid);
		  }else{
			$this->form->setFormField($label,$this->form->textAreaField($name,$this->process->post($name,$value),$rows,$cols,$optional,$jserror).$inputcaption,$makevalid);  
		  }
		
	  }

	  public function createUploadField(array $field,$makevalid=null){
	  	//prep vals
		 $inputcaption ='';
		 $labelattr =(array_key_exists("labelattr", $field))?$field["labelattr"]:"";
         $label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["name"], ucfirst($field["label"]),$labelattr):null;
         $id =(array_key_exists("id", $field))?$field["id"]:$field["id"] = "";
         $name =(array_key_exists("name", $field))?$field["name"]:$field["name"] = "";
         $value = (array_key_exists("value", $field))?$field["value"]:$field["value"] = "";
         $optional = (array_key_exists("optionalattr", $field))?$field["optionalattr"]:null;
		 $jserror =(array_key_exists("jserror", $field))?$field["jserror"]:false;
		 $caption = (array_key_exists("caption", $field))?$field["caption"]:null;
		 $inputcaption = ($caption<>null)?"{$caption}":"";
         
		 //Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
			$labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
			$labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr), $this->form->wrapField($this->form->uploadField($id,$name,$optional,$jserror).$inputcaption,$inputtag,$inputattr),$makevalid);
		  }else{
			$this->form->setFormField($label,$this->form->uploadField($id,$name,$optional,$jserror).$inputcaption,$makevalid); 
		  }

	  }
	  
    public function createSelectField(array $field,$makevalid=null){
	   //prep vals
		 $inputcaption ='';
		 $labelattr =(array_key_exists("labelattr", $field))?$field["labelattr"]:"";
         $label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["name"], ucfirst($field["label"]),$labelattr):null;
		 $name =(array_key_exists("name", $field))?$field["name"]:$field["name"] = "";
		 $value = (array_key_exists("value", $field))?$field["value"]:null;
		 $options = (array_key_exists("options", $field))?$field["options"]:array();
		 $optional = (array_key_exists("optionalattr", $field))?$field["optionalattr"]:null;
		 $jserror =(array_key_exists("jserror", $field))?$field["jserror"]:false;
		 $caption = (array_key_exists("caption", $field))?$field["caption"]:null;
		 $inputcaption = ($caption<>null)?"{$caption}":"";
		 //Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
			$labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
			$labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr), $this->form->wrapField($this->form->selectOptions($name,$options,$this->process->post($name,$value),$optional,$jserror).$inputcaption,$inputtag,$inputattr),$makevalid);
		  }else{
			$this->form->setFormField($label,$this->form->selectOptions($name,$options,$this->process->post($name,$value),$optional,$jserror).$inputcaption,$makevalid);
		  }
	   }

	public function createDataListField(array $field, $makevalid = null){
	   //prep vals
		$inputcaption = '';
		$labelattr = (array_key_exists("labelattr", $field)) ? $field["labelattr"] : "";
		$label = (array_key_exists("label", $field) && $field["label"] != null) ? $this->form->inputLabel($field["name"], ucfirst($field["label"]), $labelattr) : null;
		$name = (array_key_exists("name", $field)) ? $field["name"] : $field["name"] = "";
		$value = (array_key_exists("value", $field)) ? $field["value"] : null;
		$options = (array_key_exists("options", $field)) ? $field["options"] : array();
		$optional = (array_key_exists("optionalattr", $field)) ? $field["optionalattr"] : null;
		$jserror = (array_key_exists("jserror", $field)) ? $field["jserror"] : false;
		$caption = (array_key_exists("caption", $field)) ? $field["caption"] : null;
		$inputcaption = ($caption <> null) ? "{$caption}" : "";
		 //Set form Input Field and Field Wrap
		if (array_key_exists("wrap", $field)) {
			$labeltag  = (array_key_exists("fieldlabel", $field["wrap"])) ? $field["wrap"]["fieldlabel"] : null;
			$labelattr = (array_key_exists("labelattr", $field["wrap"])) ? $field["wrap"]["labelattr"] : null;
			$inputtag  = (array_key_exists("fieldinput", $field["wrap"])) ? $field["wrap"]["fieldinput"] : null;
			$inputattr = (array_key_exists("inputattr", $field["wrap"])) ? $field["wrap"]["inputattr"] : null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label, $labeltag, $labelattr), $this->form->setFormField($label, $this->form->dataList($name, $options)));
		} else {
			$this->form->setFormField($label, $this->form->dataList($name, $options));
		}
	}
  
    /*
		 * use this attributes for radio and checkbox fields
		 * $field=array("label"=>"Reciepies","checkboxes"=>array(array("checklabel"=>"male","name"=>"gender","value"=>"","checked"=>$this->checkedBoxValue("name","value")),
	     * array("checklabel"=>"female",,"name"=>"gender","value"=>"","checked"=>$this->checkedBoxValue("name","value")),..)) 
		 */

    public function createRadioButton(array $field,$makevalid=null){
		$addradiogroup =""; $radiobuttons = array();
	  	if(array_key_exists("radiogroup",$field)){
		   $radiobuttons = count($field["radiogroup"]);
			for($key=0; $key<$radiobuttons; $key++){
				//prep vals
				$labelattr =(array_key_exists("labelattr", $field))?$field["labelattr"]:"";
				$label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["label"], ucfirst($field["label"]),$labelattr):null;
				$name  = (array_key_exists("name", $field["radiogroup"][$key]))?$field["radiogroup"][$key]["name"]:$field["radiogroup"][$key]["name"] = "";
				$value = (array_key_exists("value",$field["radiogroup"][$key]))?$field["radiogroup"][$key]["value"]:$field["radiogroup"][$key]["value"] = "";
				$radiolabel = (array_key_exists("radiolabel", $field["radiogroup"][$key]))?$field["radiogroup"][$key]["radiolabel"]:$field["radiogroup"][$key]["radiolabel"] = "";
				$checked = (array_key_exists("checked",$field["radiogroup"][$key]))?$field["radiogroup"][$key]["checked"]:$field["radiogroup"][$key]["checked"] = false;
				$optional = (array_key_exists("optionalattr",$field))?$field["optionalattr"]:$field["optionalattr"] = "style=\"display:inline\"";
				$jserror =(array_key_exists("jserror", $field))?$field["jserror"]:false;			
		  		$addradiogroup .= $this->form->radioButton(ucfirst($radiolabel),$name,$value,$checked,$optional,$jserror);
		}
			
		 //Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
			$labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
			$labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr), $this->form->wrapField($addradiogroup,$inputtag,$inputattr),$makevalid);
		  }else{
			$this->form->setFormField($label,$addradiogroup,$makevalid);
		  }
	  }
     }
	 	
    //check box field
	  public function createCheckBox(array $field,$makevalid=null){
	    $addcheckboxes = array(); 
		$addcheckbox ='';
	  	if(array_key_exists("checkboxes",$field)){
		   $checkboxes = count($field["checkboxes"]);
		   for($key=0; $key<$checkboxes; $key++){
			//prep vals
		  	$labelattr =(array_key_exists("labelattr", $field))?$field["labelattr"]:"";
		  	$label =(array_key_exists("label",$field) && $field["label"] != null)?$this->form->inputLabel($field["label"], ucfirst($field["label"]),$labelattr):null;
		  	$name  = (array_key_exists("name",$field["checkboxes"][$key]))?$field["checkboxes"][$key]["name"]:$field["checkboxes"][$key]["name"] = "";
		  	$value = (array_key_exists("value",$field["checkboxes"][$key]))?$field["checkboxes"][$key]["value"]:$field["checkboxes"][$key]["value"] = "";
		  	$checked = (array_key_exists("checked",$field["checkboxes"][$key]))?$field["checkboxes"][$key]["checked"]:$field["checkboxes"][$key]["checked"] = false;
          	$optional = (array_key_exists("optionalattr",$field))?$field["optionalattr"]:$field["optionalattr"] = "style=\"display:block\"";
		  	$checklabel = (array_key_exists("checklabel",$field["checkboxes"][$key]))?$field["checkboxes"][$key]["checklabel"]:null;
		  	$jserror =(array_key_exists("jserror", $field))?$field["jserror"]:false;
		  	$addcheckbox .= $this->form->checkBox(ucfirst($checklabel),$name,$value,$checked,$optional,$jserror);/**/
		}
			
		 //Set form Input Field and Field Wrap
		 if(array_key_exists("wrap", $field)){
			$labeltag  = (array_key_exists("fieldlabel",$field["wrap"]))?$field["wrap"]["fieldlabel"]:null;
			$labelattr = (array_key_exists("labelattr",$field["wrap"]))?$field["wrap"]["labelattr"]:null;
		    $inputtag  = (array_key_exists("fieldinput",$field["wrap"]))?$field["wrap"]["fieldinput"]:null;
		    $inputattr = (array_key_exists("inputattr",$field["wrap"]))?$field["wrap"]["inputattr"]:null;
			//Set Form Fields
			$this->form->setFormField($this->form->wrapField($label,$labeltag,$labelattr), $this->form->wrapField($addcheckbox,$inputtag,$inputattr),$makevalid);
		  }else{
			$this->form->setFormField($label,$addcheckbox,$makevalid);
		  }	 
                  
                  }                
        }
	
        public function formCollections(){
            return $this->form->formCollections();            
        }
        
        public function displayForm(&$layout){
            return $this->form->displayForm($layout);
        }
}
?>