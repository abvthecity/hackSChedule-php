<?php

//include 'db.php';
//include 'functions.php';

function convertToMin($time){
	$hours = explode(":",$time)[0];
	$minutes = explode(":",$time)[1];
	if(strpos($minutes,"am")){
		$minutes = str_replace("am","",$minutes);
	}
	else if(strpos($minutes,"pm")){
		$minutes = str_replace("pm","",$minutes);
		if($hours != 12) $hours += 12;
	}
	return ($hours*60) + $minutes;
}

// ASSUME t12 > t11, t22 > t21
function checkConflict($t1_start,$t1_end,$t2_start,$t2_end){
	if(convertToMin($t1_start) < convertToMin($t2_start))
		if(convertToMin($t1_end) > convertToMin($t2_start))
			return true;
		else
			return false;
	else
		checkConflict($t2_start,$t2_end,$t1_start,$t1_end);
}

class Section {
	public $id;
	public $class_id;
	public $type;
	public $section;
	public $time_start;
	public $time_end;
	public $days;
	public $instructor;
	public $room;

	public function conflictBool($s){
		return checkConflict($time_start, $time_end, $s->time_start, $s->time_end);
	}
}

function generator($class,$conn){

//$class = "AMST-200gm";

$query = "SELECT * FROM section WHERE class_id='$class'";
$result = $conn->query($query);

$type = array();
$id = array();
$den = array();

$sect = array();

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()) {
		$sect[] = new Section;
		$obj = $sect[count($sect)-1];
		$obj->id = $row["id"];
		$obj->class_id = $row["class_id"];
		$obj->type = $row["type"];
		$obj->section = $row["section"];
		$obj->time_start = $row["time_start"];
		$obj->time_end = $row["time_end"];
		$obj->days = $row["days"];
		$obj->instructor = $row["instructor"];
		$obj->room = $row["room"];
		$type[] = $obj->type;
		$id[] = $obj->id;
		$den[] = ($obj->room == "DEN@Viterbi") ? true : false;
	}
}

$count = count($sect);

/*
	Lecture
	Discussion
	Lab
	Quiz
	Lecture-Lab
	Lecture-Discussion
*/


$master=array();

// CHECK IF EXIST
$hasLecture = in_array("Lecture",$type);
$hasDiscussion = in_array("Discussion",$type);
$hasQuiz = in_array("Quiz",$type);
$hasLab = in_array("Lab",$type);
$hasLectureLab = in_array("Lecture-Lab",$type);
$hasLectureDiscussion = in_array("Lecture-Discussion",$type);
$hasDen = in_array(true,$den);

if($hasDen){
	// GET THE DEN OUT OF THE WAY
	$denarr=array();
	for($i=0;$i<$count;$i++){
		if($den[$i]){
			$denarr[]=$sect[$i];
			unset($type[$i]);
			unset($sect[$i]);
		}
	}
	$master[] = $denarr;
}

// FILL UP ARRAY WITH LECTURE-LAB or LECTURE-DISCUSSION
if($hasLectureLab || $hasLectureDiscussion) {
	for($i=0;$i<$count;$i++){
		if($type[$i]=="Lecture-Lab") $master[] = array($sect[$i]);
		else if($type[$i]=="Lecture-Discussion") $master[] = array($sect[$i]);
	}
}

// FILL WITH LECTURE WITHOUT DISCUSSION OR LAB
if($hasLecture && !($hasDiscussion || $hasLab)){
	for($i=0;$i<$count;$i++)
		if($type[$i]=="Lecture") $master[]=array($sect[$i]);
}

// FIND IF ITERABLE
$iterable = false;
if(($hasLecture && $hasDiscussion) || ($hasLecture && $hasLab)){
	if(array_count_values($type)["Lecture"] <= 1) $iterable = false;
	else {
		for($i=0;$i<$count;$i++){
			if($type[$i] == "Lecture"){
				$iterable = ($type[$i+1] != "Lecture");
				break;
			}
		}
	}
}

if($iterable){
	$iter = array_keys($type, "Lecture");
	foreach($iter as $key => $val){
		$max = (count($iter)-1 <= $key) ? count($type) : $iter[$key+1];
		$lookfor = $type[$val+1];
		for($i=$val+1;$i<$max;$i++){
			if($type[$i]=="Discussion" || $type[$i]=="Lab"){
				$temparr = array($sect[$val]);
				if($type[$i] == $lookfor) $temparr[] = $sect[$i];
				if($lookfor == "Discussion" && $type[count($type)-1] == "Lab") $temparr[] = $sect[count($type)-1];
				if($type[$max-1] == "Quiz") $temparr[] = $sect[$max-1];
				$master[] = $temparr;
			}
		}
	}

}
else {
	for($i=0;$i<$count;$i++){
		if($type[$i] == "Lecture"){
			for($j=0;$j<$count;$j++){
				if($type[$j] == "Discussion" || $type[$j] == "Lab"){
					if($hasDiscussion && $hasLab){
						for($k=$j+1;$k<$count;$k++)
							if(($type[$j]=="Discussion" && $type[$k]=="Lab") || ($type[$j]=="Lab" && $type[$k]=="Discussion"))
								if($hasQuiz){
									for($n=0;$n<$count;$n++) 
										if($type[$n] == "Quiz") 
											$master[] = array($sect[$i],$sect[$j],$sect[$k],$sect[$n]);
								}
								else $master[] = array($sect[$i],$sect[$j],$sect[$k]);
					}
					else {
						if($hasQuiz){
							for($n=0;$n<$count;$n++) 
								if($type[$n] == "Quiz") 
									$master[] = array($sect[$i],$sect[$j],$sect[$n]);
						}
						else $master[] = array($sect[$i],$sect[$j]);
					}
				}
			}
		}
	}
}

if(!$hasLecture && ($hasDiscussion || $hasLab)){
	for($i=0;$i<$count;$i++)
		$master[]=array($sect[$i]);
}

return $master;

}




?>