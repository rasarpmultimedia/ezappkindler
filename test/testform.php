<?php
include_once"../includes/init.php";
/**
 * How to use the from class: implement it like this */
try{

$createform = new BuildForm("textform",filter_var($_SERVER["PHP_SELF"]),"post");
echo $createform->formHtml("Sign Up Form ");
	//$form->formHtml("<p>Spouse Information </p>");
// $firstname= array("label"=>"*Firstname","type"=>"text","name"=>"firstname","value"=>"","required"=>true,"optionalattr"=>"class=\"formclass\" placeholder=\"Enter name here\"");
// $lastname = array("label"=>"*Lastname","type"=>"text","name"=>"lastname","value"=>"","required"=>true,"optionalattr"=>"class=\"formclass\" placeholder=\"Enter name here\"");
$email = array("label"=>"*Email Address","type"=>"email","name"=>"email","value"=>"","optionalattr"=>"class=\"formclass\" placeholder=\"Enter your email here\"");
 //$password= array("label"=>"*Password","type"=>"password","name"=>"pass","value"=>"","required"=>true,"optionalattr"=>"class=\"formclass\"");
$submit   = array("type"=>"submit","name"=>"submit","value"=>"Register Me","optionalattr"=>"class=\"formclass\"");
$hidden   = array("type"=>"hidden","name"=>"session","value"=>"Rasarp","optionalattr"=>"class=\"formclass\"");
// $createform->formHtml("<h3>More Info</h3>");
//$createform->createInputField($email);
$createform->createInputField(
	   /* Do this in PHP 5.4 or above 
	    * ["label"=>"*Lastname","type"=>"text","name"=>"lastname",
	    * "value"=>"","required"=>true,
	    * "optionalattr"=>"class=\"formclass\" placeholder=\"Enter name here\""]
	    */
	   array("label"=>"*Enter Firstname","type"=>"text",
	   		 "name"=>"firstname","value"=>"","required"=>true,
	         "optionalattr"=>"class=\"formclass\" placeholder=\"Enter name here\"")
	   );
$createform->createInputField(
			array("label"=>"*Enter Lastname",
			"type"=>"text","name"=>"lastname","value"=>"",
			"required"=>true,"optionalattr"=>"class=\"formclass\" placeholder=\"Enter name here\""));
	
//Wrapped Form Field
$createform->createInputField(
	array("label"=>"*Enter Username",
		  /*"wrap"=>array("fieldlabel"=>"p","labelattr"=>"class=\"label\"",
	"fieldinput"=>"div","inputattr"=>"class=\"input-style\""),*/
	"type"=>"text","name"=>"username","value"=>"",
	"optionalattr"=>"class=\"formclass\" placeholder=\"Enter username here\" required"
		 )
);

$createform->createInputField(
	array("label"=>"*Enter Login",
		  /*"wrap"=>array("fieldlabel"=>"","labelattr"=>"",
						"fieldinput"=>"div","inputattr"=>"class=\"input-style\""),*/
		  "type"=>"text","name"=>"login","value"=>"",
		  "optionalattr"=>"class=\"formclass\" placeholder=\"Login here\"")
);	

//$createform->createInputField($password);
$createform->createTextField(array("label"=>"*Type Your Message",
	   		 "name"=>"comments","cols"=>40,"rows"=>10,"value"=>"",
	         "optionalattr"=>"class=\"formclass\" placeholder=\"Type your message here\""));
	
$gender=array("label"=>"Select your Gender",
			 //"optionalattr"=>"style=\"display:inline; padding:0.6em\" class=\"radio-primary\"",
              "radiogroup"=>array(
			  array("radiolabel"=>"male","checked"=>$createform->checkedRadioValue("gender","male"),"name"=>"gender","value"=>"male"),       	 
			  array("radiolabel"=>"female","checked"=>$createform->checkedRadioValue("gender","female"),"name"=>"gender","value"=>"female"))
             );
			
$sex=array("label"=>"Choose your sex","name"=>"sex",
		  	"options"=>array("....","male"=>"Male","female"=>"Female"));

$recipes=array("label"=>"Choose your favourite foods",
			   //"optionalattr"=>"style=\"display:inline; padding:0.6em\" class=\"radio-primary\"",
		 	   "checkboxes"=>array(
		 	   array("checklabel"=>"Banku and Tilapia","name"=>"recipes[]","value"=>"Banku and Tilapia","checked"=>$createform->checkedBoxValue("recipes[]",'Banku and Tilapia')),
	     	   array("checklabel"=>"Rice and Stew","name"=>"recipes[]","value"=>"Rice and Stew","checked"=>$createform->checkedBoxValue("recipes[]",'Rice and Stew')),
	     	   array("checklabel"=>"Fried Rice and Chicken","name"=>"recipes[]","value"=>"Fried Rice and Chicken","checked"=>$createform->checkedBoxValue("recipes[]","Fried Rice and Chicken")),
			   array("checklabel"=>"Gari Foto and Chicken","name"=>"recipes[]","value"=>"Gari Foto and Chicken","checked"=>$createform->checkedBoxValue("recipes[]","Gari Foto and Chicken"))
               ));
	
$food=array("label"=>"Choose your favourite food",//"style"=>"display:block",
		 	   "checkboxes"=>array(
		 	   array("checklabel"=>"Fufu and Goat Soup","name"=>"food[]","value"=>"Fufu and Goat Soup","checked"=>$createform->checkedBoxValue("food[]",'Fufu and Goat Soup'))
			   ));

//Input group 
$createform->createInputGroup(array("label"=>" Full Name",
				"inputgroup"=>array(
					["type"=>"text","name"=>"firstname","value"=>"","optionalattr"=>"style=\"display:inline; margin-right:.3em; padding:0.6em\" class=\"formclass\" placeholder=\"First Name\""],
					["type"=>"text","name"=>"middlename","value"=>"","optionalattr"=>"style=\"display:inline; margin-right:.3em; padding:0.6em\" class=\"formclass\" placeholder=\"Middle Name \""],
					["type"=>"text","name"=>"lastname","value"=>"", "optionalattr"=>"style=\"display:inline; margin-right:.3em; padding:0.6em\" class=\"formclass\" placeholder=\"Last Name\""]
			 	)));
$createform->createCheckBox($food);
$createform->createCheckBox($recipes);
$createform->createSelectField($sex);
//$createform->formHtml("<h3>More Info</h3>");
$createform->createRadioButton($gender);
$createform->createInputField($hidden);
//$createform->createInputField($hidden);
$createform->createInputField($submit);
	
	
//shows entire form on screen
//echo $createform->displayForm("Top_Labling");
	
//Show how to implement BuildForm class with using customForm methods 
$output="<div class=\"login\"><div class=\"main\">
 		   <div class=\"form\">";
$output.= "<h3>REGISTER HERE</h3><p>Register for Litecoin and Bitcoin Trading Account</p>";

$collections = $createform->customFormCollections();	
//var_dump($collections);
$output .= $createform->startForm();
foreach($collections as $label=>$data){
		 if(is_numeric($label)){
			 $error = $data["error"]<>null?"<div class=\"error\">{$data["error"]}</div>":"";
			 $output .="{$data["input"]}{$error}";
		  }else{
			 $error = $data["error"]<>null?"<div class=\"error\">{$data["error"]}</div>":"";
			 $output .="<p><span>{$label}</span><br>{$data["input"]}{$error}</p>";
		 }
}
$output.="</div></div></div>{$createform->endForm()}";
$content = $createform->DisplayCustomForm($output);	
echo $content;
}catch(Exception $e){
 //Debug Problem here	$e->getMessage()
}


?>