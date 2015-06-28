<?php
//require_once(dirname(__FILE__).'/Threadi/Loader.php');
//require_once(dirname(__FILE__).'/Map_handler.js');
require('readPList.php');
	
  abstract class Database_Object
    {
        protected static $DB_Name;
        protected static $DB_Open = null;
        protected static $DB_Conn;

        protected function __construct($database, $hostname, $hostport, $username, $password)
        {
            self::$DB_Name = $database;
            if($hostport!=''){
                try{
				    self::$DB_Conn = new PDO (sprintf('mysql:host=%s;port=%s;dbname=%s', $hostname, $hostport, $database), $username, $password);
                } catch (PDOException $exception) {
                    echo "Connection Error with PDO (host with port): ".$exception->getMessage();
                }
            } else {
				try{
				    self::$DB_Conn = new PDO (sprintf('mysql:host=%s;dbname=%s', $hostname, $database), $username, $password);
                } catch (PDOException $exception) {
                    echo "Connection Error with PDO (host without port): ".$exception->getMessage();
                }
            }
        }

        private function __clone() {}

        public function __destruct()
        {
//            mysql_close(self::$DB_Conn);  <-- commented out due to current shared-link close 'feature'.  If left in, causes a warning that this is not a valid link resource.
            try{
                self::$DB_Conn = null;
            } catch (PDOException $exception) {
                echo "PDO Disconnecting error: ".$exception->getMessage();
            }
        }
    }


   final class DB extends Database_Object
    {
       
       //Mysql database on amazon
//        public static function Open($database = 'depqp2rcu5m', $hostname = 'mysqlsdb.co8hm2var4k9.eu-west-1.rds.amazonaws.com', $hostport = '3306', $username = 'depqp2rcu5m', $password = '11UGxl4cUr3D')
        public static function Open()
        {
            //get the credential to hit the database
            $creds_string = file_get_contents ($_ENV['CRED_FILE'], false);
            if ($creds_string == false) {
                die('FATAL: Could not read credentials file');
            }
            $creds = json_decode($creds_string, true);
            $database   = $creds['MYSQLS']['MYSQLS_DATABASE'];
            $host       = $creds['MYSQLS']['MYSQLS_HOSTNAME'];
            $port       = $creds['MYSQLS']['MYSQLS_PORT'];
            $username   = $creds['MYSQLS']['MYSQLS_USERNAME'];
            $password   = $creds['MYSQLS']['MYSQLS_PASSWORD'];
       
            if (!self::$DB_Open)
            {
                self::$DB_Open = new self($database, $host, $port, $username, $password);
            }
            else
            {
                self::$DB_Open = null;
                self::$DB_Open = new self($database, $host, $port, $username, $password);
            }
            return self::$DB_Open;
        }

        public function _query($sql)
        {
            try{
                $result = self::$DB_Conn->query($sql);
                return $result;
            }catch (PDOException $exp) {
                echo "DB query error!".$exp->getMessage();
            }
        }
       
       public function disconnect(){
         try{
                self::$DB_Conn = null;
            } catch (PDOException $exception) {
                echo "PDO Disconnecting error: ".$exception->getMessage();
            }   
       }
    }

        
     class DBHandler{
		function __construct (){
			$this->DB = DB::Open();
		}
		public $userID_Array = array(); 
		 
		 //syntax checked
		public  function get_user(){
			$stm = "SELECT * FROM User ORDER BY userID ASC";				
			$result = $this->DB->_query($stm);
            
            //var_dump($result);
            
			echo"<script language=\"JavaScript\">\n";
			echo"<!-- hide from older browsers. (get_user) \n";
			echo"   window.User_Array = [];\n";
			foreach($result as $row){	
				echo"var User = new User_details(\"".$row['userID']."\",\"".$row['name']."\",\"".$row['gender']."\",\"".$row['birthday']."\",\"".$row['address']."\",\"".$row['IPaddress']."\",\"".$row['phoneNumber']."\",\"".$row['email']."\",\"".$row['bloodType']."\",\"".$row['specialMedicalCond']."\");\n";
				echo"addUser(User);\n";
				//echo "<p>".$row['userID']."  name:  ".$row['name']."  gender:  ".$row['gender']."  birthday:  ".$row['birthday']."  phone: ".$row['phoneNumber']."  bloodType:  ".$row['bloodType']."</p>\n";
			}
			echo"// -->\n";				
			echo"</script>\n";
		}
		
		public function get_message(){
			$stm = "SELECT * FROM ArrivedMessage WHERE changed = 'true' ORDER BY userID ASC";
			$result = $this->DB->_query($stm);
			echo"<script language=\"JavaScript\">\n";
			echo"<!-- hide from older browsers. (get_message) \n";
			echo"   window.Message_Array = [];\n";
			foreach($result as $row){
				//echo "<p>".$row['messageID']."  ".$row['userID']."  addtoDB:  ".$row['addtodbtime']."  event:  ".$row['event']."  urgency:  ".$row['urgency']."  long: ".$row['longitude']."  lat:  ".$row['latitude']."</p>\n";
				echo"var Message = new Message_details(\"".$row['userID']."\",\"".$row['sentDate']."\",\"".$row['messageType']."\",\"".$row['messageStatus']."\",\"".$row['scope']."\",\"".$row['restriction']."\",\"".$row['address']."\",\"".$row['reference']."\",\"".$row['note']."\",\"".$row['clientMessageID']."\",\"".$row['addtodbtime']."\",\"".$row['language']."\",\"".$row['event']."\",\"".$row['category']."\",\"".$row['urgency']."\",\"".$row['severity']."\",\"".$row['certainty']."\",\"".$row['instruction']."\",\"".$row['areaDesc']."\",\"".$row['longitude']."\",\"".$row['latitude']."\",\"".$row['elevation']."\",\"".$row['IPAddress']."\",\"".$row['changed']."\");\n";
				echo"window.Message_Array.push(Message);\n";
				//if(checkOnArray($this->$userID_Array, $row['userID'])!=-1){
					//$this->$userID_Array[] = $row['userID'];
				//}
			}
			echo"// -->\n";				
			echo"</script>\n";
			$stm = "UPDATE ArrivedMessage SET changed='false'";
			$result = $this->DB->_query($stm);
		} 
		
		public function InitWait(){
			//echo"<script language=\"JavaScript\">\n";
			//echo"<!-- hide from older browsers. \n";
			foreach($this->userID_Array as $user){
				$stm = "SELECT userID, sentDate FROM Message WHERE userID = '$user' ORDER BY sentDate ASC";
				$result = $this->DB->_query($stm);
				$row = $result->fetch(PDO::FETCH_ASSOC);
				echo"	InitWait(\"".$row['userID']."\",\"".$row['sentDate']."\");\n";									
			}
			//echo"// -->\n";				
			//echo"</script>\n";
		}
		
		public function first_get_message(){
			$stm = "SELECT * FROM ArrivedMessage ORDER BY userID ASC";
			$result = $this->DB->_query($stm);
			echo"<script language=\"JavaScript\">\n";
			echo"<!-- hide from older browsers. \n";
			foreach($result as $row){
				//echo "<p>".$row['messageID']."  ".$row['userID']."  addtoDB:  ".$row['addtodbtime']."  event:  ".$row['event']."  urgency:  ".$row['urgency']."  long: ".$row['longitude']."  lat:  ".$row['latitude']."</p>\n";
				echo"var Message = new Message_details(\"".$row['userID']."\",\"".$row['sentDate']."\",\"".$row['messageType']."\",\"".$row['messageStatus']."\",\"".$row['scope']."\",\"".$row['restriction']."\",\"".$row['address']."\",\"".$row['reference']."\",\"".$row['note']."\",\"".$row['clientMessageID']."\",\"".$row['addtodbtime']."\",\"".$row['language']."\",\"".$row['event']."\",\"".$row['category']."\",\"".$row['urgency']."\",\"".$row['severity']."\",\"".$row['certainty']."\",\"".$row['instruction']."\",\"".$row['areaDesc']."\",\"".$row['longitude']."\",\"".$row['latitude']."\",\"".$row['elevation']."\",\"".$row['IPAddress']."\",\"".$row['changed']."\");\n";
				echo"window.Message_Array.push(Message);\n";
				array_push($this->userID_Array, $row['userID']);
			}
			echo"// -->\n";				
			echo"</script>\n";
			$stm = "UPDATE ArrivedMessage SET changed='false'";
			$result = $this->DB->_query($stm);
			//echo"alert(".mysql_error().");\n";
		}
		
		/*
		public function createProcessList(){
			$stm = "SELECT * FROM ProUser";
			$result = $this->DB->_query($stm);
			//echo"<script language=\"JavaScript\">\n";
			//echo"<!-- hide from older browsers. \n";
			while($row = mysql_fetch_assoc($result)){
				echo"	initRP(\"".$row['userID']."\");\n";
			}
		} this function is temporarily deprecated */
		
		
		public function createProcessList(){
			$listelements = readxmlfile();
			foreach($listelements as $ele){
				echo"	initRP(\"".$ele."\");\n";	
			}			
		}
		
		public function firstmessageCount(){
			date_default_timezone_set("Asia/Tokyo");
            $CT = date('Y-m-d H:i:s');
			$now = time($CT);
			$fiveMinutes = $now - 5*60;
			$tenMinutes = $now - 10*60;
			$fifteenMinutes = $now - 15*60;
			$twentyMinutes = $now - 20*60;
			$twentyfiveMinutes = $now - 25*60;
			$thirtyMinutes = $now - 30*60;
			$fortyMinutes = $now - 40*60;
			$thirtyfiveMinutes = $now - 35*60;
			$onemonth = $now - 30*24*60*60;
			
			
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$fiveMinutes)."' AND '".date('Y-m-d H:i:s',$now)."'";
			$result = $this->DB->_query($stm);
			$five = $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$tenMinutes)."' AND '".date('Y-m-d H:i:s',$fiveMinutes)."'";
			$result = $this->DB->_query($stm);
			$ten = $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$fifteenMinutes)."' AND '".date('Y-m-d H:i:s',$tenMinutes)."'";
			$result = $this->DB->_query($stm);
			$fifteen =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$twentyMinutes)."' AND '".date('Y-m-d H:i:s',$fifteenMinutes)."'";
			$result = $this->DB->_query($stm);
			$twenty =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$twentyfiveMinutes)."' AND '".date('Y-m-d H:i:s',$twentyMinutes)."'";
			$result = $this->DB->_query($stm);
			$twentyfive =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$thirtyMinutes)."' AND '".date('Y-m-d H:i:s',$twentyfiveMinutes)."'";
			$result = $this->DB->_query($stm);
			$thirty =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$thirtyfiveMinutes)."' AND '".date('Y-m-d H:i:s',$thirtyMinutes)."'";
			$result = $this->DB->_query($stm);
			$thirtyfive =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$fortyMinutes)."' AND '".date('Y-m-d H:i:s',$thirtyfiveMinutes)."'";
			$result = $this->DB->_query($stm);
			$forty =  $result->rowCount();
			
			echo"<script language=\"JavaScript\">\n";
			echo"<!-- hide from older browsers. \n";
			//echo" alert($five + $ten + $fifteen);\n";
			echo"	firstGraphCreate($five, $ten, $fifteen, $twenty, $twentyfive, $thirty, $thirtyfive, $forty);\n";
			echo"// -->\n";				
			echo"</script>\n";
					
		}
		
		public function continousMessageCount(){
			$CT = date('Y-m-d H:i:s');
			date_default_timezone_set("Asia/Tokyo");
			$now = time($CT);
			$fiveMinutes = $now - 5*60;
			$tenMinutes = $now - 10*60;
			$fifteenMinutes = $now - 15*60;
			$twentyMinutes = $now - 20*60;
			$twentyfiveMinutes = $now - 25*60;
			$thirtyMinutes = $now - 30*60;
			$fortyMinutes = $now - 40*60;
			$thirtyfiveMinutes = $now - 35*60;
			$onemonth = $now - 30*24*60*60;
			
			
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$fiveMinutes)."' AND '".date('Y-m-d H:i:s',$now)."'";
			$result = $this->DB->_query($stm);
			$five =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$tenMinutes)."' AND '".date('Y-m-d H:i:s',$fiveMinutes)."'";
			$result = $this->DB->_query($stm);
			$ten = $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$fifteenMinutes)."' AND '".date('Y-m-d H:i:s',$tenMinutes)."'";
			$result = $this->DB->_query($stm);
			$fifteen =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$twentyMinutes)."' AND '".date('Y-m-d H:i:s',$fifteenMinutes)."'";
			$result = $this->DB->_query($stm);
			$twenty =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$twentyfiveMinutes)."' AND '".date('Y-m-d H:i:s',$twentyMinutes)."'";
			$result = $this->DB->_query($stm);
			$twentyfive =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$thirtyMinutes)."' AND '".date('Y-m-d H:i:s',$twentyfiveMinutes)."'";
			$result = $this->DB->_query($stm);
			$thirty =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$thirtyfiveMinutes)."' AND '".date('Y-m-d H:i:s',$thirtyMinutes)."'";
			$result = $this->DB->_query($stm);
			$thirtyfive =  $result->rowCount();
			$stm = "SELECT * FROM Message WHERE addtodbtime BETWEEN '".date('Y-m-d H:i:s',$fortyMinutes)."' AND '".date('Y-m-d H:i:s',$thirtyfiveMinutes)."'";
			$result = $this->DB->_query($stm);
			$forty =  $result->rowCount();
			
			
			echo"<script language=\"JavaScript\">\n";
			echo"<!-- hide from older browsers. \n";
			echo"	continuousData($five, $ten, $fifteen, $twenty, $twentyfive, $thirty, $thirtyfive, $forty);\n";
			echo"// -->\n";				
			echo"</script>\n";			
		}
		
		
		 /*  deprecated functions.
        public function scanDb(){
			$time = date("m/d/Y H:i:s");
			$stm = "SELECT * FROM TEST1";				
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
		*/
	}
	
	function checkOnArray($Array, $key){
		$size = count($Array);
		for($i=0;$i<$size;$i++){
			if($Array[$i]==$key)
				return $i;
		}
		return -1;
	}