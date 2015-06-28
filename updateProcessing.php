<?php
include('db_handler.php');

global $db_handler;
$stm = "INSERT INTO ProUser 
(userID) VALUES('$_GET[userID]') ";
$db_handler->DB->_query($stm);
$db_handler->DB->disconnect();

?>
