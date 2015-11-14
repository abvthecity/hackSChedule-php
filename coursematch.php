<?php

include 'func/db.php';
include 'func/generator.php';
include 'func/functions.php';

$courselist = array("CSCI-170","CSCI-104L","MATH-226");

$masterarr = array();

function addMaster($array, &$master){
	$master[] = $array;
}


function coursemerge($courselist, $index, $array, &$masterarr, $conn){
	$next = generator($courselist[$index+1], $conn);
	foreach($array as $com){
		foreach($next as $com2){
			$newArr = array_merge($com, $com2);
			$conflict = false;

			for($i=0;$i<count($newArr)-1;$i++)
				for($j=$i+1;$j<count($newArr);$j++)
					//if($newArr[$i]->conflictBool($newArr[$j])) $conflict = true;

			if(!$conflict){
				if($index < count($courselist)){
					coursemerge($courselist, $index+1, $newArr, $masterarr, $conn);
				}
				else addMaster($newArr, $masterarr);
			}
		}
	}
}

coursemerge($courselist, 0, generator($courselist[0], $conn), $masterarr, $conn);

//print_r($masterarr);


/*
coursemerge(array(),$courselist[0],0);

foreach($courselist as $i => $course){
	$catch1 = generator($course);
	for($j=$i+1;$j<count($courselist);$j++){
		$catch2 = generator($courselist[$j]);
		foreach($catch1 as $group1){
			foreach($catch2 as $group2){
				$masterarr[] = array_merge($group1,$group2);
			}
		}
	}
}*/



//print_r($masterarr);


?>