<?php
	include('db_handler.php');
	//execute
	//$dbHandler = new DBHandler();
	global $db_handler;
	$db_handler->get_user();	   //get_user checked
	$db_handler->first_get_message();	//get_message checked
	echo"<script type=\"text/javascript\">\n";
	echo"	createPin(window.User_Array,window.Message_Array);\n";
	//echo"	copyParentPin_Array();\n";
	//$db_handler->InitWait();
	echo"</script>\n";
?>
