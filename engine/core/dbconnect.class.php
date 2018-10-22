<?php
namespace engine\core;
//include_once"../lib/settings.class.php";
class DBConnect {
	 protected  static $instance = 0;
	 protected $connect;
	 protected $dns; 
	 protected $driver;
	 protected $user; 
	 protected $password;
	 protected $host;
	 protected $dbname;
         protected static $stmt;
         protected static $connection;
         public static $getfields;
	 
	private function __construct() {	 	
	 	$conf = new Settings;
	 	$config  = $conf->config();
                $this->driver = $config["Database"]["Driver"];
	 	$this->host   = $config["Database"]["Host"];
	 	$this->user   = $config["Database"]["User"];
		$this->password = $config["Database"]["Password"];
		$this->dbname   = $config["Database"]["Name"];
	 	$this->dns =$this->driver.":host=".$this->host.";dbname=".$this->dbname;
         
            try{
                $this->connect = new \PDO($this->dns,$this->user,$this->password,[\PDO::ATTR_PERSISTENT=>true]);
		$this->connect->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
                    static::$connection = $this->connect;
                if(!$this->connect){
                    static::$instance = 0;
                }else{
                    static::$instance = 1;	
                }            
            }catch(\PDOException $e){
		    //Error Message here
            $error_message = "<p class=\"dberror\"> Unable to connect to Database:"
                . "--ErrorCode: ".$e->getCode()."<br>"
                . "Error Message: ".$e->getMessage()
                ."in <br>File: ".$e->getFile()."\r\n"
                ." <br>on  Line Number: ".$e->getLine()."</p>";
            echo $error_message;
            new Error("Unable to connect to Database:","database_error.log",$e);
            static::$instance = 0;
        }        
            //Database Connection
	   // echo "<h1>Successfully connected to database :)</h1>";
	}

    /**Creates an instantace database Connection */
    public static function getDBInstance(){
        return new self;
    }   
   //Executes prepared statements $fields=array(':username'=>$username,':password'=>$password);
   public  function exeQuery($sql,array $fields){
       try{
         static::$getfields = $fields;
         //var_dump($sql);
         //var_dump($fields);
         static::$stmt = $this->connect->prepare($sql,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
         static::$stmt->execute($fields); 
         
       }catch(\PDOException $e){
       echo "<p class=\"dberror\">Opp an Error Occured, Could not Query Database --ErrorCode: ".$e->getCode()."<br>
             Error Message: ".$e->getMessage()."\r\n"." in <br>File: ".$e->getFile()."\r\n".
             " <br>on  Line Number: ".$e->getLine()."</p>";
             new Error("Opp an Error Occured, Could not Query Database --ErrorCode:", "database_error.log", $e);          
       }
       return true;  
   }
   
    /** Close Database Connections */
   public function closeConnection(){
   	 return static::$stmt->closeCursor();
   }
  
   //Counts number of rows in a sql statement
   public  function sqlNumRows(){
   	   return static::$stmt->rowCount();
   }

   //Fetch Data from database
   public  function fetch($constant=\PDO::FETCH_ASSOC){
   	   return static::$stmt->fetch($constant);
   }

   public  function fetchObject($classname){
   	   return static::$stmt->fetchObject($classname);
   }
   
   //Start Database Transaction methods
   public  function startTranstaction(){
   	return static::$connection->beginTransaction();
   }
   public  function commit(){
   	return static::$connection->commit();
   }
   public  function rollBack(){
   	return static::$connection->rollBack();
   }
   public  function query($sql){
   	return static::$connection->query($sql);
   }
   /* This Function is for General Perpose query to databases */
   public static function getFieldsError(){
       return static::$getfields;
   }

}
//$db = new $connect;
//var_dump(DBConnect::getFieldsError());
?>