<?php
class CURL{
	public $setopts_arr = [];
	public $options;
	public $channel;
	public $setoptions = [];
	
	public function __construct($url=''){
		$this->channel = curl_init($url);
		if($this->channel ===false){
		  exit("CURL Error Occured:".curl_error($this->channel)." Error Number:".curl_errno($this->channel));
		 //return $this->channel;
		}
		$this->setoptions =[
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_URL=>"",
			CURLOPT_HEADER=>true,
			CURLOPT_HTTPHEADER=>[],
			CURLOPT_POST =>true,
			CURLOPT_UPLOAD => false,
			CURLOPT_SAFE_UPLOAD=>false,
			CURLOPT_HTTPGET=>false,
			CURLOPT_POSTFIELDS=>false,
			CURLOPT_FILE =>false,
		
		];
	}
	
   public function setOpts($options='',$value=''){
		if(isset($options) && is_array($options)){
			$this->setopts_arr = array_merge($this->setopts_arr,$options);
			$this->options = curl_setopt_array($this->channel,$this->setopts_arr);
		}else{
			$this->options = curl_setopt($this->channel,$options,$value);	
		}
		return $this->options;
	}
	
   public function curlExec(){
	   return curl_exec($this->channel);
   }
   public function curlClose(){
	   return curl_close($this->channel);
   }
	
}

/**Text CURL */
$url = new CURL();

$url->setOpts([
	$url->setoptions[CURLOPT_URL]="http://localhost/goldcoin.com.gh/index.php",
	$url->setoptions[CURLOPT_HEADER]=>true]);
if($url->curlExec()){
}
$url->curlClose();
?>