<!DOCTYPE HTML>
<html lang="en-gb">
<head>
<title>Test page</title>
</head>
<body>
<?php
include_once"includes/initialize.inc.php";
//Create a test form here
$message = $model->form_data["message"];
//Edit
$firstname = isset($model->form_data["firstname"])?$model->form_data["firstname"]:"";
	
$form = new Form("testform",filter_var($_SERVER["PHP_SELF"].QUERY_STRING."client/register"),"post","enctype='www-form/application'");
//Firstname
$form->setFormField($form->inputLable("firstname", "Firstname"),
				    $form->inputField("text", "firstname",$process->post("firstname",$firstname)),
				    $errormsg='');
//Lastname
$form->setFormField($form->inputLable("lastname", "Lastname"),
					$form->inputField("text", "lastname",$process->post("lastname")),
					$errormsg='');
//Gender
$gender_options = array('Select Gender','Male','Female');
$form->setFormField($form->inputLable("gender", "Gender"),
					$form->selectOptions("gender",$gender_options,$process->post("gender")),
					$errormsg='');
//Username
$form->setFormField($form->inputLable("username", "Username"),
     				$form->inputField("text", "username",$process->post("username")),
     				$errormsg='');
//Email Address
$form->setFormField($form->inputLable("email", "Email"),
					$form->inputField("email", "email_address",$process->post("email_address")),
					$errormsg='');
//Password
$form->setFormField($form->inputLable("password", "Password"),
					$form->inputField("password", "password",$process->post("password")),
					$errormsg='');
//Confirm password					
$form->setFormField($form->inputLable("confirm_password", "Confirm Password"),
					$form->inputField("password","confirm_password",$process->post("confirm_password")),
					$errormsg='');
//Submit Form
$form->setFormField(null,$form->inputField("submit", "send","Send"));

//Create Forms
$output ="<div class=\"container\">";
$output .="<div class=\"col-md-6 rest-grid1\">";
$output .= "<div class=\"form-grid\">{$message}";
$output .= "<h3>Add User</h3>";
$collections = $form->customFormCollections();	
foreach($collections as $label=>$data){
		 if(is_numeric($label)){
			 $error = $data["error"]<>null?"<div class=\"error\">{$data["error"]}</div>":"";
			 $output .="{$data["input"]}{$error}";
		  }else{
			 $error = $data["error"]<>null?"<div class=\"error\">{$data["error"]}</div>":"";
			 $output .="{$label}{$data["input"]}{$error}";
		 }
}
$output .="</div></div>
</div>";
?>
</body></html>
