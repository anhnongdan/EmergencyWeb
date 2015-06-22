<?php
function list_db()
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$db_list = mysql_list_dbs($con);

while ($db = mysql_fetch_object($db_list))
  {
  echo $db->Database."\n" ;
  }

echo "¥n";
mysql_close($con);
}
/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
function create_db($db_name)
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
if (mysql_query("CREATE DATABASE $db_name",$con))
{
	echo "Database created\n";
}
else
{
	echo "Error creating database: ".mysql_error();
}

mysql_close();
}
/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
function drop_db()
{
	$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
if (mysql_query("DROP DATABASE my_db",$con))
{
	echo "Drop database successful\n";
}
else
{
	echo "Error dropping database: ".mysql_error();
}	

mysql_close();
}
/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/


function create_table()
{
	$con = mysql_connect("localhost","thang","tatthang");
	mysql_select_db("my_db",$con);
/*____________________________________________________________________*/
	$sql = "CREATE TABLE User
		(
		userID  char(8) NOT NULL ,
		PRIMARY KEY(userID),
		name varchar(20),
		gender ENUM('M','F'),
		birthday date,
		address varchar(100),
		IPaddress varchar(20),
		phoneNumber char(12),
		email varchar(50),
		bloodType char(2),
		specialMedicalCond text
		)";

if (mysql_query($sql,$con))
{
	echo "User table created\n";
}
else
{
	echo "can not create User table\n".mysql_error()."\n";
}
/*____________________________________________________________________*/

$sql = "CREATE TABLE Message 
		(
		messageID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(messageID),
		userID char(8),
		FOREIGN KEY (userID) REFERENCES User(userID),
		sentDate datetime,
		messageType ENUM('Actual','Exercise','System','Test','Draft'),
		messageStatus ENUM('Alert','Update','Cancel','Ack','Error'),
		scope ENUM('Public','Private'),
		restriction text,
		address text,
		reference text,
		note text,
		clientMessageID varchar(10),
		solved int Default '0',
		addtodbtime datetime
		)";

if (mysql_query($sql,$con))
{
	echo "Message table created\n";
}
else
{
	echo "can not create Message table\n".mysql_error()."\n";
}

/*____________________________________________________________________*/

$sql = "CREATE TABLE IPAddress 
		(
		IPAddress varchar(20) NOT NULL,
		PRIMARY KEY(IPAddress),
		userID CHAR(8),
		FOREIGN KEY (userID) REFERENCES User(userID),
		longitude double(17,14),
		latitude double(17,14),
		elevation varchar(20)
		)";

if (mysql_query($sql,$con))
{
	echo "IPAddress table created\n";
}
else
{
	echo "can not create IPAddress table\n".mysql_error()."\n";
}

/*____________________________________________________________________*/

$sql = "CREATE TABLE Info
		(
		messageID int  NOT NULL,
		infoNumber int NOT NULL,
		PRIMARY KEY(messageID,infoNumber),
		FOREIGN KEY (messageID) REFERENCES Message(messageID),
		language char(2),
		category ENUM('Rescue','Fire','Health','Earthquake','Safety'),
		event text,
		urgency ENUM('Immediate','Expected','Future','Past','Unknown'),
		severity ENUM('Extreme','Severe','Moderate','Minor','Unknown'),
		certainty ENUM('Observed','Likely','Possible','Unlikely','Unknown'),
		instruction text
		)";

if (mysql_query($sql,$con))
{
	echo "Info table created\n";
}
else
{
	echo "can not create Info table\n".mysql_error()."\n";
}

/*____________________________________________________________________*/

$sql = "CREATE TABLE Area
		(
		messageID int  NOT NULL,
		infoNumber int NOT NULL,
		PRIMARY KEY(messageID,infoNumber),
		areaDesc text,
		longitude double(17,14),
		latitude  double(17,14),
		elevation double(17,14),
		IPAddress varchar(20)
		)";

if (mysql_query($sql,$con))
{
	echo "Area table created\n";
}
else
{
	echo "can not create Area table\n".mysql_error()."\n";
}

/*____________________________________________________________________*/
}


