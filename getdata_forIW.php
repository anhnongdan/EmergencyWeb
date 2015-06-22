<?php
	//include('db_handler.php');
	//global $db_handler;
	//$db_handler->get_user();	   //get_user checked
	//$db_handler->get_message();	//get_message checked
	echo"<script type=\"text/javascript\">\n";
	echo"	window.User_Array=opener.window.User_Array;\n";
	echo" 	window.Message_Array=opener.window.Message_Array;\n";
	echo"	createPin(window.User_Array,window.Message_Array);\n";
	echo"</script>\n";
?>
