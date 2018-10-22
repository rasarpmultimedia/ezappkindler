<?php
namespace engine\lib;
class HTMLHelper{
    public static $data = array();
	public static function addElement($tag,$content=null,$extra_attr=''){
	   $tag = ($content!=null?"<{$tag} {$extra_attr}>{$content}</{$tag}>":"<{$tag}/>");
           return $tag;
    }
    
    public static function ul(array $items,$extra_attr=''){
		$puts = "<ul {$extra_attr}>";
		foreach ($items as $item) {
		$puts .= "<li>{$item}</li>";	
		}
		$puts .= "</ul>";
		return $puts;
	}
    public static function ol(array $items,$extra_attr=''){
		$puts = "<ol {$extra_attr}>";
		foreach ($items as $item) {
		$puts .= "<li>{$item}</li>";	
		}
		$puts .= "</ol>";
		return $puts;
	}

   /** @var $table = [
		 "table-row"=>[
			 "table-head"=>array("Head1","Head2","Head3","Head4","Head5"),
			 "table-data"=>array(
				 ["col1-row1","col1-row2","col1-row3","col1-row4","col1-row5"],
				 ["col2-row1","col2-row2","col2-row3","col2-row4","col2-row5"],
				 ["col3-row1","col3-row2","col3-row3","col3-row4","col3-row5"],
				 ["col4-row1","col4-row2","col4-row3","col4-row4","col4-row5"],
				 ["col5-row1","col5-row2","col5-row3","col5-row4","col5-row5"],
				)
			]
		]; 
  echo $html->table($table);
*/
    public static function table(array $table,$attr=''){
		$output ='<table '.$attr.'>'."\n";
		for ($i=0, $size=count($table["table-row"]); $i < $size; $i++) {
			if(count($table["table-row"]["table-head"]) === count($table["table-row"]["table-data"][0])){
			$output.= '<thead><tr class="table-head-row">'."\n";
			foreach($table["table-row"]["table-head"] as $th){ $output.='<th class="table-head">'.$th.'</th>'."\n"; }
			$output.='</tr></thead>'."\n";
			//tbody
			$output.= '<tbody>';
			$tabledata = $table["table-row"]["table-data"];	//var_dump($tabledata);	
			for ($i=0, $size=count($tabledata[0]); $i < $size; $i++) {
				$output.= '<tr class="table-data-row">'."\n";
				 foreach($tabledata as $td){
					$output.='<td class="table-data">'.$td[$i].'</td>'."\n";}
				 $output.='</tr>';
			}	 								
			$output.='</tbody>';	
			}	
		 }
		$output .='</table>';
		return $output;
	}

	public static function htmlTag($element,$content='',$attr=''){
		if($attr!==null || $content==null){$element="<{$element} {$attr}>{$content}</{$element}>";}else{$element = "<{$element}>{$content}</{$element}>";}
		if($content!==null){$element = "<{$element} {$attr}/>";}
		return $element;
	}
	//div elelment
    public static function div($content,$attr=''){
		if($attr!==null){$element="<div {$attr}>{$content}</div>";}else{$element = "<div>{$content}</div>";}
		return $element;
	}
    
	public static function hyperlink($name,$url="",$attribute=""){
		return "<a href=\"".BASE_URL.QUERYSTRING."$url\" {$attribute}>$name</a>";
	}
	
  public function htmlImage($src, $attribute =""){
   $image = "<img src=\"$src\" $attribute >";
   return $image;
   }
    public static function addCSS($href,$rel="stylesheet",$media="screen"){
   	return "<link rel=\"{$rel}\" media=\"{$media}\" href=\"{$href}\" />";
   }
	
  public static function addScripts(array$srcs){
  	 //array_push($srcs);
	 $puts ="";foreach($srcs as $src){
	 $puts .=  "<script src=\"{$src}\"></script>\n";
	}
   	return $puts;
   }
	
  public function addScript($scripts,$src=''){
	  if(is_array($scripts)){
		   $puts =  "<script>\n";
			  foreach($scripts as $script){$puts .= $script."\n";}
		   $puts .= "</script>\n";
	  }else{  
		 $src  = !empty($src)?"src=\"{$src}\" ":"";
		 $puts =  "<script {$src}>{$scripts}\n</script>\n";  
	  }
	 
   	return $puts;
   }
}
/*
$html = new HtmlHelper;
$table = [
		 "table-row"=>[
			 "table-head"=>["Col1-Head1","C2-Head2","Col-Head3","Col4-Head4","Col5-Head5"],
			 "table-data"=>[
				 ["col1-row1","col1-row2","col1-row3","col1-row4","col1-row5"],
				 ["col2-row1","col2-row2","col2-row3","col2-row4","col2-row5"],
				 ["col3-row1","col3-row2","col3-row3","col3-row4","col3-row5"],
				 ["col4-row1","col4-row2","col4-row3","col4-row4","col4-row5"],
				 ["col5-row1","col5-row2","col5-row3","col5-row4","col5-row5"]
				 ]
			
			 ]
		];

 //echo $html->table($table,"class=\"table\"");
 //echo $html->table($table);*/
?>