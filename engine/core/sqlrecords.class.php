<?php
namespace engine\core;
//include_once"dbconnect.class.php";
/** 
 * This piece of software is written by Sarpong A-Rahman o Rasarp Mutimedia Incorporated please don't delete this comment. liences GPL and MIT 
 **/
class SQLRecords{
  /** SQL Records is SQL Query Method
    *@param $id is unique identifier for data records in a sql database 
    *@param $tablename table name in sql database 
    *@param $db database connection attribute
    *@method
  **/
    protected $tablename;
    protected static $db;
    protected $results= [];
    public $placeholder=[];
    public $tablefields=[];
    public  $id;
    public $where_clause;
    public $getsql;
    public $lastInsertId;
    public static $sql;
    
    /* Connect to Database class */
    public function __construct($tablename='') {
        static::$db = DBConnect::getDBInstance();
        return (!empty($tablename)) ? $this->tablename($tablename) : "";
      }
     
    private  function tablename($tablename=""){
       $this->tablename = ($tablename)?$tablename:"";
       return $this->tablename;
    }  
	
    public function numRowsAffected(){
        return static::$db->sqlNumRows();
    }
    
    /*Creates RecordSet Object */   
	
    /*Pass ID form URL into the function to fatch id form database */
    
    public function getRowId($id){
            return $this->id = $id?$id:null;
    }
    /** Initialized Query Object for Execution */
    public function initQuery($tablename=''){
        return new self($tablename);
    }
    
    public static function PDOInstance(){
        return DBConnect::getDBInstance();
    }
    
    /** Set Prepared Statement */
    public function setPrepStmt($fieldname,$prep_name,$value){
        $this->placeholder[$prep_name] = $value;
        $this->tablefields[$fieldname] = $prep_name;
        return true;
    }
    
    /**This method be use to select the database using sql syntax 
      * in a table and return the values into object array
      * @method readQuery, $sql->readQuery("select name,email from users where id=:id");
      * @param  $query  this parameter is for prepared stataments or query statement
      * @param  public static $placeholder= array(":id"=>"1") for name placeholder=array("1") 
      * @return $this->results array : use foreach to go thru this array
      * */

    public function readQuery($query){
        $placeholder  = (!empty($this->placeholder))?$this->placeholder:array();
        $this->getsql = $query;
        $this->results = static::getQuery($query,$placeholder);
        return $this->results;  
    }
	
	 /**This method be use to update and insert queries into the database using sql syntax 
	  * in a table and return the values into object array
	  * @method writeQuery, $sql->writeQuery("update users set name = rahman where id=:id");
	  * @param  $query  this parameter is for prepared stataments or query statement
	  * @param  public static $placeholder= array(":id"=>"1") for name placeholder=array("1") 
	  * @return $this->results array : use foreach to go thru this array
	  * */
    public function writeQuery($query){
        $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
		$this->getsql = $query;      
		if(static::sendQuery($query,$placeholder)){			
			$result = $this->readQuery("SELECT LAST_INSERT_ID() AS LastInsertId");
			$id = $this->getSingleRow($result);
                        if($id){$this->lastInsertId  =  $id->LastInsertId;}				
		}
		return true;
    }
	
    /**This function return the values of a single row data object
	 * @method getSingleRow,
	 * @param $query_results
	 * */
	public function getSingleRow($query_results){
            return (!empty($query_results))?array_shift($query_results):false;
        }
    
        /**This function selects all rows in a table and return the values into object array
        * @method selectAllRecords,
        * @param  $addclause = null, e.g default value is null. usage "order by desc" or "where Id=?"
        * @param  public static $placeholder= array(":name"=>"ama") for name placeholder or array("ama") 
        * @return $this->results array : use foreach to go thru this array
         * */
        public  function selectAllRecords($addclause=''){
               $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
               $sql = "SELECT * FROM ".$this->tablename." $addclause ";
               $this->getsql = $sql;
               $this->results =  static::getQuery($sql,$placeholder);
               return $this->results;
         }
    
	/**This function selects one row in a table and return the values into an object array
	 * @method selectRecord,
	 * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder= array(":id"=>"1") for name placeholder or array("1")
	 * */
        public  function selectRecord($whichfield=null){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
            $query = "SELECT * FROM ".$this->tablename."";
            $query .= ($whichfield!=null?$this->where($whichfield):"");
            $this->results = static::getQuery($query,$placeholder);
            $this->getsql = $query;
            return $this->getSingleRow($this->results);
	}
        
        /** Get Last Insetered ID **/
        public function lastInsertId(){
           return $this->lastInsertId;
        } 
    
