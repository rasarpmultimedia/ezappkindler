<?php
class AjaxPaginate{
	private $current_page = 1;
	private $res_per_page;
	//private $page_range;
	//public  $mid_range = 6;
	private $start_range;
	//private $end_range;
	private $req;
	private $totalcount;
	private $html_link;
	
        
	public function __construct($currpage,$per_page,$totalcount){
		   $this->current_page = (int)$currpage;
		   $this->results_per_page = (int)$per_page;
		   $this->totalcount = (int)$totalcount;
		   //$this->page_range = $this->pageRange();
           $this->html_link = new Generateurl;
		   //$this->req  = Dispatcher::getRequest();
	}
	
	public function pageOffset(){
	 //show eg page 1 = (1-1)*10 = 0 offset and so on limit 0,10 offset=0,limit=10,
	 $offset = ($this->current_page - 1) * $this->results_per_page;
	 return $offset; 
	}
	private function totalPage(){
		return ceil($this->totalcount/$this->results_per_page);
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
	
	public function buildAjaxPagination(){
	 $output ="<ul>";
	  if($this->totalPage() > 0 || $this->totalPage()>=1){
		if($this->hasPreviousPage()){
		  $prev = $this->previousPage();
		  $output .= "<span class=\"active\"><a onclick=\"ezapp.ajax.paginate.loadAjaxPage({$prev})\" href=\"javascript:void(0);\"> &laquo; Prev</a></span>";
		}else{
		  $output .= "&nbsp;<span class=\"disabled\">&laquo; Prev</span>";	
		}
	  }
	 for($i=1; $i <= ($this->totalPage());$i++){
		 if($i == $this->current_page){
		   $output .= "&nbsp;<span class=\"disabled\">".$i."</span>"; 
		 }else{
		   $output .= "&nbsp;<span class=\"active\"><a onclick=\"ezapp.ajax.paginate.loadAjaxPage({$i})\" href=\"javascript:void(0);\">{$i}</a></span>"; 
		 }
	 }
		
	 if(!$this->totalPage()){
		if($this->hasNextPage()){
		  $next = $this->nextPage();
		  $output .= "<li>&nbsp;<span class=\"active\"><a onclick=\"ezapp.ajax.paginate.loadAjaxPage({$next})\" href=\"javascript:void(0);\">&laquo;Next</a></span></li>";
		}else{
		  $output .= "<li><span class=\"disabled\">&raquo; Next</span></li>";
		}
	  }	
	$output.="</ul>";
	return $output;
}
	


}