<?php
//include_once"dbconnect.class.php";
/** 
This piece of software is written by Sarpong A-Rahman please don't delete this comment. 
liences GPL and MIT 

**/
class SQLRecords{
  /** SQL Records is SQL Query Method
    @param $id is unique identifer for data records in a sql database 
    @param $tablename table name in sql database 
    @param $dbcon database connection attribute
    @methods
  **/
	 protected static $tablename;
	 protected static $dbcon;
	 
	 public static $placeholder=array();
	 public static $tablefields=array();
     public static $id;
     public static $saveid;
    
	 /* Connect to Database class */
     public function __construct($tablename='') {
        $database = new DBConnect;
        static::$dbcon = $database;
     
   	  return $this->getTable($tablename);
	 } 
	
	protected static function setTable($tablename=""){
	   static::$tablename = ($tablename)?$tablename:"";
	}
    
	public function getTable($tablename='') {
   	  return (!empty($tablename))?$this->setTable($tablename):"";
	}
    /**This Method is use to initiate SQL Query **/
    public function initQuery($tablename){
       $query = new RecordSet($tablename);
       return $query; 
    }
   
	/* Custom Sql Select Statments Starts here */
	
	 /**This method be use to query the database using sql syntax 
	  * in a table and return the values into object array
	  * @method customQuery, $sql::customQuery("select name,email from users where id=:id");
	  * @param  $prep_query  this parameter is for prepared stataments or query statement
	  * @param  public static $placeholder= array(":id"=>"1") for name placeholder=array("1") 
	  * @return $results array : use foreach to go thru this array
	  * */
    public static function query($prep_query){
        $results =  static::getQuery($prep_query,static::$placeholder);
		return $results;
    }
	 /**This function selects all rows in a table and return the values into object array
	 * @method selectAllRecords,
	 * @param  $addclause = null, e.g default value is null. usage "order by desc" or "Id=?"
	 * @param  public static $placeholder= array(":name"=>"ama") for name placeholder or array("ama") 
	 * @return $results array : use foreach to go thru this array
	  * */
	 public static function selectAllRecords($addclause=''){
	 	$placeholder = (!empty(static::$placeholder))?static::$placeholder:array();
	  	$sql = "SELECT * FROM ".static::$tablename." $addclause ";
	  	$results =  static::getQuery($sql,$placeholder);
		return $results;
	  }
	
	/**This function selects one row in a table and return the values into an object array
	 * @method selectRecord,
	 * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder= array(":id"=>"1") for name placeholder or array("1")
	 * */
    public static function selectRecord($whichfield=null){
        $placeholder = (!empty(static::$placeholder))?static::$placeholder:array();
    	$query = "SELECT * FROM ".static::$tablename."";
	 	$query .= ($whichfield!=null?static::where($whichfield):"");
	 	$result = static::getQuery($query,$placeholder);
		return (!empty($result))? array_shift($result):false;
	 }
     /**Get Last Insetered ID **/
	 public function lastInsertedID(){
      $result =  $this->customQuery("SELECT LAST_INSERT_ID() AS Inserted_id");
      return (!empty($result))? array_shift($result):false;
      //return $result;//LAST_INSERT_ID()
     } 
	/** This method joins two tables into one statement
	 * @method selectJoinRecords,
	 * @param $table2 eg the second table to join
	 * @param  array$setfields usage e.g. array('Fieldname1','Fieldname1')
     * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder = array(":id"=>"1") or array("1") 
     * @param  $order ='' e.g default value is empty. usage "ORDER BY Id ASC|DESC"
     * @param  $limit ='' e.g default value is empty. usage "5 or 10 OFFSET 50"
     * @return $results object array: use foreach to go thru this array
    * */
	 public function selectJoinRecords($join_table,array $setfields,$join_on,$whichfield=null,$orderby='',$limit=''){
        $placeholder = (!empty(static::$placeholder))?static::$placeholder:array();
	 	$setfields = is_array($setfields)?$setfields:"";	
	 	$query = "SELECT "; 
	  	$query .= (count($setfields)>=1)?implode(",",$setfields):"*";
	  	$query .=" FROM ".static::$tablename;
	    $query .=" LEFT JOIN ".$join_table." ON ".$join_on;
	  	$query .=" ".($whichfield!=null?static::where($whichfield):"");
	    $limit = !empty($limit)?"LIMIT $limit":"";
	    $query .=" $orderby  $limit";
	   $results = static::getQuery($query,$placeholder);
      return $results ;	
	 }
	 
