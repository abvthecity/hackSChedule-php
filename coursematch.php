<?php

include 'func/db.php';
include 'func/generator.php';

$courselist = array("CSCI-170","CSCI-104L");

$masterarr = array();

function addMaster($array, &$master){
	$print_r($array);
	$master[] = $array;
}


$queue=array();
$index = 1;
$count = count($courselist);

$queue[] = generator($courselist[0], $conn);
$iterations=array(1);

while(!empty($queue)){
	echo "<b>".$index."</b><br>";
	if($index < $count){
		$curr = generator($courselist[$index],$conn);
		$iterations[$index]=0;
		for($a=0;$a<$iterations[$index-1];$a++){
			foreach($queue[0] as $con1){
				array_shift($queue);
				foreach($curr as $con2){
					$conflict = false;
					for($i=0;$i<count($con1);$i++){
						for($j=0;$j<count($con2);$j++){
							$onestart = $con1[$i]->time_start;
							$oneend = $con1[$i]->time_end;
							$twostart = $con2[$j]->time_start;
							$twoend = $con2[$j]->time_end;
							echo $onestart." ".$oneend." ".$twostart." ".$twoend."<br>";
							if(checkConflict($onestart,$oneend,$twostart,$twoend)){
								$conflict = true;
								break;
							}
						}
						if($conflict) break;
					}

					if(!$conflict){
						echo "AYAYAY";
						$newArr=array();
						foreach($con1 as $elem1) { $newArr[] = $elem1; }
						foreach($con2 as $elem2) { $newArr[] = $elem2; }
						array_push($queue,$newArr);
						$iterations[$index]++;
					}
				}
			}
		}
	} 
	else {
		for($a=0;$a<$iterations[$index];$a++){
			addMaster($queue[0], $masterarr);
			array_shift($queue);
		}
	}
	$index++;
}
echo $index;

// function coursemerge($courselist, $index, $array, &$masterarr, $conn){
// 	//echo "<b>".$index."</b>";
// 	if($index < count($courselist)){
// 		$curr = generator($courselist[$index],$conn);
// 		$queue = array();
// 		for($a=0;$a<count($array);$a++){
// 			for($b=0;$b<count($curr);$b++){
// 				$con1 = $array[$a];
// 				$con2 = $array[$b];
// 				$conflict = false;

// 				for($i=0;$i<count($con1);$i++){
// 					for($j=0;$j<count($con2);$j++){
// 						//echo $con1[$i]->time_start.$con1[$i]->time_end.$con2[$j]->time_start.$con2[$j]->time_end."<br>";
// 						$onestart = $con1[$i]->time_start;
// 						$oneend = $con1[$i]->time_end;
// 						$twostart = $con2[$j]->time_start;
// 						$twoend = $con2[$j]->time_end;
// 						echo $onestart." ".$oneend." ".$twostart." ".$twoend."<br>";
// 						if(checkConflict($onestart,$oneend,$twostart,$twoend)){
// 							$conflict = true;
// 							break;
// 						}
// 					}
// 					if($conflict) break;
// 				}

// 				if(!$conflict){
// 					$newArr=array();
// 					foreach($con1 as $elem1) { $newArr[] = $elem1; }
// 					foreach($con2 as $elem2) { $newArr[] = $elem2; }
// 					//print_r($newArr);
// 					$queue[] = $newArr;
// 				}
// 			}
// 		}


// 		foreach($queue as $arrm){coursemerge($courselist, $index+1, $arrm, $masterarr, $conn);}
// 	}
// 	else addMaster($array, $masterarr);
// }

// coursemerge($courselist, 1, generator($courselist[0], $conn), $masterarr, $conn);

print_r($masterarr);


/*$next = generator($courselist[$index+1], $conn);
	foreach($array as $com){
		foreach($next as $com2){
			$newArr = array_merge($com, $com2);
			$conflict = false;

			for($i=0;$i<count($newArr)-1;$i++)
				for($j=$i+1;$j<count($newArr);$j++)
					//if($newArr[$i]->conflictBool($newArr[$j])) $conflict = true;

			if(!$conflict){
				if($index < count($courselist))
					coursemerge($courselist, $index+1, $newArr, $masterarr, $conn);
				else addMaster($newArr, $masterarr);
			}
			
		}
	}
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