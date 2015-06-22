<?php
include ("mysql.php");
$values["default"] = "default";
$values["IDENTIFIER"] = "";
$values["SENDER"] = "";
$values["SENT"] = "";
$values["MSGSTATUS"] = "";
$values["MSGTYPE"] = "";
$values["DUPLICATE"] = "";
$values["SCOPE"] = "";
$values["RESTRICTION"] = "";
$values["ADDRESS"] = "";
$values["REFERENCE"] = "";
$values["NOTE"] = "";
$values["LANGUAGE"] = "";
$values["CATEGORY"] = "";
$values["EVENT"] = "";
$values["URGENCY"] = "";
$values["SEVERITY"] = "";
$values["CERTAINTY"] = "";
$values["INSTRUCTION"] = "";
$values["SENDERNAME"] = "";
$values["AREADESC"] = "";
$values["LONGITUDE"] = "";
$values["LATITUDE"] = "";
$values["ELEVATION"] = "";
$values["IPADDRESS"] = "";
$index = "";
$gdata = "";
//Function to use at the start of an element
function start($parser,$element_name,$element_attrs)
  {
  global $index;
  $index ="";
  switch($element_name)
    {
    case "IDENTIFIER":
    	echo "<".$element_name.">";
	$index = "IDENTIFIER";
    	break;
    case "SENDER":
    	echo "<".$element_name.">";
	$index = "SENDER";
    	break;
case "SENT":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "MSGTYPE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "MSGSTATUS":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "DUPLICATE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "SCOPE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "RESTRICTION":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "ADDRESS":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "REFERENCE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "NOTE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "LANGUAGE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "CATEGORY":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "EVENT":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "URGENCY":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "SEVERITY":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "CERTAINTY":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "INSTRUCTION":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "SENDERNAME":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "AREADESC":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "LONGITUDE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "LATITUDE":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "ELEVATION":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "IPADDRESS":
    	echo "<".$element_name.">";
	$index = $element_name;
    	break;
case "ALERT":
    	echo "<".$element_name.">";
    	break;
case "INFO":
    	echo "<".$element_name.">";
    	break;
case "AREA":
    	echo "<".$element_name.">";
    	break;
    }
  }

//Function to use at the end of an element
function stop($parser,$element_name)
  {
  global $index;
  $index = "";
  echo "</".$element_name.">\n";
  
  }

function char($parser,$data) 
  {
 
  global $index;
  global $values;
  global $gdata;
  $gdata = $data;
  echo $data;
  if ($index != "") $values[$index] = "$gdata";
  }


function handleXML($xmlData)
{
	//Initialize the XML parser
	$parser=xml_parser_create();
	//Specify element handler
	xml_set_element_handler($parser,"start","stop");
	//Specify data handler
	xml_set_character_data_handler($parser,"char");	
	xml_parse($parser,$xmlData,1);
	insertToDatabase();
}