	/** Finds specified in object array
    * @method specificFields,
    * @param  array$setfields usage e.g. array('Fieldname1','Fieldname1')
    * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	* @param  public static $placeholder = array(":id"=>"1") for name placeholder or array("1") 
    * @param  $order ='' e.g default value is empty. usage "ORDER BY Id ASC|DESC"
    * @param  $limit ='' e.g default value is empty. usage "5 or 10 OFFSET 50"
    * @return $results array : use foreach to go thru this array
    * */
	public static function specificRecords(array $setfields,$whichfield = null,$orderby='',$limit=null){
	  $query = "SELECT "; 
	  $query .= (count($setfields)>=1)?implode(",",$setfields):"*";
	  $query .=" FROM ".static::$tablename;
	  $query .=" ".($whichfield!=null?static::where($whichfield):"");
	  $limit = !empty($limit)?"LIMIT $limit":"";
	  $query .=" $orderby  $limit";
	  $results = static::getQuery($query,static::$placeholder);
      return $results ;
	}
	
	 /**Finds and checks specified field and instantiate row into object array
	 * @method fieldExists,
	 * @param  $tblfield , e.g default value is null. usage "Fisrtname=:firstname or Fisrtname=?"
	 * @param  public static $placeholder = array(":id"=>"1") for name placeholder or array("1") 
	 * @return $foundfield stdClass array
	 * */
	public static function fieldExists($tablefield){
        $split_var = preg_split("/=/",$tablefield);
        $placeholder = (!empty(static::$placeholder))?static::$placeholder:array();
		$fieldname  = $split_var[0]; $fieldvalue = $split_var[1];
        $sql ="SELECT $fieldname FROM ".static::$tablename;
        $sql .= static::where("$fieldname =$fieldvalue")." LIMIT 1";
        
        $results = static::getQuery($sql,$placeholder);
        return (!empty($results))? array_shift($results):false;
     } 
	
	 /**This function count rows in a table
	 * @method countRows,
	 * @param $field eg count(id)
	 * @param  $whichfield = null, e.g default value is null. usage "Id=:id" or "Id=?"
	 * @param  public static $placeholder= array(":name"=>"ama") for name placeholder or array("ama") 
	 * */
	public static function rowCount($field=null,$whichfield=null){
		$field = ($field<>null)?$field:"*";
	 	$query = "SELECT COUNT($field) as Totalcount FROM ".static::$tablename;
		$query .=" ".($whichfield!=null?static::where($whichfield):"");
		$results = static::getQuery($query,static::$placeholder);
		return (!empty($results))? array_shift($results):false; 
	}
	
	/**sql statement phaser method usage:insert data into tables;
	 * @method insertData,
	 * @param public static $tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) or 
	 * @param public static $tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) ;
	 * @param public static $placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"])  
	 * */
	private function insertData(){
        $placeholder = (!empty(static::$placeholder))?static::$placeholder:array();
		$query = "INSERT INTO ".static::$tablename;
		$query .= "(";
		$query .= implode(", ",array_keys(static::$tablefields));
		$query .= ") VALUES (";
		  foreach (array_values(static::$tablefields) as $value) {
	     	 $fieldvalues[] = $value;
		  }
		$query .= implode(",",$fieldvalues);
		$query .= ")";
        static::sendQuery($query,$placeholder);
    }
	
	/**sql statement phaser method, update data in tables;
	 * @method updateData, 
	 * @param public static $tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) or 
	 * @param public static $tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) ;
	 * @param public static $placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"])  
	 * */
   private function updateData(){
		 $query = "UPDATE ".static::$tablename; 
		 $query .= " SET ";
		 	foreach (static::$tablefields as $fieldname => $fieldvalue) {
		 	 $setfields[] = "{$fieldname} = "."{$fieldvalue}"."";
		 	}
		 $query .= implode(", ",$setfields);
		 $query .= " ".static::where(static::$saveid)." LIMIT 1";
         //var_dump($query);
         static::sendQuery($query,static::$placeholder);
   }
    
