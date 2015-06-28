<?php
	require_once ('db_handler.php');
    $db_handler = new DBHandler();
	
	$db_handler->continousMessageCount();
    $db_handler->DB->disconnect();
