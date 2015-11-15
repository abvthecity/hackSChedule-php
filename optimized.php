<?php

set_time_limit(0);

header('Content-Type: application/json');

include 'func/db.php';
include 'func/generator.php';
include 'coursematch.php';

$num = $_GET["time"];
//$num = 0;
$darr = $_GET["days"];
//$darr = "";
/*$num = 8;
$darr = "";*/
//$courselist = array("CSCI-103L","CSCI-109");
$courselist = $_GET["courselist"];

$coursematch = coursematch($courselist,$num,$darr, $conn);

$colorarr = array("EF5350","AB47BC","5C6BC0","039BE5","009688","689F38","EF6C00","795548","EC407A");

$queue = array();

foreach($coursematch as $key => $possibility){
	foreach($possibility as $h => $s){
		foreach($s as $k){
			if(count($courselist)>1) $z=$key;
			else if(count($courselist) == 1)$z=$h;
			$queue[$z][]=array(
				"id" => $k->id,
				"class_id" => $k->class_id,
				"title" => $k->title,
				"type" => $k->type,
				"section" => $k->section,
				"time_start" => $k->time_start,
				"time_end" => $k->time_end,
				"days" => $k->days,
				"instructor" => $k->instructor,
				"room" => $k->room,
				"units" => $k->units,
				"top" => ($k->time_start == "") ? 0 : (convertToMin($k->time_start)/60-6)*50,
				"size" => (strtotime($k->time_end)-strtotime($k->time_start))/60/60*50,
				"color" => $colorarr[array_search($k->class_id,$courselist)]
			);
		}
	}
	//if($key > 10) break;
}

echo json_encode($queue);

$conn->close();

?>