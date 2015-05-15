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
 *	File: Hearbeat cronjob.
 *		Must be executed every minute.
 *		Sends an update to the cluster.
**/

//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');

//Set execution dir to the current file one
chdir(realpath(dirname(__FILE__)));

/** Including the Master configuration file. **/
include("MASTER.php");


//set POST variables
$url = "http://$CluserHostName/comm/heartbeathandler.php";
$fields = array(
						'MAC' => urlencode($MAC),
						'IP' => urlencode($IP),
						'HOSTNAME' => urlencode($HOSTNAME),
						'status' => urlencode("1")
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
//print_r( json_decode($result, true));

?>