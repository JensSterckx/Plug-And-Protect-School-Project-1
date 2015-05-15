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
 *	File: Sensor handler
**/


//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');


$response['error'] = 0;
$response['reason'] = "";


/** Check if it's a POST, if not drop **/  
if (!(isset($_POST['MAC']) && (isset($_POST['light']) || isset($_POST['temperature']))))
{
	$response['error'] = true;
	$response['type'] = "badrequest";
	$response['reason'] = "Bad requests (POST not set)";
	echo json_encode($response);
	die();
}

$APIScript = true;

/** Include the MASTER file to establish MySQL Conn **/
include("MASTER.php");

$SensorTable = "tbl_sensordata";

$MAC = $_POST['MAC'];
/*
$TYPE = $_POST['light'];
$VALUE = $_POST['temperature'];
*/

$SenseDatas = $_POST;
unset($SenseDatas['MAC']); //remove the MAC address from the list

foreach($SenseDatas as $key => $SenseData)
{
	$Query = "	INSERT INTO $SensorTable (MAC, sensor_type, value) 
				VALUES ('$MAC', '$key', '$SenseData');";

	if ($MySQL->query($Query) === TRUE) 
	{
		//Creating record was successfull
		$response['error'] = 0;
		$response['type'] = "inserted";
		$response['reason'] = "Record $key : $SenseData is added";
		echo json_encode($response);
	} 
	else 
	{
		//echo "Error: " . $Query . "<br>" . $MySQL->error;
		//There were errors in creating the record.
		$response['error'] = 1;
		$response['type'] = "inserterror";
		$response['reason'] = "Errors in inserting record $key : $SenseData: " .$MySQL->error;
		echo json_encode($response);
	}
}


/*if ($IP != $OriginIP)
{
	//The client sends another IP than it's own?
	$response['error'] = 1;
	$response['type'] = "badip";
	$response['reason'] = "Bad IP address (Origin is not same as values)";
	echo json_encode($response);
	$MySQL->close();
	die();
}*/


/** TEST VALUES **/
/*
$MAC = "b8:27:eb:d5:dc:f0";
$IP = "192.168.100.123";
$STATUS = "1";
$HOSTNAME = "test";
*/

	//Inserting it into our DB



/** Closing the MySQL connection **/
$MySQL->close();
?>
