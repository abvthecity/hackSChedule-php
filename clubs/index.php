<!doctype html>
<html>
<head>
<title>SCLUBS</title>
<link rel="stylesheet" type="text/css" href="/css/styles.css"/>
<link rel="stylesheet" type="text/css" href="/css/mobile.css"/>
</head>
<body>

<main>
	<section id="landing" class="clear">
		<div class="header block">
			<h2>List your interests, find&nbsp;your&nbsp;clubs.</h2>
			<p>Say goodbye to crowded involvement fairs, Trojans. Choose up to three interests.</p>
		</div>
		<div class="bottom block">
			<input type="text">
			<input type="text">
			<input type="text">
			<p>Suggestions: blahblahblah</p>
		</div>
	</section>
	<section id="cards">
		<div class="block bottom">
<ul class="cards"><li></li>
<?php

include('func/db.php');

function getCat($array){ // 1D array of category names
	$newArr = [];
	for($i=0;$i<sizeof($array);$i++){
		$query = "SELECT `id` FROM `cat` WHERE `name` = '".$array[$i]."'";
		$result = mysql_query($query) or die("DEAD");
		$row = mysql_fetch_array($result);
		$newArr[$i] = $row['id'];
	}
	return $newArr; // 1D array of category ID numbers
}

function populate($array){
	$newArr = [];
	for($i=0;$i<sizeof($array);$i++){
		$query = "SELECT `club_id` FROM `tag` WHERE `cat_id` = '".$array[$i]."'";
		$result = mysql_query($query);
		while($row=mysql_fetch_array($result)){
			$c=0;
			foreach($newArr as $key => &$elem)
				if($key == $row['club_id']) $c++;
			if($c>0) $newArr[$row['club_id']][] = $array[$i];
			else $newArr[$row['club_id']] = array($array[$i]);
			sort($newArr[$row['club_id']]);
		}
	}
	return $newArr;
}

function namegen($array){
	$name = "";
	foreach($array as $val){
		$query = "SELECT `name` FROM `cat` WHERE `id` = '".$val."'";
		$result = mysql_query($query);
		$name .= mysql_fetch_array($result)['name'];
		if($val != $array[sizeof($array)-1]) $name .= " + ";
	}
	return $name;
}

function flipsort($array){
	$newArr = [];
	foreach($array as $key => &$val){
		if(!isset($newArr[namegen($val)])) $newArr[namegen($val)] = array($key);
		else $newArr[namegen($val)][] = $key;
	}
	return $newArr;
}

function catGen($id){
	$string = "<p class=\"card-cat\">";
	$query = "SELECT `cat_id` FROM `tag` WHERE `club_id` = '".$id."'";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result)){
		$query2 = "SELECT `name` FROM `cat` WHERE `id` = '".$row['cat_id']."'";
		$result2 = mysql_query($query2);
		$name = mysql_fetch_array($result2)['name'];
		$string .= "<a>".$name."</a>";
	}
	$string .= "</p>";
	return $string;
}

function generate($array){
	$string = "";
	$rarr = [];
	foreach($array as $key => &$val) $rarr[$key] = sizeof($val);
	asort($rarr);

	$i = 0;
	$colorarr = array("F44336","2196F3","8BC34A","FFC107","FF6D00","673AB7","EC407A");

	foreach($rarr as $key => &$val){
		$string .= "<li class=\"stamp\"><h4>".$key."</h4></li>";
		foreach($array[$key] as $count => &$elem){
			//if($count == 12) break;
			$query = "SELECT * FROM `club` WHERE `id` = '".$elem."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			$name = $row['name'];
			$email = $row['email'];
			$string .= "<li><span><figure style=\"color:#FFF;background-color:#".$colorarr[$i]."\"><span><h4>".$name."</h4><figcaption><p><a>".$email."</a></p>".catGen($elem)."</figcaption></span></figure></span></li>\n";
		}
		$i++;
	}
	return $string;
}

$query = ["Engineering","Music","Technology"];
print_r(generate(flipsort(populate(getCat($query)))));

?>
</ul>
		</div>
	</section>
</main>
<!--
<script src="/js/masonry.js"></script>
<script>
var container = document.querySelector('.cards');
var msnry = new Masonry( container, { itemSelector: '.cards > li', columnWidth: '.cards > li', isFitWidth: true } );
container.appendChild( elem );
msnry.appended( elem );
msnry.layout();
</script>
-->

</body>
</html>