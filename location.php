<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "johncena";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("DUMP");}
//find all buildings
$result = mysql_query("SELECT id, room FROM section");

function createBuildingArray(){
	$building = array(); 
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    //printf("ID: %s  Room: %s", $row["id"], $row["room"]);
		$building = $row["room"];
	}
	$unique = array();
	for($i=0;$i<sizeof($building);$i++){
		if(!in_array($building[$i],$unique)){
			$unique[]=$building[$i];
		}
	}
	return $unique;
}


//returns approximate time
//takes in a building string (example: "BUAD-305")
function locationTime($building_1, $building_2){
	//extracts acronym from string 
	
}

?>