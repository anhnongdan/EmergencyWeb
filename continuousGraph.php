<?php
	include("db_handler.php");
	global $db_handler;
	
	$db_handler->continousMessageCount();
    $db_handler->DB->disconnect();