	/** This method joins two tables into one statement
	 * @method joinRecords,
	 * @param  $join_table eg the second table to join 
	 * @param  $join_on eg page.category_id = category.id
	 * @param  array $setfields usage e.g. array('Fieldname1','Fieldname1')
         * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder = array(":id"=>"1") or array("1") 
         * @param  $order ='' e.g default value is empty. usage "ORDER BY Id ASC|DESC"
         * @param  $limit ='' e.g default value is empty. usage "5 or 10 OFFSET 50"
         * @return $this->results object array: use foreach to go thru this array
         * */
	 public function joinRecords(array $setfields,$join_table,$join_on,$whichfield = null,$join_type='LEFT',$orderby='',$limit=''){
             $placeholder = (!empty($this->placeholder))?$this->placeholder:[];
             $setfields = is_array($setfields)?$setfields:"";
             $query = "SELECT ";
             $query .= (count($setfields)>=1)?implode(",",$setfields):"*";
             $query .=" FROM ".$this->tablename;
	     $query .=" $join_type JOIN ".$join_table." ON ".$join_on;
             $where = ($whichfield!=null?$this->where($whichfield):"");
	     $limit = (!empty($limit)?"LIMIT $limit":"");
	     $query .=" $where $orderby  $limit";
	     $this->getsql = $query;
	     $this->results = static::getQuery($query,$placeholder);
             return $this->results;
             
         }
	 
	/** Finds specified in object array
         * @method selectExactRecord,
         * @param  array$setfields usage e.g. array('Fieldname1','Fieldname1')
         * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder = array(":id"=>"1") for name placeholder or array("1") 
         * @param  $order ='' e.g default value is empty. usage "ORDER BY Id ASC|DESC"
         * @param  $limit ='' e.g default value is empty. usage "5 or 10 OFFSET 50"
         * @return $this->results array : use foreach to go thru this array
         * */
	public  function selectExactRecord(array $setfields,$whichfield = null,$orderby='',$limit=null){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
	    $query = "SELECT "; 
            $query .= (count($setfields)>=1)?implode(",",$setfields):"*";
            $query .=" FROM ".$this->tablename;
            $query .=" ".($whichfield!=null?$this->where($whichfield):"");
            $limit = !empty($limit)?"LIMIT $limit":"";
            $query .=" $orderby $limit";
            $this->getsql = $query;
            $this->results = static::getQuery($query,$placeholder);
            return $this->results;        
        }
	
	 /**Finds and checks specified field and instantiate row into object array
	 * @method fieldExists,
	 * @param  $tblfield , e.g default value is null. usage "Fisrtname=:firstname or Fisrtname=?"
	 * @param  public static $placeholder = array(":id"=>"1") for name placeholder or array("1") 
	 * @return $foundfield stdClass array
	 * */
	public  function fieldExists($tablefield){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:[];
            $split_var = preg_split("/=/",$tablefield);
	    $fieldname  = $split_var[0]; $fieldvalue = $split_var[1];
            $sql ="SELECT $fieldname FROM ".$this->tablename;
            $sql .= $this->where("$fieldname = $fieldvalue")." LIMIT 1";
            $this->getsql = $sql;
            $this->results = static::getQuery($sql,$placeholder);           
            return $this->getSingleRow($this->results);
        } 
	
	 /**This function count rows in a table
	 * @method selectCount,
	 * @param $field eg count(id)
	 * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder= array(":name"=>"ama") for name placeholder or array("ama") 
	 * */
	public function selectCount($field=null,$whichfield=null){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
	    $field = ($field<>null)?$field:"*";
            $query = "SELECT COUNT($field) as Totalcount FROM ".$this->tablename;
            $query .=" ".($whichfield!=null?$this->where($whichfield):"");
            $this->getsql = $query;
            $this->results = static::getQuery($query,$placeholder);
            return $this->getSingleRow($this->results);
	}
	
	/**sql statement phaser method usage:insert data into tables;
	 * @method insertData,
	 * @param public static $tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) or 
	 * @param public static $tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) ;
	 * @param public static $placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"])  
	 * */
	private function insertData(){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
            $query = "INSERT INTO ".$this->tablename;
            $query .= "(";
            $query .= implode(", ",array_keys($this->tablefields));
            $query .= ") VALUES (";
            $fieldvalues = [];
            foreach (array_values($this->tablefields) as $value) {
                $fieldvalues[] = $value;
            }
            $query .= implode(",",$fieldvalues);
            $query .= ")";
            if (static::sendQuery($query, $placeholder)) {
                $result = $this->readQuery("SELECT LAST_INSERT_ID() AS LastInsertId");
                $id = $this->getSingleRow($result);
                if($id){$this->lastInsertId = $id->LastInsertId;}                
            }
            $this->getsql = $query; //var_dump($query);
            return true;
        }
	
