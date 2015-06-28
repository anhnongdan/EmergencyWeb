<?php
	require_once ('db_handler.php');
    $db_handler = new DBHandler();

	$db_handler->get_user();	   //get_user checked
	$db_handler->first_get_message();	//get_message checked
	echo"<script type=\"text/javascript\">\n";
	echo"	createPin(window.User_Array,window.Message_Array);\n";
	//echo"	copyParentPin_Array();\n";
	//$db_handler->InitWait();
	echo"</script>\n";
    $db_handler->DB->disconnect();
?>
