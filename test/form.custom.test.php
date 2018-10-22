<!DOCTYPE HTML>
<html lang="en-gb">
<head>
<title>Test page</title>
</head>
<body>
<?php
include_once"includes/initialize.inc.php";
$form = new Form("register",filter_var($_SERVER["PHP_SELF"].QUERY_STRING."member/{$formaction}"),"post","enctype=\"multipart/form-data\"");

//Firstname
$form->setFormField("<h5>{$form->inputLabel("firstname", "Firstname")}</h5>","<div class=\"best-hot1\">".$form->inputField("text", "firstname",$firstname,"placeholder=\"Firstname\" required")."</div>",$validate->displayErrorField($process->errorinfo,"firstname"));

//Lastname
$form->setFormField("<h5>{$form->inputLabel("lastname", "Lastname")}</h5>","<div class=\"best-hot1\">".$form->inputField("text", "lastname",$lastname,"placeholder=\"Lastname\" required")."</div>",$validate->displayErrorField($process->errorinfo,"lastname"));

//Gender
$gender_options = array("select-gender"=>"Select Gender","M"=>"Male","F"=>"Female");
$form->setFormField($form->inputLabel("gender", "Gender"),$form->selectOptions("gender",$gender_options,$gender,"class=\"sel1\" "),$validate->displayErrorField($process->errorinfo,"gender"));

//Mobile Number
$form->setFormField("<h5>{$form->inputLabel("mobilenumber", "Mobile Number")}</h5>","<div class=\"best-hot1\">".$form->inputField("mobilenumber", "mobilenumber",$mobilenumber,"placeholder=\"Mobile Number\" required")."</div>",$validate->displayErrorField($process->errorinfo,"mobilenumber"));

//Email
$form->setFormField("<h5>{$form->inputLabel("email", "Email")}</h5>","<div class=\"best-hot1\">".$form->inputField("email", "email",$email,"placeholder=\"user@email.com\" required")."</div>",$validate->displayErrorField($process->errorinfo,"email"));

if($action=="add"){
	
//Password
$form->setFormField("<h5>{$form->inputLabel("password", "Password")}</h5>","<div class=\"best-hot1\">".$form->inputField("password","password",$password,"placeholder=\"Password\"")."</div>",$validate->displayErrorField($process->errorinfo,"password"));

//Confirm Password
$form->setFormField("<h5>{$form->inputLabel("cpassword", "Confirm Password")}</h5>","<div class=\"best-hot1\">".$form->inputField("password","cpassword","","placeholder=\"Confirm Password\"")."</div>",$validate->displayErrorField($process->errorinfo,"cpassword"));
	
}

//Accesslevel
$access_options = array("select-accesslevel"=>"Select Accesslevel","A"=>"Administrator","M"=>"Moderator","E"=>"Editor","U"=>"User");
$form->setFormField($form->inputLabel("accesslevel", "Accesslevel"),$form->selectOptions("accesslevel",$access_options,$accesslevel,"class=\"sel1\" "),$validate->displayErrorField($process->errorinfo,"accesslevel"));

//Upload avatar
$form->setFormField("<h5>{$form->inputLabel("avatar", "Upload Avatar")}</h5>","<div class=\"best-hot1\">".$form->uploadField("avatar","avatar","")."</div>",$validate->displayErrorField($process->errorinfo,"avatar"));

if($action=="add"){
	$form->setFormField(null,"<div class=\"best-hot\">".$form->inputField("submit", "register","Register")."</div>");}else{
	$form->setFormField(null,"<div class=\"best-hot\">".$form->inputField("submit", "edituser","Update User Details")."</div>");
	
}

//Create Forms
$form_heading = ($action=="edit")?"Edit User Details":"Register User";
$output ="<div class=\"container\">";
$output .="<div class=\"col-md-6 rest-grid1\">{$message}";
$output .= "<div class=\"form-grid\"><h3>{$form_heading}</h3>";

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

$title = $form_heading;
$content = $form->DisplayCustomForm($output);
echo $content;
?>
</body></html>
