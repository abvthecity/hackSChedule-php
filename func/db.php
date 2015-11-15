<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "johncena";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connection_error) die("DUMP");

?>