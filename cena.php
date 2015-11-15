<?php

include 'func/db.php';

//array of all Subject Names 
$subject = array("AHIS", "ALI", "AMST", "ANTH", "ARAB", "ASTR", "BISC", "CHEM", "CLAS", "COLT", "CORE", "CSLC", "EALC", "EASC", "ECON", "ENGL", "ENST", "EXSC", "FREN", "FSEM", "GEOG", "GEOL", "GERM", "SWMS", "GR", "HEBR", "HIST", "HBIO", "INDS", "IR", "IRAN", "ITAL", "JS", "LAT", "LBST", "LING", "MATH", "MDA", "MDES", "MPW", "NEUR", "NSCI", "OS", "PHED", "PHIL", "PHYS", "POIR", "PORT", "POSC", "PSYC", "REL", "RNR", "SLL", "SOCI", "SPAN", "SSCI", "SSEM", "USC", "WRIT", "ACCT", "ARCH", "ACAD", "BAEP", "BUAD", "BUCO", "DSO", "FBE", "GSBA", "LIM", "MKT", "MOR", "CMPP", "CNTV", "CTAN", "CTCS", "CTIN", "CTPR", "CTWR", "IML", "ASCJ", "CMGT", "COMM", "DSM", "JOUR", "PUBD", "DANC", "DENT", "CBY", "DHYG", "DIAG", "DPBL", "GDEN", "OFPM", "PEDO", "PERI", "THTR", "EDCO", "EDHP", "EDPT", "EDUC", "AME", "ASTE", "BME", "CHE", "CE", "CSCI", "EE", "ENE", "ENGR", "ISE", "INF", "ITP", "MASC", "PTE", "SAE", "ART", "CRIT", "DES", "FA", "FACE", "FACS", "FADN", "FADW", "FAIN", "FAPH", "FAPT", "FAPR", "FASC", "PAS", "WCT", "GCT", "SCIN", "SCIS", "ARLT", "SI", "ARTS", "HINQ", "SANA", "LIFE", "PSC", "QREA", "GPG", "GPH", "GESM", "GERO", "GRSC", "LAW", "ACMD", "ANST", "BIOC", "CBG", "DSR", "HP", "INTD", "MED", "MEDB", "MEDS", "MICB", "MPHY", "MSS", "NIIN", "PATH", "PHBI", "PM", "PCPA", "SCRM", "ARTL", "MTEC", "MSCR", "MUCM", "MUCO", "MUCD", "MUED", "MUEN", "MUHL", "MUIN", "MUJZ", "MPEM", "MPGU", "MPKS", "MPPM", "MPST", "MPVA", "MPWP", "MUSC", "OT", "HCDA", "MPTX", "PHRD", "PMEP", "PSCI", "BKN", "PT", "AEST", "HMGT", "MS", "NAUT", "NSC", "PPD", "PPDE", "PLUS", "RED", "SOWK");

$ge = array("ARTS","HINQ","SANA","LIFE","PSC","QREA","GPG","GPH","GESM");


function formatDate($string){
	if($string == "Mon, Wed") return "MW";
	else if($string == "Tuesday") return "T";
	else if($string == "Thursday") return "H";
	else if($string == "Friday") return "F";
	else if($string == "Tue, Thu") return "TH";
	else if($string == "Monday") return "M";
	else if($string == "Wednesday") return "W";
	else if($string == "MWF") return "MWF";
	else if($string == "") return "";
	else if($string == "MTuWTh") return "MTWH";
	else if($string == "MTuWThF") return "MTWHF";
	else if($string == "Wed, Fri") return "WF";
	else if($string == "Mon, Fri") return "MF";
	else if($string == "TuWThF") return "TWHF";
	else if($string == "MTuThF") return "MTHF";
	else if($string == "Saturday") return "S";
	else if($string == "MThFSU") return "MTFSU";
	else if($string == "TuThF") return "THF";
	else if($string == "MWTh") return "MWH";
	else if($string == "Tue, Fri") return "TF";
	else if($string == "Mon, Thu") return "MH";
	else if($string == "Thu, Fri") return "HF";
	else if($string == "Sat, U") return "SU";
	else if($string == "FSU") return "FSU";
	else if($string == "Thu, Sat") return "HS";
}

