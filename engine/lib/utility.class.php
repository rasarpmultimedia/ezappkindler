<?php
namespace engine\lib;
/*
 * This script contains optional functions that are needed in some parts 
 * of an application.
 * Written By Abdul-Rahman Sarpong (c)
*/
 
class Utility{
 
 public function debug($debug_var){
 	echo "<pre id='debug'>";
	       var_dump($debug_var);
	echo "</pre>";
 }
 
public function extractChars($sentence, $start_at=0,$strlen=100){	     
		   return substr($sentence, $start_at,$strlen) . '... '; 
}

public function extractParagraph($string, $start_at=0){	     
		  // find position of last space in sentence 
		 $newline = strpos($string, PHP_EOL); 
		 return substr($string, $start_at, $newline); 
}

public function convertToParas($string){
	return "<p>".preg_replace("/[\r\n]+/", "</p><p>", $string)."</p>";
}

function firstSentences($text, $number=2) { 
// use regex to split into sentences
$text = $this->convertToParas($text); 
$sentences = preg_split('/([.?!]["\']?\s)/', $text, $number+1,PREG_SPLIT_DELIM_CAPTURE); 
if (count($sentences) > $number * 2) { 
$remainder = array_pop($sentences); 
} else { 
$remainder = ''; 
} 
$result = []; 
$result[0] = implode('', $sentences); 
$result[1] = $remainder; 
return $result; 
} 

public function checkObj($obj){
	   $obj = is_object($obj)?$obj:null;
	   return $obj;
}

 public function redirectTo($url = null){
 	  if($url <>null){
 	 	header("Location: {$url}");
	  }
 }
 /*This function sends an email*/
 public function mailTo($to,$subject,$message,$add_header='',$add_param =''){
	            mail($to,$subject,$message,$add_header,$add_param);
 }
 
 public  function validID($id){
     return $id = preg_replace("/[^0-9]/","",$id);
 }
 /* This function helps to get user role */
public function getuser_role($role){ $accesslevel="";
		 switch($role){
			 case "A":$accesslevel = "Administrator"; break;
			 case "M":$accesslevel = "Moderator"; break;
			 case "E":$accesslevel = "Editor"; break;
			 case "U":$accesslevel = "User"; break;
	 }
	return $accesslevel;
}
/* This check if a particular year is a leap year */
	public function is_leap_year($year){
	  $year = date('Y');
	  //$year = (int) $year;
	  return((($year % 4) == 0) &&((($year % 100)!=0)||(($year % 400)==0)));
	}

	public function get_days_in_month($month,$year){
	 $month = (int) $month;
	 $year  = (int) $year;
	 return ($month == 2 ? ($year % 4 ? 28 :($year % 100 ? 29:($year % 400?28:29))):(($month - 1)%7%2?30:31));
	 }
//echo get_days_in_month('2','2000');
 

	public function remove_db_slashes($dbstring){
	  if(get_magic_quotes_gpc()){
	 	$dbstring = stripcslashes($dbstring);
	  }
	 return $dbstring;
    }

 
	public function get_file_extension($string){
		$string = strtolower($string);
	 	 $dotpos = strrpos($string,".");
	  	 if(!$dotpos) return "";
	    	$strlen = strlen($string) - $dotpos;
			$extension = substr($string,$dotpos+1,$strlen);
		 return $extension;
	}

 
	public function random_chars($length,$randmin=0){
	 $characters ="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	 $randchar ="";
	 for($i=0;$i<$length;$i++){
		 $randchar .= $characters[mt_rand($randmin,strlen($characters)-1)];
		 }
		return $randchar;
	}
//echo random_chars(10,1);
	public function random_nums($length,$min=0){
	   $numbers = "0123456789"; $randnum ="";
	   for($i=0;$i<$length;$i++){
		 $randnum .=$numbers[mt_rand($min,strlen($numbers)-1)];
	   }
	return $randnum;
	}
	

