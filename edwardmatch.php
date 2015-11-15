<?php

include 'func/db.php';
include 'func/generator.php';

$courselist = array("CSCI-170","CSCI-104L");

$queue=array();

$class1gen=$courselist[0];
$class1gen=generator($courselist[0],$conn);

$class2gen=$courselist[1];
$class2gen=generator($courselist[1],$conn);

//print_r($class1gen);
//c1 and c2 are arrays of arrays - [(l,s,q),(l,s,q)...]
foreach($class1gen as $c1){
	foreach($class2gen as $c2){
		//no conflicts yet
		$conflict=false;

		//check individual class sections with each other
		foreach($c1 as $c1op){
			foreach($c2 as $c2op){
				//check if c1option and c2option conflict
				$c1s=$c1op->time_start;
				$c1e=$c1op->time_end;
				$c2s=$c2op->time_start;
				$c2e=$c2op->time_end;
				$conflict=checkConflict($c1s,$c1s,$c2s,$c2e);
				if($conflict){
					break;
				}
			}//end inner cl-op loop
			if($conflict){
				break;
			}
		}//end outer cl-op loop
		//NO CONFLICTS
		//MERGE so it looks like [(l,s,q,l,s,q)]
		if(!$conflict){
			$arr=array();
			
			foreach($c1 as $a){$arr[]=$a;}
			foreach($c2 as $b){$arr[]=$b;}

			$queue[]=$arr;

		}
	}//end inner class loop
}//end outer class loop


print_r($queue);

?>