	/**sql statement phaser method, update data in tables;
	 * @method updateData, 
	 * @param public static $tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) or 
	 * @param public static $tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) ;
	 * @param public static $placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"])  
	 * */
        private function updateData(){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
            $query = "UPDATE ".$this->tablename; 
            $query .= " SET ";
	    $setfields = [];
            foreach ($this->tablefields as $fieldname => $fieldvalue) {
                $setfields[] = "{$fieldname} = "."{$fieldvalue}"."";                
                }
            $query .= implode(", ",$setfields);
            $query .= " ".$this->where($this->where_clause)." LIMIT 1";

            if (static::sendQuery($query, $placeholder)) {
                $result = $this->readQuery("SELECT LAST_INSERT_ID() AS LastInsertId");
                $id = $this->getSingleRow($result);
                if($id){$this->lastInsertId = $id->LastInsertId;}                
            }
		$this->getsql = $query;//var_dump($query);
                return true;
	}
    
    /**SQL statement phaser method usage: replaces data in tables;
     * @method replaceData,
     * @param public static $tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) or 
     * @param public static $tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) ;
     * @param $placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"])  
     * */
        public function replaceData(){
            $query = "REPLACE INTO ".$this->tablename;
            $query .= "(";
            $query .= implode(", ",array_keys($this->tablefields));
            $query .= ") VALUES ( '";
            foreach (array_values($this->tablefields) as $value) {
                $fieldvalues[] = $value;                
            }
            $query .= implode("','",$fieldvalues);
            $query .= "' )";
	    $this->getsql = $query;
            return static::sendQuery($query,$this->placeholder);            
        }
        
        /**SQL statement phaser methodthis deletes row in a table;
         * @method delete, 
	 * */
        public function delete(){
            $placeholder = (!empty($this->placeholder))?$this->placeholder:array();
            $query = "DELETE FROM ".$this->tablename." ".$this->where($this->where_clause);
            $query .= " LIMIT 1"; 
            $this->getsql = $query;
            return static::sendQuery($query,$placeholder);            
        }
        
        /* Custom Sql Select Statments Ends here */	
	/* MySQL where clause */
	protected function where($clause){
            return " WHERE {$clause} ";            
        }
        
	/* sends data to database */
	protected static function sendQuery($sql,$fields=array()){
            $fields = (isset($fields) && !empty($fields)?$fields:array());
            if(static::$db->exeQuery($sql,$fields)){
               return true;               
            }else{
                return static::$db->errorInfo($sql);                
            }
            return static::$db->closeConnection();
	}
        
    /*/get datasets from database*/
    protected static function getQuery($sql,$fields=array()){
        $fields = (isset($fields)&&!empty($fields))?$fields:array();
        if(static::$db->exeQuery($sql,$fields)){
            return (static::$db->sqlNumRows()!=0)? static::instantiate():null;            
        }else{
            return static::$db->errorInfo($sql);           
        }
        return static::$db->closeConnection();
        
    }
	 /** SQL statement phaser method this initialize rows into objects arrays
          * @method instantiate,
	 * */
 	 private static function instantiate(){
             $records = array();
             while($row = static::$db->fetchObject(__CLASS__)){
                 $records[] = $row;                                          
             }
           return $records;          
         }

    public function fetchData($constant = \PDO::FETCH_ASSOC){
        $object_arr = [];
        $key = 0;
        do{
            $records[] = $row;
           // $records = array_column($object_arr, $key);
            $key++;
        }while ($row = static::$db->fetch($constant));
        return $records;
    } 

    /** it saves data into database,
	*@method save,*/
     public function save($id=null){
      $this->id = ( $id!==null ? $id : $this->id);
      return ($this->id != null ?$this->updateData():$this->insertData());
    }
    
   // public function sendData(){}

    //Convert Object to string
    public function __toString() {
        return $this->getsql;
    }
}

   /* 
   How to use SQLRecords Class
   $rec = new SQLRecords;  
   //$sql::$placeholder=array(":id=>1");
   echo"<br><br>";
   $rec->getTable("users");
   $query = $rec::selectAllRecords(); 
     //$rec::readQuery("select name,email from users where id=:id");
    foreach($query as $row){
    	echo "".$row->name." ----- ".$row->email."<br>";
    }
    echo $rec::$db->sqlNumRows()."<br><br>";
	$rec::$db->closeConnection();
      //var_dump($query);  
      //$rec::$placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"],$_POST["value2"])
     //$rec::$tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) or ;
   
     //$rec::$tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) ;
	 
 */
?>