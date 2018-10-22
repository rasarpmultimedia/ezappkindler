<?php
/** 
This piece of software is written by Sarpong A-Rahman o Rasarp Mutimedia System please don't delete this comment. liences GPL and MIT 
 **/
class PinAuth extends Session{
    public  $authid;
	private $pinpinlogged_in = false;
	private $session;
    
    protected $setcookies = false;
    protected $cookie_expire;
	protected $user;
    protected $conf;
    protected $card_access;
    private static $database;
	
	function __construct(){
		//$this->sessname = session_name("PinAuth_Sess");
        parent::__construct();
        static::$database = parent::dataRecordSet();
        $conf = new Settings;
        $this->conf  = $conf->config()["Tables"];
        $this->user = $this->getRecord($this->conf["PinLogin"]);
        $this->cookie_expire = time()+(60*60*24*7);//1 week
		$this->checkPinAuth();	
	}
    private function checkPinAuth(){
  	     if(parent::getSession("pinid")!==null){
  	     	$this->authid = parent::getSession("pinid");
			$this->pinlogged_in = true;
  	     }else{
  	     	unset($this->authid);
			$this->pinlogged_in = false;
  	     }
    }
    
    protected function getRecord($table=null){
    return static::$database->initQuery($table);
    }
    
    public function isPinLoggedIn(){
		if(parent::getSession("serialnumber")!==null){
			return $this->pinlogged_in;
		}
    }
	
 public function pinLogIn($user){
   	   	 if(($user)){
			 $this->authid = isset($user->CardId)?parent::setSession("pinid",$user->CardId):"";
			 $serialnum = isset($user->SerialNumber)?parent::setSession("serialnumber",$user->SerialNumber):"";
             $this->pinlogged_in = true;
			 
         }
      return $this->pinlogged_in;
  }
	
 /** This method authenticates a login **/
 public function authenticate($serialnumber,$pin){
	 $sql = static::$database;
	 $querylogin = 'SELECT * FROM checkercard WHERE SerialNumber =:serialnumber or CardPIN =:pin  LIMIT 1';
	 $sql::$placeholder = [":serialnumber"=>$serialnumber,":pin"=>$pin];
     $query_res = $sql->getSingleRow($sql->query($querylogin));
	 //Check Card Status
	  if($query_res->CardStatus =="unused"){
		  $sql::$placeholder = [":cardstatus"=>"used",":pin"=>$pin];
		  $query ='UPDATE checkercard SET CardStatus = :cardstatus WHERE CardPIN = :pin LIMIT 1';
          $sql->writeQuery($query);			 
	  }
	 
	 //calculate login limit
	 if($query_res->CardAccessCount < 3){
		 $query = 'SELECT * FROM checkercard WHERE SerialNumber = :serialnumber AND CardPIN = :pin LIMIT 1';
	 	 $sql::$placeholder = [":serialnumber"=>$serialnumber,":pin"=>$pin];
     	 $results = $sql->getSingleRow($sql->readQuery($query));
		 if($results){
		  //Update Card Access Count
		  $accesscount = $query_res->CardAccessCount  + 1;
		  $sql::$placeholder = [":serialnumber"=>$serialnumber,":accesscount"=>$accesscount];
		  $query = 'UPDATE  checkercard SET CardAccessCount  = :accesscount WHERE SerialNumber = :serialnumber LIMIT 1';
          $sql->writeQuery($query);
		 } 
		 
	 }elseif((int)$query_res->CardAccessCount === 3){
		return "exceeded_login_limit";
	 }
	 
	 return $results;
 }
	
public function pinAuthInfo(){
     $userinfo = null;$user = $this->user;
        if($this->isPinLoggedIn()){
           $user::$placeholder = array(":id"=>$this->authid);
           //$userinfo = $user->joinRecords(["*"],$this->conf["Register"],"marriageregister.RegisterId = checkercard.RegisterId","checkercard.RegisterId=:id");
			
           $userinfo = $user->selectRecord("WHERE CardId=:id");
        }
     return $userinfo;         
 }
 
	
public function fullname(){
        $userinfo = $this->pinAuthUserInfo();
	 	if(isset($userinfo->FullName)){
	 		return ucfirst($userinfo->FullName);
	 	}else{ return "";}	
	 }

	
public function getSerialNumber(){
        $userinfo = $this->pinAuthUserInfo();
	 	if(isset($userinfo->SerialNumber)){
	 		return ucfirst($userinfo->SerialNumber);
	 	}else{ return "";}	
	 }

	
public function PinLogOut(){
  	   unset($this->authid);
	   parent::delSession("pinid");
	   $this->pinlogged_in = false;
	  return $this->pinlogged_in;
  }
	
}