function insertClasses($course,$title,$units,$ge,$conn){
	$title = addslashes($title);
	$query = "INSERT INTO classes (id, course, title, units, ge) VALUES ('', '$course', '$title', '$units', '$ge')";
	$query2 = "SELECT * FROM classes WHERE course='$course'";
	$result2 = $conn->query($query2);
	if(!empty($_POST["con"])){
		if($result2->num_rows==0){
			echo "INSERTED CLASSES.<br>";
			if($conn->query($query) === TRUE) {} else echo $query."<br>";
		} else {
			echo "hey";
			$query3 = "UPDATE classes SET ge='$ge' WHERE course='$course'";
			while($row = $result2->fetch_assoc()) {
				if($row['ge'] == '' || $ge == "GESM")
					$result = $conn->query($query3);
			}
		}
	}
}

function insertSection($class_id,$theme,$type,$section,$time_start,$time_end,$days,$instructor,$room,$conn){
	$instructor = addslashes($instructor);
	$theme = addslashes($theme);
	if(!(strpos($time_start,"am") || strpos($time_start,"pm"))){
		if(strpos($time_end,"pm")) $time_start=$time_start."pm";
		else if(strpos($time_end, "am")) $time_start=$time_start."am";
	}
	$query = "INSERT INTO section (id, class_id, theme, type, section, time_start, time_end, days, instructor, room) VALUES ('', '$class_id', '$theme', '$type', '$section', '$time_start', '$time_end', '".formatDate($days)."', '$instructor', '$room')";
	$query2 = "SELECT * FROM section WHERE section='$section' AND class_id='$class_id'";
	$result2 = $conn->query($query2);
	if(!empty($_POST["con"]) && $result2->num_rows==0){
		echo "INSERTED SECTIONS.<br>";
		if($conn->query($query) === TRUE) {} else echo $query."<br>";
	}
}
//download all csv files
//break each file down and strip quotes 
//2d array that contains each line (which is a class)
//each class line contains info about class
function parseContent($ex, $subject, $ge, $conn){
	// DUMP ALL SHIT INTO $ARRAY
	$fileName= "http://localhost/csv/".strtolower($ex)."-20161.csv";

	$ged = (in_array($ex,$ge)) ? $ex : "";

	$dump = trim(file_get_contents($fileName));
	$dump = str_replace("TBA","",$dump);
	//$dump = str_replace(":","\:",$dump);
	$lines = explode(PHP_EOL, $dump);
	array_shift($lines);
	$array = array();
	foreach ($lines as $line) {
	    $array[] = str_getcsv($line);
	}

//assigns class information from array into variables
//then insert variables into MySQL database
	$i = 0;
	$course = "";
	while($i<sizeof($array)){
		if($array[$i][0] != ""){
			$course = $array[$i][0];
			insertClasses($course,$array[$i][1],$array[$i][3],$ged,$conn);
		}
		$time_start = (isset($array[$i][7]) && $array[$i][7] != "") ? explode("-",$array[$i][7])[0] : "";
		$time_end = (isset($array[$i][7]) && $array[$i][7] != "") ? explode("-",$array[$i][7])[1] : "";
		// class_id, theme, type, section, time_start, time_end, days, instructor, room
		if(isset($array[$i][4])){
			if($array[$i][0] != "") insertSection($course,"",$array[$i][4],$array[$i][5],$time_start,$time_end,$array[$i][8],$array[$i][12],$array[$i][13],$conn);
			else insertSection($course,$array[$i][1],$array[$i][4],$array[$i][5],$time_start,$time_end,$array[$i][8],$array[$i][12],$array[$i][13],$conn);
		}
		$i++;
	}

}

for($n=0;$n<sizeof($subject);$n++){
	parseContent($subject[$n], $subject, $ge, $conn);
}

$conn->close();


?>

<form action="cena.php" method="post">
<input type="hidden" name="con" value="something">
<input type="submit" value="POPULATE SQL">
</form>
