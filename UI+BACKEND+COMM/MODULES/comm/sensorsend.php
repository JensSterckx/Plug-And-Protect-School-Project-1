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
 *	File: Send sensor data to master
**/

//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');

//Set execution dir to the current file one
chdir(realpath(dirname(__FILE__)));

chdir("bin");
exec("sudo ./sensoring", $SenseData); //dirty sudo (/dev/mem/ for BCM), sensoring gives first temperature, second the light (now)
chdir("..");
print_r($SenseData);

if($SenseData[0] == 0)
{
	//Error, shield not connected/not inited
	echo "error";
	chdir("bin");
	exec("sudo ./init"); //dirty sudo (/dev/mem/ for BCM), sensoring gives first temperature, second the light (now)
	chdir("..");
	die();
}



/** Including the Master configuration file. **/
include("MASTER.php");



//set POST variables
$url = "http://$CluserHostName/comm/sensoring.php";
$fields = array(
						'MAC' => urlencode($MAC),
						'temperature' => urlencode($SenseData[0]),
						'light' => urlencode($SenseData[1])
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
//echo $result;
//print_r( json_decode($result, true));

?>
