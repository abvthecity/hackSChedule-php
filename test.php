<?php

include 'func/db.php';

$query = "SELECT * FROM section";
$result = $conn->query($query);

function update($id,$up,$conn){
	$query = "UPDATE section SET days='$up' WHERE id=$id";

	echo "HI";
	
	$result = $conn->query($query);

}

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()) {
		//if($row["days"] == "A") update($row["id"],"",$conn);
	}
}

?>