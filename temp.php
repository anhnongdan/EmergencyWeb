<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="Map_handler.js"></script>
	</head>

<?php

	//show();
	$datetime = new DateTime();
	printf($datetime->format('Y-m-d H:i:s'));
	printf(date('Y-m-d h:i:s'));

	   abstract class Database_Object
    {
        protected static $DB_Name;
        protected static $DB_Open;
        protected static $DB_Conn;

        protected function __construct($database, $hostname, $hostport, $username, $password)
        {
            self::$DB_Name = $database;
            if($hostport!='')
				self::$DB_Conn = mysql_connect($hostname . ":" . $hostport, $username, $password);
			 else
				self::$DB_Conn = mysql_connect($hostname, $username, $password);
            if (!self::$DB_Conn) { die('Critical Stop Error: Database Error<br />' . mysql_error()); }
            mysql_select_db(self::$DB_Name, self::$DB_Conn);
        }

        private function __clone() {}

        public function __destruct()
        {
//            mysql_close(self::$DB_Conn);  <-- commented out due to current shared-link close 'feature'.  If left in, causes a warning that this is not a valid link resource.
        }
    }


   final class DB extends Database_Object
    {
        public static function Open($database = 'my_db', $hostname = 'localhost', $hostport = '', $username = 'root', $password = 'Aizu1234')
        {
            if (!self::$DB_Open)
            {
                self::$DB_Open = new self($database, $hostname, $hostport, $username, $password);
            }
            else
            {
                self::$DB_Open = null;
                self::$DB_Open = new self($database, $hostname, $hostport, $username, $password);
            }
            return self::$DB_Open;
        }

        public function _query($sql)
        {
            $query = mysql_query($sql, self::$DB_Conn) OR die(mysql_error());
			return $query;
        }
        public function _disconnect (){
			
		}
    }

        
     class DBHandler{
		function __construct (){
			$this->DB = DB::Open();
		}
		 
        public function scanDb(){
			$time = date("m/d/Y H:i:s");
			$stm = "SELECT * FROM TEST1 WHERE ";				
			$result = $this->DB->_query($stm);
			$this->Apply_Data($result);
		}
		public function Apply_Data($result){
			//javascript array 
			echo"<script language=\"JavaScript\">\n";
			echo"<!-- hide from older browsers. \n";
			echo"var logArray = new Array(); var latArray = new Array(); var i=0;\n";
			while($row = mysql_fetch_assoc($result)){
					
				//handle data from database here
				$CopyLog=$row['Longitude'];
				echo $CopyLog; echo "\n";
				echo"logArray[i]=\"$CopyLog\";\n";
				$CopyLat=$row['Lat']; 
				echo"latArray[i]=\"$CopyLat\";\n";
				echo"i++;\n";
			}
			echo"initialize(latArray,logArray);\n";
			echo"// -->\n";				
			echo"</script>\n";
		}
	}
	
	/*
	$dbhandler = new DBHandler();
	$stm = "SELECT * FROM User";
	$result = $dbhandler->DB->_query($stm);
	while($row=mysql_fetch_assoc($result)){
		echo "User ID: ".$row['userID']."Name: ".$row['name']."Gender: ".$row['gender']."\n";
	}*/
	function show(){
		echo"<script type=\"text/javascript\">\n";
		echo"cmp();\n";
		echo"</script>\n";
	}
	
?>

</html>