    public function passwEncode($password){
        $options = ['cost' =>12];
        return password_hash($password,PASSWORD_BCRYPT,$options);
    }

    public function passwDecode($password,$encodedpass){
        return password_verify($password,"$encodedpass")?true:false;
    }

    public function decodeHtmlEntity($string){
         return html_entity_decode($string, ENT_QUOTES | ENT_HTML5,'UTF-8');
    }  
    public function htmlEntities($string){
         return htmlentities($string);
    }
/**
 * Date Format public function
//echo date("jS F Y",strtotime(formatDate("2011-06-30","mysqldate")));
//echo "<br /> \n".formatDate("2011-06-30","mysqldate")
 * */
public function formatDate($origdate='',$format = "%d/%m/%Y"){
	switch($format){
	case "formatdate": $format;break;
	case "date"			: $format = "%d-%m-%Y"; break;
	case "datetime"		: $format = "%d-%m-%Y %H:%M:%S";break;
	case "mysqldate"	: $format = "%Y-%m-%d";break;
	case "mysqldatetime": $format = "%Y-%m-%d %H:%M:%S";break;  
	case "datetime_ampm": $format = "%d-%m-%Y at %I:%M:%S %p";break; 
	case "datetime_word": $format = "%a, %B %d, %Y at %I:%M:%S %p"; break;
	case "date_word": $format = "%a, %B %d, %Y"; break;
	default: $format;
	}
	if(strlen($origdate)>0){
		return strftime($format,strtotime($origdate));
	}else{ return " "; }
}

/**
	This BB Code parses formated HTML tags back to its
	original state
*/
public function bbcode_parser($string){
	$string = trim($string);
	$html_tags ='p|b|i|h1|h2|h3|h4|h5|h6|size|color|center|quote|cite|url|img';
	while(preg_match_all('`\[('.$html_tags.')=?(.*?)\](.+?)\[/\1\]`',$string,$matches)) foreach($matches[0] as $key => $match){
		 list($tags,$parameter ,$innertext) = array($matches[1][$key],$matches[2][$key],$matches[3][$key]);
		 switch($tags){
		 case "p" : $replacement = "<p> $innertext </p>";  break;
		 case "b" : $replacement = "<strong> $innertext </strong>"; break;
		 case "i" : $replacement = "<i> $innertext </i>"; break;
		 case "h1": $replacement = "<h1> $innertext </h1>"; break;
		 case "h2": $replacement = "<h2> $innertext </h2>"; break;
		 case "h3": $replacement = "<h3> $innertext </h3>"; break;
		 case "h4": $replacement = "<h4> $innertext </h4>"; break;
		 case "h5": $replacement = "<h5> $innertext </h5>"; break;
		 case "h6": $replacement = "<h6> $innertext </h6>"; break;
		 case "hr": $replacement = "<hr />";
		 case "size"  : $replacement = "<span style=\"font-size:$parameter;\">$innertext</span>"; break;
		 case "color" : $replacement = "<span style=\"color : $parameter;\">$innertext</span>"; break;
		 case "center": $replacement = "<span class=\"centered;\">$innertext</span>"; break;
		 case "quote" : $replacement = "<blockquote>$innertext</blockquote>";/**/ break;
		 case "cite":	$replacement = "<cite>$innertext</cite>"; break;
		 case "url" :	$replacement = "<a href=\"".($parameter ? $parameter : $innertext)."\">$innertext</a>"; break;
		 case "img" : list($width,$height) = preg_split("`[Xx]`",$parameter);
		 $replacement = "<img src =\"".$innertext."\" ".(is_numeric($width)? "width =\"$width\"":"")." ".(is_numeric($height)?"height=\"$height\"":"").">"; break;
			 }
		   $string = str_replace($match, $replacement, $string);
		 }
		 return $string;
	}
//echo bbcode_parser("[p][b]this is testing bb code paser[/b][/p]");   
    
}
?>