function insertToDatabase()
{
	global $values;

	//mistook atrr_array and value_array. They should be reverse. 
	
	$userID = $values["SENDER"]; $value_array[0] = "userID";
	$attr_array[0] = $userID;
	$sentDate = $values["SENT"]; $value_array[1] = "sentDate";
	$attr_array[1] = $sentDate;
	$messageType = $values["MSGTYPE"]; $value_array[2] = "messageType";
	$attr_array[2] = $messageType;
	$messageStatus = $values["MSGSTATUS"]; $value_array[3] = "messageStatus";
	$attr_array[3] = $messageStatus;
	$scope = $values["SCOPE"]; $value_array[4] = "scope";
	$attr_array[4] = $scope;
	$restriction = $values["RESTRICTION"]; $value_array[5] = "restriction";
	$attr_array[5] = $restriction;
	$address = $values["ADDRESS"];  $value_array[6] = "address";
	$attr_array[6] = $address;
	$reference = $values["REFERENCE"]; $value_array[7] = "reference";
	$attr_array[7] = $reference;
	$note = $values["NOTE"]; $value_array[8] = "note";
	$attr_array[8] = $note;
	$clientMessageID = $values["IDENTIFIER"]; $value_array[9] = "clientMessageID" ;
	$attr_array[9] = $clientMessageID;
	$addtodbtime = date("Y-m-d H:i:s"); $value_array[10] = "addtodbtime";
	$attr_array[10] = $addtodbtime;
	$ok1 = insertIntoMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime);
	if ($ok1)
	{
		$infoNumber = 1;
		$language = $values["LANGUAGE"]; $value_array[11] = "language";
		$attr_array[11] = $language;
		$event = $values["EVENT"]; $value_array[12] = "event";
		$attr_array[12] = $event;
		$category = $values["CATEGORY"];  $value_array[13] = "category";
		$attr_array[13] = $category;
		$urgency = $values["URGENCY"]; $value_array[14] = "urgency";
		$attr_array[14] = $urgency;
		$severity = $values["SEVERITY"]; $value_array[15] = "severity";
		$attr_array[15] = $severity;
		$certainty = $values["CERTAINTY"]; $value_array[16] = "certainty";
		$attr_array[16] = $certainty;
		$instruction = $values["INSTRUCTION"]; $value_array[17] = "instruction";
		$attr_array[17] = $instruction;
		insertIntoInfo($ok1,$infoNumber,$language,$event, $category,$urgency,$severity,$certainty,$instruction);	


		$infoNumber = 1;
		$areaDesc = $values["AREADESC"];  $value_array[18] = "areaDesc";
		$attr_array[18] = $areaDesc;
		$longitude = $values["LONGITUDE"];  $value_array[19] = "longitude";
		$attr_array[19] = $longitude;
		$latitude = $values["LATITUDE"];  $value_array[20] = "latitude";
		$attr_array[20] = $latitude;
		$elevation = $values["ELEVATION"];  $value_array[21] = "elevation";
		$attr_array[21] = $elevation;
		$IPAddress = $values["IPADDRESS"];  $value_array[22] = "IPAddress";
		$attr_array[22] = $IPAddress;
		insertIntoArea($ok1,$infoNumber,$areaDesc,$longitude,$latitude,$elevation,$IPAddress);
	}
	
		
	/*----------------------------------------------*/	
	/*insert Arrived Message*/	
	if($ok1){
		switch($messageStatus){
			case 'Alert':
				$result = queryArrivedMessage("SELECT userID FROM ArrivedMessage WHERE userID =  '$userID'");
				if($result==null){
					if($longitude==""&&$latitude==""){
						$getll = mysql_query("SELECT longitude, latitude FROM IPAddress WHERE userID = '$userID'");
						if($getll){
							$lla = mysql_fetch_assoc($getll);
							$longitude = $lla[0]['longitude'];
							$latitude = $lla[0]['latitude'];
							insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,'true');
						}	
					}else{
						insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,'true');	
					}				
				}else{
					queryArrivedMessage("DELETE FROM ArrivedMessage WHERE userID = '$userID'");
					insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,'true');
				}
				break;
			case 'Update':
				$result = queryArrivedMessage("SELECT userID FROM ArrivedMessage WHERE userID = '$userID'");
				if($result==null){
					if($longitude==""&&$latitude==""){
						$getll = mysql_query("SELECT longitude, latitude FROM IPAddress WHERE userID='$userID'");
						if($getll){
							$lla = mysql_fetch_assoc($getll);
							$longitude = $lla[0]['longitude'];
							$latitude = $lla[0]['latitude'];
							insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,'true');
						}	
					}else{
						insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,'true');	
					}				
					//insertIntoArrivedMessage($userID,$sentDate,$messageType,$messageStatus,$scope,$restriction,$address,$reference,$note,$clientMessageID,$addtodbtime,  $language,$event, $category,$urgency,$severity,$certainty,$instruction,  $areaDesc,$longitude,$latitude,$elevation,$IPAddress,'true');					
				}else{
					$stm = "UPDATE ArrivedMessage SET ";
					for($i=0;$i<=22;$i++){
						if($attr_array[$i]!=''){
							$stm = $stm."$value_array[$i] = '$attr_array[$i]', ";
						}						
					}
					$stm = $stm."changed = 'true' ";
					$stm = $stm." WHERE userID = '$userID'";					
					if(queryArrivedMessage($stm)){
						echo"ArrivedMessage table query successed\n";
					}else{
						echo"error: ".mysql_error();
					}
					
				}
				break;
			case 'Cancel':
				
				break;
			default:
				echo "Message Status invalid.";
				break;
		}
		
	}	
		
}








  /*
    $ackContent = "";
    $ackContent = $ackContent."<?xml version = \"1.0\" encoding=\"UTF-8\"?>\n";
    $ackContent = $ackContent."<alert xmlns = \"urn:oasis:names:tc:emergency:cap:1.1\">\n";
    $ackContent = $ackContent."<identifier>#01</identifier>\n";
    $ackContent = $ackContent."<sender>SYSTEM</sender>\n";
    $ackContent = $ackContent."<sent>2011-11-24T15:00:00-09:00</sent>\n";
    $ackContent = $ackContent."<msgStatus>Actual</msgStatus>\n";
    $ackContent = $ackContent."<msgType>ACK</msgType>\n";
    $ackContent = $ackContent."<note>THIS IS AN ACK MESSAGE</note>\n";
    $ackContent = $ackContent."<alert>\n";
    handleXML($ackContent);
    print_r($values);
 */