function createMessageTable (){
	$con = mysql_connect("localhost","thang","tatthang");
	mysql_select_db("my_db",$con);
	
	
	
$sql = "CREATE TABLE ArrivedMessage
		(
		userID char(8),
		PRIMARY KEY (userID),
		FOREIGN KEY (userID) REFERENCES User(userID),
		sentDate datetime,
		messageType ENUM('Actual','Exercise','System','Test','Draft'),
		messageStatus ENUM('Alert','Update','Cancel','Ack','Error'),
		scope ENUM('Public','Private'),
		restriction text,
		address text,
		reference text,
		note text,
		clientMessageID varchar(10),
		solved int Default '0',
		addtodbtime datetime, 
		
		language char(3),
		category ENUM('Rescue','Fire','Health','Earthquake','Safety'),
		event text,
		urgency ENUM('Immediate','Expected','Future','Past','Unknown'),
		severity ENUM('Extreme','Severe','Moderate','Minor','Unknown'),
		certainty ENUM('Observed','Likely','Possible','Unlikely','Unknown'),
		instruction text,
		
		areaDesc text,
		longitude double(17,14),
		latitude  double(17,14),
		elevation double(17,14),
		IPAddress varchar(20),
		
		changed ENUM('true','false')			
		)";  // 12 + 7 + 5 + 1 = 25

if (mysql_query($sql,$con))
{
	echo "ArrivedMessage table created\n";
}
else
{
	echo "can not create ArrivedMessage table\n".mysql_error()."\n";
}
	
}

function createProUserTable (){
	$con = mysql_connect("localhost","thang","tatthang");
	mysql_select_db("my_db",$con);
	
	
	
$sql = "CREATE TABLE ProUser
		(
		userID char(8) PRIMARY KEY
		)";
		
if (mysql_query($sql,$con))
{
	echo "ProUser table created\n";
}
else
{
	echo "can not create ProUser table\n".mysql_error()."\n";
}
	
}		


//passing parameter omitted: solved, 
function insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,$changed){
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);
$sql = "INSERT INTO ArrivedMessage (userID,sentDate,messageType,messageStatus,scope,restriction,address,reference,note,clientMessageID,addtodbtime,  language,event, category,urgency,severity,certainty,instruction,  areaDesc,longitude,latitude,elevation,IPAddress, changed)
	VALUES
		( '$userID','$sentDate','$messageType','$messageStatus','$scope','$restriction','$address','$reference','$note','$clientMessageID','$addtodbtime',  '$language','$event', '$category','$urgency','$severity','$certainty','$instruction',  '$areaDesc','$longitude','$latitude','$elevation','$IPAddress','$changed')";
if (mysql_query($sql,$con))
{
	echo "ArrivedMessage table : 1 record added\n";
	mysql_close();
	return 1;
}
else
{
	echo "ArrivedMessage table : Error : ".mysql_error();
	mysql_close();
	return 0;
}

mysql_close();	
}


function queryArrivedMessage($stm){
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);

$result = mysql_query($stm);
return $result;
}





function insertIntoUser($userID,$name,$gender,$birthday,$address,$IPAddress,$phoneNumber,$email,$bloodType,$smc)
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);
$sql = "INSERT INTO User (userID,name, gender, birthday, address, IPAddress, phoneNumber, email, bloodType, specialMedicalCond)
	VALUES
		('$userID','$name','$gender','$birthday','$address','$IPAddress','$phoneNumber','$email','$bloodType','$smc')";
if (mysql_query($sql,$con))
{
	echo "User table : 1 record added\n";
	mysql_close();
	return 1;
}
else
{
	echo "User table : Error : ".mysql_error();
	mysql_close();
	return 0;
}

mysql_close();
}

/*____________________________________________________________________*/

function insertIntoMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime)
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);

$result = mysql_query("SELECT * FROM User
WHERE userID='$userID'");
$row = mysql_fetch_array($result);
if ($row == null) insertIntoUser($userID,'','','','','','','','','');

$sql = "INSERT INTO Message (userID, sentDate, messageType, messageStatus, scope, restriction, address, reference, note,clientMessageID,addtodbtime)
	VALUES
		('$userID','$sentDate','$messageType','$messageStatus','$scope','$restriction','$address','$reference','$note','$clientMessageID','$addtodbtime')";
if (mysql_query($sql,$con))
{
	echo "Message table: 1 record added\n";
	$id = mysql_insert_id($con);
	echo "id = ".$id."\n";
	mysql_close();	
	return $id;	
}
else
{
	echo "Message table : Error : ".mysql_error()."\n";
	mysql_close();
	return 0;
}

mysql_close();
}

