<?php

	require_once ('db_handler.php');
    $db_handler = new DBHandler();

	//global $db_handler;

	$db_handler->firstmessageCount();
    $db_handler->DB->disconnect();
