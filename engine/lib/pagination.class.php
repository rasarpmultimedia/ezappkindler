<?php
namespace engine\lib;
use engine\core\{Dispatcher as Dispatcher};
class Pagination{
	private $current_page;
	private $limit_perpage;
	private $page_range;
	public  $mid_range = 6;
	private $start_range;
	private $end_range;
	
	private $totalcount;
	private $urlpath;
	protected $request;
	private $link;
        
	public function __construct($currentpage,$pagelimit,$totalcount){		   
		   $this->limit_perpage = (int) $pagelimit;
		   $this->totalcount    =(int) $totalcount; 
		   $this->page_range = $this->pageRange();
                   $this->link     = new HTMLHelper;
		   $this->request  = new \engine\core\Request;
		   //set current page is 1 and defualt page limit is 10; 
		  $this->current_page  = (int) $currentpage?$currentpage:1;
		  $this->limit_perpage = ($this->limit_perpage?$this->limit_perpage:10);
		//var_dump($this->current_page);
	}

	public function pageLimit(){
		if($this->limit_perpage){
                    $offset = (($this->current_page - 1) * $this->limit_perpage);
	 	    $limit ="{$offset} , {$this->limit_perpage}";//set page offset limit
		}else {
                    $limit ="";
		}
		return $limit;
	}

	private function totalPages(){
		return ceil($this->totalcount/$this->limit_perpage);
	}

	private function previousPage(){
		return $this->current_page - 1;
	}

	private function nextPage(){
		return $this->current_page + 1;
	}

	private function hasPreviousPage(){
	 return  $this->previousPage() >= 1 ? true : false;	
	}

	private function hasNextPage(){
	 return  $this->nextPage() <= $this->totalPages() ? true : false;
	}

	private function pageRange(){
		$this->start_range = $this->current_page - floor($this->mid_range/2); 
		$this->end_range   = $this->current_page + floor($this->mid_range/2);
		if($this->start_range <= 0 ){
			$this->start_range = 1;
		    $this->end_range += abs($this->start_range) + 1;
		}elseif($this->end_range > $this->totalPages()){
			$this->start_range -= $this->end_range - $this->totalPages();
			$this->end_range    = $this->totalPages();
		}
		return range($this->start_range,$this->end_range);
	} 

// Buliding Pagination links
public function currentOfTotalPages(){
	return "<p class =\"curr_of_totalpages\">Page: {$this->current_page} of {$this->totalPages()}</p>";
}
    
public function getPageNum(){
  return isset($_GET["pgnum"])?intval($_GET["pgnum"]):1;
}

protected function addParams($num){
    $controller = $this->request->controller?$this->request->controller:"";
    $action     = $this->request->action?$this->request->action:"";
    //$params    = $this->request->params<>null?"/".$this->request->params:"";
    return "{$controller}/{$action}/{$num}";
}
//$this->link->hyperlink($name,$url="",$attribute="")
/*/Pretty Pagenation */ 
public function buildPagination(){
	$this->page_range = $this->pageRange();
	if($this->totalPages() >= 1 || $this->totalPages() >= 10){ 
		$list = "<div style=\"margin:0 auto\" ><ol class=\"pagination\">"; 
		if($this->hasPreviousPage()){ 
			$list .= "<li class=\"page-item\"><span class=\"page-link\">".$this->link->hyperlink("&laquo; Prev ","{$this->addParams($this->previousPage())}","")."</span></li>";	
		}else {
			$list.="<li class=\"page-item\"><span class=\"page-link\">&laquo; Prev </span></li>"; 
		}
		
		for($i=1; $i <= $this->totalPages(); $i++) {
			if($this->page_range[0] > 2 && $i == $this->page_range[0]) $list .= "<span>...</span>";
			if($i==1 || $i==$this->totalPages() || in_array($i, $this->page_range)){
				if($i != $this->current_page){ 
				  $list .= "<li class=\"page-item\">".$this->link->hyperlink("{$i}","{$this->addParams($i)}","class=\"page-link\"")."</li>"; 
				}else{ $list .="<li class=\"page-item active\"><span class=\"page-link\">{$i}</span></li>"; }   
			}
			if($this->page_range[$this->mid_range-1] <= $this->totalPages()-1 && $i == $this->page_range[$this->mid_range-1]){$list .= "<li class=\"page-item disabled\"><span>...</span></li>";}
		}

	   if($this->hasNextPage()){
		   $list .= "<li class=\"page-item\">".$this->link->hyperlink("Next &raquo;","{$this->addParams($this->nextPage())}","class=\"page-link\"")."</li>";  
		}else{
		   $list .= "<li class=\"page-item\"><span class=\"page-link\"> Next &raquo;</span></li>"; 
	   }
	   $list .= "</ol></div>"; return $list; 
	}
  }
  
  public function __toString() {
      return $this->page_range;
  }
}
