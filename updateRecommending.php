<?php
include('db_handler.php');

global $db_handler;
$stm = "DELETE FROM ProUser WHERE 
userID='$_GET[userID]' ";
$db_handler->DB->_query($stm);

?>
