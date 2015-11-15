<?php

set_time_limit(0);

header('Content-Type: application/json');

include 'func/db.php';
include 'func/generator.php';
include 'coursematch.php';

$courselist = array("CSCI-170","CSCI-104L","MATH-225");

$coursematch = coursematch($courselist, $conn);

$colorarr = array("EF5350","AB47BC","5C6BC0","039BE5","009688","689F38","EF6C00","795548","EC407A");

$queue = array();

foreach($coursematch as $key => $possibility){
	foreach($possibility[0] as $s){
		$queue[$key][]=array(
			"id" => $s->id,
			"class_id" => $s->class_id,
			"title" => $s->title,
			"type" => $s->type,
			"section" => $s->section,
			"time_start" => $s->time_start,
			"time_end" => $s->time_end,
			"days" => $s->days,
			"instructor" => $s->instructor,
			"room" => $s->room,
			"units" => $s->units,
			"top" => ($s->time_start == "") ? 0 : (convertToMin($s->time_start)/60-6)*50,
			"size" => (strtotime($s->time_end)-strtotime($s->time_start))/60/60*50,
			"color" => $colorarr[array_search($s->class_id,$courselist)]
		);
	}
	//if($key > 10) break;
}

echo json_encode($queue);

$conn->close();

?>