<?php
namespace engine\components;
use engine\{
    core\Session  as  Session,
    core\Settings as Settings
};
/** 
*This piece of software is written by Sarpong A-Rahman o Rasarp Mutimedia Incoporated please don't delete this comment. liences GPL and MIT 
 **/
class Auth extends Session{
    private $logged_in = false;    
    protected $login_user;
    private static $database;
    public $setcookies = false;
    public $cookie_expire;
    public $auth_tbl_id;    
    public $userid;
    
    function __construct(){ 
        parent::__construct();
        static::$database = parent::database();
        $conf = new Settings;
        $this->dbtable  = $conf->config()["Tables"];
        $this->login_user = static::$database->initQuery($this->dbtable["Login"]);
        $this->auth_tbl_id = "LoginId";
        $this->cookie_expire = time()+(60*60*24*1);//1 day
	$this->checkAuth();
    }
    
    private function checkAuth(){
        if(parent::getSession("userid")!==null){
            $this->userid = parent::getSession("userid");
            $this->logged_in = true;
         }else{
             unset($this->userid);
             unset($this->accesslevel);
             $this->logged_in = false;
         }
    }
    
    public function isLoggedIn(){
        return $this->logged_in;
    }
	
    protected function accesslevel($accesslevel){
            $access = static::$database->initQuery($this->dbtable["Role"]);
            $access->placeholder = [":access"=>$accesslevel];
            $getrole = $access->selectRecord("Accesslevel=:access");
            return $getrole->Accesslevel;
    }
    
    public function hasAccess($access){
        $user = $this->login_user;
  	     if($this->isLoggedIn()){ 
  	        $user->placeholder = array(":id"=>$this->userid);
                $userinfo = $user->selectRecord($this->auth_tbl_id."=:id");
            return (strcasecmp($this->accesslevel($access),$userinfo->Accesslevel)==0)?true:false;
         }
    }
    
    public function getUserRole(){
            $access = static::$database->initQuery($this->dbtable["Role"]);
            $access->placeholder = [":access"=>$this->accesslevel];
            $getrole = $access->selectRecord("Accesslevel=:access");
         return $getrole->Role;
    }
    
    public function isAdmin(){ return $this->hasAccess("A"); }
    
    public function isModerator(){ return $this->hasAccess("M");}
    
    public function isEditor(){ return $this->hasAccess("E");}    

    public function isUser(){ return $this->hasAccess("U");}

    public function LogIn($user){
       if($user){
           $this->userid = parent::setSession("userid",$user->LoginId);//Login Id
           $this->accesslevel = parent::setSession("accesslevel",$user->Accesslevel);
           $this->logged_in  = true;
           $this->setcookies = true;
           }
        return $this->logged_in;
    }
     /** This method authenticates a login **/
   public function authenticate($userinput,$password){
           $sql = static::$database;
           //$query= 'SELECT * FROM member WHERE Email =:email AND Password =:password LIMIT 1';
           //$sql->placeholder = [":email"=>$userinput,":password"=>$password];
           $query = "SELECT * FROM login WHERE Username =:username AND Password =:password OR Email =:email AND Password =:password LIMIT 1";
           $sql->placeholder = [":username"=>$userinput,":email"=>$userinput,":password"=>$password];
           
           $results = $sql->getSingleRow($sql->readQuery($query));
       //var_dump($results);
       return $results;
   }

  public function authCredentials(){
          $userinfo = null;$user = $this->login_user;
          if($this->isLoggedIn()){
             $user->placeholder = [":id"=>$this->userid];
             $userinfo = $user->joinRecords(["*"],$this->dbtable["Member"],"login.LoginId = member.MemberId","login.LoginId=:id","LEFT","ORDER BY Accesslevel ");
             //$userinfo = $user->selectAllRecords("WHERE ".$this->auth_tbl_id."=:id");
          }
      return $user->getSingleRow($userinfo);         
   }

    public function logOut(){
        unset($this->userid);
        parent::delSession("userid");
        parent::delSession("accesslevel");
        $this->logged_in = false;
        return $this->logged_in;
      }

    /**
     * @method setAuthCookie ($name,$value)
     * @param $name  cookie name
     * @param $value cookie value
    **/
      
    public function setAuthCookie($name,$value,$expire=0){
        $this->cookie_expire = isset($expire)?$expire:$this->cookie_expire;
        $this->setCookie($name,$value,$this->cookie_expire);
        return true;
    }
    
     /**
     * @method getAuthCookie ($value)
     * @param $value cookie value
    **/
    public function getAuthCookie($name){ 
        $this->getCookie($name);
        return true;
     }

}
