<?php

include 'func/db.php';
/*
$query = "SELECT * FROM section";
$result = $conn->query($query);
$arr = array();
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if(!in_array($row["class_id"],$arr)) $arr[] = $row["class_id"];
	}
}*/

$arr = array("ASTR-100Lxg","BISC-220Lg","BISC-330L","CHEM-105aLg","CHEM-105bL","CHEM-115bL","CHEM-322aL","CHEM-322bL","CHEM-325bL","PHYS-135aLg","PHYS-135bL","PHYS-151Lg","PHYS-152L","PHYS-153L","PHYS-153L","PHYS-161Lg","PHYS-162L","PHYS-163L","CSCI-103L","CSCI-104L","CSCI-109","CSCI-170","CSCI-201","CSCI-270","CSCI-457","CSCI-570","CSCI-585","EE-109L","EE-209","EE-355x","EE-577a");

foreach($arr as &$val){
	$query = "SELECT * FROM section WHERE class_id='$val'";
	$result = $conn->query($query);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()) {
			echo $val . " > " . $row["type"] . "<br>";
		}
	}
	echo "<br>";
}

/*
$days=array();
$query = "SELECT * FROM section";
$result = $conn->query($query);
if($result->num_rows > 0){
	while($row=$result->fetch_assoc()){
		if(!in_array($row["days"],$days)) {$days[]=$row["days"];echo $row["days"]."<br>";}
	}
}
*/

//print_r($arr);

?>