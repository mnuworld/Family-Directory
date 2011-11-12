<?php
$host = $_SERVER["MYSQL_HOST"];
$username = $_SERVER["MYSQL_USERNAME"];
$password = $_SERVER["MYSQL_PASSWORD"];
$database = $_SERVER["MYSQL_DATABASE"];

mysql_connect($host,$username,$password);
mysql_select_db($database);
?>