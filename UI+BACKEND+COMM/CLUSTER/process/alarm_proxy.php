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
 *	File: This file will make sure that the POST request is forwarded to the corresponding module
**/
 
include("../init.php");
include("../includes/MEMBER.php");

if(isset($_GET['MAC']))
{
	if(isset($_GET['MODE']))
	{
		//Serving as a proxy (internet -> intranet) to retrieve live data.
		$info = generateModuleInformation($MySQL, "ARRAY", $_GET['MAC']);
		$ip = $info["IP"];
		
		//set POST variables
		$url = "http://$ip/comm/alarm.php";
		$fields = array(
								'MODE' => urlencode($_GET['MODE'])
						);

		//url-ify the data for the POST
		$fields_string = "";
		foreach($fields as $key=>$value) 
		{ 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		//Response of the script is stored in $result 
		echo $result;
		
	}
	else
	{
		echo "error";
	}
}
else
{
	echo "error";
}
?>
