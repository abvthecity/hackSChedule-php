<?php

//include 'func/db.php';
//include 'func/generator.php';

function coursematch($courselist, $conn){

$masterarr = array();

function addMaster($array, &$master){
	//print_r($array);
	$master[] = $array;
}

function check($con1, $con2){
	foreach($con1 as $a){
		foreach($con2 as $b){
			if($a->conflictBool($b) && $a->sameDay($b)) return 0;
		}
	}
	return 1;
}
//check using user preferences
//$time: 9,10,11,12
//days is an array in the style "M T W Th F S"
function checkPref($con1, $con2,$time,$days){
	foreach ($con1 as $a) {
		if($a->time_start>$time){
			return 0;
		}
		//checks required days with days in classes
		foreach(str_split($a->days) as $day){
			foreach(str_split($days) as $day2){
				if($day == $day2)return 0;
			}
		}
	}
	return 1;
}


$queue = array();
$index = 1;
$count = count($courselist);

$queue[] = generator($courselist[0], $conn);
$iterations=array(1);

while(!empty($queue)){
	if($index < $count){
		$curr = generator($courselist[$index],$conn);
		$iterations[$index]=0;
		for($a=0;$a<$iterations[$index-1];$a++){

			foreach($queue[0] as $con1){
				array_shift($queue);
				foreach($curr as $con2){
					if(check($con1,$con2)){
						$newArr=array();
						foreach($con1 as $elem1) { $newArr[] = $elem1; }
						foreach($con2 as $elem2) { $newArr[] = $elem2; }
						$newIteration = array($newArr);
						array_push($queue, $newIteration);
						$iterations[$index]++;
					}
				}
			}
		}
	} 
	else {
		for($a=0;$a<$iterations[$index-1];$a++){
			if(!empty($queue[0])){
				addMaster($queue[0], $masterarr);
				array_shift($queue);
			}
		}
	}
	$index++;
}

//print_r($masterarr);
return $masterarr;

}

//print_r(coursematch(array("CSCI-170","CSCI-104L","MATH-225"),$conn));


?>