/*____________________________________________________________________*/

function insertIntoInfo($messageID,$infoNumber,$language,$event, $category,$urgency,$severity,$certainty,$instruction)
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);
$sql = "INSERT INTO Info (messageID,infoNumber, language, event, category, urgency, severity, certainty, instruction)
	VALUES
		('$messageID','$infoNumber','$language','$event','$category','$urgency','$severity','$certainty','$instruction')";
if (mysql_query($sql,$con))
{
	echo "Info table: 1 record added \n";
	mysql_close();
	return 1;
}
else
{
	echo "Info table : Error : ".mysql_error();
	echo "\n";
	mysql_close();
	return 0;
}

mysql_close();
}

/*____________________________________________________________________*/

function insertIntoArea($messageID,$infoNumber,$areaDesc,$longitude,$latitude,$elevation,$IPAddress)
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);
$sql = "INSERT INTO Area (messageID,infoNumber,areaDesc, longitude, latitude, elevation, IPAddress)
	VALUES
		('$messageID','$infoNumber','$areaDesc','$longitude','$latitude','$elevation','$IPAddress')";
if (mysql_query($sql,$con))
{
	echo "ARea table : 1 record added\n";
	mysql_close();
	return 1;
}
else
{
	echo "Area table : Error : ".mysql_error()."\n";
	mysql_close();
	return 0;
}

mysql_close();
}


/*____________________________________________________________________*/

function selectSomething()
{
	$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
mysql_select_db("my_db",$con);

$result = mysql_query("SELECT * FROM Message");
while ($row = mysql_fetch_array($result))
{
	print_r($row);
	print "\n";
}
}


// Function execute area, Choose the commands below to do what you want
list_db();
//drop_db();
//create_db("my_db");
//create_table();
//createMessageTable();
//insertIntoUser('AIZU0020','NT Dung','M','1958-01-01','aizu daigaku somei ryo','127.0.0.1','090123456785','bachvutrong@gmail.com','AB','unknown');
//insertIntoUser('AIZU0001','Nguyen Minh Triet','M','1940-01-01','aizu daigaku somei ryo','127.0.0.1','090123456785','bachvutrong@gmail.com','B','unknown');
//insertIntoUser('AIZU0014','Biladen','M','1955-01-01','aizu daigaku somei ryo','127.0.0.1','090123456785','bachvutrong@gmail.com','O','leg broken');
//insertIntoUser('AIZU0015','Biladen2','M','1955-01-01','aizu daigaku somei ryo','127.0.0.1','090123456785','bachvutrong@gmail.com','O','leg broken');
//insertIntoUser('AIZU0016','Biladen3','M','1955-01-01','aizu daigaku somei ryo','127.0.0.1','090123456785','bachvutrong@gmail.com','B','leg broken');
//insertIntoMessage('AIZU0014','2012-01-01 21:04:02','Test','Alert','Public','','','','this is a test message','1','2012-01-01 21:05:02');
//selectSomething();
//insertIntoArrivedMessage('AIZU0020','2012-02-24 21:04:03','Actual','Alert','Public','','Aizu Dai','test 11','die die die','who know?','2012-02-24 21:07:03',  'ENG', 'die', 'Health','Expected','Severe','Likely' , 'leave me alone',  'dark and cold', '143.31', '32.1434', '1000', '127.0.0.1','true');
//insertIntoArrivedMessage('AIZU0015','2012-02-24 21:04:03','Exercise','Alert','Public','','Aizu Dai','test 262','die hard','who know?','2012-02-24 21:07:03',  'ENG', 'die', 'Health','Immediate','Severe','Likely' , 'leave me alone',  'warm', '139.9287411816406', '37.5172831050124', '1000', '127.0.0.1','true');
//createProUserTable();

//insertIntoArrivedMessage('AIZU0016','2012-02-24 21:04:03','Exercise','Alert','Public','','Aizu Dai','test 262','die hard','who know?','2012-02-24 21:07:03',  'ENG', 'die', 'Health','Immediate','Severe','Likely' , 'leave me alone',  'warm', '140.0287411816406', '37.5172831050124', '1000', '127.0.0.1','true');

//insertIntoMessage('AIZU0016','2012-01-01 21:04:02','Test','Alert','Public','','','','this is a test message','1','2012-01-01 21:05:02');