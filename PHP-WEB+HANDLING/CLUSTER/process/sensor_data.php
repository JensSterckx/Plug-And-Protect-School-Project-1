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
 *	File: Sensor retriever from database
 *		and parse it to use in js front-end
**/
 
include("../init.php");
include("../includes/MEMBER.php");

if(isset($_GET['MAC']))
{
	if(isset($_GET['MODE']) && $_GET['MODE'] == "live")
	{
		//Serving as a proxy (internet -> intranet) to retrieve live data.
		$info = generateModuleInformation($MySQL, "ARRAY", $_GET['MAC']);
		$ip = $info["IP"];
		$sensorlocation = "/comm/sensorlive.php";
		
		$ret = @file_get_contents("http://" . $ip . $sensorlocation);
		if($ret === FALSE)
		{
			echo "{\"error\": \"No connection\"}";
		}
		else
		{
			echo $ret; //Send data back to the JS frontend
		}
		
	}
	else
	{
		if(isset($_GET['MAXS']) && is_numeric($_GET['MAXS']) && isset($_GET['MINS']) && is_numeric($_GET['MINS']))
		{
			echo generateSensorInformation($MySQL, $_GET['MAC'], $_GET['MINS'], $_GET['MAXS']);
		}
		else
		{
			echo generateSensorInformation($MySQL, $_GET['MAC'], "ALL", "ALL");
		}
	}
}
else
{
	//echo generateModulesList($MySQL, "JSON");
}

?>
