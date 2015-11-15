<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "SClubs";

// Create connection
mysql_connect($server, $username, $password);
@mysql_select_db($database);

?>