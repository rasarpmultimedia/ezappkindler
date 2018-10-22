<?php
class CheckerCard extends AppModel{
	public $cardserial;
	public $cardpin;
	protected $record;
	protected $carddata;
	
	function __construct(){
		parent::__construct();
		$this->record = $this->data_record;	
		$this->checker_table = $this->dbtable["Card"];
		$this->carddata = $this->record->initQuery($this->checker_table);
		$this->util = $this->util;
	}
	
	public function generateNewCard($numofcards=10){
		$datacard = $this->carddata;
		$add=0;
		while($add <= $numofcards){
			$pin = $this->util->random_nums(4);
			$cardserial = date("dm",time()).$this->util->random_nums(4);
			$date = $this->util->formatDate(date("d-m-Y",time()),"mysqldate");
			
			$datacard::$placeholder = array(
				$pin,$cardserial,$date
			);
			
			$datacard::$tablefields = array(
				"CardPIN"=>"?", "SerialNumber"=>"?", "DateGenerated"=>"?"
			);
			$datacard->save();
			$add++;
		}	
	}
			
	public function removeNewCard($card_id){}
	
	public function printCards($numofcards=10){}
	
	
}