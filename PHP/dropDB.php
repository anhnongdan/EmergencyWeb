<?php

function drop_db()
{
	$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
{
	die('Could not connect:'.mysql_error());
}
if (mysql_query("DROP DATABASE my_db",$con))
{
	echo "Drop database successful \n";
}
else
{
	echo "Error dropping database: ".mysql_error();
}	

mysql_close();
}


function list_db()
{
$con = mysql_connect("localhost","thang","tatthang");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$db_list = mysql_list_dbs($con);

while ($db = mysql_fetch_object($db_list))
  {
  echo $db->Database."\n" ;
  }

echo "¥n";
mysql_close($con);
}

list_db();
drop_db();
?>