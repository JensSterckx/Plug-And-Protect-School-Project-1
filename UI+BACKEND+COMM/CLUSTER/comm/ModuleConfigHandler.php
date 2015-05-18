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
 *	File: Module Config Handler.
 *		This script receive a MAC address from a module,
 *		and will reply with a JSON string containing all personal configuration files with their content. 
**/


//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');


$response['error'] = 0;
$response['reason'] = "";


/** Check if it's a POST, if not drop **/  


if (!(isset($_POST['MAC'])))
{
	$response['error'] = 1;
	$response['type'] = "badrequest";
	$response['reason'] = "Bad requests (POST not set)";
	echo json_encode($response);
	die();
}

//Fixes some IP problems in MASTER.php
$APIScript = true;

/** Include the MASTER file to establish MySQL Conn **/
include("MASTER.php");
include("../init.php");


$ConfigTable = "tbl_mconfig";

$MAC = $_POST['MAC'];
$ModuleInfo = generateModuleInformation($MySQL, "ARRAY", $MAC);


/** TEST VALUES **/


$Query = "	SELECT type, file, content 
			FROM $ConfigTable 
			WHERE MAC='$MAC' OR MAC='ff:ff:ff:ff:ff:ff';";

$result = $MySQL->query($Query);

if ($result->num_rows > 0) 
{
	$configs = array();
	$indexnr = 0;
    while($row = $result->fetch_assoc()) 
	{
		//Replacings for MAC and Hostname in motion.conf
		if($row['type'] == "CAMERA" && $row['file'] == "/etc/motion/motion.conf")
		{
			$row["content"] = str_replace("{MAC}", $MAC, $row["content"]);
			$row["content"] = str_replace("{MODULE_HOSTNAME}", $ModuleInfo['HOSTNAME'], $row["content"]);
		}
		$configs[$indexnr] = $row;
		$indexnr++;
    }
	echo json_encode($configs);
	//print_r($configs);
}
else 
{
	//No configuration settings found
	$response['error'] = 1;
	$response['type'] = "noconfig";
	$response['reason'] = "This module is not configured yet!";
	echo json_encode($response);
}

/** Closing the MySQL connection **/
$MySQL->close();
?>