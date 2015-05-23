<?php
/**
 *	Plug & Protect
 *
 *	-> Sterckx Jens
 *	-> Moinil Wesley
 *	-> Dauwe Jelle
 *
 *
 *
 *	File: Motion Snapshot retriever
 *		Will send out an array containing all images/movies for the webpage
**/
 
include("../init.php");
include("../includes/MEMBER.php");

if(isset($_GET["MAX"]))
{
	$MAX = $_GET["MAX"];
}
else
{
	$MAX = 100;
}

if(isset($_GET["MIN"]))
{
	$MIN = $_GET["MIN"];
}
else
{
	$MIN = 50;
}

$i = 1;

if(isset($_GET["MAC"]))
{
	$ImageArray = array();
	$MAC = $_GET["MAC"];

	$File = glob("/var/www/images/motion/$MAC/*.{jpg,avi,flv}", GLOB_NOSORT | GLOB_BRACE);
	//usort($File, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
	sort($File);
	$File = array_reverse($File);
	foreach ($File as $filename) {
		$i++;
		
		//Interval
		if($i <= $MIN)
		{
			//Continue if we don't need this;
			continue;
		}
	
		array_push($ImageArray, str_replace("/var/www/", "", $filename));
		
		//interval
		if($i > $MAX)
		{
			break;
		}
	}
	
	if(empty($ImageArray))
	{
		$ImageArray[0] = "images/motion/none.jpg";
	}
	
	echo json_encode($ImageArray);
}
?>