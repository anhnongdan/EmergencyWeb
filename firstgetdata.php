<?php
	require_once 'db_handler.php';
	require_once 'readPlace.php';
    $db_handler = new DBHandler();

	readPlaceFile();
	
    //$db_handler are initiated in db_handler.php
    //global $db_handler;

	$db_handler->get_user();	   //get_user checked
	$db_handler->first_get_message();	//get_message checked

    //echo "In firstgetdata.php";
    //var_dump($db_handler);

	echo"<script type=\"text/javascript\">\n";
	//echo"	alert(\"table[0]: \" +document.getElementsByTagName('table')[2]);\n";
    //echo"	alert(\"User_array: \" + window.User_Array.length);\n";

	echo"	createPin(window.User_Array,window.Message_Array);\n";
	$db_handler->InitWait();
	$db_handler->createProcessList();


	echo"	createList(window.Pin_Array);\n";
	echo"	createAreaDropList();\n"; 
	echo"</script>\n";
    $db_handler->DB->disconnect();