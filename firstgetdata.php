<?php
	include('db_handler.php');
	include('readPlace.php');
	//execute
	//$dbHandler = new DBHandler();
	readPlaceFile();
	global $db_handler;
	$db_handler->get_user();	   //get_user checked
	$db_handler->first_get_message();	//get_message checked
	echo"<script type=\"text/javascript\">\n";
	//echo"	alert(\"table[0]: \" +document.getElementsByTagName('table')[2]);\n";
	echo"	createPin(window.User_Array,window.Message_Array);\n";
	$db_handler->InitWait();
	$db_handler->createProcessList();
	//echo" initialize();\n";
	//echo"	testPriorityArray();\n";
	//echo"	showPinOnMap();\n";
	echo"	createList(window.Pin_Array);\n";
	echo"	createAreaDropList();\n"; 
	//echo"	createProcessList();\n";
	echo"</script>\n";
?>
