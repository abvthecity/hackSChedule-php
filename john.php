<?php

include 'func/db.php';
include 'func/generator.php';
include 'coursematch.php';

$courselist = array("CSCI-170","CSCI-104L","MATH-225");

$coursematch = coursematch($courselist, $conn);

$colorarr = array("EF5350","AB47BC","5C6BC0","039BE5","009688","689F38","EF6C00","795548","EC407A");
$golden = $coursematch[3][0];
//print_r($golden);

$queue = array("U" => array(), "M" => array(), "T" => array(), "W" => array(), "H" => array(), "F" => array(), "S" => array(), "A" => array());
$listq = array();

foreach($golden as $key){
	foreach(str_split($key->days) as $jack){
		if($jack == "" || $key->time_start == "") $jack = "A";
		$top = ($key->time_start == "") ? 0 : (convertToMin($key->time_start)/60-6)*50;
		$size = (strtotime($key->time_end)-strtotime($key->time_start))/60/60*50;
		$html = "<li class=\"event\" data-class=\"".$key->class_id."\" style=\"top:".$top."px;background-color:#".$colorarr[array_search($key->class_id,$courselist)].";min-height:".$size."px\"><span><i>".$key->type."</i><br><b>".$key->class_id."</b></span><p>".(($key->instructor !="")?$key->instructor."<br>":"").(($key->room !="")?$key->room."<br>":"").$key->section.(($key->time_start != "")?"<br>".$key->time_start."-".$key->time_end:"")."</p></li>";
		$queue[$jack][] = $html;
	}
	$listq[$key->class_id][]="<tr><td>".$key->section."</td><td>".$key->type."</td><td>".$key->units."</td><td>".$key->time_start."-".$key->time_end."</td><td>".$key->days."</td><td>".$key->instructor."</td><td>".$key->room."</td></tr>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="wwe.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>WWE SMACKDOWN</title>
</head>
<body>

<main>

<section id="coursecontainer">
	<h4>Classes</h4>
	<ul id="dynamicInput" class="typeahead">
		<li><input type="text" id="classInput" name="class-1" placeholder="Enter a class"/><p>Hit enter to add a new class.</p></li>
	</ul>
	<input class="btn" type="button" value="Next" onclick="$('#filtercontainer').show();">
</section>

<section id="filtercontainer">
	<h4>Optimize Schedule</h4>
	<div>
		<b>I don't want classes before: </b>
		<p>
			<input type="radio" id="9am" name="class" value="9"/><label for="9am">9am</label>
			<input type="radio" id="10am" name="class" value="10"/><label for="10am">10am</label>
			<input type="radio" id="11am" name="class" value="11"/><label for="11am">11am</label>
			<input type="radio" id="12am" name="class" value="12"/><label for="12am">12am</label>
			<input type="radio" id="idc" name="class" value="idc" checked><label for="idc">I don't care</label>
		</p>
	</div>
	<div>
		<b>I don't want classes on these days: </b>
		<p>
			<input type="checkbox" id="mon" name="filter" value="mon"/><label for="mon">Monday</label>
			<input type="checkbox" id="tue" name="filter" value="tue"/><label for="tue">Tuesday</label>
			<input type="checkbox" id="wed" name="filter" value="wed"/><label for="wed">Wednesday</label>
			<input type="checkbox" id="thu" name="filter" value="thu"/><label for="thu">Thursday</label>
			<input type="checkbox" id="fri" name="filter" value="fri"/><label for="fri">Friday</label>
		</p>
	</div>
	<div>
		<b>Optimize my schedule for RateMyProfessor scores</b>
		<p>
			<input type="radio" id="rmp-t" name="rmp" value="true" checked><label for="rmp-t">Yes</label>
			<input type="radio" id="rmp-f" name="rmp" value="false"><label for="rmp-f">No</label>
		</p>
		<div><input class="btn" type="submit" value="Generate"></div>
	</div>
</section>

<section id="calendar">
	<div id="calwrap">
		<ul id="days">
			<li>Sunday</li>
			<li><b>Monday</b></li>
			<li><b>Tuesday</b></li>
			<li><b>Wednesday</b></li>
			<li><b>Thursday</b></li>
			<li><b>Friday</b></li>
			<li>Saturday</li>
			<li>TBA</li>
		</ul>
		<ul id="time">
			<li>6AM</li>
			<li>7AM</li>
			<li>8AM</li>
			<li>9AM</li>
			<li>10AM</li>
			<li>11AM</li>
			<li>12PM</li>
			<li>1PM</li>
			<li>2PM</li>
			<li>3PM</li>
			<li>4PM</li>
			<li>5PM</li>
			<li>6PM</li>
			<li>7PM</li>
			<li>8PM</li>
			<li>9PM</li>
		</ul>
		<ul id="U" class="cal-col">
<? foreach($queue["U"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="M" class="cal-col">
<? foreach($queue["M"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="T" class="cal-col">
<? foreach($queue["T"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="W" class="cal-col">
<? foreach($queue["W"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="H" class="cal-col">
<? foreach($queue["H"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="F" class="cal-col">
<? foreach($queue["F"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="S" class="cal-col">
<? foreach($queue["S"] as $html) { echo $html."\n"; } ?>
			<li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li><li class="dark"></li>
			<li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
		</ul>
		<ul id="A" class="cal-col">
<? foreach($queue["A"] as $html) { echo $html."\n"; } ?>
		</ul>
	</div>
	<div id="coursetable">
		<table id="coursetable">
			<tr><th>Section ID</th><th>Type</th><th>Units</th><th>Hours</th><th>Days</th><th>Instructor</th><th>Room</th></tr>
<?
	foreach($listq as $courseid => $course){
		$result2 = $conn->query("SELECT * FROM classes WHERE course='$courseid'");
		while($row2 = $result2->fetch_assoc()){
			$title = $row2["title"];
		}
		echo "<tr><td colspan=7><b>$courseid</b> $title</td></tr>";
		foreach($course as $indiv){
			echo $indiv;
		}
	}
?>
		</table>
	</div>
</section>


</main>

<script src="/js/handlebars.js"></script>
<script src="/js/jquery.js"></script>
<script src="/js/typeahead.js"></script>
<script>

$(document).ready(function() {

var courses = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tokens'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: '/data.php'
});


$('#dynamicInput input[type=text]').typeahead({highlight:true}, {
  name: 'course-code',
  display: 'course',
  limit: 10,
  source: courses,
  templates: {
    empty: [
      '<div class="empty-message">',
        'unable to find any courses that match the current query',
      '</div>'
    ].join('\n'),
    suggestion: Handlebars.compile('<div><strong>{{course}}</strong><br><i>{{title}}</i></div>')
  }
});


// ADD COURSE

var arrClass = [];
var maxClasses = 9;

function addCourse(){
	//var thisparent = $("#class-li-"+counter);
	var ggval = document.getElementById("classInput").value.toUpperCase();
	if(arrClass.indexOf(ggval) > -1 || arrClass.length >= maxClasses){
	}
	else {
		$.get('/course.php',{classid:ggval},function(result){
			if (result[0] == 0){ }
			else {
				arrClass.push(ggval);
				document.getElementById("classInput").value = "";
				var newdiv = $(document.createElement('li'));

				var getString = $(document.createElement('div'));
				$.get(
					'/course.php',
					{classid:ggval},
					function(result){
						for(var key in result){
							if(result[key] != 0){
								var textNode = getString.append("<input type=\"checkbox\" id=\""+ggval+"-"+key+"\" checked><label for=\""+ggval+"-"+key+"\">"+result[key]+"</label>");
							}
						}
					},
					'json'
				);

				newdiv.html("<b>"+ggval+"</b> <a>[x]</a> <input type=\"hidden\" name=\"class\" value=\""+ggval+"\">");
				newdiv.append(getString);

				$("#dynamicInput> li").last().before(newdiv);
				if(arrClass.length >= maxClasses){
					$("#dynamicInput > li").last().hide();
				}
			}
		},'json');
	}
}


$("ul#dynamicInput > li").on("keyup","input",function(e){
	if(e.which==13){
		e.preventDefault();
		addCourse();
	}
});

$("ul#dynamicInput").on("click","a",function(){
	var crs = $(this).parent("li").children("b").html();
	$(this).closest("li").replaceWith("");
	arrClass.splice(arrClass.indexOf(crs),1);
	if(arrClass.length < maxClasses){
		$("#dynamicInput > li").last().show();
	}
});


$("ul.cal-col > li.event").mouseenter(function(e){
	data_class = $(this).attr("data-class");
	$("ul.cal-col > li.event[data-class=" + data_class + "]").addClass("hover");
}).mouseleave(function(e){
	data_class = $(this).attr("data-class");
	$("ul.cal-col > li.event[data-class=" + data_class + "]").removeClass("hover superhover");
}).mousedown(function(e){
	$(this).toggleClass("superhover");
});


});

</script>

</body>

</html>