   /**sql statement phaser method usage: replaces data in tables;
     * @method replaceData,
     * @param public static $tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) or 
	 * @param public static $tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) ;
	 * @param $placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"])  
	 * */
   public static function replaceData(){
   		$query = "REPLACE INTO ".static::$tablename;
		$query .= "(";
		$query .= implode(", ",array_keys(static::$tablefields));
		$query .= ") VALUES ( '";
		  foreach (array_values(static::$tablefields) as $value) {
	     	 $fieldvalues[] = $value;
		  }
		$query .= implode("','",$fieldvalues);
		$query .= "' )";
        static::sendQuery($query,static::$placeholder); 
   }
   
   /**sql statement phaser methodthis deletes row in a table;
    * @method delete, 
	 * */
   public function delete(){
 	 $query = "DELETE FROM ".static::$tablename." ".static::where(static::$saveid);
	 $query .= " LIMIT 1";
	 return static::getQuery($query,static::$placeholder);
   }
   /* Custom Sql Select Statments Ends here */
	
	// MySQL where clause
	protected static function where($clause){
     	return " WHERE {$clause}";
     }
	//sends data to database
	protected static function sendQuery($sql,$fields=array()){
		    $fields = (isset($fields)&&!empty($fields))?$fields:array();
		    if(static::$dbcon->prepQuery($sql)){
               static::$dbcon->exeQuery($fields);
			 }else{
			   return static::$dbcon->comfirmQuery($sql);
			 }
		return static::$dbcon->closeConnection();
	}
    //get datasets from database
    protected static function getQuery($sql,$fields=array()){
		    $fields = (isset($fields)&&!empty($fields))?$fields:array();
		    if(static::$dbcon->prepQuery($sql)){
               static::$dbcon->exeQuery($fields);
             //return static::$dbcon->exeQuery($fields)?static::instantiate():null;
			 return (static::$dbcon->sqlNumRows()!=0)? static::instantiate():null;
			 }else{
			   return static::$dbcon->comfirmQuery($sql);
			 }
		return static::$dbcon->closeConnection();
	}
	 /** sql statement phaser method this initialize rows into objects arrays
          * @method instantiate,
	 * */
 	 private static function instantiate(){
 	 	     $objects = array();
		     $classname = get_called_class();
	         while($row = static::$dbcon->fetchObject("$classname")){
			    $objects[] = $row;
	     	 }
	   return $objects;
	}

    /**it saves data into database,
  * @method save,
 * */
    public function save(){
      return (isset(static::$id) == null? 
              $this->insertData():$this->updateData());
  }

}

/**This Class is use to initiate SQL Records **/
class RecordSet extends SQLRecords{
     public static $id;
     public static $placeholder=array();
	 public static $tablefields=array();
/* Connect to Database class */
     public function __construct($tablename='') {
      parent::__construct($tablename);
      static::$id = parent::$id;
      //static::$placeholder = parent::$placeholder;
      //static::$tablefields = parent::$tablefields;
	 } 
};



   /* 
   How to use SQLRecord Class
   $rec = new SQLRecords;  
   //$sql::$placeholder=array(":id=>1");
   echo"<br><br>";
   $rec->getTable("users");
   $query = $rec::selectAllRecords(); 
     //$rec::customQuery("select name,email from users where id=:id");
    foreach($query as $row){
    	echo "".$row->name." ----- ".$row->email."<br>";
    }
    echo $rec::$dbcon->sqlNumRows()."<br><br>";
	$rec::$dbcon->closeConnection();
      //var_dump($query);
     //$rec::$tablefields = array('fieldname1'=>'?','fieldname2'=>'?',...) or ;
     //$rec::$tablefields = array('fieldname1'=>':value1','fieldname2'=>':value2',...) ;
	 //$rec::$placeholder = array(":value1"=>$_POST["value1"]) for name placeholder or array($_POST["value1"],$_POST["value2"])
 */
?>