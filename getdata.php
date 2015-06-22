<?php
	include('db_handler.php');
	//execute
	//$dbHandler = new DBHandler();
	global $db_handler;
	$db_handler->get_user();	   //get_user checked
	$db_handler->get_message();	//get_message checked
	echo"<script type=\"text/javascript\">\n";
	echo"	createPin(window.User_Array,window.Message_Array);\n";
	//echo" initialize();\n";
	//echo"	testMessageArray();\n";
	//echo"	showPinOnMap();\n";
	echo"	createList(window.Pin_Array);\n";
	echo"</script>\n";
?>
